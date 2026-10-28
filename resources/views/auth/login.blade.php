@component('auth.layouts.auth-content')

<p class="eyebrow">خوش آمدید</p>
<h1 class="auth-card__title">ورود به حساب کاربری</h1>

@if (session('status'))
<p class="form-success">{{ session('status') }}</p>
@endif

<form method="POST" action="{{ route('login') }}" novalidate>
    @csrf

    <div class="form-group">
        <label class="form-label" for="email">ایمیل</label>
        <input
            id="email"
            type="email"
            name="email"
            value="{{ old('email') }}"
            class="form-input"
            autocomplete="email"
            autofocus
            aria-invalid="{{ $errors->has('email') ? 'true' : 'false' }}" />
        @error('email')
        <p class="form-error">{{ $message }}</p>
        @enderror
    </div>

    <div class="form-group">
        <label class="form-label" for="password">رمز عبور</label>
        <input
            id="password"
            type="password"
            name="password"
            class="form-input"
            autocomplete="current-password"
            aria-invalid="{{ $errors->has('password') ? 'true' : 'false' }}" />
        @if($errors->has('password'))
        @foreach ($errors->get('password') as $message)
        <p class="form-error">{{ $message }}</p>
        @endforeach
        @endif
    </div>

    <div class="auth-row">
        <label class="filter-check">
            <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }} />
            <span>مرا به خاطر بسپار</span>
        </label>
        <a href="{{ route('password.request') }}" class="auth-link">فراموشی رمز عبور؟</a>
    </div>

    <button type="submit" class="btn btn--primary btn--block">ورود</button>
</form>

@include('auth.partials.social-buttons')

<p class="auth-card__footer">
    حساب کاربری ندارید؟ <a href="{{ route('register') }}" class="auth-link">ثبت‌نام کنید</a>
</p>

@endcomponent