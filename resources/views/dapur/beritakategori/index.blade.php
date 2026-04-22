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
                                    {{-- <button type="button" id="btnFormTambah2" onclick="reloadTable()" class="btn waves-effect waves-light btn-primary btn-icon m-0" data-toggle="tooltip" data-placement="top" title="Tambah">
									<i class="feather icon-refresh-cw"></i> Muat Ulang
								</button> --}}
                                </div>
                            </nav>

                            <hr>
                            <div class="dt-responsive table-responsive">
                                <table id="tabelku" class="table table-striped table-bordered table-hover nowrap2"
                                    width="100%">
                                    <thead>
                                        <tr>
                                            <th width="5%">No</th>
                                            <th width="30%">Kategori Berita</th>
                                            <th width="30%">Kategori Berita (En)</th>
                                            <th width="25%">Slug</th>
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

            var table = $('#tabelku').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ url('kategoriberita/getlist') }}",

                order: [], // penting: jangan auto order kolom pertama

                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'kategori_berita',
                        name: 'kategori_berita'
                    },
                    {
                        data: 'kategori_berita_en',
                        name: 'kategori_berita_en'
                    },
                    {
                        data: 'kategori_slug',
                        name: 'kategori_slug'
                    },
                    {
                        data: 'action',
                        orderable: false,
                        searchable: false
                    }
                ],

                columnDefs: [{
                        className: "ratatengah",
                        targets: [4]
                    },
                    {
                        className: "ndrparagraf",
                        targets: "_all"
                    }
                ]

            });

        });

        function showFormadd() {
            $('#modalku').modal('show').find('#modalku_content').load("{{ url('/kategoriberita/add') }}");
        }

        function showFormedit(id) {
            $('#modalku').modal('show').find('#modalku_content').load("{{ url('/kategoriberita/edit') }}?id=" + id);
        }

        function hapus(id) {
            event.preventDefault(); // prevent form submit

            swal({
                title: "Apakah kamu yakin?",
                text: "Kamu akan kehilangan data untuk selamanya! ",
                icon: "warning",
                buttons: {
                    confirm: 'Ya',
                    cancel: 'Batal'
                },
                dangerMode: false,
            }).then((willDelete) => {
                if (willDelete) {
                    $.ajax({
                        url: "{{ url('/kategoriberita/delete') }}",
                        type: 'POST',
                        data: {
                            "id": id
                        },
                        dataType: 'json',
                        success: function(resp) {
                            if (resp.result == "success") {
                                swal({
                                    title: "",
                                    text: resp.message,
                                    icon: "success",
                                }).then(function() {
                                    reloadTable();
                                });
                            }
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            console.log('jqXMLHTTReq: ' + jqXHR + ', Status: ' + textStatus +
                                ', Error: ' + errorThrown);
                        }
                    });
                }
            });
        }
    </script>
@endsection
