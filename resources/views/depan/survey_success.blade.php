@extends('layout.depan.layout_survey')

@section('content')
   <style>
      /* 🎨 CSS CUSTOM/MODIFIED START */

      /* List Pertanyaan Utama */
      .list-kode {
         list-style: none !important;
         padding-left: 0;
      }

      .list-kode>li {
         position: relative;
         padding-left: 2rem;
      }

      .list-kode>li::before {
         content: attr(data-kode) ". ";
         position: absolute;
         left: 0;
         top: 0;
         font-weight: bold;
      }

      /* Opsi Jawaban (Container Horizontal - Untuk Sejajar 4) */
      .option-ordened {
         display: flex;
         justify-content: space-around;
         /* Menyebar item secara merata */
         gap: 10px;
         flex-wrap: wrap;
         padding: 10px 0;
      }

      /* Item Opsi (Label) */
      .option-item {
         display: flex;
         flex-direction: column;
         align-items: center;
         cursor: pointer;

         /* PENTING: Untuk dibagi 4, gunakan flex-basis/width */
         flex: 1 1 20%;
         max-width: 24%;

         margin-bottom: 12px;
         border: 2px solid #ccc;
         /* Border default abu-abu */
         border-radius: 8px;
         padding: 8px;
         transition: all 0.2s ease-in-out;
      }

      /* Input Radio Tersembunyi */
      .option-item .option-radio {
         display: none;
      }

      /* Icon/Image Container */
      .option-icon-container {
         width: 60px;
         height: 60px;
         border-radius: 50%;
         display: flex;
         align-items: center;
         justify-content: center;
         margin-bottom: 8px;
         overflow: hidden;
      }

      .option-icon-container img {
         width: 100%;
         height: 100%;
         object-fit: cover;

         /* DEFAULT: ICON HITAM-PUTIH */
         filter: grayscale(100%);
         transition: filter 0.3s ease;
      }

      /* Number Container (untuk pertanyaan non-ikon) */
      .option-item .option-number {
         margin-right: 0;
         margin-bottom: 8px;
         width: 40px;
         height: 40px;
         border-radius: 50%;
         border: 1px solid #27ae60;
         display: flex;
         align-items: center;
         justify-content: center;
         font-size: 14px;
         font-weight: bold;
         color: #27ae60;
         transition: all 0.2s;
      }

      /* Teks Item (DEFAULT) */
      .option-item .option-text {
         font-size: 0.85rem;
         color: #555;
      }

      /* PENTING BARU: HIGHLIGHT SAAT HOVER (Sebelum dipilih) */
      .option-item:hover .option-icon-container img {
         filter: grayscale(0%) !important;
         /* Kembalikan ke warna asli */
      }

      .option-item:hover {
         border-color: #a8a8a8;
         /* Border sedikit abu-abu */
      }

      /* PENTING 2: ICON KEMBALI BERWARNA SETELAH DIPILIH */
      .option-item .option-radio:checked+.option-icon-container img {
         filter: grayscale(0%) !important;
         /* Kembalikan ke warna asli (PENTING: Gunakan !important jika hover tidak berfungsi) */
      }

      /* Highlight Number saat Radio DICENTANG */
      .option-item .option-radio:checked+.option-number {
         background: #27ae60;
         color: #fff;
      }

      /* Highlight Teks saat Radio DICENTANG */
      .option-item .option-radio:checked~.option-text {
         font-weight: bold;
         color: #27ae60;
      }

      /* Highlight border luar untuk seluruh option-item saat dipilih (Ikon atau Number) */
      .option-item:has(.option-radio:checked) {
         border-color: #27ae60 !important;
         background-color: #e8f5e9;
      }

      /* Perbaikan CSS Step untuk Transisi yang Halus */
      .step {
         opacity: 0;
         transform: translateY(15px);
         transition: all .35s ease;
         position: absolute;
         width: 100%;
         pointer-events: none;
         display: none;
      }

      .step.active {
         opacity: 1;
         transform: translateY(0);
         position: relative;
         pointer-events: auto;
         display: block !important;
      }

      /* Container anti flicker */
      #steps-container {
         position: relative;
         min-height: calc(100vh - var(--header-height, 0px) - var(--page-header-height, 0px) - var(--footer-height, 0px) - 50px);
      }

      /* 🎨 CSS CUSTOM/MODIFIED END */
   </style>

   {{-- SISA HTML DAN JAVASCRIPT TETAP SAMA SEPERTI SKRIP LENGKAP TERAKHIR --}}

   <div role="main" class="main">
      <section class="page-header page-header-modern bg-color-light-scale-1 page-header-md mb-0" style="background: url('{{ asset('theme/img/ndr-banner-green.webp') }}'); background-size:cover; background-position: 0 100%; height: 200px;">
         <div class="container">
            <div class="row">
               <div class="col-md-12 align-self-center p-static order-2 text-center">
                  <h1 class="font-weight-bold text-6 warna-putih" style="color: white;">{{ session('layanan_nama') }}</h1>
               </div>
            </div>
         </div>
      </section>

      <div class="container py-4 page-content-full" style="min-height: 50vh">

            <h4 class="mb-3">Terima kasih <strong>{{ session('responden_nama') }}</strong></h4>

            <p class="fw-bold">
               telah mengisi survey tentang {{ session('layanan_nama') }}
            </p>
      </div>
   </div>

   {{-- Asumsi Anda sudah memuat jQuery di layout utama, jika belum, tambahkan di sini --}}
   {{-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> --}}

   <script>
      // ---------------------------------------------------
      // FUNGSI BLOK KONDISIONAL
      // ---------------------------------------------------
      function setInstansi(ini) {
         var val = $(ini).val();

         // Atur required untuk input responden yang relevan
         // Note: Untuk select/input di blok yang HIDE, attribute 'required' dihapus, 
         // lalu ditambahkan lagi saat blok di SHOW.

         // Sembunyikan semua dan hapus required
         $('.apk').hide().find('select').removeAttr('required');
         $('.ap').hide().find('select').removeAttr('required');

         if (val == 'Anggota Pemangku Kepentingan (APK) DEN') {
            $('.apk').show().find('select').attr('required', 'required');
         } else if (val == 'Anggota Pemerintah (AP) DEN / Kementerian') {
            $('.ap').show().find('select').attr('required', 'required');
         }
         // Untuk "Lainnya" atau default, kedua blok tetap tersembunyi
      }


      // ---------------------------------------------------
      // LOGIKA MULTI-STEP SURVEI
      // ---------------------------------------------------
      $(document).ready(function() {

         let currentStep = 0;
         let $steps = $(".step");
         let totalSteps = $steps.length;

         // Pertanyaan dihitung dari elemen <li> dengan data-kode (Hanya pertanyaan survei)
         const totalQuestions = $("li[data-kode]").length;

         // Inisialisasi Tampilan Awal
         $steps.eq(0).addClass("active");
         updateProgressBar();


         // Panggil updateProgressBar setiap kali radio button dipilih/diubah
         $(".option-radio").on("change", function() {
            updateProgressBar();
         });

         // NEXT BUTTON
         $("#nextBtn").on("click", function() {

            // Validasi HANYA step saat ini
            if (!validateStep(currentStep)) {
               alert("Harap isi semua kolom/jawaban pada bagian ini sebelum melanjutkan.");
               return;
            }

            // Jika ini adalah step terakhir
            if (currentStep === totalSteps - 1) {
               // Cek apakah semua pertanyaan survei sudah terjawab
               if (answeredQuestionsCount() < totalQuestions) {
                  alert("Ada pertanyaan survei yang belum terjawab (Progress: " + Math.round((answeredQuestionsCount() / totalQuestions) * 100) + "%). Silakan periksa kembali.");
                  return;
               }

               // Kirim (submit) formulir utama
               $("#surveyForm").submit();
               return;
            }

            goToStep(currentStep + 1);
         });

         // PREV BUTTON
         $("#prevBtn").on("click", function() {
            goToStep(currentStep - 1);
         });

         // NAVIGATION HANDLER
         function goToStep(target) {

            // Hapus kelas aktif dari step saat ini (untuk fade out)
            $steps.eq(currentStep).removeClass("active").fadeOut(150, function() {
               // Tambahkan kelas aktif ke step target setelah yang lama fade out
               $steps.eq(target).addClass("active").fadeIn(150);
            });

            currentStep = target;

            // Update Tombol Navigasi
            $("#prevBtn").toggle(currentStep > 0);
            $("#nextBtn").text(currentStep === totalSteps - 1 ? "Simpan & Kirim" : "Selanjutnya");

            updateProgressBar();
         }

         // FUNGSI PENDUKUNG PROGRESS BAR
         function answeredQuestionsCount() {
            // Menghitung nama pertanyaan unik yang sudah dijawab
            let answeredNames = new Set();
            $("input.option-radio:checked").each(function() {
               answeredNames.add($(this).attr('name'));
            });
            return answeredNames.size;
         }

         function updateProgressBar() {
            const answered = answeredQuestionsCount();

            let percent = 0;
            if (totalQuestions > 0) {
               // Hitung persentase berdasarkan jumlah jawaban yang terisi
               percent = Math.round((answered / totalQuestions) * 100);
            }

            $("#progressBar")
               .css("width", percent + "%")
               .text(percent + "%");
         }

         // VALIDASI UNTUK STEP SAAT INI
         function validateStep(stepIndex) {
            let valid = true;
            let $currentStep = $steps.eq(stepIndex);

            // 1. Validasi Input Responden (Step 0)
            if (stepIndex === 0) {
               $currentStep.find('input[required], select[required]').each(function() {
                  // Check required input di step 0
                  if (!$(this).val() || $(this).val().trim() === '') {
                     valid = false;
                     return false; // Keluar dari loop
                  }
               });
            }
            // 2. Validasi Pertanyaan Survei (Step > 0)
            else {
               // Cari semua LI pertanyaan di step saat ini
               $currentStep.find("li[data-kode]").each(function() {
                  let name = $(this).find(".option-radio").attr("name");

                  // Pastikan radio button untuk nama ini sudah dicentang
                  if ($("input[name='" + name + "']:checked").length === 0) {
                     valid = false;
                     return false; // Keluar dari loop .each() li
                  }
               });
            }

            return valid;
         }

         // Panggil setInstansi untuk inisialisasi tampilan blok APK/AP
         $('select[name="kuesioner_responden_instansi_asal"').trigger('change');
      });

      // Penyesuaian tinggi footer (kode dari Anda)
      $(document).ready(function() {
         let headerHeight = $("#header").outerHeight() || 0;
         let pageheaderHeight = $("section.page-header").outerHeight() || 0;
         let footerHeight = $("#footer").outerHeight() || 0;

         document.documentElement.style.setProperty("--header-height", headerHeight + "px");
         document.documentElement.style.setProperty("--page-header-height", pageheaderHeight + "px");
         document.documentElement.style.setProperty("--footer-height", footerHeight + "px");
      });
   </script>
@endsection
