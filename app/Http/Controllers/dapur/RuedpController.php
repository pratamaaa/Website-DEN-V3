<?php

namespace App\Http\Controllers\dapur;

use App\Http\Controllers\Controller;
use App\Models\Ruedp;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class RuedpController extends Controller
{
    public function index()
    {
        return view('dapur.ruedp.index', [
            'judulhalaman' => 'RUED Provinsi',
        ]);
    }

    public function getList()
    {
        $data = Ruedp::orderBy('urutan', 'asc')->get();

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('statuspenyusunan', function ($row) {
                return '<p class="ndrparagraf">'.$row->status_penyusunan.'</p>';
            })
            ->addColumn('daftarprovinsi', function ($row) {
                return '<p class="ndrparagraf">'.$row->daftar_provinsi.'</p>';
            })
            ->addColumn('action', function ($row) {
                return '
                    <button type="button" onclick="showFormedit(\''.$row->id_ruedp.'\')"
                        title="Edit" class="btn btn-sm waves-effect waves-light btn-info m-b-0"
                        style="padding-bottom:0px;padding-top:0px;">
                        <i class="feather icon-edit-2"></i>
                    </button>
                    <button type="button" onclick="hapus(\''.$row->id_ruedp.'\')"
                        title="Hapus" class="btn btn-sm waves-effect waves-light btn-danger m-b-0"
                        style="padding-bottom:0px;padding-top:0px;">
                        <i class="feather icon-trash-2"></i>
                    </button>';
            })
            ->rawColumns(['statuspenyusunan', 'daftarprovinsi', 'action'])
            ->make(true);
    }

    public function add()
    {
        return view('dapur.ruedp.add', [
            'judulmodal' => 'Tambah RUED Provinsi',
        ]);
    }

    public function save(Request $req)
    {
        try {
            $ruedp = Ruedp::create([
                'status_penyusunan' => $req->status_penyusunan,
                'jumlah_provinsi' => $req->jumlah_provinsi,
                'daftar_provinsi' => $req->daftar_provinsi,
                'pertanggal' => $req->pertanggal,
                'urutan' => $req->urutan,
            ]);

            audit_log('Tambah RUED Provinsi: '.$ruedp->status_penyusunan, 'RUEDp');

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
        $data = Ruedp::findOrFail($req->id);

        return view('dapur.ruedp.edit', [
            'judulmodal' => 'Edit RUED Provinsi',
            'data' => $data,
        ]);
    }

    public function saveupdate(Request $req)
    {
        try {
            $ruedp = Ruedp::findOrFail($req->id_ruedp);

            $ruedp->update([
                'status_penyusunan' => $req->status_penyusunan,
                'jumlah_provinsi' => $req->jumlah_provinsi,
                'daftar_provinsi' => $req->daftar_provinsi,
                'pertanggal' => $req->pertanggal,
                'urutan' => $req->urutan,
            ]);

            audit_log('Update RUED Provinsi: '.$ruedp->status_penyusunan, 'RUEDp');

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
        $ruedp = Ruedp::findOrFail($req->id);
        $nama = $ruedp->status_penyusunan;
        $ruedp->delete();

        audit_log('Hapus RUED Provinsi: '.$nama, 'RUEDp');

        return response()->json([
            'result' => 'success',
            'message' => 'Deleting data successfully',
        ]);
    }
}
