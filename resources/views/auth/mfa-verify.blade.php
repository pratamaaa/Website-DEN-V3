@extends('layout.depan.app')

@section('content')
    <div class="container d-flex justify-content-center align-items-center" style="min-height: 70vh;">
        <div class="card shadow-lg border-0" style="max-width: 420px; width: 100%; border-radius: 15px;">
            <div class="card-body p-4">

                <div class="text-center mb-4">
                    <div class="mb-3">
                        <i class="fas fa-user-shield fa-3x text-success"></i>
                    </div>
                    <h4 class="fw-bold">Verifikasi Keamanan</h4>
                    <p class="text-muted small mb-0">
                        Masukkan kode OTP dari aplikasi Authenticator Anda
                    </p>
                </div>

                @if ($errors->any())
                    <div class="alert alert-danger py-2">
                        {{ $errors->first('otp') }}
                    </div>
                @endif
                @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif
                <form method="POST" action="{{ route('mfa.verify.post') }}">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Kode OTP</label>
                        <input type="text" name="otp"
                            class="form-control form-control-lg text-center tracking-wide @error('otp') is-invalid @enderror"
                            placeholder="••••••" maxlength="6" required autofocus>
                    </div>

                    <button type="submit" class="btn btn-success w-100 py-2 fw-semibold">
                        Verifikasi
                    </button>
                </form>

            </div>
        </div>
    </div>
@endsection
