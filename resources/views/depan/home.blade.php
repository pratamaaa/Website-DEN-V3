@extends('layout.depan.app')

@section('content')
    <div role="main" class="main">
        <div class="slider-container rev_slider_wrapper" style="height: 720px;">
            <div id="revolutionSlider" class="slider rev_slider" data-version="5.4.8" data-plugin-revolution-slider
                data-plugin-options="{'delay': 9000, 'gridwidth': 2000, 'gridheight': 720, 'disableProgressBar': 'on', 'responsiveLevels': [4096,1200,992,500], 'parallax': { 'type': 'scroll', 'origo': 'enterpoint', 'speed': 1000, 'levels': [2,3,4,5,6,7,8,9,12,50], 'disable_onmobile': 'on' }, 'navigation' : {'arrows': { 'enable': true }, 'bullets': {'enable': true, 'style': 'bullets-style-1', 'h_align': 'center', 'v_align': 'bottom', 'space': 7, 'v_offset': 70, 'h_offset': 0}}}">
                <ul>
                    @if ($sliders->count() == 0)
                        <li data-transition="fade">
                            <img src="{{ asset('theme/img/slides/slide-bg.jpg') }}" alt=""
                                data-bgposition="center center" data-bgfit="cover" data-bgrepeat="no-repeat"
                                class="rev-slidebg">
                        </li>
                    @else
                        {{-- @foreach ($sliders->get() as $sli) --}}
                        @foreach ($sliders as $sli)
                            <li data-transition="fade">
                                <img src="{{ asset('uploads/imagesliders/' . $sli->gambar) }}" alt=""
                                    data-bgposition="center center" data-bgfit="cover" data-bgrepeat="no-repeat"
                                    class="rev-slidebg">
                            </li>
                        @endforeach
                    @endif
                </ul>
            </div>
        </div>

        <section class="section section-default border-0 section-center__ bg-white"
            style="background: url('{{ asset('theme/img/ndr-bg2.webp') }}'); background-size:cover; background-position: 0 100%;margin: 0px !important;">
            <div class="container">
                <div class="row">
                    <div class="col">
                        <h2 class="text-color-dark font-weight-normal text-6 mb-2 pb-1">
                            <a href="{{ url('/berita') }}">
                                <strong class="font-weight-extra-bold">Berita</strong> <span
                                    class="warna-hijau">Terbaru</span>
                            </a>
                        </h2>
                        <div class="owl-carousel owl-theme show-nav-title"
                            data-plugin-options="{'items': 4, 'margin': 10, 'loop': false, 'nav': true, 'dots': false}">
                            @foreach ($berita as $berita)
                                <div>
                                    <a href="{{ url('/berita/' . $berita->slug) }}">
                                        @if ($berita->gambar != '')
                                            <img alt="" class="img-fluid rounded"
                                                src="{{ asset('uploads/berita/' . $berita->gambar) }}">
                                        @else
                                            <img alt="" class="img-fluid rounded"
                                                src="{{ asset('uploads/logo/logoden-default2.jpg') }}">
                                        @endif
                                        <p class="text-2 marginatas-10 marginbawah-10 lineheight-17 ratakiri">
                                            <i class="fa fa-calendar"></i>
                                            {{ App\Helpers\Gudangfungsi::tanggalindo_hari($berita->tanggal_publikasi) }}
                                        </p>
                                        {{-- <br> --}}
                                        <p class="text-2 lineheight-17 ratakiri font-weight-bold warna-hitam">
                                            {{ $berita->judul }}</p>
                                    </a>
                                    <a href="{{ url('/berita/' . $berita->slug) }}" class="btn btn-primary btn-sm mb-2">
                                        Selengkapnya
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="section section-default border-0 section-center bgwarna-grey1">
            <div class="container">

                {{-- Header --}}
                <div class="row mb-3">
                    <div class="col">
                        <h2 class="text-color-dark font-weight-normal text-6 mb-0 pb-1"
                            style="margin-bottom: -15px !important;">
                            <span class="warna-hijau">Progresaaaaa Penyusunan</span>
                            <strong class="font-weight-extra-bold warna-putih">RUED Provinsi</strong>
                        </h2>
                        <p class="lead text-4 pt-2 font-weight-normal warna-light">
                            Status update per tanggal: {{ App\Helpers\Gudangfungsi::tanggalindo($rued->pertanggal) }}
                        </p>
                    </div>
                </div>

                {{-- Legend --}}
                <div class="row mb-3">
                    <div class="col">
                        <div class="d-flex flex-wrap gap-3">
                            @foreach ($ruedpStatus as $st)
                                <div class="d-flex align-items-center gap-2 me-3">
                                    <div
                                        style="width:14px;height:14px;border-radius:3px;background:{{ $st->warna }};border:1px solid rgba(255,255,255,0.3);flex-shrink:0;">
                                    </div>
                                    <span class="warna-light" style="font-size:0.82rem;">
                                        <strong>{{ $st->provinsi_count }}</strong> — {{ $st->nama_status }}
                                    </span>
                                </div>
                            @endforeach
                            <div class="d-flex align-items-center gap-2">
                                <div
                                    style="width:14px;height:14px;border-radius:3px;background:#e0e0e0;border:1px solid rgba(255,255,255,0.3);flex-shrink:0;">
                                </div>
                                <span class="warna-light" style="font-size:0.82rem;">Data belum tersedia</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Map --}}
                <div class="row mb-4">
                    <div class="col">
                        <div id="mapContainer"
                            style="width:100%;background:rgba(0,0,0,0.15);border-radius:12px;padding:16px;position:relative;min-height:300px;">
                            <div id="mapLoading" class="text-center py-5">
                                <i class="fas fa-spinner fa-spin fa-2x" style="color:rgba(255,255,255,0.5);"></i>
                                <p style="color:rgba(255,255,255,0.5);" class="mt-2">Memuat peta...</p>
                            </div>
                            <div id="mapWrapper" style="display:none;">
                                <div id="indonesiaMap" style="width:100%;"></div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Cards --}}
                <div class="row mb-5 pb-3">
                    @php
                        $jumRuedp = $ruedp->count();
                        $jumBox = max(1, intval(12 / $jumRuedp));
                    @endphp

                    {{-- @foreach ($ruedp->get() as $item) --}}
                    @foreach ($ruedp as $item)
                        <div class="col-md-6 col-lg-{{ $jumBox }} mb-4 mb-lg-0 appear-animation"
                            data-appear-animation="fadeInUpShorter" data-appear-animation-delay="200">
                            <div class="card text-center rounded-1" style="border-top:3px solid #2d8a45;">
                                <div class="card-body">
                                    <h4 class="card-title mb-1 text-4 font-weight-bold">
                                        {{ $item->jumlah_provinsi }} Provinsi
                                    </h4>
                                    <p class="card-text mb-2 pb-1">{{ $item->status_penyusunan }}</p>
                                    <a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#modalku"
                                        onclick="showFormRUED({{ $item->id_ruedp }})"
                                        class="read-more text-color-primary font-weight-semibold text-2">
                                        Read More <i class="fas fa-angle-right position-relative top-1 ms-1"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

            </div>
        </section>

        {{-- Tooltip --}}
        <div id="mapTooltip"
            style="
    position:fixed;
    background:rgba(0,0,0,0.8);
    color:#fff;
    padding:6px 12px;
    border-radius:6px;
    font-size:0.8rem;
    pointer-events:none;
    display:none;
    z-index:9999;
