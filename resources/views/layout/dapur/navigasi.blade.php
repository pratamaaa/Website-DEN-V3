@php($role = session('sesLevel'))
<style>
    .logout-button {
        width: 100%;
        border: none;
        background: transparent;
        text-align: left;
        padding: 10px 15px;
        cursor: pointer;
    }

    .logout-button:hover {
        background: #ffeaea;
    }

    .logout-button .pcoded-mtext {
        font-weight: 600;
    }
</style>

<nav class="pcoded-navbar menupos-fixed menu-light">
    <div class="navbar-wrapper">
        <div class="navbar-content scroll-div">

            <ul class="nav pcoded-inner-navbar">

                {{-- ================= MENU PENGELOLAAN ================= --}}
                @if (in_array($role, ['admin-sistem', 'manager-konten']))
                    <li class="nav-item pcoded-menu-caption">
                        <label>Manajemen Konten</label>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('dashboard') }}" class="nav-link">
                            <span class="pcoded-micon"><i class="feather icon-home"></i></span>
                            <span class="pcoded-mtext">Dashboard</span>
                        </a>
                    </li>

                    <li class="nav-item pcoded-hasmenu">
                        <a href="#" class="nav-link">
                            <span class="pcoded-micon"><i class="feather icon-video"></i></span>
                            <span class="pcoded-mtext">Media & Publikasi</span>
                        </a>

                        <ul class="pcoded-submenu">

                            <li><a href="{{ route('kategoriberita.index') }}">Kategori Berita</a></li>
                            <li><a href="{{ route('berita.index') }}">Berita</a></li>

                            <li><a href="{{ route('infopublik.index') }}">Info Publik Grouping</a></li>
                            <li><a href="{{ route('dip.index') }}">Daftar Informasi Publik</a></li>

                            <li><a href="{{ route('kategoripublikasi.index') }}">Kategori Publikasi</a></li>
                            <li><a href="{{ route('publikasi.index') }}">Publikasi</a></li>

                            <li><a href="{{ route('imageslider.index') }}">Image Slider</a></li>
                            <li><a href="{{ route('ruedp.index') }}">RUED P</a></li>

                            <li><a href="{{ route('infografis.index') }}">Infografis</a></li>
                            <li><a href="{{ route('galerivideo.index') }}">Galeri Video</a></li>

                            <li><a href="{{ route('layananpublik.index') }}">Layanan Publik</a></li>

                        </ul>
                    </li>

                    <li class="nav-item pcoded-hasmenu">

                        <a href="#" class="nav-link">
                            <span class="pcoded-micon"><i class="feather icon-book"></i></span>
                            <span class="pcoded-mtext">RUED-P</span>
                        </a>

                        <ul class="pcoded-submenu">
                            <li><a href="{{ route('ruedpstatus.index') }}">Status RUED</a></li>
                            <li><a href="{{ route('ruedpprovinsi.index') }}">Provinsi RUED</a></li>
                        </ul>

                    </li>

                    <li class="nav-item pcoded-hasmenu">

                        <a href="#" class="nav-link">
                            <span class="pcoded-micon"><i class="feather icon-trending-up"></i></span>
                            <span class="pcoded-mtext">Reformasi Birokrasi</span>
                        </a>

                        <ul class="pcoded-submenu">
                            <li><a href="{{ route('rbkategori.index') }}">Kategori RB</a></li>
                            <li><a href="{{ route('rb.index') }}">Reformasi Birokrasi</a></li>

                            {{-- TAMBAHAN --}}
                            <li><a href="{{ route('admin.gratifikasi.index') }}">Pelaporan Gratifikasi</a></li>
                        </ul>

                    </li>


                    <li class="nav-item pcoded-hasmenu">

                        <a href="#" class="nav-link">
                            <span class="pcoded-micon"><i class="feather icon-bookmark"></i></span>
                            <span class="pcoded-mtext">Profil</span>
                        </a>

                        <ul class="pcoded-submenu">
                            <li><a href="{{ route('identitasorganisasi.index') }}">Identitas Organisasi</a></li>
                            <li><a href="{{ route('profilden.index') }}">Profil DEN</a></li>
                            <li><a href="{{ route('organisasiden.index') }}">Organisasi DEN</a></li>
                            {{-- <li><a href="{{ route('profilsetjen.index') }}">Profil Setjen</a></li> --}}
                            <li><a href="{{ route('strukturorganisasi.index') }}">Struktur Organisasi</a></li>
                            <li><a href="{{ route('tugasfungsi.index') }}">Tugas & Fungsi</a></li>
                        </ul>

                    </li>
                @endif


                {{-- ================= KUESIONER ================= --}}
                @if (in_array($role, ['admin-sistem', 'operator-kuesioner']))
                    <li class="nav-item pcoded-menu-caption">
                        <label>Layanan Kuesioner</label>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('kuesioner.overview') }}" class="nav-link">
                            <span class="pcoded-micon"><i class="feather icon-bar-chart"></i></span>
                            <span class="pcoded-mtext">Overview</span>
                        </a>
                    </li>

                    <li class="nav-item pcoded-hasmenu">

                        <a href="#" class="nav-link">
                            <span class="pcoded-micon"><i class="feather icon-settings"></i></span>
                            <span class="pcoded-mtext">Manajemen</span>
                        </a>

                        <ul class="pcoded-submenu">
                            <li><a href="{{ route('kuesioner.template') }}">Template Jawaban</a></li>
                            <li><a href="{{ route('kuesioner.layanan') }}">Layanan</a></li>
                        </ul>

                    </li>

                    <li class="nav-item">
                        <a href="{{ route('kuesioner.responden') }}" class="nav-link">
                            <span class="pcoded-micon"><i class="feather icon-users"></i></span>
                            <span class="pcoded-mtext">Data Responden</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('kuesioner.analisa') }}" class="nav-link">
                            <span class="pcoded-micon"><i class="feather icon-pie-chart"></i></span>
                            <span class="pcoded-mtext">Hasil & Analisa</span>
                        </a>
                    </li>
                @endif


                {{-- ================= AUDIT ================= --}}
                @if (in_array($role, ['admin-sistem', 'auditor']))
                    <li class="nav-item pcoded-menu-caption">
                        <label>Audit Sistem</label>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('audit.dashboard') }}" class="nav-link">
                            <span class="pcoded-micon"><i class="feather icon-activity"></i></span>
                            <span class="pcoded-mtext">Audit Dashboard</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('audit.logs') }}" class="nav-link">
                            <span class="pcoded-micon"><i class="feather icon-file-text"></i></span>
                            <span class="pcoded-mtext">Audit Logs</span>
                        </a>
                    </li>
                @endif


                {{-- ================= PENGATURAN ================= --}}
                <li class="nav-item pcoded-menu-caption">
                    <label>Pengaturan</label>
                </li>

                @if ($role == 'admin-sistem')
                    <li class="nav-item">
                        <a href="{{ route('levelpengguna.index') }}" class="nav-link">
                            <span class="pcoded-micon"><i class="feather icon-user-check"></i></span>
                            <span class="pcoded-mtext">Level Pengguna</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('pengguna.index') }}" class="nav-link">
                            <span class="pcoded-micon"><i class="feather icon-users"></i></span>
                            <span class="pcoded-mtext">Pengguna</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('admin.mfa.index') }}" class="nav-link">
                            <span class="pcoded-micon"><i class="feather icon-lock"></i></span>
                            <span class="pcoded-mtext">Reset MFA</span>
                        </a>
                    </li>
                @endif


                <li class="nav-item">
                    <a href="{{ route('pengguna.password') }}" class="nav-link">
                        <span class="pcoded-micon"><i class="feather icon-unlock"></i></span>
                        <span class="pcoded-mtext">Ganti Password</span>
                    </a>
                </li>


                {{-- ================= LOGOUT ================= --}}
                <li class="nav-item pcoded-menu-caption">
                    <label>Akun</label>
                </li>

                <li class="nav-item">
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button class="nav-link logout-button" type="submit">
                            <span class="pcoded-micon text-danger">
                                <i class="feather icon-log-out"></i>
                            </span>
                            <span class="pcoded-mtext text-danger">
                                Logout
                            </span>
                        </button>
                    </form>
                </li>

            </ul>
        </div>
    </div>
</nav>
