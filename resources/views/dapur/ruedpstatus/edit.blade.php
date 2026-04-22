<div class="modal-header">
    <h5 class="modal-title">{{ $judulmodal }}</h5>
    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
</div>
<div class="modal-body">
    <form id="formEdit">
        @csrf
        <input type="hidden" name="id_ruedp_status" value="{{ $data->id_ruedp_status }}">
        <div class="form-group row">
            <label class="col-sm-3 col-form-label">Nama Status <span class="text-danger">*</span></label>
            <div class="col-sm-9">
                <input type="text" name="nama_status" class="form-control" value="{{ $data->nama_status }}" required>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-3 col-form-label">Warna <span class="text-danger">*</span></label>
            <div class="col-sm-9">
                <div class="input-group">
                    <input type="color" name="warna" class="form-control" value="{{ $data->warna }}"
                        style="height:38px;padding:2px;width:60px;">
                    <input type="text" id="warnaHexEdit" class="form-control" value="{{ $data->warna }}"
                        placeholder="#000000" style="max-width:120px;">
                </div>
                <small class="text-muted">Pilih warna untuk provinsi di peta.</small>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-3 col-form-label">Urutan</label>
            <div class="col-sm-9">
                <input type="number" name="urutan" class="form-control" value="{{ $data->urutan }}">
            </div>
        </div>
    </form>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
    <button type="button" class="btn btn-primary" onclick="simpanUpdate()">Update</button>
</div>

<script>
    $('input[name="warna"]').on('input', function() {
        $('#warnaHexEdit').val($(this).val());
    });
    $('#warnaHexEdit').on('input', function() {
        $('input[name="warna"]').val($(this).val());
    });

    function simpanUpdate() {
        $('input[name="warna"]').val($('#warnaHexEdit').val());

        $.ajax({
            url: "{{ url('/dap/ruedp-status/saveupdate') }}",
            type: 'POST',
            data: $('#formEdit').serialize(),
            dataType: 'json',
            success: function(resp) {
                if (resp.result == 'success') {
                    swal({
                            title: "",
                            text: resp.message,
                            icon: "success"
                        })
                        .then(function() {
                            $('#modalku').modal('hide');
                            reloadTable();
                        });
                } else {
                    swal({
                        title: "Gagal",
                        text: resp.message,
                        icon: "error"
                    });
                }
            }
        });
    }
</script>
