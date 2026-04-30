@extends('layout.depan.app')

@section('content')
<div role="main" class="main">

    {{-- HERO BANNER (Porto original) --}}
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
                </div>
            </div>
        </div>
    </section>

    @php
        // Langsung get() supaya query tidak dieksekusi berulang (hindari bug foto tidak muncul)
        $ketuaData   = \App\Models\Organisasiden::where('kategori_jabatan', 'KETUA')->where('is_active', 'yes')->get();
        $waketuaData = \App\Models\Organisasiden::where('kategori_jabatan', 'WAKETUA')->where('is_active', 'yes')->get();
        $kharianData = \App\Models\Organisasiden::where('kategori_jabatan', 'KHARIAN')->where('is_active', 'yes')->get();
        $apData      = \App\Models\Organisasiden::where('kategori_jabatan', 'AP')->where('is_active', 'yes')->get();
        $apkData     = \App\Models\Organisasiden::where('kategori_jabatan', 'APK')->where('is_active', 'yes')->get();

        $k  = $ketuaData->first();
        $w  = $waketuaData->first();
        $kh = $kharianData->first();

        $defaultFoto = asset('theme/img/blog/square/blog-11.jpg');
    @endphp

    <div class="so-wrapper">

        {{-- PIMPINAN --}}
        <div class="so-section-label">Pimpinan DEN</div>
        <div class="so-pimpinan-list">

        {{-- KETUA --}}
        <div class="so-pimpinan-card so-pimpinan-ketua">
                <img src="{{ $k ? asset('uploads/profilden/'.$k->foto) : $defaultFoto }}"
                     class="so-pimpinan-photo" alt="{{ $k?->namalengkap ?? 'Ketua' }}">
                <div class="so-pimpinan-info">
                    <div class="so-pimpinan-name">{{ $k?->namalengkap ?? 'Belum diset' }}</div>
                    <div class="so-pimpinan-role">{{ $k?->jabatan ?? '-' }}</div>
                </div>
                <div class="so-pimpinan-right">
                    <span class="so-badge so-badge-ketua">Ketua DEN</span>
                    @if($k)
                        <a href="javascript:void(0)" class="so-btn-detail"
                           data-bs-toggle="modal" data-bs-target="#modalku"
                           onclick="showDetail('{{ $k->id_organisasiden }}')">Detail →</a>
                    @endif
                </div>
            </div>

            <div class="so-connector"></div>

            {{-- WAKIL KETUA --}}
            <div class="so-pimpinan-card so-pimpinan-wakil">
                <img src="{{ $w ? asset('uploads/profilden/'.$w->foto) : $defaultFoto }}"
                     class="so-pimpinan-photo" alt="{{ $w?->namalengkap ?? 'Wakil Ketua' }}">
                <div class="so-pimpinan-info">
                    <div class="so-pimpinan-name">{{ $w?->namalengkap ?? 'Belum diset' }}</div>
                    <div class="so-pimpinan-role">{{ $w?->jabatan ?? '-' }}</div>
                </div>
                <div class="so-pimpinan-right">
                    <span class="so-badge so-badge-wakil">Wakil Ketua DEN</span>
                    @if($w)
                        <a href="javascript:void(0)" class="so-btn-detail"
                           data-bs-toggle="modal" data-bs-target="#modalku"
                           onclick="showDetail('{{ $w->id_organisasiden }}')">Detail →</a>
                    @endif
                </div>
            </div>

            <div class="so-connector"></div>

            {{-- KETUA HARIAN --}}
            <div class="so-pimpinan-card so-pimpinan-harian">
                <img src="{{ $kh ? asset('uploads/profilden/'.$kh->foto) : $defaultFoto }}"
                     class="so-pimpinan-photo" alt="{{ $kh?->namalengkap ?? 'Ketua Harian' }}">
                <div class="so-pimpinan-info">
                    <div class="so-pimpinan-name">{{ $kh?->namalengkap ?? 'Belum diset' }}</div>
                    <div class="so-pimpinan-role">{{ $kh?->jabatan ?? '-' }}</div>
                </div>
                <div class="so-pimpinan-right">
                    <span class="so-badge so-badge-harian">Ketua Harian</span>
                    @if($kh)
                        <a href="javascript:void(0)" class="so-btn-detail"
                           data-bs-toggle="modal" data-bs-target="#modalku"
                           onclick="showDetail('{{ $kh->id_organisasiden }}')">Detail →</a>
                    @endif
                </div>
            </div>
        </div>

        {{-- DIVIDER ANGGOTA --}}
        <div class="so-divider">
            <div class="so-divider-line"></div>
            <div class="so-divider-badge"><span class="so-divider-dot"></span> Anggota DEN</div>
            <div class="so-divider-line"></div>
        </div>

        {{-- TAB --}}
        <div class="so-tab-bar">
            <div class="so-tab active" data-target="tab-pemerintah">Unsur Pemerintah</div>
            <div class="so-tab" data-target="tab-pemangku">Pemangku Kepentingan</div>
        </div>

        {{-- TAB: ANGGOTA PEMERINTAH --}}
        <div id="tab-pemerintah" class="so-tab-content">
            <div class="so-anggota-grid">
                @if($apData->isEmpty())
                    <div class="so-empty">Data belum tersedia.</div>
                @else
                    @foreach($apData as $item)
                        <div class="so-anggota-card">
                            <img src="{{ asset('uploads/profilden/'.$item->foto) }}"
                                 class="so-anggota-photo" alt="{{ $item->namalengkap }}">
                            <div class="so-anggota-name">{{ $item->namalengkap }}</div>
                            <div class="so-anggota-role">{{ $item->jabatan }}</div>
                            <a href="javascript:void(0)" class="so-btn-detail"
                               data-bs-toggle="modal" data-bs-target="#modalku"
                               onclick="showDetail('{{ $item->id_organisasiden }}')">Detail →</a>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>

        {{-- TAB: PEMANGKU KEPENTINGAN --}}
        <div id="tab-pemangku" class="so-tab-content" style="display:none;">
            <div class="so-anggota-grid">
                @if($apkData->isEmpty())
                    <div class="so-empty">Data belum tersedia.</div>
                @else
                    @foreach($apkData as $item)
                        <div class="so-anggota-card">
                            <img src="{{ asset('uploads/profilden/'.$item->foto) }}"
                                 class="so-anggota-photo" alt="{{ $item->namalengkap }}">
                            <div class="so-anggota-name">{{ $item->namalengkap }}</div>
                            <div class="so-anggota-role">{{ $item->jabatan }}</div>
                            @if(!empty($item->kalangan))
                                <span class="so-tag-kalangan">{{ $item->kalangan }}</span>
                            @endif
                            <a href="javascript:void(0)" class="so-btn-detail"
                               data-bs-toggle="modal" data-bs-target="#modalku"
                               onclick="showDetail('{{ $item->id_organisasiden }}')">Detail →</a>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>

    </div>{{-- end so-wrapper --}}

