@extends('layout.dapur.app')

@section('content')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        :root {
            --brand-blue: #4099ff;
            --brand-green: #0e9e4a;
            --brand-orange: #FFB64D;
            --brand-purple: #7c5cff;
            --brand-red: #D85A30;
        }

        .ov-card {
            background: #fff;
            border: 1px solid #edf0f5;
            border-radius: 16px;
            transition: box-shadow .2s ease, transform .2s ease;
        }

        .ov-card:hover {
            box-shadow: 0 8px 24px rgba(20, 30, 60, 0.06);
        }

        .ov-card-header {
            padding: 18px 22px;
            border-bottom: 1px solid #f0f2f7;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .ov-card-header h6 {
            font-size: 14px;
            font-weight: 700;
            color: #22262e;
            margin: 0;
            letter-spacing: .2px;
        }

        .ov-card-header .ov-sub {
            font-size: 12px;
            color: #8a93a3;
            margin-top: 2px;
        }

        .ov-card-body {
            padding: 20px 22px;
        }

        /* KPI cards */
        .kpi {
            position: relative;
            background: #fff;
            border: 1px solid #edf0f5;
            border-radius: 16px;
            padding: 20px 22px;
            overflow: hidden;
            height: 100%;
        }

        .kpi::before {
            content: '';
            position: absolute;
            inset: 0 auto 0 0;
            width: 4px;
        }

        .kpi.total::before { background: var(--brand-blue); }
        .kpi.c-1::before   { background: var(--brand-green); }
        .kpi.c-2::before   { background: var(--brand-orange); }
        .kpi.c-3::before   { background: var(--brand-purple); }

        .kpi-icon {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            margin-bottom: 14px;
        }

        .kpi.total .kpi-icon { background: rgba(64,153,255,.12); color: var(--brand-blue); }
        .kpi.c-1 .kpi-icon   { background: rgba(14,158,74,.12); color: var(--brand-green); }
        .kpi.c-2 .kpi-icon   { background: rgba(255,182,77,.18); color: #c98411; }
        .kpi.c-3 .kpi-icon   { background: rgba(124,92,255,.12); color: var(--brand-purple); }

        .kpi .kpi-number {
            font-size: 28px;
            font-weight: 700;
            color: #1a1d24;
            line-height: 1.1;
        }

        .kpi .kpi-label {
            font-size: 12.5px;
            color: #8a93a3;
            margin-top: 4px;
            font-weight: 500;
        }

        .page-header-modern {
            background: linear-gradient(135deg, #4099ff 0%, #3b7ce0 100%);
            border-radius: 18px;
            padding: 26px 28px;
            color: #fff;
            margin-bottom: 24px;
        }

        .page-header-modern h5 {
            font-weight: 700;
            margin: 0 0 4px 0;
        }

        .page-header-modern p {
            margin: 0;
            opacity: .85;
            font-size: 13px;
        }

        .table-modern thead th {
            font-size: 11.5px;
            text-transform: uppercase;
            letter-spacing: .4px;
            color: #8a93a3;
            font-weight: 700;
            border-bottom: 1px solid #edf0f5;
            background: #fafbfd;
        }

        .table-modern td {
            vertical-align: middle;
            font-size: 13.5px;
        }
    </style>

    <section class="pcoded-main-container">
        <div class="pcoded-content">

            {{-- HEADER --}}
            <div class="page-header-modern">
                <h5>{{ $judulhalaman }}</h5>
                <p>Ringkasan partisipasi dan sebaran responden survei kepuasan layanan.</p>
            </div>

            {{-- === KPI CARDS === --}}
            <div class="row g-3 mb-3">
                <div class="col-md-3 col-sm-6">
                    <div class="kpi total">
                        <div class="kpi-icon"><i class="fa fa-users"></i></div>
                        <div class="kpi-number">{{ number_format($total_responden) }}</div>
                        <div class="kpi-label">Total Partisipan</div>
                    </div>
                </div>

                @php
                    $kpiColors = ['c-1', 'c-2', 'c-3', 'c-1'];
                    $kpiIcons = [];
                    $i = 0;
                @endphp
                @foreach ($summary_instansi as $sum)
                    <div class="col-md-3 col-sm-6">
                        <div class="kpi {{ $kpiColors[$i % 4] }}">
                            <div class="kpi-icon">
                                @if (Str::contains($sum->referensi_nama, 'Pemerintah'))
                                    <i class="fa fa-landmark"></i>
                                @elseif(Str::contains($sum->referensi_nama, 'Pemangku'))
                                    <i class="fa fa-briefcase"></i>
                                @else
                                    <i class="fa fa-user-tag"></i>
                                @endif
                            </div>
                            <div class="kpi-number">{{ number_format($sum->total) }}</div>
                            <div class="kpi-label">{{ Str::limit($sum->referensi_nama, 28) }}</div>
                        </div>
                    </div>
                    @php $i++; @endphp
                @endforeach
            </div>

            {{-- === CHART: INSTANSI + APK === --}}
            <div class="row g-3 mb-3">
                <div class="col-lg-4 col-md-12">
                    <div class="ov-card h-100">
                        <div class="ov-card-header">
                            <div>
                                <h6>Komposisi Instansi Asal</h6>
                                <div class="ov-sub">Distribusi seluruh responden</div>
                            </div>
                        </div>
                        <div class="ov-card-body">
                            <div style="height: 280px; position: relative;">
                                <canvas id="chartInstansi"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-8 col-md-12">
                    <div class="ov-card h-100">
                        <div class="ov-card-header">
                            <div>
                                <h6>Detail Anggota Pemangku Kepentingan (APK)</h6>
                                <div class="ov-sub">Jumlah responden per kalangan</div>
                            </div>
                        </div>
                        <div class="ov-card-body">
                            <div style="height: 280px;">
                                <canvas id="chartAPK"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- === CHART: KEMENTERIAN (FULL ROW) === --}}
            <div class="row g-3 mb-3">
                <div class="col-sm-12">
                    <div class="ov-card">
                        <div class="ov-card-header">
                            <div>
                                <h6>Partisipasi Anggota Pemerintah (Kementerian/Lembaga)</h6>
                                <div class="ov-sub">Jumlah responden per kementerian/lembaga</div>
                            </div>
                        </div>
                        <div class="ov-card-body">
                            <div style="height: 380px;">
                                <canvas id="chartAP"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- === CHART: PEMDA / PROVINSI (FULL ROW, BARU) === --}}
            <div class="row g-3 mb-3">
                <div class="col-sm-12">
                    <div class="ov-card">
                        <div class="ov-card-header">
                            <div>
                                <h6>Sebaran Responden Pemerintah Daerah / Provinsi</h6>
                                <div class="ov-sub">Total {{ number_format($total_pemda_responden) }} responden dari unsur Pemda</div>
                            </div>
                        </div>
                        <div class="ov-card-body">
    @php
        $pemdaHeight = max(480, count($chart_pemda_label) * 26);
    @endphp
    <div style="height: {{ $pemdaHeight }}px;">
        <canvas id="chartPemda"></canvas>
    </div>
