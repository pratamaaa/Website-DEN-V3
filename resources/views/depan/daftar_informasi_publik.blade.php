@extends('layout.depan.app')

@section('content')
<style>
.valign-tengah{
    vertical-align: middle;
}
.wargabg-hijau{
    background-color: #03A303 !important;
}
.warna-hitam{
    color: #000000 !important;
}
.warna-putih{
    color: #ffffff !important;
}
</style>
<div role="main" class="main">

    <section class="page-header page-header-modern bg-color-light-scale-1 page-header-md" style="background: url('{{ asset('theme/img/ndr-banner-green.webp') }}'); background-size:cover; background-position: 0 100%;">
        <div class="container">
            <div class="row">
                <div class="col-md-12 align-self-center order-1">
                    <ul class="breadcrumb d-block text-center">
                        <li><a href="#" class="warna-putih">Home</a></li>
                        <li><a href="#" class="warna-putih">Informasi Publik</a></li>
                        <li class="active warna-hitam">Daftar Informasi Publik</li>
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
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th class="ratatengah valign-tengah wargabg-hijau warna-putih">No</th>
                            <th class="ratatengah valign-tengah wargabg-hijau warna-putih">Ringkasan Isi Informasi</th>
                            <th class="ratatengah valign-tengah wargabg-hijau warna-putih">Satker yang Menguasai Informasi</th>
                            <th class="ratatengah valign-tengah wargabg-hijau warna-putih">Penaggungjawab Informasi</th>
                            <th class="ratatengah valign-tengah wargabg-hijau warna-putih">Waktu dan Tempat Pembuatan</th>
                            <th class="ratatengah valign-tengah wargabg-hijau warna-putih">Bentuk dan Informasi</th>
                            <th class="ratatengah valign-tengah wargabg-hijau warna-putih">Jangka Waktu Penyimpanan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($dip->get() as $key => $dip)
                            <tr>
                                <td>{{ $key+1 }}</td>
                                <td>{{ $dip->ringkasan_isi }}</td>
                                <td>{{ $dip->pic_satker }}</td>
                                <td>{{ $dip->penanggungjawab }}</td>
                                <td>{{ $dip->waktu_tempat }}</td>
                                <td>{{ $dip->bentuk_informasi }}</td>
                                <td>{{ $dip->retensi_arsip }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </div>

</div>

<script>
$(document).ready(function() {
    
});
</script>
@endsection