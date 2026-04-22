@extends('layout.depan.app')

@section('content')
<div role="main" class="main">

    <section class="page-header page-header-modern bg-color-light-scale-1 page-header-md" style="background: url('{{ asset('theme/img/ndr-banner-green.webp') }}'); background-size:cover; background-position: 0 100%;">
        <div class="container">
            <div class="row">
                <div class="col-md-12 align-self-center order-1">
                    <ul class="breadcrumb d-block text-center">
                        <li><a href="#" class="warna-putih">Home</a></li>
                        <li class="active warna-hitam">Reformasi Birokrasi</li>
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
                                            {{-- <span><a href="{{ url('/berita-kategori/'.$brt->kategori_slug) }}" class="tautanku2"><i class="far fa-folder"></i> {{ $brt->kategori_berita }}</a> </span> --}}
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

                    @if ($kategorirb->count() != 0)
                        <h5 class="font-weight-semi-bold pt-4">Menu</h5>
                        <ul class="nav nav-list flex-column mb-5">
                            @foreach ($kategorirb->get() as $kat)
                                <li class="nav-item"><a class="nav-link" href="{{ url('/reformasi-birokrasi/'.$kat->slug) }}">{{ $kat->nama_rbkategori }}</a></li>
                            @endforeach
                        </ul>    
                    @endif
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