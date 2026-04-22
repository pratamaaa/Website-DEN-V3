<!doctype html>
<html lang="en">

<head>

    <title>Lupa Password | Sistem Informasi DEN</title>

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
            display: flex;
            align-items: center;
            justify-content: center;
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

        /* LINK */

        .back-login {
            display: block;
            margin-top: 15px;
            font-size: 13px;
            color: #666;
            text-decoration: none;
        }

        .back-login:hover {
            color: #009933;
        }

        /* ALERT */

        .alert {
            padding: 10px;
            background: #d4edda;
            border-radius: 6px;
            font-size: 13px;
            margin-bottom: 15px;
        }

        .error {
            color: #e74c3c;
            font-size: 13px;
            margin-top: 5px;
            text-align: left;
        }

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

    <!-- LEFT -->

    <div class="login-left"></div>

    <!-- RIGHT -->

    <div class="login-right">

        <div class="login-card">

            <img src="{{ asset('theme/img/logo/logo-den-id-trans.png') }}" class="logo">

            <div class="title">
                Lupa Password
            </div>

            @if (session('success'))
                <div class="alert">
                    {{ session('success') }}
                </div>
            @endif

            <form method="POST" action="{{ route('password.email') }}">

                @csrf

                <div class="form-group">

                    <input type="email" name="email" class="form-control" placeholder="Masukkan email anda"
                        value="{{ old('email') }}" required>

                    @error('email')
                        <div class="error">{{ $message }}</div>
                    @enderror

                </div>

                <button class="btn-submit">
                    Kirim Link Reset Password
                </button>

            </form>

            <a href="{{ url('/kelola') }}" class="back-login">
                ← Kembali ke Login
            </a>

            <div class="app-version">
                Version 3.1.1
            </div>

        </div>

    </div>

</body>

</html>
