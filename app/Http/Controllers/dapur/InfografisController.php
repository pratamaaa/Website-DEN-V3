<?php

namespace App\Http\Controllers\dapur;

use App\Helpers\Gudangfungsi;
use App\Http\Controllers\Controller;
use App\Models\Infografis;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class InfografisController extends Controller
{
    public function index()
    {
        return view('dapur.infografis.index', [
            'judulhalaman' => 'Infografis',
        ]);
    }

    public function getList()
    {
        $data = Infografis::orderBy('tanggal_publikasi', 'desc')->get();

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('judulinfografis', fn ($row) => '<p class="ndrparagraf">'.$row->judul_infografis.'</p>')
            ->addColumn('gambarsampul', function ($row) {
                $src = $row->gambar_sampul
                    ? asset('storage/infografis/'.$row->gambar_sampul)
                    : asset('storage/default-image/default-avatar.png');

                return '<img src="'.$src.'" width="80px" height="80px" style="margin-top:5px;border-radius:5px;border:1px solid #cdcdcd;object-fit:cover;">';
            })
            ->addColumn('file', function ($row) {
                if ($row->berkas != '') {
                    return '<a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#modalku"
                        onclick="showFormRead(\''.$row->id_infografis.'\', \'infografis\')">
                        <i class="feather icon-download-cloud"></i></a>';
                }
                return '-';
            })
            ->addColumn('isactive', fn ($row) => $row->is_active == 'yes'
                ? '<span class="badge bg-success">Yes</span>'
                : '<span class="badge bg-primary">No</span>'
            )
            ->addColumn('tanggalposting', fn ($row) => '<p class="ndrparagraf">'.Gudangfungsi::tanggalindoshort($row->tanggal_publikasi).'</p>')
            ->addColumn('counter', fn ($row) => '<p class="ndrparagraf">'.$row->hits.'</p>')
            ->addColumn('action', function ($row) {
                return '
                    <button type="button" onclick="showFormedit(\''.$row->id_infografis.'\')"
                        title="Edit" class="btn btn-sm waves-effect waves-light btn-info m-b-0"
                        style="padding-bottom:0px;padding-top:0px;">
                        <i class="feather icon-edit-2"></i>
                    </button>
                    <button type="button" onclick="hapus(\''.$row->id_infografis.'\')"
                        title="Hapus" class="btn btn-sm waves-effect waves-light btn-danger m-b-0"
                        style="padding-bottom:0px;padding-top:0px;">
                        <i class="feather icon-trash-2"></i>
                    </button>';
            })
            ->rawColumns(['judulinfografis', 'gambarsampul', 'file', 'isactive', 'tanggalposting', 'counter', 'action'])
            ->make(true);
    }

    public function add()
    {
        return view('dapur.infografis.add', [
            'judulmodal' => 'Tambah Infografis',
        ]);
    }

    public function save(Request $req)
    {
        try {
            $namagambar = $this->uploadFile($req, 'gambar');
            $namaberkas = $this->uploadFile($req, 'berkas');
            [$berkas, $berkas_sumber] = $this->resolveBerkas($req, $namaberkas);

            $infografis = Infografis::create([
                'judul_infografis' => $req->judul_infografis,
                'gambar_sampul'    => $namagambar,
                'berkas_sumber'    => $berkas_sumber,
                'berkas'           => $berkas,
                'is_active'        => $req->is_active != '' ? 'yes' : 'no',
                'tanggal_publikasi'=> $req->tanggal_publikasi,
            ]);

            audit_log('Tambah Infografis: '.$infografis->judul_infografis, 'Infografis');

            return response()->json(['result' => 'success', 'message' => 'Save successfully']);

        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json([
                'result'  => 'failed',
                'message' => $e->errorInfo[1] == 1062 ? 'Duplicate key found.' : 'Save failed.',
            ]);
        }
    }

    public function edit(Request $req)
    {
        return view('dapur.infografis.edit', [
            'judulmodal' => 'Edit Infografis',
            'data'       => Infografis::findOrFail($req->id),
        ]);
    }

    public function saveupdate(Request $req)
    {
        try {
            $infografis = Infografis::findOrFail($req->id_infografis);

            $namagambar = $req->hasFile('gambar')
                ? $this->uploadFile($req, 'gambar')
                : $req->gambar_current;

            $namaberkas = $req->hasFile('berkas')
                ? $this->uploadFile($req, 'berkas')
                : $req->berkas_current;

            [$berkas, $berkas_sumber] = $this->resolveBerkas($req, $namaberkas);

            $infografis->update([
                'judul_infografis' => $req->judul_infografis,
                'gambar_sampul'    => $namagambar,
                'berkas_sumber'    => $berkas_sumber,
                'berkas'           => $berkas,
                'is_active'        => $req->is_active != '' ? 'yes' : 'no',
                'tanggal_publikasi'=> $req->tanggal_publikasi,
            ]);

            audit_log('Update Infografis: '.$infografis->judul_infografis, 'Infografis');

            return response()->json(['result' => 'success', 'message' => 'Update successfully']);

        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json([
                'result'  => 'failed',
                'message' => $e->errorInfo[1] == 1062 ? 'Duplicate key found.' : 'Save failed.',
            ]);
        }
    }

    public function delete(Request $req)
    {
        $infografis = Infografis::findOrFail($req->id);

        $this->deleteFile($infografis->gambar_sampul);
        $this->deleteFile($infografis->berkas);

        $judul = $infografis->judul_infografis;
        $infografis->delete();

        audit_log('Hapus Infografis: '.$judul, 'Infografis');

        return response()->json(['result' => 'success', 'message' => 'Deleting data successfully']);
    }

    private function uploadFile(Request $req, string $fieldName): string
    {
        if ($req->hasFile($fieldName)) {
            $file        = $req->file($fieldName);
            $namafileOri = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $ekstensi    = $file->getClientOriginalExtension();
            $namafile    = $namafileOri.'_'.time().'.'.$ekstensi;

            $file->storeAs('infografis', $namafile, 'public');

            return $namafile;
        }

        return '';
    }

    private function resolveBerkas(Request $req, string $namaberkas): array
    {
        if ($req->is_internal != '') {
            return [$namaberkas, 'internal'];
        }

        return [$req->berkas_url, 'eksternal'];
    }

    private function deleteFile(?string $namafile): void
    {
        if ($namafile && Storage::disk('public')->exists('infografis/'.$namafile)) {
            Storage::disk('public')->delete('infografis/'.$namafile);
        }
    }
}