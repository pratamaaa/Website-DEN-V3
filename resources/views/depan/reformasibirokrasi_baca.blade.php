@extends('layout.depan.app')

@section('content')
<div role="main" class="main">

    <section class="page-header page-header-modern bg-color-light-scale-1 page-header-md" style="background: url('{{ asset('theme/img/ndr-banner-green.webp') }}'); background-size:cover; background-position: 0 100%;">
        <div class="container">
            <div class="row">
                <div class="col-md-12 align-self-center order-1">
                    <ul class="breadcrumb d-block text-center">
                        <li><a href="#" class="warna-putih">Home</a></li>
                        <li><a href="active warna-hitam">Reformasi Birokrasi</a></li>
                    </ul>
                </div>
                <div class="col-md-12 align-self-center p-static order-2 text-center">
                    <h1 class="font-weight-bold text-6 warna-putih">{{ $judulhalaman }}</h1>
                    {{-- <span class="sub-title text-dark">{{ $judulhalaman }}</span> --}}
                </div>
            </div>
        </div>
    </section>

    <div class="container py-4">

        <div class="row">
            <div class="col-lg-3">
                <div class="tabs tabs-vertical tabs-right tabs-navigation tabs-navigation-simple">
                    <ul class="nav nav-tabs col-sm-3">
                        @foreach ($kategorirb as $kat)
                            @php
                            $aktif = ($kat->urutan == '1' ? ' active' : '');
                            @endphp
                            <li class="nav-item">
                                <a class="nav-link{{ $aktif }}" href="#{{ $kat->slug }}" data-bs-toggle="tab">{{ $kat->nama_rbkategori }}</a>
                            </li>    
                        @endforeach
                    </ul>
                </div>
            </div>
            <div class="col-lg-9">
                @foreach ($kategorirb as $kat)
                    @php
                    $aktif = ($kat->urutan == '1' ? ' active' : '');
                    @endphp
                    <div class="tab-pane tab-pane-navigation{{ $aktif }}" id="{{ $kat->slug }}">
                        @if ($kat->slug == 'berita-rb')
                            <h4 class="warna-hijau">{{ $kat->nama_rbkategori }}</h4>
                            <div class="blog-posts single-post">
                                <article class="post post-large blog-single-post border-0 m-0 p-0">
                                    <div class="post-image ms-0">
                                        <a href="blog-post.html">
                                            <img src="{{url('/uploads/berita/'.$berita->gambar)}}" class="img-fluid img-thumbnail img-thumbnail-no-borders rounded-0" alt="" />
                                        </a>
                                    </div>
            
                                    <div class="post-date ms-0">
                                        <span class="day">{{ App\Helpers\Gudangfungsi::tanggalsaja($berita->tanggal_publikasi) }}</span>
                                        <span class="month">{{ App\Helpers\Gudangfungsi::bulantahunsaja($berita->tanggal_publikasi) }}</span>
                                    </div>
            
                                    <div class="post-content ms-0">
            
                                        <h2 class="font-weight-semi-bold fontsize-23 lineheight-30">
                                            {{ $berita->judul }}
                                        </h2>
            
                                        <div class="post-meta">
                                            <span><a href="{{ url('/reformasi-birokrasi') }}" class="tautanku2"><i class="far fa-folder"></i> Berita Reformasi Birokrasi</a> </span>
                                            <span><i class="far fa-comments"></i> Dibaca {{ $berita->hits }} kali</span>
                                        </div>
            
                                        @php
                                            echo $berita->isi_berita;
                                        @endphp
            
                                        <div class="post-block mt-5 post-share">
                                            <h4 class="fontsize-14" style="margin-bottom:0px !important;"><i class="fas fa-share"></i> Bagikan ini</h4>
                                            <a href="https://www.facebook.com/sharer.php?u={{ url('berita/'.$berita->slug) }}" class="mb-1 mt-1 me-1 btn sosmed-biru warna-putih"><i class="fa-brands fa-facebook-f"></i></a>
                                            <a href="https://wa.me/?text={{ $berita->judul }}%20{{ url('berita/'.$berita->slug) }}" class="mb-1 mt-1 me-1 btn sosmed-hijau warna-putih"><i class="fa-brands fa-whatsapp"></i></a>
                                            <a href="mailto:?subject={{ $berita->judul }}&body={{ url('berita/'.$berita->slug) }}" class="mb-1 mt-1 me-1 btn sosmed-merah warna-putih"><i class="fa-solid fa-envelope"></i></a>
                                        </div>
                                    </div>
                                </article>
                            </div>
                        @elseif ($kat->slug == 'kode-etik-pegawai')
                            <h4>{{ $kat->nama_rbkategori }}</h4>    
                            Isi dari kode etik pegawai
                        @elseif ($kat->slug == 'probis-sop-dan-laporan-rb')
                            <h4>{{ $kat->nama_rbkategori }}</h4>
                            Isi dari Probis-SOP
                        @endif
                    </div>
                @endforeach

            </div>
        </div>

    </div>

</div>

<script>
$(document).ready(function() {
    
});
</script>
@endsection