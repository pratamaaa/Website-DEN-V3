a@extends('layout.admin.app')

@section('content')
    <div class="card">
        <div class="card-header">
            <h5>Data Pelaporan Gratifikasi</h5>
        </div>

        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Instansi</th>
                        <th>Jenis</th>
                        <th>Tanggal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data as $i => $row)
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td>{{ $row->namalengkap }}</td>
                            <td>{{ $row->instansi }}</td>
                            <td>{{ $row->jenispenerimaan }}</td>
                            <td>{{ $row->tanggal }}</td>
                            <td>
                                <a href="{{ route('admin.gratifikasi.show', $row->id) }}"
                                    class="btn btn-sm btn-info">Detail</a>

                                <form action="{{ route('admin.gratifikasi.destroy', $row->id) }}" method="POST"
                                    style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button onclick="return confirm('Hapus data?')"
                                        class="btn btn-sm btn-danger">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

        </div>
    </div>
@endsection
