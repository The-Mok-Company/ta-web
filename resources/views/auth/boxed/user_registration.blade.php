@extends('auth.layouts.authentication')

@push('styles')
<style>
    /* Background */
    .auth-bg {
        min-height: 100dvh;
        background-image: url("{{ uploaded_asset(get_setting('customer_register_page_image')) }}");
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        position: relative;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 16px;
    }

    /* Overlay + Blur */
    .auth-bg::before {
        content: "";
        position: absolute;
        inset: 0;
        background: rgba(0,0,0,.55);
        backdrop-filter: blur(8px);
        -webkit-backdrop-filter: blur(8px);
    }

    /* Modal container */
    .auth-modal {
        position: relative;
        z-index: 2;
        width: 100%;
        max-width: 720px;
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 24px 70px rgba(0,0,0,.35);
        display: flex;
        flex-direction: column;

        /* مهم جداً */
        max-height: calc(100dvh - 32px);
        overflow: hidden; /* يمنع خروج عناصر برّا */
    }

    /* Header ثابت */
    .auth-header {
        padding: 22px 22px 10px;
        text-align: center;
        position: relative;
        flex-shrink: 0;
        border-bottom: 1px solid rgba(0,0,0,.06);
    }

    /* Close button */
    .auth-close {
        position: absolute;
        top: 12px;
        right: 12px;
        width: 34px;
        height: 34px;
        border-radius: 50%;
        border: 1px solid rgba(0,0,0,.12);
        background: #fff;
        font-size: 18px;
        cursor: pointer;
        line-height: 1;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        color: #444;
    }
    .auth-close:hover { background: #f7f7f7; }

    /* Body هو اللي هيعمل scroll */
    .auth-body {
        padding: 18px 22px 22px;
        overflow-y: auto; /* ✅ الاسكرول هنا */
        -webkit-overflow-scrolling: touch;
    }

    /* Inputs nicer */
    .auth-body .form-control {
        border-radius: 10px !important;
    }

    /* Buttons round */
    .auth-body .btn.round-pill-btn{
        border-radius: 999px !important;
    }

    @media (max-width: 576px){
        .auth-modal { border-radius: 14px; }
        .auth-header { padding: 18px 16px 8px; }
        .auth-body { padding: 16px 16px 18px; }
    }
</style>
@endpush


@section('content')
<div class="aiz-main-wrapper">
    <section class="auth-bg">

        <div class="auth-modal">

            {{-- Header (ثابت) --}}
            <div class="auth-header">
                <button type="button" class="auth-close" onclick="window.location='{{ url()->previous() }}'">×</button>

                <div class="size-48px mb-2 mx-auto">
                    <img src="{{ uploaded_asset(get_setting('site_icon')) }}"
                         alt="{{ translate('Site Icon')}}"
                         class="img-fit h-100">
                </div>

                <h1 class="fs-20 fs-md-24 fw-700 text-primary mb-0" style="text-transform: uppercase;">
                    {{ translate('Create an account') }}
                </h1>
            </div>

            {{-- Body (Scrollable) --}}
            <div class="auth-body">

                <form id="reg-form" class="form-default" role="form" action="{{ route('register') }}" method="POST">
                    @csrf

                    <!-- Name -->
                    <div class="form-group">
                        <label for="name" class="fs-12 fw-700 text-soft-dark">{{ translate('Full Name') }}</label>
                        <input type="text"
                               class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}"
                               value="{{ old('name') }}"
                               placeholder="{{ translate('Full Name') }}"
                               name="name">
                        @if ($errors->has('name'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('name') }}</strong>
                            </span>
                        @endif
                    </div>

                    @if (addon_is_activated('otp_system'))
                        <div>
                            <div id="emailOrPhoneDiv">
                                <div class="form-group phone-form-group mb-1">
                                    <label for="phone" class="fs-12 fw-700 text-soft-dark">{{ translate('Phone') }}</label>
                                    <div class="input-group registration-iti">
                                        <input type="tel" phone-number id="phone-code"
                                               class="form-control{{ $errors->has('phone') ? ' is-invalid' : '' }}"
                                               value="{{ old('phone') }}"
                                               placeholder=""
                                               name="phone"
                                               autocomplete="off">
                                        @if(get_setting('customer_registration_verify') == '1')
                                            <button class="btn btn-primary" type="button" id="sendOtpPhoneBtn" onclick="sendVerificationCode(this)">
                                                {{ translate('Verify') }}
                                            </button>
                                        @endif
                                    </div>
                                </div>

                                <input type="hidden" id="country_code" name="country_code" value="{{ old('country_code', 'US') }}">

                                <div class="form-group email-form-group mb-1 d-none">
                                    <label for="email" class="fs-12 fw-700 text-soft-dark">{{ translate('Email') }}</label>
                                    <div class="input-group">
                                        <input type="email"
                                               class="form-control {{ $errors->has('email') ? ' is-invalid' : '' }}"
                                               value="{{ old('email') }}"
                                               placeholder="{{ translate('Email') }}"
                                               id="signinAddonEmail"
                                               name="email"
                                               autocomplete="off">
                                        @if(get_setting('customer_registration_verify') == '1')
                                            <button class="btn btn-primary ml-2" type="button" id="sendOtpBtn" onclick="sendVerificationCode(this)">
                                                {{ translate('Verify') }}
                                            </button>
                                        @endif
                                    </div>
                                    @if ($errors->has('email'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('email') }}</strong>
                                        </span>
                                    @endif
                                </div>

                                <div class="form-group text-right mb-0">
                                    <button class="btn btn-link p-0 text-primary" type="button" onclick="toggleEmailPhone(this)">
                                        <i>*{{ translate('Use Email Instead') }}</i>
                                    </button>
                                </div>
                            </div>

                            <div class="form-group mb-3 d-none">
                                <label class="form-label" for="verification_code">{{ translate('Verification Code') }}</label>
                                <div class="input-group">
                                    <input type="text"
                                           class="form-control @error('verification_code') is-invalid @enderror border-right-0"
                                           name="code" id="verification_code"
                                           placeholder="{{ translate('Verification Code') }}"
                                           maxlength="6">
                                    <span class="btn border border-left-0" id="verifyOtpBtn">
                                        <i class="las la-lg la-arrow-right"></i>
                                    </span>
                                    @error('otp')
                                    <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="form-group email-phone-div" id="emailOrPhoneDiv">
                            <label for="email" class="fs-12 fw-700 text-soft-dark">{{ translate('Email') }}</label>
                            <div class="input-group">
                                <input type="email"
                                       class="form-control {{ $errors->has('email') ? ' is-invalid' : '' }}"
                                       name="email" id="signinSrEmail"
                                       placeholder="{{ translate('Email Address') }}"
                                       value="{{ old('email') }}">
                                @if(get_setting('customer_registration_verify') == '1')
                                    <button class="btn btn-primary ml-2" type="button" id="sendOtpBtn" onclick="sendVerificationCode()">
                                        {{ translate('Verify') }}
                                    </button>
                                @endif
                            </div>
                            @if ($errors->has('email'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('email') }}</strong>
                                </span>
                            @endif
                        </div>

                        <div class="form-group mb-3 d-none">
                            <label class="form-label" for="verification_code">{{ translate('Verification Code') }}</label>
                            <div class="input-group">
                                <input type="text"
                                       class="form-control @error('verification_code') is-invalid @enderror border-right-0"
                                       name="code" id="verification_code"
                                       placeholder="{{ translate('Verification Code') }}"
                                       maxlength="6">
                                <span class="btn border border-left-0" id="verifyOtpBtn">
                                    <i class="las la-lg la-arrow-right"></i>
                                </span>
                                @error('otp')
                                <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    @endif

                    <!-- Password -->
                    <div class="form-group mb-0">
                        <label for="password" class="fs-12 fw-700 text-soft-dark">{{ translate('Password') }}</label>
                        <div class="position-relative">
                            <input type="password"
                                   class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}"
                                   placeholder="{{ translate('Password') }}"
                                   name="password">
                            <i class="password-toggle las la-2x la-eye"></i>
                        </div>
                        <div class="text-right mt-1">
                            <span class="fs-12 fw-400 text-gray-dark">{{ translate('Password must contain at least 6 digits') }}</span>
                        </div>
                        @if ($errors->has('password'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('password') }}</strong>
                            </span>
                        @endif
                    </div>

                    <!-- Confirm -->
                    <div class="form-group">
                        <label for="password_confirmation" class="fs-12 fw-700 text-soft-dark">{{ translate('Confirm Password') }}</label>
                        <div class="position-relative">
                            <input type="password" class="form-control"
                                   placeholder="{{ translate('Confirm Password') }}"
                                   name="password_confirmation">
                            <i class="password-toggle las la-2x la-eye"></i>
                        </div>
                    </div>

                    <!-- Recaptcha -->
                    @if(get_setting('google_recaptcha') == 1 && get_setting('recaptcha_customer_register') == 1)
                        @if ($errors->has('g-recaptcha-response'))
                            <span class="border invalid-feedback rounded p-2 mb-3 bg-danger text-white" role="alert" style="display:block;">
                                <strong>{{ $errors->first('g-recaptcha-response') }}</strong>
                            </span>
                        @endif
                    @endif

                    <!-- Terms -->
                    <div class="mb-3">
                        <label class="aiz-checkbox">
                            <input type="checkbox" name="checkbox_example_1" required>
                            <span>{{ translate('By signing up you agree to our ') }}
                                <a href="{{ route('terms') }}" class="fw-500">{{ translate('terms and conditions.') }}</a>
                            </span>
                            <span class="aiz-square-check"></span>
                        </label>
                    </div>

                    <!-- Submit -->
                    <div class="mb-2 mt-3">
                        <button type="submit" class="btn btn-primary btn-block fw-600 round-pill-btn" id="createAccountBtn">
                            {{ translate('Create Account') }}
                        </button>
                    </div>
                </form>

                <!-- Social Login -->
                @if(get_setting('google_login') == 1 || get_setting('facebook_login') == 1 || get_setting('twitter_login') == 1 || get_setting('apple_login') == 1)
                    <div class="text-center my-3">
                        <span class="bg-white fs-12 text-gray">{{ translate('Or Join With') }}</span>
                    </div>
                    <ul class="list-inline social colored text-center mb-3">
                        @if (get_setting('facebook_login') == 1)
                            <li class="list-inline-item">
                                <a href="{{ route('social.login', ['provider' => 'facebook']) }}" class="facebook">
                                    <i class="lab la-facebook-f"></i>
                                </a>
                            </li>
                        @endif
                        @if (get_setting('twitter_login') == 1)
                            <li class="list-inline-item">
                                <a href="{{ route('social.login', ['provider' => 'twitter']) }}" class="x-twitter">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="#ffffff" viewBox="0 0 16 16" class="mb-2 pb-1">
                                        <path d="M12.6.75h2.454l-5.36 6.142L16 15.25h-4.937l-3.867-5.07-4.425 5.07H.316l5.733-6.57L0
                                        .75h5.063l3.495 4.633L12.601.75Zm-.86 13.028h1.36L4.323 2.145H2.865z"/>
                                    </svg>
                                </a>
                            </li>
                        @endif
                        @if(get_setting('google_login') == 1)
                            <li class="list-inline-item">
                                <a href="{{ route('social.login', ['provider' => 'google']) }}" class="google">
                                    <i class="lab la-google"></i>
                                </a>
                            </li>
                        @endif
                        @if (get_setting('apple_login') == 1)
                            <li class="list-inline-item">
                                <a href="{{ route('social.login', ['provider' => 'apple']) }}" class="apple">
                                    <i class="lab la-apple"></i>
                                </a>
                            </li>
                        @endif
                    </ul>
                @endif

                <!-- Login link -->
                <p class="fs-12 text-center mb-0 text-muted">
                    {{ translate('Already have an account?') }}
                    <a href="{{ route('user.login') }}" class="ml-1 fw-700">{{ translate('Log In') }}</a>
                </p>

            </div>
        </div>

    </section>
