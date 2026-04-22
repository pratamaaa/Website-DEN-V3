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
                    <label class="labelku" style="color: #626365;font-size:13px;"><b>Nama organisasi:</b></label>
                    <input type="input" class="form-control form-control-sm" value="{{ $data->nama_organisasi }}" id="nama_organisasi" name="nama_organisasi" style="width: 100%;">
                    <input type="hidden" class="form-control form-control-sm" value="{{ $data->id_identitas_organisasi }}" id="id_identitas_organisasi" name="id_identitas_organisasi" style="width: 100%;">
                </div>
                <div class="form-group mb-1">
                    <label class="labelku" style="color: #626365;font-size:13px;"><b>Nama organisasi (EN):</b></label>
                    <input type="input" class="form-control form-control-sm" value="{{ $data->nama_organisasi_en }}" id="nama_organisasi_en" name="nama_organisasi_en" style="width: 100%;">
                </div>
                <div class="form-group mb-1">
                    <label class="labelku" style="color: #626365;font-size:13px;"><b>Logo:</b></label>
                    <input type="file" class="form-control form-control-sm" id="logo" name="logo" style="width: 60%;">
                    <input type="hidden" class="form-control form-control-sm" value="{{ $data->logo }}" id="logo_current" name="logo_current" style="width: 40%;">
                    {{-- <br> --}}
                    @if ($data->logo != "")
                        <img src="{{ asset('uploads/logo/'.$data->logo) }}" width="150px" style="margin-top:10px;border-radius:5px;border:1px solid #cdcdcd;">
                    @else
                        <img src="{{ asset('mainpro/images/no-image.jpg') }}" width="100px" style="margin-top:10px;border-radius:5px;border:1px solid #cdcdcd;">
                    @endif
                </div>
                <div class="form-group" style="margin-top:0px !important;margin-bottom:0px !important;">
                    <label class="labelku" style="color: #626365;font-size:13px;"><b>Alamat:</b></label>
                    <textarea class="form-control" id="alamat" name="alamat" rows="5">{{ $data->alamat }}</textarea>
                </div>
                <div class="form-group" style="margin-top:0px !important;margin-bottom:0px !important;">
                    <label class="labelku" style="color: #626365;font-size:13px;"><b>Alamat (EN):</b></label>
                    <textarea class="form-control" id="alamat_en" name="alamat_en" rows="5">{{ $data->alamat_en }}</textarea>
                </div>
                <div class="form-group mb-1">
                    <label class="labelku" style="color: #626365;font-size:13px;"><b>Telpon:</b></label>
                    <input type="input" class="form-control form-control-sm" value="{{ $data->telpon }}" id="telpon" name="telpon" style="width: 100%;">
                </div>
                <div class="form-group mb-1">
                    <label class="labelku" style="color: #626365;font-size:13px;"><b>Fax:</b></label>
                    <input type="input" class="form-control form-control-sm" value="{{ $data->fax }}" id="fax" name="fax" style="width: 100%;">
                </div>
                <div class="form-group mb-1">
                    <label class="labelku" style="color: #626365;font-size:13px;"><b>Email:</b></label>
                    <input type="input" class="form-control form-control-sm" value="{{ $data->email }}" id="email" name="email" style="width: 100%;">
                </div>
                <div class="form-group mb-1">
                    <label class="labelku" style="color: #626365;font-size:13px;"><b>Instagram:</b></label>
                    <input type="input" class="form-control form-control-sm" value="{{ $data->instagram }}" id="instagram" name="instagram" style="width: 100%;">
                </div>
                <div class="form-group mb-1">
                    <label class="labelku" style="color: #626365;font-size:13px;"><b>Facebook:</b></label>
                    <input type="input" class="form-control form-control-sm" value="{{ $data->facebook }}" id="facebook" name="facebook" style="width: 100%;">
                </div>
                <div class="form-group mb-1">
                    <label class="labelku" style="color: #626365;font-size:13px;"><b>X (Twitter):</b></label>
                    <input type="input" class="form-control form-control-sm" value="{{ $data->twitter }}" id="twitter" name="twitter" style="width: 100%;">
                </div>
                <div class="form-group mb-1">
                    <label class="labelku" style="color: #626365;font-size:13px;"><b>Youtube:</b></label>
                    <input type="input" class="form-control form-control-sm" value="{{ $data->youtube }}" id="youtube" name="youtube" style="width: 100%;">
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
    $.validator.setDefaults({
      submitHandler: function(){
        event.preventDefault();
        proceed = true;
    
        $.ajax({
            url: "{{ url('/dap/identitasorganisasi/saveupdate') }}",
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
        $('.datepicker').datepicker({
            language: "en",
            autoclose: true,
            format: "yyyy/mm/dd",
        });
    
        $('#judul').focus();
        $('.summernote').summernote({
            placeholder: '',
            tabsize: 2,
            height: 100,
        });
    
        $('#formulir').validate({
            rules: {
                nama_organisasi: {
                    required: true
                },
                nama_organisasi_en: {
                    required: true
                },
                alamat: {
                    required: true
                },
                alamat_en: {
                    required: true
                },
                telpon: {
                    required: true
                },
                fax: {
                    required: true
                },
                email: {
                    required: true
                },
            }
        });
    });
    </script>