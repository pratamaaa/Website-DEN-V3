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
                        <li><a href="active warna-hitam">Infografis</a></li>
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
                <form action="{{ url('/infografis-cari') }}" method="post">
                    @csrf
                    <span class="cetaktebal fontsize-15">Pencarian Infografis</span>
                    <div class="input-group mb-3 pb-1">
                        <input class="form-control text-2" placeholder="Search..." name="katakunci" id="katakunci" type="text">
                        <button type="submit" class="btn btn-primary text-1 p-2"><i class="fas fa-search m-2"></i></button>
                    </div>
                </form>
            </div>
        </div>

        <div class="row">
            @php
                if ($infog->count() == 0) {
                    echo '<div class="alert alert-danger">
                            Data yang Anda cari tidak ditemukan.
                        </div>';
                }
            @endphp
            
            @foreach ($infog as $info)
            <div class="col-lg-4" style="margin-bottom: 25px;">
                
                <span class="thumb-info thumb-info-no-borders thumb-info-no-borders-rounded thumb-info-lighten thumb-info-bottom-info thumb-info-bottom-info-dark thumb-info-bottom-info-show-more thumb-info-no-zoom">
                    <a href="#">
                    <span class="thumb-info-wrapper">
                        <a class="lightbox" href="{{ asset('/uploads/infografis/'.$info->gambar_sampul) }}" data-plugin-options="{'type':'image'}">
                            <img src="{{ asset('/uploads/infografis/'.$info->gambar_sampul) }}" class="img-fluid" style="min-height: 280px;" alt="">
                        </a>
                        <span class="thumb-info-title opacity-8">
                            <span class="thumb-info-inner line-height-1" style="font-size:13px !important;">{{ $info->judul_infografis }}</span>
                            <span class="thumb-info-show-more-content opacity-7" style="font-size:11px !important;font-weight:normal !important;">
                                {{ App\Helpers\Gudangfungsi::tanggalindo_hari($info->tanggal_publikasi) }} - hits {{ $info->hits }}
                            </span>
                            <span class="thumb-info-show-more-content opacity-10" style="font-size:11px !important;">
                                @if ($info->berkas != '')
                                    <a href="javascript:void(0)" class="btn btn-primary btn-xs mb-2" data-bs-toggle="modal" data-bs-target="#modalku" onclick="showFormRead('{{ $info->id_infografis }}', 'infografis')">
                                        Unduh
                                    </a>
                                @else
                                    <a href="#" data-plugin-options="{'type':'image'}" class="btn btn-quaternary btn-xs mb-2">Unduh</a>
                                @endif
                            </span>
                            
                        </span>
                    </span>
                    </a>
                </span>
            </div>
            @endforeach

            {{-- PAGINATION --}}
            <div class="col-lg-12 order-lg-1" style="margin-top:20px;">
                <div class="blog-posts">
                    
                    <ul class="pagination float-end">
                        {{ $infog->links() }}
                    </ul>

                </div>
            </div>
        </div>
    </div>
</div>

{{-- MODAL --}}
<div class="modal fade" id="modalku" tabindex="-1" role="dialog" aria-labelledby="largeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" id="modalku-content"></div>
    </div>
</div>
<div class="modal fade" id="modalkudefault" tabindex="-1" role="dialog" aria-labelledby="largeModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content" id="modalkudefault-content"></div>
    </div>
</div>

<script>
function showFormRead(id, cat){
    $('#modalku').modal('show').find('#modalku-content').load("{{ url('/pdfviewer') }}?id="+id+"&cat="+cat);
}

$(document).ready(function() {
    
});
</script>

<script>
$(document).ready(function() {
    
});
</script>
@endsection