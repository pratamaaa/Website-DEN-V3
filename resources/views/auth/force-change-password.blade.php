<!doctype html>
<html lang="en">

<head>

    <title>Ganti Password | Sistem Informasi DEN</title>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="shortcut icon" href="{{ asset('theme/img/logo/favicon.png') }}">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        .form-control.is-invalid {
            border-color: #e74c3c;
            box-shadow: 0 0 0 3px rgba(231, 76, 60, .15);
        }

        .progress {
            background-color: #e9ecef;
            border-radius: 4px;
        }

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
        }

        .form-control:focus {
            outline: none;
            border-color: #009933;
            box-shadow: 0 0 0 3px rgba(0, 153, 51, .15);
        }

        .toggle-password {
            position: absolute;
            right: 14px;
            top: 14px;
            cursor: pointer;
            color: #777;
        }

        .btn-login {
            width: 100%;
            height: 48px;
            border: none;
            border-radius: 8px;
            background: linear-gradient(90deg, #00b300, #008000);
            color: white;
            font-weight: 600;
            cursor: pointer;
        }

        .invalid-feedback {
            font-size: 13px;
            color: #e74c3c;
            text-align: left;
            margin-top: 5px;
        }

        .alert {
            padding: 10px;
            border-radius: 6px;
            margin-bottom: 15px;
            font-size: 14px;
        }

        .alert-warning {
            background: #fff3cd;
            color: #856404;
        }

        .alert-success {
            background: #d4edda;
            color: #155724;
        }

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

    <div class="login-left"></div>

    <div class="login-right">

        <div class="login-card">

            <img src="{{ asset('theme/img/logo/logo-den-id-trans.png') }}" class="logo">

            <div class="title">
                Ganti Password
            </div>

            {{-- WARNING --}}
            @if (session('warning'))
                <div class="alert alert-warning">
                    {{ session('warning') }}
                </div>
            @endif

            {{-- SUCCESS --}}
            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <form action="{{ url('/force-change-password') }}" method="POST">
                @csrf

                {{-- PASSWORD BARU --}}
                <div class="form-group">
                    <div style="position: relative;">
                        <input type="password" id="password" name="password_baru"
                            class="form-control @error('password_baru') is-invalid @enderror"
                            placeholder="Password Baru">
                        <i class="fa fa-eye toggle-password" toggle="#password"></i>
                    </div>

                    <div class="progress mt-1" style="height: 6px; display:none;" id="strength-bar-wrap">
                        <div id="password-strength-bar" class="progress-bar" role="progressbar"></div>
                    </div>

                    <small id="password-rules" class="form-text text-muted"
                        style="display:none; text-align:left;"></small>

                    @error('password_baru')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- KONFIRMASI --}}
                <div class="form-group">
                    <div style="position: relative;">
                        <input type="password" id="password_confirmation" name="password_baru_confirmation"
                            class="form-control @error('password_baru_confirmation') is-invalid @enderror"
                            placeholder="Konfirmasi Password">
                        <i class="fa fa-eye toggle-password" toggle="#password_confirmation"></i>
                    </div>

                    @error('password_baru_confirmation')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <button class="btn-login">
                    SIMPAN PASSWORD
                </button>

            </form>

        </div>

    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        // Toggle show/hide password
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

        // Password strength checker
        $('#password').on('keyup', function() {
            let val = $(this).val();
            let strength = 0;

            if (val.length >= 12) strength++;
            if (/[a-z]/.test(val) && /[A-Z]/.test(val)) strength++;
            if (/[0-9]/.test(val)) strength++;
            if (/[^A-Za-z0-9]/.test(val)) strength++;

            let bar = $('#password-strength-bar');
            let rules = $('#password-rules');

            let html = '';
            html += (val.length >= 12 ? '✅' : '❌') + ' Minimal 12 karakter <br>';
            html += ((/[a-z]/.test(val) && /[A-Z]/.test(val)) ? '✅' : '❌') + ' Huruf besar & kecil <br>';
            html += ((/[0-9]/.test(val)) ? '✅' : '❌') + ' Angka <br>';
            html += ((/[^A-Za-z0-9]/.test(val)) ? '✅' : '❌') + ' Simbol';
            rules.html(html);

            if (strength <= 1) {
                bar.css('width', '25%').removeClass().addClass('progress-bar bg-danger');
            } else if (strength == 2) {
                bar.css('width', '50%').removeClass().addClass('progress-bar bg-warning');
            } else if (strength == 3) {
                bar.css('width', '75%').removeClass().addClass('progress-bar bg-info');
            } else {
                bar.css('width', '100%').removeClass().addClass('progress-bar bg-success');
            }
        });

        // Validasi sebelum submit
        $('form').on('submit', function(e) {
            let pass = $('#password').val();
            let confirm = $('#password_confirmation').val();
            let errors = [];

            if (pass.length < 12) errors.push('Password minimal 12 karakter');
            if (!/[a-z]/.test(pass) || !/[A-Z]/.test(pass)) errors.push('Password harus ada huruf besar & kecil');
            if (!/[0-9]/.test(pass)) errors.push('Password harus mengandung angka');
            if (!/[^A-Za-z0-9]/.test(pass)) errors.push('Password harus mengandung simbol');
            if (pass !== confirm) errors.push('Konfirmasi password tidak cocok');

            if (errors.length > 0) {
                e.preventDefault();
                alert('⚠️ ' + errors.join('\n'));
            }
        });
        $('#password').on('focus', function() {
            $('#strength-bar-wrap').show();
            $('#password-rules').show();
        });

        $('#password').on('blur', function() {
            if ($(this).val() === '') {
                $('#strength-bar-wrap').hide();
                $('#password-rules').hide();
            }
        });

        $('#password').on('keyup', function() {
            let val = $(this).val();
            let strength = 0;

            if (val.length >= 12) strength++;
            if (/[a-z]/.test(val) && /[A-Z]/.test(val)) strength++;
            if (/[0-9]/.test(val)) strength++;
            if (/[^A-Za-z0-9]/.test(val)) strength++;

            let bar = $('#password-strength-bar');
            let rules = $('#password-rules');

            let html = '';
            html += (val.length >= 12 ? '✅' : '❌') + ' Minimal 12 karakter<br>';
            html += ((/[a-z]/.test(val) && /[A-Z]/.test(val)) ? '✅' : '❌') + ' Huruf besar & kecil<br>';
            html += (/[0-9]/.test(val) ? '✅' : '❌') + ' Angka<br>';
            html += (/[^A-Za-z0-9]/.test(val) ? '✅' : '❌') + ' Simbol';
            rules.html(html);

            if (strength <= 1) {
                bar.css('width', '25%').removeClass().addClass('progress-bar bg-danger');
            } else if (strength == 2) {
                bar.css('width', '50%').removeClass().addClass('progress-bar bg-warning');
            } else if (strength == 3) {
                bar.css('width', '75%').removeClass().addClass('progress-bar bg-info');
            } else {
                bar.css('width', '100%').removeClass().addClass('progress-bar bg-success');
            }
        });
    </script>
</body>

</html>
