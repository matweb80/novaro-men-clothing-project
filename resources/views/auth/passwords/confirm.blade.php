@component('auth.layouts.auth-content')

<p class="eyebrow">تایید رمز عبور</p>
<h1 class="auth-card__title">{{ __('Please confirm your password before continuing.') }}</h1>

@if (session('status'))
<p class="form-success">{{ session('status') }}</p>
@endif

<form method="POST" action="{{ route('password.confirm') }}" novalidate>
    @csrf

    <div class="form-group">
        <label class="form-label" for="password">رمز عبور</label>
        <input
            id="password"
            type="password"
            name="password"
            class="form-input"
            autocomplete="current-password"
            aria-invalid="{{ $errors->has('password') ? 'true' : 'false' }}" />
        @error('password')
        <p class="form-error">{{ $message }}</p>
        @enderror
    </div>

    <button type="submit" class="btn btn--primary btn--block">{{ __('Confirm Password') }}</button>
</form>


<p class="auth-card__footer">
    <a href="{{ route('password.request') }}" class="auth-link">فراموشی رمز عبور؟</a>
</p>

@endcomponent