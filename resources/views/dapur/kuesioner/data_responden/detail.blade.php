@extends('layout.dapur.app')

@section('content')
   <style>
      /* Mengembalikan font agar sinkron dengan template */
      .res-info-label {
         font-size: 11px;
         text-transform: uppercase;
         color: #919aa3;
         font-weight: 700;
         letter-spacing: 1px;
         margin-bottom: 3px;
      }

      .res-info-value {
         font-size: 14px;
         font-weight: 600;
         color: #464d5c;
         margin-bottom: 15px;
         line-height: 1.4;
      }

      /* Memperbaiki Sticky agar Pas */
      .sticky-profile {
         position: -webkit-sticky;
         position: sticky;
         top: 90px;
         /* Jarak dari navbar atas */
         z-index: 10;
      }

      /* Konsistensi Card */
      .question-card {
         border-radius: 4px;
         border: 1px solid #e2e5e8;
         box-shadow: none;
         margin-bottom: 20px;
      }

      .question-header {
         background-color: #f4f7fa;
         padding: 12px 15px;
         border-bottom: 1px solid #e2e5e8;
      }

      /* Styling Pilihan Jawaban yang Padat */
      .option-item {
         padding: 8px 12px;
         border-radius: 4px;
         border: 1px solid #e2e5e8;
         display: flex;
         align-items: center;
         background: #fff;
         min-height: 42px;
         height: 100%;
         transition: all 0.3s;
      }

      .option-item.selected {
         background-color: #e0f4f5;
         border-color: #0fb3c2;
         color: #0fb3c2;
      }

      .option-check {
         width: 18px;
         height: 18px;
         border-radius: 50%;
         border: 2px solid #ced4da;
         margin-right: 10px;
         display: flex;
         align-items: center;
         justify-content: center;
         flex-shrink: 0;
         font-size: 10px;
      }

      .selected .option-check {
         border-color: #0fb3c2;
         background-color: #0fb3c2;
         color: #fff;
      }

      .param-section {
         border-left: 4px solid #0fb3c2;
         padding-left: 15px;
         margin: 25px 0 15px 0;
         font-weight: 700;
      }

      @media print {
         .pcoded-main-container {
            margin-left: 0;
            padding-top: 0;
         }

         .sticky-profile {
            position: static;
         }

         .btn-print,
         .breadcrumb,
         .pcoded-navbar,
         .pcoded-header {
            display: none !important;
         }

         .card {
            border: 1px solid #000 !important;
         }
      }
   </style>

   <div class="pcoded-main-container">
      <div class="pcoded-content">
         <div class="page-header">
            <div class="page-block">
               <div class="row align-items-center">
                  <div class="col-md-12">
                     <div class="page-header-title">
                        <h5 class="m-b-10">{{ $judulhalaman }}</h5>
                     </div>
                     <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ url('/kuesioner/overview') }}"><i class="fa fa-home"></i></a></li>
                        <li class="breadcrumb-item"><a href="{{ url('/kuesioner/data-responden?layanan_id=' . $layanan_terpilih) }}">Data Responden</a></li>
                        <li class="breadcrumb-item">Detail</li>
                     </ul>
                  </div>
               </div>
            </div>
         </div>

         <div class="row">
            <div class="col-xl-3 col-lg-4">
               <div class="sticky-profile">
                  <div class="card shadow-none" style="border: 1px solid #e2e5e8;">
                     <div class="card-header border-bottom p-3">
                        <h6 class="m-0 font-weight-bold"><i class="fa fa-user mr-2 text-info"></i>Profil Responden</h6>
                     </div>
                     <div class="card-body p-3">
                        <div class="res-info-label">Nama Lengkap</div>
                        <div class="res-info-value text-info">{{ $responden->kuesioner_responden_nama }}</div>

                        <div class="res-info-label">Email / Telp</div>
                        <div class="res-info-value">{{ $responden->kuesioner_responden_email }} <br> {{ $responden->kuesioner_responden_telp }}</div>

                        <div class="res-info-label">Instansi Asal</div>
                        <div class="res-info-value">{{ $responden->instansi_asal ?? '-' }}</div>

                        @if ($responden->pemangku_kepentingan)
                           <div class="res-info-label">Pemangku Kepentingan</div>
                           <div class="res-info-value">{{ $responden->pemangku_kepentingan }}</div>
                        @endif

                        <div class="res-info-label">Waktu Pengisian</div>
                        <div class="res-info-value">{{ date('d/m/Y H:i', strtotime($responden->kuesioner_responden_created_date)) }}</div>

                        <div class="bg-light p-2 mb-3" style="border-left: 3px solid #0fb3c2; border-radius: 2px;">
                           <div class="res-info-label text-dark">Saran & Masukan</div>
                           <p class="m-0" style="font-style: italic; font-size: 13px;">"{{ $responden->kuesioner_responden_saran ?: '-' }}"</p>
                        </div>

                        <button onclick="window.print()" class="btn btn-info btn-sm btn-block btn-print">
                           <i class="fa fa-print mr-1"></i> Cetak Laporan
                        </button>
                     </div>
                  </div>
               </div>
            </div>

            <div class="col-xl-9 col-lg-8">
               <div class="d-flex align-items-center justify-content-between mb-3">
                  <h5 class="m-0 font-weight-bold">Hasil Survei</h5>
                  <span class="badge badge-light-info shadow-sm">{{ $responden->kuesioner_layanan_nama }}</span>
               </div>

               @foreach ($pertanyaan as $header)
                  <div class="param-section text-info">
                     {{ $header->kuesioner_pertanyaan_kode }}. {{ $header->kuesioner_pertanyaan_nama }}
                  </div>

                  @foreach ($header->children as $q)
                     <div class="card question-card">
                        <div class="question-header d-flex align-items-center justify-content-between">
                           <div class="d-flex align-items-center">
                              <span class="badge badge-info mr-2">{{ $q->kuesioner_pertanyaan_kode }}</span>
                              <span class="font-weight-600 text-dark" style="font-size: 13px;">{{ $q->kuesioner_pertanyaan_nama }}</span>
                           </div>
                           <span class="badge {{ $q->kuesioner_pertanyaan_aspect == 1 ? 'badge-warning' : 'badge-success' }} text-uppercase" style="font-size: 9px;">
                              {{ $q->kuesioner_pertanyaan_aspect == 1 ? 'Importance' : 'Performance' }}
                           </span>
                        </div>
                        <div class="card-body p-3">
                           <div class="row">
                              @foreach ($q->jawaban as $opt)
                                 @php $isSelected = in_array($opt->kuesioner_jawaban_uuid, $jawaban_terpilih); @endphp
                                 <div class="col-md-6 mb-2">
                                    <div class="option-item {{ $isSelected ? 'selected' : '' }}">
                                       <div class="option-check">
                                          @if ($isSelected)
                                             <i class="fa fa-check"></i>
                                          @endif
                                       </div>
                                       <div class="flex-grow-1 d-flex justify-content-between align-items-center">
                                          <span style="font-size: 13px;">{{ $opt->kuesioner_jawaban_nama }}</span>

                                          @if ($isSelected)
                                             <div class="small font-weight-bold ml-2 text-nowrap">
                                                Bobot: {{ number_format($opt->kuesioner_jawaban_bobot, 0) }}
                                             </div>
                                          @endif
                                       </div>
                                    </div>
                                 </div>
                              @endforeach
                           </div>
                        </div>
                     </div>
                  @endforeach
               @endforeach
            </div>
         </div>
      </div>
   </div>
@endsection
