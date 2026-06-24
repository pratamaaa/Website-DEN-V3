<style>
    .labelku { margin-top: 5px !important; margin-bottom: 2px !important; font-weight: bold; font-size: 13px; }
</style>

<div class="modal-header" style="background-color:#128D93;">
    <h5 class="modal-title h5" style="color:#ffffff;">{{ $judulmodal }}</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<div class="modal-body">
    <form id="formulir" name="formulir" method="post" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="id_infografis" value="{{ $data->id_infografis }}">

        <div class="row">
            <div class="col-md-12">

                <div class="form-group mb-2">
                    <label class="labelku"><b>Judul infografis:</b></label>
                    <input type="text" class="form-control form-control-sm"
                        value="{{ $data->judul_infografis }}" id="judul_infografis" name="judul_infografis">
                </div>

                <div class="form-group mb-2">
                    <label class="labelku"><b>Gambar sampul:</b></label>
                    <input type="file" class="form-control form-control-sm" id="gambar" name="gambar" style="width:60%;">
                    <input type="hidden" value="{{ $data->gambar_sampul }}" id="gambar_current" name="gambar_current">
                    @if ($data->gambar_sampul)
                        <img src="{{ asset('storage/infografis/' . $data->gambar_sampul) }}"
                            width="150px" style="margin-top:10px;border-radius:5px;border:1px solid #cdcdcd;">
                    @else
                        <img src="{{ asset('mainpro/images/no-image.jpg') }}"
                            width="100px" style="margin-top:10px;border-radius:5px;border:1px solid #cdcdcd;">
                    @endif
                </div>

                <div class="form-group mb-2 mt-3">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="is_active" name="is_active"
                            value="yes" {{ $data->is_active == 'yes' ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_active" style="font-size:13px;">
                            Is Active?
                        </label>
                    </div>
                </div>

                <div class="form-group mb-2 mt-2">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="is_internal" name="is_internal"
                            value="yes" {{ $data->berkas_sumber == 'internal' ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_internal" style="font-size:13px;">
                            Sumber berkas dari internal
                        </label>
                    </div>
                </div>

                <div class="form-group mb-2" id="div_berkas">
                    <label class="labelku"><b>Upload berkas:</b></label>
                    <input type="file" class="form-control form-control-sm" id="berkas" name="berkas"
                        style="width:60%;margin-bottom:5px;">
                    <input type="hidden" value="{{ $data->berkas }}" id="berkas_current" name="berkas_current">
                    @if ($data->berkas && $data->berkas_sumber == 'internal')
                        <a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#modalku2"
                            onclick="showFormRead('{{ $data->id_infografis }}', 'infografis')">
                            <span><i class="feather icon-download-cloud"></i> {{ $data->berkas }}</span>
                        </a>
                    @endif
                </div>

                <div class="form-group mb-2" id="div_berkasurl">
                    <label class="labelku"><b>Alamat URL:</b></label>
                    <input type="text" class="form-control form-control-sm"
                        value="{{ $data->berkas_sumber == 'eksternal' ? $data->berkas : '' }}"
                        id="berkas_url" name="berkas_url"
                        placeholder="https://alamaturl.com/namafile.pdf">
                </div>

                <div class="form-group mb-2">
                    <label class="labelku"><b>Tanggal publikasi:</b></label>
                    <div class="datepicker date input-group" style="width:200px;">
                        <input type="text" placeholder="Pilih tanggal"
                            value="{{ $data->tanggal_publikasi }}"
                            class="form-control" id="tanggal_publikasi" name="tanggal_publikasi">
                        <div class="input-group-append">
                            <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                        </div>
                    </div>
                </div>

            </div>

            <div class="col-md-12 text-right mt-2">
                <button type="submit" class="btn btn-info btn-sm">
                    <i class="feather icon-save"></i> Simpan
                </button>
                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">
                    <i class="feather icon-x"></i> Batal
                </button>
            </div>
        </div>
    </form>
</div>

<script type="text/javascript">
    $("#is_internal").change(function() {
        if ($(this).is(":checked")) {
            $('#div_berkas').show();
            $('#div_berkasurl').hide();
        } else {
            $('#div_berkas').hide();
            $('#div_berkasurl').show();
        }
    });

    $.validator.setDefaults({
        submitHandler: function() {
            event.preventDefault();
            $.ajax({
                url: "{{ url('/dap/infografis/saveupdate') }}",
                type: 'POST',
                data: new FormData($('#formulir')[0]),
                dataType: 'json',
                contentType: false,
                processData: false,
                success: function(resp) {
                    if (resp.result == "success") {
                        swal({ title: "", text: resp.message, icon: "success" })
                            .then(function() {
                                $('#modalku').modal('toggle');
                                reloadTable();
                            });
                    } else {
                        swal({ title: "", text: resp.message, icon: "error" });
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log('Error: ' + errorThrown);
                }
            });
        },
        errorPlacement: function(error, element) {
            var $parent = $(element).parents('.form-group');
            if (!$parent.find('.jquery-validation-error').length) {
                $parent.append(error.addClass('jquery-validation-error small form-text invalid-feedback'));
            }
        },
        highlight: function(element) {
            $(element).parents('.form-group').find('input, select, textarea').addClass('is-invalid');
        },
        unhighlight: function(element) {
            $(element).parents('.form-group').find('.is-invalid').removeClass('is-invalid');
        },
    });

    $(document).ready(function() {
        // Init show/hide berkas
        if ("{{ $data->berkas_sumber }}" == 'internal') {
            $('#div_berkas').show();
            $('#div_berkasurl').hide();
        } else {
            $('#div_berkas').hide();
            $('#div_berkasurl').show();
        }

        $('.datepicker').datepicker({
            language: "en",
            autoclose: true,
            format: "yyyy/mm/dd",
        });

        $('#formulir').validate({
            rules: {
                judul_infografis:  { required: true },
                tanggal_publikasi: { required: true },
            }
        });
    });
</script>