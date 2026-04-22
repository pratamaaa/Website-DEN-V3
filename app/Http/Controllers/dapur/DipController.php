<?php

namespace App\Http\Controllers\dapur;

use App\Http\Controllers\Controller;
use App\Models\Dip;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class DipController extends Controller
{
    public function index()
    {
        return view('dapur.dip.index', [
            'judulhalaman' => 'Daftar Informasi Publik',
        ]);
    }

    public function getList()
    {
        // ✅ Pakai ->get() agar jadi Collection, bukan query builder
        // sehingga DataTables tidak ikut-ikutan modify SQL (penyebab error DT_RowIndex)
        $data = Dip::orderBy('created_at', 'asc')->get();

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('ringkasanisi', function ($row) {
                return '<p class="ndrparagraf">'.$row->ringkasan_isi.'</p>';
            })
            ->addColumn('picsatker', function ($row) {
                return '<p class="ndrparagraf">'.$row->pic_satker.'</p>';
            })
            ->addColumn('pic', function ($row) {
                return '<p class="ndrparagraf">'.$row->penanggungjawab.'</p>';
            })
            ->addColumn('waktutempat', function ($row) {
                return '<p class="ndrparagraf">'.$row->waktu_tempat.'</p>';
            })
            ->addColumn('bentukinformasi', function ($row) {
                return '<p class="ndrparagraf">'.$row->bentuk_informasi.'</p>';
            })
            ->addColumn('retensiarsip', function ($row) {
                return '<p class="ndrparagraf">'.$row->retensi_arsip.'</p>';
            })
            ->addColumn('action', function ($row) {
                return '
                    <button type="button" onclick="showFormedit(\''.$row->id_dip.'\')"
                        title="Edit" class="btn btn-sm waves-effect waves-light btn-info m-b-0"
                        style="padding-bottom:0px;padding-top:0px;">
                        <i class="feather icon-edit-2"></i>
                    </button>
                    <button type="button" onclick="hapus(\''.$row->id_dip.'\')"
                        title="Hapus" class="btn btn-sm waves-effect waves-light btn-danger m-b-0"
                        style="padding-bottom:0px;padding-top:0px;">
                        <i class="feather icon-trash-2"></i>
                    </button>';
            })
            ->rawColumns(['ringkasanisi', 'picsatker', 'pic', 'waktutempat', 'bentukinformasi', 'retensiarsip', 'action'])
            ->make(true);
    }

    public function add()
    {
        return view('dapur.dip.add', [
            'judulmodal' => 'Tambah Daftar Informasi Publik',
        ]);
    }

    public function save(Request $req)
    {
        $dip = Dip::create([
            'ringkasan_isi' => $req->ringkasan_isi,
            'pic_satker' => $req->pic_satker,
            'penanggungjawab' => $req->penanggungjawab,
            'waktu_tempat' => $req->waktu_tempat,
            'bentuk_informasi' => $req->bentuk_informasi,
            'retensi_arsip' => $req->retensi_arsip,
        ]);

        audit_log('Tambah DIP: '.$dip->ringkasan_isi, 'DIP');

        return response()->json([
            'result' => 'success',
            'message' => 'Save successfully',
        ]);
    }

    public function edit(Request $req)
    {
        $data = Dip::findOrFail($req->id);

        return view('dapur.dip.edit', [
            'judulmodal' => 'Edit Daftar Informasi Publik',
            'data' => $data,
        ]);
    }

    public function saveupdate(Request $req)
    {
        $dip = Dip::findOrFail($req->id_dip);

        $dip->update([
            'ringkasan_isi' => $req->ringkasan_isi,
            'pic_satker' => $req->pic_satker,
            'penanggungjawab' => $req->penanggungjawab,
            'waktu_tempat' => $req->waktu_tempat,
            'bentuk_informasi' => $req->bentuk_informasi,
            'retensi_arsip' => $req->retensi_arsip,
        ]);

        audit_log('Update DIP: '.$dip->ringkasan_isi, 'DIP');

        return response()->json([
            'result' => 'success',
            'message' => 'Update successfully',
        ]);
    }

    public function delete(Request $req)
    {
        $dip = Dip::findOrFail($req->id);
        $nama = $dip->ringkasan_isi;
        $dip->delete();

        audit_log('Hapus DIP: '.$nama, 'DIP');

        return response()->json([
            'result' => 'success',
            'message' => 'Deleting data successfully',
        ]);
    }
}
