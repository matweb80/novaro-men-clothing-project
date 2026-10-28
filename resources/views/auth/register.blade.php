@component('auth.layouts.auth-content')

@slot('script')
<script>
    const password = document.getElementById('password');
    const bar = document.getElementById('password-strength-bar');
    const text = document.getElementById('password-strength-text');

    password.addEventListener('input', function() {

        const value = password.value;

        let strength = 0;

        if (value.length >= 8) strength++;
        if (/[A-Z]/.test(value)) strength++;
        if (/[0-9]/.test(value)) strength++;
        if (/[^A-Za-z0-9]/.test(value)) strength++;

        switch (strength) {

            case 0:
                bar.style.width = '0%';
                text.innerHTML = '';
                break;

            case 1:
                bar.style.width = '25%';
                bar.className = 'progress-bar bg-danger';
                text.innerHTML = 'ضعیف';
                break;

            case 2:
                bar.style.width = '50%';
                bar.className = 'progress-bar bg-warning';
                text.innerHTML = 'متوسط';
                break;

            case 3:
                bar.style.width = '75%';
                bar.className = 'progress-bar bg-info';
                text.innerHTML = 'خوب';
                break;

            case 4:
                bar.style.width = '100%';
                bar.className = 'progress-bar bg-success';
                text.innerHTML = 'قوی';
                break;
        }
    });
</script>
@endslot

<p class="eyebrow">عضویت</p>
<h1 class="auth-card__title">ساخت حساب کاربری</h1>

<form method="POST" action="{{ route('register') }}" novalidate>
    @csrf

    <div class="form-group">
        <label class="form-label" for="name">نام و نام خانوادگی</label>
        <input
            id="name"
            type="text"
            name="name"
            value="{{ old('name') }}"
            class="form-input"
            autocomplete="name"
            autofocus
            aria-invalid="{{ $errors->has('name') ? 'true' : 'false' }}" />
        @error('name')
        <p class="form-error">{{ $message }}</p>
        @enderror
    </div>

    <div class="form-group">
        <label class="form-label" for="email">ایمیل</label>
        <input
            id="email"
            type="email"
            name="email"
            value="{{ old('email') }}"
            class="form-input"
            autocomplete="email"
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
            autocomplete="new-password"
            aria-invalid="{{ $errors->has('password') ? 'true' : 'false' }}" />
        <div class="progress mt-2" style="height: 8px;">
            <div id="password-strength-bar" class="progress-bar" role="progressbar" style="width: 0%;"></div>
        </div>
        <small id="password-strength-text"></small>

        @if($errors->has('password'))
        @foreach ($errors->get('password') as $message)
        <p class="form-error">{{ $message }}</p>
        @endforeach
        @endif
    </div>

    <div class="form-group">
        <label class="form-label" for="password-confirm">تکرار رمز عبور</label>
        <input
            id="password-confirm"
            type="password"
            name="password_confirmation"
            class="form-input"
            autocomplete="new-password" />
    </div>
    <div class="form-group">
        <x-recaptcha :has-error="$errors->has('g-recaptcha-response')" />
        @error('g-recaptcha-response')
        <strong class="form-error" role="alert">
            {{ $message }}
        </strong>
        @enderror
    </div>

    <button type="submit" class="btn btn--primary btn--block">ثبت‌نام</button>
</form>

@include('auth.partials.social-buttons')

<p class="auth-card__footer">
    قبلاً ثبت‌نام کرده‌اید؟ <a href="{{ route('login') }}" class="auth-link">وارد شوید</a>
</p>

@endcomponent