">
        </div>

        {{-- Modal klik peta --}}
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

        <script src="https://cdnjs.cloudflare.com/ajax/libs/d3/7.8.5/d3.min.js"></script>

        <script>
            // Data dari Laravel
            const mapData = {};
            const provinsiRaw = @json($ruedpMapData);

            provinsiRaw.forEach(function(p) {
                mapData[p.kode] = p;
            });

            $(document).ready(function() {
                loadMap();
            });

            function loadMap() {
                const container = document.getElementById('indonesiaMap');
                const width = container.offsetWidth || 900;
                const height = Math.round(width * 0.45);

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

                d3.json("{{ asset('/maps/indonesia-provinces.json') }}").then(function(geojson) {

                    svg.selectAll('path')
                        .data(geojson.features)
                        .enter()
                        .append('path')
                        .attr('d', path)
                        .attr('fill', function(d) {
                            const kode = d.properties.Propinsi.toUpperCase();
                            return mapData[kode] ? mapData[kode].warna : '#e0e0e0';
                        })
                        .attr('stroke', '#ffffff')
                        .attr('stroke-width', 0.7)
                        .style('cursor', 'pointer')
                        .on('mousemove', function(event, d) {
                            const kode = d.properties.Propinsi.toUpperCase();
                            const info = mapData[kode];
                            const nama = info ? info.nama : (d.properties.name || kode);
                            const status = info ? info.status : 'Data belum tersedia';

                            d3.select('#mapTooltip')
                                .style('display', 'block')
                                .style('left', (event.clientX + 14) + 'px')
                                .style('top', (event.clientY - 32) + 'px')
                                .html('<strong>' + nama + '</strong><br>' + status);

                            d3.select(this)
                                .attr('stroke', '#ffeb3b')
                                .attr('stroke-width', 1.5);
                        })
                        .on('mouseleave', function() {
                            d3.select('#mapTooltip').style('display', 'none');
                            d3.select(this)
                                .attr('stroke', '#ffffff')
                                .attr('stroke-width', 0.7);
                        })
                        .on('click', function(event, d) {
                            const kode = d.properties.Propinsi.toUpperCase();
                            const info = mapData[kode];
                            const nama = info ? info.nama : (d.properties.name || kode);

                            $('#modalPetaHeader').css('border-bottom', '3px solid ' + (info ? info.warna : '#ccc'));
                            $('#modalPetaTitle').text(nama);

                            if (info) {
                                $('#modalPetaBody').html(`
                        <table class="table table-sm table-borderless">
                            <tr>
                                <td width="40%"><strong>Status</strong></td>
                                <td>
                                    <span class="badge" style="background:${info.warna};color:#fff;">
                                        ${info.status}
                                    </span>
                                </td>
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
                            } else {
                                $('#modalPetaBody').html(
                                    '<p class="text-muted">Data belum tersedia untuk provinsi ini.</p>');
                            }

                            $('#modalPeta').modal('show');
                        });

                    $('#mapLoading').hide();
                    $('#mapWrapper').show();

                }).catch(function(err) {
                    console.error('Gagal load GeoJSON:', err);
                    $('#mapLoading').html('<p style="color:rgba(255,255,255,0.5);">Peta tidak dapat dimuat.</p>');
                });
            }
        </script>

        {{-- <script>
            const mapData = {};
            const provinsiRaw = @json($ruedpMapData);

            provinsiRaw.forEach(function(p) {
                mapData[p.kode] = p;
            });

            $(document).ready(function() {
                loadMap();
            });

            function loadMap() {
                const container = document.getElementById('indonesiaMap');
                const width = container.offsetWidth || 900;
                const height = Math.round(width * 0.45);

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

                d3.json("{{ asset('maps/indonesia-province.json') }}").then(function(geojson) {

                    svg.selectAll('path')
                        .data(geojson.features)
                        .enter()
                        .append('path')
                        .attr('d', path)
                        .attr('fill', function(d) {
                            const kode = d.properties.ISO_1; // ✅ ISO_1
                            return mapData[kode] ? mapData[kode].warna : '#e0e0e0';
                        })
                        .attr('stroke', '#ffffff')
                        .attr('stroke-width', 0.7)
                        .style('cursor', 'pointer')
                        .on('mousemove', function(event, d) {
                            const kode = d.properties.ISO_1; // ✅ ISO_1
                            const info = mapData[kode];
                            const nama = info ? info.nama : d.properties.NAME_1; // ✅ NAME_1
                            const status = info ? info.status : 'Data belum tersedia';

                            d3.select('#mapTooltip')
                                .style('display', 'block')
                                .style('left', (event.clientX + 14) + 'px')
                                .style('top', (event.clientY - 32) + 'px')
                                .html('<strong>' + nama + '</strong><br>' + status);

                            d3.select(this)
                                .attr('stroke', '#ffeb3b')
                                .attr('stroke-width', 1.5);
                        })
                        .on('mouseleave', function() {
                            d3.select('#mapTooltip').style('display', 'none');
                            d3.select(this)
                                .attr('stroke', '#ffffff')
                                .attr('stroke-width', 0.7);
                        })
                        .on('click', function(event, d) {
                            const kode = d.properties.ISO_1; // ✅ ISO_1
                            const info = mapData[kode];
                            const nama = info ? info.nama : d.properties.NAME_1; // ✅ NAME_1

                            $('#modalPetaHeader').css('border-bottom', '3px solid ' + (info ? info.warna : '#ccc'));
                            $('#modalPetaTitle').text(nama);

                            if (info) {
                                $('#modalPetaBody').html(`
                            <table class="table table-sm table-borderless">
                                <tr>
                                    <td width="40%"><strong>Status</strong></td>
                                    <td>
                                        <span class="badge" style="background:${info.warna};color:#fff;">
                                            ${info.status}
                                        </span>
                                    </td>
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
                            } else {
                                $('#modalPetaBody').html(
                                    '<p class="text-muted">Data belum tersedia untuk provinsi ini.</p>');
                            }

                            $('#modalPeta').modal('show');
                        });

                    $('#mapLoading').hide();
                    $('#mapWrapper').show();

                }).catch(function(err) {
                    console.error('Gagal load GeoJSON:', err);
                    $('#mapLoading').html('<p style="color:rgba(255,255,255,0.5);">Peta tidak dapat dimuat.</p>');
                });
            }
        </script> --}}
        <section class="section section-default border-0 section-center bgwarna-grey1"
            style="background: url('{{ asset('theme/img/ndr-bg1.webp') }}'); background-size:cover; background-position: 0 100%;margin-bottom:-100px !important;">
            <div class="container appear-animation" data-appear-animation="fadeInUpShorter"
                data-appear-animation-delay="300">
                <div class="row pt-5 pb-4 my-5">
                    <div class="col-md-6 order-2 order-md-1 text-center text-md-start">
                        <div class="owl-carousel owl-theme nav-style-1 nav-center-images-only stage-margin mb-0"
                            data-plugin-options="{'responsive': {'576': {'items': 1}, '768': {'items': 1}, '992': {'items': 2}, '1200': {'items': 2}}, 'margin': 25, 'loop': false, 'nav': true, 'dots': false, 'stagePadding': 40}">

                            {{-- $button = $button.' <a href="javascript:void(0)" class="btn btn-info btn-sm btnUpdate paddingku" onclick="showFormedit(\''.$dt->id_instansi.'\')" data-id="'.$dt->id_instansi.'">
                        <i class="icon-check feather"></i>
                    </a>'; --}}

                            @foreach ($prokumden as $prokumden)
                                <a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#modalku"
                                    onclick="showFormRead('{{ $prokumden->id_publikasi }}', '{{ Request::segment(1) }}')">
                                    <div>
                                        @php
                                            if ($prokumden->gambar_sampul == '') {
                                                $cover = 'theme/img/team/team-1.jpg';
                                            } else {
                                                $cover = 'uploads/publikasi-image/' . $prokumden->gambar_sampul;
                                            }

                                        @endphp
                                        <img class="img-fluid rounded-0 mb-2" src="{{ asset($cover) }}"
                                            alt="" />
                                        <p class="text-2 mb-0 lineheight-17 ratatengah warna-putih">
                                            {{ $prokumden->judul_publikasi }}
                                            {{-- <a href="javascript:void(0)" class="btn btn-primary btn-sm mb-2">
                                Selengkapnya
                            </a> --}}
                                        </p>

                                    </div>
                            @endforeach
                            </a>


                        </div>
                    </div>
                    <div class="col-md-6 order-1 order-md-2 text-center text-md-start mb-5 mb-md-0"
                        style="background-color: rgba(0,0,33,0.6)">
                        <br>
                        <h2 class="font-weight-normal text-6 mb-2 pb-1 warna-hijau">Produk Hukum <strong
                                class="font-weight-extra-bold warna-putih">DEN</strong></h2>
                        <p class="lead warna-putih">
                            Sesuai dengan UU Nomor 30 Tahun 2007, DEN bertugas:
                        <ul style="margin-top: -20px;" class="warna-putih">
                            <li>Merancang dan merumuskan Kebijakan Energi Nasional</li>
                            <li>Menetapkan Rencana Umum Energi Nasional</li>
                            <li>Menetapkan langkah-langkah penanggulangan kondisi dan darurat energi</li>
                            <li>Mengawasi pelaksanaan kebijakan di bidang energi yang bersifat lintas sektor</li>
                        </ul>
                        </p>
                        {{-- <p class="mb-4"></p> --}}
                    </div>
                </div>
            </div>
        </section>

        <section id="elements" class="section section-height-2 border-0 mt-5 mb-0 pt-5 bgwarna-grey2">

            <div class="container py-2">
                <div class="row mt-3 pb-4">
                    <div class="col text-center">
                        <h2 class="text-color-dark font-weight-normal text-6 mb-2 pb-1"><strong
                                class="font-weight-extra-bold warna-putih">Layanan</strong> <span
                                class="warna-hijau">Publik</span></h2>
                        {{-- <p class="lead text-4 pt-2 font-weight-normal">Porto comes with several elements options, it's easy to customize<br> and create the content of your website's pages.</p> --}}
                    </div>
                </div>

                <div class="row text-center">
                    <div class="col-lg-1"></div>
                    <div class="col-lg-10">
                        <div class="owl-carousel owl-theme dots-morphing"
                            data-plugin-options="{'responsive': {'0': {'items': 1}, '479': {'items': 1}, '768': {'items': 2}, '979': {'items': 3}, '1199': {'items': 6}}, 'loop': false, 'autoHeight': true, 'margin': 10}">
                            {{-- @foreach ($layananpublik->get() as $pub) --}}
                            @foreach ($layananpublik as $pub)
                                <a href="{{ $pub->alamat_url }}">
                                    <div>
                                        <i class="icon-{{ $pub->icon }} icons fa-5x warna-putih"></i>
                                        <br>
                                        <p class="warna-light lineheight-17 marginatas-15">{{ $pub->nama_layananpublik }}
                                        </p>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                    <div class="col-lg-1"></div>

                </div>
            </div>
        </section>

        <section class="section section-default border-0 section-center__ bg-white"
            style="background: url('{{ asset('theme/img/ndr-bg5.jpg') }}'); background-size:cover; background-position: 0 100%;margin: 0px !important;">
            <div class="container my-5 py-3" id="main">
                <div class="row mt-3 pb-4">
                    <div class="col text-center">
                        <h2 class="text-color-dark font-weight-normal text-6 mb-2 pb-1">
                            <strong class="font-weight-extra-bold">Media</strong>
                            dan
                            <strong class="font-weight-extra-bold warna-hijau">Publikasi</strong>
                        </h2>
                    </div>
                </div>

                <div class="row mb-5 pb-3">
                    <div class="col-lg-4">
                        <div class="tabs">
                            <ul class="nav nav-tabs nav-justified flex-column flex-md-row">
                                <li class="nav-item">
                                    <a class="nav-link active" href="#facebook" data-bs-toggle="tab"
                                        class="text-center">Facebook</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="#instagram" data-bs-toggle="tab"
                                        class="text-center">Instagram</a>
                                </li>
                            </ul>
                            <div class="tab-content medsos-tabcontent">
                                <div id="facebook" class="tab-pane active">
                                    <div id="fb-root"></div>
                                    <script async defer crossorigin="anonymous" src="https://connect.facebook.net/en_GB/sdk.js#xfbml=1&version=v13.0"
                                        nonce="itp0CbFk"></script>
                                    <div class="fb-page" data-href="https://www.facebook.com/dewanenerginasional"
                                        data-tabs="timeline" data-width="" data-height="" data-small-header="false"
                                        data-adapt-container-width="true" data-hide-cover="false"
                                        data-show-facepile="true">
                                        <iframe title="Facebook Page"
                                            src="https://www.facebook.com/plugins/page.php?href=https%3A%2F%2Fwww.facebook.com%2Fdewanenerginasional&tabs=timeline&small_header=false&adapt_container_width=true&hide_cover=false&show_facepile=true&appId=661144383905425&width=326&height=620"
                                            width="450px" height="575" style="width: 100%;border:none;overflow:hidden"
                                            scrolling="no" frameborder="0" allowTransparency="true"></iframe>
                                    </div>
                                </div>
                                <div id="instagram" class="tab-pane">
                                    <blockquote class="instagram-media" data-instgrm-captioned
                                        data-instgrm-permalink="https://www.instagram.com/p/CtqXymVvtb9/?utm_source=ig_embed&amp;utm_campaign=loading"
                                        data-instgrm-version="14"
                                        style=" background:#FFF; border:0; border-radius:3px; box-shadow:0 0 1px 0 rgba(0,0,0,0.5),0 1px 10px 0 rgba(0,0,0,0.15); margin: 1px; max-width:540px; min-width:326px; padding:0; width:99.375%; width:-webkit-calc(100% - 2px); width:calc(100% - 2px);">
                                        <div style="padding:16px;"> <a
                                                href="https://www.instagram.com/p/CcpcAkLv1IN/?utm_source=ig_embed&amp;utm_campaign=loading"
                                                style=" background:#FFFFFF; line-height:0; padding:0 0; text-align:center; text-decoration:none; width:100%;"
                                                target="_blank">
                                                <div style=" display: flex; flex-direction: row; align-items: center;">
                                                    <div
                                                        style="background-color: #F4F4F4; border-radius: 50%; flex-grow: 0; height: 40px; margin-right: 14px; width: 40px;">
                                                    </div>
                                                    <div
                                                        style="display: flex; flex-direction: column; flex-grow: 1; justify-content: center;">
                                                        <div
                                                            style=" background-color: #F4F4F4; border-radius: 4px; flex-grow: 0; height: 14px; margin-bottom: 6px; width: 100px;">
                                                        </div>
                                                        <div
                                                            style=" background-color: #F4F4F4; border-radius: 4px; flex-grow: 0; height: 14px; width: 60px;">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div style="padding: 19% 0;"></div>
                                                <div style="display:block; height:50px; margin:0 auto 12px; width:50px;">
                                                    <svg width="50px" height="50px" viewBox="0 0 60 60"
                                                        version="1.1" xmlns="https://www.w3.org/2000/svg"
                                                        xmlns:xlink="https://www.w3.org/1999/xlink">
                                                        <g stroke="none" stroke-width="1" fill="none"
                                                            fill-rule="evenodd">
                                                            <g transform="translate(-511.000000, -20.000000)"
                                                                fill="#000000">
                                                                <g>
                                                                    <path
                                                                        d="M556.869,30.41 C554.814,30.41 553.148,32.076 553.148,34.131 C553.148,36.186 554.814,37.852 556.869,37.852 C558.924,37.852 560.59,36.186 560.59,34.131 C560.59,32.076 558.924,30.41 556.869,30.41 M541,60.657 C535.114,60.657 530.342,55.887 530.342,50 C530.342,44.114 535.114,39.342 541,39.342 C546.887,39.342 551.658,44.114 551.658,50 C551.658,55.887 546.887,60.657 541,60.657 M541,33.886 C532.1,33.886 524.886,41.1 524.886,50 C524.886,58.899 532.1,66.113 541,66.113 C549.9,66.113 557.115,58.899 557.115,50 C557.115,41.1 549.9,33.886 541,33.886 M565.378,62.101 C565.244,65.022 564.756,66.606 564.346,67.663 C563.803,69.06 563.154,70.057 562.106,71.106 C561.058,72.155 560.06,72.803 558.662,73.347 C557.607,73.757 556.021,74.244 553.102,74.378 C549.944,74.521 548.997,74.552 541,74.552 C533.003,74.552 532.056,74.521 528.898,74.378 C525.979,74.244 524.393,73.757 523.338,73.347 C521.94,72.803 520.942,72.155 519.894,71.106 C518.846,70.057 518.197,69.06 517.654,67.663 C517.244,66.606 516.755,65.022 516.623,62.101 C516.479,58.943 516.448,57.996 516.448,50 C516.448,42.003 516.479,41.056 516.623,37.899 C516.755,34.978 517.244,33.391 517.654,32.338 C518.197,30.938 518.846,29.942 519.894,28.894 C520.942,27.846 521.94,27.196 523.338,26.654 C524.393,26.244 525.979,25.756 528.898,25.623 C532.057,25.479 533.004,25.448 541,25.448 C548.997,25.448 549.943,25.479 553.102,25.623 C556.021,25.756 557.607,26.244 558.662,26.654 C560.06,27.196 561.058,27.846 562.106,28.894 C563.154,29.942 563.803,30.938 564.346,32.338 C564.756,33.391 565.244,34.978 565.378,37.899 C565.522,41.056 565.552,42.003 565.552,50 C565.552,57.996 565.522,58.943 565.378,62.101 M570.82,37.631 C570.674,34.438 570.167,32.258 569.425,30.349 C568.659,28.377 567.633,26.702 565.965,25.035 C564.297,23.368 562.623,22.342 560.652,21.575 C558.743,20.834 556.562,20.326 553.369,20.18 C550.169,20.033 549.148,20 541,20 C532.853,20 531.831,20.033 528.631,20.18 C525.438,20.326 523.257,20.834 521.349,21.575 C519.376,22.342 517.703,23.368 516.035,25.035 C514.368,26.702 513.342,28.377 512.574,30.349 C511.834,32.258 511.326,34.438 511.181,37.631 C511.035,40.831 511,41.851 511,50 C511,58.147 511.035,59.17 511.181,62.369 C511.326,65.562 511.834,67.743 512.574,69.651 C513.342,71.625 514.368,73.296 516.035,74.965 C517.703,76.634 519.376,77.658 521.349,78.425 C523.257,79.167 525.438,79.673 528.631,79.82 C531.831,79.965 532.853,80.001 541,80.001 C549.148,80.001 550.169,79.965 553.369,79.82 C556.562,79.673 558.743,79.167 560.652,78.425 C562.623,77.658 564.297,76.634 565.965,74.965 C567.633,73.296 568.659,71.625 569.425,69.651 C570.167,67.743 570.674,65.562 570.82,62.369 C570.966,59.17 571,58.147 571,50 C571,41.851 570.966,40.831 570.82,37.631">
                                                                    </path>
                                                                </g>
                                                            </g>
                                                        </g>
                                                    </svg>
                                                </div>
                                                <div style="padding-top: 8px;">
                                                    <div
                                                        style=" color:#3897f0; font-family:Arial,sans-serif; font-size:14px; font-style:normal; font-weight:550; line-height:18px;">
                                                        View this post on Instagram</div>
                                                </div>
                                                <div style="padding: 12.5% 0;"></div>
                                                <div
                                                    style="display: flex; flex-direction: row; margin-bottom: 14px; align-items: center;">
                                                    <div>
                                                        <div
                                                            style="background-color: #F4F4F4; border-radius: 50%; height: 12.5px; width: 12.5px; transform: translateX(0px) translateY(7px);">
                                                        </div>
                                                        <div
                                                            style="background-color: #F4F4F4; height: 12.5px; transform: rotate(-45deg) translateX(3px) translateY(1px); width: 12.5px; flex-grow: 0; margin-right: 14px; margin-left: 2px;">
                                                        </div>
                                                        <div
                                                            style="background-color: #F4F4F4; border-radius: 50%; height: 12.5px; width: 12.5px; transform: translateX(9px) translateY(-18px);">
                                                        </div>
                                                    </div>
                                                    <div style="margin-left: 8px;">
                                                        <div
                                                            style=" background-color: #F4F4F4; border-radius: 50%; flex-grow: 0; height: 20px; width: 20px;">
                                                        </div>
                                                        <div
                                                            style=" width: 0; height: 0; border-top: 2px solid transparent; border-left: 6px solid #f4f4f4; border-bottom: 2px solid transparent; transform: translateX(16px) translateY(-4px) rotate(30deg)">
                                                        </div>
                                                    </div>
                                                    <div style="margin-left: auto;">
                                                        <div
                                                            style=" width: 0px; border-top: 8px solid #F4F4F4; border-right: 8px solid transparent; transform: translateY(16px);">
                                                        </div>
                                                        <div
                                                            style=" background-color: #F4F4F4; flex-grow: 0; height: 12px; width: 16px; transform: translateY(-4px);">
                                                        </div>
                                                        <div
                                                            style=" width: 0; height: 0; border-top: 8px solid #F4F4F4; border-left: 8px solid transparent; transform: translateY(-4px) translateX(8px);">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div
                                                    style="display: flex; flex-direction: column; flex-grow: 1; justify-content: center; margin-bottom: 24px;">
                                                    <div
                                                        style=" background-color: #F4F4F4; border-radius: 4px; flex-grow: 0; height: 14px; margin-bottom: 6px; width: 224px;">
                                                    </div>
                                                    <div
                                                        style=" background-color: #F4F4F4; border-radius: 4px; flex-grow: 0; height: 14px; width: 144px;">
                                                    </div>
                                                </div>
                                            </a>
                                            <p
                                                style=" color:#c9c8cd; font-family:Arial,sans-serif; font-size:14px; line-height:17px; margin-bottom:0; margin-top:8px; overflow:hidden; padding:8px 0 7px; text-align:center; text-overflow:ellipsis; white-space:nowrap;">
                                                <a href="https://www.instagram.com/p/CcpcAkLv1IN/?utm_source=ig_embed&amp;utm_campaign=loading"
                                                    style=" color:#c9c8cd; font-family:Arial,sans-serif; font-size:14px; font-style:normal; font-weight:normal; line-height:17px; text-decoration:none;"
                                                    target="_blank">A post shared by Dewan Energi Nasional
                                                    (@dewanenergi)</a>
                                            </p>
                                        </div>
                                    </blockquote>
                                    <script async src="//www.instagram.com/embed.js"></script>
                                </div>

                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4">

                        <div class="row">
                            <div class="col-lg-12">
                                <a href="{{ url('/video') }}">
                                    <h4 class="warna-hitam">Video</h4>
                                </a>
                                <div class="ratio ratio-16x9 ratio-borders__">
                                    <iframe class="kotakku" frameborder="0" allowfullscreen=""
                                        src="//www.youtube.com/embed/RhwW7U44Yr4?showinfo=0&amp;wmode=opaque"></iframe>
                                </div>
                                <div style="margin-top: 5px;">
                                    <p class="text-2 mb-0 mt-2 lineheight-17 ratatengah warna-putih">{{ $video->judul }}
                                    </p>
                                </div>
                            </div>
                        </div>
                        {{-- <hr style="border: 1px #ffffff solid;"> --}}
                        <div class="row mt-4">
                            <div class="col-md-12 order-2 order-md-1 text-center text-md-start">
                                <a href="{{ url('/infografis') }}">
                                    <h4 class="ratakiri warna-hitam">Infografis</h4>
                                </a>
                                <div class="owl-carousel owl-theme nav-inside nav-inside-edge nav-squared nav-with-transparency nav-dark"
                                    data-plugin-options="{'items': 1, 'margin': 10, 'loop': false, 'nav': true, 'dots': false}">
                                    @foreach ($infografis as $info)
                                        <div>
                                            <a class="lightbox"
                                                href="{{ asset('/uploads/infografis/' . $info->gambar_sampul) }}"
                                                data-plugin-options="{'type':'image'}">
                                                <img src="{{ asset('/uploads/infografis/' . $info->gambar_sampul) }}"
                                                    class="img-fluid rounded-0 mb-2 kotakku" style="min-height: 280px;"
                                                    alt="">
                                            </a>
                                            <p class="text-2 mb-0 mt-2 lineheight-17 ratatengah warna-putih">
                                                {{ $info->judul_infografis }}</p>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <h4 class="ratakiri warna-hitam">GPR Kominfo</h4>

                        <div class="featured-box featured-box-primary featured-box-effect-1"
                            style="background-color: #F4F4F6;">
                            <div class="box-content box-content-border-0 p-5">
                                {{-- <marquee style="min-height: 300px; max-height: 510px;" direction = "up" onmouseover="this.stop();" onmouseout="this.start();" scrollamount="6">
                            @php
                            libxml_use_internal_errors(true);
                            try {
                                $gprdata = simplexml_load_file('https://widget.kominfo.go.id/data/covid-19/gpr.xml');

                                foreach($gprdata->item as $item){
                                    echo '<div class="rows">
                                            <div class="marquee-left">
                                                <img src="https://www.kominfo.go.id/images/icon_gpr/i4.png">    
                                            </div>
                                            <div class="marquee-right">
                                                <span>'.$item->pubDate.'</span>
                                                <a href="'.$item->link.'" target="_blank" class="lineheight-17"><b>'.$item->title.'</b></a>   
                                            </div>
                                            <br>
                                        </div>';
                                }
                            } catch (\Throwable $th) {
                                echo "Tidak bisa menampilkan konten GPR Kominfo karena sedang terjadi masalah pada server GPR Kominfo";
                            }
                            @endphp
                        </marquee> --}}
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </section>
    </div>

    {{-- MODAL --}}
    <div class="modal fade" id="modalku" tabindex="-1" role="dialog" aria-labelledby="largeModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content" id="modalku-content"></div>
        </div>
    </div>
    <div class="modal fade" id="modalkudefault" tabindex="-1" role="dialog" aria-labelledby="largeModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" id="modalkudefault-content"></div>
        </div>
    </div>

    <script type="text/javascript">
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        function showFormRead(id) {
            $('#modalku').modal('show').find('#modalku-content').load("{{ url('/bacadokumen') }}?id=" + id);
        }

        function showFormRUED(id) {
            $('#modalkudefault').modal('show').find('#modalkudefault-content').load("{{ url('/modalruedp') }}?id=" + id);
        }
    </script>

@endsection
