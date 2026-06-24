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
                        <li><a href="#" class="warna-putih">Media dan Publikasi</a></li>
                        <li class="active warna-hitam">Infografis</li>
                    </ul>
                </div>
                <div class="col-md-12 align-self-center p-static order-2 text-center">
                    <h1 class="font-weight-bold text-6 warna-putih">{{ $judulhalaman }}</h1>
                </div>
            </div>
        </div>
    </section>

    <div class="container py-5">

        {{-- SEARCH --}}
        <div class="row mb-4">
            <div class="col-lg-6 mx-auto">
                <form action="{{ url('/infografis-cari') }}" method="post">
                    @csrf
                    <div class="input-group">
                        <input class="form-control" placeholder="Cari infografis..."
                            name="katakunci" id="katakunci" type="text">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        @if ($infog->count() == 0)
            <div class="alert alert-danger text-center">Data yang Anda cari tidak ditemukan.</div>
        @else

            {{-- SLIDESHOW FEATURED --}}
            <div class="row mb-5">
                <div class="col-12">
                    <div id="infografisSlideshow" style="position: relative; background: #111; border-radius: 12px; overflow: hidden;">

                        {{-- Slides --}}
                        <div id="infografisSlides" style="display: flex; transition: transform 0.4s ease;">
                            @foreach ($infog as $index => $info)
                                <div class="infografis-slide" style="min-width: 100%; position: relative;">
                                    <img src="{{ asset('storage/infografis/' . $info->gambar_sampul) }}"
                                        style="width:100%; max-height: 70vh; object-fit: contain; display: block; background: #111;"
                                        alt="{{ $info->judul_infografis }}">
                                    <div style="position: absolute; bottom: 0; left: 0; right: 0; background: linear-gradient(transparent, rgba(0,0,0,0.8)); padding: 24px 32px 20px;">
                                        <p style="color: #fff; font-size: 16px; font-weight: 600; margin: 0 0 8px;">
                                            {{ $info->judul_infografis }}
                                        </p>
                                        <div style="display: flex; align-items: center; gap: 16px; flex-wrap: wrap;">
                                            <span style="color: rgba(255,255,255,0.6); font-size: 12px;">
                                                <i class="fa fa-calendar me-1"></i>
                                                {{ App\Helpers\Gudangfungsi::tanggalindo_hari($info->tanggal_publikasi) }}
                                            </span>
                                            <span style="color: rgba(255,255,255,0.6); font-size: 12px;">
                                                <i class="fa fa-eye me-1"></i> {{ $info->hits }} views
                                            </span>
                                            @if ($info->berkas)
                                                <a href="javascript:void(0)"
                                                    onclick="showFormRead('{{ $info->id_infografis }}', 'infografis')"
                                                    data-bs-toggle="modal" data-bs-target="#modalku"
                                                    class="btn btn-primary btn-sm">
                                                    <i class="feather icon-download me-1"></i> Unduh
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        {{-- Prev / Next --}}
                        <button id="slidePrev"
                            style="position:absolute; left:12px; top:50%; transform:translateY(-50%); width:44px; height:44px; background:rgba(0,0,0,0.5); border:none; border-radius:50%; color:#fff; font-size:20px; cursor:pointer; z-index:10; display:flex; align-items:center; justify-content:center;"
                            onclick="slideMove(-1)">&#8249;</button>
                        <button id="slideNext"
                            style="position:absolute; right:12px; top:50%; transform:translateY(-50%); width:44px; height:44px; background:rgba(0,0,0,0.5); border:none; border-radius:50%; color:#fff; font-size:20px; cursor:pointer; z-index:10; display:flex; align-items:center; justify-content:center;"
                            onclick="slideMove(1)">&#8250;</button>

                        {{-- Counter --}}
                        <div id="slideCounter"
                            style="position:absolute; top:12px; right:16px; background:rgba(0,0,0,0.5); color:#fff; font-size:12px; padding:4px 10px; border-radius:20px; z-index:10;">
                            1 / {{ $infog->count() }}
                        </div>

                    </div>

                    {{-- Thumbnail strip --}}
                    <div style="display:flex; gap:8px; margin-top:12px; overflow-x:auto; padding-bottom:4px; scrollbar-width:thin;">
                        @foreach ($infog as $index => $info)
                            <div class="infografis-thumb"
                                data-index="{{ $loop->index }}"
                                onclick="goToSlide({{ $loop->index }})"
                                style="flex-shrink:0; width:80px; height:80px; border-radius:6px; overflow:hidden; cursor:pointer; border:2px solid {{ $loop->first ? '#2d8a45' : 'transparent' }}; opacity:{{ $loop->first ? '1' : '0.6' }}; transition: all 0.2s;">
                                <img src="{{ asset('storage/infografis/' . $info->gambar_sampul) }}"
                                    style="width:100%; height:100%; object-fit:cover;" alt="">
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- GRID SEMUA INFOGRAFIS --}}
            <div class="row mb-4">
                <div class="col-12">
                    <h5 class="font-weight-bold mb-3">Semua Infografis</h5>
                </div>
            </div>
            <div class="row">
                @foreach ($infog as $info)
                    <div class="col-6 col-md-4 col-lg-3 mb-4">
                        <div style="background:#fff; border-radius:10px; overflow:hidden; box-shadow:0 2px 12px rgba(0,0,0,0.08); height:100%; display:flex; flex-direction:column; transition: transform 0.2s, box-shadow 0.2s;"
                            onmouseover="this.style.transform='translateY(-4px)'; this.style.boxShadow='0 8px 24px rgba(0,0,0,0.15)'"
                            onmouseout="this.style.transform=''; this.style.boxShadow='0 2px 12px rgba(0,0,0,0.08)'">
                            <a class="lightbox"
                                href="{{ asset('storage/infografis/' . $info->gambar_sampul) }}"
                                data-plugin-options="{'type':'image'}"
                                style="display:block; flex:1;">
                                <img src="{{ asset('storage/infografis/' . $info->gambar_sampul) }}"
                                    style="width:100%; aspect-ratio:3/4; object-fit:cover; display:block;" alt="">
                            </a>
                            <div style="padding:12px;">
                                <p style="font-size:12px; font-weight:600; color:#1a3a2a; margin:0 0 6px; line-height:1.4;">
                                    {{ Str::limit($info->judul_infografis, 60) }}
                                </p>
                                <p style="font-size:11px; color:#888; margin:0 0 8px;">
                                    {{ App\Helpers\Gudangfungsi::tanggalindo_hari($info->tanggal_publikasi) }}
                                </p>
                                @if ($info->berkas)
                                    <a href="javascript:void(0)"
                                        onclick="showFormRead('{{ $info->id_infografis }}', 'infografis')"
                                        data-bs-toggle="modal" data-bs-target="#modalku"
                                        class="btn btn-primary btn-sm w-100">
                                        <i class="feather icon-download me-1"></i> Unduh
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- PAGINATION --}}
            <div class="row mt-2">
                <div class="col-12">
                    <ul class="pagination float-end">
                        {{ $infog->links() }}
                    </ul>
                </div>
            </div>

        @endif

    </div>
