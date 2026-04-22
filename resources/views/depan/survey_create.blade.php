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
                  <h1 class="font-weight-bold text-6 warna-putih" style="color: white;">{{ $layanan->kuesioner_layanan_nama ?? 'Survei Kepuasan' }}</h1>
               </div>
            </div>
         </div>
      </section>

      <div class="container py-4 page-content-full">
         {{-- BUNGKUS SELURUH KONTEN STEP DALAM FORM UNTUK SUBMIT AKHIR --}}
         <form id="surveyForm" method="POST" action="{{ url('/survey/save') }}" novalidate>
            @csrf
            <div id="steps-container">

               {{-- STEP 0: Data Responden --}}
               <div class="step">
                  <h4 class="mb-4">Data Responden</h4>

                  <div class="contact-form-success alert alert-success d-none mt-4">
                     <strong>Success!</strong> Your message has been sent to us.
                  </div>

                  <div class="contact-form-error alert alert-danger d-none mt-4">
                     <strong>Error!</strong> There was an error sending your message.
                     <span class="mail-error-message text-1 d-block"></span>
                  </div>
                  <input type="hidden" name="kuesioner_responden_uuid" value="{{ Str::uuid() }}">
                  <input type="hidden" name="kuesioner_layanan_uuid" value="{{ $layanan->kuesioner_layanan_uuid }}">
                  <input type="hidden" name="kuesioner_layanan_nama" value="{{ $layanan->kuesioner_layanan_nama }}">

                  <div class="row">
                     <div class="form-group col-lg-12">
                        <label class="form-label mb-1 text-2">Nama Lengkap</label>
                        <input type="text" value="" data-msg-required="Please enter your name." maxlength="100" class="form-control text-3 h-auto py-2" name="kuesioner_responden_nama" id="kuesioner_responden_nama" required>
                     </div>
                  </div>
                  <div class="row">

                     <div class="form-group col-lg-6">
                        <label class="form-label mb-1 text-2">Email</label>
                        <input type="email" value="" data-msg-required="Please enter your email address." data-msg-email="Please enter a valid email address." maxlength="100" class="form-control text-3 h-auto py-2" name="kuesioner_responden_email" id="kuesioner_responden_email" required>
                     </div>
                     <div class="form-group col-lg-6">
                        <label class="form-label mb-1 text-2">Telp</label>
                        <input type="text" value="" data-msg-required="Please enter your name." maxlength="100" class="form-control text-3 h-auto py-2" name="kuesioner_responden_telp" id="kuesioner_responden_telp" required>
                     </div>
                  </div>
                  {{-- 1. INSTANSI ASAL --}}
                  <div class="row">
                     <div class="form-group col">
                        <label class="form-label">Instansi Asal</label>
                        <div class="custom-select-1">
                           {{-- Name diganti jadi _uuid --}}
                           <select class="form-select form-control h-auto py-2" name="kuesioner_responden_instansi_asal_uuid" id="kuesioner_responden_instansi_asal_uuid" required onchange="setInstansi(this)">
                              <option value="" data-label="">- Pilih Instansi -</option>
                              @if (isset($ref_instansi))
                                 @foreach ($ref_instansi as $ins)
                                    {{-- Value = UUID, Data-Label = Nama (untuk JS) --}}
                                    <option value="{{ $ins->referensi_uuid }}" data-label="{{ $ins->referensi_nama }}">
                                       {{ $ins->referensi_nama }}
                                    </option>
                                 @endforeach
                              @endif
                           </select>
                        </div>
                     </div>
                  </div>

                  {{-- 2. APK (Pemangku Kepentingan) --}}
                  <div class="row apk" style="display: none">
                     <div class="form-group col">
                        <label class="form-label">Anggota Pemangku Kepentingan dari Kalangan?</label>
                        <div class="custom-select-1">
                           <select class="form-select form-control h-auto py-2" name="kuesioner_responden_pemangku_kepentingan_uuid" id="kuesioner_responden_pemangku_kepentingan_uuid">
                              <option value="">- Pilih Kalangan -</option>
                              @if (isset($ref_apk))
                                 @foreach ($ref_apk as $apk)
                                    <option value="{{ $apk->referensi_uuid }}">{{ $apk->referensi_nama }}</option>
                                 @endforeach
                              @endif
                           </select>
                        </div>
                     </div>
                  </div>

                  {{-- 3. AP (Kementerian) --}}
                  <div class="row ap" style="display: none">
                     <div class="form-group col">
                        <label class="form-label">Anggota Pemerintah Kementerian / Lembaga?</label>
                        <div class="custom-select-1">
                           <select class="form-select form-control h-auto py-2" name="kuesioner_responden_kementerian_lembaga_uuid" id="kuesioner_responden_kementerian_lembaga_uuid">
                              <option value="">- Pilih Kementerian -</option>
                              @if (isset($ref_kementerian))
                                 @foreach ($ref_kementerian as $kem)
                                    <option value="{{ $kem->referensi_uuid }}">{{ $kem->referensi_nama }}</option>
                                 @endforeach
                              @endif
                           </select>
                        </div>
                     </div>
                  </div>

               </div>

               {{-- STEP 1 dst: Pertanyaan Survei --}}
               @foreach ($pertanyaan as $header)
                  <div class="step">
                     <h4 class="mb-4">{{ $header->kuesioner_pertanyaan_kode }}. {{ $header->kuesioner_pertanyaan_nama }}</h4>

                     <ol class="list-kode ms-4">
                        @foreach ($header->children as $p)
                           <li data-kode="{{ $p->kuesioner_pertanyaan_kode }}">
                              {{ $p->kuesioner_pertanyaan_nama }}

                              <input type="hidden" name="pertanyaan_{{ $p->kuesioner_pertanyaan_uuid }}" value="{{ $p->kuesioner_pertanyaan_id }}">
                              @if ($p->kuesioner_pertanyaan_is_icon == 1)
                                 {{-- Container Jawaban Horizontal (Flexbox) --}}
                                 <div class="option-ordened mt-2">
                                    @foreach ($p->jawaban as $j)
                                       <label class="option-item">
                                          {{-- Input Radio (Wajib ada untuk menyimpan nilai) --}}
                                          <input class="option-radio" type="radio" required name="jawaban[{{ $p->kuesioner_pertanyaan_uuid }}][kuesioner_jawaban_uuid]" value="{{ $j->kuesioner_jawaban_uuid }}">
                                          @if ($p->kuesioner_pertanyaan_is_icon == 1)
                                             {{-- Tampilan ICON --}}
                                             <span class="option-icon-container">
                                                <img src="{{ asset($j->kuesioner_jawaban_icon) }}" alt="{{ $j->kuesioner_jawaban_nama }}">
                                             </span>
                                          @else
                                             {{-- Tampilan NUMBER --}}
                                             <span class="option-number">{{ $j->kuesioner_jawaban_code }}</span>
                                          @endif

                                          <span class="option-text">{{ $j->kuesioner_jawaban_nama }}</span>
                                       </label>
                                    @endforeach
                                 </div>
                              @else
                                 @foreach ($p->jawaban as $j)
                                    <label class="option-item">
                                       <input class="option-radio" type="radio" required name="jawaban[{{ $p->kuesioner_pertanyaan_uuid }}][kuesioner_jawaban_uuid]" value="{{ $j->kuesioner_jawaban_uuid }}">
                                       <span class="option-text">{{ $j->kuesioner_jawaban_nama }}</span>
                                    </label>
                                 @endforeach
                              @endif
                           </li>
                        @endforeach
                     </ol>
                  </div>
               @endforeach


               {{-- STEP TAMBAHAN: Saran & Masukan (Kondisional) --}}
               @if ($layanan->kuesioner_layanan_is_saran == 1)
                  <div class="step">
                     <h4 class="mb-4">Saran & Masukan</h4>
                     <p class="text-muted">Masukan saran dan masukan Anda untuk peningkatan kualitas layanan kami.</p>

                     <div class="form-group">
                        <label class="form-label font-weight-bold" for="kuesioner_responden_saran">Saran & Masukan</label>
                        {{-- Hapus attribute 'required' jika saran bersifat opsional --}}
                        <textarea class="form-control" name="kuesioner_responden_saran" id="kuesioner_responden_saran" rows="6" placeholder="Tuliskan saran Anda di sini..."></textarea>
                     </div>
                  </div>
               @endif


               {{-- Progress Bar --}}
               <div class="progress mb-4 mt-4" style="height: 20px;">
                  <div id="progressBar" class="progress-bar bg-success" role="progressbar" style="width: 0%;">
                     0%
                  </div>
               </div>

               {{-- Tombol Navigasi --}}
               <div class="mt-4 d-flex justify-content-end">
                  <button id="prevBtn" class="btn btn-outline btn-danger me-2" style="display:none;" type="button">
                     Sebelumnya
                  </button>

                  <button id="nextBtn" class="btn btn-outline btn-success" type="button">
                     Selanjutnya
                  </button>
               </div>
            </div>
         </form>
      </div>
      <div class="modal fade" id="largeModal" tabindex="-1" aria-hidden="true">
         <div class="modal-dialog modal-lg">
            <div class="modal-content">
               <div class="modal-header">
                  <h5 class="modal-title">Tata Cara Pengisian Survey</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
               </div>
               <div class="modal-body">
                  <p class="mb-3 text-muted">
                     Terima kasih atas partisipasi Anda. Untuk memudahkan pengisian, mohon perhatikan langkah-langkah berikut:
                  </p>

                  <ol class="list-group list-group-numbered mb-3">
                     <li class="list-group-item d-flex justify-content-between align-items-start">
                        <div class="ms-2 me-auto">
                           <div class="fw-bold">Lengkapi Data Responden</div>
                           Silakan isi data diri Anda dengan benar pada halaman awal. Data ini diperlukan untuk validitas survey.
                        </div>
                     </li>
                     <li class="list-group-item d-flex justify-content-between align-items-start">
                        <div class="ms-2 me-auto">
                           <div class="fw-bold">Isi Pertanyaan Survey</div>
                           Setelah data diri terisi, lanjut ke bagian kuesioner. Pilihlah jawaban yang paling menggambarkan kepuasan Anda terhadap pelayanan kami.
                        </div>
                     </li>
                     <li class="list-group-item d-flex justify-content-between align-items-start">
                        <div class="ms-2 me-auto">
                           <div class="fw-bold">Kirim Jawaban</div>
                           Pastikan seluruh pertanyaan telah dijawab, kemudian klik tombol "Kirim" atau "Selesai" untuk menyimpan data.
                        </div>
                     </li>
                  </ol>

                  <div class="alert alert-info d-flex align-items-center" role="alert">
                     <i class="bi bi-shield-lock-fill me-2"></i>
                     <div>
                        <strong>Jaminan Privasi:</strong><br>
                        Data pribadi Anda akan dijaga kerahasiaannya dan hanya digunakan untuk keperluan evaluasi pelayanan.
                     </div>
                  </div>
               </div>
               <div class="modal-footer">
                  <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
               </div>
            </div>
         </div>
      </div>

   </div>

   {{-- Asumsi Anda sudah memuat jQuery di layout utama, jika belum, tambahkan di sini --}}
   {{-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> --}}

   <script>
      // ---------------------------------------------------
      // FUNGSI BLOK KONDISIONAL
      // ---------------------------------------------------
      function setInstansi(ini) {
         // Kita ambil atribut 'data-label' dari option yang dipilih, BUKAN value-nya (karena value isinya UUID)
         var labelText = $(ini).find(':selected').data('label');

         // Reset
         $('.apk').hide().find('select').removeAttr('required');
         $('.ap').hide().find('select').removeAttr('required');

         // Logic tetap sama, membandingkan Teks Label
         if (labelText == 'Anggota Pemangku Kepentingan (APK) DEN') {
            $('.apk').show().find('select').attr('required', 'required');
         } else if (labelText == 'Anggota Pemerintah (AP) DEN / Kementerian') {
            $('.ap').show().find('select').attr('required', 'required');
         }
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

            // 1. Validasi Input Standar (Text, Select, Textarea) yang REQUIRED di STEP INI
            // Ini akan mencakup Step 0 (Data Diri) DAN Step Saran (jika textarea dikasih required)
            $currentStep.find('input[required], select[required], textarea[required]').each(function() {
               if (!$(this).val() || $(this).val().trim() === '') {
                  // Beri efek visual error (opsional)
                  $(this).addClass('is-invalid');
                  valid = false;
               } else {
                  $(this).removeClass('is-invalid');
               }
            });

            if (!valid) return false; // Jika input standar gagal, stop di sini

            // 2. Validasi Khusus Pertanyaan Survei (Radio Button)
            // Hanya dijalankan jika di step ini terdapat pertanyaan (li[data-kode])
            if ($currentStep.find("li[data-kode]").length > 0) {
               $currentStep.find("li[data-kode]").each(function() {
                  let name = $(this).find(".option-radio").attr("name");

                  // Pastikan radio button untuk nama ini sudah dicentang
                  if ($("input[name='" + name + "']:checked").length === 0) {
                     // Highlight pertanyaan yang belum diisi (opsional)
                     $(this).css("border", "1px solid red");
                     valid = false;
                     // return false di sini hanya break loop .each, bukan function validateStep
                  } else {
                     $(this).css("border", "none"); // Reset style
                  }
               });
            }

            return valid;
         }

         // Panggil setInstansi untuk inisialisasi tampilan blok APK/AP
         $('#kuesioner_responden_instansi_asal_uuid').trigger('change');
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
