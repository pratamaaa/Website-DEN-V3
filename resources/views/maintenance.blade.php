```blade
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Under Maintenance - Dewan Energi Nasional</title>
    <link rel="shortcut icon" href="{{ asset('theme/img/logo/den.png') }}" />
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            min-height: 100vh;
            font-family: "Segoe UI", sans-serif;
            background: linear-gradient(135deg, #0d1b2a, #1b263b);
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 30px;
        }

        .container {
            width: 100%;
            max-width: 1200px;
            text-align: center;
        }

        .card {
            background: #ffffff;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 15px 40px rgba(0, 0, 0, .35);
        }

        .card img {
            width: 100%;
            display: block;
        }

        .footer {
            padding: 20px;
            background: #f8f9fa;
            border-top: 1px solid #e5e5e5;
        }

        .footer h2 {
            color: #0d1b2a;
            margin-bottom: 8px;
            font-size: 28px;
        }

        .footer p {
            color: #666;
            font-size: 15px;
        }

        .badge {
            display: inline-block;
            margin-top: 12px;
            background: #ffc107;
            color: #222;
            padding: 8px 18px;
            border-radius: 50px;
            font-weight: 600;
        }

        @media(max-width:768px) {
            .footer h2 {
                font-size: 22px;
            }

            .footer p {
                font-size: 14px;
            }
        }
    </style>
</head>

<body>

    <div class="container">

        <div class="card">

            <img src="{{ asset('theme/img/maintenance.png') }}" alt="Website Maintenance">

            <div class="footer">


                <div class="badge">
                    Dewan Energi Nasional | 2026
                </div>
            </div>

        </div>

    </div>

</body>

</html>
```