</div>

{{-- MODAL --}}
<div class="modal fade" id="modalku" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" id="modalku-content"></div>
    </div>
</div>

<script>
var currentSlide = 0;
var totalSlides = {{ $infog->count() }};

function goToSlide(index) {
    currentSlide = index;
    document.getElementById('infografisSlides').style.transform = 'translateX(-' + (index * 100) + '%)';
    document.getElementById('slideCounter').innerText = (index + 1) + ' / ' + totalSlides;

    // Update thumbnail active state
    document.querySelectorAll('.infografis-thumb').forEach(function(el, i) {
        el.style.borderColor = i === index ? '#2d8a45' : 'transparent';
        el.style.opacity = i === index ? '1' : '0.6';
    });

    // Scroll thumbnail ke posisi aktif
    var activeTumb = document.querySelector('.infografis-thumb[data-index="' + index + '"]');
    if (activeTumb) {
        activeTumb.scrollIntoView({ behavior: 'smooth', block: 'nearest', inline: 'center' });
    }
}

function slideMove(direction) {
    var next = currentSlide + direction;
    if (next < 0) next = totalSlides - 1;
    if (next >= totalSlides) next = 0;
    goToSlide(next);
}

// Auto slide setiap 5 detik
var autoSlide = setInterval(function() {
    slideMove(1);
}, 5000);

// Stop auto slide saat user interaksi
document.getElementById('infografisSlideshow').addEventListener('mouseenter', function() {
    clearInterval(autoSlide);
});

function showFormRead(id, cat) {
    $('#modalku').modal('show').find('#modalku-content').load("{{ url('/pdfviewer') }}?id=" + id + "&cat=" + cat);
}
</script>
@endsection