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
                            <li class="active warna-hitam">Struktur Organisasi</li>
                        </ul>
                    </div>
                    <div class="col-md-12 align-self-center p-static order-2 text-center">
                        <h1 class="font-weight-bold text-6 warna-putih">Struktur Organisasi</h1>
                        <span class="sub-title warna-putih">Sekretariat Jenderal Dewan Energi Nasional</span>
                    </div>
                </div>
            </div>
        </section>

        <div class="container-fluid py-5 px-4">
            <div id="strukturOrganisasi" class="so-tree">
                @foreach ($struktur as $topLevel)
                    <div class="so-group">

                        {{-- Card Top Level --}}
                        <div class="so-card so-card--top">
                            <div class="so-card__photo">
                                <img src="{{ $topLevel->foto ? asset('uploads/strukturorganisasi/' . $topLevel->foto) : asset('uploads/default-image/default-avatar.png') }}"
                                    alt="{{ $topLevel->nama_lengkap }}">
                            </div>
                            <div class="so-card__info">
                                <div class="so-card__jabatan">{{ $topLevel->jabatan }}</div>
                                <div class="so-card__nama">{{ $topLevel->nama_lengkap }}</div>
                            </div>
                        </div>

                        @if ($topLevel->allChildren->count() > 0)
                            <div class="so-connector-v"></div>
                            @include('partials.so-children', [
                                'children' => $topLevel->allChildren,
                                'level' => 2,
                            ])
                        @endif

                    </div>
                @endforeach
            </div>

            <div class="so-update">Update : {{ \Carbon\Carbon::now()->isoFormat('MMMM YYYY') }}</div>
        </div>

    </div>
@endsection
