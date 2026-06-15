<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class SsoController extends Controller
{
    private string $casUrl = 'https://auth.esdm.go.id/cas';

    private string $sipegUrl = 'https://apic.esdm.go.id/production/prod-sandbox/sipeg';

    private string $callbackUrl;

    public function __construct()
    {
        $this->callbackUrl = env('URL_LOGIN_SSO', url('/login/sso'));
    }

    /**
     * Entry point SSO — redirect ke CAS kalau belum ada ticket
     */
    public function login(Request $request)
    {
        if ($request->has('ticket')) {
            return $this->handleTicket($request->get('ticket'));
        }

        // Belum ada ticket, redirect ke CAS login
        $redirectUrl = $this->casUrl.'/login?service='.urlencode($this->callbackUrl);

        return redirect($redirectUrl);
    }

    /**
     * Verifikasi ticket ke CAS
     */
    private function handleTicket(string $ticket)
    {
        $validateUrl = $this->casUrl.'/p3/serviceValidate?format=json'
            .'&service='.urlencode($this->callbackUrl)
            .'&ticket='.$ticket;

        $response = @file_get_contents($validateUrl, false, stream_context_create([
            'ssl' => ['verify_peer' => false, 'verify_peer_name' => false],
        ]));

        if (! $response) {
            return redirect('/login')->with('error', 'Gagal terhubung ke server SSO.');
        }

        $data = json_decode($response, true);

        // Cek response CAS
        if (! isset($data['serviceResponse'])) {
            return redirect('/login')->with('error', 'Response SSO tidak valid.');
        }

        if (isset($data['serviceResponse']['authenticationFailure'])) {
            $msg = $data['serviceResponse']['authenticationFailure']['description'] ?? 'Autentikasi gagal.';

            return redirect('/login')->with('error', $msg);
        }

        if (! isset($data['serviceResponse']['authenticationSuccess'])) {
            return redirect('/login')->with('error', 'Response SSO tidak dikenali.');
        }

        $casData = $data['serviceResponse']['authenticationSuccess']['attributes'] ?? [];
        $username = $data['serviceResponse']['authenticationSuccess']['user'] ?? null;
        $nip = $casData['sn'][0] ?? null;

        if (! $nip) {
            return redirect('/login')->with('error', 'NIP tidak ditemukan dari SSO.');
        }

        // Ambil data dari SIPEG
        $sipegData = $this->getSipegData($nip);

        // Upsert user
        $user = User::updateOrCreate(
            ['nip' => $nip],
            [
                'username' => $username ?? $nip,
                'name' => $casData['displayName'][0] ?? $sipegData['nama'] ?? $nip,
                'email' => $casData['mail'][0] ?? $sipegData['email'] ?? '',
                'kode_org' => $sipegData['kode_org'] ?? null,
                'is_active' => true,
                'last_login_at' => now(),
                // id_user_level hanya diset saat user BARU, tidak overwrite kalau sudah ada
            ]
        );

        // Set default level auditor hanya untuk user baru
        if ($user->wasRecentlyCreated) {
            $user->id_user_level = 4; // auditor
            $user->save();
        }

        // Cek user aktif
        if (! $user->is_active) {
            return redirect('/login')->with('error', 'Akun Anda tidak aktif. Hubungi administrator.');
        }

        // Login
        Auth::login($user);
        session()->regenerate();
        session(['sso_login' => true]);

        // Audit log SSO
        DB::table('audit_logs')->insert([
            'user_id' => $user->id,
            'activity' => 'LOGIN_SSO',
            'module' => 'auth',
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'created_at' => now(),
        ]);

        return redirect()->intended('/dapur');
    }

    /**
     * Ambil data pegawai dari SIPEG
     */
    private function getSipegData(string $nip): array
    {
        try {
            $response = Http::withHeaders([
                'client-id' => env('SIPEG_CLIENT_ID'),
                'client-secret' => env('SIPEG_CLIENT_SECRET'),
            ])->get($this->sipegUrl.'/sso-info', ['nip' => $nip]);

            if ($response->successful()) {
                return $response->json('data') ?? [];
            }
        } catch (\Exception $e) {
            // SIPEG gagal, lanjut saja dengan data CAS
        }

        return [];
    }

    /**
     * Logout SSO
     */
    public function logout()
    {
        Auth::logout();
        session()->invalidate();
        session()->regenerateToken();

        // Redirect ke CAS logout
        return redirect($this->casUrl.'/logout');
    }
}
