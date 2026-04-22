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
                    <label class="labelku" style="color: #626365;font-size:13px;"><b>Kategori:</b></label>
                    <select class="form-control" id="id_profil_kategori" name="id_profil_kategori" style="width: 30%;">
                        @if ($kategori->count() != 0)
                            @foreach ($kategori->get() as $kat)
                                if ($kat->id_profil_kategori == $data->id_profil_kategori)
                                    <option value="{{ $kat->id_profil_kategori }}" selected>{{ strtoupper($kat->nama_kategori) }}</option>
                                else
                                    <option value="{{ $kat->id_profil_kategori }}">{{ strtoupper($kat->nama_kategori) }}</option>
                            @endforeach
                        @else
                            <option>Kategori not found</option>
                        @endif
                    </select>
                </div>
                <div class="form-group mb-1">
                    <label class="labelku" style="color: #626365;font-size:13px;"><b>Judul:</b></label>
                    <input type="input" class="form-control form-control-sm" value="{{ $data->judul }}" id="judul" name="judul" style="width: 100%;">
                    <input type="hidden" class="form-control form-control-sm" value="{{ $data->id_profil }}" id="id_profil" name="id_profil" style="width: 100%;">
                </div>
                <div class="form-group mb-1">
                    <label class="labelku" style="color: #626365;font-size:13px;"><b>Judul (EN):</b></label>
                    <input type="input" class="form-control form-control-sm" value="{{ $data->judul_en }}" id="judul_en" name="judul_en" style="width: 100%;">
                </div>
                <div class="form-group mb-1">
                    <label class="labelku" style="color: #626365;font-size:13px;"><b>Judul menu:</b></label>
                    <input type="input" class="form-control form-control-sm" value="{{ $data->judulmenu }}" id="judulmenu" name="judulmenu" style="width: 100%;">
                </div>
                <div class="form-group mb-1">
                    <label class="labelku" style="color: #626365;font-size:13px;"><b>Judul menu (EN):</b></label>
                    <input type="input" class="form-control form-control-sm" value="{{ $data->judulmenu_en }}" id="judulmenu_en" name="judulmenu_en" style="width: 100%;">
                </div>
                <div class="form-group mb-1">
                    <label class="labelku" style="color: #626365;font-size:13px;"><b>Konten profil:</b></label>
                    <textarea class="form-control summernote" id="konten" name="konten" rows="5">{{ $data->konten }}</textarea>
                </div>
                <div class="form-group" style="margin-top:-20px !important;margin-bottom:-20px !important;">
                    <label class="labelku" style="color: #626365;font-size:13px;"><b>Konten profil (EN):</b></label>
                    <textarea class="form-control summernote" id="konten_en" name="konten_en" rows="5">{{ $data->konten_en }}</textarea>
                </div>
                <div class="form-group mb-1">
                    <label class="labelku" style="color: #626365;font-size:13px;"><b>Is page:</b></label>
                    <select class="form-control" id="is_page" name="is_page" style="width: 20%;">
                        @if ($data->is_page == 'no')
                        <option value="no" selected>Tidak</option>
                        <option value="yes">Ya</option>
                        @else
                        <option value="no">Tidak</option>
                        <option value="yes" selected>Ya</option>
                        @endif
                    </select>
                </div>
                <div class="form-group mb-1">
                    <label class="labelku" style="color: #626365;font-size:13px;"><b>Nama page:</b></label>
                    <input type="input" class="form-control form-control-sm" value="{{ $data->nama_page }}" id="nama_page" name="nama_page" style="width: 50%;">
                </div>
                <div class="form-group mb-1">
                    <label class="labelku" style="color: #626365;font-size:13px;"><b>Urutan:</b></label>
                    <input type="input" class="form-control form-control-sm" value="{{ $data->urutan }}" id="urutan" name="urutan" style="width: 10%;">
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
    $("#is_page").change(function() {
        if ($('#is_page').val() == 'yes'){
            $('#nama_page').prop("disabled", false);
            $('#nama_page').focus();
            $('#nama_page').val("{{ $data->nama_page }}");
        }else{
            $('#nama_page').prop("disabled", true);
            $('#nama_page').val('');
        }
    });
    
    $.validator.setDefaults({
      submitHandler: function(){
        event.preventDefault();
        proceed = true;
    
        $.ajax({
            url: "{{ url('/dap/profilden/saveupdate') }}",
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
        if ("{{ $data->is_page }}" == "no"){
            $('#nama_page').prop("disabled", true);
        }else{
            $('#nama_page').prop("disabled", false);
        }

        $('.datepicker').datepicker({
            language: "en",
            autoclose: true,
            format: "yyyy/mm/dd",
        });
    
        $('#judul').focus();
        $('.summernote').summernote({
            placeholder: '',
            tabsize: 2,
            height: 200,
        });
    
        $('#formulir').validate({
        rules: {
            judul: {
                required: true
            },
            judulmenu: {
                required: true
            },
            judul_en: {
                required: true
            },
            judulmenu_en: {
                required: true
            },
            konten: {
                required: true
            },
            konten_en: {
                required: true
            },
            nama_page: {
                required: true
            },
            urutan: {
                required: true, number: true,
            },
        }
        });
    });
    </script>