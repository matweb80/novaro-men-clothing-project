<?php

namespace App\Http\Controllers;

use Modules\Cart\Helpers\Cart;
use App\Models\Payment;
use Exception;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
use PayPing\PayPingException;
use Shetabit\Multipay\Exceptions\InvalidPaymentException;
use Shetabit\Multipay\Payment as ShetabitPayment;
use Shetabit\Multipay\Invoice;

class PaymentController extends Controller
{
    public function payment(Request $request)
    {
        $data = $request->validate([
            'address_id' => 'required'
        ]);

        $instance = $request->instance ?? 'my-cart';
        $cart = Cart::instance($instance);
        $cartItems = $cart->all();
        if ($cartItems->count()) {
            $price = $cartItems->sum(function ($cart) {
                return $cart['discount_percent'] == 0
                    ? $cart['product']->finalPrice() * $cart['quantity']
                    : $cart['product']->finalPrice() * $cart['quantity'] * (1 - ($cart['discount_percent']));
            });

            $orderItems = $cartItems->mapWithKeys(function ($cart) {
                return [$cart['product']->id => ['quantity' => $cart['quantity']]];
            });

            $order = auth()->user()->orders()->create([
                'address_id' => $data['address_id'],
                'status' => 'unpaid',
                'price' => $price,
                'shipping_price' => 0,
            ]);

            $order->products()->attach($orderItems);

            $paymentConfig = config('payment');

            $payment = new ShetabitPayment($paymentConfig);

            // $payment = new \PayPing\Payment($token);

            // $invoice = (new Invoice)->amount($price);
            $invoice = (new Invoice)->amount(1000);

            $payment->callbackUrl(route('payment.callback'))->via('local')->purchase($invoice, function ($driver, $transactionId) use ($order, $cart, $invoice) {
                $order->payments()->create([
                    'resnumber' => $invoice->getUuid(),
                ]);

                $cart->flush();
            })->pay()->render();
        } else {
            return response()->json([
                'success' => false,
                'error' => 'Cart empty',
            ], 422);
        }
        return response()->json([
            'success' => true,
        ]);
    }

    public function callback(Request $request)
    {
        $paymentConfig = config('payment');

        $payment_obj = new ShetabitPayment($paymentConfig);

        try {
            $payment = Payment::where('resnumber', $request->clientrefid)->firstOrFail();

            // $receipt = $payment_obj->amount($payment->order()->price)->transactionId($transaction_id)->verify();
            $receipt = $payment_obj->amount(1000)->transactionId($request->clientrefid)->verify();

            $payment->update([
                'status' => 1
            ]);

            $order = $payment->order;
            if ($order->status !== 'paid') {
                $order->update([
                    'status' => 'paid'
                ]);
                // increase sales count
                foreach ($order->products as $product) {
                    $quantity = $product->pivot->quantity;

                    $product->increment('sales_count', $quantity);
                }
            }

            Alert::success('پرداخت شما موفق بود');
            return redirect('/products');
        } catch (InvalidPaymentException $exception) {

            // when payment is not verified, it will throw an exception.
            // We can catch the exception to handle invalid payments.
            // getMessage method, returns a suitable message that can be used in user interface.

            Alert::error($exception->getMessage());
            return redirect('/products');
        }
    }
}
