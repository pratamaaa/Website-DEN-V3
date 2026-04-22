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
                        {{-- Form mengarah ke route UPDATE dengan UUID Group --}}
                        <form action="{{ url('/kuesioner/manajemen-template-jawaban-update/'.$group->kuesioner_template_jawaban_group_uuid) }}" 
                              method="POST" 
                              enctype="multipart/form-data"> 
                            @csrf

                            {{-- === HEADER GROUP === --}}
                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label fw-bold">Nama Group</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="nama_group" value="{{ $group->kuesioner_template_jawaban_group_nama }}" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label fw-bold">Keterangan</label>
                                <div class="col-sm-10">
                                    <textarea class="form-control" name="keterangan" rows="2">{{ $group->kuesioner_template_jawaban_group_keterangan }}</textarea>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label fw-bold">Opsi Icon</label>
                                <div class="col-sm-10 d-flex align-items-center">
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" class="custom-control-input" id="is_icon" name="is_icon" value="1" 
                                        {{ $group->kuesioner_template_jawaban_group_is_icon == 1 ? 'checked' : '' }}>
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
                                            <th width="20%" class="col-icon text-center" style="display: none;">Icon</th>
                                            <th width="5%" class="text-center">
                                                <button type="button" class="btn btn-sm btn-light btn-add-row" title="Tambah Baris">
                                                    <i class="fa fa-plus text-primary fw-bold"></i>
                                                </button>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        {{-- LOOPING DATA LAMA --}}
                                        @foreach($jawaban as $key => $row)
                                        <tr>
                                            <td>
                                                {{-- HIDDEN UUID UNTUK UPDATE --}}
                                                <input type="hidden" name="jawaban[{{$key}}][uuid]" value="{{ $row->kuesioner_template_jawaban_uuid }}">
                                                <input type="text" class="form-control text-center" name="jawaban[{{$key}}][code]" value="{{ $row->kuesioner_template_jawaban_code }}" required>
                                            </td>
                                            <td>
                                                <input type="text" class="form-control" name="jawaban[{{$key}}][nama]" value="{{ $row->kuesioner_template_jawaban_nama }}" required>
                                            </td>
                                            <td>
                                                <input type="number" step="0.01" class="form-control text-center" name="jawaban[{{$key}}][bobot]" value="{{ $row->kuesioner_template_jawaban_bobot }}" required>
                                            </td>
                                            <td class="col-icon" style="display: none;">
                                                {{-- PREVIEW ICON LAMA --}}
                                                @if(!empty($row->kuesioner_template_jawaban_icon))
                                                    <div class="mb-2 text-center">
                                                        <img src="{{ asset('survey/'.$row->kuesioner_template_jawaban_icon) }}" style="height: 30px; object-fit: contain;">
                                                        <br><small class="text-muted">File saat ini</small>
                                                    </div>
                                                @endif
                                                
                                                {{-- HIDDEN INPUT NAMA FILE LAMA --}}
                                                <input type="hidden" name="jawaban[{{$key}}][old_icon]" value="{{ $row->kuesioner_template_jawaban_icon }}">
                                                
                                                <input type="file" class="form-control p-1" name="jawaban[{{$key}}][icon]" accept="image/*">
                                            </td>
                                            <td class="text-center">
                                                <button type="button" class="btn btn-sm btn-danger btn-remove-row">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <div class="form-group row mt-4">
                                <div class="col-sm-12 text-right">
                                    <a href="{{ url('/kuesioner/manajemen-template-jawaban') }}" class="btn btn-secondary">
                                        <i class="fa fa-arrow-left"></i> Kembali
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fa fa-save"></i> Perbarui Data
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
        // Start index dari jumlah data yang ada agar tidak bentrok
        var rowIndex = {{ count($jawaban) }}; 

        // 1. Fungsi Toggle Kolom Icon
        function toggleIconColumn() {
            if ($('#is_icon').is(':checked')) {
                $('.col-icon').show();
            } else {
                $('.col-icon').hide();
            }
        }

        // Jalankan saat load & saat checkbox berubah
        toggleIconColumn();
        $('#is_icon').change(function() {
            toggleIconColumn();
        });

        // 2. Tambah Baris Baru
        $('.btn-add-row').click(function() {
            var displayIcon = $('#is_icon').is(':checked') ? '' : 'display:none;';

            var html = `
                <tr>
                    <td>
                        {{-- UUID KOSONG UTK DATA BARU --}}
                        <input type="hidden" name="jawaban[${rowIndex}][uuid]" value="">
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
                            <i class="fa fa-trash"></i>
                        </button>
                    </td>
                </tr>
            `;
            
            $('#table-jawaban tbody').append(html);
            rowIndex++;
        });

        // 3. Hapus Baris
        $(document).on('click', '.btn-remove-row', function() {
            var rowCount = $('#table-jawaban tbody tr').length;
            if (rowCount > 1) {
                // Di Form Edit, menghapus baris di HTML berarti baris tersebut 
                // TIDAK akan terkirim ke controller.
                // Controller akan otomatis mendeteksi UUID yang hilang dan melakukan Soft Delete.
                $(this).closest('tr').remove();
            } else {
                alert("Minimal harus ada satu opsi jawaban.");
            }
        });
    });
</script>
@endsection