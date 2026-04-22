<?php

namespace App\Http\Controllers\dapur;

use App\Helpers\Gudangfungsi;
use App\Http\Controllers\Controller;
use App\Models\Infografis;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
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
            ->addColumn('judulinfografis', function ($row) {
                return '<p class="ndrparagraf">'.$row->judul_infografis.'</p>';
            })
            ->addColumn('gambarsampul', function ($row) {
                return '<img src="'.asset('uploads/infografis/'.$row->gambar_sampul).'" width="100px" style="margin-top:10px;border-radius:5px;border:1px solid #cdcdcd;">';
            })
            ->addColumn('file', function ($row) {
                if ($row->berkas != '') {
                    $cat = 'infografis';

                    return '<a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#modalku" onclick="showFormRead(\''.$row->id_infografis.'\', \''.$cat.'\')"><i class="feather icon-download-cloud"></i></a>';
                }

                return '-';
            })
            ->addColumn('isactive', function ($row) {
                return $row->is_active == 'yes'
                    ? '<span class="badge bg-success">Yes</span>'
                    : '<span class="badge bg-primary">No</span>';
            })
            ->addColumn('tanggalposting', function ($row) {
                return '<p class="ndrparagraf">'.Gudangfungsi::tanggalindoshort($row->tanggal_publikasi).'</p>';
            })
            ->addColumn('counter', function ($row) {
                return '<p class="ndrparagraf">'.$row->hits.'</p>';
            })
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
                'gambar_sampul' => $namagambar,
                'berkas_sumber' => $berkas_sumber,
                'berkas' => $berkas,
                'is_active' => $req->is_active != '' ? 'yes' : 'no',
                'tanggal_publikasi' => $req->tanggal_publikasi,
            ]);

            audit_log('Tambah Infografis: '.$infografis->judul_infografis, 'Infografis');

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
        $data = Infografis::findOrFail($req->id);

        return view('dapur.infografis.edit', [
            'judulmodal' => 'Edit Infografis',
            'data' => $data,
        ]);
    }

    public function saveupdate(Request $req)
    {
        try {
            $infografis = Infografis::findOrFail($req->id_infografis);

            // Pakai file baru jika ada upload, atau tetap file lama
            $namagambar = $req->hasFile('gambar')
                ? $this->uploadFile($req, 'gambar')
                : $req->gambar_current;

            $namaberkas = $req->hasFile('berkas')
                ? $this->uploadFile($req, 'berkas')
                : $req->berkas_current;

            [$berkas, $berkas_sumber] = $this->resolveBerkas($req, $namaberkas);

            $infografis->update([
                'judul_infografis' => $req->judul_infografis,
                'gambar_sampul' => $namagambar,
                'berkas_sumber' => $berkas_sumber,
                'berkas' => $berkas,
                'is_active' => $req->is_active != '' ? 'yes' : 'no',
                'tanggal_publikasi' => $req->tanggal_publikasi,
            ]);

            audit_log('Update Infografis: '.$infografis->judul_infografis, 'Infografis');

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
        $infografis = Infografis::findOrFail($req->id);

        // Hapus gambar sampul & berkas dari storage jika ada
        $this->deleteFile($infografis->gambar_sampul);
        $this->deleteFile($infografis->berkas);

        $judul = $infografis->judul_infografis;
        $infografis->delete();

        audit_log('Hapus Infografis: '.$judul, 'Infografis');

        return response()->json([
            'result' => 'success',
            'message' => 'Deleting data successfully',
        ]);
    }

    // ✅ Upload gambar atau berkas, return nama file
    private function uploadFile(Request $req, string $fieldName): string
    {
        if ($req->hasFile($fieldName)) {
            $file = $req->file($fieldName);
            $namafileOri = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $ekstensi = $file->getClientOriginalExtension();
            $namafile = $namafileOri.'_'.time().'.'.$ekstensi;

            $file->move('public/uploads/infografis', $namafile);

            return $namafile;
        }

        return '';
    }

    // ✅ Tentukan berkas & berkas_sumber berdasarkan is_internal
    private function resolveBerkas(Request $req, string $namaberkas): array
    {
        if ($req->is_internal != '') {
            return [$namaberkas, 'internal'];
        }

        return [$req->berkas_url, 'eksternal'];
    }

    // ✅ Hapus file dari storage jika ada
    private function deleteFile(?string $namafile): void
    {
        $path = 'public/uploads/infografis/'.$namafile;
        if ($namafile && File::exists($path)) {
            File::delete($path);
        }
    }
}
