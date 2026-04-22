@extends('layout.dapur.app')

@section('content')
    <div class="pcoded-main-container">
        <div class="pcoded-content">

            <div class="page-wrapper">
                <div class="page-body">

                    {{-- WELCOME --}}
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body text-center">

                                    <i class="feather icon-eye text-primary mb-3" style="font-size:40px"></i>

                                    <h4 class="mb-2">Selamat Datang di Dashboard Auditor</h4>

                                    <p class="text-muted mx-auto" style="max-width:700px;">
                                        Halaman ini digunakan untuk memantau dan menelusuri riwayat aktivitas pengguna
                                        serta perubahan data pada sistem melalui audit log.
                                    </p>

                                </div>
                            </div>
                        </div>
                    </div>


                    {{-- STATISTICS --}}
                    <div class="row">

                        <div class="col-md-4">
                            <div class="card order-card bg-c-blue">
                                <div class="card-body">
                                    <h6 class="text-white">Total Logs</h6>
                                    <h2 class="text-white">{{ $totalLogs }}</h2>
                                    {{-- <i class="feather icon-list card-icon"></i> --}}
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="card order-card bg-c-green">
                                <div class="card-body">
                                    <h6 class="text-white">Total Login</h6>
                                    <h2 class="text-white">{{ $totalLogin }}</h2>
                                    {{-- <i class="feather icon-log-in card-icon"></i> --}}
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="card order-card bg-c-yellow">
                                <div class="card-body">
                                    <h6 class="text-white">Total CRUD</h6>
                                    <h2 class="text-white">{{ $totalCrud }}</h2>
                                    <i class="feather icon-database card-icon"></i>
                                </div>
                            </div>
                        </div>

                    </div>


                    {{-- RECENT ACTIVITY --}}
                    <div class="row">

                        <div class="col-12">

                            <div class="card">

                                <div class="card-header">
                                    <h5>Recent Activity</h5>
                                </div>

                                <div class="card-body table-border-style">

                                    <div class="table-responsive">

                                        <table class="table table-hover">

                                            <thead>
                                                <tr>
                                                    <th>User</th>
                                                    <th>Activity</th>
                                                    <th>Module</th>
                                                    <th>IP</th>
                                                    <th>Tanggal</th>
                                                </tr>
                                            </thead>

                                            <tbody>

                                                @foreach ($recent as $r)
                                                    <tr>
                                                        <td>{{ $r->user_id }}</td>
                                                        <td>{{ $r->activity }}</td>
                                                        <td>{{ $r->module }}</td>
                                                        <td>{{ $r->ip_address }}</td>
                                                        <td>{{ $r->created_at }}</td>
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
