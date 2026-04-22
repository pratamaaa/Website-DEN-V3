<footer id="footer" class="mt-0">
    @php
        $idorg = \App\Models\IdentitasOrganisasi::orderBy('id_identitas_organisasi', 'desc')->first();
    @endphp

    <style>
        .app-version {
            font-size: 12px;
            color: #9aa0a6;
            line-height: 1.6;
        }

        .social-icons li {
            background: #e9ecef;
            border-radius: 6px;
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .social-icons li a {
            color: #555;
        }
    </style>

    <div class="footer-copyright__ footer-copyright-style-2">
        <div class="container py-3">

            <div class="row align-items-center">

                <div class="col-md-8 mb-3 mb-md-0">

                    <p class="warna-hijau fontsize-14 mb-1">
                        © {{ date('Y') }} {{ $idorg->nama_organisasi }}
                    </p>

                    <p class="warna-gray1 fontsize-12 mb-2 lineheight-17">

                        {!! $idorg->alamat !!}<br>

                        Telepon: {{ $idorg->telpon }}
                        @if ($idorg->fax)
                            | Fax: {{ $idorg->fax }}
                        @endif
                        <br>

                        Email:
                        <a href="mailto:{{ $idorg->email }}">
                            {{ $idorg->email }}
                        </a>

                    </p>

                    <ul class="header-social-icons social-icons social-icons-clean d-flex gap-2">

                        @if ($idorg->facebook)
                            <li class="social-icons-facebook">
                                <a href="{{ $idorg->facebook }}" target="_blank">
                                    <i class="fab fa-facebook-f"></i>
                                </a>
                            </li>
                        @endif

                        @if ($idorg->instagram)
                            <li class="social-icons-instagram">
                                <a href="{{ $idorg->instagram }}" target="_blank">
                                    <i class="fab fa-instagram"></i>
                                </a>
                            </li>
                        @endif

                        @if ($idorg->youtube)
                            <li class="social-icons-youtube">
                                <a href="{{ $idorg->youtube }}" target="_blank">
                                    <i class="fab fa-youtube"></i>
                                </a>
                            </li>
                        @endif

                    </ul>

                </div>


                <div class="col-md-4 text-md-end text-start">

                    <div class="app-version">
                        Sistem Informasi DEN<br>
                        Version <strong>{{ config('app.version', '3.1.1') }}</strong>
                    </div>

                </div>

            </div>

        </div>
    </div>

</footer>
