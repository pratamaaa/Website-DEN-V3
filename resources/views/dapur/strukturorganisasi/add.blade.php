<div class="modal-header">
    <h5 class="modal-title">{{ $judulmodal }}</h5>
    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
</div>
<div class="modal-body">
    <form id="formAdd" enctype="multipart/form-data">
        @csrf
        <div class="form-group row">
            <label class="col-sm-3 col-form-label">Atasan</label>
            <div class="col-sm-9">
                <select name="id_parent" class="form-control">
                    <option value="0">-- Top Level --</option>
                    @foreach ($parents as $parent)
                        <option value="{{ $parent->id_so }}">
                            {{ $parent->jabatan }} — {{ $parent->nama_lengkap }}
                        </option>
                    @endforeach
                </select>
                <small class="text-muted">Kosongkan (Top Level) jika tidak punya atasan.</small>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-3 col-form-label">Nama Lengkap <span class="text-danger">*</span></label>
            <div class="col-sm-9">
                <input type="text" name="nama_lengkap" class="form-control" required>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-3 col-form-label">Jabatan <span class="text-danger">*</span></label>
            <div class="col-sm-9">
                <input type="text" name="jabatan" class="form-control" required>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-3 col-form-label">Foto</label>
            <div class="col-sm-9">
                <input type="file" name="foto" class="form-control" accept="image/*">
                <small class="text-muted">Format: JPG, PNG. Maks 2MB.</small>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-3 col-form-label">Urutan</label>
            <div class="col-sm-9">
                <input type="number" name="urutan" class="form-control" value="0">
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-3 col-form-label">Status</label>
            <div class="col-sm-9">
                <select name="is_active" class="form-control">
                    <option value="yes">Aktif</option>
                    <option value="no">Non-Aktif</option>
                </select>
            </div>
        </div>
    </form>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
    <button type="button" class="btn btn-primary" onclick="simpan()">Simpan</button>
</div>

<script>
    function simpan() {
        var formData = new FormData($('#formAdd')[0]);
        $.ajax({
            url: "{{ url('/dap/strukturorganisasi/save') }}",
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
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
