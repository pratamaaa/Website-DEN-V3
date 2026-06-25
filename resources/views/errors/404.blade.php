<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Halaman Tidak Ditemukan | DEN</title>
    <link rel="shortcut icon" href="{{ asset('theme/img/logo/den.png') }}" />
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            background: #f0f4f0;
            overflow: hidden;
        }

        .error-wrapper {
            width: 100%;
            max-width: 1200px;
            min-height: 100vh;
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Gambar konsep sebagai full background */
        .error-bg {
            position: fixed;
            inset: 0;
            width: 100vw;
            height: 100vh;
            object-fit: contain;
            background: #f4f7fb;
        }

        /* Overlay tipis supaya tombol tetap terbaca */
        .error-overlay {
            position: fixed;
            inset: 0;
            background: rgba(255, 255, 255, 0.08);
            z-index: 1;
        }

        /* Tombol aksi di atas gambar */
        .error-actions {
            position: fixed;
            bottom: 40px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 10;
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
            justify-content: center;
        }

        .btn-home {
            background: #1a5c2a;
            color: #fff;
            padding: 12px 28px;
            border-radius: 30px;
            text-decoration: none;
            font-size: 14px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            box-shadow: 0 4px 16px rgba(26, 92, 42, 0.4);
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .btn-home:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(26, 92, 42, 0.5);
            color: #fff;
            text-decoration: none;
        }

        .btn-back {
            background: rgba(255, 255, 255, 0.9);
            color: #1a5c2a;
            padding: 12px 28px;
            border-radius: 30px;
            text-decoration: none;
            font-size: 14px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.15);
            transition: transform 0.2s, box-shadow 0.2s;
            border: 2px solid #1a5c2a;
        }

        .btn-back:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.2);
            color: #1a5c2a;
            text-decoration: none;
        }

        @media (max-width: 600px) {
            .error-bg {
                object-position: 70% center;
            }

            .error-actions {
                bottom: 24px;
                width: 90%;
            }

            .btn-home,
            .btn-back {
                flex: 1;
                justify-content: center;
                padding: 12px 16px;
            }
        }
    </style>
</head>

<body>

    <img src="{{ asset('theme/img/error-404.png') }}" alt="404" class="error-bg">
    <div class="error-overlay"></div>

    <div class="error-actions">
        <a href="/" class="btn-home">
            &#8962; Kembali ke Beranda
        </a>
        <a href="javascript:history.back()" class="btn-back">
            &#8592; Halaman Sebelumnya
        </a>
    </div>

</body>

</html>
