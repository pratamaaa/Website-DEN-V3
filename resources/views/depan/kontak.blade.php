@extends('layout.depan.app')

@section('content')
<div role="main" class="main">

    <section class="page-header page-header-modern bg-color-light-scale-1 page-header-md" style="background: url('{{ asset('theme/img/ndr-banner-green.webp') }}'); background-size:cover; background-position: 0 100%;">
        <div class="container">
            <div class="row">
                <div class="col-md-12 align-self-center order-1">
                    <ul class="breadcrumb d-block text-center">
                        <li><a href="#" class="warna-putih">Home</a></li>
                        <li><a href="active warna-hitam">Kontak</a></li>
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
            <div class="col-lg-4">
                <h4>Sekretariat Jenderal <strong>Dewan Energi Nasional</strong></h4>
                @if ($kontak->count() != 0)
                    <ul class="list list-icons list-icons-style-3 mt-2">
                        <li>
                            <i class="fas fa-map-marker-alt top-6_"></i> <strong>Alamat:</strong> 
                            @php
                            echo $kontak->first()->alamat
                            @endphp
                        </li>
                        <li><i class="fas fa-phone top-6"></i> <strong>Telp:</strong> {{ $kontak->first()->telpon }}</li>
                        <li><i class="fas fa-fax top-6"></i> <strong>Fax:</strong> {{ $kontak->first()->fax }}</li>
                        <li><i class="fas fa-envelope top-6"></i> <strong>Email:</strong> <a href="mailto:{{ $kontak->first()->email }}">{{ $kontak->first()->email }}</a></li>
                    </ul>  
                @else
                    <div class="alert alert-danger">Belum ada data identitas organisasi</div>
                @endif
                
            </div>
            <div class="col-lg-8">
                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3966.1976542827983!2d106.82752941532395!3d-6.23765906282019!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69f3c2bb43b917%3A0xba600cb1460efcc!2sDewan+Energi+Nasional!5e0!3m2!1sid!2sid!4v1526453755722"
                          width="100%" height="350" frameborder="0" style="border:0" allowfullscreen></iframe>

            </div>
        </div>

    </div>

</div>

<script>
$(document).ready(function() {
    
});
</script>
@endsection