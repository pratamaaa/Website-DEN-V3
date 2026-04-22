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

   <section class="pcoded-main-container">
      <div class="pcoded-content">
         <div class="row">
            <div class="col-sm-12">
               <div class="card">
                  <div class="card-body">
                     <nav class="navbar" style="margin-left:-10px !important;">
                        <span class="m-r-15">
                           <h5>{{ $judulhalaman ?? 'Form Edit Kuesioner' }}</h5>
                        </span>
                     </nav>
                     <hr>

                     {{-- ACTION DIUBAH KE UPDATE DENGAN UUID --}}
                     <form action="{{ url('/kuesioner/manajemen-layanan-update/' . $layanan->kuesioner_layanan_uuid) }}" method="POST">
                        @csrf

                        <div class="card shadow-sm mb-4">
                           <div class="card-header text-white p-3" style="background: linear-gradient(to right, #000000, #0fb3c2); color:#ffffff !important;">
                              <h5 class="mb-0 text-white">Data Utama Layanan</h5>
                           </div>
                           <div class="card-body mt-4">
                              <div class="row">
                                 <div class="col-md-4 mb-3">
                                    <label class="form-label">Tahun</label>
                                    <input type="text" class="form-control datepicker-year" name="kuesioner_layanan_tahun" value="{{ $layanan->kuesioner_layanan_tahun }}" required>
                                 </div>
                                 <div class="col-md-4 mb-3">
                                    <label class="form-label">Tanggal Mulai</label>
                                    <div class="datepicker date input-group">
                                       <input type="text" placeholder="Pilih tanggal" value="{{ date('Y/m/d', strtotime($layanan->kuesioner_layanan_tanggal_mulai)) }}" class="form-control" id="kuesioner_layanan_tanggal_mulai" name="kuesioner_layanan_tanggal_mulai" required>
                                       <div class="input-group-append">
                                          <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                                       </div>
                                    </div>
                                 </div>
                                 <div class="col-md-4 mb-3">
                                    <label class="form-label">Tanggal Selesai</label>
                                    <div class="datepicker date input-group">
                                       <input type="text" placeholder="Pilih tanggal" value="{{ $layanan->kuesioner_layanan_tanggal_selesai ? date('Y/m/d', strtotime($layanan->kuesioner_layanan_tanggal_selesai)) : '' }}" class="form-control" id="kuesioner_layanan_tanggal_selesai" name="kuesioner_layanan_tanggal_selesai">
                                       <div class="input-group-append">
                                          <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                                       </div>
                                    </div>
                                 </div>
                              </div>
                              <div class="row">
                                 <div class="col-md-12 mb-3">
                                    <label class="form-label">Nama Layanan</label>
                                    <input type="text" class="form-control" name="kuesioner_layanan_nama" value="{{ $layanan->kuesioner_layanan_nama }}" required>
                                 </div>
                              </div>
                              <div class="row">
                                 <div class="col-md-12 mb-3">
                                    <label class="form-label">Tampilkan Saran & Masukan ?</label>
                                    <select class="form-select form-control" name="kuesioner_layanan_is_saran" required>
                                       <option value="1" {{ $layanan->kuesioner_layanan_is_saran == 1 ? 'selected' : '' }}>Ya</option>
                                       <option value="0" {{ $layanan->kuesioner_layanan_is_saran == 0 ? 'selected' : '' }}>Tidak</option>
                                    </select>
                                 </div>
                              </div>
                           </div>
                        </div>

                        <div class="card shadow-sm">
                           <div class="card-header p-3 text-white d-flex justify-content-between align-items-center" style="background: linear-gradient(to right, #000000, #0fb3c2); color:#ffffff !important;">
                              <h5 class="mb-0 text-white">Daftar Pertanyaan</h5>
                              <button type="button" class="btn btn-sm waves-effect waves-light btn-primary add-header-btn"><i class="feather icon-plus-square"></i> Tambah Parameter</button>
                           </div>
                           <div class="card-body">
                              <div id="questions-container">
                                 {{-- Konten Dinamis diisi via initOldData --}}
                              </div>
                           </div>
                           <div class="card-header p-3 text-white d-flex justify-content-end align-items-center" style="background: linear-gradient(to right, #000000, #0fb3c2); color:#ffffff !important;border-radius:0 0 calc(5px - 0px) calc(5px - 0px)">
                              <button type="button" class="btn btn-sm waves-effect waves-light btn-primary add-header-btn"><i class="feather icon-plus-square"></i> Tambah Parameter</button>
                           </div>
                        </div>

                        <div class="d-grid gap-2 mt-4">
                           <button type="submit" class="btn btn-info btn-sm"><i class="feather icon-save"></i> Simpan Perubahan</button>
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
      const availableTemplates = @json($templateGroups);
      let questionIndex = 0;
      const container = document.getElementById('questions-container');
      const addHeaderButtons = document.querySelectorAll('.add-header-btn');

      function generateId() {
         return Date.now() + Math.random().toString(36).substr(2, 9);
      }

      // --- FUNGSI GLOBAL AGAR TIDAK REFERENCE ERROR ---

      window.set_parameter = function(el) {
         var el_name = $(el).attr('name').replace('kuesioner_pertanyaan_parameter_uuid', 'kuesioner_pertanyaan_nama');
         var el_code = $(el).attr('name').replace('kuesioner_pertanyaan_parameter_uuid', 'kuesioner_pertanyaan_kode');
         var selectedOption = el.options[el.selectedIndex];
         var code = selectedOption.getAttribute('data-code');
         var name = selectedOption.getAttribute('data-name');
         $('input[name="' + el_code + '"]').val(code);
         $('input[name="' + el_name + '"]').val(name);
         checkUsedParameters();
      };

      window.removeElement = function(id) {
         const el = document.getElementById(id);
         if (el) {
            el.remove();
            renumberQuestions();
            checkUsedParameters();
            checkUsedAspects();
         }
      };

      window.createHeaderForm = function() {
         const tempIndex = questionIndex++;
         const tempUuid = generateId();
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
                           ${$('#list_kuesioner_parameter_uuid').html()}
                        </select>
                    </div>
                </div>
                <div style="display:none;">
                   <input type="text" name="questions[${tempIndex}][kuesioner_pertanyaan_kode]">
                   <input type="text" name="questions[${tempIndex}][kuesioner_pertanyaan_nama]">
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
            </div>`;
         container.insertAdjacentHTML('beforeend', headerHtml);
         $(`#question-${tempIndex} .select2`).select2({
            width: '100%'
         });
         renumberQuestions();
         checkUsedParameters();
         return tempUuid;
      };

      window.createSubQuestionForm = function(parentUuid) {
         const tempIndex = questionIndex++;
         const subContainer = document.getElementById(`sub-questions-container-${parentUuid}`);
         let templateOptions = '<option value="">Template Jawaban</option>';
         availableTemplates.forEach(template => {
            templateOptions += `<option value="${template.kuesioner_template_jawaban_group_uuid}" data-icon="${template.kuesioner_template_jawaban_group_is_icon}">${template.kuesioner_template_jawaban_group_nama}</option>`;
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
                    <input type="text" class="form-control rounded-0" name="questions[${tempIndex}][kuesioner_pertanyaan_kode]" required readonly>
                </div>
                <div class="mb-3">
                    <label class="form-label">Isi Pertanyaan</label>
                    <textarea class="form-control rounded-0" name="questions[${tempIndex}][kuesioner_pertanyaan_nama]" rows="2" required></textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label">Tipe Jawaban</label>
                    <select class="form-select form-control select2 rounded-0" id="type-${tempIndex}" name="questions[${tempIndex}][kuesioner_pertanyaan_template_jawaban_type]" onchange="toggleOptions(${tempIndex}, this.value)" required data-placeholder="Pilih Tipe Jawaban">
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
                        <select class="form-select form-control select2" id="template-select-${tempIndex}" data-placeholder="Pilih Template Jawaban">
                            ${templateOptions}
                        </select>
                        <button class="btn btn-outline-secondary ms-2" type="button" onclick="loadTemplate(${tempIndex})">Terapkan</button>
                    </div>
                    <input type="hidden" name="questions[${tempIndex}][kuesioner_pertanyaan_template_jawaban_group_uuid]" id="template-uuid-hidden-${tempIndex}">
                </div>
                <div class="card card-body bg-light border-0 p-3 my-3">
                    <label class="form-label fw-bold">Opsi Jawaban:</label>
                    <div id="answers-container-${tempIndex}">
                        <textarea class="form-control" disabled placeholder="User akan mengisi teks disini"></textarea>
                    </div>
                    <div class="mt-2 manual-add-btn-${tempIndex}" style="display: none;">
                        <button type="button" class="btn btn-sm btn-link text-decoration-none" onclick="addAnswer(${tempIndex})">+ Tambah Opsi Manual</button>
                    </div>
                </div>
            </div>`;

         const placeholder = subContainer.querySelector('small.text-muted');
         if (placeholder) placeholder.remove();
         subContainer.insertAdjacentHTML('beforeend', questionHtml);
         $(`#question-${tempIndex} .select2`).select2({
            width: '100%'
         });
         renumberQuestions();
         checkUsedAspects();
         return tempIndex;
      };

      window.toggleOptions = function(qId, type) {
         const container = document.getElementById(`answers-container-${qId}`);
         const templateSelectWrapper = document.getElementById(`template-select-container-${qId}`);
         const manualBtn = document.querySelector(`.manual-add-btn-${qId}`);
         const templateUuidHidden = document.getElementById(`template-uuid-hidden-${qId}`);

         container.innerHTML = '';
         templateSelectWrapper.style.display = 'none';
         manualBtn.style.display = 'none';
         if (templateUuidHidden) templateUuidHidden.value = '';

         if (type === '4') {
            container.innerHTML = `<textarea class="form-control" disabled placeholder="User akan mengisi teks disini"></textarea>`;
         } else if (['1', '2', '3'].includes(type)) {
            container.innerHTML = `<table class="table table-sm table-striped"><thead><tr><th style="width: 15%">Kode</th><th style="width: 65%">Jawaban</th><th class="text-center" style="width: 15%">Bobot</th><th style="width: 5%"></th></tr></thead><tbody id="answer-table-body-${qId}"></tbody></table>`;
            if (type === '1') {
               templateSelectWrapper.style.display = 'block';
            } else {
               manualBtn.style.display = 'block';
               addAnswer(qId);
            }
         }
      };

      window.addAnswer = function(qId, code = '', text = '', bobot = '', icon = '') {
         const answerId = generateId();
         const namePrefix = `questions[${qId}][answers][${answerId}]`;
         const html = `
            <tr id="answer-${answerId}">
                <td><input type="text" name="${namePrefix}[kuesioner_jawaban_code]" class="form-control form-control-sm" value="${code}" required></td>
                <td><input type="text" name="${namePrefix}[kuesioner_jawaban_nama]" class="form-control form-control-sm" value="${text}" required><input type="hidden" name="${namePrefix}[kuesioner_jawaban_icon]" value="${icon}"></td>
                <td><input type="number" name="${namePrefix}[kuesioner_jawaban_bobot]" class="form-control form-control-sm text-end" value="${bobot}" step="0.01" required></td>
                <td class="text-center"><button type="button" class="btn btn-sm btn-danger p-1" onclick="removeElement('answer-${answerId}')"><i class="feather icon-trash-2"></i></button></td>
            </tr>`;
         $(`#answer-table-body-${qId}`).append(html);
      };

      window.loadTemplate = async function(qId) {
         const selectBox = document.getElementById(`template-select-${qId}`);
         const templateUuid = selectBox.value;
         if (!templateUuid) return alert('Pilih template!');
         const isIconValue = selectBox.options[selectBox.selectedIndex].getAttribute('data-icon');
         $(`#is-icon-hidden-${qId}`).val(isIconValue || 0);

         try {
            const resp = await fetch(`{{ url('/kuesioner/manajemen-layanan-getjawaban') }}/${templateUuid}`);
            const options = await resp.json();
            $(`#answer-table-body-${qId}`).empty();
            options.forEach(opt => {
               addAnswer(qId, opt.kuesioner_template_jawaban_code, opt.kuesioner_template_jawaban_nama, opt.kuesioner_template_jawaban_bobot, opt.kuesioner_template_jawaban_icon);
            });
            $(`#template-uuid-hidden-${qId}`).val(templateUuid);
         } catch (e) {
            alert('Gagal memuat template');
         }
      };

      // --- FUNGSI INIT DATA LAMA (KHUSUS EDIT) ---
      function initOldData() {
         const oldData = @json($pertanyaan);
         if (!oldData || oldData.length === 0) {
            createHeaderForm();
            return;
         }

         // Gunakan async/await jika memungkinkan, atau proses satu per satu
         oldData.forEach(header => {
            // 1. Buat Header
            const tempUuid = createHeaderForm();
            const hIdx = questionIndex - 1;
            const $hEl = $(`#question-${hIdx}`);

            // Set Parameter
            $hEl.find('.parameter-select').val(header.kuesioner_pertanyaan_parameter_uuid).trigger('change');

            // 2. Buat Sub Pertanyaan
            if (header.children && header.children.length > 0) {
               header.children.forEach(sub => {
                  const sIdx = createSubQuestionForm(tempUuid);
                  const $sEl = $(`#question-${sIdx}`);

                  // Set Aspek
                  $sEl.find('.aspect-select').val(sub.kuesioner_pertanyaan_aspect).trigger('change');

                  // Set Nama
                  $sEl.find('textarea[name$="[kuesioner_pertanyaan_nama]"]').val(sub.kuesioner_pertanyaan_nama);

                  // --- FIX UTAMA: SET VALUE SEBELUM & SESUDAH TOGGLE ---
                  const typeVal = sub.kuesioner_pertanyaan_template_jawaban_type;

                  // 1. Set value ke element select asli
                  const $typeSelect = $sEl.find('select[id^="type-"]');
                  $typeSelect.val(typeVal);

                  // 2. Jalankan logika UI (munculkan tabel/template)
                  window.toggleOptions(sIdx, typeVal.toString());

                  // 3. Update tampilan visual Select2
                  $typeSelect.trigger('change.select2');

                  if (typeVal == 1) {
                     // Set Template Group
                     const $tplSelect = $sEl.find('select[id^="template-select-"]');
                     $tplSelect.val(sub.kuesioner_pertanyaan_template_jawaban_group_uuid).trigger('change.select2');
                     $sEl.find('input[id^="template-uuid-hidden"]').val(sub.kuesioner_pertanyaan_template_jawaban_group_uuid);
                  }

                  // 4. Isi Jawaban
                  if (sub.jawaban && sub.jawaban.length > 0) {
                     $(`#answer-table-body-${sIdx}`).empty(); // Bersihkan baris kosong default
                     sub.jawaban.forEach(j => {
                        addAnswer(sIdx, j.kuesioner_jawaban_code, j.kuesioner_jawaban_nama, j.kuesioner_jawaban_bobot, j.kuesioner_jawaban_icon);
                     });
                  }
               });
            }
         });

         renumberQuestions();
      }

      function renumberQuestions() {
         $('#questions-container > div[id^="question-"]').each(function(hIdx) {
            const hNum = hIdx + 1;
            $(this).find('h5.text-success').text(`Parameter #${hNum}`);
            const tempUuid = $(this).find('input[name$="[temp_uuid]"]').val();
            $(`#sub-questions-container-${tempUuid} > div`).each(function(sIdx) {
               const sNum = `${hNum}.${sIdx + 1}`;
               $(this).find('h6.text-primary').text(`Pertanyaan #${sNum}`);
               $(this).find('input[name$="[kuesioner_pertanyaan_kode]"]').val(sNum);
            });
         });
      }

      function checkUsedParameters() {
         let selected = [];
         $('.parameter-select').each(function() {
            if ($(this).val()) selected.push($(this).val());
         });
         $('.parameter-select').each(function() {
            let curr = $(this).val();
            $(this).find('option').each(function() {
               if ($(this).val() && selected.includes($(this).val()) && $(this).val() !== curr) $(this).prop('disabled', true);
               else $(this).prop('disabled', false);
            });
         });
      }

      function checkUsedAspects() {
         $('#questions-container > div').each(function() {
            let used = [];
            $(this).find('.aspect-select').each(function() {
               if ($(this).val()) used.push($(this).val());
            });
            $(this).find('.aspect-select').each(function() {
               let curr = $(this).val();
               $(this).find('option').each(function() {
                  if ($(this).val() && used.includes($(this).val()) && $(this).val() !== curr) $(this).prop('disabled', true);
                  else $(this).prop('disabled', false);
               });
            });
         });
      }

      $(document).ready(function() {
         $(".select2").select2({
            width: '100%'
         });
         $('.datepicker').datepicker({
            format: "yyyy/mm/dd",
            autoclose: true
         });
         $('.datepicker-year').datepicker({
            format: "yyyy",
            viewMode: "years",
            minViewMode: "years",
            autoclose: true
         });

         // LOAD DATA LAMA
         initOldData();

         addHeaderButtons.forEach(btn => btn.addEventListener('click', createHeaderForm));
      });
   </script>
@endsection
