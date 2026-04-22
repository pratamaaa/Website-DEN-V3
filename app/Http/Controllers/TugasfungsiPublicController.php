<?php

namespace App\Http\Controllers;

use App\Models\Tugasfungsi;

class TugasfungsiPublicController extends Controller
{
    public function index()
    {
        $data = Tugasfungsi::first();

        return view('depan.tugasfungsi', [
            'judulhalaman' => 'Tugas dan Fungsi Setjen DEN',
            'data' => $data,
        ]);
    }
}
