<?php

namespace App\Http\Controllers\Api\Profile;

use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Models\Order;
use Illuminate\Http\Request;
use Shetabit\Multipay\Payment as ShetabitPayment;
use Shetabit\Multipay\Invoice;

class OrderController extends Controller
{
    public function payment(Order $order)
    {
        $this->authorize('view', $order);

        $paymentConfig = config('payment');

        $payment = new ShetabitPayment($paymentConfig);

        // $invoice = (new Invoice)->amount($price);
        $invoice = (new Invoice)->amount(1000);

        $payment->callbackUrl(route('payment.callback'))->via('local')->purchase($invoice, function ($driver, $transactionId) use ($order, $invoice) {
            $order->payments()->create([
                'resnumber' => $invoice->getUuid(),
            ]);
        })->pay()->render();
    }
}
