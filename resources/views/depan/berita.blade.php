@extends('layout.depan.app')

@section('content')
<div role="main" class="main">

    <section class="page-header page-header-modern bg-color-light-scale-1 page-header-md" style="background: url('{{ asset('theme/img/ndr-banner-green.webp') }}'); background-size:cover; background-position: 0 100%;">
        <div class="container">
            <div class="row">
                <div class="col-md-12 align-self-center order-1">
                    <ul class="breadcrumb d-block text-center">
                        <li><a href="#" class="warna-putih">Home</a></li>
                        <li><a href="#" class="warna-putih">Media dan Publikasi</a></li>
                        <li class="active warna-hitam">Berita</li>
                    </ul>
                </div>
                <div class="col-md-12 align-self-center p-static order-2 text-center">
                    <h1 class="font-weight-bold text-6 warna-putih">Baca Berita Terbaru Kami</h1>
                    <span class="sub-title warna-putih">{{ $judulhalaman }}</span>
                </div>
            </div>
        </div>
    </section>

    <div class="container py-4">

        <div class="row">
            {{-- KONTEN INTI --}}
            <div class="col-lg-9 order-lg-1">
                <div class="blog-posts">
                    @php
                        if ($berita->count() == 0) {
                            echo '<div class="alert alert-success">
                                    Data yang Anda cari tidak ditemukan.
                                </div>';
                        }
                    @endphp
                    @foreach ($berita as $brt)
                        <article class="post post-medium">
                            <div class="row mb-3">
                                <div class="col-lg-5">
                                    <div class="post-image">
                                        <a href="{{ url('/berita', $brt->slug) }}">
                                            @if ($brt->gambar != '')
                                            <img src="{{asset('uploads/berita/'.$brt->gambar)}}" class="img-fluid img-thumbnail img-thumbnail-no-borders rounded-0" alt="" />    
                                            @else
                                            <img src="{{asset('uploads/logo/logoden-default.jpg')}}" class="img-fluid img-thumbnail img-thumbnail-no-borders rounded-0" alt="">
                                            @endif
                                            {{-- <img src="{{asset('uploads/berita/'.$brt->gambar)}}" class="img-fluid img-thumbnail img-thumbnail-no-borders rounded-0" alt="" /> --}}
                                        </a>
                                    </div>
                                </div>
                                <div class="col-lg-7">
                                    <div class="post-content">
                                        <h2 class="font-weight-semibold pt-4 pt-lg-0 text-5 line-height-4 mb-2">
                                            <a href="{{ url('/berita', $brt->slug) }}" class="tautanku1">{{ $brt->judul }}</a>
                                        </h2>
                                        <div class="post-meta lineheight-15">
                                            <i class="far fa-calendar-alt"></i> <span>{{ App\Helpers\Gudangfungsi::tanggalindo_hari($brt->tanggal_publikasi) }} </span>
                                            <span><a href="{{ url('/berita-kategori/'.$brt->kategori_slug) }}" class="tautanku2"><i class="far fa-folder"></i> {{ $brt->kategori_berita }}</a> </span>
                                        </div>
                                        @php echo substr($brt->isi_berita, 0, 290).' [...] '; @endphp
                                    </div>
                                </div>
                            </div>
                        </article>
                    @endforeach

                    <ul class="pagination float-end">
                        {{ $berita->links() }}
                    </ul>

                </div>
            </div>

            {{-- SIDEBAR KANAN --}}
            <div class="col-lg-3 order-lg-2">
                <aside class="sidebar">
                    <form action="{{ url('/berita-cari') }}" method="post">
                        @csrf
                        <div class="input-group mb-3 pb-1">
                            <input class="form-control text-1" placeholder="Search..." name="katakunci" id="katakunci" type="text">
                            <button type="submit" class="btn btn-primary text-1 p-2"><i class="fas fa-search m-2"></i></button>
                        </div>
                    </form>

                    @if ($kategori->count() != 0)
                        <h5 class="font-weight-semi-bold pt-4">Kategori</h5>
                        <ul class="nav nav-list flex-column mb-5">
                            @foreach ($kategori->get() as $kat)
                                <li class="nav-item"><a class="nav-link" href="{{ url('/berita-kategori/'.$kat->kategori_slug) }}">{{ $kat->kategori_berita }}</a></li>
                            @endforeach
                        </ul>    
                    @endif
                    
                    <h5>Infografis</h5>
                    <div class="owl-carousel owl-theme nav-inside nav-inside-edge nav-squared nav-with-transparency nav-dark" data-plugin-options="{'items': 1, 'margin': 10, 'loop': false, 'nav': true, 'dots': false}">
                        @foreach ($infografis as $info)
                        <div>
                            {{-- <a class="img-thumbnail img-thumbnail-no-borders img-thumbnail-hover-icon" href="{{ asset('uploads/infografis/'.$info->gambar_sampul) }}"> --}}
                            <a class="lightbox" href="{{ asset('uploads/infografis/'.$info->gambar_sampul) }}" data-plugin-options="{'type':'image'}">
                                <img class="img-fluid rounded-0 mb-2 kotakku" src="{{ asset('uploads/infografis/'.$info->gambar_sampul) }}" alt="" />
                            </a>
                            <p class="text-2 mb-0 lineheight-17 ratatengah cetaktebal">{{ $info->judul_infografis }}</p>
                        </div>
                        @endforeach
                    </div>

                    <div class="tabs tabs-dark mb-4 pb-2">
                        <ul class="nav nav-tabs">
                            <li class="nav-item"><a class="nav-link show active text-1 font-weight-bold text-uppercase" href="#popularPosts" data-bs-toggle="tab">Terpopuler</a></li>
                            <li class="nav-item"><a class="nav-link text-1 font-weight-bold text-uppercase" href="#recentPosts" data-bs-toggle="tab">Terkini</a></li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane active" id="popularPosts">
                                <ul class="simple-post-list">
                                    @foreach ($popularnews as $popnews)
                                    <li>
                                        <div class="post-image">
                                            <div class="img-thumbnail img-thumbnail-no-borders d-block">
                                                <a href="{{ url('/berita', $popnews->slug) }}">
                                                    @if ($popnews->gambar != '')
                                                    <img src="{{asset('uploads/berita/'.$popnews->gambar)}}" width="70" height="70" alt="">
                                                    @else
                                                    <img src="{{asset('uploads/logo/logoden-default-square.jpg')}}" width="70" height="70" alt="">
                                                    @endif
                                                </a>
                                            </div>
                                        </div>
                                        <div class="post-info fontsize-12 lineheight-17">
                                            <a href="{{ url('/berita', $popnews->slug) }}" class="tautanku1">{{ $popnews->judul }}</a>
                                            <div class="post-meta">
                                                <span>
                                                {{ App\Helpers\Gudangfungsi::tanggalindo_hari($popnews->tanggal_publikasi) }}
                                                </span>
                                            </div>
                                        </div>
                                    </li>
                                    @endforeach
                                </ul>
                            </div>
                            <div class="tab-pane" id="recentPosts">
                                <ul class="simple-post-list">
                                    @foreach ($recentnews as $recent)
                                    <li>
                                        <div class="post-image">
                                            <div class="img-thumbnail img-thumbnail-no-borders d-block">
                                                <a href="{{ url('/berita', $recent->slug) }}">
                                                    @if ($recent->gambar != '')
                                                    <img src="{{asset('uploads/berita/'.$recent->gambar)}}" width="70" height="70" alt="">
                                                    @else
                                                    <img src="{{asset('uploads/logo/logoden-default-square.jpg')}}" width="70" height="70" alt="">
                                                    @endif
                                                </a>
                                            </div>
                                        </div>
                                        <div class="post-info fontsize-12 lineheight-17">
                                            <a href="{{ url('/berita', $recent->slug) }}" class="fontsize-12">{{ $recent->judul }}</a>
                                            <div class="post-meta">
                                                <span>
                                                    {{ App\Helpers\Gudangfungsi::tanggalindo_hari($popnews->tanggal_publikasi) }}
                                                </span>
                                            </div>
                                        </div>
                                    </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </aside>
            </div>
        </div>

    </div>

</div>

<script>
$(document).ready(function() {
    
});
</script>
@endsection