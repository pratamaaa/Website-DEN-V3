@extends('layout.dapur.app')

@section('content')
   {{-- Library Chart.js --}}
   <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

   <style>
      /* =========================================
         1. STYLE TABEL UTAMA (FIXED & SCROLL)
         ========================================= */
      .table th {
         text-align: center;
         vertical-align: middle;
         font-size: 11px;
         padding: 8px 5px !important;
         white-space: nowrap; /* Header jangan turun baris biar rapi */
      }

      .table td {
         vertical-align: middle;
         padding: 5px 5px !important;
         font-size: 12px;
      }

      /* Container Scroll (Pengganti scrollX DataTables) */
      .table-responsive {
         overflow-x: auto;
         -webkit-overflow-scrolling: touch;
      }

      /* AGAR KOLOM TIDAK GEPENG SAAT SCROLL */
      .th-layanan {
         min-width: 120px !important; /* Lebar minimal kolom layanan */
         white-space: normal !important; /* Isi header boleh wrap text */
         box-shadow: inset -1px 0 0 #dee2e6; /* Garis pemisah halus */
      }
      
      .th-parameter {
         min-width: 250px !important; /* Parameter lebar */
         white-space: normal !important;
      }

      /* =========================================
         2. TAB & CONTENT STYLES
         ========================================= */
      .nav-tabs { border-bottom: 1px solid #0fb3c2; }
      .nav-tabs .nav-link { margin-bottom: -1px; border: 1px solid transparent; color: #6c757d; font-weight: 500; padding: 8px 20px; transition: all 0.3s ease; }
      .nav-tabs .nav-link:hover { background-color: #f8f9fa; border-color: #e9ecef #e9ecef #0fb3c2; }
      .nav-tabs .nav-link.active { background: linear-gradient(to right, #000000, #0fb3c2) !important; color: #ffffff !important; border: 1px solid #0fb3c2; border-bottom-color: transparent; box-shadow: 0 -3px 8px rgba(15, 179, 194, 0.2); }
      .nav-tabs .nav-link.active i { color: #ffffff !important; }
      .tab-content { border: 1px solid #0fb3c2; border-top: none; padding: 15px; background-color: #fff; min-height: 300px; position: relative; margin-top: -1px; border-radius: 0 0 5px 5px; }
      .nav-link i { margin-right: 5px; }

      /* =========================================
         3. WIDGET JUMLAH RESPONDEN (MODERN STYLE)
         ========================================= */
      .card-responden {
         border: none; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.08); overflow: hidden; background: #fff; transition: transform 0.3s ease;
      }
      .card-responden:hover { transform: translateY(-5px); }
      .card-responden-header {
         background: linear-gradient(135deg, #0fb3c2 0%, #0a8f9c 100%); padding: 20px 25px; color: white; position: relative;
      }
      .card-responden-header::after {
         content: ''; position: absolute; top: -10px; right: -10px; width: 60px; height: 60px; border-radius: 50%; background: rgba(255,255,255,0.1);
      }
      .table-modern { margin-bottom: 0; }
      .table-modern thead th { background-color: #f8f9fa; color: #888; font-size: 11px; text-transform: uppercase; letter-spacing: 1px; border-bottom: 1px solid #eee; padding: 12px 20px; }
      .table-modern tbody td { padding: 15px 20px; vertical-align: middle; border-bottom: 1px dashed #f0f0f0; color: #555; font-weight: 500; }
      .table-modern tbody tr:last-child td { border-bottom: none; }
      .badge-count { background-color: #e0f7fa; color: #006064; padding: 5px 12px; border-radius: 20px; font-weight: bold; font-size: 12px; }
      .responden-footer { background-color: #fcfcfc; padding: 20px; border-top: 1px solid #f0f0f0; display: flex; justify-content: space-between; align-items: center; }
      .total-label { font-size: 13px; color: #666; font-weight: 600; text-transform: uppercase; }
      .total-value { font-size: 24px; font-weight: 800; color: #0fb3c2; line-height: 1; }

      /* =========================================
         4. DASHBOARD IKL (CARD STYLE)
         ========================================= */
      .card-ikl { border: 1px solid #edf2f7; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.05); background: #fff; }
      .ikl-score-box { text-align: center; padding: 20px; }
      .ikl-score-value { font-size: 4rem; font-weight: 800; line-height: 1; color: #0fb3c2; text-shadow: 2px 2px 0px rgba(15,179,194,0.1); }
      .ikl-score-label { font-size: 1.1rem; font-weight: 600; color: #555; margin-bottom: 15px; }
      .ikl-stats-container { background-color: #f8f9fa; border-radius: 10px; padding: 15px; margin-top: 10px; border: 1px solid #edf2f7; }
      .stat-item { display: flex; justify-content: space-between; align-items: center; padding: 8px 0; border-bottom: 1px dashed #d1d9e6; font-size: 13px; }
      .stat-item:last-child { border-bottom: none; }
      .stat-label { color: #6c757d; }
      .stat-value { font-weight: 700; color: #333; }
      
      .legend-list { list-style: none; padding: 0; margin: 0; }
      .legend-item { padding: 10px 15px; margin-bottom: 8px; border-radius: 8px; font-size: 13px; background-color: #fff; border: 1px solid #e9ecef; display: flex; align-items: center; }
      .legend-item.active-category { transform: scale(1.02); box-shadow: 0 4px 10px rgba(0,0,0,0.1); border: none; }
      .legend-item.cat-bad.active-category { background: #ff5252; color: #fff; }
      .legend-item.cat-poor.active-category { background: #ffba00; color: #fff; }
      .legend-item.cat-good.active-category { background: #0fb3c2; color: #fff; }
      .legend-item.cat-excellent.active-category { background: #4099ff; color: #fff; }
   </style>

   <section class="pcoded-main-container">
      <div class="pcoded-content">
         <div class="row">
            <div class="col-sm-12">
               <div class="card">
                  <div class="card-header">
                     <h5>{{ $judulhalaman }}</h5>
                  </div>
                  <div class="card-body">

                     {{-- TABS NAVIGATION --}}
                     <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item"> <a class="nav-link active" id="data-tab" data-toggle="tab" href="#tab-data" role="tab"><i class="feather icon-check-square"></i> Rekap Input</a> </li>
                        <li class="nav-item"> <a class="nav-link" id="grafik-tab" data-toggle="tab" href="#tab-grafik" role="tab"><i class="feather icon-bar-chart"></i> Rekap IKL</a> </li>
                        <li class="nav-item"> <a class="nav-link" id="info-tab" data-toggle="tab" href="#tab-info" role="tab"><i class="feather icon-grid"></i> Rekap Matriks</a> </li>
                     </ul>

                     <div class="tab-content" id="myTabContent">
                        
                        {{-- === TAB 1: REKAP INPUT === --}}
                        <div class="tab-pane fade show active" id="tab-data" role="tabpanel">
                           
                           {{-- 1.A WIDGET JUMLAH RESPONDEN (MODERN) --}}
                           <div class="row mb-4">
                              <div class="col-md-5">
                                 <div class="card card-responden">
                                    <div class="card-responden-header">
                                       <h5 class="m-0 text-white fw-bold"><i class="feather icon-users mr-2"></i> Partisipasi Responden</h5>
                                       <small class="text-white-50" style="font-size: 11px;">Rekapitulasi data per jenis layanan</small>
                                    </div>
                                    <div class="card-body p-0">
                                       <table class="table table-modern" id="table-responden-tab1" style="width: 100%;">
                                          <thead>
                                             <tr>
                                                <th width="10%" class="text-center">#</th>
                                                <th>Jenis Layanan</th>
                                                <th width="25%" class="text-center">Jumlah</th>
                                             </tr>
                                          </thead>
                                          <tbody></tbody>
                                       </table>
                                    </div>
                                    <div class="responden-footer">
                                       <div class="total-label"><i class="feather icon-bar-chart mr-1"></i> Total Keseluruhan</div>
                                       <div class="total-value" id="total-responden-display">0</div>
                                    </div>
                                 </div>
                              </div>
                           </div>
                           
                           {{-- 1.B TABEL UTAMA (GAP ANALYSIS) --}}
                           <div class="dt-responsive table-responsive">
                              <table id="table-laporan" class="table table-bordered table-hover" style="width:100%">
                                 <thead class="table-light">
                                    <tr>
                                       <th class="text-center align-middle bg-light" rowspan="2">No</th>
                                       <th class="text-center align-middle bg-light" rowspan="2">Kode</th>
                                       <th class="text-center align-middle bg-light th-parameter" rowspan="2">Parameter</th>
                                       <th class="text-center align-middle bg-light" rowspan="2">Aspek</th>
                                       <th class="text-center align-middle" colspan="{{ $daftarLayanan->count() }}">LAYANAN</th>
                                       <th class="text-center align-middle bg-light" rowspan="2">Rata<br>Rata</th>
                                    </tr>
                                    <tr>
                                       @foreach ($daftarLayanan as $l)
                                          <th class="text-center align-middle th-layanan">{{ $l->kuesioner_layanan_nama }}</th>
                                       @endforeach
                                    </tr>
                                 </thead>
                                 <tbody></tbody>
                              </table>
                           </div>
                        </div>

                        {{-- === TAB 2: REKAP IKL === --}}
                        <div class="tab-pane fade" id="tab-grafik" role="tabpanel">
                           <div class="row">
                              <div class="col-sm-12">
                                 <div class="card mb-4 border shadow-none">
                                    <div class="card-header bg-light py-2"><h6 class="mb-0 fw-bold">Tabel Perhitungan Bobot & Indeks</h6></div>
                                    <div class="card-body p-0">
                                       <div class="table-responsive">
                                          <table id="table-ikl" class="table table-bordered table-striped mb-0" style="width:100%">
                                             <thead class="bg-light">
                                                <tr><th class="text-center">NO</th><th class="text-center">KODE</th><th class="text-center">PARAMETER</th><th class="text-center">IMPORTANCE</th><th class="text-center">WEIGHT</th><th class="text-center">PERFORMANCE</th><th class="text-center">WEIGHT INDEX</th></tr>
                                             </thead>
                                             <tbody></tbody>
                                             <tfoot class="fw-bold bg-light">
                                                <tr><td colspan="3" class="text-end">TOTAL</td><td class="text-center" id="foot-total-imp">-</td><td class="text-center" id="foot-total-weight">-</td><td class="text-center">-</td><td class="text-center text-primary" id="foot-total-index">-</td></tr>
                                             </tfoot>
                                          </table>
                                       </div>
                                    </div>
                                 </div>
                                 <div class="card-ikl mt-4">
                                    <div class="card-body p-4">
                                       <div class="row align-items-center">
                                          <div class="col-md-6 border-end">
                                             <div class="ikl-score-box">
                                                <div class="ikl-score-label">Indeks Kepuasan Layanan (IKL)</div>
                                                <div class="ikl-score-value" id="display-ikl-score">0,00</div>
                                                <div class="text-muted small mt-2">Kinerja Unit Pelayanan</div>
                                             </div>
                                             <div class="ikl-stats-container">
                                                <div class="stat-item"><span class="stat-label"><i class="feather icon-users mr-1"></i> Jumlah Responden (n)</span><span class="stat-value" id="display-responden">0</span></div>
                                                <div class="stat-item"><span class="stat-label"><i class="feather icon-bar-chart-2 mr-1"></i> Simpangan Baku (Asumsi)</span><span class="stat-value">0,5</span></div>
                                                <div class="stat-item"><span class="stat-label"><i class="feather icon-alert-circle mr-1"></i> Margin of Error (90%)</span><span class="stat-value text-danger">± <span id="display-moe">0.0%</span></span></div>
                                             </div>
                                          </div>
                                          <div class="col-md-6 pl-md-4">
                                             <h6 class="fw-bold mb-3 text-secondary">Kategori Nilai Mutu Pelayanan:</h6>
                                             <ul class="legend-list">
                                                <li id="cat-1" class="legend-item cat-bad"> <span class="legend-range">1,00 - 2,59</span> <span>Tidak Baik</span> </li>
                                                <li id="cat-2" class="legend-item cat-poor"> <span class="legend-range">2,60 - 3,06</span> <span>Kurang Baik</span> </li>
                                                <li id="cat-3" class="legend-item cat-good"> <span class="legend-range">3,07 - 3,53</span> <span>Baik</span> </li>
                                                <li id="cat-4" class="legend-item cat-excellent"> <span class="legend-range">3,54 - 4,00</span> <span>Sangat Baik</span> </li>
                                             </ul>
                                          </div>
                                       </div>
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </div>

                        {{-- === TAB 3: REKAP MATRIKS === --}}
                        <div class="tab-pane fade" id="tab-info" role="tabpanel">
                           <div class="row">
                              <div class="col-md-5">
                                 <div class="card mb-3 shadow-sm border-0">
                                    <div class="card-body p-0">
                                       {{-- Note: Tabel Axis kita pakai style sederhana saja biar rapi --}}
                                       <table class="table table-sm table-bordered mb-0" style="width:100%">
                                          <thead class="bg-light"><tr><th class="text-left pl-3">Posisi Axis Y dan X</th><th class="text-center" width="30%">Nilai</th></tr></thead>
                                          <tbody>
                                             <tr><td class="pl-3">Posisi Vertical Axis Y (Importance)</td><td class="text-center fw-bold text-primary" id="val-axis-y">0.00</td></tr>
                                             <tr><td class="pl-3">Posisi Horizontal Axis X (Performance)</td><td class="text-center fw-bold text-primary" id="val-axis-x">0.00</td></tr>
                                          </tbody>
                                       </table>
                                    </div>
                                 </div>
                                 <div class="card shadow-none border">
                                    <div class="card-header bg-light py-2"><h6 class="mb-0 fw-bold">Tabel Data Matriks</h6></div>
                                    <div class="card-body p-0">
                                       <div class="table-responsive">
                                          <table id="table-matriks" class="table table-striped table-bordered mb-0" style="width:100%">
                                             <thead class="bg-light">
                                                <tr><th class="text-center">NO</th><th class="text-center">KODE</th><th class="text-center">PARAMETER</th><th class="text-center">PERF.</th><th class="text-center">IMP.</th><th class="text-center">GAP</th></tr>
                                             </thead>
                                             <tbody></tbody>
                                          </table>
                                       </div>
                                    </div>
                                 </div>
                              </div>
                              <div class="col-md-7">
                                 <div class="card shadow-sm border">
                                    <div class="card-body">
                                       <h5 class="text-center mb-3">Importance vs Performance</h5>
                                       <div style="height: 500px; width: 100%;"><canvas id="matriksChart"></canvas></div>
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </section>

   <script>
      $(document).ready(function() {
         $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });

         // =========================================================
         // 1. LOGIKA TAB 1 (REKAP INPUT & WIDGET RESPONDEN)
         // =========================================================
         var layananList = @json($daftarLayanan);
         
         var dtColumns = [
            { data: null, className: 'text-center align-middle bg-white', orderable: false, render: function(data, type, row, meta) { return Math.floor(meta.row / 3) + 1; } },
            { data: 'kode', name: 'kode', className: 'text-center fw-bold align-middle bg-white' },
            { data: 'parameter', name: 'parameter', className: 'align-middle bg-white' },
            { data: 'tipe', name: 'tipe', className: 'text-center' }
         ];

         layananList.forEach(function(layanan) {
            dtColumns.push({
               data: 'layanan_' + layanan.kuesioner_layanan_uuid,
               className: 'text-center', // Biarkan CSS yang atur
               render: function(data) { return data ? data : '0.00'; }
            });
         });
         
         dtColumns.push({ data: 'rata_rata', name: 'rata_rata', className: 'text-center fw-bold bg-light' });

         var table = $('#table-laporan').DataTable({
            processing: true, 
            serverSide: false,
            // AUTO WIDTH FALSE & NO SCROLLX untuk alignment sempurna
            autoWidth: false, 
            ajax: {
               url: "{{ url('/kuesioner/hasil-analisa-list') }}",
               dataSrc: function(json) {
                  // --- FILL WIDGET RESPONDEN (MODERN) ---
                  if (json.responden_per_layanan) {
                     var rows = '';
                     $.each(json.responden_per_layanan, function(i, val) {
                        rows += `
                           <tr>
                              <td class="text-center text-muted">${i+1}</td>
                              <td>${val.kuesioner_layanan_nama}</td>
                              <td class="text-center"><span class="badge-count">${val.total}</span></td>
                           </tr>`;
                     });
                     $('#table-responden-tab1 tbody').html(rows);
                     // Update Footer Total
                     $('#total-responden-display').text(json.total_responden);
                  }
                  return json.data;
               }
            },
            columns: dtColumns,
            ordering: false, 
            pageLength: 100, 
            drawCallback: function(settings) {
               var api = this.api(); var rows = api.rows({ page: 'current' }).nodes();
               [0, 1, 2].forEach(function(colIdx) {
                  api.column(colIdx, { page: 'current' }).data().each(function(content, i) {
                     if (i % 3 === 0) {
                        var cell = $(rows).eq(i).find('td').eq(colIdx);
                        cell.attr('rowspan', '3'); cell.css({ 'vertical-align': 'middle' });
                     } else { $(rows).eq(i).find('td').eq(colIdx).css('display', 'none'); }
                  });
               });
            },
            createdRow: function(row, data, dataIndex) {
               if (data.tipe === 'Gap') {
                  $(row).addClass('fw-bold').css({ 'color': '#0d6efd', 'border-bottom': '2px solid #dee2e6' });
                  layananList.forEach(function(l) {
                     var k = 'layanan_' + l.kuesioner_layanan_uuid;
                     if (parseFloat(data[k]) < 0) $('td', row).filter(function() { return $(this).text() == data[k]; }).addClass('text-danger');
                  });
                  if (parseFloat(data['rata_rata']) < 0) $('td:last', row).addClass('text-danger');
               }
            }
         });

         // =========================================================
         // 2. LOGIKA TAB 2 (REKAP IKL)
         // =========================================================
         function loadRekapIKL() {
            $.ajax({
               url: "{{ url('/kuesioner/hasil-analisa-list-ikl') }}", method: "GET",
               success: function(response) {
                  var dataRows = response.data; var totalResponden = parseInt(response.total_responden); var totalImp = parseFloat(response.total_importance || 0);
                  var tableBody = ""; var sumWeight = 0; var sumWeightIndex = 0;
                  $.each(dataRows, function(index, item) {
                     var w = parseFloat(item.weight); var wIdx = parseFloat(item.weight_index); sumWeight += w; sumWeightIndex += wIdx;
                     tableBody += `<tr><td class="text-center">${index + 1}</td><td class="text-center fw-bold">${item.kode}</td><td>${item.parameter}</td><td class="text-center">${parseFloat(item.importance).toFixed(2)}</td><td class="text-center">${w.toFixed(3)}</td><td class="text-center">${parseFloat(item.performance).toFixed(2)}</td><td class="text-center fw-bold text-primary">${wIdx.toFixed(3)}</td></tr>`;
                  });
                  $('#table-ikl tbody').html(tableBody); $('#foot-total-imp').text(totalImp.toFixed(2)); $('#foot-total-weight').text(sumWeight.toFixed(3)); $('#foot-total-index').text(sumWeightIndex.toFixed(3));
                  $('#display-ikl-score').text(sumWeightIndex.toFixed(2).replace('.', ',')); $('#display-responden').text(totalResponden);
                  var moePercent = "0.0%";
                  if (totalResponden > 0) { var zScore = 1.645; var sigma = 0.5; var standardError = sigma / Math.sqrt(totalResponden); var moe = zScore * standardError; moePercent = (moe * 100).toFixed(1) + '%'; }
                  $('#display-moe').text(moePercent);
                  $('#cat-1, #cat-2, #cat-3, #cat-4').removeClass('active-category');
                  var score = sumWeightIndex;
                  if (score >= 1.00 && score <= 2.59) { $('#cat-1').addClass('active-category'); } else if (score >= 2.60 && score <= 3.06) { $('#cat-2').addClass('active-category'); } else if (score >= 3.07 && score <= 3.53) { $('#cat-3').addClass('active-category'); } else if (score >= 3.54) { $('#cat-4').addClass('active-category'); }
               }
            });
         }

         // =========================================================
         // 3. LOGIKA TAB 3 (REKAP MATRIKS)
         // =========================================================
         var chartMatriks = null;
         function loadRekapMatriks() {
            $.ajax({
               url: "{{ url('/kuesioner/hasil-analisa-list-matriks') }}", method: "GET",
               success: function(response) {
                  var data = response.data; var axisX = parseFloat(response.axis_x); var axisY = parseFloat(response.axis_y);
                  $('#val-axis-y').text(axisY.toFixed(2)); $('#val-axis-x').text(axisX.toFixed(2));
                  var tableBody = ""; var chartPoints = [];
                  $.each(data, function(index, item) {
                     var p = parseFloat(item.performance); var i = parseFloat(item.importance); var g = parseFloat(item.gap_score); var gapClass = g < 0 ? 'text-danger fw-bold' : 'text-success fw-bold';
                     tableBody += `<tr><td class="text-center">${index + 1}</td><td class="text-center fw-bold">${item.kode}</td><td>${item.parameter}</td><td class="text-center">${p.toFixed(2)}</td><td class="text-center">${i.toFixed(2)}</td><td class="text-center ${gapClass}">${g.toFixed(2)}</td></tr>`;
                     chartPoints.push({ x: p, y: i, kode: item.kode, parameter: item.parameter });
                  });
                  $('#table-matriks tbody').html(tableBody);
                  var ctx = document.getElementById('matriksChart').getContext('2d');
                  if (chartMatriks) chartMatriks.destroy();
                  chartMatriks = new Chart(ctx, {
                     type: 'scatter', data: { datasets: [{ label: 'Parameter', data: chartPoints, backgroundColor: '#5b9bd5', borderColor: '#41719c', borderWidth: 1, pointRadius: 8, pointHoverRadius: 10 }] },
                     options: { responsive: true, maintainAspectRatio: false, layout: { padding: 20 }, scales: { x: { title: { display: true, text: 'Performance' }, min: 0, max: 6, grid: { color: '#e0e0e0' } }, y: { title: { display: true, text: 'Importance' }, min: 0, max: 6, grid: { color: '#e0e0e0' } } }, plugins: { legend: { display: false }, quadrantLines: false } },
                     plugins: [{
                        id: 'customMatrix',
                        beforeDraw: function(chart) {
                           var ctx = chart.ctx; var xAxis = chart.scales['x']; var yAxis = chart.scales['y']; var xVal = axisX; var yVal = axisY; if(xVal <= 0 || yVal <= 0) return;
                           var xPixel = xAxis.getPixelForValue(xVal); var yPixel = yAxis.getPixelForValue(yVal);
                           ctx.save(); ctx.strokeStyle = '#bfbfbf'; ctx.lineWidth = 3; ctx.beginPath(); ctx.moveTo(xPixel, yAxis.top); ctx.lineTo(xPixel, yAxis.bottom); ctx.stroke(); ctx.beginPath(); ctx.moveTo(xAxis.left, yPixel); ctx.lineTo(xAxis.right, yPixel); ctx.stroke(); ctx.restore();
                        },
                        afterDatasetsDraw: function(chart) {
                           var ctx = chart.ctx; ctx.font = 'bold 12px Arial'; ctx.fillStyle = '#333'; ctx.textAlign = 'center'; ctx.textBaseline = 'middle'; 
                           chart.data.datasets.forEach(function(dataset, i) { var meta = chart.getDatasetMeta(i); meta.data.forEach(function(element, index) { var dataItem = dataset.data[index]; if(dataItem.x > 0 && dataItem.y > 0) { ctx.fillText(dataItem.kode, element.x, element.y + 15); } }); });
                        }
                     }]
                  });
               }
            });
         }

         // --- TRIGGER PINDAH TAB ---
         $('a[data-toggle="tab"]').on('shown.bs.tab', function(e) {
            if (e.target.hash === '#tab-grafik') loadRekapIKL();
            else if (e.target.hash === '#tab-data') setTimeout(function() { table.columns.adjust().draw(); }, 200);
            else if (e.target.hash === '#tab-info') loadRekapMatriks();
         });
      });
   </script>
@endsection