<div class="modal-header">
    <h5 class="modal-title">{{ $judulmodal }}</h5>
    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
</div>
<div class="modal-body">
    <form id="formAdd">
        @csrf
        <div class="form-group row">
            <label class="col-sm-3 col-form-label">Nama Provinsi <span class="text-danger">*</span></label>
            <div class="col-sm-9">
                <select name="nama_provinsi" id="selectProvinsi" class="form-control" required>
                    <option value="">-- Pilih Provinsi --</option>
                    @foreach ($provinsiList as $kode => $nama)
                        <option value="{{ $nama }}" data-kode="{{ $kode }}">{{ $nama }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <input type="hidden" name="kode_provinsi" id="kodeProvinsi">
        <div class="form-group row">
            <label class="col-sm-3 col-form-label">Status <span class="text-danger">*</span></label>
            <div class="col-sm-9">
                <select name="id_ruedp_status" class="form-control" required>
                    <option value="">-- Pilih Status --</option>
                    @foreach ($statusList as $status)
                        <option value="{{ $status->id_ruedp_status }}">{{ $status->nama_status }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-3 col-form-label">Nomor Perda</label>
            <div class="col-sm-9">
                <input type="text" name="nomor_perda" class="form-control" placeholder="Contoh: No. 3 Tahun 2022">
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-3 col-form-label">Tanggal Update</label>
            <div class="col-sm-9">
                <input type="date" name="tanggal_update" class="form-control">
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-3 col-form-label">Keterangan</label>
            <div class="col-sm-9">
                <textarea name="keterangan" class="form-control" rows="3"></textarea>
            </div>
        </div>
    </form>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
    <button type="button" class="btn btn-primary" onclick="simpan()">Simpan</button>
</div>

<script>
    // Auto-set kode provinsi dari pilihan
    $('#selectProvinsi').on('change', function() {
        var kode = $(this).find(':selected').data('kode');
        $('#kodeProvinsi').val(kode);
    });

    function simpan() {
        $.ajax({
            url: "{{ url('/dap/ruedp-provinsi/save') }}",
            type: 'POST',
            data: $('#formAdd').serialize(),
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
