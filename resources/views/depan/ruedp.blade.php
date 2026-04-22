@extends('layout.depan.app')

@section('content')
    <div role="main" class="main">

        <section class="page-header page-header-modern bg-color-light-scale-1 page-header-md"
            style="background: url('{{ asset('theme/img/ndr-banner-green.webp') }}'); background-size:cover; background-position: 0 100%;">
            <div class="container">
                <div class="row">
                    <div class="col-md-12 align-self-center order-1">
                        <ul class="breadcrumb d-block text-center">
                            <li><a href="{{ url('/') }}" class="warna-putih">Home</a></li>
                            <li class="active warna-hitam">Progres RUED Provinsi</li>
                        </ul>
                    </div>
                    <div class="col-md-12 align-self-center p-static order-2 text-center">
                        <h1 class="font-weight-bold text-6 warna-putih">Progres Penyusunan RUED Provinsi</h1>
                    </div>
                </div>
            </div>
        </section>

        <div class="container py-5">

            {{-- Legend Status --}}
            <div class="row mb-4">
                <div class="col-12">
                    <div class="d-flex flex-wrap gap-3 justify-content-center" id="legendStatus">
                        @foreach ($statusList as $status)
                            <div class="d-flex align-items-center gap-2">
                                <div
                                    style="width:16px;height:16px;border-radius:3px;background:{{ $status->warna }};border:1px solid #ddd;">
                                </div>
                                <span style="font-size:0.85rem;">
                                    <strong>{{ $status->provinsi_count }}</strong> Provinsi — {{ $status->nama_status }}
                                </span>
                            </div>
                        @endforeach
                        <div class="d-flex align-items-center gap-2">
                            <div style="width:16px;height:16px;border-radius:3px;background:#e0e0e0;border:1px solid #ddd;">
                            </div>
                            <span style="font-size:0.85rem;">Data belum tersedia</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Map Container --}}
            <div class="row">
                <div class="col-12">
                    <div id="mapContainer"
                        style="width:100%;background:#f8f9fa;border-radius:12px;padding:20px;position:relative;">
                        <div id="mapLoading" class="text-center py-5">
                            <i class="fas fa-spinner fa-spin fa-2x text-muted"></i>
                            <p class="text-muted mt-2">Memuat peta...</p>
                        </div>
                        <div id="mapWrapper" style="display:none;">
                            <div id="indonesiaMap" style="width:100%;"></div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Status Cards --}}
            <div class="row mt-5">
                @foreach ($statusList as $status)
                    <div class="col-md-4 mb-4">
                        <div class="card h-100" style="border-top:4px solid {{ $status->warna }};border-radius:10px;">
                            <div class="card-body text-center">
                                <h2 class="font-weight-bold mb-1" style="color:{{ $status->warna }};font-size:2.5rem;">
                                    {{ $status->provinsi_count }}
                                </h2>
                                <p class="text-muted mb-0" style="font-size:0.85rem;">Provinsi</p>
                                <p class="font-weight-semibold mt-1">{{ $status->nama_status }}</p>
                                <button class="btn btn-sm btn-outline-secondary mt-2"
                                    onclick="showDetailStatus({{ $status->id_ruedp_status }}, '{{ $status->nama_status }}', '{{ $status->warna }}')">
                                    Lihat Detail <i class="fas fa-chevron-right"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

        </div>
    </div>

    {{-- Modal Detail Provinsi --}}
    <div class="modal fade" id="modalProvinsi" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header" id="modalProvinsiHeader">
                    <h5 class="modal-title" id="modalProvinsiTitle">Detail Provinsi</h5>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <div class="modal-body" id="modalProvinsiBody"></div>
            </div>
        </div>
    </div>

    {{-- Modal Detail Klik Peta --}}
    <div class="modal fade" id="modalPeta" tabindex="-1" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header" id="modalPetaHeader">
                    <h5 class="modal-title" id="modalPetaTitle">-</h5>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <div class="modal-body" id="modalPetaBody"></div>
            </div>
        </div>
    </div>

    {{-- Tooltip --}}
    <div id="mapTooltip"
        style="
    position:fixed;
    background:rgba(0,0,0,0.75);
    color:#fff;
    padding:6px 12px;
    border-radius:6px;
    font-size:0.8rem;
    pointer-events:none;
    display:none;
    z-index:9999;
