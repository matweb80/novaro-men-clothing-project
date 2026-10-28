@component('admin.layouts.content',['title'=>'لیست پیام های فرم تماس با ما'])

@slot('breadcrumb')
<li class="breadcrumb-item"><a href="/admin">پنل مدیریت</a></li>
<li class="breadcrumb-item active">لیست پیام های فرم تماس با ما</li>
@endslot

<div class="container">
    <h2 class="mb-3">مشاهده پیام</h2>

    <div class="card p-3">
        <p><strong>نام:</strong> {{ $message->name }}</p>
        <p><strong>ایمیل:</strong> {{ $message->email }}</p>
        <p><strong>تلفن:</strong> {{ $message->phone ?? '-' }}</p>
        <p><strong>موضوع:</strong> {{ $message->subject }}</p>
        <p><strong>پیام:</strong></p>
        <p class="border p-3 bg-light">{{ $message->message }}</p>

        @if($message->reply)
        <hr>
        <p><strong>پاسخ ادمین:</strong></p>
        <p class="border p-3 bg-success bg-opacity-10">{{ $message->reply }}</p>
        @else
        <form method="POST" action="{{ route('admin.messages.reply', $message->id) }}" class="mt-4">
            @csrf
            <div class="mb-3">
                <label>ارسال پاسخ</label>
                <textarea name="reply" class="form-control" rows="3" required></textarea>
            </div>
            <button class="btn btn-success">ارسال پاسخ</button>
        </form>
        @endif
    </div>
</div>

@endcomponent