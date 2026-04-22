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
                <label class="labelku" style="color: #626365;font-size:13px;"><b>Nama kategori</b></label>
	            <input type="input" class="form-control form-control form-control-sm" id="nama_kategori" name="nama_kategori" style="width: 100%;">
            </div>
            <div class="form-group mb-1 mt-3">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="is_parent" name="is_parent" checked>
                    <label class="form-check-label" for="gridCheck1" style="color: #626365;font-size:13px;">
                    Sebagai kategori induk
                    </label>
                </div>
            </div>
            <div class="form-group mb-1">
                <label class="labelku" style="color: #626365;font-size:13px;"><b>Kategori induk</b></label>
                <select class="form-control form-control-sm" id="kategori_induk" name="kategori_induk" style="width: 80%">
                    @if ($kategoriinduk->count() == 0)
                        <option>Mohon input kategori publikasi terlebih dahulu</option>
                    @else
                        @foreach ($kategoriinduk->get() as $kat)
                            {{-- <option value="{{ $kat->id_publikasi_kategori==$data->id_publikasi_kategori ? "selected" : "" }}">{{ $kat->nama_kategori }}</option> --}}
                            <option value="{{ $kat->id_publikasi_kategori }}">{{ $kat->nama_kategori }}</option>
                        @endforeach
                    @endif
                </select>
            </div>
            <div class="form-group mb-1">
                <label class="labelku" style="color: #626365;font-size:13px;"><b>Group Informasi Publik</b></label>
                <select class="form-control form-control-sm" id="group_infopublik" name="group_infopublik" style="width: 60%">
                    @if ($groupinfopublik->count() == 0)
                        <option>Mohon input kategori publikasi terlebih dahulu</option>
                    @else
                        @foreach ($groupinfopublik->get() as $grp)
                            <option value="{{ $grp->id_informasipublik }}">{{ $grp->informasipublik }}</option>
                        @endforeach
                    @endif
                </select>
            </div>
            <div class="form-group mb-1">
                <label class="labelku" style="color: #626365;font-size:13px;"><b>Is a page?</b></label>
	            <select class="form-control form-control-sm" id="is_page" name="is_page" style="width: 30%">
                    <option value="yes">Ya</option>
                    <option value="no">Tidak</option>
                </select>
            </div>
            <div class="form-group mb-1">
                <label class="labelku" style="color: #626365;font-size:13px;"><b>Alamat URL</b></label>
	            <input type="input" class="form-control form-control form-control-sm" id="url" name="url" style="width: 100%;">
            </div>
            <div class="form-group mb-1">
                <label class="labelku" style="color: #626365;font-size:13px;"><b>Urutan</b></label>
	            <input type="input" class="form-control form-control form-control-sm" id="urutan" name="urutan" style="width: 20%;">
            </div>
            <div class="form-group mb-1">
                <label class="labelku" style="color: #626365;font-size:13px;"><b>Is Active?</b></label>
	            <select class="form-control form-control-sm" id="is_active" name="is_active" style="width: 30%">
                    <option value="yes">Ya</option>
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
$("#is_parent").change(function() {
    if ($('#is_parent').is(":checked")){
        $('#kategori_induk').prop('disabled', true)
    }else{
        $('#kategori_induk').prop('disabled', false)
    }
});

$.validator.setDefaults({
  submitHandler: function(){
    event.preventDefault();
    proceed = true;

    $.ajax({
        url: "{{ url('/kategoripublikasi/save') }}",
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
    $('#nama_kategori').focus();
    $('#kategori_induk').prop('disabled', true)

    $('#formulir').validate({
    rules: {
        nama_kategori: {
            required: true
        },
        url: {
            required: true
        },
        urutan: {
            required: true
        },
    }
    });
});
</script>