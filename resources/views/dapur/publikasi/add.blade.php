<style>
    .margin1 {
        margin-bottom: 5px;
    }

    .margin2 {
        margin-bottom: -8px;
    }

    .margin3 {
        margin-bottom: 7px;
    }

    .warnafont {
        color: #000000 !important;
    }

    .labelku {
        margin-top: 5px !important;
        margin-bottom: 2px !important;
        font-weight: bold;
        font-size: 13px;
    }

    .warnamerah {
        color: red
    }

    ;
</style>

<div class="modal-header" style="background-color:#128D93;">
    <h5 class="modal-title h5" id="formProdukTambah_judul" style="color:#ffffff;">
        {{ $judulmodal }}
    </h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
            aria-hidden="true">&times;</span></button>
</div>
<div class="modal-body" id="formtambah_body">
    <form id="formulir" name="formulir" method="post" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-md-12">
                <div class="form-group mb-1">
                    <label class="labelku" style="color: #626365;font-size:13px;"><b>Kategori:</b></label>
                    <select class="form-control" id="kategori_publikasi" name="kategori_publikasi" style="width: 60%;">
                        @if ($kategori->count() != 0)
                            {{-- @foreach ($kategori->get() as $kat) --}}
                            @foreach ($kategori as $kat)
                                <option value="{{ $kat->id_publikasi_kategori }}">{{ $kat->nama_kategori }}</option>
                            @endforeach
                        @else
                            <option>Kategori not found</option>
                        @endif
                    </select>
                </div>
                <div class="form-group mb-1">
                    <label class="labelku" style="color: #626365;font-size:13px;"><b>Judul publikasi:</b></label>
                    <input type="input" class="form-control form-control-sm" id="judul_publikasi"
                        name="judul_publikasi" style="width: 100%;">
                </div>
                <div class="form-group mb-1">
                    <label class="labelku" style="color: #626365;font-size:13px;"><b>Judul publikasi (EN):</b></label>
                    <input type="input" class="form-control form-control-sm" id="judul_publikasi_en"
                        name="judul_publikasi_en" style="width: 100%;">
                </div>
                <div class="form-group mb-1">
                    <label class="labelku" style="color: #626365;font-size:13px;"><b>Deskripsi:</b></label>
                    <textarea class="form-control summernote" id="deskripsi" name="deskripsi" rows="3"></textarea>
                </div>
                <div class="form-group" style="margin-top:-20px !important;margin-bottom:-20px !important;">
                    <label class="labelku" style="color: #626365;font-size:13px;"><b>Deskripsi (EN):</b></label>
                    <textarea class="form-control summernote" id="deskripsi_en" name="deskripsi_en" rows="3"></textarea>
                </div>
                <div class="form-group mb-1">
                    <label class="labelku" style="color: #626365;font-size:13px;"><b>Gambar sampul:</b></label>
                    <input type="file" accept=".jpg,.jpeg,.png" class="form-control form-control-sm" id="gambar"
                        name="gambar" style="width: 60%;">
                </div>
                <div class="form-group mb-1 mt-3">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="is_internal" name="is_internal" checked>
                        <label class="form-check-label" for="gridCheck1" style="color: #626365;font-size:13px;">
                            Sumber berkas dari internal
                        </label>
                    </div>
                </div>
                <div class="form-group mb-1" id="div_berkas">
                    <label class="labelku" style="color: #626365;font-size:13px;"><b>Upload berkas:</b></label>
                    <input type="file" accept=".pdf,.doc,.docx" class="form-control form-control-sm" id="berkas"
                        name="berkas" style="width: 60%;">
                </div>
                <div class="form-group mb-1" id="div_berkasurl">
                    <label class="labelku" style="color: #626365;font-size:13px;"><b>Alamat Url:</b></label>
                    <input type="text" class="form-control form-control-sm" id="berkas_url" name="berkas_url"
                        style="width: 100%;" placeholder="https://alamaturl.com/namafile.pdf">
                </div>
                <div class="form-group mb-1">
                    <label class="labelku" style="color: #626365;font-size:13px;"><b>Tanggal publikasi:</b></label>
                    <div class="datepicker date input-group" style="width:200px;">
                        <input type="text" placeholder="Pilih tanggal" value="{{ date('Y/m/d') }}"
                            class="form-control" id="tanggal_publikasi" name="tanggal_publikasi">
                        <div class="input-group-append">
                            <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-12 text-right">
                <button type="submit" class="btn btn-info btn-sm" value="Simpan"><i class="feather icon-save"></i>
                    Simpan</button>
                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal"><i
                        class="feather icon-x"></i> Batal</button>
            </div>
        </div>
    </form>
</div>

<script>
    $(document).ready(function() {

        $('#div_berkas').show();
        $('#div_berkasurl').hide();

        $('.datepicker').datepicker({
            language: "en",
            autoclose: true,
            format: "yyyy/mm/dd",
        });

        $('.summernote').summernote({
            height: 100
        });

        $("#is_internal").change(function() {

            if ($(this).is(":checked")) {

                $('#div_berkas').show();
                $('#div_berkasurl').hide();

            } else {

                $('#div_berkas').hide();
                $('#div_berkasurl').show();

            }

        });

        /*
        |--------------------------------------
        | VALIDATION
        |--------------------------------------
        */

        $('#formulir').validate({

            rules: {
                kategori_publikasi: {
                    required: true
                },
                judul_publikasi: {
                    required: true
                },
                judul_publikasi_en: {
                    required: true
                },
                deskripsi: {
                    required: true
                },
                deskripsi_en: {
                    required: true
                },
                tanggal_publikasi: {
                    required: true
                },
            },

            submitHandler: function(form) {

                $.ajax({

                    url: "{{ route('publikasi.save') }}",
                    type: "POST",
                    data: new FormData(form),
                    processData: false,
                    contentType: false,
                    dataType: "json",

                    success: function(resp) {

                        if (resp.result == "success") {

                            swal({
                                icon: 'success',
                                title: 'Berhasil',
                                text: resp.message
                            }).then(function() {

                                $('#modalku').modal('hide');
                                $('#tabelku').DataTable().ajax.reload();

                            });

                        } else if (resp.result == "validation") {

                            swal("Validasi gagal", resp.message, "warning");

                        } else {

                            swal("Error", resp.message, "error");

                        }

                    },

                    error: function(xhr) {

                        swal("Error", "Terjadi kesalahan server", "error");

                    }

                });

            }

        });

    });
</script>
