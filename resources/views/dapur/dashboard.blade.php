@extends('layout.dapur.app')

@section('content')
<section class="pcoded-main-container">
    <div class="pcoded-content">

        <div class="row">
            <div class="col-sm-12">
                <div class="card">

                    <div class="card-body">
                        <nav class="navbar" style="margin-left:-10px !important;">
                            <span class="m-r-15"><h5>{{ $judulhalaman }}</h5></span>
                        </nav>

                        <hr>
                        <div class="row">
                            <div class="col-md-6 col-xl-3">
                                <div class="card bg-c-blue order-card">
                                    <div class="card-body">
                                        <h6 class="text-white">Berita</h6>
                                        <h2 class="text-end text-white">
                                            <i class="fa-regular fa-newspaper"></i> <span>{{ $cberita }}</span>
                                        </h2>
                                        <a href="{{ url('/dap/berita') }}" class="warna-putih">
                                            <p class="m-b-0">
                                                Lihat lebih lanjut 
                                                <i class="fa-solid fa-arrow-right" style="font-size:13px !important;"></i>
                                            </p>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-xl-3">
                                <div class="card bg-c-green order-card">
                                    <div class="card-body">
                                        <h6 class="text-white">Infografis</h6>
                                        <h2 class="text-end text-white">
                                            <i class="fa-solid fa-circle-info"></i> <span>{{ $cinfografis }}</span>
                                        </h2>
                                        <a href="{{ url('/dap/infografis') }}" class="warna-putih">
                                            <p class="m-b-0">
                                                Lihat lebih lanjut 
                                                <i class="fa-solid fa-arrow-right" style="font-size:13px !important;"></i>
                                            </p>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-xl-3">
                                <div class="card bg-c-yellow order-card">
                                    <div class="card-body">
                                        <h6 class="text-white">Publikasi</h6>
                                        <h2 class="text-end text-white">
                                            <i class="fa-regular fa-clipboard"></i> <span>{{ $cpublikasi }}</span>
                                        </h2>
                                        <a href="{{ url('/dap/publikasi') }}" class="warna-putih">
                                            <p class="m-b-0">
                                                Lihat lebih lanjut 
                                                <i class="fa-solid fa-arrow-right" style="font-size:13px !important;"></i>
                                            </p>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-xl-3">
                                <div class="card bg-c-red order-card">
                                    <div class="card-body">
                                        <h6 class="text-white">Video</h6>
                                        <h2 class="text-end text-white">
                                            <i class="fa-regular fa-file-video"></i> <span>{{ $cvideo }}</span>
                                        </h2>
                                        <a href="{{ url('/dap/galerivideo') }}" class="warna-putih">
                                            <p class="m-b-0">
                                                Lihat lebih lanjut 
                                                <i class="fa-solid fa-arrow-right" style="font-size:13px !important;"></i>
                                            </p>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection