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
                            <li><a href="#" class="warna-putih">Sekretariat</a></li>
                            <li class="active warna-hitam">Tugas & Fungsi</li>
                        </ul>
                    </div>
                    <div class="col-md-12 align-self-center p-static order-2 text-center">
                        <h1 class="font-weight-bold text-6 warna-putih">Tugas & Fungsi</h1>
                        <span class="sub-title warna-putih">Sekretariat Jenderal Dewan Energi Nasional</span>
                    </div>
                </div>
            </div>
        </section>

        <div class="container py-5">
            <div class="row">
                <div class="col-lg-12">
                    {!! $data->konten ?? '<p class="text-muted">Konten belum tersedia.</p>' !!}
                </div>
            </div>
        </div>

    </div>
@endsection
