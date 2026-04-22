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

        .ratakanan {
            text-align: right;
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
                                    <h5>Data Pelaporan Gratifikasi</h5>
                                </span>
                            </nav>

                            <hr>

                            <div class="dt-responsive table-responsive">
                                <table id="tabelku" class="table table-striped table-bordered table-hover nowrap2"
                                    width="100%">
                                    <thead>
                                        <tr>
                                            <th width="5%">No</th>
                                            <th width="20%">Nama</th>
                                            <th width="20%">Instansi</th>
                                            <th width="15%">Jenis</th>
                                            <th width="15%">Tanggal</th>
                                            <th width="10%">File</th>
                                            <th width="15%">Aksi</th>
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

    {{-- MODAL --}}

    <div id="modalku" class="modal fade bd-example-modal-lg" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content" id="modalku_content"></div>
        </div>
    </div>

    <script>
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
                ajax: "{{ url('dap/gratifikasi/getlist') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'namalengkap',
                        name: 'namalengkap'
                    },
                    {
                        data: 'instansi',
                        name: 'instansi'
                    },
                    {
                        data: 'jenispenerimaan',
                        name: 'jenispenerimaan'
                    },
                    {
                        data: 'tanggal',
                        name: 'tanggal'
                    },
                    {
                        data: 'file',
                        name: 'file',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },
                ],
                columnDefs: [{
                    className: "ratatengah",
                    targets: [0, 4, 5, 6]
                }],
            });
        });

        // ✅ Fix: gunakan route param /{id}, bukan query string ?id=
        function showDetail(id) {
            $('#modalku_content').html(
                '<div class="p-4 text-center"><i class="fas fa-spinner fa-spin"></i> Memuat...</div>');
            $('#modalku').modal('show');
            $('#modalku_content').load("{{ url('/dap/gratifikasi') }}/" + id);
        }

        // ✅ Fix: gunakan DELETE method via $.ajax, bukan $.post ke /delete
        function hapus(id) {
            event.preventDefault();

            swal({
                title: "Yakin hapus?",
                text: "Data akan dihapus permanen!",
                icon: "warning",
                buttons: {
                    confirm: 'Ya',
                    cancel: 'Batal'
                },
            }).then((ok) => {
                if (ok) {
                    $.ajax({
                        url: "{{ url('/dap/gratifikasi') }}/" + id,
                        method: 'DELETE',
                        success: function(resp) {
                            if (resp.result == "success") {
                                swal(resp.message, {
                                        icon: "success"
                                    })
                                    .then(() => reloadTable());
                            }
                        },
                        error: function() {
                            swal("Gagal!", "Terjadi kesalahan saat menghapus data.", "error");
                        }
                    });
                }
            });
        }
    </script>
@endsection
