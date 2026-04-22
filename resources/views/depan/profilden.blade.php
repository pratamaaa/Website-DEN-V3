@extends('layout.depan.app')

@section('content')
<div role="main" class="main">

    <section class="page-header page-header-modern bg-color-light-scale-1 page-header-md"  style="background: url('{{ asset('theme/img/ndr-banner-green.webp') }}'); background-size:cover; background-position: 0 100%;">
        <div class="container">
            <div class="row">
                <div class="col-md-12 align-self-center order-1">
                    <ul class="breadcrumb d-block text-center">
                        <li><a href="#" class="warna-putih">Home</a></li>
                        <li class="active warna-hitam">Profil DEN</li>
                    </ul>
                </div>
                <div class="col-md-12 align-self-center p-static order-2 text-center">
                    <h1 class="font-weight-bold text-6 warna-putih">{{ $profil->judul }}</h1>
                    {{-- <span class="sub-title text-dark">Check out our Latest News!</span> --}}
                </div>
            </div>
        </div>
    </section>

    <div class="container py-4">

        <div class="row">
            <div class="col">
                <div class="blog-posts__">

                    <article class="post post-large">
                        <div class="post-content ratajustify">
                            @if ($profil->is_konten_gambar == 'yes')
                                <img src="{{asset('uploads/profilden/'.$profil->konten_gambar)}}" class="img-fluid img-thumbnail img-thumbnail-no-borders rounded-0" alt="" />
                            @else
                                @php echo $profil->konten; @endphp
                            @endif
                        </div>
                    </article>

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