<!doctype html>
<html lang="en">

<head>

    <title>Login | Sistem Informasi DEN</title>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="shortcut icon" href="{{ asset('theme/img/logo/favicon.png') }}">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <script src="https://www.google.com/recaptcha/api.js" async defer></script>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', sans-serif;
        }

        body {
            height: 100vh;
            display: flex;
        }

        /* LEFT SIDE */

        .login-left {
            flex: 1;
            background: url('{{ asset('login/images/bgweb.png') }}') center/cover no-repeat;
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
        }

        .login-left::before {
            content: "";
            position: absolute;
            inset: 0;
            background: rgba(0, 0, 0, 0.35);
        }

        .left-content {
            position: relative;
            text-align: center;
            max-width: 420px;
            padding: 30px;
        }

        .left-content h1 {
            font-size: 34px;
            font-weight: 600;
            margin-bottom: 10px;
        }

        .left-content p {
            opacity: .9;
            line-height: 1.6;
        }


        /* RIGHT SIDE */

        .login-right {
            width: 420px;
            background: #f8f9fa;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px;
        }

        .login-card {
            width: 100%;
            background: white;
            padding: 35px;
            border-radius: 14px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
            text-align: center;
        }

        .logo {
            width: 80px;
            margin-bottom: 10px;
        }

        .title {
            font-size: 20px;
            font-weight: 600;
            margin-bottom: 25px;
            color: #444;
        }

        /* INPUT */

        .form-group {
            margin-bottom: 18px;
            position: relative;
        }

        .form-control {
            width: 100%;
            height: 48px;
            border-radius: 8px;
            border: 1px solid #ddd;
            padding: 0 15px;
            font-size: 14px;
            transition: .25s;
        }

        .form-control:focus {
            outline: none;
            border-color: #009933;
            box-shadow: 0 0 0 3px rgba(0, 153, 51, .15);
        }

        .form-control::placeholder {
            color: #aaa;
        }

        .toggle-password {
            position: absolute;
            right: 14px;
            top: 14px;
            cursor: pointer;
            color: #777;
        }

        /* BUTTON LOGIN */

        .btn-login {
            width: 100%;
            height: 48px;
            border: none;
            border-radius: 8px;
            background: linear-gradient(90deg, #00b300, #008000);
            color: white;
            font-weight: 600;
            cursor: pointer;
            transition: .2s;
        }

        .btn-login:hover {
            transform: translateY(-1px);
            box-shadow: 0 8px 18px rgba(0, 0, 0, 0.2);
        }

        /* DIVIDER */

        .divider {
            display: flex;
            align-items: center;
            gap: 10px;
            margin: 18px 0;
            color: #aaa;
            font-size: 13px;
        }

        .divider::before,
        .divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: #e0e0e0;
        }

        /* BUTTON SSO */

        .btn-sso {
            width: 100%;
            height: 48px;
            border: none;
            border-radius: 8px;
            background: #fcc600;
            color: #333;
            font-weight: 700;
            font-size: 14px;
            cursor: pointer;
            transition: .2s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            text-decoration: none;
        }

        .btn-sso:hover {
            transform: translateY(-1px);
            box-shadow: 0 8px 18px rgba(252, 198, 0, 0.4);
            background: #e6b400;
            color: #333;
        }

        .btn-sso img {
            width: 18px;
            height: 18px;
            object-fit: contain;
        }

        /* ERROR */

        .invalid-feedback {
            font-size: 13px;
            color: #e74c3c;
            text-align: left;
            margin-top: 5px;
        }

        .app-version {
            margin-top: 15px;
            font-size: 12px;
            color: #999;
            text-align: center;
        }

        /* MOBILE */

        @media(max-width:900px) {

            .login-left {
                display: none;
            }

            .login-right {
                width: 100%;
            }

        }

        .form-extra {
            display: flex;
            justify-content: flex-end;
            margin-top: -5px;
            margin-bottom: 15px;
        }

        .forgot-link {
            font-size: 13px;
            color: #666;
            text-decoration: none;
        }

        .forgot-link:hover {
            color: #009933;
            text-decoration: underline;
        }
    </style>

</head>

<body>

    <!-- LEFT PANEL -->

    <div class="login-left">
        <div class="left-content"></div>
    </div>

    <!-- RIGHT PANEL -->

    <div class="login-right">

        <div class="login-card">

            <img src="{{ asset('theme/img/logo/logo-den-id-trans.png') }}" class="logo">

            <div class="title">
                Login Sistem
            </div>

            <form action="{{ url('/loginprocess') }}" method="post">

                @csrf

                <!-- USERNAME -->
                <div class="form-group">

                    <input type="text" name="username" class="form-control @error('username') is-invalid @enderror"
                        placeholder="Username" value="{{ old('username') }}">

                    @error('username')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror

                </div>


                <!-- PASSWORD -->
                <div class="form-group">

                    <input type="password" id="password-field" name="password"
                        class="form-control @error('password') is-invalid @enderror" placeholder="Password">

                    <i class="fa fa-eye toggle-password" toggle="#password-field"></i>

                    @error('password')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror

                </div>


                <!-- LUPA PASSWORD -->
                <div class="form-extra">

                    <a href="{{ route('password.request') }}" class="forgot-link">
                        Lupa Password?
                    </a>

                </div>


                <!-- CAPTCHA -->
                <div style="margin:20px 0; display:flex; justify-content:center;">

                    <div class="g-recaptcha" data-sitekey="{{ config('services.recaptcha.site_key') }}">
                    </div>

                </div>

                @error('g-recaptcha-response')
                    <div class="invalid-feedback" style="text-align:center">
                        {{ $message }}
                    </div>
                @enderror


                <!-- BUTTON LOGIN -->
                <button class="btn-login">
                    LOGIN
                </button>

            </form>


            <!-- DIVIDER -->
            <div class="divider">atau</div>

            
            <!-- BUTTON SSO -->
            <a href="#" 
   style="width:100%; height:48px; background:#fcc600; color:#333; display:flex; align-items:center; justify-content:center; border-radius:8px; font-weight:700; text-decoration:none; gap:10px; margin-top:5px;">
    <div style="width:18px; height:18px; overflow:hidden; flex-shrink:0;">
        <img src="{{ asset('theme/img/logo/logo-esdm.png') }}" alt="ESDM" 
             style="width:100%; height:100%; object-fit:contain;">
    </div>
    Single Sign-On ESDM
</a>


            <div class="app-version">
                Version 3.1.1
            </div>

        </div>

    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        $(".toggle-password").click(function() {

            let input = $($(this).attr("toggle"));

            if (input.attr("type") === "password") {

                input.attr("type", "text");
                $(this).removeClass("fa-eye").addClass("fa-eye-slash");

            } else {

                input.attr("type", "password");
                $(this).removeClass("fa-eye-slash").addClass("fa-eye");

            }

        });
    </script>

</body>

</html>