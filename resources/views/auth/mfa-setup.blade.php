@extends('layout.depan.app')

@section('content')
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">

                <div class="card shadow-lg border-0 rounded-4">
                    <div class="card-body p-4 text-center">

                        <div class="mb-3">
                            <i class="fa-solid fa-user-shield fa-3x text-success"></i>
                        </div>

                        <h4 class="fw-bold mb-2">Aktivasi Multi-Factor Authentication</h4>
                        <p class="text-muted small mb-4">
                            Scan QR Code menggunakan <strong>Google Authenticator</strong> atau
                            <strong>Microsoft Authenticator</strong>
                        </p>

                        <div class="bg-light rounded-3 p-3 mb-4 d-inline-block">
                            <img src="https://api.qrserver.com/v1/create-qr-code/?size=220x220&data={{ urlencode($qrCodeUrl) }}"
                                class="img-fluid" alt="QR Code MFA">
                        </div>

                        <form method="POST" action="{{ route('mfa.enable') }}">
                            @csrf

                            <div class="mb-3 text-start">
                                <label class="form-label fw-semibold">Kode OTP</label>
                                <input type="text" name="otp"
                                    class="form-control form-control-lg text-center tracking-widest" maxlength="6"
                                    placeholder="••••••" required>
                                @error('otp')
                                    <div class="text-danger small mt-1">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <button class="btn btn-success w-100 py-2 fw-semibold">
                                Aktifkan MFA
                            </button>
                        </form>

                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
