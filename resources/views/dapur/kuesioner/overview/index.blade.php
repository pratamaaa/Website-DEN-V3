@extends('layout.dapur.app')

@section('content')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        .card {
            border: 0.5px solid #e0e0e0;
            border-radius: 12px;
        }

        .card-header {
            background: transparent;
            border-bottom: 0.5px solid #e0e0e0;
            padding: 14px 18px;
        }

        .card-header h6 {
            font-size: 13px;
            font-weight: 500;
            color: #333;
            margin: 0;
        }

        /* Metric cards */
        .card-counter {
            border: 0.5px solid #e0e0e0 !important;
            border-left: none !important;
            border-radius: 12px !important;
            padding: 16px 18px !important;
            height: auto !important;
        }

        .card-counter::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            border-radius: 12px 12px 0 0;
        }

        .card-counter.total::before {
            background: #378ADD;
        }

        .card-counter.c-1::before {
            background: #1D9E75;
        }

        .card-counter.c-2::before {
            background: #EF9F27;
        }

        .card-counter.c-3::before {
            background: #D85A30;
        }

        .card-counter .count-numbers {
            font-size: 30px;
            font-weight: 500;
        }

        .card-counter .count-name {
            font-size: 12px;
            text-transform: none;
            letter-spacing: 0;
        }

        .card-counter i {
            font-size: 3.5em;
            opacity: 0.07;
        }
    </style>

    <section class="pcoded-main-container">
        <div class="pcoded-content">

            <div class="page-header mb-4">
                <div class="page-block">
                    <div class="row align-items-center">
                        <div class="col-md-12">
                            <div class="page-header-title">
                                <h5 class="m-b-10 font-weight-bold">{{ $judulhalaman }}</h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- === BAGIAN 1: KARTU SUMMARY (DINAMIS) === --}}
            <div class="row">
                {{-- Kartu Grand Total --}}
                <div class="col-md-3 col-sm-6">
                    <div class="card-counter total">
                        <i class="fa fa-users text-primary"></i>
                        <span class="count-numbers">{{ $total_responden }}</span>
                        <span class="count-name">Total Partisipan</span>
                    </div>
                </div>

                {{-- Kartu Per Instansi (Looping) --}}
                @php
                    $colors = ['c-1', 'c-2', 'c-3', 'c-1'];
                    $i = 0;
                @endphp
                @foreach ($summary_instansi as $sum)
                    <div class="col-md-3 col-sm-6">
                        <div class="card-counter {{ $colors[$i % 4] }}">
                            {{-- Icon dinamis simple --}}
                            @if (Str::contains($sum->referensi_nama, 'Pemerintah'))
                                <i class="fa fa-building text-success"></i>
                            @elseif(Str::contains($sum->referensi_nama, 'Pemangku'))
                                <i class="fa fa-briefcase text-warning"></i>
                            @else
                                <i class="fa fa-user-tag text-danger"></i>
                            @endif

                            <span class="count-numbers">{{ $sum->total }}</span>
                            {{-- Memendekkan nama agar muat di kartu --}}
                            <span class="count-name">{{ Str::limit($sum->referensi_nama, 25) }}</span>
                        </div>
                    </div>
                    @php $i++; @endphp
                @endforeach
            </div>

            {{-- === BAGIAN 2: GRAFIK UTAMA === --}}
            <div class="row">
                {{-- Doughnut Chart: Komposisi Instansi --}}
                <div class="col-lg-4 col-md-12">
                    <div class="card">
                        <div class="card-header pb-0 border-0">
                            <h6 class="font-weight-bold">Komposisi Instansi Asal</h6>
                        </div>
                        <div class="card-body">
                            <div style="height: 280px; position: relative;">
                                <canvas id="chartInstansi"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Bar Chart: Sebaran APK --}}
                <div class="col-lg-8 col-md-12">
                    <div class="card">
                        <div class="card-header pb-0 border-0">
                            <h6 class="font-weight-bold">Detail Anggota Pemangku Kepentingan (APK)</h6>
                        </div>
                        <div class="card-body">
                            <div style="height: 280px;">
                                <canvas id="chartAPK"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- === BAGIAN 3: GRAFIK KEMENTERIAN (FULL ROW) === --}}
            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-header pb-0 border-0">
                            <h6 class="font-weight-bold">Partisipasi Anggota Pemerintah (Kementerian/Lembaga)</h6>
                        </div>
                        <div class="card-body">
                            {{-- Height disesuaikan agar tidak gepeng --}}
                            <div style="height: 400px;">
                                <canvas id="chartAP"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- === BAGIAN 4: TABEL DATA === --}}
            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="font-weight-bold">Statistik Per Layanan Survei</h5>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover mb-0">
                                    <thead class="thead-light">
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
                                                <td class="font-weight-bold text-dark">{{ $ls->kuesioner_layanan_nama }}
                                                </td>
                                                <td class="text-center">
                                                    <h5 class="m-0 text-primary">{{ number_format($ls->total) }}</h5>
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
                                                <td colspan="4" class="text-center py-4 text-muted">Belum ada layanan
                                                    survei.</td>
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

            // Konfigurasi Umum Chart agar RAPIH
            Chart.defaults.font.family = "'Proxima Nova', sans-serif";
            Chart.defaults.color = '#666';
            Chart.defaults.scale.grid.color = '#f0f0f0'; // Grid halus
            Chart.defaults.scale.grid.borderColor = 'transparent'; // Hilangkan border axis

            const dataInstansiLabel = {!! json_encode($chart_instansi_label) !!};
            const dataInstansiVal = {!! json_encode($chart_instansi_data) !!};
            const dataApkLabel = {!! json_encode($chart_apk_label) !!};
            const dataApkVal = {!! json_encode($chart_apk_data) !!};
            const dataApLabel = {!! json_encode($chart_ap_label) !!};
            const dataApVal = {!! json_encode($chart_ap_data) !!};

            // 1. DOUGHNUT CHART (Instansi)
            new Chart(document.getElementById('chartInstansi'), {
                type: 'doughnut',
                data: {
                    labels: dataInstansiLabel,
                    datasets: [{
                        data: dataInstansiVal,
                        backgroundColor: ['#4099ff', '#0e9e4a', '#FFB64D'], // Biru, Hijau, Orange
                        borderWidth: 2,
                        borderColor: '#fff',
                        hoverOffset: 10
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '65%', // Bolong tengah lebih besar (Modern look)
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                boxWidth: 12,
                                usePointStyle: true,
                                padding: 20
                            }
                        }
                    }
                }
            });

            // 2. VERTICAL BAR CHART (APK)
            new Chart(document.getElementById('chartAPK'), {
                type: 'bar',
                data: {
                    labels: dataApkLabel,
                    datasets: [{
                        label: 'Responden',
                        data: dataApkVal,
                        backgroundColor: '#4099ff',
                        borderRadius: 5, // Sudut tumpul
                        barPercentage: 0.6, // Batang lebih tebal (default 0.9, makin kecil makin tipis, tapi categoryPercentage ngaruh)
                        categoryPercentage: 0.8 // Jarak antar kategori
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1,
                                precision: 0
                            },
                            grid: {
                                borderDash: [2, 2]
                            } // Grid putus-putus
                        },
                        x: {
                            grid: {
                                display: false
                            } // Hilangkan grid vertikal biar bersih
                        }
                    }
                }
            });

            // 3. HORIZONTAL BAR CHART (Kementerian)
            new Chart(document.getElementById('chartAP'), {
                type: 'bar',
                data: {
                    labels: dataApLabel,
                    datasets: [{
                        label: 'Responden',
                        data: dataApVal,
                        backgroundColor: '#0e9e4a', // Hijau
                        borderRadius: 4,
                        barPercentage: 0.7, // Batang tebal
                        categoryPercentage: 0.8
                    }]
                },
                options: {
                    indexAxis: 'y', // Mode Horizontal
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        x: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1
                            },
                            grid: {
                                borderDash: [2, 2]
                            }
                        },
                        y: {
                            grid: {
                                display: false
                            } // Hilangkan grid di sumbu Y (Label kementerian)
                        }
                    }
                }
            });
        });
    </script>
@endsection
