<aside class="left-sidebar">
    <!-- Sidebar scroll-->
    <div class="scroll-sidebar">
        <!-- Sidebar navigation-->
        <nav class="sidebar-nav">
            <ul id="sidebarnav">
                @if (Request::segment(1) == "bmn" )
                    <li class="nav-small-cap">
                        <i class="mdi mdi-dots-horizontal"></i><span class="hide-menu">Barang Persediaan</span>
                    </li>
                    <li class="sidebar-item">
                        <a class="sidebar-link waves-effect waves-dark sidebar-link" href="{{ url('/bmn/persediaan') }}" aria-expanded="false">
                            <i class="mdi mdi-calendar"></i><span class="hide-menu">Barang Persediaan</span>
                        </a>
                    </li>
                    <li class="sidebar-item">
                        <a class="sidebar-link waves-effect waves-dark sidebar-link" href="{{ url('/bmn/penerimaan') }}" aria-expanded="false">
                            <i class="mdi mdi-comment-processing-outline"></i><span class="hide-menu">Penerimaan</span>
                        </a>
                    </li>
                    <li class="sidebar-item">
                        <a class="sidebar-link waves-effect waves-dark sidebar-link" href="{{ url('/bmn/pengeluaran') }}" aria-expanded="false">
                            <i class="mdi mdi-account-box"></i><span class="hide-menu">Pengeluaran</span>
                        </a>
                    </li>
                    <li class="sidebar-item">
                        <a class="sidebar-link waves-effect waves-dark sidebar-link" href="{{ url('/bmn/jenisbarang') }}" aria-expanded="false">
                            <i class="mdi mdi-book"></i><span class="hide-menu">Jenis Barang</span>
                        </a>
                    </li>
                    <li class="sidebar-item">
                        <a class="sidebar-link waves-effect waves-dark sidebar-link" href="{{ url('/bmn/satuan') }}" aria-expanded="false">
                            <i class="mdi mdi-arrange-bring-forward"></i><span class="hide-menu">Satuan</span>
                        </a>
                    </li>
                    <li class="sidebar-item">
                        <a class="sidebar-link waves-effect waves-dark sidebar-link" href="{{ url('/bmn/instansi') }}"  aria-expanded="false">
                            <i class="mdi mdi-clipboard-text"></i><span class="hide-menu">Instansi</span>
                        </a>
                    </li>
                @elseif (Request::segment(1) == "rapat")
                    <li class="nav-small-cap"><i class="mdi mdi-dots-horizontal"></i><span class="hide-menu">Rapat dan Kunjungan</span></li>
                    <li class="sidebar-item">
                        <a class="sidebar-link waves-effect waves-dark sidebar-link" href="{{ url('/rapat') }}" aria-expanded="false">
                            <i class="mdi mdi-calendar"></i><span class="hide-menu">Booking Ruang Rapat</span>
                        </a>
                    </li>
                    <li class="sidebar-item">
                        <a class="sidebar-link waves-effect waves-dark sidebar-link" href="{{ url('/rapat/kunjungan') }}" aria-expanded="false">
                            <i class="mdi mdi-comment-processing-outline"></i><span class="hide-menu">Kunjungan</span>
                        </a>
                    </li>
                    <li class="sidebar-item">
                        <a class="sidebar-link waves-effect waves-dark sidebar-link" href="{{ url('/rapat/ruangrapat') }}" aria-expanded="false">
                            <i class="mdi mdi-comment-processing-outline"></i><span class="hide-menu">Ruang Rapat</span>
                        </a>
                    </li>
                @endif
                

                

            </ul>
        </nav>
        <!-- End Sidebar navigation -->
    </div>
    <!-- End Sidebar scroll-->
    <!-- Bottom points-->
    <!-- <div class="sidebar-footer">
        <a href="" class="link" data-toggle="tooltip" title="Settings"><i class="ti-settings"></i></a>
        <a href="" class="link" data-toggle="tooltip" title="Email"><i class="mdi mdi-gmail"></i></a>
        <a href="" class="link" data-toggle="tooltip" title="Logout"><i class="mdi mdi-power"></i></a>
    </div> -->
    <!-- End Bottom points-->
</aside>
