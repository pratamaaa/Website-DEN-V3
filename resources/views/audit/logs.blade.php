@php
    use Illuminate\Support\Str;
@endphp

@extends('layout.dapur.app')

@section('content')
    <div class="pcoded-main-container">
        <div class="pcoded-content">

            <div class="page-wrapper">
                <div class="page-body">

                    <div class="row">
                        <div class="col-sm-12">

                            <div class="card">

                                <div class="card-header d-flex justify-content-between align-items-center">

                                    <h5>Audit Logs</h5>

                                    <a href="{{ route('audit.export') }}" class="btn btn-success btn-sm">
                                        <i class="feather icon-download"></i> Export Excel
                                    </a>

                                </div>


                                <div class="card-body">


                                    {{-- FILTER --}}
                                    <form method="GET" class="mb-4">

                                        <div class="row align-items-end">

                                            <div class="col-md-3">
                                                <label>Search</label>
                                                <input type="text" name="search" class="form-control"
                                                    placeholder="User / activity / module" value="{{ request('search') }}">
                                            </div>

                                            <div class="col-md-3">
                                                <label>Start Date</label>
                                                <input type="date" name="start_date" class="form-control"
                                                    value="{{ request('start_date') }}">
                                            </div>

                                            <div class="col-md-3">
                                                <label>End Date</label>
                                                <input type="date" name="end_date" class="form-control"
                                                    value="{{ request('end_date') }}">
                                            </div>

                                            <div class="col-md-2">
                                                <button class="btn btn-primary btn-block">
                                                    <i class="feather icon-filter"></i> Filter
                                                </button>
                                            </div>

                                            <div class="col-md-1">
                                                <a href="{{ route('audit.logs') }}" class="btn btn-secondary btn-block">
                                                    Reset
                                                </a>
                                            </div>

                                        </div>

                                    </form>


                                    {{-- TABLE --}}
                                    <div class="table-responsive">

                                        <table class="table table-hover table-striped">

                                            <thead>
                                                <tr>
                                                    <th>User</th>
                                                    <th>Activity</th>
                                                    <th>Module</th>
                                                    <th>IP</th>
                                                    <th>Device</th>
                                                    <th>Date</th>
                                                    {{-- <th width="90">Detail</th> --}}
                                                </tr>
                                            </thead>

                                            <tbody>

                                                @foreach ($logs as $log)
                                                    <tr>

                                                        <td>{{ $log->user_name ?? '-' }}</td>

                                                        <td>

                                                            @if (Str::contains($log->activity, 'LOGIN') && !Str::contains($log->activity, 'FAILED'))
                                                                <span class="badge badge-success">LOGIN</span>
                                                            @elseif (Str::contains($log->activity, 'LOGIN_FAILED'))
                                                                <span class="badge badge-danger">LOGIN FAILED</span>
                                                            @elseif (Str::contains($log->activity, 'Logout'))
                                                                <span class="badge badge-secondary">LOGOUT</span>
                                                            @elseif (Str::contains($log->activity, 'Tambah'))
                                                                <span class="badge badge-primary">CREATE</span>
                                                            @elseif (Str::contains($log->activity, 'Update'))
                                                                <span class="badge badge-warning">UPDATE</span>
                                                            @elseif (Str::contains($log->activity, 'Hapus'))
                                                                <span class="badge badge-dark">DELETE</span>
                                                            @else
                                                                <span class="badge badge-info">{{ $log->activity }}</span>
                                                            @endif

                                                        </td>

                                                        <td>{{ $log->module }}</td>

                                                        <td>{{ $log->ip_address }}</td>

                                                        <td
                                                            style="max-width:300px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                                                            {{ $log->user_agent ?? '-' }}
                                                        </td>

                                                        <td>{{ $log->created_at }}</td>


                                                        {{-- <td>

                                                            @if ($log->old_data || $log->new_data)
                                                                <button class="btn btn-sm btn-info" data-toggle="modal"
                                                                    data-target="#log{{ $log->id }}">
                                                                    Detail
                                                                </button>
                                                            @else
                                                                -
                                                            @endif

                                                        </td> --}}

                                                    </tr>



                                                    {{-- MODAL --}}
                                                    @if ($log->old_data || $log->new_data)
                                                        <div class="modal fade" id="log{{ $log->id }}" tabindex="-1">

                                                            <div class="modal-dialog modal-lg">
                                                                <div class="modal-content">

                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title">Audit Log Detail</h5>

                                                                        <button type="button" class="close"
                                                                            data-dismiss="modal">
                                                                            &times;
                                                                        </button>

                                                                    </div>


                                                                    <div class="modal-body">

                                                                        <div class="row">

                                                                            <div class="col-md-6">

                                                                                <h6>Old Data</h6>

                                                                                <pre style="background:#f4f4f4;padding:10px;border-radius:6px;">
{{ $log->old_data ? json_encode(json_decode($log->old_data), JSON_PRETTY_PRINT) : '-' }}
</pre>

                                                                            </div>


                                                                            <div class="col-md-6">

                                                                                <h6>New Data</h6>

                                                                                <pre style="background:#f4f4f4;padding:10px;border-radius:6px;">
{{ $log->new_data ? json_encode(json_decode($log->new_data), JSON_PRETTY_PRINT) : '-' }}
</pre>

                                                                            </div>

                                                                        </div>

                                                                    </div>

                                                                </div>
                                                            </div>

                                                        </div>
                                                    @endif
                                                @endforeach

                                            </tbody>

                                        </table>


                                        {{-- PAGINATION --}}
                                        <div class="mt-3">
                                            {{ $logs->links() }}
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
