@component('admin.layouts.content',['title'=>'لیست پیام های فرم تماس با ما'])

@slot('breadcrumb')
<li class="breadcrumb-item"><a href="/admin">پنل مدیریت</a></li>
<li class="breadcrumb-item active">لیست پیام های فرم تماس با ما</li>
@endslot

<div class="container">
    <h2 class="mb-4"> پیام‌ها (خوانده‌نشده): <span class="badge bg-warning">{{ $unreadCount }}</span></h2>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>نام</th>
                <th>ایمیل</th>
                <th>تلفن</th>
                <th>موضوع</th>
                <th>تاریخ</th>
                <th>وضعیت</th>
                <th>عملیات</th>
            </tr>
        </thead>
        <tbody>
            @foreach($messages as $msg)
            <tr @if(!$msg->is_read) class="bg-warning bg-opacity-25" @endif>
                <td>{{ $msg->name }}</td>
                <td>{{ $msg->email }}</td>
                <td>{{ $msg->phone ?? '-' }}</td>
                <td>{{ $msg->subject }}</td>
                <td>{{ jdate($msg->created_at)->format('Y/m/d') }}</td>
                <td>
                    @if($msg->is_read)
                    <span class="badge bg-success">خوانده‌شده</span>
                    @else
                    <span class="badge bg-warning">خوانده‌نشده</span>
                    @endif
                </td>
                <td>
                    <a href="{{ route('admin.messages.show', $msg->id) }}" class="btn btn-sm btn-primary">مشاهده</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{ $messages->render("pagination::bootstrap-5") }}
</div>

@endcomponent