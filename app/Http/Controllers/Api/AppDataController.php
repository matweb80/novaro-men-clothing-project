<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Route;

class AppDataController extends Controller
{
    public function decrypt(Request $request)
    {
        return decrypt($request->data);
    }
}
