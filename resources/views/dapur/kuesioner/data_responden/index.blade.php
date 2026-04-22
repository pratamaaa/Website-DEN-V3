@extends('layout.dapur.app')

@section('content')
    <style>
        .table-container {
            border: 1px solid #dee2e6;
            border-radius: 8px;
            overflow: hidden;
            background: #fff;
        }

        .table-responsive-custom {
            overflow-x: auto;
            width: 100%;
            -webkit-overflow-scrolling: touch;
        }

        .table {
            margin-bottom: 0 !important;
            width: 100%;
            border-collapse: collapse;
        }

        .table th {
            text-align: center;
            vertical-align: middle;
            font-size: 11px;
            padding: 10px 8px !important;
            white-space: nowrap;
            background-color: #f8f9fa;
            border-bottom: 2px solid #dee2e6 !important;
            border-right: 1px solid #dee2e6;
            text-transform: uppercase;
        }

        .table td {
            vertical-align: middle;
            padding: 8px 8px !important;
            font-size: 12px;
            text-align: center;
            border-bottom: 1px solid #e9ecef;
            border-right: 1px solid #e9ecef;
        }

        .th-responden {
            min-width: 150px !important;
            background-color: #e0f7fa !important;
            color: #006064;
            font-weight: 700;
        }

        .col-param {
            text-align: left !important;
            white-space: normal !important;
            min-width: 300px !important;
            line-height: 1.4;
        }

        .col-aspek {
            font-weight: 500;
            font-size: 11px;
        }

        .row-gap td {
            font-weight: bold;
            background-color: #fcfcfc;
            border-top: 1px solid #dee2e6;
        }

        .text-negatif {
            color: #dc3545 !important;
            font-weight: 800;
        }

        .text-positif {
            color: #198754 !important;
            font-weight: 800;
        }

        .text-muted-custom {
            color: #ced4da;
            font-weight: normal;
        }

        .filter-box {
            background: #fff;
            border: 1px solid #edf2f7;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.03);
            margin-bottom: 20px;
        }

        .btn-detail-resp {
            padding: 2px 8px;
            font-size: 10px;
            margin-top: 5px;
        }
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
                            <div class="filter-box">
                                <div class="form-group row mb-0 align-items-center">
                                    <label class="col-md-2 col-form-label fw-bold">Pilih Layanan Survei:</label>
                                    <div class="col-md-6">
                                        <select class="form-control" id="filter_layanan">
                                            <option value="">-- Pilih Layanan --</option>
                                            @foreach ($daftarLayanan as $l)
                                                <option value="{{ $l->kuesioner_layanan_uuid }}">
                                                    {{ $l->kuesioner_layanan_nama }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <button class="btn btn-primary btn-block" onclick="loadTable()">
                                            <i class="feather icon-search"></i> Tampilkan Data
                                        </button>
                                    </div>
                                    <div class="col-md-2">
                                        <button id="btnExport" class="btn btn-success btn-block" onclick="exportExcel()"
                                            style="display:none;">
                                            <i class="feather icon-download"></i> Export Excel
                                        </button>
                                    </div>

                                </div>
                            </div>

                            <div class="table-container">
                                <div class="table-responsive-custom">
                                    <table id="table-responden" class="table table-hover mb-0">
                                        {{-- Header dan Body diisi oleh Javascript --}}
                                    </table>
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
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            // Tampilan awal tabel
            $('#table-responden').html(
                '<tbody><tr><td class="p-5 text-muted">Silahkan pilih layanan terlebih dahulu.</td></tr></tbody>'
            );
        });

        function loadTable() {
            var layananId = $('#filter_layanan').val();
            if (!layananId) {
                alert('Harap pilih layanan terlebih dahulu!');
                return;
            }

            $('#table-responden').html(
                '<tbody><tr><td class="text-center p-5"><i class="fa fa-spinner fa-spin mr-2"></i> Sedang memuat data...</td></tr></tbody>'
            );

            $.ajax({
                url: "{{ url('/kuesioner/data-responden-list') }}",
                type: "GET",
                data: {
                    layanan_id: layananId
                },
                success: function(response) {
                    renderTable(response);
                },
                error: function() {
                    alert('Gagal memuat data.');
                }
            });
        }

        function renderTable(data) {
            var headers = data.headers; // Array of {nama, uuid}
            var rows = data.rows;

            // --- 1. BUILD HEADER ---
            var theadHtml = '<thead class="table-light text-center"><tr>';
            theadHtml += '<th width="50px">No</th>';
            theadHtml += '<th width="70px">Kode</th>';
            theadHtml += '<th class="col-param">Parameter</th>';
            theadHtml += '<th width="90px">Aspek</th>';

            headers.forEach(function(h) {
                theadHtml += `
                <th class="th-responden">
                    <div>${h.nama}</div>
                    <a href="{{ url('/kuesioner/data-responden-detail') }}/${h.uuid}" class="btn btn-info btn-detail-resp shadow-sm">Detail</a>
                </th>`;
            });

            theadHtml += '<th class="bg-light" width="80px">Rata-Rata</th></tr></thead>';

            // --- 2. BUILD BODY ---
            var tbodyHtml = '<tbody>';
            if (rows.length === 0) {
                tbodyHtml += '<tr><td colspan="100%" class="p-5 text-muted">Belum ada data pertanyaan/responden.</td></tr>';
            } else {
                rows.forEach(function(row) {
                    var trClass = (row.aspek === 'Gap') ? 'row-gap' : '';
                    var aspekHtml = (row.aspek === 'Gap') ? '<span class="text-primary fw-bold">Gap</span>' : row
                        .aspek;

                    tbodyHtml += `<tr class="${trClass}">`;

                    if (row.aspek === 'Importance') {
                        tbodyHtml += `<td class="bg-white" rowspan="3">${row.no}</td>`;
                        tbodyHtml += `<td class="bg-white fw-bold" rowspan="3">${row.kode}</td>`;
                        tbodyHtml += `<td class="bg-white col-param" rowspan="3">${row.parameter}</td>`;
                    }

                    tbodyHtml += `<td class="bg-white col-aspek">${aspekHtml}</td>`;

                    // Skor per responden
                    row.scores.forEach(function(score) {
                        var displayScore = (row.aspek === 'Gap') ? score.toFixed(2) : score;
                        var cellClass = '';
                        if (row.aspek === 'Gap') {
                            if (score < 0) cellClass = 'text-negatif';
                            else if (score > 0) cellClass = 'text-positif';
                        }
                        if (score == 0 && row.aspek !== 'Gap') cellClass = 'text-muted-custom';

                        tbodyHtml +=
                            `<td class="${cellClass}">${displayScore == 0 && row.aspek !== 'Gap' ? '-' : displayScore}</td>`;
                    });

                    // Rata-rata
                    var avgVal = parseFloat(row.rata_rata);
                    var avgClass = 'fw-bold bg-light';
                    if (row.aspek === 'Gap') {
                        if (avgVal < 0) avgClass += ' text-negatif';
                        else if (avgVal > 0) avgClass += ' text-positif';
                    }
                    tbodyHtml += `<td class="${avgClass}">${row.rata_rata}</td></tr>`;
                });
            }
            tbodyHtml += '</tbody>';

            $('#table-responden').html(theadHtml + tbodyHtml);

            // Tambah ini di paling bawah fungsi renderTable
            if (data.rows && data.rows.length > 0) {
                $('#btnExport').show();
            }

            // Simpan data untuk export
            window._lastExportData = data;

        }

        function exportExcel() {
            var data = window._lastExportData;
            if (!data || !data.rows || data.rows.length === 0) {
                alert('Tidak ada data untuk diekspor.');
                return;
            }

            var headers = data.headers;
            var rows = data.rows;

            // Build header row
            var headRow = ['No', 'Kode', 'Parameter', 'Aspek'];
            headers.forEach(function(h) {
                headRow.push(h.nama);
            });
            headRow.push('Rata-Rata');

            // Build data rows
            var sheetData = [headRow];
            rows.forEach(function(row) {
                var r = [row.no, row.kode, row.parameter, row.aspek];
                row.scores.forEach(function(score) {
                    if (row.aspek === 'Gap') {
                        r.push(parseFloat(score.toFixed(2)));
                    } else {
                        r.push(score == 0 ? '-' : score);
                    }
                });
                r.push(row.rata_rata);
                sheetData.push(r);
            });

            // Buat workbook
            var wb = XLSX.utils.book_new();
            var ws = XLSX.utils.aoa_to_sheet(sheetData);

            // Auto column width
            var colWidths = headRow.map(function(h, i) {
                var maxLen = String(h).length;
                sheetData.forEach(function(r) {
                    var cellLen = r[i] ? String(r[i]).length : 0;
                    if (cellLen > maxLen) maxLen = cellLen;
                });
                return {
                    wch: Math.min(maxLen + 2, 40)
                };
            });
            ws['!cols'] = colWidths;

            // Nama file dari dropdown
            var layananNama = $('#filter_layanan option:selected').text().trim();
            var fileName = 'data-responden-' + layananNama.replace(/\s+/g, '-').toLowerCase() + '.xlsx';

            XLSX.utils.book_append_sheet(wb, ws, 'Data Responden');
            XLSX.writeFile(wb, fileName);
        }

        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // --- TAMBAHKAN LOGIKA INI ---
            const urlParams = new URLSearchParams(window.location.search);
            const idDariUrl = urlParams.get('layanan_id');

            if (idDariUrl) {
                // Otomatis pilih di select option
                $('#filter_layanan').val(idDariUrl);
                // Langsung panggil fungsi muat tabel
                loadTable();
            } else {
                // Tampilan default jika tidak ada parameter
                $('#table-responden').html(
                    '<tbody><tr><td class="p-5 text-muted">Silahkan pilih layanan terlebih dahulu.</td></tr></tbody>'
                );
            }
            // ----------------------------
        });
    </script>
@endsection
