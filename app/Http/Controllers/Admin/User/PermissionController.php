<?php

namespace App\Http\Controllers\Admin\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class PermissionController extends Controller
{
    public function create(User $user)
    {
        if ($user->isStaffUser())
            return view('admin.users.permissions', compact('user'));
        else
            abort(403, 'This action is unauthorized.(User is not staff)');
    }

    public function store(Request $request, User $user)
    {

        $user->roles()->sync($request->roles);
        $user->permissions()->sync($request->permissions);

        Alert::success('مطلب موردنظر شما با موفقیت ثبت شد');

        return redirect(route('admin.users.index'));
    }
}
