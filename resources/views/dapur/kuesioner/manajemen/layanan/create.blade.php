@extends('layout.dapur.app')

@section('content')
   <style>
      input.form-control,
      textarea.form-control,
      select.form-select {
         border-radius: 0 !important;
      }

      .input-group-text {
         border-radius: 0 !important;
      }
   </style>
   {{-- Tidak ada penambahan CSS kustom di sini --}}

   <section class="pcoded-main-container">
      <div class="pcoded-content">
         <div class="row">
            <div class="col-sm-12">
               <div class="card">
                  <div class="card-body">
                     <nav class="navbar" style="margin-left:-10px !important;">
                        <span class="m-r-15">
                           <h5>{{ $judulhalaman ?? 'Form Pembuatan Kuesioner Baru' }}</h5>
                        </span>
                     </nav>
                     <hr>

                     <form action="{{ url('/kuesioner/manajemen-layanan-create') }}" method="POST">
                        @csrf

                        <div class="card shadow-sm mb-4">
                           <div class="card-header text-white p-3" style="background: linear-gradient(to right, #000000, #0fb3c2); color:#ffffff !importance;">
                              <h5 class="mb-0 text-white">Data Utama Layanan</h5>
                           </div>
                           <div class="card-body mt-4">
                              <div class="row">
                                 <div class="col-md-4 mb-3"><label class="form-label">Tahun</label><input type="text" class="form-control datepicker-year" name="kuesioner_layanan_tahun" required></div>
                                 <div class="col-md-4 mb-3"><label class="form-label">Tanggal Mulai</label>
                                    <div class="datepicker date input-group">
                                       <input type="text" placeholder="Pilih tanggal" value="{{ date('Y/m/d') }}" class="form-control" id="kuesioner_layanan_tanggal_mulai" name="kuesioner_layanan_tanggal_mulai" required>
                                       <div class="input-group-append">
                                          <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                                       </div>
                                    </div>
                                 </div>
                                 <div class="col-md-4 mb-3"><label class="form-label">Tanggal Selesai</label>
                                    <div class="datepicker date input-group">
                                       <input type="text" placeholder="Pilih tanggal" value="{{ date('Y/m/d') }}" class="form-control" id="kuesioner_layanan_tanggal_selesai" name="kuesioner_layanan_tanggal_selesai">
                                       <div class="input-group-append">
                                          <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                                       </div>
                                    </div>
                                 </div>
                              </div>
                              <div class="row">
                                 <div class="col-md-12 mb-3">
                                    <label class="form-label">Nama Layanan</label><input type="text" class="form-control" name="kuesioner_layanan_nama" required>
                                 </div>
                              </div>
                              <div class="row">
                                 <div class="col-md-12 mb-3">
                                    <label class="form-label">Tampilkan Saran & Masukan ?</label>
                                    <select class="form-select form-control" name="kuesioner_layanan_is_saran" required>
                                       <option value="1">Ya</option>
                                       <option value="0">Tidak</option>
                                    </select>
                                 </div>
                              </div>
                           </div>
                        </div>

                        <div class="card shadow-sm">
                           <div class="card-header p-3 text-white d-flex justify-content-between align-items-center" style="background: linear-gradient(to right, #000000, #0fb3c2); color:#ffffff !importance;">
                              <h5 class="mb-0 text-white">Daftar Pertanyaan</h5>
                              <button type="button" class="btn btn-sm waves-effect waves-light btn-primary add-header-btn"><i class="feather icon-plus-square"></i> Tambah Parameter</button>
                           </div>
                           <div class="card-body">
                              <div id="questions-container">
                              </div>
                           </div>
                           <div class="card-header p-3 text-white d-flex justify-content-end align-items-center" style="background: linear-gradient(to right, #000000, #0fb3c2); color:#ffffff !importance;border-radius:0 0 calc(5px - 0px) calc(5px - 0px)">
                              <button type="button" class="btn btn-sm waves-effect waves-light btn-primary add-header-btn"><i class="feather icon-plus-square"></i> Tambah Parameter</button>
                           </div>
                        </div>

                        <div class="d-grid gap-2 mt-4">
                           <button type="submit" class="btn btn-info btn-sm"><i class="feather icon-save"></i> Simpan</button>
                           <div style="display: none;">
                              <select id="list_kuesioner_parameter_uuid" name="list_kuesioner_parameter_uuid">
                                 <option></option>
                                 @foreach ($KuesionerParameter as $p)
                                    <option value="{{ $p->kuesioner_parameter_uuid }}" data-code="{{ $p->kuesioner_parameter_code }}" data-name="{{ $p->kuesioner_parameter_nama }}">{{ $p->kuesioner_parameter_code }}. {{ $p->kuesioner_parameter_nama }}</option>
                                 @endforeach
                              </select>
                           </div>

                        </div>
                     </form>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </section>

   <script>
      // 1. DAFTAR TEMPLATE DARI DATABASE
      const availableTemplates = @json($templateGroups);
      console.log("Available Templates:", availableTemplates);

      function generateId() {
         return Date.now() + Math.random().toString(36).substr(2, 9);
      }

      // questionIndex tetap menjadi index global untuk memastikan KEY array PHP unik
      let questionIndex = 0;
      const container = document.getElementById('questions-container');
      const addHeaderButtons = document.querySelectorAll('.add-header-btn');

      // --- FUNGSI BARU: CEK DUPLIKASI PARAMETER ---
      function checkUsedParameters() {
         // 1. Kumpulkan semua value yang sedang terpilih
         let selectedValues = [];
         $('.parameter-select').each(function() {
            let val = $(this).val();
            if (val) {
               selectedValues.push(val);
            }
         });

         // 2. Loop setiap dropdown parameter untuk update status disabled
         $('.parameter-select').each(function() {
            let currentDropdown = $(this);
            let currentValue = currentDropdown.val();

            // Loop setiap option di dalam dropdown ini
            currentDropdown.find('option').each(function() {
               let optionValue = $(this).val();

               // Skip jika option kosong (placeholder)
               if (!optionValue) return;

               // Logic: Jika value ada di daftar terpilih, TAPI bukan value milik dropdown ini sendiri
               if (selectedValues.includes(optionValue) && optionValue !== currentValue) {
                  $(this).prop('disabled', true); // Disable
               } else {
                  $(this).prop('disabled', false); // Enable kembali
               }
            });
         });

         // 3. Refresh Select2 (Re-init) untuk menerapkan tampilan disabled
         $('.parameter-select').select2({
            width: '100%'
         });
      }


      // --- LOGIC PERBAIKAN PENOMORAN ---
      function renumberQuestions() {
         const headerCards = document.querySelectorAll('#questions-container > div[id^="question-"]');
         let headerCount = 0;

         headerCards.forEach((headerEl) => {
            // Cek apakah ini Header
            const isHeader = headerEl.querySelector(`input[name$="[is_header]"]`)?.value === 'true';

            if (isHeader) {
               headerCount++;
               const headerNum = headerCount;

               // 1. Update Judul Header
               const headerTitle = headerEl.querySelector('h5.text-success');
               if (headerTitle) {
                  headerTitle.textContent = `Paremeter #${headerNum}`;
               }

               // OPTIONAL: Jika ingin Kode Header juga otomatis (misal: 1, 2, 3), uncomment baris bawah ini:
               // headerEl.querySelector('input[name$="[kuesioner_pertanyaan_kode]"]').value = headerNum;

               const tempUuid = headerEl.querySelector(`input[name$="[temp_uuid]"]`).value;
               const subContainer = document.getElementById(`sub-questions-container-${tempUuid}`);

               if (subContainer) {
                  const subQuestionCards = subContainer.querySelectorAll('div[id^="question-"]');
                  let subCount = 0;

                  subQuestionCards.forEach((subEl) => {
                     subCount++;
                     const subNum = `${headerNum}.${subCount}`; // Contoh: 1.1, 1.2

                     // 2. Update Judul Pertanyaan (Visual)
                     const subTitle = subEl.querySelector('h6.text-primary');
                     if (subTitle) {
                        subTitle.textContent = `Pertanyaan #${subNum}`;
                     }

                     // 3. BARU: Update Input Kode Pertanyaan (Value)
                     // Mencari input yang name-nya berakhiran [kuesioner_pertanyaan_kode]
                     const codeInput = subEl.querySelector('input[name$="[kuesioner_pertanyaan_kode]"]');
                     if (codeInput) {
                        codeInput.value = subNum;
                        // Jika Anda ingin readonly agar user tidak bisa ubah, tambahkan:
                        // codeInput.readOnly = true; 
                     }
                  });

                  // Handle placeholder kosong
                  const placeholder = subContainer.querySelector('small.text-muted');
                  if (subQuestionCards.length === 0 && !placeholder) {
                     subContainer.innerHTML = '<small class="text-muted">Tambahkan pertanyaan di sini.</small>';
                  } else if (subQuestionCards.length > 0 && placeholder) {
                     placeholder.remove();
                  }
               }
            }
         });
      }

      // --- FUNGSI MANIPULASI DOM ---

      window.removeElement = function(id) {
         const el = document.getElementById(id);
         if (el) {
            el.remove();
            renumberQuestions();
            // Panggil cek parameter setelah delete
            checkUsedParameters();
            checkUsedAspects();
         }
      };


      function createHeaderForm() {
         const tempIndex = questionIndex++;
         const tempUuid = generateId();

         // PERHATIKAN: Saya menambahkan class 'parameter-select' pada tag select di bawah ini
         const headerHtml = `
            <div class="p-3 my-3 border border-success rounded bg-light" id="question-${tempIndex}">
                <input type="hidden" name="questions[${tempIndex}][is_header]" value="true">
                <input type="hidden" name="questions[${tempIndex}][temp_uuid]" value="${tempUuid}"> 

                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="text-success">Parameter</h5>
                 
                    <button type="button" class="btn btn-sm waves-effect waves-light btn-danger" onclick="removeElement('question-${tempIndex}')">
                        <i class="feather icon-trash-2"></i> Hapus Parameter
                    </button>
                </div>
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label class="form-label">Parameter</label>
                        <select class="form-select form-control select2 rounded-0 parameter-select" name="questions[${tempIndex}][kuesioner_pertanyaan_parameter_uuid]" required data-placeholder="Pilih Parameter" onchange="set_parameter(this)">
                           ` + $('#list_kuesioner_parameter_uuid').html() + `
                        </select>
                    </div>
                </div>
                <div style="display:none;">
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label class="form-label">Kode Header</label>
                        <input type="text" class="form-control" name="questions[${tempIndex}][kuesioner_pertanyaan_kode]" placeholder="Contoh: A" required>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label class="form-label">Nama Header</label>
                        <input type="text" class="form-control" name="questions[${tempIndex}][kuesioner_pertanyaan_nama]" placeholder="Contoh: A. Responsif Pegawai" required>
                    </div>
                </div>
                </div>
                <hr>
                <div class="d-flex justify-content-end mb-2">
                  <button type="button" class="btn btn-sm waves-effect waves-light btn-primary" onclick="createSubQuestionForm('${tempUuid}')">
                     <i class="feather icon-plus-square"></i> Tambah Pertanyaan
                     </button>
                </div>
                
                <div id="sub-questions-container-${tempUuid}">
                    <small class="text-muted">Tambahkan pertanyaan di sini.</small>
                </div>
            </div>
        `;
         container.insertAdjacentHTML('beforeend', headerHtml);

         // Init Select2 untuk elemen baru ini
         $("#question-" + tempIndex + " .select2").select2({
            width: '100%'
         });

         renumberQuestions();
         // Panggil cek parameter setelah add
         checkUsedParameters();
         checkUsedAspects();
      }


      window.createSubQuestionForm = function(parentUuid) {
         // ... (KODE createSubQuestionForm ANDA TIDAK BERUBAH, SAMA SEPERTI SEBELUMNYA) ...
         // ... Copy paste kode createSubQuestionForm yang lama di sini ...
         const tempIndex = questionIndex++;
         const subContainer = document.getElementById(`sub-questions-container-${parentUuid}`);

         let templateOptions = '<option value="">Template Jawaban</option>';
         availableTemplates.forEach(template => {
            const uuid = template.kuesioner_template_jawaban_group_uuid;
            const nama = template.kuesioner_template_jawaban_group_nama;
            const is_icon = template.kuesioner_template_jawaban_group_is_icon;
            console.log("Template:", template);
            templateOptions += `<option value="${uuid}" data-icon="${is_icon}">${nama}</option>`;
         });

         const questionHtml = `
            <div class="p-3 my-3 border rounded bg-white" id="question-${tempIndex}">
                <input type="hidden" name="questions[${tempIndex}][is_header]" value="false">
                <input type="hidden" name="questions[${tempIndex}][kuesioner_pertanyaan_parent_uuid]" value="${parentUuid}"> 
                <input type="hidden" name="questions[${tempIndex}][kuesioner_pertanyaan_is_icon]" id="is-icon-hidden-${tempIndex}" value="0">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="text-primary">Pertanyaan Baru</h6>
                    <button type="button" class="btn btn-sm waves-effect waves-light btn-danger" onclick="removeElement('question-${tempIndex}')">
                        <i class="feather icon-trash-2"></i> Hapus
                    </button>
                </div>
                 <div class="mb-3">
                    <label class="form-label">Aspek</label>
                    <select class="form-select form-control select2 rounded-0 aspect-select" id="aspek-${tempIndex}" name="questions[${tempIndex}][kuesioner_pertanyaan_aspect]" required data-placeholder="Pilih Aspek" onchange="checkUsedAspects()">
                        <option value=""></option>
                        <option value="1">Importance</option>
                        <option value="2">Performance</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Kode Pertanyaan</label>
                    <input type="text" class="form-control rounded-0" name="questions[${tempIndex}][kuesioner_pertanyaan_kode]"  required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Isi Pertanyaan</label>
                    <textarea class="form-control rounded-0" name="questions[${tempIndex}][kuesioner_pertanyaan_nama]" rows="2" required></textarea>
                </div>
                
                

                <div class="mb-3">
                    <label class="form-label">Tipe Jawaban</label>
                    <select class="form-select form-control select2 rounded-0" id="type-${tempIndex}" name="questions[${tempIndex}][kuesioner_pertanyaan_template_jawaban_type]" onchange="toggleOptions(${tempIndex}, this.value)" required  data-placeholder="Pilih Tipe Jawaban">
                        <option value=""></option>
                        <option value="1">Radio Button (Template)</option>
                        <option value="2">Radio Button (Custom)</option>
                        <option value="3">Checkbox (Custom)</option>
                        <option value="4">Textarea (Isian Bebas)</option>
                    </select>
                </div>

                <div class="mb-3" id="template-select-container-${tempIndex}" style="display: none;">
                    <label class="form-label">Template Jawaban</label>
                    <div class="input-group">
                        <select class="form-select form-control select2" id="template-select-${tempIndex}"  data-placeholder="Pilih Template Jawaban">
                            ${templateOptions}
                        </select>
                        <button class="btn btn-outline-secondary ms-2" type="button" onclick="loadTemplate(${tempIndex})">
                            Terapkan
                        </button>
                    </div>
                    <input type="hidden" name="questions[${tempIndex}][kuesioner_pertanyaan_template_jawaban_group_uuid]" id="template-uuid-hidden-${tempIndex}">
                </div>
                
                <div class="card card-body bg-light border-0 p-3 my-3">
                    <label class="form-label fw-bold">Opsi Jawaban:</label>
                    
                    <div id="answers-container-${tempIndex}">
                        <textarea class="form-control" disabled placeholder="User akan mengisi teks disini"></textarea>
                    </div>

                    <div class="mt-2 manual-add-btn-${tempIndex}" style="display: none;">
                        <button type="button" class="btn btn-sm btn-link text-decoration-none" onclick="addAnswer(${tempIndex})">
                            + Tambah Opsi Manual
                        </button>
                    </div>
                </div>
            </div>
        `;

         const placeholder = subContainer.querySelector('small.text-muted');
         if (placeholder) {
            placeholder.remove();
         }

         subContainer.insertAdjacentHTML('beforeend', questionHtml);
         // Init select2 hanya untuk elemen baru ini
         $("#question-" + tempIndex + " .select2").select2({
            width: '100%'
         });
         renumberQuestions();
         checkUsedAspects();
      }

      // ... (SISA FUNGSI SEPERTI toggleOptions, addAnswer, loadTemplate BIARKAN SAMA) ...
      // ... Saya hanya mempersingkat di jawaban ini agar tidak kepanjangan ...

      // LOGIC TEMPLATE & OPSI JAWABAN (Tidak berubah)
      function toggleOptions(qId, type) {
         // ... kode anda ...
         const container = document.getElementById(`answers-container-${qId}`);
         const templateSelectWrapper = document.getElementById(`template-select-container-${qId}`);
         const manualBtn = document.querySelector(`.manual-add-btn-${qId}`);
         const templateUuidHidden = document.getElementById(`template-uuid-hidden-${qId}`);

         container.innerHTML = '';
         templateSelectWrapper.style.display = 'none';
         manualBtn.style.display = 'none';
         templateUuidHidden.value = '';
         templateUuidHidden.removeAttribute('required');

         if (type === '4') { // Textarea (Isian Bebas)
            container.innerHTML = `<textarea class="form-control" disabled placeholder="User akan mengisi teks disini"></textarea>`;

         } else if (type === '1' || type === '2' || type === '3') {
            // Inisialisasi tabel untuk Tipe 1, 2, dan 3
            container.innerHTML = `
                <table class="table table-sm table-striped">
                    <thead>
                        <tr>
                            <th style="width: 15%">Kode</th>
                            <th style="width: 65%">Jawaban</th>
                            <th class="text-center" style="width: 15%">Bobot</th>
                            <th style="width: 5%"></th> </tr>
                    </thead>
                    <tbody id="answer-table-body-${qId}">
                        </tbody>
                </table>
            `;

            if (type === '1') { // Template
               templateSelectWrapper.style.display = 'block';

            } else { // Custom (2 atau 3)
               manualBtn.style.display = 'block';
               addAnswer(qId); // Tambah 1 baris kosong default
            }
         }
      }

      function addAnswer(qId, code = '', text = '', bobot = '', icon = '') {
         // ... kode anda ...
         const answerId = generateId();
         const qIndex = qId;
         const namePrefix = `questions[${qIndex}][answers][${answerId}]`;

         const html = `
        <tr id="answer-${answerId}">
            <td>
                <input type="text" name="${namePrefix}[kuesioner_jawaban_code]" class="form-control form-control-sm" value="${code}" required>
                <input type="hidden" name="${namePrefix}[kuesioner_jawaban_icon]" class="form-control form-control-sm" value="${icon}" required>
            </td>
            
            <td>
                <input type="text" name="${namePrefix}[kuesioner_jawaban_nama]" class="form-control form-control-sm" value="${text}" required>
            </td>
            
            <td>
                <input type="number" name="${namePrefix}[kuesioner_jawaban_bobot]" class="form-control form-control-sm text-end" value="${bobot}" step="0.01" required>
            </td>
            
            <td class="text-center">
               <button type="button" title="Hapus" class="btn btn-sm waves-effect waves-light btn-danger m-b-0 " style="padding-bottom: 0px;padding-top:0px;" onclick="removeElement('answer-${answerId}')" title="Hapus Opsi">
                  <i class="feather icon-trash-2"></i>
               </button>
            </td>
        </tr>`;

         const tbody = document.getElementById(`answer-table-body-${qId}`);
         if (tbody) {
            tbody.insertAdjacentHTML('beforeend', html);
         }
      }

      async function loadTemplate(qId) {
         // ... kode anda (tidak berubah) ...
         const selectBox = document.getElementById(`template-select-${qId}`);
         const templateUuid = selectBox.value;
         const templateUuidHidden = document.getElementById(`template-uuid-hidden-${qId}`);
         const iconHidden = document.getElementById(`is-icon-hidden-${qId}`);
         if (!templateUuid) {
            alert('Pilih template yang ingin diterapkan!');
            return;
         }
         // TAMBAHAN: Ambil data-icon dari option yang dipilih
         const selectedOption = selectBox.options[selectBox.selectedIndex];
         const isIconValue = selectedOption.getAttribute('data-icon');

         // Set value ke hidden input (default 0 jika null/undefined)
         if (iconHidden) {
            iconHidden.value = isIconValue ? isIconValue : 0;
         }
         try {
            const apiUrl = `{{ url('/kuesioner/manajemen-layanan-getjawaban') }}/${templateUuid}`;

            const response = await fetch(apiUrl);
            if (!response.ok) throw new Error('Gagal mengambil data template. Status: ' + response.status);
            const options = await response.json();

            const tbody = document.getElementById(`answer-table-body-${qId}`);
            if (tbody) tbody.innerHTML = '';

            if (options.length > 0) {
               options.forEach(opt => {
                  addAnswer(
                     qId,
                     opt.kuesioner_template_jawaban_code || '',
                     opt.kuesioner_template_jawaban_nama || '',
                     opt.kuesioner_template_jawaban_bobot || 0,
                     opt.kuesioner_template_jawaban_icon || ''
                  );
               });

               templateUuidHidden.value = templateUuid;
               templateUuidHidden.setAttribute('required', 'required');

            } else {
               alert('Template tidak memiliki opsi jawaban. Dibatalkan.');
               templateUuidHidden.value = '';
               templateUuidHidden.removeAttribute('required');
               addAnswer(qId);
            }

         } catch (e) {
            console.error("Error saat memuat template:", e);
            alert('Terjadi kesalahan saat memuat template. Cek Console.');
         }
      }

      // --- EVENT LISTENERS & INIT ---

      // 2. Loop setiap tombol dan pasang event listener
      addHeaderButtons.forEach(button => {
         button.addEventListener('click', createHeaderForm);
      });
      
      // createHeaderForm(); // Matikan auto create di awal jika mau bersih, atau biarkan. 
      // Jika dibiarkan menyala, pastikan createHeaderForm di atas sudah terupdate.
      createHeaderForm();

      function set_parameter(el) {
         var el_name = $(el).attr('name').replace('kuesioner_pertanyaan_parameter_uuid', 'kuesioner_pertanyaan_nama');
         var el_code = $(el).attr('name').replace('kuesioner_pertanyaan_parameter_uuid', 'kuesioner_pertanyaan_kode');

         var selectedOption = el.options[el.selectedIndex];

         var code = selectedOption.getAttribute('data-code');
         var name = selectedOption.getAttribute('data-name');

         $('input[name="' + el_code + '"]').val(code);
         $('input[name="' + el_name + '"]').val(name);

         // TAMBAHAN: Panggil cek parameter setelah memilih
         checkUsedParameters();
      }

      function checkUsedAspects() {
         // 1. Ambil semua Header Group yang ada
         const headerCards = document.querySelectorAll('#questions-container > div[id^="question-"]');

         headerCards.forEach((headerEl) => {
            // Pastikan ini adalah elemen Header
            const isHeader = headerEl.querySelector(`input[name$="[is_header]"]`)?.value === 'true';

            if (isHeader) {
               // Ambil UUID temp header ini untuk mencari container anaknya
               const tempUuid = headerEl.querySelector(`input[name$="[temp_uuid]"]`).value;
               const subContainer = document.getElementById(`sub-questions-container-${tempUuid}`);

               if (subContainer) {
                  // 2. Cari semua dropdown Aspek HANYA di dalam header ini
                  const aspectSelects = $(subContainer).find('.aspect-select');

                  // 3. Kumpulkan value yang sudah terpilih di group ini
                  let usedValues = [];
                  aspectSelects.each(function() {
                     let val = $(this).val();
                     if (val) usedValues.push(val);
                  });

                  // 4. Disable opsi yang sudah dipakai (kecuali dirinya sendiri)
                  aspectSelects.each(function() {
                     let currentDropdown = $(this);
                     let currentValue = currentDropdown.val();

                     currentDropdown.find('option').each(function() {
                        let optionValue = $(this).val();
                        if (!optionValue) return;

                        // Jika value ada di daftar terpakai DAN bukan milik dropdown ini
                        if (usedValues.includes(optionValue) && optionValue !== currentValue) {
                           $(this).prop('disabled', true);
                        } else {
                           $(this).prop('disabled', false);
                        }
                     });
                  });

                  // 5. Refresh Select2 (Per Group)
                  aspectSelects.select2({
                     width: '100%'
                  });
               }
            }
         });
      }
      $(document).ready(function(e) {
         $(".select2").select2({
            width: '100%'
         });
         $('.datepicker').datepicker({
            language: "en",
            autoclose: true,
            format: "yyyy/mm/dd",
         });
         $('.datepicker-year').datepicker({
            format: "yyyy",
            startView: 2,
            minViewMode: 2,
            autoclose: true
         });

         // Inisialisasi awal (jika ada data lama)
         checkUsedParameters();
         checkUsedAspects();
      })
   </script>
@endsection
