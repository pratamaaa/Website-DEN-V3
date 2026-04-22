@extends('layout.dapur.app')

@section('content')
<section class="pcoded-main-container">
    <div class="pcoded-content">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <h5>{{ $judulhalaman }}</h5>
                    </div>
                    <div class="card-body">
                        {{-- Form Action mengarah ke manajemen_template_jawaban_save --}}
                        <form action="{{ url('/kuesioner/manajemen-template-jawaban-save') }}" 
                              method="POST" 
                              enctype="multipart/form-data"> 
                            @csrf

                            {{-- === HEADER GROUP === --}}
                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label fw-bold">Nama Group</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="nama_group" required placeholder="Contoh: Skala Likert 1-4 (Sangat Baik - Buruk)">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label fw-bold">Keterangan</label>
                                <div class="col-sm-10">
                                    <textarea class="form-control" name="keterangan" rows="2" placeholder="Deskripsi singkat..."></textarea>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label fw-bold">Opsi Icon</label>
                                <div class="col-sm-10 d-flex align-items-center">
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" class="custom-control-input" id="is_icon" name="is_icon" value="1">
                                        <label class="custom-control-label" for="is_icon">Gunakan Icon / Gambar pada Jawaban</label>
                                    </div>
                                </div>
                            </div>

                            <hr>

                            {{-- === DETAIL JAWABAN (DYNAMIC TABLE) === --}}
                            <h5 class="mb-3 mt-4">Daftar Opsi Jawaban</h5>
                            
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped" id="table-jawaban">
                                    <thead class="bg-primary text-white">
                                        <tr>
                                            <th width="15%" class="text-center">Kode</th>
                                            <th class="text-center">Label Jawaban</th>
                                            <th width="15%" class="text-center">Bobot Nilai</th>
                                            <th width="20%" class="col-icon text-center" style="display: none;">Upload Icon</th>
                                            <th width="5%" class="text-center">
                                                <button type="button" class="btn btn-sm btn-light btn-add-row" title="Tambah Baris">
                                                    <i class="fa fa-plus text-primary fw-bold"></i>
                                                </button>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        {{-- Baris Pertama Default --}}
                                        <tr>
                                            <td>
                                                <input type="text" class="form-control text-center" name="jawaban[0][code]" required placeholder="Kode">
                                            </td>
                                            <td>
                                                <input type="text" class="form-control" name="jawaban[0][nama]" required placeholder="Label">
                                            </td>
                                            <td>
                                                <input type="number" step="0.01" class="form-control text-center" name="jawaban[0][bobot]" required placeholder="0">
                                            </td>
                                            <td class="col-icon" style="display: none;">
                                                <input type="file" class="form-control p-1" name="jawaban[0][icon]" accept="image/*">
                                                <small class="text-muted">Format: jpg, png</small>
                                            </td>
                                            <td class="text-center">
                                                <button type="button" class="btn btn-sm btn-danger btn-remove-row">
                                                    <i class="feather icon-trash-2"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <div class="form-group row mt-4">
                                <div class="col-sm-12 text-right">
                                    <a href="{{ url('/kuesioner/manajemen-template-jawaban') }}" class="btn btn-secondary">
                                        <i class="fa fa-arrow-left"></i> Kembali
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="feather icon-save"></i> Simpan Data
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- JQUERY SCRIPT --}}
<script>
    $(document).ready(function() {
        var rowIndex = 1; // Counter untuk array input name

        // 1. Fungsi Toggle Kolom Icon
        function toggleIconColumn() {
            if ($('#is_icon').is(':checked')) {
                $('.col-icon').show();
            } else {
                $('.col-icon').hide();
                // Opsional: Reset value file input jika di-uncheck (agar tidak terupload)
                // $('.col-icon input[type="file"]').val(''); 
            }
        }

        // Jalankan saat load & saat checkbox berubah
        toggleIconColumn();
        $('#is_icon').change(function() {
            toggleIconColumn();
        });

        // 2. Tambah Baris Baru
        $('.btn-add-row').click(function() {
            // Cek status icon saat ini untuk baris baru
            var displayIcon = $('#is_icon').is(':checked') ? '' : 'display:none;';

            var html = `
                <tr>
                    <td>
                        <input type="text" class="form-control text-center" name="jawaban[${rowIndex}][code]" required placeholder="Kode">
                    </td>
                    <td>
                        <input type="text" class="form-control" name="jawaban[${rowIndex}][nama]" required placeholder="Label">
                    </td>
                    <td>
                        <input type="number" step="0.01" class="form-control text-center" name="jawaban[${rowIndex}][bobot]" required placeholder="0">
                    </td>
                    <td class="col-icon" style="${displayIcon}">
                        <input type="file" class="form-control p-1" name="jawaban[${rowIndex}][icon]" accept="image/*">
                    </td>
                    <td class="text-center">
                        <button type="button" class="btn btn-sm btn-danger btn-remove-row">
                            <i class="feather icon-trash-2"></i>
                        </button>
                    </td>
                </tr>
            `;
            
            $('#table-jawaban tbody').append(html);
            rowIndex++;
        });

        // 3. Hapus Baris
        $(document).on('click', '.btn-remove-row', function() {
            // Cek jumlah baris tersisa
            var rowCount = $('#table-jawaban tbody tr').length;
            
            if (rowCount > 1) {
                $(this).closest('tr').remove();
            } else {
                alert("Minimal harus ada satu opsi jawaban.");
            }
        });
    });
</script>
@endsection