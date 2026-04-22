<div class="modal-header">
    <h5 class="modal-title">{{ $judulmodal }}</h5>
    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
</div>
<div class="modal-body">
    <form id="formEdit" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="id_so" value="{{ $data->id_so }}">
        <input type="hidden" name="foto_current" value="{{ $data->foto }}">

        <div class="form-group row">
            <label class="col-sm-3 col-form-label">Atasan</label>
            <div class="col-sm-9">
                <select name="id_parent" class="form-control">
                    <option value="0" {{ $data->id_parent == 0 ? 'selected' : '' }}>-- Top Level --</option>
                    @foreach ($parents as $parent)
                        <option value="{{ $parent->id_so }}" {{ $data->id_parent == $parent->id_so ? 'selected' : '' }}>
                            {{ $parent->jabatan }} — {{ $parent->nama_lengkap }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-3 col-form-label">Nama Lengkap <span class="text-danger">*</span></label>
            <div class="col-sm-9">
                <input type="text" name="nama_lengkap" class="form-control" value="{{ $data->nama_lengkap }}"
                    required>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-3 col-form-label">Jabatan <span class="text-danger">*</span></label>
            <div class="col-sm-9">
                <input type="text" name="jabatan" class="form-control" value="{{ $data->jabatan }}" required>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-3 col-form-label">Foto</label>
            <div class="col-sm-9">
                @if ($data->foto)
                    <div class="mb-2">
                        <img src="{{ asset('uploads/strukturorganisasi/' . $data->foto) }}" width="60"
                            height="60" style="border-radius:50%;object-fit:cover;border:2px solid #ddd;">
                    </div>
                @endif
                <input type="file" name="foto" class="form-control" accept="image/*">
                <small class="text-muted">Kosongkan jika tidak ingin mengubah foto.</small>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-3 col-form-label">Urutan</label>
            <div class="col-sm-9">
                <input type="number" name="urutan" class="form-control" value="{{ $data->urutan }}">
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-3 col-form-label">Status</label>
            <div class="col-sm-9">
                <select name="is_active" class="form-control">
                    <option value="yes" {{ $data->is_active == 'yes' ? 'selected' : '' }}>Aktif</option>
                    <option value="no" {{ $data->is_active == 'no' ? 'selected' : '' }}>Non-Aktif</option>
                </select>
            </div>
        </div>
    </form>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
    <button type="button" class="btn btn-primary" onclick="simpanUpdate()">Update</button>
</div>

<script>
    function simpanUpdate() {
        var form = document.getElementById('formEdit');
        console.log('form:', form);
        console.log('foto files:', form.querySelector('[name="foto"]').files);

        var formData = new FormData(form);
        console.log('FormData entries:');
        for (var pair of formData.entries()) {
            console.log(pair[0] + ': ' + pair[1]);
        }

        $.ajax({
            url: "{{ url('/dap/strukturorganisasi/saveupdate') }}",
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
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.log('Error: ' + errorThrown);
                console.log(jqXHR.responseText);
            }
        });
    }
</script>
