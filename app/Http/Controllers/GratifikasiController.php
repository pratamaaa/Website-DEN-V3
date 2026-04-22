<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GratifikasiController extends Controller
{
    // public function create()
    // {
    //     return view('gratifikasi'); // sesuaikan
    // }

    public function store(Request $request)
    {
        $request->validate([
            'namalengkap' => 'required|string|max:255',
            'email' => 'nullable|email',
            'file_bukti' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:2048',
        ]);

        $filePath = null;

        if ($request->hasFile('file_bukti')) {
            $filePath = $request->file('file_bukti')->store('gratifikasi', 'public');
        }

        DB::table('gratifikasi_reports')->insert([
            'namalengkap' => $request->namalengkap,
            'nomorktp' => $request->nomorktp,
            'jabatan' => $request->jabatan,
            'instansi' => $request->instansi,
            'email' => $request->email,
            'notelpon' => $request->notelpon,
            'alamat' => $request->alamat,

            'jenispenerimaan' => $request->jenispenerimaan,
            'nominal' => $request->nominal,
            'eventpenerimaan' => $request->eventpenerimaan,
            'tempat' => $request->tempat,
            'tanggal' => $request->tanggal,
            'file_bukti' => $filePath,

            'nama_pemberi' => $request->nama_pemberi,
            'pekerjaan_pemberi' => $request->pekerjaan_pemberi,
            'jabatan_pemberi' => $request->jabatan_pemberi,
            'email_pemberi' => $request->email_pemberi,
            'notelpon_pemberi' => $request->notelpon_pemberi,
            'alamat_pemberi' => $request->alamat_pemberi,
            'hubungan_pemberi' => $request->hubungan_pemberi,
            'alasan' => $request->alasan,
            'kronologi' => $request->kronologi,
            'catatan_tambahan' => $request->catatan_tambahan,

            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Data berhasil dikirim!');
    }
}
