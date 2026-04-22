<?php

namespace App\Http\Controllers\dapur;

use App\Helpers\Gudangfungsi;
use App\Http\Controllers\Controller;
use App\Models\Rbkategori;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class RbkategoriController extends Controller
{
    public function index()
    {
        return view('dapur.rbkategori.index', [
            'judulhalaman' => 'Kategori Reformasi Birokrasi',
        ]);
    }

    public function getList()
    {
        $data = Rbkategori::orderBy('urutan', 'asc')->get();

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('namarb', function ($row) {
                return '<p class="ndrparagraf">'.$row->nama_rbkategori.'</p>';
            })
            ->addColumn('namarben', function ($row) {
                return $row->nama_rbkategori_en != ''
                    ? '<p class="ndrparagraf">'.$row->nama_rbkategori_en.'</p>'
                    : '-';
            })
            ->addColumn('slugrb', function ($row) {
                return '<p class="ndrparagraf">'.$row->slug.'</p>';
            })
            ->addColumn('urutanrb', function ($row) {
                return '<p class="ndrparagraf">'.$row->urutan.'</p>';
            })
            ->addColumn('action', function ($row) {
                return '
                    <button type="button" onclick="showFormedit(\''.$row->id_rbkategori.'\')"
                        title="Edit" class="btn btn-sm waves-effect waves-light btn-info m-b-0"
                        style="padding-bottom:0px;padding-top:0px;">
                        <i class="feather icon-edit-2"></i>
                    </button>
                    <button type="button" onclick="hapus(\''.$row->id_rbkategori.'\')"
                        title="Hapus" class="btn btn-sm waves-effect waves-light btn-danger m-b-0"
                        style="padding-bottom:0px;padding-top:0px;">
                        <i class="feather icon-trash-2"></i>
                    </button>';
            })
            ->rawColumns(['namarb', 'namarben', 'slugrb', 'urutanrb', 'action'])
            ->make(true);
    }

    public function add()
    {
        return view('dapur.rbkategori.add', [
            'judulmodal' => 'Tambah Kategori Reformasi Birokrasi',
        ]);
    }

    public function save(Request $req)
    {
        $kategori = Rbkategori::create([
            'nama_rbkategori' => $req->nama_rbkategori,
            'nama_rbkategori_en' => $req->nama_rbkategori_en,
            'slug' => Gudangfungsi::slug($req->nama_rbkategori),
            'urutan' => $req->urutan,
        ]);

        audit_log('Tambah Kategori RB: '.$kategori->nama_rbkategori, 'RBKategori');

        return response()->json([
            'result' => 'success',
            'message' => 'Save successfully',
        ]);
    }

    public function edit(Request $req)
    {
        $data = Rbkategori::findOrFail($req->id);

        return view('dapur.rbkategori.edit', [
            'judulmodal' => 'Edit Kategori Reformasi Birokrasi',
            'data' => $data,
        ]);
    }

    public function saveupdate(Request $req)
    {
        $kategori = Rbkategori::findOrFail($req->id_rbkategori);

        $kategori->update([
            'nama_rbkategori' => $req->nama_rbkategori,
            'nama_rbkategori_en' => $req->nama_rbkategori_en,
            'slug' => Gudangfungsi::slug($req->nama_rbkategori),
            'urutan' => $req->urutan,
        ]);

        audit_log('Update Kategori RB: '.$kategori->nama_rbkategori, 'RBKategori');

        return response()->json([
            'result' => 'success',
            'message' => 'Update successfully',
        ]);
    }

    public function delete(Request $req)
    {
        $kategori = Rbkategori::findOrFail($req->id);
        $nama = $kategori->nama_rbkategori;
        $kategori->delete();

        audit_log('Hapus Kategori RB: '.$nama, 'RBKategori');

        return response()->json([
            'result' => 'success',
            'message' => 'Deleting data successfully',
        ]);
    }
}