">
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/d3/7.8.5/d3.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/topojson/3.0.2/topojson.min.js"></script>

    <script>
        let mapData = {};
        let allProvinsiData = [];

        // Load data provinsi dari API
        $.get("{{ route('ruedp.mapdata') }}", function(data) {
            allProvinsiData = data;
            data.forEach(function(p) {
                mapData[p.kode] = p;
            });
            loadMap();
        });

        function loadMap() {
            const width = document.getElementById('indonesiaMap').offsetWidth || 900;
            const height = width * 0.5;

            const svg = d3.select('#indonesiaMap')
                .append('svg')
                .attr('width', '100%')
                .attr('viewBox', `0 0 ${width} ${height}`)
                .attr('preserveAspectRatio', 'xMidYMid meet');

            const projection = d3.geoMercator()
                .center([118, -2])
                .scale(width * 1.1)
                .translate([width / 2, height / 2]);

            const path = d3.geoPath().projection(projection);

            // Load GeoJSON Indonesia
            d3.json("{{ asset('maps/indonesia-provinces.json') }}").then(function(geojson) {
                svg.selectAll('path')
                    .data(geojson.features)
                    .enter()
                    .append('path')
                    .attr('d', path)
                    .attr('fill', function(d) {
                        const kode = d.properties.ISO;
                        return mapData[kode] ? mapData[kode].warna : '#e0e0e0';
                    })
                    .attr('stroke', '#fff')
                    .attr('stroke-width', 0.8)
                    .style('cursor', 'pointer')
                    .on('mousemove', function(event, d) {
                        const kode = d.properties.ISO;
                        const info = mapData[kode];
                        const nama = info ? info.nama : (d.properties.name || kode);
                        const status = info ? info.status : 'Data belum tersedia';

                        d3.select('#mapTooltip')
                            .style('display', 'block')
                            .style('left', (event.clientX + 12) + 'px')
                            .style('top', (event.clientY - 28) + 'px')
                            .html('<strong>' + nama + '</strong><br>' + status);

                        d3.select(this)
                            .attr('stroke', '#333')
                            .attr('stroke-width', 1.5);
                    })
                    .on('mouseleave', function() {
                        d3.select('#mapTooltip').style('display', 'none');
                        d3.select(this)
                            .attr('stroke', '#fff')
                            .attr('stroke-width', 0.8);
                    })
                    .on('click', function(event, d) {
                        const kode = d.properties.ISO;
                        const info = mapData[kode];
                        if (info) {
                            showModalPeta(info);
                        } else {
                            const nama = d.properties.name || kode;
                            $('#modalPetaTitle').text(nama);
                            $('#modalPetaBody').html(
                                '<p class="text-muted">Data belum tersedia untuk provinsi ini.</p>');
                            $('#modalPeta').modal('show');
                        }
                    });

                $('#mapLoading').hide();
                $('#mapWrapper').show();
            });
        }

        function showModalPeta(info) {
            $('#modalPetaHeader').css('border-bottom', '3px solid ' + info.warna);
            $('#modalPetaTitle').text(info.nama);
            $('#modalPetaBody').html(`
        <table class="table table-sm table-borderless">
            <tr>
                <td width="40%"><strong>Status</strong></td>
                <td><span class="badge" style="background:${info.warna};color:#fff;">${info.status}</span></td>
            </tr>
            <tr>
                <td><strong>Nomor Perda</strong></td>
                <td>${info.nomor_perda}</td>
            </tr>
            <tr>
                <td><strong>Tanggal Update</strong></td>
                <td>${info.tanggal_update}</td>
            </tr>
            <tr>
                <td><strong>Keterangan</strong></td>
                <td>${info.keterangan}</td>
            </tr>
        </table>
    `);
            $('#modalPeta').modal('show');
        }

        function showDetailStatus(idStatus, namaStatus, warna) {
            const provinsi = allProvinsiData.filter(p => {
                return p.status === namaStatus;
            });

            $('#modalProvinsiHeader').css('border-bottom', '3px solid ' + warna);
            $('#modalProvinsiTitle').text(namaStatus + ' (' + provinsi.length + ' Provinsi)');

            if (provinsi.length === 0) {
                $('#modalProvinsiBody').html('<p class="text-muted text-center">Belum ada data provinsi.</p>');
            } else {
                let html = '<div class="table-responsive"><table class="table table-sm table-striped">';
                html +=
                    '<thead><tr><th>No</th><th>Provinsi</th><th>No. Perda</th><th>Tgl Update</th><th>Keterangan</th></tr></thead><tbody>';
                provinsi.forEach(function(p, i) {
                    html += `<tr>
                <td>${i+1}</td>
                <td>${p.nama}</td>
                <td>${p.nomor_perda}</td>
                <td>${p.tanggal_update}</td>
                <td>${p.keterangan}</td>
            </tr>`;
                });
                html += '</tbody></table></div>';
                $('#modalProvinsiBody').html(html);
            }

            $('#modalProvinsi').modal('show');
        }
    </script>
@endsection
