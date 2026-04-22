<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PragmaRX\Google2FA\Google2FA;

class MfaController extends Controller
{
    public function setup()
    {
        $user = Auth::user();

        if (! $user) {
            return redirect()->route('login');
        }

        $google2fa = new Google2FA;

        if (! $user->mfa_secret) {
            $user->mfa_secret = $google2fa->generateSecretKey();
            $user->save();
        }

        $qrCodeUrl = $google2fa->getQRCodeUrl(
            config('app.name'),
            $user->email,
            $user->mfa_secret
        );

        return view('auth.mfa-setup', compact('qrCodeUrl'));
    }

    public function enable(Request $request)
    {
        $request->validate([
            'otp' => 'required|digits:6',
        ]);

        $user = Auth::user();

        if (! $user || ! $user->mfa_secret) {
            return redirect()->route('mfa.setup');
        }

        $google2fa = new Google2FA;

        $valid = $google2fa->verifyKey(
            $user->mfa_secret,
            $request->otp,
            1
        );

        if (! $valid) {
            return back()->withErrors([
                'otp' => 'Kode OTP tidak valid',
            ]);
        }

        return redirect()->route('mfa.verify');
    }
}
