@extends('layout.dapur.app')

@section('content')
   <style>
      .table th { text-align: center; vertical-align: middle; }
      .table td { vertical-align: middle; padding: 7px 7px; }
   </style>

   <section class="pcoded-main-container">
      <div class="pcoded-content">
         <div class="row">
            <div class="col-sm-12">
               <div class="card">
                  <div class="card-body">
                     {{-- Navbar Header --}}
                     <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5>{{ $judulhalaman }}</h5>
                        <a href="{{ url('/kuesioner/manajemen-template-jawaban-create') }}" class="btn waves-effect waves-light btn-primary btn-sm btn-icon">
                           <i class="fa fa-plus-circle"></i> Tambah
                        </a>
                     </div>

                     <div class="dt-responsive table-responsive">
                        <table id="tabelku" class="table table-striped table-bordered table-hover nowrap" width="100%">
                           <thead>
                              <tr>
                                 <th class="text-center" width="5%">No</th>
                                 <th class="text-center">Nama Group</th>
                                 <th class="text-center">Keterangan</th>
                                 <th class="text-center">Opsi Jawaban</th>
                                 <th class="text-center" width="15%">Tanggal Buat</th>
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

   {{-- Script Javascript --}}
   <script type="text/javascript">
      $.ajaxSetup({
         headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
      });

      function reloadTable() {
         $('#tabelku').DataTable().ajax.reload();
      }

      $(document).ready(function() {
         var table = $('#tabelku').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ url('/kuesioner/manajemen-template-jawaban-list') }}",
            columns: [
               { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
               { data: 'kuesioner_template_jawaban_group_nama', name: 'kuesioner_template_jawaban_group_nama' },
               { data: 'kuesioner_template_jawaban_group_keterangan', name: 'kuesioner_template_jawaban_group_keterangan' },
               { 
                  data: 'kuesioner_template_jawaban_group_jawaban', 
                  name: 'kuesioner_template_jawaban_group_jawaban',
                  render: function(data) {
                     return '<span class="text-wrap" style="white-space: normal;">' + (data ? data : '-') + '</span>';
                  }
               },
               { data: 'kuesioner_template_jawaban_group_created_date', name: 'kuesioner_template_jawaban_group_created_date' },
               { data: 'kuesioner_template_jawaban_group_status_name', name: 'kuesioner_template_jawaban_group_status_name' },
               { data: 'action', name: 'action', orderable: false, searchable: false }
            ],
            columnDefs: [
               { className: "text-center", targets: [0, 4, 5, 6] }, // Kolom No, Tgl, Status, Aksi rata tengah
               { className: "text-left", targets: [1, 2, 3] }       // Sisanya rata kiri
            ]
         });
      });

      // --- FUNGSI DELETE (Updated UUID) ---
      function hapus(uuid) {
         swal({
            title: "Apakah kamu yakin?",
            text: "Data group dan seluruh opsi jawabannya akan dihapus permanen!",
            icon: "warning",
            buttons: {
               confirm: { text: 'Ya, Hapus!', className: 'btn btn-danger' },
               cancel: { text: 'Batal', visible: true, className: 'btn btn-secondary' }
            },
            dangerMode: true,
         }).then((willDelete) => {
            if (willDelete) {
               $.ajax({
                  url: "{{ url('/kuesioner/manajemen-template-jawaban-delete') }}",
                  type: 'POST',
                  data: { uuid: uuid }, // Kirim UUID
                  dataType: 'json',
                  success: function(resp) {
                     if (resp.result == "success") {
                        swal("Berhasil!", resp.message, "success").then(function() {
                           reloadTable();
                        });
                     } else {
                        swal("Gagal!", resp.message, "error");
                     }
                  },
                  error: function(xhr) {
                     swal("Error!", "Terjadi kesalahan sistem.", "error");
                     console.log(xhr.responseText);
                  }
               });
            }
         });
      }
   </script>
@endsection