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
                        <li class="active warna-hitam">Video</li>
                    </ul>
                </div>
                <div class="col-md-12 align-self-center p-static order-2 text-center">
                    <h1 class="font-weight-bold text-6 warna-putih">{{ $judulhalaman }}</h1>
                </div>
            </div>
        </div>
    </section>

    <div class="container py-4">

        {{-- SEARCH --}}
        <div class="row mb-4">
            <div class="col-lg-6 mx-auto">
                <form action="{{ url('/video-cari') }}" method="post">
                    @csrf
                    <div class="input-group">
                        <input class="form-control" placeholder="Cari video..."
                            name="katakunci" id="katakunci" type="text">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- VIDEO GRID --}}
        @if ($video->count() == 0)
            <div class="alert alert-danger text-center">Data yang Anda cari tidak ditemukan.</div>
        @else
            <div class="row">
                @foreach ($video as $vid)
                    <div class="col-6 col-md-6 col-lg-6 mb-4">
                        <a href="https://www.youtube.com/watch?v={{ $vid->youtube_id }}"
                            target="_blank" style="text-decoration: none; display: block;">

                            {{-- Thumbnail --}}
                            <div style="position: relative; border-radius: 10px; overflow: hidden; box-shadow: 0 2px 12px rgba(0,0,0,0.12);">
                                <img src="https://img.youtube.com/vi/{{ $vid->youtube_id }}/mqdefault.jpg"
                                    style="width:100%; aspect-ratio:16/9; object-fit:cover; display:block;"
                                    alt="{{ $vid->judul }}">
                                {{-- Play button overlay --}}
                                <div style="position:absolute; inset:0; display:flex; align-items:center; justify-content:center; background: rgba(0,0,0,0.15); transition: background 0.2s;"
                                    onmouseover="this.style.background='rgba(0,0,0,0.3)'"
                                    onmouseout="this.style.background='rgba(0,0,0,0.15)'">
                                    <div style="width:56px; height:56px; background:rgba(255,0,0,0.9); border-radius:50%; display:flex; align-items:center; justify-content:center; box-shadow: 0 4px 16px rgba(255,0,0,0.4);">
                                        <div style="border-left:20px solid #fff; border-top:11px solid transparent; border-bottom:11px solid transparent; margin-left:5px;"></div>
                                    </div>
                                </div>
                                {{-- YouTube badge --}}
                                <div style="position:absolute; bottom:10px; right:10px; background:rgba(0,0,0,0.7); color:#fff; font-size:10px; padding:3px 8px; border-radius:4px; display:flex; align-items:center; gap:4px;">
                                    <i class="fab fa-youtube" style="color:#ff0000;"></i> YouTube
                                </div>
                            </div>

                            {{-- Info --}}
                            <div style="padding: 10px 4px 0;">
                                <p style="font-size:12px; color:#888; margin:0 0 4px;">
                                    <i class="fa fa-calendar" style="color:#2d8a45;"></i>
                                    {{ App\Helpers\Gudangfungsi::tanggalindo_hari($vid->tanggal_publikasi) }}
                                </p>
                                <p style="font-size:14px; font-weight:600; color:#1a3a2a; margin:0; line-height:1.4;">
                                    {{ Str::limit($vid->judul, 80) }}
                                </p>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>

            {{-- PAGINATION --}}
            <div class="row mt-2">
                <div class="col-12">
                    <ul class="pagination float-end">
                        {{ $video->links() }}
                    </ul>
                </div>
            </div>
        @endif

    </div>
</div>
@endsection