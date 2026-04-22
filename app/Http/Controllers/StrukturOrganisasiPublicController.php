<?php

// app/Http/Controllers/StrukturOrganisasiPublicController.php

namespace App\Http\Controllers;

use App\Models\StrukturOrganisasi;

class StrukturOrganisasiPublicController extends Controller
{
    public function index()
    {
        // Ambil top level, lalu load children rekursif
        $struktur = StrukturOrganisasi::where('id_parent', 0)
            ->where('is_active', 'yes')
            ->orderBy('urutan')
            ->with('allChildren')
            ->get();

        return view('depan.strukturorganisasi', compact('struktur'));
    }
}
