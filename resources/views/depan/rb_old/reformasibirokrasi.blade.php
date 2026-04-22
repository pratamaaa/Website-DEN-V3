@extends('layout.depan.app')

@section('content')
<div role="main" class="main">

    <section class="page-header page-header-modern bg-color-light-scale-1 page-header-md">
        <div class="container">
            <div class="row">
                <div class="col-md-12 align-self-center p-static order-2 text-center">
                    <h1 class="text-dark font-weight-bold text-6">{{ $judulhalaman }}</h1>
                    {{-- <span class="sub-title text-dark">{{ $judulhalaman }}</span> --}}
                </div>
                <div class="col-md-12 align-self-center order-1">
                    <ul class="breadcrumb d-block text-center">
                        <li><a href="#">Home</a></li>
                        <li><a href="active">Reformasi Birokrasi</a></li>
                    </ul>
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
                            <h4 class="warna-hijau">{{ $kat->nama_rbkategori }}</h4><hr>
                            <div class="blog-posts_">
                                @php
                                    if ($beritarb->count() == 0) {
                                        echo '<div class="alert alert-success">
                                                Data yang Anda cari tidak ditemukan.
                                            </div>';
                                    }
                                @endphp
                                @foreach ($beritarb as $brt)
                                    <article class="post post-medium">
                                        <div class="row mb-3">
                                            <div class="col-lg-5">
                                                <div class="post-image">
                                                    <a href="{{ url('/reformasi-birokrasi', $brt->slug) }}">
                                                        <img src="{{url('/uploads/berita/'.$brt->gambar)}}" class="img-fluid img-thumbnail img-thumbnail-no-borders rounded-0" alt="" />
                                                    </a>
                                                </div>
                                            </div>
                                            <div class="col-lg-7">
                                                <div class="post-content">
                                                    <h4 class="font-weight-semibold pt-4 pt-lg-0 text-5 line-height-4 mb-2 fontsize-16">
                                                        <a href="{{ url('/reformasi-birokrasi', $brt->slug) }}" class="tautanku1">{{ $brt->judul }}</a>
                                                    </h4>
                                                    <div class="post-meta lineheight-15">
                                                        <i class="far fa-calendar-alt"></i> <span>{{ App\Helpers\Gudangfungsi::tanggalindo_hari($brt->tanggal_publikasi) }} </span>
                                                        <span><a href="{{ url('/reformasi-birokrasi') }}" class="tautanku2"><i class="far fa-folder"></i> Berita Reformasi Birokrasi</a> </span>
                                                    </div>
                                                    @php echo substr($brt->isi_berita, 0, 290).' [...] '; @endphp
                                                </div>
                                            </div>
                                        </div>
                                    </article>
                                    <hr>
                                @endforeach
            
                                <ul class="pagination float-end">
                                    {{ $beritarb->links() }}
                                </ul>
            
                            </div>
                        @elseif ($kat->slug == 'kode-etik-pegawai')
                            <h4>{{ $kat->nama_rbkategori }}</h4><hr>
                            @php
                                $kodeEtik = App\Helpers\Gudangfungsi::reformasibirokrasi($kat->id_rbkategori);

                                if ($kodeEtik->count() != 0){
                                    foreach ($kodeEtik->get() as $kdetik) {
                                        if ($kdetik->gambar_sampul != ''){
                                            $sampul = url('/uploads/rb/'.$kdetik->gambar_sampul);
                                        }else{
                                            $sampul = url('/uploads/default-image/sampul-rb.jpg');
                                        }

                                        echo '<article class="post post-medium">
                                            <div class="row mb-3">
                                                <div class="col-lg-2">
                                                    <div class="post-image">
                                                        <a href="">
                                                            <img src="'.$sampul.'" style="width:100px;" class="img-fluid img-thumbnail img-thumbnail-no-borders rounded-0" alt="" />
                                                        </a>
                                                    </div>
                                                </div>
                                                <div class="col-lg-10">
                                                    <div class="post-content">
                                                        <h4 class="font-weight-semibold pt-4 pt-lg-0 text-5 line-height-4 mb-2 fontsize-16">
                                                            <a href="" class="tautanku1">'.$kdetik->judul.'</a>
                                                        </h4>
                                                        <div class="post-meta lineheight-15">
                                                            <i class="far fa-calendar-alt"></i> <span></span>
                                                            <span><a href="" class="tautanku2"><i class="far fa-folder"></i> Berita Reformasi Birokrasi</a> </span>
                                                        </div>
                                                        '.substr($kdetik->deskripsi, 0, 290).'<br>
                                                        <a href="'.url('/download').'?pca='.$kat->slug.'&num='.$kdetik->id_rb.'" class="btn btn-sm btn-3d btn-primary mb-2 mt-2">
                                                            <i class="icon-cloud-download icons"></i> <span style="font-size:12px;">Unduh</span>    
                                                        </a>
                                                        <a href="#" class="btn btn-sm btn-3d btn-primary mb-2 mt-2">
                                                            <i class="icon-menu icons"></i> <span style="font-size:12px;">Baca</span>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </article>
                                        <hr>';
                                    }
                                }else{
                                    echo '<article class="post post-medium">
                                            <div class="row mb-3">
                                                <div class="col-lg-12">
                                                    <div class="alert alert-danger">
                                                        Tidak ada data.
                                                    </div>
                                                </div>
                                            </div>
                                           </article>';
                                }
                            @endphp  
                        @elseif ($kat->slug == 'probis-sop-dan-laporan-rb')
                            <h4>{{ $kat->nama_rbkategori }}</h4><hr>
                            @php
                                $kodeEtik = App\Helpers\Gudangfungsi::reformasibirokrasi($kat->id_rbkategori);

                                if ($kodeEtik->count() != 0){
                                    foreach ($kodeEtik->get() as $kdetik) {
                                        if ($kdetik->gambar_sampul != ''){
                                            $sampul = url('/uploads/rb/'.$kdetik->gambar_sampul);
                                        }else{
                                            $sampul = url('/uploads/default-image/sampul-rb.jpg');
                                        }

                                        echo '<article class="post post-medium">
                                            <div class="row mb-3">
                                                <div class="col-lg-2">
                                                    <div class="post-image">
                                                        <a href="">
                                                            <img src="'.$sampul.'" style="width:100px;" class="img-fluid img-thumbnail img-thumbnail-no-borders rounded-0" alt="" />
                                                        </a>
                                                    </div>
                                                </div>
                                                <div class="col-lg-10">
                                                    <div class="post-content">
                                                        <h4 class="font-weight-semibold pt-4 pt-lg-0 text-5 line-height-4 mb-2 fontsize-16">
                                                            <a href="" class="tautanku1">'.$kdetik->judul.'</a>
                                                        </h4>
                                                        <div class="post-meta lineheight-15">
                                                            <i class="far fa-calendar-alt"></i> <span></span>
                                                            <span><a href="" class="tautanku2"><i class="far fa-folder"></i> Berita Reformasi Birokrasi</a> </span>
                                                        </div>
                                                        '.substr($kdetik->deskripsi, 0, 290).'<br>
                                                        <a href="'.url('/download').'?pca='.$kat->slug.'&num='.$kdetik->id_rb.'" class="btn btn-sm btn-3d btn-primary mb-2 mt-2">
                                                            <i class="icon-cloud-download icons"></i> <span style="font-size:12px;">Unduh</span>    
                                                        </a>
                                                        <a href="#" class="btn btn-sm btn-3d btn-primary mb-2 mt-2">
                                                            <i class="icon-menu icons"></i> <span style="font-size:12px;">Baca</span>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </article>
                                        <hr>';
                                    }
                                }else{
                                    echo '<article class="post post-medium">
                                            <div class="row mb-3">
                                                <div class="col-lg-12">
                                                    <div class="alert alert-danger">
                                                        Tidak ada data.
                                                    </div>
                                                </div>
                                            </div>
                                           </article>';
                                }
                            @endphp 
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