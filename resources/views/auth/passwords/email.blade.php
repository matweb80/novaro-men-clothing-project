@component('auth.layouts.auth-content')

<p class="eyebrow">فراموشی رمز</p>
<h1 class="auth-card__title">ارسال لینک تغییر رمز عبور</h1>

@if (session('status'))
<p class="form-success">{{ session('status') }}</p>
@endif

<form method="POST" action="{{ route('password.email') }}" novalidate>
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

    <button type="submit" class="btn btn--primary btn--block">{{ __('Send Password Reset Link') }}</button>
</form>

@endcomponent