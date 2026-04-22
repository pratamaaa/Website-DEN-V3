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
                    <div class="form-group mb-1">
                        <label class="labelku" style="color: #626365;font-size:13px;"><b>Group:</b></label>
                        <select class="form-control" id="kategori_jabatan" name="kategori_jabatan" style="width: 90%;">
                            @if ($kategori->count() != 0)
                                @foreach ($kategori->get() as $kat)
                                    <option value="{{ $kat->kategori_organisasi }}">{{ $kat->nama_kategori }}</option>
                                @endforeach
                            @else
                                <option>Kategori not found</option>
                            @endif
                        </select>
                    </div>
                </div>
                <div class="form-group mb-1">
                    <label class="labelku" style="color: #626365;font-size:13px;"><b>Nama lengkap:</b></label>
                    <input type="input" class="form-control form-control-sm" id="namalengkap" name="namalengkap" style="width: 100%;">
                </div>
                <div class="form-group mb-1">
                    <label class="labelku" style="color: #626365;font-size:13px;"><b>Jabatan:</b></label>
                    <input type="input" class="form-control form-control-sm" id="jabatan" name="jabatan" style="width: 100%;">
                </div>
                <div class="form-group mb-1">
                    <label class="labelku" style="color: #626365;font-size:13px;"><b>Jabatan (EN):</b></label>
                    <input type="input" class="form-control form-control-sm" id="jabatan_en" name="jabatan_en" style="width: 100%;">
                </div>
                <div class="form-group mb-1">
                    <label class="labelku" style="color: #626365;font-size:13px;"><b>Gambar:</b></label>
                    <input type="file" class="form-control form-control-sm" id="gambar" name="gambar" style="width: 60%;">
                </div>
                <div class="form-group mb-1">
                    <label class="labelku" style="color: #626365;font-size:13px;"><b>Periode:</b></label>
                    <input type="input" class="form-control form-control-sm" id="periode" name="periode" style="width: 100%;">
                </div>
                <div class="form-group mb-1">
                    <label class="labelku" style="color: #626365;font-size:13px;"><b>Profil:</b></label>
                    <textarea class="form-control summernote" id="profil" name="profil" rows="3"></textarea>
                </div>
                <div class="form-group mb-0" style="margin-top:-20px !important;margin-bottom:-20px !important;">
                    <label class="labelku" style="color: #626365;font-size:13px;"><b>Profil (EN):</b></label>
                    <textarea class="form-control summernote" id="profil_en" name="profil_en" rows="3"></textarea>
                </div>
                <div class="form-group mb-1">
                    <label class="labelku" style="color: #626365;font-size:13px;"><b>Urutan:</b></label>
                    <input type="input" class="form-control form-control-sm" id="urutan" name="urutan" style="width: 20%;">
                </div>
                <div class="form-group mb-1">
                    <div class="form-group mb-1">
                        <label class="labelku" style="color: #626365;font-size:13px;"><b>Is active?:</b></label>
                        <select class="form-control" id="is_active" name="is_active" style="width: 20%;">
                            <option value="yes" selected>Ya</option>
                            <option value="no">Tidak</option>
                        </select>
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
            url: "{{ url('/dap/organisasiden/save') }}",
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
    
        $('#namalengkap').focus();
        $('.summernote').summernote({
            placeholder: '',
            tabsize: 2,
            height: 150,
        });
    
        $('#formulir').validate({
        rules: {
            kategori_jabatan: {
                required: true
            },
            namalengkap: {
                required: true
            },
            jabatan: {
                required: true
            },
            jabatan_en: {
                required: true
            },
            // gambar: {
            //     required: true
            // },
            periode: {
                required: true
            },
            periode_en: {
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