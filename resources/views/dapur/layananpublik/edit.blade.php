<style>
    .margin1{margin-bottom:5px;}
    .margin2{margin-bottom: -8px;}
    .margin3{margin-bottom: 7px;}
    .warnafont{color: #000000 !important;}
    .labelku{margin-top: 5px !important; margin-bottom: 2px !important;font-weight: bold;font-size: 13px;}
    .warnamerah{color:red};
    </style>
    
    <div class="modal-header" style="background-color:#128D93;">
        <h5 class="modal-title h5" id="formProdukTambah_judul" style="color:#ffffff;">
        {{ $judulmodal }}
        </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    </div>
    <div class="modal-body" id="formtambah_body">
        <form id="formulir" name="formulir" method="post" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-md-12">
                <div class="form-group mb-1">
                    <label class="labelku" style="color: #626365;font-size:13px;"><b>Nama layanan publik:</b></label>
                    <input type="input" class="form-control form-control-sm" value="{{ $data->nama_layananpublik }}" id="nama_layananpublik" name="nama_layananpublik" style="width: 100%;">
                    <input type="hidden" class="form-control form-control-sm" value="{{ $data->id_layananpublik }}" id="id_layananpublik" name="id_layananpublik" style="width: 100%;">
                </div>
                <div class="form-group mb-1">
                    <label class="labelku" style="color: #626365;font-size:13px;"><b>Nama layanan publik (EN):</b></label>
                    <input type="input" class="form-control form-control-sm" value="{{ $data->nama_layananpublik_en }}" id="nama_layananpublik_en" name="nama_layananpublik_en" style="width: 100%;">
                </div>
                <div class="form-group mb-1">
                    <label class="labelku" style="color: #626365;font-size:13px;"><b>Deskripsi:</b></label>
                    <textarea class="form-control summernote" id="deskripsi" name="deskripsi" rows="3">{{ $data->deskripsi }}</textarea>
                </div>
                <div class="form-group mb-1">
                    <label class="labelku" style="color: #626365;font-size:13px;"><b>Deskripsi (EN):</b></label>
                    <textarea class="form-control summernote" id="deskripsi_en" name="deskripsi_en" rows="3">{{ $data->deskripsi_en }}</textarea>
                </div>
                <div class="form-group mb-1">
                    <label class="labelku" style="color: #626365;font-size:13px;"><b>Alamat URL:</b></label>
                    <input type="input" class="form-control form-control-sm" value="{{ $data->alamat_url }}" id="alamat_url" name="alamat_url" style="width: 100%;" placeholder="https://alamaturl.com">
                </div>
                <div class="form-group mb-1">
                    <label class="labelku" style="color: #626365;font-size:13px;"><b>Icon:</b></label>
                    <input type="input" class="form-control form-control-sm" value="{{ $data->icon }}" id="icon" name="icon" style="width: 100%;" placeholder="home">
                    <a href="{{ url('/dap/layananpublik/icons') }}" target="_blank">
                        <span style="font-size: 11px;" class="mt-4">
                            <i class="icon-menu icons"></i> Lihat daftar icon
                        </span>
                    </a>
                </div>
                <div class="form-group mb-1">
                    <label class="labelku" style="color: #626365;font-size:13px;"><b>Urutan:</b></label>
                    <input type="input" class="form-control form-control-sm" value="{{ $data->urutan }}" id="urutan" name="urutan" style="width: 20%;">
                </div>
            </div>
    
            <div class="col-md-12 text-right">
                <button type="submit" class="btn btn-info btn-sm" value="Simpan"><i class="feather icon-save"></i> Simpan</button>
                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal"><i class="feather icon-x"></i> Batal</button>
            </div>
        </div>
        </form>
    </div>
    
<script type="text/javascript">
    // $("#is_internal").change(function() {
    //     if ($('#is_internal').is(":checked")){
    //         $('#div_berkas').show()
    //         $('#div_berkasurl').hide()
    //     }else{
    //         $('#div_berkas').hide()
    //         $('#div_berkasurl').show()
    //     }
    // });
    $.validator.setDefaults({
      submitHandler: function(){
        event.preventDefault();
        proceed = true;
    
        $.ajax({
            url: "{{ url('/dap/layananpublik/saveupdate') }}",
            type: 'POST',
            data: new FormData($('#formulir')[0]),
            dataType: 'json',
            contentType: false,
            processData: false,
            success: function(resp){
                if (resp.result == "success"){
                    swal({
                        title: "",
                        text: resp.message,
                        icon: "success",
                    }).then(function(){
                        $('#modalku').modal('toggle');
                        reloadTable();
                    });
                }else{
                    var span = document.createElement("span");
                    span.innerHTML = resp.message+'<br><br>'+resp.errors;
                    swal({
                        html: true,
                        title: "",
                        content: span,
                        icon: "error",
                    }).then(function(){
                        $('#modalku').modal('toggle');
                        reloadTable();
                    });
                }
            },
            error: function(jqXHR, textStatus, errorThrown){
                    console.log('jqXMLHTTReq: '+jqXHR+', Status: '+textStatus+', Error: '+errorThrown);
                },
        });
      },
      errorPlacement: function errorPlacement(error, element) {
        var $parent = $(element).parents('.form-group');
        if ($parent.find('.jquery-validation-error').length) {
            return;
        }
        $parent.append(error.addClass('jquery-validation-error small form-text invalid-feedback'));
      },
      highlight: function(element) {
        var $el = $(element);
        var $parent = $el.parents('.form-group');
        $el.addClass('is-invalid');
        if ($el.hasClass('select2-hidden-accessible') || $el.attr('data-role') === 'tagsinput') {
            $el.parent().addClass('is-invalid');
        }
      },
      unhighlight: function(element) {
          $(element).parents('.form-group').find('.is-invalid').removeClass('is-invalid');
      },
    });
    
    $(document).ready(function(e){
        // $('#div_berkas').show()
        // $('#div_berkasurl').hide()
        $('.datepicker').datepicker({
            language: "en",
            autoclose: true,
            format: "yyyy/mm/dd",
        });
    
        $('#nama_layananpublik').focus();
        // $('.summernote').summernote({
        //     placeholder: '',
        //     tabsize: 2,
        //     height: 150,
        // });
    
        $('#formulir').validate({
            rules: {
            nama_layananpublik: {
                required: true
            },
            nama_layananpublik_en: {
                required: true
            },
            deskripsi: {
                required: true
            },
            deskripsi_en: {
                required: true
            },
            alamat_url: {
                required: true
            },
            icon: {
                required: true
            },
            urutan: {
                required: true,
                number: true,
            },
        }
        });
    });
    </script>