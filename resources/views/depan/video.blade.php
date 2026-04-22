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
                        <li class="active warna-hitam">Infografis</li>
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
            <div class="col-lg-12">
                <form action="{{ url('/video-cari') }}" method="post">
                    @csrf
                    <span class="cetaktebal fontsize-15">Pencarian Video</span>
                    <div class="input-group mb-3 pb-1">
                        <input class="form-control text-2" placeholder="Search..." name="katakunci" id="katakunci" type="text">
                        <button type="submit" class="btn btn-primary text-1 p-2"><i class="fas fa-search m-2"></i></button>
                    </div>
                </form>
            </div>
        </div>

        <div class="row">
            @php
                if ($video->count() == 0) {
                    echo '<div class="alert alert-danger">
                            Data yang Anda cari tidak ditemukan.
                        </div>';
                }
            @endphp

            @foreach ($video as $vid)
            <div class="col-lg-6" style="margin-bottom:20px;">
                <div class="ratio ratio-16x9 ratio-borders">
                    <iframe frameborder="0" allowfullscreen="" src="//www.youtube.com/embed/{{ $vid->youtube_id }}?showinfo=0&amp;wmode=opaque"></iframe>
                </div>
                <div style="padding-left: 5px;">
                    <span style="margin-top:10px !important;">
                        <i class="fa fa-calendar warna-hijau"></i> {{ App\Helpers\Gudangfungsi::tanggalindo_hari($vid->created_at) }}
                    </span>
                    <br>
                    <span class="cetaktebal">
                        @php echo $vid->judul; @endphp
                    </span>
                </div>
            </div>
            @endforeach
        </div>

        <div class="row">
            <div class="col-lg-12 order-lg-1" style="margin-top:40px;">
                <div class="blog-posts">
                    
                    <ul class="pagination float-end">
                        {{ $video->links() }}
                    </ul>

                </div>
            </div>
        </div>

    </div>

</div>

<script>
$(document).ready(function() {
    
});
</script>
@endsection