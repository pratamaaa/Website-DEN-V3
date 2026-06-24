@extends('layout.depan.app')

@section('content')
    <div role="main" class="main">

        {{-- ═══════════════════════════════════════════
             IMAGE SLIDER
        ═══════════════════════════════════════════ --}}
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
                        @foreach ($sliders as $sli)
                            <li data-transition="fade">
                                <img src="{{ asset('storage/imagesliders/' . $sli->gambar) }}" alt=""
                                    data-bgposition="center center" data-bgfit="cover" data-bgrepeat="no-repeat"
                                    class="rev-slidebg">
                            </li>
                        @endforeach
                    @endif
                </ul>
            </div>
        </div>

        {{-- ═══════════════════════════════════════════
             BERITA TERBARU
        ═══════════════════════════════════════════ --}}
        <section class="section section-default border-0 section-center__ bg-white"
            style="background: url('{{ asset('theme/img/ndr-bg2.webp') }}'); background-size:cover; background-position: 0 100%; margin: 0px !important;">
            <div class="container">
                <div class="row">
                    <div class="col">
                        <h2 class="text-color-dark font-weight-normal text-6 mb-2 pb-1">
                            <a href="{{ url('/berita') }}">
                                <strong class="font-weight-extra-bold">Berita</strong>
                                <span class="warna-hijau">Terbaru</span>
                            </a>
                        </h2>
                        <div class="owl-carousel owl-theme show-nav-title"
                            data-plugin-options="{'items': 4, 'margin': 10, 'loop': false, 'nav': true, 'dots': false}">
                            {{-- Fix: ganti $berita as $berita → $berita as $brt (nama variabel konflik) --}}
                            @foreach ($berita as $brt)
                                <div>
                                    <a href="{{ url('/berita/' . $brt->slug) }}">
                                        <img alt="" class="img-fluid rounded"
                                            src="{{ asset($brt->gambar ? 'storage/berita/' . $brt->gambar : 'theme/img/team/team-1.jpg') }}"
                                            style="width:100%; height:180px; object-fit:cover;">
                                        <p class="text-2 marginatas-10 marginbawah-10 lineheight-17 ratakiri">
                                            <i class="fa fa-calendar"></i>
                                            {{ App\Helpers\Gudangfungsi::tanggalindo_hari($brt->tanggal_publikasi) }}
                                        </p>
                                        <p class="text-2 lineheight-17 ratakiri font-weight-bold warna-hitam">
                                            {{ Str::limit($brt->judul, 80) }}
                                        </p>
                                    </a>
                                    <a href="{{ url('/berita/' . $brt->slug) }}" class="btn btn-primary btn-sm mb-2">
                                        Selengkapnya
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- ═══════════════════════════════════════════
             PROGRES RUED PROVINSI
        ═══════════════════════════════════════════ --}}
        <section class="section section-default border-0 section-center bgwarna-grey1">
            <div class="container">

                <div class="row mb-3">
                    <div class="col">
                        <h2 class="text-color-dark font-weight-normal text-6 mb-0 pb-1"
                            style="margin-bottom: -15px !important;">
                            <span class="warna-hijau">Progres Penyusunan</span>
                            <strong class="font-weight-extra-bold warna-putih">RUED Provinsi</strong>
                        </h2>
                        <p class="lead text-4 pt-2 font-weight-normal warna-light">
                            Status update per tanggal: {{ App\Helpers\Gudangfungsi::tanggalindo($rued->pertanggal) }}
                        </p>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col">
                        <div class="d-flex flex-wrap gap-3">
                            @foreach ($ruedpStatus as $st)
                                <div class="d-flex align-items-center gap-2 me-3">
                                    <div style="width:14px;height:14px;border-radius:3px;background:{{ $st->warna }};border:1px solid rgba(255,255,255,0.3);flex-shrink:0;"></div>
                                    <span class="warna-light" style="font-size:0.82rem;">
                                        <strong>{{ $st->provinsi_count }}</strong> — {{ $st->nama_status }}
                                    </span>
                                </div>
                            @endforeach
                            <div class="d-flex align-items-center gap-2">
                                <div style="width:14px;height:14px;border-radius:3px;background:#e0e0e0;border:1px solid rgba(255,255,255,0.3);flex-shrink:0;"></div>
                                <span class="warna-light" style="font-size:0.82rem;">Data belum tersedia</span>
                            </div>
                        </div>
                    </div>
                </div>

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

                <div class="row mb-5 pb-3">
                    @php
                        $jumRuedp = $ruedp->count();
                        $jumBox = max(1, intval(12 / $jumRuedp));
                    @endphp
                    @foreach ($ruedp as $item)
                        <div class="col-md-6 col-lg-{{ $jumBox }} mb-4 mb-lg-0 appear-animation"
                            data-appear-animation="fadeInUpShorter" data-appear-animation-delay="200">
                            <div class="card text-center rounded-1" style="border-top:3px solid #2d8a45;">
                                <div class="card-body">
                                    <h4 class="card-title mb-1 text-4 font-weight-bold">
                                        {{ $item->jumlah_provinsi }} Provinsi
                                    </h4>
                                    <p class="card-text mb-2 pb-1">{{ $item->status_penyusunan }}</p>
                                    <a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#modalkudefault"
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

        {{-- Tooltip peta --}}
        <div id="mapTooltip"
            style="position:fixed;background:rgba(0,0,0,0.8);color:#fff;padding:6px 12px;border-radius:6px;font-size:0.8rem;pointer-events:none;display:none;z-index:9999;"></div>

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
            const mapData = {};
            const provinsiRaw = @json($ruedpMapData);
            provinsiRaw.forEach(p => { mapData[p.kode] = p; });

            $(document).ready(function() { loadMap(); });

            function loadMap() {
                const container = document.getElementById('indonesiaMap');
                const width = container.offsetWidth || 900;
                const height = Math.round(width * 0.45);

                const svg = d3.select('#indonesiaMap').append('svg')
                    .attr('width', '100%')
                    .attr('viewBox', `0 0 ${width} ${height}`)
                    .attr('preserveAspectRatio', 'xMidYMid meet');

                const projection = d3.geoMercator()
                    .center([118, -2]).scale(width * 1.1).translate([width / 2, height / 2]);

                const path = d3.geoPath().projection(projection);

                d3.json("{{ asset('/maps/indonesia-provinces.json') }}").then(function(geojson) {
                    svg.selectAll('path').data(geojson.features).enter().append('path')
                        .attr('d', path)
                        .attr('fill', d => {
                            const kode = d.properties.Propinsi.toUpperCase();
                            return mapData[kode] ? mapData[kode].warna : '#e0e0e0';
                        })
                        .attr('stroke', '#ffffff').attr('stroke-width', 0.7).style('cursor', 'pointer')
                        .on('mousemove', function(event, d) {
                            const kode = d.properties.Propinsi.toUpperCase();
                            const info = mapData[kode];
                            d3.select('#mapTooltip').style('display', 'block')
                                .style('left', (event.clientX + 14) + 'px')
                                .style('top', (event.clientY - 32) + 'px')
                                .html('<strong>' + (info ? info.nama : kode) + '</strong><br>' + (info ? info.status : 'Data belum tersedia'));
                            d3.select(this).attr('stroke', '#ffeb3b').attr('stroke-width', 1.5);
                        })
                        .on('mouseleave', function() {
                            d3.select('#mapTooltip').style('display', 'none');
                            d3.select(this).attr('stroke', '#ffffff').attr('stroke-width', 0.7);
                        })
                        .on('click', function(event, d) {
                            const kode = d.properties.Propinsi.toUpperCase();
                            const info = mapData[kode];
                            const nama = info ? info.nama : (d.properties.name || kode);
                            $('#modalPetaHeader').css('border-bottom', '3px solid ' + (info ? info.warna : '#ccc'));
                            $('#modalPetaTitle').text(nama);
                            $('#modalPetaBody').html(info ? `
                                <table class="table table-sm table-borderless">
                                    <tr><td width="40%"><strong>Status</strong></td><td><span class="badge" style="background:${info.warna};color:#fff;">${info.status}</span></td></tr>
                                    <tr><td><strong>Nomor Perda</strong></td><td>${info.nomor_perda}</td></tr>
                                    <tr><td><strong>Tanggal Update</strong></td><td>${info.tanggal_update}</td></tr>
                                    <tr><td><strong>Keterangan</strong></td><td>${info.keterangan}</td></tr>
                                </table>` : '<p class="text-muted">Data belum tersedia untuk provinsi ini.</p>');
                            $('#modalPeta').modal('show');
                        });

                    $('#mapLoading').hide();
                    $('#mapWrapper').show();
                }).catch(err => {
                    console.error('Gagal load GeoJSON:', err);
                    $('#mapLoading').html('<p style="color:rgba(255,255,255,0.5);">Peta tidak dapat dimuat.</p>');
                });
            }
        </script>

        {{-- ═══════════════════════════════════════════
             PRODUK HUKUM DEN
        ═══════════════════════════════════════════ --}}
        <section class="section section-default border-0 section-center bgwarna-grey1"
            style="background: url('{{ asset('theme/img/ndr-bg1.webp') }}'); background-size:cover; background-position: 0 100%; margin-bottom:-100px !important;">
            <div class="container appear-animation" data-appear-animation="fadeInUpShorter"
                data-appear-animation-delay="300">
                <div class="row pt-5 pb-4 my-5 align-items-center">

                    {{-- KIRI: Teks tugas DEN --}}
                    <div class="col-md-5 order-1 order-md-2 mb-5 mb-md-0"
                        style="background-color: rgba(0,0,33,0.65); padding: 30px 35px; border-radius: 10px;">
                        <h2 class="font-weight-normal text-6 mb-3 warna-hijau">
                            Produk Hukum <strong class="font-weight-extra-bold warna-putih">DEN</strong>
                        </h2>
                        <p class="warna-putih mb-2" style="font-size:0.95rem;">
                            Sesuai dengan UU Nomor 30 Tahun 2007, DEN bertugas:
                        </p>
                        <ul class="warna-putih" style="padding-left: 18px; line-height: 2; font-size:0.9rem;">
                            <li>Merancang dan merumuskan Kebijakan Energi Nasional</li>
                            <li>Menetapkan Rencana Umum Energi Nasional</li>
                            <li>Menetapkan langkah-langkah penanggulangan kondisi dan darurat energi</li>
                            <li>Mengawasi pelaksanaan kebijakan di bidang energi yang bersifat lintas sektor</li>
                        </ul>
                    </div>

                    {{-- KANAN: Grid cover publikasi --}}
                    <div class="col-md-7 order-2 order-md-1">
                        <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 14px;">
                            @foreach ($prokumden as $prok)
                                <a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#modalku"
                                    onclick="showFormRead('{{ $prok->id_publikasi }}')"
                                    style="text-decoration: none; display: block;">
                                    <div style="background: rgba(255,255,255,0.08); border: 1px solid rgba(255,255,255,0.15); border-radius: 8px; overflow: hidden; transition: transform 0.2s, box-shadow 0.2s;"
                                        onmouseover="this.style.transform='translateY(-5px)'; this.style.boxShadow='0 8px 24px rgba(0,0,0,0.3)'; this.style.background='rgba(255,255,255,0.14)'"
                                        onmouseout="this.style.transform=''; this.style.boxShadow=''; this.style.background='rgba(255,255,255,0.08)'">
                                        <img src="{{ asset($prok->gambar_sampul ? 'storage/publikasi-image/' . $prok->gambar_sampul : 'theme/img/team/team-1.jpg') }}"
                                            style="width:100%; aspect-ratio: 3/4; object-fit:cover; display:block;" alt="">
                                        <div style="padding: 8px 10px;">
                                            <p style="font-size:11px; color:rgba(255,255,255,0.85); margin:0; text-align:center; line-height:1.4;">
                                                {{ Str::limit($prok->judul_publikasi, 55) }}
                                            </p>
                                        </div>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>

                </div>
            </div>
        </section>

        {{-- ═══════════════════════════════════════════
             LAYANAN PUBLIK
        ═══════════════════════════════════════════ --}}
        <section id="elements" class="section section-height-2 border-0 mt-5 mb-0 pt-5 bgwarna-grey2">
            <div class="container py-2">
                <div class="row mt-3 pb-4">
                    <div class="col text-center">
                        <h2 class="text-color-dark font-weight-normal text-6 mb-2 pb-1">
                            <strong class="font-weight-extra-bold warna-putih">Layanan</strong>
                            <span class="warna-hijau">Publik</span>
                        </h2>
                    </div>
                </div>
                <div class="row text-center">
                    <div class="col-lg-1"></div>
                    <div class="col-lg-10">
                        <div class="owl-carousel owl-theme dots-morphing"
                            data-plugin-options="{'responsive': {'0': {'items': 1}, '479': {'items': 1}, '768': {'items': 2}, '979': {'items': 3}, '1199': {'items': 6}}, 'loop': false, 'autoHeight': true, 'margin': 10}">
                            @foreach ($layananpublik as $pub)
                                <a href="{{ $pub->alamat_url }}">
                                    <div>
                                        <i class="icon-{{ $pub->icon }} icons fa-5x warna-putih"></i>
                                        <br>
                                        <p class="warna-light lineheight-17 marginatas-15">{{ $pub->nama_layananpublik }}</p>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                    <div class="col-lg-1"></div>
                </div>
            </div>
        </section>

        {{-- ═══════════════════════════════════════════
             MEDIA & PUBLIKASI
        ═══════════════════════════════════════════ --}}

