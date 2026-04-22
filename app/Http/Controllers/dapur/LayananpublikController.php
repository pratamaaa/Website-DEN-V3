<?php

namespace App\Http\Controllers\dapur;

use App\Http\Controllers\Controller;
use App\Models\Layananpublik;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class LayananpublikController extends Controller
{
    public function index()
    {
        return view('dapur.layananpublik.index', [
            'judulhalaman' => 'Layanan Publik',
        ]);
    }

    public function icons()
    {
        return view('dapur.layananpublik.icons', [
            'judulhalaman' => 'Daftar Icons',
        ]);
    }

    public function getList()
    {
        $data = Layananpublik::orderBy('urutan', 'asc')->get();

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('layananpublik', function ($row) {
                return '<p class="ndrparagraf">'.$row->nama_layananpublik.'</p>';
            })
            ->addColumn('desk', function ($row) {
                return $row->deskripsi != ''
                    ? '<p class="ndrparagraf">'.$row->deskripsi.'</p>'
                    : '-';
            })
            ->addColumn('gambaricon', function ($row) {
                return '<i class="icon-'.$row->icon.' icons" fa-2x"></i>';
            })
            ->addColumn('alamaturl', function ($row) {
                return '<p class="ndrparagraf">'.$row->alamat_url.'</p>';
            })
            ->addColumn('nourut', function ($row) {
                return '<p class="ndrparagraf">'.$row->urutan.'</p>';
            })
            ->addColumn('action', function ($row) {
                return '
                    <button type="button" onclick="showFormedit(\''.$row->id_layananpublik.'\')"
                        title="Edit" class="btn btn-sm waves-effect waves-light btn-info m-b-0"
                        style="padding-bottom:0px;padding-top:0px;">
                        <i class="feather icon-edit-2"></i>
                    </button>
                    <button type="button" onclick="hapus(\''.$row->id_layananpublik.'\')"
                        title="Hapus" class="btn btn-sm waves-effect waves-light btn-danger m-b-0"
                        style="padding-bottom:0px;padding-top:0px;">
                        <i class="feather icon-trash-2"></i>
                    </button>';
            })
            ->rawColumns(['layananpublik', 'desk', 'gambaricon', 'alamaturl', 'nourut', 'action'])
            ->make(true);
    }

    public function add()
    {
        return view('dapur.layananpublik.add', [
            'judulmodal' => 'Tambah Layanan Publik',
        ]);
    }

    public function save(Request $req)
    {
        try {
            $layanan = Layananpublik::create([
                'nama_layananpublik' => $req->nama_layananpublik,
                'nama_layananpublik_en' => $req->nama_layananpublik_en,
                'deskripsi' => $req->deskripsi,
                'deskripsi_en' => $req->deskripsi_en,
                'icon' => $req->icon,
                'alamat_url' => $req->alamat_url,
                'urutan' => $req->urutan,
            ]);

            audit_log('Tambah Layanan Publik: '.$layanan->nama_layananpublik, 'LayananPublik');

            return response()->json([
                'result' => 'success',
                'message' => 'Save successfully',
            ]);

        } catch (\Illuminate\Database\QueryException $e) {
            if ($e->errorInfo[1] == 1062) {
                return response()->json([
                    'result' => 'failed',
                    'message' => 'Duplicate key found.',
                ]);
            }

            return response()->json([
                'result' => 'failed',
                'message' => 'Save failed.',
            ]);
        }
    }

    public function edit(Request $req)
    {
        $data = Layananpublik::findOrFail($req->id);

        return view('dapur.layananpublik.edit', [
            'judulmodal' => 'Edit Layanan Publik',
            'data' => $data,
        ]);
    }

    public function saveupdate(Request $req)
    {
        try {
            $layanan = Layananpublik::findOrFail($req->id_layananpublik);

            $layanan->update([
                'nama_layananpublik' => $req->nama_layananpublik,
                'nama_layananpublik_en' => $req->nama_layananpublik_en,
                'deskripsi' => $req->deskripsi,
                'deskripsi_en' => $req->deskripsi_en,
                'icon' => $req->icon,
                'alamat_url' => $req->alamat_url,
                'urutan' => $req->urutan,
            ]);

            audit_log('Update Layanan Publik: '.$layanan->nama_layananpublik, 'LayananPublik');

            return response()->json([
                'result' => 'success',
                'message' => 'Update successfully',
            ]);

        } catch (\Illuminate\Database\QueryException $e) {
            if ($e->errorInfo[1] == 1062) {
                return response()->json([
                    'result' => 'failed',
                    'message' => 'Duplicate key found.',
                ]);
            }

            return response()->json([
                'result' => 'failed',
                'message' => 'Save failed.',
            ]);
        }
    }

    public function delete(Request $req)
    {
        $layanan = Layananpublik::findOrFail($req->id);
        $nama = $layanan->nama_layananpublik;
        $layanan->delete();

        audit_log('Hapus Layanan Publik: '.$nama, 'LayananPublik');

        return response()->json([
            'result' => 'success',
            'message' => 'Deleting data successfully',
        ]);
    }
}
