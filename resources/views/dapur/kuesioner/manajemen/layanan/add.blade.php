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
	            <select class="form-control" id="kategori_rb" name="kategori_rb" style="width: 70%;">
                    @if ($kategori->count() != 0)
                        @foreach ($kategori->get() as $kat)
                            <option value="{{ $kat->id_rbkategori }}">{{ $kat->nama_rbkategori }}</option>
                        @endforeach
                    @else
                        <option>Kategori not found</option>
                    @endif
                </select>
            </div>
            <div class="form-group mb-1">
                <label class="labelku" style="color: #626365;font-size:13px;"><b>Judul:</b></label>
	            <input type="input" class="form-control form-control-sm" id="judul" name="judul" style="width: 100%;">
            </div>
            <div class="form-group mb-1">
                <label class="labelku" style="color: #626365;font-size:13px;"><b>Judul (EN):</b></label>
	            <input type="input" class="form-control form-control-sm" id="judul_en" name="judul_en" style="width: 100%;">
            </div>
            <div class="form-group mb-1">
                <label class="labelku" style="color: #626365;font-size:13px;"><b>Deskripsi:</b></label>
                <textarea class="form-control summernote" id="deskripsi" name="deskripsi" rows="5"></textarea>
            </div>
            <div class="form-group" style="margin-top:-20px !important;margin-bottom:-20px !important;">
                <label class="labelku" style="color: #626365;font-size:13px;"><b>Deskripsi (EN):</b></label>
	            <textarea class="form-control summernote" id="deskripsi_en" name="deskripsi_en" rows="5"></textarea>
            </div>
            <div class="form-group mb-1">
                <label class="labelku" style="color: #626365;font-size:13px;"><b>Gambar:</b></label>
	            <input type="file" class="form-control form-control-sm" id="gambar" name="gambar" style="width: 60%;">
            </div>
            <div class="form-group mb-1">
                <label class="labelku" style="color: #626365;font-size:13px;"><b>Upload berkas:</b></label>
                <input type="file" class="form-control form-control-sm" id="berkas" name="berkas" style="width: 60%;">
            </div>
            <div class="form-group mb-1">
                <label class="labelku" style="color: #626365;font-size:13px;"><b>Tanggal publikasi:</b></label>
                <div class="datepicker date input-group" style="width:200px;">
                  <input type="text" placeholder="Pilih tanggal" value="{{ date('Y/m/d') }}" class="form-control" id="tanggal_publikasi" name="tanggal_publikasi">
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
        url: "{{ url('/dap/rb/save') }}",
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

    $('#kategori_rb').focus();
    $('.summernote').summernote({
        placeholder: '',
        tabsize: 2,
        height: 100,
    });

    $('#formulir').validate({
    rules: {
        kategori_rb: {
            required: true
        },
        judul: {
            required: true
        },
        judul_en: {
            required: true
        },
        deskripsi: {
            required: true
        },
        deskripsi_rb: {
            required: true
        },
        berkas: {
            required: true
        },
        tanggal_publikasi: {
            required: true
        },
    }
    });
});
</script>