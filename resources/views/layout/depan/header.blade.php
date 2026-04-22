<header id="header" class="header-effect-shrink"
    data-plugin-options="{'stickyEnabled': true, 'stickyEffect': 'shrink', 'stickyEnableOnBoxed': true, 'stickyEnableOnMobile': false, 'stickyChangeLogo': true, 'stickyStartAt': 120, 'stickyHeaderContainerHeight': 70}">
    <div class="header-body border-top-0">

        <div class="header-container container">
            <div class="header-row">
                <div class="header-column">
                    <div class="header-row">
                        <div class="header-logo">
                            <a href="#">
                                <img alt="Porto" width="60" height="60" data-sticky-width="60"
                                    data-sticky-height="60" src="{{ asset('theme/img/logo/logo-den.png') }}">
                            </a>
                        </div>
                        <div class="textlogo">
                            <a href="{{ url('/') }}" style="">
                                <span class="textlogo-big">
                                    DEWAN ENERGI NASIONAL
                                </span><br>
                                <span class="textlogo-small">
                                    REPUBLIK INDONESIA
                                </span>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="header-column justify-content-end">
                    <div class="header-row">
                        <div
                            class="header-nav header-nav-line header-nav-top-line header-nav-top-line-with-border order-2 order-lg-1">
                            <div
                                class="header-nav-main header-nav-main-square header-nav-main-effect-2 header-nav-main-sub-effect-1">
                                <nav class="collapse">
                                    <ul class="nav nav-pills" id="mainNav">
                                        <li class="dropdown">
                                            <a class="dropdown-item dropdown-toggle active" href="{{ url('/') }}">
                                                Home
                                            </a>
                                        </li>
                                        <li class="dropdown">
                                            <a class="dropdown-item dropdown-toggle" href="#">
                                                Profil DEN
                                            </a>
                                            <ul class="dropdown-menu">
                                                @php
                                                    $profilden = \App\Models\Profil::where(
                                                        'id_profil_kategori',
                                                        '1',
                                                    )->get();

                                                    foreach ($profilden as $profil) {
                                                        $url = url('/profil/' . $profil->slug);
                                                        echo '<li><a href="' .
                                                            $url .
                                                            '" class="dropdown-item">' .
                                                            $profil->judulmenu .
                                                            '</a></li>';
                                                    }
                                                @endphp
                                            </ul>
                                        </li>
                                        <li class="dropdown">
                                            <a class="dropdown-item dropdown-toggle" href="#">
                                                Sekretariat
                                            </a>
                                            <ul class="dropdown-menu">
                                                <li>
                                                    <a href="{{ route('strukturorganisasi') }}" class="dropdown-item">
                                                        Struktur Organisasi
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="{{ route('tugasfungsi.publik') }}" class="dropdown-item">
                                                        Tugas & Fungsi
                                                    </a>
                                                </li>
                                            </ul>
                                        </li>

                                        <li class="dropdown">
                                            <a class="dropdown-item dropdown-toggle" href="#">
                                                Media
                                            </a>
                                            <ul class="dropdown-menu">

                                                @php
                                                    $kondisi = [
                                                        ['id_informasipublik', '=', '5'],
                                                        ['is_active', '=', 'yes'],
                                                    ];
                                                    $katpub = \App\Models\Kategoripublikasi::where($kondisi)
                                                        ->orderBy('urutan', 'asc')
                                                        ->get();

                                                    foreach ($katpub as $kat) {
                                                        if ($kat->haschild == 'no') {
                                                            $dropClass = '';
                                                        } else {
                                                            $dropClass = 'class="dropdown-submenu"';
                                                        }
                                                        $alamatUrl =
                                                            $kat->is_page == 'yes' ? url($kat->url) : $kat->url;
                                                        echo '<li ' . $dropClass . '>'; //<li class="dropdown-submenu>"
                                                        if ($kat->id_parent == 0 && $kat->haschild == 'no') {
                                                            echo '<a href="' .
                                                                $alamatUrl .
                                                                '" class="dropdown-item">' .
                                                                $kat->nama_kategori .
                                                                '</a>';
                                                        } elseif ($kat->id_parent == 0 && $kat->haschild == 'yes') {
                                                            echo '<a href="' .
                                                                $alamatUrl .
                                                                '" class="dropdown-item">' .
                                                                $kat->nama_kategori .
                                                                '</a>';

                                                            $katChild = \App\Models\Kategoripublikasi::where(
                                                                'id_parent',
                                                                $kat->id_publikasi_kategori,
                                                            )
                                                                ->orderBy('urutan', 'asc')
                                                                ->get();
                                                            echo '<ul class="dropdown-menu">';
                                                            foreach ($katChild as $katChild) {
                                                                $alamatUrlAnak =
                                                                    $katChild->is_page == 'yes'
                                                                        ? url($katChild->url)
                                                                        : $katChild->url;
                                                                echo '<a href="' .
                                                                    $alamatUrlAnak .
                                                                    '" class="dropdown-item">' .
                                                                    $katChild->nama_kategori .
                                                                    '</a>';
                                                            }
                                                            echo '</ul>';
                                                        }
                                                        echo '</li>';
                                                    }
                                                @endphp
                                            </ul>
                                        </li>

                                        <li class="dropdown">
                                            <a class="dropdown-item dropdown-toggle" href="#">
                                                Informasi Publik
                                            </a>
                                            <ul class="dropdown-menu">
                                                @php
                                                    $kondisiIP = [
                                                        ['group', '=', 'informasipublik'],
                                                        ['is_active', '=', 'yes'],
                                                    ];
                                                    $infopublik = \App\Models\Informasipublik::where($kondisiIP)
                                                        ->orderBy('urutan', 'asc')
                                                        ->get();

                                                    foreach ($infopublik as $info) {
                                                        if ($info->jenis == 'menu') {
                                                            $dropdownClass = 'class="dropdown-submenu"';
                                                        } else {
                                                            $dropdownClass = '';
                                                        }
                                                        echo '<li ' . $dropdownClass . '>'; //<li class="dropdown-submenu">
                                                        if ($info->jenis == 'tautan') {
                                                            echo '<a href="' .
                                                                $info->alamat_url .
                                                                '" class="dropdown-item">' .
                                                                $info->informasipublik .
                                                                '</a>';
                                                        } else {
                                                            echo '<a href="#" class="dropdown-item">' .
                                                                $info->informasipublik .
                                                                '</a>';
                                                        }

                                                        // Attach menu anak disini
                                                        $katpubKondisi = [
                                                            ['is_active', '=', 'yes'],
                                                            ['id_informasipublik', '=', $info->id_informasipublik],
                                                        ];
                                                        $katpub = \App\Models\Kategoripublikasi::where($katpubKondisi)
                                                            ->orderBy('urutan', 'asc')
                                                            ->get();

                                                        echo '<ul class="dropdown-menu">';
                                                        foreach ($katpub as $kat) {
                                                            if ($kat->haschild == 'no') {
                                                                $dropClass = '';
                                                            } else {
                                                                $dropClass = 'class="dropdown-submenu"';
                                                            }
                                                            $alamatUrl =
                                                                $kat->is_page == 'yes' ? url($kat->url) : $kat->url;
                                                            echo '<li ' . $dropClass . '>';
                                                            if ($kat->id_parent == 0 && $kat->haschild == 'no') {
                                                                echo '<a href="' .
                                                                    $alamatUrl .
                                                                    '" class="dropdown-item">' .
                                                                    $kat->nama_kategori .
                                                                    '</a>';
                                                            } elseif ($kat->id_parent == 0 && $kat->haschild == 'yes') {
                                                                echo '<a href="' .
                                                                    $alamatUrl .
                                                                    '" class="dropdown-item">' .
                                                                    $kat->nama_kategori .
                                                                    '</a>';

                                                                $katChild = \App\Models\Kategoripublikasi::where(
                                                                    'id_parent',
                                                                    $kat->id_publikasi_kategori,
                                                                )
                                                                    ->orderBy('urutan', 'asc')
                                                                    ->get();
                                                                echo '<ul class="dropdown-menu">';
                                                                foreach ($katChild as $katChild) {
                                                                    $alamatUrlAnak =
                                                                        $katChild->is_page == 'yes'
                                                                            ? url($katChild->url)
                                                                            : $katChild->url;
                                                                    echo '<a href="' .
                                                                        $alamatUrlAnak .
                                                                        '" class="dropdown-item">' .
                                                                        $katChild->nama_kategori .
                                                                        '</a>';
                                                                }
                                                                echo '</ul>';
                                                            }
                                                            echo '</li>';
                                                        }
                                                        echo '</ul>';
                                                        echo '</li>';
                                                    }
                                                @endphp


                                            </ul>
                                        </li>

                                        <li class="dropdown">
                                            <a class="dropdown-item dropdown-toggle" href="#">
                                                Reformasi Birokrasi
                                            </a>
                                            <ul class="dropdown-menu">
                                                @php
                                                    $rb = \App\Models\Kategorirb::orderBy('urutan', 'asc')->get();

                                                    foreach ($rb as $rbi) {
                                                        $url = url('/reformasi-birokrasi/' . $rbi->slug);
                                                        echo '<li><a href="' .
                                                            $url .
                                                            '" class="dropdown-item">' .
                                                            $rbi->nama_rbkategori .
                                                            '</a></li>';
                                                    }
                                                @endphp
                                            </ul>
                                        </li>
                                        <li class="dropdown">
                                            <a class="dropdown-item dropdown-toggle"
                                                href="{{ url('/kontak') }}">Kontak</a>
                                        </li>
                                    </ul>
                                </nav>
                            </div>
                            <button class="btn header-btn-collapse-nav" data-bs-toggle="collapse"
                                data-bs-target=".header-nav-main nav">
                                <i class="fas fa-bars"></i>
                            </button>
                        </div>
                        <div
                            class="header-nav-features header-nav-features-no-border header-nav-features-lg-show-border order-1 order-lg-2">
                            <nav
                                style="margin-left:-20px !important; color: #999 !important;font-size: 0.9em;padding: 6px 0px;display: inline-block;letter-spacing: -0.5px;">
                                <ul class="nav nav-pills">
                                    <li
                                        class="nav-item dropdown nav-item-left-border d-none d-sm-block nav-item-left-border-remove nav-item-left-border-md-show">
                                        <a class="nav-link" style="color: #999 !important;font-size: 1em;"
                                            href="#" role="button" id="dropdownLanguage"
                                            data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <img src="{{ asset('theme/img/blank.gif') }}" class="flag flag-id"
                                                alt="Indonesia" /> ID
                                            <i class="fas fa-angle-down"></i>
                                        </a>
                                        <div class="dropdown-menu" aria-labelledby="dropdownLanguage">
                                            <a class="dropdown-item" style="color: #999; font-size: 0.75em !important;"
                                                href="#"><img src="{{ asset('theme/img/blank.gif') }}"
                                                    class="flag flag-id" alt="Indonesia" /> Indonesia</a>
                                            <a class="dropdown-item" style="color: #999; font-size: 0.75em !important;"
                                                href="#"><img src="{{ asset('theme/img/blank.gif') }}"
                                                    class="flag flag-us" alt="English" /> English</a>
                                        </div>
                                    </li>
                                </ul>
                            </nav>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