</div>
                    </div>
                </div>
            </div>

            {{-- === TABEL DATA === --}}
            <div class="row g-3">
                <div class="col-sm-12">
                    <div class="ov-card">
                        <div class="ov-card-header">
                            <div>
                                <h6>Statistik Per Layanan Survei</h6>
                                <div class="ov-sub">Jumlah responden pada masing-masing layanan</div>
                            </div>
                        </div>
                        <div class="ov-card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-modern table-hover mb-0">
                                    <thead>
                                        <tr>
                                            <th class="text-center" width="5%">No</th>
                                            <th>Nama Layanan Survei</th>
                                            <th class="text-center" width="20%">Jumlah Responden</th>
                                            <th class="text-center" width="15%">Status Data</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($layanan_stats as $index => $ls)
                                            <tr>
                                                <td class="text-center">{{ $index + 1 }}</td>
                                                <td class="font-weight-bold text-dark">{{ $ls->kuesioner_layanan_nama }}</td>
                                                <td class="text-center">
                                                    <span class="fw-bold text-primary">{{ number_format($ls->total) }}</span>
                                                </td>
                                                <td class="text-center">
                                                    @if ($ls->total > 0)
                                                        <span class="badge badge-light-success">Tersedia</span>
                                                    @else
                                                        <span class="badge badge-light-secondary">Kosong</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="text-center py-4 text-muted">Belum ada layanan survei.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>

    <script>
        document.addEventListener("DOMContentLoaded", function() {

            Chart.defaults.font.family = "'Proxima Nova', sans-serif";
            Chart.defaults.color = '#666';
            Chart.defaults.scale.grid.color = '#f0f0f0';
            Chart.defaults.scale.grid.borderColor = 'transparent';

            const dataInstansiLabel = {!! json_encode($chart_instansi_label) !!};
            const dataInstansiVal = {!! json_encode($chart_instansi_data) !!};
            const dataApkLabel = {!! json_encode($chart_apk_label) !!};
            const dataApkVal = {!! json_encode($chart_apk_data) !!};
            const dataApLabel = {!! json_encode($chart_ap_label) !!};
            const dataApVal = {!! json_encode($chart_ap_data) !!};
            const dataPemdaLabel = {!! json_encode($chart_pemda_label) !!};
            const dataPemdaVal = {!! json_encode($chart_pemda_data) !!};

            // 1. DOUGHNUT (Instansi)
            new Chart(document.getElementById('chartInstansi'), {
                type: 'doughnut',
                data: {
                    labels: dataInstansiLabel,
                    datasets: [{
                        data: dataInstansiVal,
                        backgroundColor: ['#4099ff', '#0e9e4a', '#FFB64D', '#7c5cff'],
                        borderWidth: 2,
                        borderColor: '#fff',
                        hoverOffset: 10
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '65%',
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: { boxWidth: 12, usePointStyle: true, padding: 20 }
                        }
                    }
                }
            });

            // 2. BAR VERTICAL (APK)
            new Chart(document.getElementById('chartAPK'), {
                type: 'bar',
                data: {
                    labels: dataApkLabel,
                    datasets: [{
                        label: 'Responden',
                        data: dataApkVal,
                        backgroundColor: '#4099ff',
                        borderRadius: 5,
                        barPercentage: 0.6,
                        categoryPercentage: 0.8
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        y: { beginAtZero: true, ticks: { stepSize: 1, precision: 0 }, grid: { borderDash: [2, 2] } },
                        x: { grid: { display: false } }
                    }
                }
            });

            // 3. BAR HORIZONTAL (Kementerian)
            new Chart(document.getElementById('chartAP'), {
                type: 'bar',
                data: {
                    labels: dataApLabel,
                    datasets: [{
                        label: 'Responden',
                        data: dataApVal,
                        backgroundColor: '#0e9e4a',
                        borderRadius: 4,
                        barPercentage: 0.7,
                        categoryPercentage: 0.8
                    }]
                },
                options: {
                    indexAxis: 'y',
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        x: { beginAtZero: true, ticks: { stepSize: 1 }, grid: { borderDash: [2, 2] } },
                        y: { grid: { display: false } }
                    }
                }
            });

            // 4. BAR HORIZONTAL (Pemda / Provinsi) - BARU
            new Chart(document.getElementById('chartPemda'), {
    type: 'bar',
    data: {
        labels: dataPemdaLabel,
        datasets: [{
            label: 'Responden',
            data: dataPemdaVal,
            backgroundColor: '#7c5cff',
            borderRadius: 4,
            barPercentage: 0.7,
            categoryPercentage: 0.8
        }]
    },
    options: {
        indexAxis: 'y',
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { display: false } },
        scales: {
            x: { beginAtZero: true, ticks: { stepSize: 1 }, grid: { borderDash: [2, 2] } },
            y: {
                grid: { display: false },
                ticks: { font: { size: 11 } } // font label provinsi sedikit lebih kecil
            }
        }
    }
});
        });
    </script>
@endsection