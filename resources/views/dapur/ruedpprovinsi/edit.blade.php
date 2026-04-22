<div class="modal-header">
    <h5 class="modal-title">{{ $judulmodal }}</h5>
    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
</div>
<div class="modal-body">
    <form id="formEdit">
        @csrf
        <input type="hidden" name="id_ruedp_provinsi" value="{{ $data->id_ruedp_provinsi }}">
        <div class="form-group row">
            <label class="col-sm-3 col-form-label">Nama Provinsi <span class="text-danger">*</span></label>
            <div class="col-sm-9">
                <select name="nama_provinsi" id="selectProvinsiEdit" class="form-control" required>
                    <option value="">-- Pilih Provinsi --</option>
                    @foreach ($provinsiList as $kode => $nama)
                        <option value="{{ $nama }}" data-kode="{{ $kode }}"
                            {{ $data->nama_provinsi == $nama ? 'selected' : '' }}>
                            {{ $nama }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
        <input type="hidden" name="kode_provinsi" id="kodeProvinsiEdit" value="{{ $data->kode_provinsi }}">
        <div class="form-group row">
            <label class="col-sm-3 col-form-label">Status <span class="text-danger">*</span></label>
            <div class="col-sm-9">
                <select name="id_ruedp_status" class="form-control" required>
                    <option value="">-- Pilih Status --</option>
                    @foreach ($statusList as $status)
                        <option value="{{ $status->id_ruedp_status }}"
                            {{ $data->id_ruedp_status == $status->id_ruedp_status ? 'selected' : '' }}>
                            {{ $status->nama_status }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-3 col-form-label">Nomor Perda</label>
            <div class="col-sm-9">
                <input type="text" name="nomor_perda" class="form-control" value="{{ $data->nomor_perda }}">
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-3 col-form-label">Tanggal Update</label>
            <div class="col-sm-9">
                <input type="date" name="tanggal_update" class="form-control" value="{{ $data->tanggal_update }}">
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-3 col-form-label">Keterangan</label>
            <div class="col-sm-9">
                <textarea name="keterangan" class="form-control" rows="3">{{ $data->keterangan }}</textarea>
            </div>
        </div>
    </form>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
    <button type="button" class="btn btn-primary" onclick="simpanUpdate()">Update</button>
</div>

<script>
    $('#selectProvinsiEdit').on('change', function() {
        var kode = $(this).find(':selected').data('kode');
        $('#kodeProvinsiEdit').val(kode);
    });

    function simpanUpdate() {
        $.ajax({
            url: "{{ url('/dap/ruedp-provinsi/saveupdate') }}",
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
