@extends('layout.depan.app')

@section('content')
<div class="page-header">
    {{-- <div class="container">
        <h1 class="title">Blog Grid 2 Column - Right Sidebar</h1>
    </div> --}}
    <div class="breadcrumb-box">
        <div class="container">
            <ul class="breadcrumb">
                <li>
                    <a href="{{ url('/') }}">Home</a>
                </li>
                <li class="active">
                    <a href="{{ url('/berita') }}">Berita</a>
                </li>
            </ul>
        </div>
    </div>
</div>

<section class="page-section">
    <div class="container">
        <div class="row">
            <div class="sparatorputih-10">&nbsp;</div>

            {{-- MAIN CONTENT --}}
            <div class="col-sm-12 col-md-9 post-list">
                @foreach ($berita as $brt)
                    <div class="post-item">
                        <div class="post-image pull-left">
                            <img src="{{url('/uploads/berita/'.$brt->gambar)}}" style="border-radius:7px;" alt="Image" title="" width="320" height="230"/>
                        </div>
                        <div>
                            <h4 class="cetak-tebal" style="margin-bottom:0px !important; line-height:30px;">
                                <a href="{{ url('/berita', $brt->slug) }}">
                                    {{ $brt->judul }}
                                </a>
                            </h4>
                            <span class="warna-grey3 cetak-tebal" style="font-size: 12px !important;">
                                <i class="fa fa-calendar warna-hijau"></i> {{ App\Helpers\Gudangfungsi::tanggalindo_hari($brt->tanggal_publikasi) }}
                            </span>
                            <div class="sparatorputih-10">&nbsp;</div>
                            <div class="post-content">
                                @php echo substr($brt->isi_berita, 0, 310).'....'; @endphp 
                            </div>
                        </div>
                        
                    </div>
                    <div style="border-top: 1px dashed #cccccc;padding-bottom:5px;"></div>
                @endforeach
                
                <div class="col-md-12">
                    <nav class="navbar-right">{{ $berita->links() }}</nav>
                </div>
            </div>
            
            {{-- SIDEBAR --}}
            <div class="sidebar col-sm-12 col-md-3 marginatas-20">
                @if ($infografis->count() != 0)
                    <div class="widget">
                        <div class="widget-title">
                            <h3 class="title">Infografis</h3>
                        </div>
                        <div class="owl-carousel navigation-1" data-pagination="false" data-items="1" data-autoplay="true" data-navigation="true">
                        @foreach ($infografis->get() as $info)
                            <img src="{{url('/uploads/infografis/'.$info->gambar_sampul)}}" style="border-radius:7px;" width="270" height="270" alt="" />    
                        @endforeach
                        </div>
                    </div>    
                @endif
                
                @if ($kategori->count() != 0)
                    <div class="widget">
                        <div class="widget-title">
                            <h3 class="title">Kategori</h3>
                        </div>
                        <ul class="tags">
                            @foreach ($kategori->get() as $kat)
                                <li><a href="#">{{ $kat->kategori_berita }}</a></li>
                            @endforeach    
                        </ul>
                    </div>
                @endif
            </div>
        </div>
        
    </div>
</section>

<script>
$(document).ready(function() {
    
});
</script>
@endsection