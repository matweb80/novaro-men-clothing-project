<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use RealRashid\SweetAlert\Facades\Alert;

class OrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:show-orders')->only(['index']);
        $this->middleware('can:show-order-details')->only(['show']);
        $this->middleware('can:show-order-payments')->only(['payments']);
        $this->middleware('can:edit-order')->only(['edit', 'update']);
        $this->middleware('can:delete-order')->only(['destroy']);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $orders = Order::query();

        if ($keyword = request('search')) {
            $orders->where('status', request('type'))->where('id', 'LIKE', "%{$keyword}%")->orWhere('tracking_serial', 'LIKE', "%{$keyword}%");
        }
        $type = request('type');
        $orders = $orders->where('status', request('type'))->latest()->paginate(30)->withQueryString();
        return view('admin.orders.all', compact('orders'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        $products = $order->products()->paginate(20);
        return view('admin.orders.details', compact('products', 'order'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Order $order)
    {
        return view('admin.orders.edit', compact('order'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Order $order)
    {
        $data = $request->validate([
            'status' => ['required', Rule::in(['unpaid', 'paid', 'preparation', 'posted', 'received', 'canceled'])],
            'tracking_serial' => 'required'
        ]);

        $order->update($data);

        Alert::success('سفارش موردنظر ویرایش شد');
        return redirect(route('admin.orders.index') . "?type=$order->status");
    }

    public function payments(Order $order)
    {
        $payments = $order->payments()->latest()->paginate(20);
        return view('admin.orders.payments', compact('payments', 'order'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        $order->delete();

        Alert::success('سفارش موردنظر حذف شد');
        return back();
    }
}
