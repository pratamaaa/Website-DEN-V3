@extends('layout.depan.app')

@section('content')
<div role="main" class="main">

    <section class="page-header page-header-modern bg-color-light-scale-1 page-header-md" style="background: url('{{ asset('theme/img/ndr-banner-green.webp') }}'); background-size:cover; background-position: 0 100%;">
        <div class="container">
            <div class="row">
                <div class="col-md-12 align-self-center order-1">
                    <ul class="breadcrumb d-block text-center">
                        <li><a href="#" class="warna-putih">Home</a></li>
                        <li class="active warna-hitam">Profil DEN</li>
                    </ul>
                </div>
                <div class="col-md-12 align-self-center p-static order-2 text-center">
                    <h1 class="warna-putih font-weight-bold text-6">{{ $profil->judul }}</h1>
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

                            @php
                                $ketua = \App\Models\Organisasiden::where('kategori_jabatan', 'KETUA')->where('is_active', 'yes');
                                $waketua = \App\Models\Organisasiden::where('kategori_jabatan', 'WAKETUA')->where('is_active', 'yes');
                                $kharian = \App\Models\Organisasiden::where('kategori_jabatan', 'KHARIAN')->where('is_active', 'yes');
                                $ap = \App\Models\Organisasiden::where('kategori_jabatan', 'AP')->where('is_active', 'yes');
                                $apk = \App\Models\Organisasiden::where('kategori_jabatan', 'APK')->where('is_active', 'yes');
                            @endphp

                            <div class="row mt-lg-12">
                                <div class="col-lg-5"></div>
                                <div class="col-lg-2">
									<span class="thumb-info border_all thumb-info-show-button-hover">
										<span class="thumb-info-wrapper">
                                            @if ($ketua->count() == 0)
                                                <img src="{{ asset('theme/img/blog/square/blog-11.jpg') }}" class="img-fluid" alt="">
                                            @else
                                                <img src="{{ asset('uploads/profilden/'.$ketua->first()->foto) }}" class="img-fluid" alt="">
                                            @endif
											
										</span>
									</span>
								</div>
                                <div class="col-lg-5"></div>

                                <div class="col-lg-4"></div>
                                <div class="col-lg-4">
                                    @if ($ketua->count() == 0)
                                        <p class="text-2 mb-0 lineheight-15 fontsize-15 ratatengah cetaktebal mt-2">Nama: Not Set</p>
                                        <p class="text-2 mb-0 lineheight-15 fontsize-13 ratatengah mt-2">Jabatan: Not set</p>
                                    @else
                                        <p class="text-2 mb-0 lineheight-15 fontsize-15 ratatengah cetaktebal mt-2">{{ $ketua->first()->namalengkap }}</p>
                                        <p class="text-2 mb-0 lineheight-15 fontsize-13 ratatengah mt-2">{{ $ketua->first()->jabatan }}</p>
                                        <p class="text-2 mb-0 lineheight-15 fontsize-13 ratatengah mt-3">
                                            <a href="javascript:void(0)" class="btn btn-primary btn-sm mb-2" data-bs-toggle="modal" data-bs-target="#modalku" onclick="showDetail('{{ $ketua->first()->id_organisasiden }}')">
                                                Selengkapnya
                                            </a>
                                        </p>
                                    @endif
                                </div>
                                <div class="col-lg-4"></div>
                            </div>

                            <div class="row mt-lg-12 mt-4">
                                <div class="col-lg-5"></div>
                                <div class="col-lg-2">
									<span class="thumb-info border_all thumb-info-show-button-hover">
										<span class="thumb-info-wrapper">
                                            @if ($waketua->count() == 0)
                                                <img src="{{ asset('theme/img/blog/square/blog-11.jpg') }}" class="img-fluid" alt="">
                                            @else
                                                <img src="{{ asset('uploads/profilden/'.$waketua->first()->foto) }}" class="img-fluid" alt="">    
                                            @endif
											
										</span>
									</span>
								</div>
                                <div class="col-lg-5"></div>

                                <div class="col-lg-4"></div>
                                <div class="col-lg-4">
                                    @if ($waketua->count() == 0)
                                        <p class="text-2 mb-0 lineheight-15 fontsize-15 ratatengah cetaktebal mt-2">Nama: Not Set</p>
                                        <p class="text-2 mb-0 lineheight-15 fontsize-13 ratatengah mt-2">Jabatan: Not set</p>
                                    @else
                                        <p class="text-2 mb-0 lineheight-15 fontsize-15 ratatengah cetaktebal mt-2">{{ $waketua->first()->namalengkap }}</p>
                                        <p class="text-2 mb-0 lineheight-15 fontsize-13 ratatengah mt-2">{{ $waketua->first()->jabatan }}</p>
                                        <p class="text-2 mb-0 lineheight-15 fontsize-13 ratatengah mt-3">
                                            <a href="javascript:void(0)" class="btn btn-primary btn-sm mb-2" data-bs-toggle="modal" data-bs-target="#modalku" onclick="showDetail('{{ $waketua->first()->id_organisasiden }}')">
                                                Selengkapnya
                                            </a>
                                        </p>
                                    @endif
                                </div>
                                <div class="col-lg-4"></div>
                            </div>

                            <div class="row mt-lg-12 mt-4">
                                <div class="col-lg-5"></div>
                                <div class="col-lg-2">
									<span class="thumb-info border_all thumb-info-show-button-hover">
										<span class="thumb-info-wrapper">
                                            @if ($kharian->count() == 0)
                                                <img src="{{ asset('theme/img/blog/square/blog-11.jpg') }}" class="img-fluid" alt="">
                                            @else
                                                <img src="{{ asset('uploads/profilden/'.$kharian->first()->foto) }}" class="img-fluid" alt="">
                                            @endif
										</span>
									</span>
								</div>
                                <div class="col-lg-5"></div>

                                <div class="col-lg-4"></div>
                                <div class="col-lg-4">
                                    @if ($kharian->count() == 0)
                                        <p class="text-2 mb-0 lineheight-15 fontsize-15 ratatengah cetaktebal mt-2">Nama: Not Set</p>
                                        <p class="text-2 mb-0 lineheight-15 fontsize-13 ratatengah mt-2">Jabatan: Not set</p>
                                    @else
                                        <p class="text-2 mb-0 lineheight-15 fontsize-15 ratatengah cetaktebal mt-2">{{ $kharian->first()->namalengkap }}</p>
                                        <p class="text-2 mb-0 lineheight-15 fontsize-13 ratatengah mt-2">{{ $kharian->first()->jabatan }}</p>
                                        <p class="text-2 mb-0 lineheight-15 fontsize-13 ratatengah mt-3">
                                            <a href="javascript:void(0)" class="btn btn-primary btn-sm mb-2" data-bs-toggle="modal" data-bs-target="#modalku" onclick="showDetail('{{ $kharian->first()->id_organisasiden }}')">
                                                Selengkapnya
                                            </a>
                                        </p>
                                    @endif
                                </div>
                                <div class="col-lg-4"></div>
                            </div>
                            
                            {{-- <div class="mt-4 mb-4"></div> --}}
                            <div class="divider divider-style-4 divider-solid divider-xs divider-icon-sm taller">
								<i class="fas fa-chevron-down"></i>
							</div>

                            <div class="row mt-lg-12 mt-4">
                                <p class="text-3 mb-0 lineheight-15 fontsize-17 ratakiri mt-2">
                                    <span class="badge badge-primary badge-sm"><i class="icons icon-arrow-right font-weight-extra-bold"></i></span>
                                    <span class="badge badge-primary badge-md warnabg-grey warna-hitam" style="margin-left:-7px !important;">
                                        Anggota Unsur <strong class="font-weight-extra-bold fontsize-18">Pemerintah</strong>
                                    </span>
                                </p>

                                @if ($ap->count() == 0)
                                    <div class="col-lg-2 mt-4">
                                        <span class="thumb-info border-all thumb-info-show-button-hover">
                                            <span class="thumb-info-wrapper">
                                                <img src="{{ asset('theme/img/blog/square/blog-11.jpg') }}" class="img-fluid" alt="">
                                            </span>
                                        </span>
                                        <p class="text-2 mb-0 lineheight-15 fontsize-15 ratatengah cetaktebal mt-2">Nama: Not Set</p>
                                        <p class="text-2 mb-0 lineheight-15 fontsize-13 ratatengah mt-2">Jabatan: Not set</p>
                                    </div>    
                                @else
                                    @foreach ($ap->get() as $ap)
                                        <div class="col-lg-3 mt-4">
                                            <span class="thumb-info border-all thumb-info-show-button-hover warnabg-grey">
                                                <span class="thumb-info-wrapper">
                                                    <img src="{{ asset('uploads/profilden/'.$ap->foto) }}" class="img-fluid" alt="">
                                                </span>
                                            </span>
                                            <p class="text-2 mb-0 lineheight-15 fontsize-15 ratatengah cetaktebal mt-2">{{ $ap->namalengkap }}</p>
                                            <p class="text-2 mb-0 lineheight-15 fontsize-13 ratatengah mt-2">{{ $ap->jabatan }}</p>
                                            <p class="text-2 mb-0 lineheight-15 fontsize-13 ratatengah mt-3">
                                                <a href="javascript:void(0)" class="btn btn-primary btn-sm mb-2" data-bs-toggle="modal" data-bs-target="#modalku" onclick="showDetail('{{ $ap->id_organisasiden }}')">
                                                    Selengkapnya
                                                </a>
                                            </p>
                                        </div>
                                    @endforeach
                                @endif
                            </div>

                            {{-- <div class="mt-4 mb-4"></div> --}}
                            <div class="divider divider-style-4 divider-solid divider-xs divider-icon-sm taller">
								<i class="fas fa-chevron-down"></i>
							</div>
                            
                            <div class="row mt-lg-12 mt-5">
                                <p class="text-3 mb-0 lineheight-15 fontsize-17 ratakiri mt-2 warna-hitam">
                                    <span class="badge badge-primary badge-sm"><i class="icons icon-arrow-right font-weight-extra-bold"></i></span>
                                    <span class="badge badge-primary badge-md warnabg-grey warna-hitam" style="margin-left:-7px !important;">
                                        Anggota Unsur <strong class="font-weight-extra-bold fontsize-18">Pemangku Kepentingan</strong>
                                    </span>
                                </p>

                                @if ($apk->count() == 0)
                                    <div class="col-lg-2 mt-4">
                                        <span class="thumb-info border-all thumb-info-show-button-hover">
                                            <span class="thumb-info-wrapper">
                                                <img src="{{ asset('theme/img/blog/square/blog-11.jpg') }}" class="img-fluid" alt="">
                                            </span>
                                        </span>
                                        <p class="text-2 mb-0 lineheight-15 fontsize-15 ratatengah cetaktebal mt-2">Nama: Not Set</p>
                                        <p class="text-2 mb-0 lineheight-15 fontsize-13 ratatengah mt-2">Jabatan: Not set</p>
                                    </div>    
                                @else
                                    @foreach ($apk->get() as $apk)
                                        <div class="col-lg-3 mt-4 ratatengah">
                                            <span class="thumb-info border-all thumb-info-show-button-hover warnabg-grey">
                                                <span class="thumb-info-wrapper">
                                                    <img src="{{ asset('uploads/profilden/'.$apk->foto) }}" class="img-fluid" alt="">
                                                </span>
                                            </span>
                                            <p class="text-2 mb-0 lineheight-15 fontsize-15 ratatengah cetaktebal mt-2">{{ $apk->namalengkap }}</p>
                                            <p class="text-2 mb-0 lineheight-15 fontsize-13 ratatengah mt-2">{{ $apk->jabatan }}</p>
                                            <p class="text-2 mb-0 lineheight-15 fontsize-13 ratatengah mt-3">
                                                <a href="javascript:void(0)" class="btn btn-primary btn-sm mb-2" data-bs-toggle="modal" data-bs-target="#modalku" onclick="showDetail('{{ $apk->id_organisasiden }}')">
                                                    Selengkapnya
                                                </a>
                                            </p>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                            
                        </div>
                        <div class="mt-4 mb-4" style="height: 20px;"></div>
                    </article>
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

<script type="text/javascript">
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    function showDetail(id){
        $('#modalku').modal('show').find('#modalku-content').load("{{ url('/profildetail') }}?id="+id);
    }
    // function showFormRUED(id){
    //     $('#modalkudefault').modal('show').find('#modalkudefault-content').load("{{ url('/modalruedp') }}?id="+id);
    // }

    $(document).ready(function() {
    
    });
</script>

@endsection