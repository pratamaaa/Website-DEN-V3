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
                    <select class="form-control" id="kategori_berita" name="kategori_berita" style="width: 40%;">
                        @if ($kategori->count() != 0)
                            @foreach ($kategori->get() as $kat)
                                @if ($kat->id_berita_kategori == $data->id_berita_kategori)
                                    <option value="{{ $kat->id_berita_kategori }}" selected>{{ $kat->kategori_berita }}</option>
                                @else
                                    <option value="{{ $kat->id_berita_kategori }}">{{ $kat->kategori_berita }}</option>
                                @endif
                            @endforeach
                        @else
                            <option>Kategori not found</option>
                        @endif
                    </select>
                </div>
                <div class="form-group mb-1">
                    <label class="labelku" style="color: #626365;font-size:13px;"><b>Judul:</b></label>
                    <input type="input" class="form-control form-control-sm" value="{{ $data->judul }}" id="judul" name="judul" style="width: 100%;">
                    <input type="hidden" class="form-control form-control-sm" value="{{ $data->id_berita }}" id="id_berita" name="id_berita" style="width: 100%;">
                </div>
                <div class="form-group mb-1">
                    <label class="labelku" style="color: #626365;font-size:13px;"><b>Judul (EN):</b></label>
                    <input type="input" class="form-control form-control-sm" value="{{ $data->judul_en }}" id="judul_en" name="judul_en" style="width: 100%;">
                </div>
                <div class="form-group mb-1">
                    <label class="labelku" style="color: #626365;font-size:13px;"><b>Isi Berita:</b></label>
                    <textarea class="form-control summernote" id="isi_berita" name="isi_berita" rows="5">{{ $data->isi_berita }}</textarea>
                </div>
                <div class="form-group" style="margin-top:-20px !important;margin-bottom:-20px !important;">
                    <label class="labelku" style="color: #626365;font-size:13px;"><b>Isi Berita (EN):</b></label>
                    <textarea class="form-control summernote" id="isi_berita_en" name="isi_berita_en" rows="5">{{ $data->isi_berita_en }}</textarea>
                </div>
                <div class="form-group mb-1">
                    <label class="labelku" style="color: #626365;font-size:13px;"><b>Gambar:</b></label>
                    <input type="file" class="form-control form-control-sm" id="gambar" name="gambar" style="width: 40%;">
                    <input type="hidden" class="form-control form-control-sm" value="{{ $data->gambar }}" id="gambar_current" name="gambar_current" style="width: 40%;">
                    {{-- <br> --}}
                    @if ($data->gambar != "")
                        <img src="{{ asset('uploads/berita/'.$data->gambar) }}" width="150px" style="margin-top:10px;border-radius:5px;border:1px solid #cdcdcd;">
                    @else
                        <img src="{{ asset('mainpro/images/no-image.jpg') }}" width="100px" style="margin-top:10px;border-radius:5px;border:1px solid #cdcdcd;">
                    @endif
                </div>
                <div class="form-group mb-1">
                    <label class="labelku" style="color: #626365;font-size:13px;"><b>Status berita:</b></label>
                    <select class="form-control" id="status" name="status" style="width: 20%;">
                        @if ($status->count() != 0)
                            @foreach ($status->get() as $kat)
                                @if ($kat->id_status_berita == $data->id_status_berita)
                                    <option value="{{ $kat->id_status_berita }}" selected>{{ ucfirst($kat->status_berita) }}</option>
                                @else
                                    <option value="{{ $kat->id_status_berita }}">{{ ucfirst($kat->status_berita) }}</option>
                                @endif
                            @endforeach
                        @else
                            <option>Status berita not found</option>
                        @endif
                    </select>
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
    $.validator.setDefaults({
      submitHandler: function(){
        event.preventDefault();
        proceed = true;
    
        $.ajax({
            url: "{{ url('/dap/berita/saveupdate') }}",
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
            height: 200,
        });
    
        $('#formulir').validate({
        rules: {
            kategori_berita: {
                required: true
            },
            judul: {
                required: true
            },
            judul_en: {
                required: true
            },
            isi_berita: {
                required: true
            },
            isi_berita_en: {
                required: true
            },
            status: {
                required: true
            },
            tanggal_publikasi: {
                required: true
            }
        }
        });
    });
    </script>