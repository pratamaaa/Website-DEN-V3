@extends('layout.dapur.app')

@section('title', 'Reset MFA')

@section('content')

    <div class="pcoded-main-container">
        <div class="pcoded-content">

            <div class="page-header">
                <div class="page-block">
                    <div class="row align-items-center">
                        <div class="col-md-12">

                            <div class="page-header-title">
                                <h5 class="m-b-10">Manajemen Reset MFA</h5>
                            </div>

                            {{-- <ul class="breadcrumb">
                                <li class="breadcrumb-item">
                                    <a href="{{ route('dashboard') }}">
                                        <i class="feather icon-home"></i>
                                    </a>
                                </li>
                                <li class="breadcrumb-item">Reset MFA</li>
                            </ul> --}}

                        </div>
                    </div>
                </div>
            </div>


            <div class="page-wrapper">
                <div class="page-body">

                    <div class="row">

                        <div class="col-sm-12">

                            <div class="card">

                                <div class="card-header">
                                    <h5>Daftar Pengguna</h5>
                                </div>


                                <div class="card-body">

                                    @if (session('success'))
                                        <div class="alert alert-success alert-dismissible fade show">

                                            {{ session('success') }}

                                            <button type="button" class="close" data-dismiss="alert">
                                                &times;
                                            </button>

                                        </div>
                                    @endif


                                    <div class="table-responsive">

                                        <table class="table table-hover table-striped" id="table-mfa">

                                            <thead>
                                                <tr>
                                                    <th width="5%">No</th>
                                                    <th>Nama</th>
                                                    <th>Email</th>
                                                    <th width="15%">Status MFA</th>
                                                    <th width="15%">Aksi</th>
                                                </tr>
                                            </thead>


                                            <tbody>

                                                @foreach ($users as $user)
                                                    <tr>

                                                        <td>{{ $loop->iteration }}</td>

                                                        <td>
                                                            <strong>{{ $user->name }}</strong>
                                                        </td>

                                                        <td>{{ $user->email }}</td>


                                                        <td>

                                                            @if ($user->mfa_enabled)
                                                                <span class="badge badge-success">
                                                                    <i class="feather icon-check"></i> Aktif
                                                                </span>
                                                            @else
                                                                <span class="badge badge-secondary">
                                                                    Tidak Aktif
                                                                </span>
                                                            @endif

                                                        </td>


                                                        <td>

                                                            @if ($user->mfa_enabled)
                                                                <form action="{{ route('admin.mfa.reset', $user->id) }}"
                                                                    method="POST" class="form-reset">

                                                                    @csrf

                                                                    <button type="submit" class="btn btn-sm btn-danger">

                                                                        <i class="feather icon-refresh-cw"></i>
                                                                        Reset MFA

                                                                    </button>

                                                                </form>
                                                            @else
                                                                <span class="text-muted">-</span>
                                                            @endif

                                                        </td>

                                                    </tr>
                                                @endforeach

                                            </tbody>

                                        </table>

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>
        </div>
    </div>

@endsection



@section('scripts')

    <script>
        $(document).ready(function() {

            $('#table-mfa').DataTable({

                pageLength: 10,
                ordering: true,
                responsive: true

            });

        });
    </script>


    <script>
        $('.form-reset').submit(function(e) {

            e.preventDefault();

            let form = this;

            Swal.fire({

                title: 'Reset MFA?',
                text: 'User harus melakukan setup MFA ulang.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, Reset',
                cancelButtonText: 'Batal'

            }).then((result) => {

                if (result.isConfirmed) {

                    form.submit();

                }

            });

        });
    </script>

@endsection
