<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AddressController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'billing_full_name'   => 'required|string|max:255',
            'billing_mobile'      => 'required|string|max:11',
            'province_id' => 'required',
            'city_id'     => 'required',
            'billing_address'     => 'required|string',
            'billing_postal_code' => 'nullable|string|max:10',
            'billing_extra_description' => 'nullable|string|min:3',
        ]);

        $user = $request->user();

        $address = $user->address;

        if ($address) {
            // بررسی اینکه واقعاً تغییری هست یا نه
            if ($address->only(array_keys($data)) != $data) {
                $address->update($data);
            }
        } else {
            $address = $user->address()->create($data);
        }

        return response()->json([
            'status' => 'success',
            'address_id' => $address->id
        ]);
    }

    public function show(Request $request)
    {
        return response()->json([
            'address' => $request->user()->address,
        ]);
    }
}
