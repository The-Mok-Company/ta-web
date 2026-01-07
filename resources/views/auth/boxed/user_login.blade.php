@extends('auth.layouts.authentication')

@push('styles')
<style>
    /* Fullscreen background with image */
    .auth-bg {
        min-height: 100vh;
        background-image: url("{{ uploaded_asset(get_setting('customer_login_page_image')) }}");
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        position: relative;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 24px 12px;
    }

    /* Dark + blur overlay like your screenshot */
    .auth-bg::before{
        content: "";
        position: absolute;
        inset: 0;
        background: rgba(0,0,0,.55);
        backdrop-filter: blur(8px);
        -webkit-backdrop-filter: blur(8px);
    }

    /* Modal card */
    .auth-modal{
        position: relative;
        z-index: 2;
        width: 100%;
        max-width: 520px;
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 24px 70px rgba(0,0,0,.35);
        overflow: hidden;
        padding: 28px 28px 22px;
    }

    /* Close button */
    .auth-close{
        position: absolute;
        top: 14px;
        right: 14px;
        width: 34px;
        height: 34px;
        border-radius: 999px;
        border: 1px solid rgba(0,0,0,.08);
        background: #fff;
        color: #444;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        line-height: 1;
        font-size: 18px;
    }
    .auth-close:hover{ background: #f7f7f7; }

    /* Title spacing */
    .auth-title{
        text-align: center;
        margin-top: 6px;
        margin-bottom: 16px;
    }

    /* Social separator */
    .auth-sep{
        display: flex;
        align-items: center;
        gap: 10px;
        margin: 14px 0;
        color: #8a8a8a;
        font-size: 12px;
    }
    .auth-sep::before,
    .auth-sep::after{
        content:"";
        flex: 1;
        height: 1px;
        background: #e9e9e9;
    }

    /* Social buttons (pill) */
    .auth-social-btn{
        width: 100%;
        border: 1px solid #e6e6e6;
        background: #fff;
        border-radius: 999px;
        padding: 10px 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        font-weight: 600;
        font-size: 13px;
        color: #222;
        margin-bottom: 10px;
        transition: .15s ease;
    }
    .auth-social-btn:hover{
        background: #fafafa;
        transform: translateY(-1px);
    }

    /* Make inputs look clean */
    .auth-modal .form-control{
        border-radius: 10px !important;
    }

    @media (max-width: 420px){
        .auth-modal{ padding: 22px 18px 18px; border-radius: 14px; }
    }
</style>
@endpush

@section('content')
<div class="aiz-main-wrapper">
    <section class="auth-bg">

        <div class="auth-modal">

            {{-- Close --}}
            <button type="button" class="auth-close" onclick="window.location='{{ url()->previous() }}'">×</button>

            {{-- Icon --}}
            <div class="size-48px mb-2 mx-auto text-center">
                <img src="{{ uploaded_asset(get_setting('site_icon')) }}" alt="{{ translate('Site Icon')}}" class="img-fit h-100">
            </div>

            {{-- Titles --}}
            <div class="auth-title">
                <h3 class="fw-700 mb-1" style="font-size:18px;">{{ translate('Login using email') }}</h3>
                <div class="text-muted" style="font-size:12px;">{{ translate('Login to your account') }}</div>
            </div>

            {{-- FORM (نفس الفورم بتاعك) --}}
            <form class="form-default loginForm" id="user-login-form" role="form" action="{{ route('login') }}" method="POST">
                @csrf

                @if (addon_is_activated('otp_system'))
                    <div class="form-group mb-2">
                        <label for="phone" class="fs-12 fw-700 text-soft-dark">{{ translate('Phone') }}</label>
                        <input type="tel" phone-number id="phone-code"
                               class="form-control{{ $errors->has('phone') ? ' is-invalid' : '' }}"
                               value="{{ old('phone') }}" name="phone" autocomplete="off">
                    </div>

                    <input type="hidden" name="country_code" value="">

                    <div class="form-group mb-2 email-form-group d-none">
                        <label for="email" class="fs-12 fw-700 text-soft-dark">{{ translate('Email') }}</label>
                        <input type="email"
                               class="form-control {{ $errors->has('email') ? ' is-invalid' : '' }}"
                               value="{{ old('email') }}"
                               placeholder="{{ translate('johndoe@example.com') }}"
                               name="email" id="email" autocomplete="off">
                        @if ($errors->has('email'))
                            <span class="invalid-feedback" role="alert"><strong>{{ $errors->first('email') }}</strong></span>
                        @endif
                    </div>

                    <div class="form-group text-right mb-2">
                        <button class="btn btn-link p-0 text-primary fs-12 fw-400" type="button" onclick="toggleEmailPhone(this)">
                            <i>*{{ translate('Use Email Instead') }}</i>
                        </button>
                    </div>
                @else
                    <div class="form-group mb-2">
                        <label for="email" class="fs-12 fw-700 text-soft-dark">{{ translate('Email Address') }}</label>
                        <input type="email"
                               class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}"
                               value="{{ old('email') }}"
                               placeholder="{{ translate('Enter your email address') }}"
                               name="email" id="email" autocomplete="off">
                        @if ($errors->has('email'))
                            <span class="invalid-feedback" role="alert"><strong>{{ $errors->first('email') }}</strong></span>
                        @endif
                    </div>
                @endif

                <div class="password-login-block">
                    <div class="form-group mb-1">
                        <label for="password" class="fs-12 fw-700 text-soft-dark">{{ translate('Password') }}</label>
                        <div class="position-relative">
                            <input type="password"
                                   class="form-control {{ $errors->has('password') ? ' is-invalid' : '' }}"
                                   placeholder="{{ translate('Enter your password') }}"
                                   name="password" id="password">
                            <i class="password-toggle las la-2x la-eye"></i>
                        </div>
                    </div>

                    {{-- Recaptcha errors --}}
                    @if(get_setting('google_recaptcha') == 1 && get_setting('recaptcha_customer_login') == 1)
                        @if ($errors->has('g-recaptcha-response'))
                            <span class="border invalid-feedback rounded p-2 mb-2 bg-danger text-white" role="alert" style="display: block;">
                                <strong>{{ $errors->first('g-recaptcha-response') }}</strong>
                            </span>
                        @endif
                    @endif

                    <div class="d-flex align-items-center justify-content-between mt-2 mb-2">
                        <label class="aiz-checkbox mb-0">
                            <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
                            <span class="has-transition fs-12 fw-400 text-gray-dark hov-text-primary">{{ translate('Remember Me') }}</span>
                            <span class="aiz-square-check"></span>
                        </label>

                        <div class="text-right">
                            @if(get_setting('login_with_otp'))
                                <a href="javascript:void(0);" class="fs-12 fw-400 text-gray-dark hov-text-primary toggle-login-with-otp" onclick="toggleLoginPassOTP(this)">
                                    {{ translate('Login With OTP') }} /
                                </a>
                            @endif
                            <a href="{{ route('password.request') }}" class="fs-12 fw-400 text-gray-dark hov-text-primary">
                                <u>{{ translate('Forget password?') }}</u>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="mt-3">
                    <button type="submit" class="btn btn-primary btn-block fw-700" style="border-radius: 999px;">
                        {{ translate('Login') }}
                    </button>
                </div>
            </form>

            {{-- Social login (optional) --}}
            @if(get_setting('google_login') == 1 || get_setting('facebook_login') == 1 || get_setting('twitter_login') == 1 || get_setting('apple_login') == 1)
                <div class="auth-sep">{{ translate('Continue with') }}</div>

                @if (get_setting('facebook_login') == 1)
                    <a href="{{ route('social.login', ['provider' => 'facebook']) }}" class="auth-social-btn">
                        <i class="lab la-facebook-f"></i> {{ translate('Continue with Facebook') }}
                    </a>
                @endif

                @if(get_setting('google_login') == 1)
                    <a href="{{ route('social.login', ['provider' => 'google']) }}" class="auth-social-btn">
                        <i class="lab la-google"></i> {{ translate('Continue with Google') }}
                    </a>
                @endif

                @if (get_setting('apple_login') == 1)
                    <a href="{{ route('social.login', ['provider' => 'apple']) }}" class="auth-social-btn">
                        <i class="lab la-apple"></i> {{ translate('Continue with Apple') }}
                    </a>
                @endif
            @endif

            {{-- Register --}}
            <p class="fs-12 text-center mt-3 mb-0 text-muted">
                {{ translate('New to the platform?') }}
                <a href="{{ route('user.registration') }}" class="fw-700">{{ translate('Join Now') }}</a>
            </p>

        </div>
    </section>
</div>
@endsection

@section('script')
<script>
    function autoFillCustomer(){
        $('#email').val('customer@example.com');
        $('#password').val('123456');
    }
</script>

@if(get_setting('google_recaptcha') == 1 && get_setting('recaptcha_customer_login') == 1)
<script src="https://www.google.com/recaptcha/api.js?render={{ env('CAPTCHA_KEY') }}"></script>
<script>
    document.getElementById('user-login-form').addEventListener('submit', function(e) {
        e.preventDefault();
        grecaptcha.ready(function() {
            grecaptcha.execute(`{{ env('CAPTCHA_KEY') }}`, {action: 'login'}).then(function(token) {
                var input = document.createElement('input');
                input.setAttribute('type', 'hidden');
                input.setAttribute('name', 'g-recaptcha-response');
                input.setAttribute('value', token);
                e.target.appendChild(input);

                var actionInput = document.createElement('input');
                actionInput.setAttribute('type', 'hidden');
                actionInput.setAttribute('name', 'recaptcha_action');
                actionInput.setAttribute('value', 'recaptcha_customer_login');
                e.target.appendChild(actionInput);

                e.target.submit();
            });
        });
    });
</script>
@endif
@endsection
