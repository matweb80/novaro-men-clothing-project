@php
    $currentAdmin = auth()->user();
    $unreadMessagesCount = $currentAdmin && $currentAdmin->can('show-messages')
        ? \App\Models\ContactMessage::where('is_read', false)->count()
        : 0;
@endphp

<nav class="main-header navbar navbar-expand navbar-white navbar-light border-bottom" aria-label="ناوبری اصلی">
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button" aria-label="باز و بسته کردن منو">
                <i class="fa fa-bars" aria-hidden="true"></i>
            </a>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
            <a href="{{ route('admin.dashboard') }}" class="nav-link">خانه</a>
        </li>
        @can('show-messages')
            <li class="nav-item">
                <a href="{{ route('admin.messages.index') }}" class="nav-link" aria-label="پیام‌های فرم تماس">
                    <i class="fa fa-envelope-o" aria-hidden="true"></i>
                    <span class="d-inline d-sm-none">پیام‌ها</span>
                    @if ($unreadMessagesCount > 0)
                        <span class="badge badge-danger navbar-badge">
                            {{ number_format($unreadMessagesCount) }}
                        </span>
                    @endif
                </a>
            </li>
        @endcan
    </ul>

    <ul class="navbar-nav mr-auto">
        @if ($currentAdmin)
            <li class="nav-item d-none d-sm-inline-block">
                <span class="nav-link">{{ $currentAdmin->name }}</span>
            </li>
        @endif
    </ul>
</nav>