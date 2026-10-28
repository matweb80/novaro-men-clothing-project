<?php

namespace App\Http\Controllers\Api\Profile;

use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Models\Order;
use DateTime;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Route;

class UserDataController extends Controller
{
    public function user()
    {
        $user = Auth::user();
        // registered time
        $registrationDateString = $user->created_at;

        // 2. Create DateTime objects for the registration and the current time
        $registrationDate = new DateTime($registrationDateString);
        $today = new DateTime(); // Defaults to "now"

        // 3. Calculate the difference
        $interval = $registrationDate->diff($today);

        // success buy (paid order)
        $orders = $user->orders()->with('products')->paginate(12);
        $paidOrders = $user->orders->where('status', 'paid');

        return response()->json([
            'user_name' => Auth::check() ? Auth::user()->name : null,
            'user_email' => Auth::check() ? Auth::user()->email : null,
            'user_phone_number' => Auth::check() ? Auth::user()->phone_number : null,
            'user_two_factor_type' => Auth::check() ? Auth::user()->two_factor_type : null,
            'registeredDays' => $interval->days,
            'orders' => $orders,
            'paidOrders' => $paidOrders->count(),
            'approvedComments' => $user->approvedComments->count(),
        ]);
    }

    public function showDetails($id)
    {
        $order = auth()->user()->orders()->with('products')->findOrFail($id);
        return response()->json([
            'order' => $order,
            'orderProducts' => $order->products,
            'address' => Address::where('id', $order->address_id)->firstOrFail()
        ]);
    }
}
