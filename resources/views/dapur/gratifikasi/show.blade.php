@if (!$data)
    <div class="p-3">
        <div class="alert alert-danger">
            Data tidak ditemukan
        </div>
    </div>
@else
    <div class="modal-header">
        <h5 class="modal-title">Detail Gratifikasi</h5>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
    </div>

    <div class="modal-body">

        <table class="table table-borderless table-sm">
            <tr>
                <td width="30%"><b>Nama</b></td>
                <td>: {{ $data->namalengkap }}</td>
            </tr>
            <tr>
                <td><b>Instansi</b></td>
                <td>: {{ $data->instansi ?? '-' }}</td>
            </tr>
            <tr>
                <td><b>Jenis</b></td>
                <td>: {{ $data->jenispenerimaan ?? '-' }}</td>
            </tr>
            <tr>
                <td><b>Nominal</b></td>
                <td>: {{ $data->nominal ?? '-' }}</td>
            </tr>
            <tr>
                <td><b>Tanggal</b></td>
                <td>: {{ $data->tanggal ?? '-' }}</td>
            </tr>
        </table>

        <hr>

        {{-- FILE --}}
        @if ($data->file_bukti)
            <div class="mb-2">
                <b>File Bukti:</b>
            </div>

            @php
                $ext = pathinfo($data->file_bukti, PATHINFO_EXTENSION);
            @endphp

            {{-- IMAGE --}}
            @if (in_array($ext, ['jpg', 'jpeg', 'png']))
                <img src="{{ asset('storage/' . $data->file_bukti) }}" class="img-fluid mb-2" style="max-height:400px;">

                {{-- PDF --}}
            @elseif($ext == 'pdf')
                <iframe src="{{ asset('storage/' . $data->file_bukti) }}" width="100%" height="400px"></iframe>

                {{-- OTHER --}}
            @else
                <a href="{{ asset('storage/' . $data->file_bukti) }}" target="_blank" class="btn btn-primary btn-sm">
                    Download File
                </a>
            @endif
        @else
            <p class="text-muted">Tidak ada file</p>
        @endif

    </div>

    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
    </div>

@endif
