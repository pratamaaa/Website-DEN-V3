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
                    <select class="form-control" id="kategori_publikasi" name="kategori_publikasi" style="width: 60%;">
                        @if ($kategori->count() != 0)
                            @foreach ($kategori->get() as $kat)
                                @if ($kat->id_publikasi_kategori == $data->id_publikasi_kategori)
                                    <option value="{{ $kat->id_publikasi_kategori }}" selected>{{ $kat->nama_kategori }}</option>
                                @else
                                    <option value="{{ $kat->id_publikasi_kategori }}">{{ $kat->nama_kategori }}</option>
                                @endif
                            @endforeach
                        @else
                            <option>Kategori not found</option>
                        @endif
                    </select>
                </div>
                <div class="form-group mb-1">
                    <label class="labelku" style="color: #626365;font-size:13px;"><b>Judul publikasi:</b></label>
                    <input type="input" class="form-control form-control-sm" value="{{ $data->judul_publikasi }}" id="judul_publikasi" name="judul_publikasi" style="width: 100%;">
                    <input type="hidden" class="form-control form-control-sm" value="{{ $data->id_publikasi }}" id="id_publikasi" name="id_publikasi" style="width: 100%;">
                </div>
                <div class="form-group mb-1">
                    <label class="labelku" style="color: #626365;font-size:13px;"><b>Judul publikasi (EN):</b></label>
                    <input type="input" class="form-control form-control-sm" value="{{ $data->judul_publikasi_en }}" id="judul_publikasi_en" name="judul_publikasi_en" style="width: 100%;">
                </div>
                <div class="form-group mb-1">
                    <label class="labelku" style="color: #626365;font-size:13px;"><b>Deskripsi:</b></label>
                    <textarea class="form-control summernote" id="deskripsi" name="deskripsi" rows="3">{{ $data->deskripsi }}</textarea>
                </div>
                <div class="form-group" style="margin-top:-20px !important;margin-bottom:-20px !important;">
                    <label class="labelku" style="color: #626365;font-size:13px;"><b>Deskripsi (EN):</b></label>
                    <textarea class="form-control summernote" id="deskripsi_en" name="deskripsi_en" rows="3">{{ $data->deskripsi_en }}</textarea>
                </div>
                <div class="form-group mb-1">
                    <label class="labelku" style="color: #626365;font-size:13px;"><b>Gambar sampul:</b></label>
                    <input type="file" class="form-control form-control-sm" id="gambar" name="gambar" style="width: 60%;">
                    <input type="hidden" class="form-control form-control-sm" value="{{ $data->gambar_sampul }}" id="gambar_current" name="gambar_current" style="width: 60%;margin-bottom:5px;">
                    @if ($data->gambar_sampul != "")
                        <img src="{{ asset('uploads/publikasi-image/'.$data->gambar_sampul) }}" width="150px" style="margin-top:10px;border-radius:5px;border:1px solid #cdcdcd;">
                    @else
                        <img src="{{ asset('mainpro/images/no-image.jpg') }}" width="100px" style="margin-top:10px;border-radius:5px;border:1px solid #cdcdcd;">
                    @endif
                </div>
                <div class="form-group mb-1 mt-3">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="is_internal" name="is_internal" {{ $data->berkas_sumber == "internal" ? "checked" : "" }}>
                        <label class="form-check-label" for="gridCheck1" style="color: #626365;font-size:13px;">
                        Sumber berkas dari internal
                        </label>
                    </div>
                </div>
                <div class="form-group mb-1" id="div_berkas">
                    <label class="labelku" style="color: #626365;font-size:13px;"><b>Upload berkas:</b></label>
                    <input type="file" class="form-control form-control-sm" id="berkas" name="berkas" style="width: 60%;margin-bottom:5px;">
                    <input type="hidden" class="form-control form-control-sm" value="{{ $data->berkas }}" id="berkas_current" name="berkas_current" style="width: 60%;margin-bottom:5px;">
                    <a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#modalku" onclick="showFormRead('{{ $data->id_publikasi }}', 'publikasi')">
                        <span style="margin-top:10px !important;"><i class="feather icon-download-cloud"></i> {{ $data->berkas }}</span>
                    </a>
                </div>
                <div class="form-group mb-1" id="div_berkasurl">
                    <label class="labelku" style="color: #626365;font-size:13px;"><b>Alamat Url:</b></label>
                    <input type="text" class="form-control form-control-sm" value="{{ $data->berkas }}" id="berkas_url" name="berkas_url" style="width: 100%;" placeholder="https://alamaturl.com/namafile.pdf">
                </div>
                <div class="form-group mb-1">
                    <label class="labelku" style="color: #626365;font-size:13px;"><b>Tanggal publikasi:</b></label>
                    <div class="datepicker date input-group" style="width:200px;">
                      <input type="text" placeholder="Pilih tanggal" value="{{ $data->tanggal_publikasi }}" class="form-control" id="tanggal_publikasi" name="tanggal_publikasi">
                      <div class="input-group-append">
                        <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                      </div>
                    </div>
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
    $("#is_internal").change(function() {
        if ($('#is_internal').is(":checked")){
            $('#div_berkas').show()
            $('#div_berkasurl').hide()
        }else{
            $('#div_berkas').hide()
            $('#div_berkasurl').show()
        }
    });
    $.validator.setDefaults({
      submitHandler: function(){
        event.preventDefault();
        proceed = true;
    
        $.ajax({
            url: "{{ url('/dap/publikasi/saveupdate') }}",
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
       if ("{{ $data->berkas_sumber }}" == 'internal'){
            $('#div_berkas').show()
            $('#div_berkasurl').hide()
        }else{
            $('#div_berkas').hide()
            $('#div_berkasurl').show()
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
            height: 100,
        });
    
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
        }
        });
    });
    </script>