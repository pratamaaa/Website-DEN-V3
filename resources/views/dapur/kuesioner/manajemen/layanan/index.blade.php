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

        .ul_table {
            padding-left: 1rem;
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
                                    {{-- <button type="button" id="btnFormTambah2" onclick="showFormadd()" class="btn waves-effect waves-light btn-primary btn-sm btn-icon m-0" data-toggle="tooltip" data-placement="top" title="Tambah">
                                    <i class="feather icon-plus-circle"></i> Tambah
                                </button> --}}
                                    <a href="{{ url('/kuesioner/manajemen-layanan-create') }}" id="btnFormTambah2"
                                        class="btn waves-effect waves-light btn-primary btn-sm btn-icon m-0"
                                        data-toggle="tooltip" data-placement="top" title="Tambah">
                                        <i class="feather icon-plus-circle"></i> Tambah
                                    </a>
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
                                            <th class="text-center" width="5%">No</th>
                                            <th class="text-center" width="5%">Kode</th>
                                            <th class="text-center" width="5%">Tahun</th>
                                            <th class="text-center" width="15%">Nama</th>
                                            <th class="text-center" width="5%">Jumlah Pertanyaan</th>
                                            <th class="text-center" width="10%">Periode</th>
                                            <th class="text-center" width="10%">Tanggal Buat</th>
                                            <th class="text-center" width="10%">Tanggal Publish</th>
                                            <th class="text-center" width="5%">Status</th>
                                            <th class="text-center" width="10%">Aksi</th>
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
        {{-- <div class="modal-dialog modal-lg"> --}}
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
                ajax: "{{ url('/kuesioner/manajemen-layanan-list') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex'
                    },
                    {
                        data: 'kuesioner_layanan_kode',
                        name: 'kuesioner_layanan_kode'
                    },
                    {
                        data: 'kuesioner_layanan_tahun',
                        name: 'kuesioner_layanan_tahun'
                    },
                    {
                        data: 'kuesioner_layanan_nama',
                        name: 'kuesioner_layanan_nama'
                    },
                    {
                        data: 'kuesioner_layanan_jumlah_pertanyaan',
                        name: 'kuesioner_layanan_jumlah_pertanyaan'
                    },
                    {
                        data: 'periode',
                        name: 'periode'
                    },
                    {
                        data: 'kuesioner_layanan_created_date',
                        name: 'kuesioner_layanan_created_date'
                    },
                    {
                        data: 'kuesioner_layanan_publish_date',
                        name: 'kuesioner_layanan_publish_date'
                    },

                    {
                        data: 'kuesioner_layanan_status_name',
                        name: 'kuesioner_layanan_status_name'
                    },
                    {
                        data: 'action'
                    }
                ],
                columnDefs: [{
                        className: "text-center",
                        "targets": [0, 1, 2, 4, 5, 6, 7, 8, 9]
                    },
                    // { className: "text-left", "targets": [1,2,3]},
                    // { className: "ndrparagraf", "targets": "_all"},
                ],
            });
        });

        function showFormadd() {
            $('#modalku').modal('show').find('#modalku_content').load("{{ url('/dap/rb/add') }}");
        }

        function showFormedit(id) {
            $('#modalku').modal('show').find('#modalku_content').load("{{ url('/dap/rb/edit') }}?id=" + id);
        }

        function showFormRead(id, cat) {
            $('#modalku2').modal('show').find('#modalku-content2').load("{{ url('/pdfviewer') }}?id=" + id + "&cat=" + cat);
        }

        function hapus(uuid) {
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
                        url: "{{ url('/kuesioner/manajemen-layanan-delete') }}/" + uuid,
                        type: 'POST',
                        data: {
                            "_token": "{{ csrf_token() }}"
                        },
                        dataType: 'json',
                        success: function(resp) {
                            if (resp.result == "success") {
                                swal("", resp.message, "success").then(function() {
                                    reloadTable();
                                });
                            }
                        },
                    });
                }
            });
        }

        function copy_url(e) {
            var uuid = $(e).data("uuid");
            var url = "{{ url('/survey') }}/" + uuid;

            // Fallback untuk HTTP (localhost/dev)
            if (navigator.clipboard && window.isSecureContext) {
                navigator.clipboard.writeText(url).then(function() {
                    swal("", "Link survey berhasil disalin ke clipboard!", "success");
                });
            } else {
                // Fallback manual
                var $temp = $("<input>");
                $("body").append($temp);
                $temp.val(url).select();
                document.execCommand("copy");
                $temp.remove();
                swal("", "Link survey berhasil disalin ke clipboard!", "success");
            }
        }
    </script>
@endsection