<section class="section section-default border-0 section-center__ bg-white"
    style="background: url('{{ asset('theme/img/ndr-bg5.jpg') }}'); background-size:cover; background-position: 0 100%; margin: 0px !important;">
    <div class="container my-5 py-3">

        <div class="row mt-3 pb-4">
            <div class="col text-center">
                <h2 class="text-color-dark font-weight-normal text-6 mb-2 pb-1">
                    <strong class="font-weight-extra-bold">Media</strong>
                    dan
                    <strong class="font-weight-extra-bold warna-hijau">Publikasi</strong>
                </h2>
            </div>
        </div>

        {{-- BARIS ATAS: Sosial Media | Video --}}
        <div class="row mb-4">

            {{-- KOLOM KIRI: Sosial Media tabs --}}
            <div class="col-lg-6 mb-4 mb-lg-0">
                <div class="tabs tabs-dark h-100">
                    <ul class="nav nav-tabs nav-justified">
                        <li class="nav-item">
                            <a class="nav-link active text-1 font-weight-bold" href="#tabFacebook" data-bs-toggle="tab">
                                <i class="fab fa-facebook-f me-1"></i> Facebook
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-1 font-weight-bold" href="#tabInstagram" data-bs-toggle="tab">
                                <i class="fab fa-instagram me-1"></i> Instagram
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-1 font-weight-bold" href="#tabYoutube" data-bs-toggle="tab">
                                <i class="fab fa-youtube me-1"></i> YouTube
                            </a>
                        </li>
                    </ul>
                    <div class="tab-content" style="min-height: 400px;">

                        {{-- Facebook --}}
                        <div id="tabFacebook" class="tab-pane active pt-3">
                            <iframe title="Facebook Page"
                                src="https://www.facebook.com/plugins/page.php?href=https%3A%2F%2Fwww.facebook.com%2Fdewanenerginasional&tabs=timeline&small_header=false&adapt_container_width=true&hide_cover=false&show_facepile=true&appId=661144383905425&height=400"
                                width="100%" height="400"
                                style="border:none;overflow:hidden;border-radius:8px;"
                                scrolling="no" frameborder="0" allowTransparency="true">
                            </iframe>
                        </div>

                        {{-- Instagram --}}
                        <div id="tabInstagram" class="tab-pane pt-3">
                            @if ($identitas && $identitas->instagram_embed_url)
                                <blockquote class="instagram-media" data-instgrm-captioned
                                    data-instgrm-permalink="{{ $identitas->instagram_embed_url }}"
                                    data-instgrm-version="14"
                                    style="background:#FFF;border:0;border-radius:8px;box-shadow:0 0 1px 0 rgba(0,0,0,0.5),0 1px 10px 0 rgba(0,0,0,0.15);margin:0;width:100%;">
                                </blockquote>
                                <script async src="//www.instagram.com/embed.js"></script>
                            @else
                                <div class="text-center py-5">
                                    <p class="warna-light">Belum ada postingan Instagram yang ditampilkan.</p>
                                    <a href="{{ url('/dap/identitasorganisasi') }}" class="btn btn-sm btn-outline-success">
                                        Setting di Admin Panel
                                    </a>
                                </div>
                            @endif
                        </div>

                        {{-- YouTube --}}
                        <div id="tabYoutube" class="tab-pane pt-3">
                            @if ($identitas && $identitas->youtube)
                                <div class="text-center mb-3">
                                    <a href="{{ $identitas->youtube }}" target="_blank"
                                        class="btn btn-sm btn-danger">
                                        <i class="fab fa-youtube me-1"></i> Kunjungi Channel YouTube
                                    </a>
                                </div>
                            @endif
                            @if ($videos->count() > 0)
                                <div style="display: flex; flex-direction: column; gap: 12px;">
                                    @foreach ($videos as $vid)
                                        <a href="https://www.youtube.com/watch?v={{ $vid->youtube_id }}"
                                            target="_blank"
                                            style="display: flex; gap: 12px; align-items: flex-start; text-decoration: none; background: rgba(255,255,255,0.05); border-radius: 8px; padding: 10px; transition: background 0.2s;"
                                            onmouseover="this.style.background='rgba(255,255,255,0.1)'"
                                            onmouseout="this.style.background='rgba(255,255,255,0.05)'">
                                            <div style="position: relative; flex-shrink: 0; width: 120px;">
                                                <img src="https://img.youtube.com/vi/{{ $vid->youtube_id }}/mqdefault.jpg"
                                                    style="width:120px; height:68px; object-fit:cover; border-radius:6px;" alt="">
                                                <div style="position:absolute; inset:0; display:flex; align-items:center; justify-content:center;">
                                                    <div style="width:28px; height:28px; background:rgba(255,0,0,0.85); border-radius:50%; display:flex; align-items:center; justify-content:center;">
                                                        <div style="border-left:10px solid #fff; border-top:6px solid transparent; border-bottom:6px solid transparent; margin-left:3px;"></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div style="flex: 1;">
                                                <p style="font-size: 12px; font-weight: bold; color: rgba(255,255,255,0.9); margin: 0 0 4px; line-height: 1.4;">
                                                    {{ Str::limit($vid->judul, 70) }}
                                                </p>
                                                <p style="font-size: 11px; color: rgba(255,255,255,0.45); margin: 0;">
                                                    <i class="fa fa-calendar me-1"></i>
                                                    {{ App\Helpers\Gudangfungsi::tanggalindoshort($vid->tanggal_publikasi) }}
                                                </p>
                                            </div>
                                        </a>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-4">
                                    <p class="warna-light">Belum ada video yang ditampilkan.</p>
                                </div>
                            @endif
                        </div>

                    </div>
                </div>
            </div>

            {{-- KOLOM KANAN: Video featured (thumbnail besar pertama) --}}
            <div class="col-lg-6">
                <h4 class="ratakiri warna-hitam mb-3">
                    <a href="{{ url('/video') }}" class="warna-hitam">
                        Video <i class="fas fa-angle-right text-2"></i>
                    </a>
                </h4>
                @if ($videos->count() > 0)
                    @php $featuredVideo = $videos->first(); @endphp
                    <div class="ratio ratio-16x9 mb-3" style="border-radius: 10px; overflow: hidden;">
                        <iframe
                            src="https://www.youtube.com/embed/{{ $featuredVideo->youtube_id }}?showinfo=0&wmode=opaque"
                            frameborder="0" allowfullscreen></iframe>
                    </div>
                    <p class="text-2 mb-0 lineheight-17 warna-putih font-weight-bold">
                        {{ Str::limit($featuredVideo->judul, 100) }}
                    </p>
                    @if ($videos->count() > 1)
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-top: 14px;">
                            @foreach ($videos->skip(1) as $vid)
                                <a href="https://www.youtube.com/watch?v={{ $vid->youtube_id }}" target="_blank"
                                    style="text-decoration: none;">
                                    <div style="position: relative;">
                                        <img src="https://img.youtube.com/vi/{{ $vid->youtube_id }}/mqdefault.jpg"
                                            style="width:100%; height:90px; object-fit:cover; border-radius:8px;" alt="">
                                        <div style="position:absolute; inset:0; display:flex; align-items:center; justify-content:center;">
                                            <div style="width:28px; height:28px; background:rgba(255,0,0,0.85); border-radius:50%; display:flex; align-items:center; justify-content:center;">
                                                <div style="border-left:10px solid #fff; border-top:6px solid transparent; border-bottom:6px solid transparent; margin-left:3px;"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <p style="font-size:11px; color:rgba(255,255,255,0.7); margin: 6px 0 0; line-height:1.3;">
                                        {{ Str::limit($vid->judul, 55) }}
                                    </p>
                                </a>
                            @endforeach
                        </div>
                    @endif
                @else
                    <div class="text-center py-4">
                        <p class="warna-light">Belum ada video.</p>
                    </div>
                @endif
            </div>

        </div>

        {{-- BARIS BAWAH: Infografis full width --}}
        <div class="row">
            <div class="col-12">
                <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 16px;">
                    <h4 class="warna-hitam mb-0">
                        <a href="{{ url('/infografis') }}" class="warna-hitam">
                            Infografis <i class="fas fa-angle-right text-2"></i>
                        </a>
                    </h4>
                </div>
                <div class="owl-carousel owl-theme nav-inside nav-inside-edge nav-squared nav-with-transparency nav-dark"
                    data-plugin-options="{'responsive': {'0': {'items': 1}, '576': {'items': 2}, '768': {'items': 3}, '992': {'items': 4}, '1200': {'items': 5}}, 'margin': 14, 'loop': false, 'nav': true, 'dots': true}">
                    @foreach ($infografis as $info)
                        <div>
                            <a class="lightbox"
                                href="{{ asset('storage/infografis/' . $info->gambar_sampul) }}"
                                data-plugin-options="{'type':'image'}">
                                <img src="{{ asset('storage/infografis/' . $info->gambar_sampul) }}"
                                    class="img-fluid rounded mb-2"
                                    style="width:100%; aspect-ratio: 3/4; object-fit:cover; border:1px solid rgba(255,255,255,0.1);"
                                    alt="{{ $info->judul_infografis }}">
                            </a>
                            <p class="text-2 mb-0 mt-1 lineheight-17 ratatengah warna-putih">
                                {{ Str::limit($info->judul_infografis, 60) }}
                            </p>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

    </div>
</section>

    </div>

    {{-- ═══════════════════════════════════════════
         MODALS
    ═══════════════════════════════════════════ --}}
    <div class="modal fade" id="modalku" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content" id="modalku-content"></div>
        </div>
    </div>
    <div class="modal fade" id="modalkudefault" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" id="modalkudefault-content"></div>
        </div>
    </div>

    <script type="text/javascript">
        $.ajaxSetup({
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
        });

        function showFormRead(id) {
            $('#modalku').modal('show').find('#modalku-content').load("{{ url('/bacadokumen') }}?id=" + id);
        }

        function showFormRUED(id) {
            $('#modalkudefault').modal('show').find('#modalkudefault-content').load("{{ url('/modalruedp') }}?id=" + id);
        }
    </script>

@endsection