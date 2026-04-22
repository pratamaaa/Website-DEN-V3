<?php

namespace App\Http\Controllers\dapur;

use App\Http\Controllers\Controller;
use App\Models\Tugasfungsi;
use Illuminate\Http\Request;

class TugasfungsiController extends Controller
{
    public function index()
    {
        return view('dapur.tugasfungsi.index', [
            'judulhalaman' => 'Tugas & Fungsi Setjen DEN',
            'data' => Tugasfungsi::first(),
        ]);
    }

    public function save(Request $req)
    {
        $existing = Tugasfungsi::first();

        if ($existing) {
            $existing->update([
                'konten' => $req->konten,
                'konten_en' => $req->konten_en,
            ]);
        } else {
            Tugasfungsi::create([
                'konten' => $req->konten,
                'konten_en' => $req->konten_en,
            ]);
        }

        audit_log('Update Tugas & Fungsi Setjen DEN', 'TugasFungsi');

        return response()->json([
            'result' => 'success',
            'message' => 'Konten berhasil disimpan',
        ]);
    }
}