</div>

{{-- MODAL --}}
<div class="modal fade" id="modalku" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" id="modalku-content"></div>
    </div>
</div>

{{-- CSS --}}
<style>
.so-wrapper { max-width: 900px; margin: 0 auto; padding: 2rem 1.5rem 3rem; }

.so-section-label {
    font-size: 11px;
    font-weight: 600;
    letter-spacing: 1.2px;
    text-transform: uppercase;
    color: #888;
    margin-bottom: 14px;
}

/* PIMPINAN */
.so-pimpinan-list { display: flex; flex-direction: column; gap: 0; }

.so-pimpinan-card {
    background: #fff;
    border: 1px solid #e8e8e8;
    border-radius: 12px;
    display: flex;
    align-items: center;
    gap: 16px;
    padding: 14px 18px;
    border-left-width: 4px;
}
.so-pimpinan-ketua  { border-left-color: #1e7a35; }
.so-pimpinan-wakil  { border-left-color: #2d9c4a; }
.so-pimpinan-harian { border-left-color: #5abf76; }

.so-pimpinan-photo {
    width: 60px; height: 60px;
    border-radius: 10px;
    object-fit: cover;
    border: 1px solid #e0e0e0;
    flex-shrink: 0;
    background: #f5f5f5;
}
.so-pimpinan-info { flex: 1; min-width: 0; }
.so-pimpinan-name { font-size: 14px; font-weight: 600; color: #1a1a1a; line-height: 1.35; }
.so-pimpinan-role { font-size: 12px; color: #666; margin-top: 3px; line-height: 1.4; }

.so-pimpinan-right {
    display: flex; flex-direction: column;
    align-items: flex-end; gap: 8px; flex-shrink: 0;
}

.so-badge {
    font-size: 10.5px; font-weight: 600;
    padding: 3px 10px; border-radius: 20px;
    white-space: nowrap;
}
.so-badge-ketua  { background: #dff2e5; color: #155a27; }
.so-badge-wakil  { background: #e5f5ea; color: #1e6e30; }
.so-badge-harian { background: #eef8f1; color: #2a7d3e; }

.so-connector {
    width: 3px; height: 18px;
    background: #d0e8d8;
    margin: 0 0 0 28px;
    border-radius: 2px;
}

/* DIVIDER */
.so-divider {
    display: flex; align-items: center;
    gap: 12px; margin: 2rem 0 1.25rem;
}
.so-divider-line { flex: 1; height: 1px; background: #e5e5e5; }
.so-divider-badge {
    display: flex; align-items: center; gap: 7px;
    background: #1a5c2a; color: #fff;
    font-size: 12px; font-weight: 600;
    padding: 6px 14px; border-radius: 20px;
    white-space: nowrap;
}
.so-divider-dot { width: 6px; height: 6px; border-radius: 50%; background: rgba(255,255,255,0.55); }

/* TABS */
.so-tab-bar {
    display: flex;
    border-bottom: 1.5px solid #e5e5e5;
    margin-bottom: 20px;
}
.so-tab {
    flex: 1; text-align: center;
    padding: 11px 8px;
    font-size: 13px; font-weight: 500;
    color: #888; cursor: pointer;
    border-bottom: 2.5px solid transparent;
    margin-bottom: -1.5px;
    transition: color 0.15s, border-color 0.15s;
}
.so-tab:hover { color: #2d8a47; }
.so-tab.active { color: #1a6b30; border-bottom-color: #1a6b30; }

/* ANGGOTA GRID */
.so-anggota-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
    gap: 12px;
    margin-bottom: 2rem;
}
.so-anggota-card {
    background: #fff;
    border: 1px solid #e8e8e8;
    border-radius: 12px;
    padding: 16px 12px;
    display: flex; flex-direction: column;
    align-items: center; text-align: center; gap: 8px;
    transition: border-color 0.15s, box-shadow 0.15s;
}
.so-anggota-card:hover {
    border-color: #a8d8b5;
    box-shadow: 0 2px 12px rgba(30,120,60,0.08);
}
.so-anggota-photo {
    width: 72px; height: 72px;
    border-radius: 50%;
    object-fit: cover;
    border: 2px solid #e0ede4;
    background: #f5f5f5;
}
.so-anggota-name {
    font-size: 12px; font-weight: 600;
    color: #1a1a1a; line-height: 1.35;
}
.so-anggota-role {
    font-size: 11px; color: #666; line-height: 1.4; margin-top: -2px;
}
.so-tag-kalangan {
    font-size: 10px; font-weight: 600;
    padding: 3px 10px; border-radius: 20px;
    background: #f0f0f0; color: #555;
    border: 1px solid #e0e0e0;
}

/* BUTTONS */
.so-btn-detail {
    display: inline-block;
    font-size: 11px; font-weight: 600;
    color: #1a6b30; background: #dff2e5;
    border: none; border-radius: 20px;
    padding: 5px 12px; cursor: pointer;
    text-decoration: none;
    transition: background 0.15s;
}
.so-btn-detail:hover { background: #c8ebcf; color: #155a27; text-decoration: none; }

.so-empty { font-size: 13px; color: #999; padding: 1rem 0; }

@media (max-width: 576px) {
    .so-wrapper { padding: 1.5rem 1rem 2rem; }
    .so-anggota-grid { grid-template-columns: repeat(2, 1fr); }
    .so-pimpinan-name { font-size: 13px; }
    .so-hero-title { font-size: 20px; }
}
</style>

{{-- SCRIPTS --}}
<script>
$.ajaxSetup({
    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
});

function showDetail(id) {
    $('#modalku').modal('show').find('#modalku-content').load("{{ url('/profildetail') }}?id=" + id);
}

$(document).ready(function () {
    $('.so-tab').on('click', function () {
        var target = $(this).data('target');
        $('.so-tab').removeClass('active');
        $(this).addClass('active');
        $('.so-tab-content').hide();
        $('#' + target).show();
    });
});
</script>

@endsection