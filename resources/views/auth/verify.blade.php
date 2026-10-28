@component('auth.layouts.auth-content')

<p class="eyebrow">تایید ایمیل</p>
<h1 class="auth-card__title">{{ __('Verify Your Email Address') }}</h1>

@if (session('resent'))
<p class="form-success">{{ __('A fresh verification link has been sent to your email address.') }}</p>
@endif
{{ __('Before proceeding, please check your email for a verification link.') }}
{{ __('If you did not receive the email') }},

<form method="POST" action="{{ route('verification.resend') }}" novalidate>
    @csrf

    <button type="submit" class="btn btn--primary btn--block">{{ __('click here to request another') }}</button>
</form>

@endcomponent