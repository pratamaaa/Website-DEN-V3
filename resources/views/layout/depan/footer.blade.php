<footer id="footer" class="mt-0">
    @php
        $idorg = \App\Models\IdentitasOrganisasi::orderBy('id_identitas_organisasi', 'desc')->first();
    @endphp

    <style>
        .footer-modern {
            background: #111820;
            color: #fff;
            padding: 50px 0 20px;
        }

        .footer-modern h5 {
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 20px;
            color: #fff;
        }

        .footer-modern p,
        .footer-modern a {
            font-size: 14px;
            color: rgba(255,255,255,0.75);
            line-height: 1.8;
            text-decoration: none;
        }

        .footer-modern a:hover {
            color: #28a745;
        }

        .footer-logo {
    text-align: center;
}

.footer-logo img {
    width: 130px;
    height: auto;
    margin-bottom: 20px;
}

        .footer-org-name {
            font-size: 18px;
            font-weight: 700;
            line-height: 1.4;
        }

        .footer-social {
            display: flex;
            gap: 10px;
            list-style: none;
            padding: 0;
            margin-top: 10px;
        }

        .footer-social li {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            background: rgba(255,255,255,0.08);
            display: flex;
            align-items: center;
            justify-content: center;
            transition: 0.3s;
        }

        .footer-social li:hover {
            background: #28a745;
        }

        .footer-social li a {
            color: #fff;
        }

        .footer-divider {
            border-right: 1px solid rgba(255,255,255,0.1);
        }

        .footer-bottom {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid rgba(255,255,255,0.1);
            text-align: center;
            font-size: 13px;
            color: rgba(255,255,255,0.6);
        }

        @media(max-width:768px){
            .footer-divider{
                border-right:none;
            }
        }
    </style>

    <div class="footer-modern">
        <div class="container">
            <div class="row gy-4">

                {{-- Logo --}}
                <div class="col-lg-3 footer-divider">
                    <div class="footer-logo">
                        <img src="{{ asset('theme/img/logo/den.png') }}" alt="Logo DEN">
                    </div>
                </div>

                {{-- Alamat --}}
                <div class="col-lg-3 footer-divider">
                    <h5>Alamat</h5>
                    <p>{!! $idorg->alamat !!}</p>
                </div>

                {{-- Kontak --}}
                <div class="col-lg-3 footer-divider">
                    <h5>Kontak</h5>
                    <p>
                        Telepon: {{ $idorg->telpon }} <br>

                        @if ($idorg->fax)
                            Fax: {{ $idorg->fax }} <br>
                        @endif

                        Email:
                        <a href="mailto:{{ $idorg->email }}">
                            {{ $idorg->email }}
                        </a>
                    </p>
                </div>

                {{-- Media Sosial --}}
                <div class="col-lg-3">
                    <h5>Media Sosial</h5>

                    <ul class="footer-social">
                        @if ($idorg->facebook)
                            <li>
                                <a href="{{ $idorg->facebook }}" target="_blank">
                                    <i class="fab fa-facebook-f"></i>
                                </a>
                            </li>
                        @endif

                        @if ($idorg->instagram)
                            <li>
                                <a href="{{ $idorg->instagram }}" target="_blank">
                                    <i class="fab fa-instagram"></i>
                                </a>
                            </li>
                        @endif

                        @if ($idorg->youtube)
                            <li>
                                <a href="{{ $idorg->youtube }}" target="_blank">
                                    <i class="fab fa-youtube"></i>
                                </a>
                            </li>
                        @endif
                    </ul>
                </div>

            </div>

            {{-- Bottom --}}
            <div class="footer-bottom">
                © {{ date('Y') }} {{ $idorg->nama_organisasi }} <br>
                Version <strong>{{ config('app.version', '3.1.1') }}</strong>
            </div>
        </div>
    </div>
</footer>