@extends('layout.dapur.app')

@section('content')
    <style>
        .table th {
            text-align: center;
        }

        .table td,
        .table th {
            padding: 7px 7px;
        }

        .ratatengah {
            text-align: center !important;
        }
    </style>

    <section class="pcoded-main-container">
        <div class="pcoded-content">
            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-body">
                            <nav class="navbar" style="margin-left:-10px !important;">
                                <span class="m-r-15">
                                    <h5>{{ $judulhalaman }}</h5>
                                </span>
                                <div class="nav-item nav-grid f-view">
                                    <button type="button" onclick="showFormadd()"
                                        class="btn waves-effect waves-light btn-primary btn-sm btn-icon m-0"
                                        data-toggle="tooltip" title="Tambah">
                                        <i class="feather icon-plus-circle"></i> Tambah
                                    </button>
                                </div>
                            </nav>
                            <hr>
                            <div class="dt-responsive table-responsive">
                                <table id="tabelku" class="table table-striped table-bordered table-hover nowrap2"
                                    width="100%">
                                    <thead>
                                        <tr>
                                            <th width="5%">No</th>
                                            <th width="35%">Nama Status</th>
                                            <th width="20%">Warna</th>
                                            <th width="15%">Urutan</th>
                                            <th width="15%">Jumlah Provinsi</th>
                                            <th width="10%">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div id="modalku" class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content" id="modalku_content"></div>
        </div>
    </div>

    <script type="text/javascript">
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        function reloadTable() {
            $('#tabelku').DataTable().ajax.reload();
        }

        $(function() {
            $('#tabelku').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ url('/dap/ruedp-status/getlist') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex'
                    },
                    {
                        data: 'nama_col',
                        name: 'nama_col'
                    },
                    {
                        data: 'warna_col',
                        name: 'warna_col'
                    },
                    {
                        data: 'urutan',
                        name: 'urutan'
                    },
                    {
                        data: 'jumlah_col',
                        name: 'jumlah_col'
                    },
                    {
                        data: 'action'
                    },
                ],
                columnDefs: [{
                        className: "ratatengah",
                        targets: [0, 3, 4, 5]
                    },
                    {
                        className: "ndrparagraf",
                        targets: "_all"
                    },
                ],
            });
        });

        function showFormadd() {
            $('#modalku').modal('show').find('#modalku_content').load("{{ url('/dap/ruedp-status/add') }}");
        }

        function showFormedit(id) {
            $('#modalku').modal('show').find('#modalku_content').load("{{ url('/dap/ruedp-status/edit') }}?id=" + id);
        }

        function hapus(id) {
            event.preventDefault();
            swal({
                title: "Apakah kamu yakin?",
                text: "Kamu akan kehilangan data untuk selamanya!",
                icon: "warning",
                buttons: {
                    confirm: 'Ya',
                    cancel: 'Batal'
                },
                dangerMode: false,
            }).then((willDelete) => {
                if (willDelete) {
                    $.ajax({
                        url: "{{ url('/dap/ruedp-status/delete') }}",
                        type: 'POST',
                        data: {
                            id: id
                        },
                        dataType: 'json',
                        success: function(resp) {
                            if (resp.result == 'success') {
                                swal({
                                        title: "",
                                        text: resp.message,
                                        icon: "success"
                                    })
                                    .then(function() {
                                        reloadTable();
                                    });
                            }
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            console.log('Error: ' + errorThrown);
                        }
                    });
                }
            });
        }
    </script>
@endsection
