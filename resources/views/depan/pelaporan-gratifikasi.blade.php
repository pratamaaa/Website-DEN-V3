@extends('layout.depan.app')

@section('content')

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&family=Merriweather:wght@700&display=swap');

        .grat-wrapper {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: #f4f6f8;
            min-height: 100vh;
        }

        /* HERO BANNER */
        .grat-hero {
            background: url('{{ asset('theme/img/ndr-banner-green.webp') }}') center/cover no-repeat;
            position: relative;
            padding: 60px 0 50px;
            overflow: hidden;
        }

        .grat-hero::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(10, 80, 40, 0.75) 0%, rgba(20, 120, 60, 0.55) 100%);
        }

        .grat-hero .container {
            position: relative;
            z-index: 1;
        }

        .grat-hero .breadcrumb {
            background: none;
            padding: 0;
            margin-bottom: 12px;
        }

        .grat-hero .breadcrumb-item a {
            color: rgba(255, 255, 255, 0.75);
            font-size: 13px;
            text-decoration: none;
        }

        .grat-hero .breadcrumb-item.active {
            color: #fff;
            font-size: 13px;
        }

        .grat-hero .breadcrumb-item+.breadcrumb-item::before {
            color: rgba(255, 255, 255, 0.5);
        }

        .grat-hero h1 {
            font-family: 'Merriweather', serif;
            font-size: 2rem;
            color: #fff;
            margin: 0;
            text-shadow: 0 2px 12px rgba(0, 0, 0, 0.2);
        }

        /* LAYOUT */
        .grat-body {
            padding: 40px 0 60px;
        }

        /* SIDEBAR */
        .grat-sidebar {
            background: #fff;
            border-radius: 14px;
            padding: 24px;
            box-shadow: 0 2px 16px rgba(0, 0, 0, 0.07);
            position: sticky;
            top: 24px;
        }

        .grat-sidebar .search-box {
            display: flex;
            gap: 8px;
            margin-bottom: 20px;
        }

        .grat-sidebar .search-box input {
            border: 1.5px solid #e0e5ec;
            border-radius: 8px;
            padding: 8px 12px;
            font-size: 13px;
            flex: 1;
            outline: none;
            transition: border-color .2s;
        }

        .grat-sidebar .search-box input:focus {
            border-color: #1a7a40;
        }

        .grat-sidebar .search-box button {
            background: #1a7a40;
            color: #fff;
            border: none;
            border-radius: 8px;
            padding: 8px 14px;
            cursor: pointer;
            transition: background .2s;
        }

        .grat-sidebar .search-box button:hover {
            background: #156233;
        }

        .grat-sidebar h6 {
            font-size: 11px;
            letter-spacing: 1.2px;
            text-transform: uppercase;
            color: #999;
            font-weight: 600;
            margin-bottom: 10px;
        }

        .grat-sidebar .nav-link {
            color: #444;
            font-size: 13.5px;
            padding: 7px 10px;
            border-radius: 7px;
            transition: all .2s;
        }

        .grat-sidebar .nav-link:hover {
            background: #eaf5ee;
            color: #1a7a40;
            padding-left: 14px;
        }

        /* FORM CARD */
        .grat-card {
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 2px 20px rgba(0, 0, 0, 0.07);
            overflow: hidden;
            margin-bottom: 20px;
        }

        .grat-card-header {
            background: linear-gradient(90deg, #1a7a40 0%, #24a857 100%);
            padding: 16px 24px;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .grat-card-header .icon {
            width: 36px;
            height: 36px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 9px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            color: #fff;
            flex-shrink: 0;
        }

        .grat-card-header h5 {
            color: #fff;
            margin: 0;
            font-size: 15px;
            font-weight: 700;
            letter-spacing: .3px;
        }

        .grat-card-header p {
            color: rgba(255, 255, 255, 0.75);
            font-size: 12px;
            margin: 0;
        }

        .grat-card-body {
            padding: 24px;
        }

        /* FORM FIELDS */
        .form-row-custom {
            display: grid;
            grid-template-columns: 220px 1fr;
            gap: 0 16px;
            align-items: center;
            padding: 10px 0;
            border-bottom: 1px solid #f0f2f5;
        }

        .form-row-custom:last-child {
            border-bottom: none;
        }

        .form-label-custom {
            font-size: 13.5px;
            font-weight: 600;
            color: #555;
        }

        .form-label-custom .req {
            color: #e53935;
            margin-left: 3px;
        }

        .form-control-custom {
            border: 1.5px solid #e0e5ec;
            border-radius: 9px;
            padding: 9px 14px;
            font-size: 13.5px;
            font-family: 'Plus Jakarta Sans', sans-serif;
            color: #333;
            width: 100%;
            outline: none;
            transition: border-color .2s, box-shadow .2s;
            background: #fafbfc;
        }

        .form-control-custom:focus {
            border-color: #1a7a40;
            box-shadow: 0 0 0 3px rgba(26, 122, 64, 0.1);
            background: #fff;
        }

        .form-control-custom::placeholder {
            color: #bbb;
        }

        /* FILE UPLOAD */
        .file-upload-area {
            border: 2px dashed #c8d6cb;
            border-radius: 10px;
            padding: 18px;
            text-align: center;
            background: #f7faf8;
            cursor: pointer;
            transition: all .2s;
            position: relative;
        }

        .file-upload-area:hover {
            border-color: #1a7a40;
            background: #edf7f1;
        }

        .file-upload-area input[type="file"] {
            position: absolute;
            inset: 0;
            opacity: 0;
            cursor: pointer;
            width: 100%;
        }

        .file-upload-area .upload-icon {
            font-size: 28px;
            color: #1a7a40;
            margin-bottom: 6px;
        }

        .file-upload-area .upload-text {
            font-size: 13px;
            color: #555;
            font-weight: 500;
        }

        .file-upload-area .upload-hint {
            font-size: 11.5px;
            color: #aaa;
            margin-top: 3px;
        }

        /* DIVIDER */
        .section-gap {
            margin-bottom: 6px;
        }

        /* ALERT */
        .alert-custom {
            background: #eaf5ee;
            border-left: 4px solid #1a7a40;
            border-radius: 8px;
            padding: 14px 18px;
            font-size: 13.5px;
            color: #1a7a40;
            margin-bottom: 20px;
        }

        /* SUBMIT */
        .btn-submit {
            background: linear-gradient(90deg, #1a7a40 0%, #24a857 100%);
            color: #fff;
            border: none;
            border-radius: 10px;
            padding: 13px 36px;
            font-size: 15px;
            font-weight: 700;
            font-family: 'Plus Jakarta Sans', sans-serif;
            cursor: pointer;
            transition: all .25s;
            box-shadow: 0 4px 14px rgba(26, 122, 64, 0.3);
            letter-spacing: .3px;
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(26, 122, 64, 0.4);
        }

        .btn-submit:active {
            transform: translateY(0);
        }

        /* PROGRESS STEPS */
        .form-steps {
            display: flex;
            align-items: center;
            margin-bottom: 28px;
            gap: 0;
        }

        .step-item {
            display: flex;
            align-items: center;
            flex: 1;
        }

        .step-circle {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: #e0e8e3;
            color: #888;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 13px;
            font-weight: 700;
            flex-shrink: 0;
        }

        .step-circle.active {
            background: #1a7a40;
            color: #fff;
        }

        .step-label {
            font-size: 12px;
            color: #999;
            margin-left: 8px;
            font-weight: 500;
        }

        .step-label.active {
            color: #1a7a40;
            font-weight: 600;
        }

        .step-line {
            flex: 1;
            height: 2px;
            background: #e0e8e3;
            margin: 0 8px;
        }

        @media (max-width: 768px) {
            .form-row-custom {
                grid-template-columns: 1fr;
                gap: 4px;
            }

            .form-steps {
                display: none;
            }

            .grat-hero h1 {
                font-size: 1.4rem;
            }
        }

        /* SUCCESS MODAL OVERLAY */
        .modal-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.55);
            backdrop-filter: blur(4px);
            z-index: 9999;
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            pointer-events: none;
            transition: opacity .3s ease;
        }

        .modal-overlay.show {
            opacity: 1;
            pointer-events: all;
        }

        .modal-box {
            background: #fff;
            border-radius: 20px;
            padding: 44px 40px 36px;
            max-width: 460px;
            width: 90%;
            text-align: center;
            box-shadow: 0 24px 64px rgba(0, 0, 0, 0.18);
            transform: translateY(30px) scale(0.97);
            transition: transform .35s cubic-bezier(.34, 1.56, .64, 1), opacity .3s ease;
            opacity: 0;
        }

        .modal-overlay.show .modal-box {
            transform: translateY(0) scale(1);
            opacity: 1;
        }

        /* ANIMATED CHECK ICON */
        .check-wrap {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #1a7a40, #24a857);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            box-shadow: 0 8px 24px rgba(26, 122, 64, 0.35);
            animation: bounceIn .5s .1s both;
        }

        @keyframes bounceIn {
            0% {
                transform: scale(0);
                opacity: 0;
            }

            60% {
                transform: scale(1.15);
            }

            100% {
                transform: scale(1);
                opacity: 1;
            }
        }

        .check-wrap i {
            font-size: 36px;
            color: #fff;
        }

        .modal-box h4 {
            font-family: 'Merriweather', serif;
            font-size: 1.25rem;
            color: #1a2e1e;
            margin-bottom: 10px;
        }

        .modal-box p {
            font-size: 13.5px;
            color: #666;
            line-height: 1.7;
            margin-bottom: 0;
        }

        /* PRIVACY BADGE */
        .privacy-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: #f0f9f4;
            border: 1.5px solid #b6dfc5;
            border-radius: 10px;
            padding: 10px 16px;
            margin: 18px 0 24px;
            font-size: 12.5px;
            color: #1a7a40;
            font-weight: 600;
        }

        .privacy-badge i {
            font-size: 16px;
        }

        .modal-close-btn {
            background: linear-gradient(90deg, #1a7a40, #24a857);
            color: #fff;
            border: none;
            border-radius: 10px;
            padding: 12px 32px;
            font-size: 14px;
            font-weight: 700;
            font-family: 'Plus Jakarta Sans', sans-serif;
            cursor: pointer;
            transition: all .2s;
            box-shadow: 0 4px 14px rgba(26, 122, 64, 0.3);
            width: 100%;
        }

        .modal-close-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(26, 122, 64, 0.4);
        }

        /* LOADING STATE BUTTON */
        .btn-submit.loading {
            opacity: .75;
            cursor: not-allowed;
            pointer-events: none;
        }

        .btn-submit .spinner {
            display: inline-block;
            width: 14px;
            height: 14px;
            border: 2px solid rgba(255, 255, 255, 0.4);
            border-top-color: #fff;
            border-radius: 50%;
            animation: spin .7s linear infinite;
            margin-right: 8px;
            vertical-align: middle;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }
    </style>

    <div class="grat-wrapper">

        {{-- HERO --}}
        <section class="grat-hero">
            <div class="container text-center">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb justify-content-center">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Gratifikasi</li>
                    </ol>
                </nav>
                <h1>Pelaporan Gratifikasi</h1>
            </div>
        </section>

        {{-- BODY --}}
        <div class="grat-body">
            <div class="container">
                <div class="row">

                    {{-- SIDEBAR --}}
                    <div class="col-lg-3 order-lg-2 mb-4 mb-lg-0">
                        <div class="grat-sidebar">
                            <form action="{{ url('/reformasi-birokrasi-cari') }}" method="post">
                                @csrf
                                <input type="hidden" name="slug" value="{{ $slug }}">
                                <div class="search-box">
                                    <input type="text" placeholder="Cari..." name="katakunci">
                                    <button type="submit"><i class="fas fa-search"></i></button>
                                </div>
                            </form>

                            @if ($kategorirb->count() != 0)
                                <h6>Menu</h6>
                                <ul class="nav flex-column">
                                    @foreach ($kategorirb->get() as $kat)
                                        <li class="nav-item">
                                            <a class="nav-link" href="{{ url('/reformasi-birokrasi/' . $kat->slug) }}">
                                                <i class="fas fa-chevron-right me-1" style="font-size:10px;"></i>
                                                {{ $kat->nama_rbkategori }}
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>
                    </div>

                    {{-- FORM --}}
                    <div class="col-lg-9 order-lg-1">

                        @if (session('success'))
                            <div class="alert-custom">
                                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                            </div>
                        @endif

                        {{-- STEP INDICATOR --}}
                        <div class="form-steps">
                            <div class="step-item">
                                <div class="step-circle active">1</div>
                                <span class="step-label active">Identitas Pelapor</span>
                            </div>
                            <div class="step-line"></div>
                            <div class="step-item">
                                <div class="step-circle">2</div>
                                <span class="step-label">Data Gratifikasi</span>
                            </div>
                            <div class="step-line"></div>
                            <div class="step-item">
                                <div class="step-circle">3</div>
                                <span class="step-label">Data Pemberi</span>
                            </div>
                        </div>

                        <form action="{{ route('gratifikasi.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            {{-- SECTION 1: IDENTITAS --}}
                            <div class="grat-card section-gap">
                                <div class="grat-card-header">
                                    <div class="icon"><i class="fas fa-user"></i></div>
                                    <div>
                                        <h5>Identitas Pelapor</h5>
                                        <p>Lengkapi data diri Anda sebagai pelapor</p>
                                    </div>
                                </div>
                                <div class="grat-card-body">
                                    @foreach ([
            'Nama Lengkap' => ['namalengkap', 'text', 'Masukkan nama lengkap', true],
            'Nomor KTP' => ['nomorktp', 'text', 'Masukkan nomor KTP', true],
            'Jabatan / Pangkat / Gol.' => ['jabatan', 'text', 'Jabatan atau pangkat', false],
            'Nama Instansi / Unit Kerja' => ['instansi', 'text', 'Nama instansi atau unit kerja', false],
            'Email' => ['email', 'email', 'contoh@email.com', false],
            'Nomor Telpon / HP' => ['notelpon', 'tel', 'Masukkan nomor telepon', false],
            'Alamat' => ['alamat', 'text', 'Alamat lengkap', false],
        ] as $label => [$name, $type, $placeholder, $required])
                                        <div class="form-row-custom">
                                            <label class="form-label-custom">
                                                {{ $label }}@if ($required)
                                                    <span class="req">*</span>
                                                @endif
                                            </label>
                                            <input type="{{ $type }}" class="form-control-custom"
                                                name="{{ $name }}" placeholder="{{ $placeholder }}"
                                                value="{{ old($name) }}" {{ $required ? 'required' : '' }}>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            {{-- SECTION 2: DATA PENERIMAAN --}}
                            <div class="grat-card section-gap">
                                <div class="grat-card-header">
                                    <div class="icon"><i class="fas fa-gift"></i></div>
                                    <div>
                                        <h5>Data Penerimaan Gratifikasi</h5>
                                        <p>Uraian detail gratifikasi yang diterima</p>
                                    </div>
                                </div>
                                <div class="grat-card-body">
                                    @foreach ([
            'Jenis Penerimaan' => ['jenispenerimaan', 'text', 'Contoh: Uang, barang, fasilitas'],
            'Harga / Nilai / Taksiran (Rp)' => ['nominal', 'text', 'Masukkan nilai estimasi'],
            'Event Penerimaan' => ['eventpenerimaan', 'text', 'Nama acara atau kejadian'],
            'Tempat' => ['tempat', 'text', 'Lokasi penerimaan'],
        ] as $label => [$name, $type, $placeholder])
                                        <div class="form-row-custom">
                                            <label class="form-label-custom">{{ $label }}</label>
                                            <input type="{{ $type }}" class="form-control-custom"
                                                name="{{ $name }}" placeholder="{{ $placeholder }}"
                                                value="{{ old($name) }}">
                                        </div>
                                    @endforeach

                                    {{-- TANGGAL --}}
                                    <div class="form-row-custom">
                                        <label class="form-label-custom">Tanggal Penerimaan</label>
                                        <input type="date" class="form-control-custom" name="tanggal"
                                            value="{{ old('tanggal') }}">
                                    </div>

                                    {{-- FILE UPLOAD --}}
                                    <div class="form-row-custom">
                                        <label class="form-label-custom">Upload Bukti</label>
                                        <div class="file-upload-area" id="uploadArea">
                                            <input type="file" name="file_bukti" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png"
                                                onchange="updateFileName(this)">
                                            <div class="upload-icon"><i class="fas fa-cloud-upload-alt"></i></div>
                                            <div class="upload-text" id="uploadText">Klik atau seret file ke sini</div>
                                            <div class="upload-hint">PDF, DOC, DOCX, JPG, PNG — Maks. 2MB</div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- SECTION 3: DATA PEMBERI --}}
                            <div class="grat-card">
                                <div class="grat-card-header">
                                    <div class="icon"><i class="fas fa-users"></i></div>
                                    <div>
                                        <h5>Data Pemberi Gratifikasi</h5>
                                        <p>Informasi mengenai pihak pemberi gratifikasi</p>
                                    </div>
                                </div>
                                <div class="grat-card-body">
                                    @foreach ([
            'Nama Pemberi' => ['nama_pemberi', 'text', 'Nama lengkap pemberi'],
            'Pekerjaan' => ['pekerjaan_pemberi', 'text', 'Pekerjaan pemberi'],
            'Jabatan' => ['jabatan_pemberi', 'text', 'Jabatan pemberi'],
            'Email' => ['email_pemberi', 'email', 'contoh@email.com'],
            'Nomor Telpon / HP' => ['notelpon_pemberi', 'tel', 'Nomor telepon pemberi'],
            'Alamat' => ['alamat_pemberi', 'text', 'Alamat lengkap pemberi'],
            'Hubungan dengan Pelapor' => ['hubungan_pemberi', 'text', 'Contoh: Rekanan, atasan, dll'],
            'Alasan Pemberian' => ['alasan', 'text', 'Alasan pemberian gratifikasi'],
            'Kronologi' => ['kronologi', 'text', 'Uraian kronologi singkat'],
            'Catatan Tambahan' => ['catatan_tambahan', 'text', 'Informasi tambahan lainnya'],
        ] as $label => [$name, $type, $placeholder])
                                        <div class="form-row-custom">
                                            <label class="form-label-custom">{{ $label }}</label>
                                            <input type="{{ $type }}" class="form-control-custom"
                                                name="{{ $name }}" placeholder="{{ $placeholder }}"
                                                value="{{ old($name) }}">
                                        </div>
                                    @endforeach

                                    <div class="pt-4 text-end">
                                        <button type="submit" class="btn-submit">
                                            <i class="fas fa-paper-plane me-2"></i>Kirim Laporan
                                        </button>
                                    </div>
                                </div>
                            </div>

                        </form>
                    </div>

                </div>
            </div>
        </div>

    </div>

    {{-- SUCCESS MODAL --}}
    <div class="modal-overlay" id="successModal">
        <div class="modal-box">
            <div class="check-wrap">
                <i class="fas fa-check"></i>
            </div>
            <h4>Laporan Berhasil Dikirim!</h4>
            <p>Terima kasih. Laporan gratifikasi Anda telah kami terima dan akan segera ditindaklanjuti.</p>

            <div class="privacy-badge">
                <i class="fas fa-shield-alt"></i>
                Data identitas pelapor dijaga kerahasiaannya
            </div>

            <p style="font-size:12px; color:#aaa; margin-bottom:20px;">
                Kami berkomitmen untuk melindungi privasi Anda sesuai ketentuan yang berlaku.
                Identitas pelapor tidak akan diungkapkan kepada pihak manapun.
            </p>

            <button class="modal-close-btn" onclick="closeModal()">
                <i class="fas fa-home me-2"></i>Kembali ke Halaman Utama
            </button>
        </div>
    </div>

    <script>
        function updateFileName(input) {
            const text = document.getElementById('uploadText');
            if (input.files && input.files[0]) {
                text.textContent = input.files[0].name;
                text.style.color = '#1a7a40';
                text.style.fontWeight = '600';
            }
        }

        // Intercept form submit → show loading → submit → show modal on success
        document.querySelector('form[action*="gratifikasi"]').addEventListener('submit', function(e) {
            e.preventDefault();

            const btn = document.querySelector('.btn-submit');
            btn.classList.add('loading');
            btn.innerHTML = '<span class="spinner"></span>Mengirim...';

            const form = this;
            const formData = new FormData(form);

            fetch(form.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(res => {
                    // Laravel biasanya redirect setelah POST — kita anggap status 200/302 = sukses
                    if (res.ok || res.redirected) {
                        showModal();
                        form.reset();
                        document.getElementById('uploadText').textContent = 'Klik atau seret file ke sini';
                        document.getElementById('uploadText').style.color = '';
                    } else {
                        // Kembalikan tombol jika ada error
                        btn.classList.remove('loading');
                        btn.innerHTML = '<i class="fas fa-paper-plane me-2"></i>Kirim Laporan';
                        alert('Terjadi kesalahan. Silakan coba lagi.');
                    }
                })
                .catch(() => {
                    // Jika server tidak support AJAX, submit biasa saja
                    form.submit();
                })
                .finally(() => {
                    btn.classList.remove('loading');
                    btn.innerHTML = '<i class="fas fa-paper-plane me-2"></i>Kirim Laporan';
                });
        });

        function showModal() {
            const overlay = document.getElementById('successModal');
            overlay.classList.add('show');
            document.body.style.overflow = 'hidden';
        }

        function closeModal() {
            const overlay = document.getElementById('successModal');
            overlay.classList.remove('show');
            document.body.style.overflow = '';
            // Scroll ke atas form
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        }

        // Tutup modal kalau klik di luar box
        document.getElementById('successModal').addEventListener('click', function(e) {
            if (e.target === this) closeModal();
        });

        // Tampilkan modal langsung jika ada session success dari server (fallback)
        @if (session('success'))
            window.addEventListener('DOMContentLoaded', () => showModal());
        @endif
    </script>

@endsection
