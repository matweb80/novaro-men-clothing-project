<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use Illuminate\Http\Request;

class ContactMessageController extends Controller
{
     public function index()
    {
        $messages = ContactMessage::latest()->paginate(10);
        $unreadCount = ContactMessage::where('is_read', false)->count();

        return view('admin.messages.index', compact('messages', 'unreadCount'));
    }

    // 👇 نمایش پیام تکی + تغییر وضعیت به خوانده‌شده
    public function show($id)
    {
        $message = ContactMessage::findOrFail($id);
        $message->update(['is_read' => true]);

        return view('admin.messages.show', compact('message'));
    }

    // 👇 پاسخ به پیام
    public function reply(Request $request, $id)
    {
        $request->validate(['reply' => 'required|string']);
        $message = ContactMessage::findOrFail($id);
        $message->update(['reply' => $request->reply]);

        return back()->with('success', 'پاسخ شما ارسال و ذخیره شد ✅');
    }
}
