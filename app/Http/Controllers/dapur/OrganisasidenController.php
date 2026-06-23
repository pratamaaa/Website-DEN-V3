<?php

namespace App\Http\Controllers\dapur;

use App\Helpers\Gudangfungsi;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class OrganisasidenController extends Controller
{
    public function index()
    {
        return view('dapur.organisasiden.index', [
            'judulhalaman' => 'Organisasi DEN',
        ]);
    }

    public function getList()
    {
        $data = DB::table('organisasi_den')
            ->orderBy('kategori_jabatan', 'desc')
            ->orderBy('urutan', 'asc')
            ->get();

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('katjabatan', fn ($row) => '<p class="ndrparagraf">'.$row->kategori_jabatan.'</p>')
            ->addColumn('namajabatan', fn ($row) => '<p class="ndrparagraf">'.$row->jabatan.'</p>')
            ->addColumn('nama', fn ($row) => '<p class="ndrparagraf">'.$row->namalengkap.'</p>')
            ->addColumn('gambar', function ($row) {
                $src = $row->foto
                    ? asset('storage/profilden/'.$row->foto)
                    : asset('storage/default-image/default-avatar.png');

                return '<img src="'.$src.'" width="60px" height="60px" style="margin-top:5px;border-radius:50%;object-fit:cover;border:2px solid #ddd;">';
            })
            ->addColumn('isactive', function ($row) {
                $badge = $row->is_active == 'yes'
                    ? '<span class="badge bg-success">YES</span>'
                    : '<span class="badge bg-danger">NO</span>';

                return '<p class="ndrparagraf">'.$badge.'</p>';
            })
            ->addColumn('action', function ($row) {
                return '
                    <button type="button" onclick="showFormedit(\''.$row->id_organisasiden.'\')"
                        title="Edit" class="btn btn-sm waves-effect waves-light btn-info m-b-0"
                        style="padding-bottom:0px;padding-top:0px;">
                        <i class="feather icon-edit-2"></i>
                    </button>
                    <button type="button" onclick="hapus(\''.$row->id_organisasiden.'\')"
                        title="Hapus" class="btn btn-sm waves-effect waves-light btn-danger m-b-0"
                        style="padding-bottom:0px;padding-top:0px;">
                        <i class="feather icon-trash-2"></i>
                    </button>';
            })
            ->rawColumns(['katjabatan', 'namajabatan', 'nama', 'gambar', 'isactive', 'action'])
            ->make(true);
    }

    public function add()
    {
        return view('dapur.organisasiden.add', [
            'judulmodal' => 'Tambah Organisasi DEN',
            'kategori'   => DB::table('organisasiden_kategori')->orderBy('id_kategori_organisasiden', 'asc'),
        ]);
    }

    public function save(Request $req)
    {
        $namagambar = $this->uploadGambar($req);

        try {
            DB::table('organisasi_den')->insert([
                'kategori_jabatan' => $req->kategori_jabatan,
                'namalengkap'      => $req->namalengkap,
                'jabatan'          => $req->jabatan,
                'jabatan_en'       => $req->jabatan_en,
                'periode'          => $req->periode,
                'foto'             => $namagambar,
                'profil'           => $req->profil,
                'profil_en'        => $req->profil_en,
                'urutan'           => $req->urutan,
                'is_active'        => $req->is_active,
                'created_at'       => now(),
            ]);

            return response()->json(['result' => 'success', 'message' => 'Save successfully']);

        } catch (\Illuminate\Database\QueryException $e) {
            if ($e->errorInfo[1] == 1062) {
                return response()->json(['result' => 'failed', 'message' => 'Duplicate key found.']);
            }

            return response()->json(['result' => 'failed', 'message' => 'Save failed.']);
        }
    }

    public function edit(Request $req)
    {
        return view('dapur.organisasiden.edit', [
            'judulmodal' => 'Edit Organisasi DEN',
            'kategori'   => DB::table('organisasiden_kategori')->orderBy('id_kategori_organisasiden', 'asc'),
            'data'       => DB::table('organisasi_den')->where('id_organisasiden', $req->get('id'))->first(),
        ]);
    }

    public function saveupdate(Request $req)
    {
        $namagambar = $req->hasFile('gambar')
            ? $this->uploadGambar($req)
            : $req->gambar_current;

        try {
            DB::table('organisasi_den')
                ->where('id_organisasiden', $req->id_organisasiden)
                ->update([
                    'kategori_jabatan' => $req->kategori_jabatan,
                    'namalengkap'      => $req->namalengkap,
                    'jabatan'          => $req->jabatan,
                    'jabatan_en'       => $req->jabatan_en,
                    'periode'          => $req->periode,
                    'foto'             => $namagambar,
                    'profil'           => $req->profil,
                    'profil_en'        => $req->profil_en,
                    'urutan'           => $req->urutan,
                    'is_active'        => $req->is_active,
                    'updated_at'       => now(),
                ]);

            return response()->json(['result' => 'success', 'message' => 'Update successfully']);

        } catch (\Illuminate\Database\QueryException $e) {
            if ($e->errorInfo[1] == 1062) {
                return response()->json(['result' => 'failed', 'message' => 'Duplicate key found.']);
            }

            return response()->json(['result' => 'failed', 'message' => 'Save failed.']);
        }
    }

    public function delete(Request $req)
    {
        $item = DB::table('organisasi_den')->where('id_organisasiden', $req->id)->first();

        if ($item && $item->foto && Storage::disk('public')->exists('profilden/'.$item->foto)) {
            Storage::disk('public')->delete('profilden/'.$item->foto);
        }

        DB::table('organisasi_den')->where('id_organisasiden', $req->id)->delete();

        return response()->json(['result' => 'success', 'message' => 'Deleting data successfully']);
    }

    private function uploadGambar(Request $req): string
    {
        if ($req->hasFile('gambar')) {
            $file        = $req->file('gambar');
            $namafileOri = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $ekstensi    = $file->getClientOriginalExtension();
            $namagambar  = $namafileOri.'_'.time().'.'.$ekstensi;

            $file->storeAs('profilden', $namagambar, 'public');

            return $namagambar;
        }

        return '';
    }
}