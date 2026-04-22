<?php

use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;

if (! function_exists('audit_log')) {

    function audit_log($activity, $module = null)
    {
        $ip = request()->server('REMOTE_ADDR');
        $agent = request()->server('HTTP_USER_AGENT');

        AuditLog::create([
            'user_id' => Auth::id(),
            'activity' => $activity,
            'module' => $module,
            'ip_address' => $ip,
            'user_agent' => $agent,
            'created_at' => now(),
        ]);
    }
}