</div>
@endsection


@section('script')
@if(get_setting('google_recaptcha') == 1 && get_setting('recaptcha_customer_register') == 1)
<script src="https://www.google.com/recaptcha/api.js?render={{ env('CAPTCHA_KEY') }}"></script>
<script>
    document.getElementById('reg-form').addEventListener('submit', function(e) {
        e.preventDefault();
        grecaptcha.ready(function() {
            grecaptcha.execute(`{{ env('CAPTCHA_KEY') }}`, {action: 'register'}).then(function(token) {
                var input = document.createElement('input');
                input.setAttribute('type', 'hidden');
                input.setAttribute('name', 'g-recaptcha-response');
                input.setAttribute('value', token);
                e.target.appendChild(input);

                e.target.submit();
            });
        });
    });
</script>
@endif

@include('auth.verifyEmailOrPhone')

<script>
    const regVerifyRequired = {{ get_setting('customer_registration_verify') ? 'true' : 'false' }};
    const createBtn = $('#createAccountBtn');
    const termsCheckbox = $('input[name="checkbox_example_1"]');

    function toggleCreateBtn() {
        const termsChecked = termsCheckbox.is(':checked');
        const regVerified  = regVerifyRequired ? (verifyBtn && verifyBtn.classList.contains('disabled')) : true;
        const enableBtn = regVerifyRequired ? (termsChecked && regVerified) : termsChecked;
        createBtn.prop('disabled', !enableBtn);
    }

    document.addEventListener('DOMContentLoaded', function() {
        toggleCreateBtn();
        termsCheckbox.on('change', toggleCreateBtn);
    });
</script>
@endsection
