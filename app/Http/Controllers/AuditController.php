<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AuditController extends Controller
{
    public function dashboard()
    {
        $totalLogs = DB::table('audit_logs')->count();

        $totalLogin = DB::table('audit_logs')
            ->where('activity', 'LOGIN')
            ->count();

        $totalCrud = DB::table('audit_logs')
            ->whereIn('activity', ['CREATE', 'UPDATE', 'DELETE'])
            ->count();

        $recent = DB::table('audit_logs')
            ->orderByDesc('created_at')
            ->limit(10)
            ->get();

        return view('audit.dashboard', compact(
            'totalLogs',
            'totalLogin',
            'totalCrud',
            'recent'
        ));
    }

    public function logs(Request $request)
    {

        // $query = DB::table('audit_logs');
        $query = DB::table('audit_logs as al')
            ->leftJoin('users as u', 'al.user_id', '=', 'u.id')
            ->select(
                'al.*',
                'u.name as user_name'
            );
        // SEARCH
        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {

                $q->where('activity', 'like', "%$search%")
                    ->orWhere('module', 'like', "%$search%")
                    ->orWhere('ip_address', 'like', "%$search%");
            });
        }

        // FILTER TANGGAL MULAI
        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }

        // FILTER TANGGAL AKHIR
        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        $logs = $query
            ->orderByDesc('created_at')
            ->paginate(20)
            ->withQueryString();

        return view('audit.logs', compact('logs'));
    }
}
