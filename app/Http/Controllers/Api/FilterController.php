<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Attribute;
use App\Models\Product;
use Illuminate\Http\Request;

class FilterController extends Controller
{
    public function index()
    {
        // get all attributes with their values
        $attributes = Attribute::with('values:id,attribute_id,value')->get(['id', 'name']);

        return response()->json($attributes);
    }
}
