<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\User;

class AdminMfaController extends Controller
{
    public function index()
    {
        $users = User::select('id', 'name', 'email', 'mfa_enabled')
            ->orderBy('name', 'asc')
            ->get();

        return view('admin.mfa.index', compact('users'));
    }

    public function reset($id)
    {
        $user = User::findOrFail($id);

        $user->mfa_secret = null;
        $user->mfa_enabled = 0;
        $user->save();

        // audit log
        AuditLog::create([
            'user_id' => auth()->id(),
            'activity' => 'Reset MFA untuk user: '.$user->email,
            'module' => 'MFA Management',
            'ip_address' => request()->ip(),
        ]);

        return redirect()
            ->route('admin.mfa.index')
            ->with('success', 'MFA user berhasil direset');
    }
}
