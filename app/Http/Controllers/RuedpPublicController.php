<?php

namespace App\Http\Controllers;

use App\Models\RuedpProvinsi;
use App\Models\RuedpStatus;

class RuedpPublicController extends Controller
{
    public function index()
    {
        $statusList = RuedpStatus::withCount('provinsi')
            ->orderBy('urutan')
            ->get();

        return view('depan.ruedp', compact('statusList'));
    }

    public function mapData()
    {
        $provinsi = RuedpProvinsi::with('status')->get();

        $data = $provinsi->map(function ($p) {
            return [
                'kode' => strtoupper($p->nama_provinsi), // ✅ key = nama provinsi
                'nama' => $p->nama_provinsi,
                'status' => $p->status->nama_status ?? '-',
                'warna' => $p->status->warna ?? '#cccccc',
                'nomor_perda' => $p->nomor_perda ?? '-',
                'tanggal_update' => $p->tanggal_update
                    ? \Carbon\Carbon::parse($p->tanggal_update)->isoFormat('D MMMM YYYY')
                    : '-',
                'keterangan' => $p->keterangan ?? '-',
            ];
        });

        return response()->json($data);
    }
}
