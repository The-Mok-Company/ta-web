@extends('auth.layouts.authentication')

@section('content')

<style>
    /* =========================
       Auth Background Wrapper
       ========================= */
    .auth-bg-wrap{
        min-height: 100vh;
        position: relative;
        padding: 24px 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #ffffff; /* Mobile: no background */
    }

    /* Desktop / Tablet background */
    @media (min-width: 768px){
        .auth-bg-wrap{
            background: #0b1220;
        }

        .auth-bg-wrap::before{
            content:"";
            position:absolute;
            inset:0;
            background-image: url("https://images.unsplash.com/photo-1504674900247-0877df9cc836?auto=format&fit=crop&w=1920&q=80");

            /* background-image: url("{{ uploaded_asset(get_setting('customer_login_page_image')) }}"); */
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            z-index: 0;
        }

        .auth-bg-wrap::after{
            content:"";
            position:absolute;
            inset:0;
            background: rgba(0,0,0,.65);
            z-index: 1;
        }
    }

    /* Center card */
    .auth-center{
        position: relative;
        z-index: 2;
        width: min(520px, 100%);
    }

    .auth-card{
        background: #fff;
        border-radius: 14px;
        box-shadow: 0 20px 50px rgba(0,0,0,.25);
        overflow: hidden;
    }

    /* Prevent cut on small screens */
    .auth-card-inner{
        max-height: calc(100vh - 32px);
        overflow-y: auto;
    }

    @media (max-width: 767px){
        .auth-bg-wrap{
            align-items: flex-start;
            padding-top: 16px;
        }

        .auth-card{
            box-shadow: none;
            border-radius: 0;
        }

        .auth-card-inner{
            max-height: none;
            overflow: visible;
        }
    }
</style>

<div class="auth-bg-wrap">
    <div class="auth-center">
        <div class="auth-card">
            <div class="auth-card-inner p-4 p-md-5">

                <!-- Site Icon -->
                <div class="size-48px mb-3 text-center">
                    <img src="{{ uploaded_asset(get_setting('site_icon')) }}" alt="{{ translate('Site Icon') }}" class="img-fit h-100">
                </div>

                <!-- Titles -->
                <div class="text-center mb-3">
                    <h1 class="fs-20 fs-md-24 fw-700 text-primary text-uppercase">
                        {{ translate('Welcome Back !') }}
                    </h1>
                    <h5 class="fs-14 fw-400 text-dark">
                        {{ translate('Login to your account') }}
                    </h5>
                </div>

                <!-- Login Form -->
                <form id="user-login-form" class="form-default" method="POST" action="{{ route('login') }}">
                    @csrf

                    <!-- Email -->
                    <div class="form-group">
                        <label class="fs-12 fw-700 text-soft-dark">{{ translate('Email') }}</label>
                        <input type="email"
                               name="email"
                               class="form-control rounded-0 {{ $errors->has('email') ? 'is-invalid' : '' }}"
                               value="{{ old('email') }}"
                               placeholder="{{ translate('johndoe@example.com') }}"
                               autocomplete="off">
                        @error('email')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div class="form-group">
                        <label class="fs-12 fw-700 text-soft-dark">{{ translate('Password') }}</label>
                        <div class="position-relative">
                            <input type="password"
                                   name="password"
                                   class="form-control rounded-0 {{ $errors->has('password') ? 'is-invalid' : '' }}"
                                   placeholder="{{ translate('Password') }}">
                            <i class="password-toggle las la-eye la-2x"></i>
                        </div>
                        @error('password')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Remember / Forgot -->
                    <div class="row mb-2">
                        <div class="col-6">
                            <label class="aiz-checkbox">
                                <input type="checkbox" name="remember">
                                <span class="fs-12">{{ translate('Remember Me') }}</span>
                                <span class="aiz-square-check"></span>
                            </label>
                        </div>
                        <div class="col-6 text-right">
                            <a href="{{ route('password.request') }}" class="fs-12 text-primary">
                                {{ translate('Forgot password?') }}
                            </a>
                        </div>
                    </div>

                    <!-- Submit -->
                    <button type="submit" class="btn btn-primary btn-block fw-700 fs-14 mt-3">
                        {{ translate('Login') }}
                    </button>
                </form>

                <!-- Register -->
                <p class="fs-12 text-gray text-center mt-4 mb-0">
                    {{ translate('Dont have an account?') }}
                    <a href="{{ route('user.registration') }}" class="fw-700 text-primary">
                        {{ translate('Register Now') }}
                    </a>
                </p>

                <!-- Back -->
                <div class="text-center mt-3">
                    <a href="{{ url()->previous() }}" class="fs-14 fw-700 text-primary d-inline-flex align-items-center">
                        <i class="las la-arrow-left mr-1"></i>
                        {{ translate('Back') }}
                    </a>
                </div>

            </div>
        </div>
    </div>
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
    document.getElementById('user-login-form').addEventListener('submit', function(e){
        e.preventDefault();
        grecaptcha.ready(function(){
            grecaptcha.execute('{{ env('CAPTCHA_KEY') }}', {action:'login'}).then(function(token){
                let input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'g-recaptcha-response';
                input.value = token;
                e.target.appendChild(input);
                e.target.submit();
            });
        });
    });
</script>
@endif
@endsection
