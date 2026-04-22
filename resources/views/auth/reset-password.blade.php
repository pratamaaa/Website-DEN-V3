<!doctype html>
<html lang="en">

<head>

    <title>Reset Password | Sistem Informasi DEN</title>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="shortcut icon" href="{{ asset('theme/img/logo/favicon.png') }}">

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

        /* LEFT */

        .login-left {
            flex: 1;
            background: url('{{ asset('login/images/bgweb.png') }}') center/cover no-repeat;
            position: relative;
        }

        .login-left::before {
            content: "";
            position: absolute;
            inset: 0;
            background: rgba(0, 0, 0, .35);
        }

        /* RIGHT */

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
            box-shadow: 0 20px 40px rgba(0, 0, 0, .15);
            text-align: center;
        }

        .logo {
            width: 80px;
            margin-bottom: 10px;
        }

        .title {
            font-size: 20px;
            font-weight: 600;
            margin-bottom: 20px;
            color: #444;
        }

        /* INPUT */

        .form-group {
            margin-bottom: 18px;
            text-align: left;
        }

        .form-control {
            width: 100%;
            height: 48px;
            border-radius: 8px;
            border: 1px solid #ddd;
            padding: 0 15px;
            font-size: 14px;
        }

        .form-control:focus {
            outline: none;
            border-color: #009933;
            box-shadow: 0 0 0 3px rgba(0, 153, 51, .15);
        }

        /* BUTTON */

        .btn-submit {
            width: 100%;
            height: 48px;
            border: none;
            border-radius: 8px;
            background: linear-gradient(90deg, #00b300, #008000);
            color: white;
            font-weight: 600;
            cursor: pointer;
        }

        .btn-submit:hover {
            box-shadow: 0 8px 18px rgba(0, 0, 0, .2);
        }

        /* ERROR */

        .error {
            font-size: 13px;
            color: #e74c3c;
            margin-top: 5px;
        }

        /* VERSION */

        .app-version {
            margin-top: 15px;
            font-size: 12px;
            color: #999;
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
    </style>

</head>

<body>

    <!-- LEFT PANEL -->

    <div class="login-left"></div>

    <!-- RIGHT PANEL -->

    <div class="login-right">

        <div class="login-card">

            <img src="{{ asset('theme/img/logo/logo-den-id-trans.png') }}" class="logo">

            <div class="title">
                Reset Password
            </div>

            <form method="POST" action="{{ route('password.update') }}">

                @csrf

                <input type="hidden" name="token" value="{{ $token }}">

                <div class="form-group">

                    <input type="email" name="email" class="form-control" placeholder="Email"
                        value="{{ request()->email }}" required>

                    @error('email')
                        <div class="error">{{ $message }}</div>
                    @enderror

                </div>


                <div class="form-group">

                    <input type="password" name="password" class="form-control" placeholder="Password Baru" required>

                    @error('password')
                        <div class="error">{{ $message }}</div>
                    @enderror

                </div>


                <div class="form-group">

                    <input type="password" name="password_confirmation" class="form-control"
                        placeholder="Konfirmasi Password" required>

                </div>


                <button class="btn-submit">
                    Reset Password
                </button>

            </form>

            <div class="app-version">
                Version 3.1.1
            </div>

        </div>

    </div>

</body>

</html>
