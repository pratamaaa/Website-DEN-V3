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
                                    <h5>{{ $judulhalaman }}</h5>
                                </span>
                                <div class="nav-item nav-grid f-view">
                                    <button type="button" id="btnFormTambah2" onclick="showFormadd()"
                                        class="btn waves-effect waves-light btn-primary btn-sm btn-icon m-0"
                                        data-toggle="tooltip" data-placement="top" title="Tambah">
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
                                            <th width="35%">Judul Publikasi</th>
                                            <th width="20%">Kategori</th>
                                            <th width="15%">Tanggal Posting</th>
                                            <th width="10%">File</th>
                                            <th width="5%">Hits</th>
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

    <div id="modalku" class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog"
        aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" id="modalku_content"></div>
        </div>
    </div>

    <div id="modalku2" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="largeModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content" id="modalku-content2"></div>
        </div>
    </div>

    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $(document).ready(function() {

            $('#tabelku').DataTable({

                processing: true,
                serverSide: true,
                ajax: "{{ route('publikasi.list') }}",

                columns: [{
                        data: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'judulpublikasi'
                    },
                    {
                        data: 'kategori'
                    },
                    {
                        data: 'tanggalposting'
                    },
                    {
                        data: 'filedownload'
                    },
                    {
                        data: 'counter'
                    },
                    {
                        data: 'action',
                        orderable: false,
                        searchable: false
                    }
                ],

                columnDefs: [{
                    className: "ratatengah",
                    targets: [0, 4, 5, 6]
                }]

            });

        });

        function showFormadd() {

            $('#modalku')
                .modal('show')
                .find('#modalku_content')
                .load("{{ route('publikasi.add') }}");

        }

        function showFormedit(id) {

            $('#modalku')
                .modal('show')
                .find('#modalku_content')
                .load("{{ route('publikasi.edit') }}?id=" + id);

        }

        function hapus(id) {

            swal({
                    title: "Apakah kamu yakin?",
                    text: "Kamu akan kehilangan data untuk selamanya!",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true
                })

                .then((willDelete) => {

                    if (willDelete) {

                        $.ajax({

                            url: "{{ route('publikasi.delete') }}",
                            type: "POST",
                            data: {
                                id: id
                            },
                            dataType: "json",

                            success: function(resp) {

                                if (resp.result == "success") {

                                    swal("Berhasil!", resp.message, "success");

                                    $('#tabelku').DataTable().ajax.reload();

                                } else {

                                    swal("Error!", resp.message, "error");

                                }

                            },

                            error: function() {

                                swal("Error", "Terjadi kesalahan server", "error");

                            }

                        });

                    }

                });

        }
    </script>
@endsection
