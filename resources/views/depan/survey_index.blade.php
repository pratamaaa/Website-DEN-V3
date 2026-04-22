<!DOCTYPE html>
<html lang="id">

<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Survey Kepuasan Masyarakat - Dewan Energi Nasional</title>
   <link rel="shortcut icon" href="{{ asset('theme/img/logo/favicon.png') }}" type="image/x-icon" />
   <link rel="apple-touch-icon" href="{{ asset('theme/img/logo/favicon.png') }}">
   <style>
      /* Reset CSS dasar */
      * {
         margin: 0;
         padding: 0;
         box-sizing: border-box;
      }

      body {
         font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
         height: 100vh;
         overflow: hidden;
         /* Agar tidak ada scrollbar */
      }

      /* Container Utama */
      .wrapper {
         /* Menggunakan gambar background Anda */
         background-image: url("{{ asset('assets_survey/bg-office.jpeg') }}");
         background-size: cover;
         /* Cover agar gambar memenuhi layar */
         background-position: center;
         background-repeat: no-repeat;

         width: 100%;
         height: 100%;

         /* Flexbox untuk menengahkan konten */
         display: flex;
         flex-direction: column;
         justify-content: space-between;
         /* Memisahkan konten utama dan footer */
         align-items: center;
      }

      /* Area Konten Tengah (Logo + Kotak Hijau) */
      .content-center {
         flex: 1;
         /* Mengambil sisa ruang agar footer terdorong ke bawah */
         display: flex;
         flex-direction: column;
         justify-content: center;
         align-items: center;
         width: 100%;
         padding: 20px;
      }

      /* Styling Logo */
      .logo {
         width: 120px;
         /* Sesuaikan ukuran logo */
         height: auto;
         margin-bottom: 20px;
         /* Drop shadow agar logo terlihat jelas di atas background */
         filter: drop-shadow(0 4px 6px rgba(0, 0, 0, 0.3));
      }

      /* Kotak Hijau Utama */
      .green-card {
         background-color: #28a745;
         /* Warna hijau mirip contoh */
         color: white;
         padding: 30px 50px;
         border-radius: 15px;
         text-align: center;
         box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
         max-width: 800px;
         width: 90%;

         /* Sedikit efek gradient agar terlihat modern */
         background: linear-gradient(135deg, #2eb85c 0%, #228e3b 100%);
      }

      .green-card h1 {
         font-size: 24px;
         font-weight: 600;
         margin-bottom: 5px;
         line-height: 1.4;
      }

      .green-card h2 {
         font-size: 28px;
         font-weight: 700;
         margin-top: 5px;
      }

      /* Footer Hitam di Bawah */
      .footer {
         width: 100%;
         background-color: rgba(0, 0, 0, 0.7);
         /* Hitam transparan */
         color: white;
         text-align: center;
         padding: 15px;
         font-size: 14px;
         font-weight: 500;
      }

      /* Responsif untuk layar HP */
      @media (max-width: 768px) {
         .green-card {
            padding: 20px;
         }

         .green-card h1 {
            font-size: 18px;
         }

         .green-card h2 {
            font-size: 22px;
         }

         .logo {
            width: 90px;
         }
      }
   </style>
</head>

<body>

   <div class="wrapper">

      <div class="content-center">
         <img src="{{ asset('assets_survey/logo_den_transparant.png') }}" alt="Logo DEN" class="logo">

         <div class="green-card">
            <h1>Sekretariat Jenderal<br>Dewan Energi Nasional</h1>
            <hr style="border: 0; border-top: 1px solid rgba(255,255,255,0.3); margin: 10px auto; width: 50%;">
            <h2>Survey Kepuasan Masyarakat</h2>
         </div>
      </div>

      <div class="footer">
         Dewan Energi Nasional | {{ date('Y') }}
      </div>

   </div>

</body>

</html>
