@extends('layout.depan.app')

@section('content')
<div role="main" class="main">

    <section class="page-header page-header-modern bg-color-light-scale-1 page-header-md" style="background: url('{{ asset('theme/img/ndr-banner-green.webp') }}'); background-size:cover; background-position: 0 100%;">
        <div class="container">
            <div class="row">
                <div class="col-md-12 align-self-center order-1">
                    <ul class="breadcrumb d-block text-center">
                        <li><a href="#" class="warna-putih">Home</a></li>
                        <li class="active warna-hitam">Media dan Publikasi</li>
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
                        if ($data->count() == 0) {
                            echo '<div class="alert alert-success">
                                    Data yang Anda cari tidak ditemukan.
                                </div>';
                        }
                    @endphp

                    @foreach ($data as $brt)
                        @php
                            $gambarsampul = ($brt->gambar_sampul == '' ? asset('/uploads/default-image/sampul-rb.jpg') : asset('/uploads/publikasi-image/'.$brt->gambar_sampul));
                            // $gambarsampul = asset('/uploads/default-image/sampul-rb.jpg');
                        @endphp
                        <article class="post post-medium">
                            <div class="row mb-3">
                                <div class="col-lg-2">
                                    <div class="post-image">
                                        <a href="#">
                                            <img src="{{ $gambarsampul }}" class="img-fluid img-thumbnail img-thumbnail-no-borders rounded-0" alt="" style="width: 130px;" />
                                        </a>
                                    </div>
                                </div>
                                <div class="col-lg-10">
                                    <div class="post-content">
                                        <h2 class="font-weight-semibold pt-4 pt-lg-0 text-5 line-height-4 mb-2" style="font-size: 18px !important;">
                                            <a href="#" class="tautanku1">{{ $brt->judul_publikasi }}</a>
                                        </h2>
                                        <div class="post-meta lineheight-15">
                                            <i class="far fa-calendar-alt"></i> <span>{{ App\Helpers\Gudangfungsi::tanggalindo_hari($brt->tanggal_publikasi) }} </span>
                                        </div>
                                        <div style="margin-top: 15px;">
                                            @php echo $brt->deskripsi; @endphp
                                        </div>
                                        <div style="margin-top:10px;">
                                            {{-- <a href="#" class="btn btn-sm btn-3d btn-primary mb-2">
                                                <i class="icon-cloud-download icons"></i> Unduh
                                            </a> --}}
                                            @if ($brt->berkas != '')
                                            <a href="javascript:void(0)" class="btn btn-sm btn-3d btn-primary mb-2" data-bs-toggle="modal" data-bs-target="#modalku" onclick="showFormRead('{{ $brt->id_publikasi }}', 'publikasi')">
                                                <i class="icon-list icons"></i> Baca Dokumen
                                            </a>
                                            @else
                                            <a href="#" class="btn btn-sm btn-3d btn-quaternary mb-2">
                                                <i class="icon-list icons"></i> Baca Dokumen
                                            </a>
                                            @endif

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </article>
                    @endforeach

                    <ul class="pagination float-end">
                        {{ $data->links() }}
                    </ul>

                </div>
            </div>

            {{-- SIDEBAR KANAN --}}
            <div class="col-lg-3 order-lg-2">
                <aside class="sidebar">
                    <form action="{{ url('/publikasi-cari') }}" method="post">
                        @csrf
                        <div class="input-group mb-3 pb-1">
                            <input type="hidden" name="slug" id="slug" value="{{ $slug }}">
                            <input class="form-control text-1" placeholder="Search..." name="katakunci" id="katakunci" type="text">
                            <button type="submit" class="btn btn-primary text-1 p-2"><i class="fas fa-search m-2"></i></button>
                        </div>
                    </form>
                    
                    @if ($katpublikasi->count() != 0)
                        <h5 class="font-weight-semi-bold pt-4">Menu</h5>

                        <ul class="nav nav-list flex-column mb-5">
                            @foreach ($katpublikasi->get() as $kat)
                                @if ($kat->id_parent == 0 && $kat->haschild == 'no')
                                    @php
                                        $alamatUrlAnak2 = ($kat->is_page == 'yes' ? url($kat->url) : $kat->url);
                                    @endphp
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{ $alamatUrlAnak2 }}">{{ $kat->nama_kategori }}</a>
                                    </li>
                                @elseif ($kat->id_parent == 0 && $kat->haschild == 'yes')
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{ $kat->url }}">{{ $kat->nama_kategori }}</a>

                                        <ul>
                                            @php
                                                $katChild = \App\Models\Kategoripublikasi::where('id_parent', $kat->id_publikasi_kategori)
                                                    ->orderBy('urutan', 'asc')
                                                    ->get();

                                                foreach ($katChild as $katChild) {
                                                    $alamatUrlAnak = ($katChild->is_page == 'yes' ? url($katChild->url) : $katChild->url);
                                                    echo '<li class="nav-item">
                                                            <a class="nav-link" href="'.$alamatUrlAnak.'">'.$katChild->nama_kategori.'</a>
                                                          </li>';
                                                }
                                            @endphp
                                        </ul>
                                    </li>
                                @endif
                            @endforeach
                        </ul>    
                    @endif
                </aside>
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
    // alert('ID: '+id+', '+cat)
}

$(document).ready(function() {
    
});
</script>
@endsection