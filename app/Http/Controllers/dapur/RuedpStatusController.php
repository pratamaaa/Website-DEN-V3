<?php

namespace App\Http\Controllers\dapur;

use App\Http\Controllers\Controller;
use App\Models\RuedpStatus;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class RuedpStatusController extends Controller
{
    public function index()
    {
        return view('dapur.ruedpstatus.index', [
            'judulhalaman' => 'Status RUED Provinsi',
        ]);
    }

    public function getList()
    {
        $data = RuedpStatus::withCount('provinsi')->orderBy('urutan')->get();

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('nama_col', function ($row) {
                return '<p class="ndrparagraf">'.$row->nama_status.'</p>';
            })
            ->addColumn('warna_col', function ($row) {
                return '<div style="width:30px;height:20px;background:'.$row->warna.';border-radius:4px;display:inline-block;border:1px solid #ddd;"></div> '.$row->warna;
            })
            ->addColumn('jumlah_col', function ($row) {
                return '<span class="badge bg-info">'.$row->provinsi_count.' Provinsi</span>';
            })
            ->addColumn('action', function ($row) {
                return '
                    <button type="button" onclick="showFormedit(\''.$row->id_ruedp_status.'\')"
                        title="Edit" class="btn btn-sm waves-effect waves-light btn-info m-b-0"
                        style="padding-bottom:0px;padding-top:0px;">
                        <i class="feather icon-edit-2"></i>
                    </button>
                    <button type="button" onclick="hapus(\''.$row->id_ruedp_status.'\')"
                        title="Hapus" class="btn btn-sm waves-effect waves-light btn-danger m-b-0"
                        style="padding-bottom:0px;padding-top:0px;">
                        <i class="feather icon-trash-2"></i>
                    </button>';
            })
            ->rawColumns(['nama_col', 'warna_col', 'jumlah_col', 'action'])
            ->make(true);
    }

    public function add()
    {
        return view('dapur.ruedpstatus.add', [
            'judulmodal' => 'Tambah Status',
        ]);
    }

    public function save(Request $req)
    {
        $status = RuedpStatus::create([
            'nama_status' => $req->nama_status,
            'warna' => $req->warna,
            'urutan' => $req->urutan ?? 0,
        ]);

        audit_log('Tambah Status RUED: '.$status->nama_status, 'RUEDPStatus');

        return response()->json(['result' => 'success', 'message' => 'Save successfully']);
    }

    public function edit(Request $req)
    {
        return view('dapur.ruedpstatus.edit', [
            'judulmodal' => 'Edit Status',
            'data' => RuedpStatus::findOrFail($req->id),
        ]);
    }

    public function saveupdate(Request $req)
    {
        $status = RuedpStatus::findOrFail($req->id_ruedp_status);
        $status->update([
            'nama_status' => $req->nama_status,
            'warna' => $req->warna,
            'urutan' => $req->urutan ?? 0,
        ]);

        audit_log('Update Status RUED: '.$status->nama_status, 'RUEDPStatus');

        return response()->json(['result' => 'success', 'message' => 'Update successfully']);
    }

    public function delete(Request $req)
    {
        $status = RuedpStatus::findOrFail($req->id);
        $nama = $status->nama_status;
        $status->delete();

        audit_log('Hapus Status RUED: '.$nama, 'RUEDPStatus');

        return response()->json(['result' => 'success', 'message' => 'Deleting data successfully']);
    }
}
