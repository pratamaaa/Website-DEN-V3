<?php

namespace App\Http\Controllers\dapur;

use App\Helpers\Gudangfungsi;
use App\Http\Controllers\Controller;
use App\Models\Galerivideo;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class GalerivideoController extends Controller
{
    public function index()
    {
        return view('dapur.galerivideo.index', [
            'judulhalaman' => 'Galeri Video',
        ]);
    }

    public function getList()
    {
        $data = Galerivideo::orderBy('tanggal_publikasi', 'desc')->get();

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('judulvideo', function ($row) {
                return '<p class="ndrparagraf">'.$row->judul.'</p>';
            })
            ->addColumn('desk', function ($row) {
                return '<p class="ndrparagraf">'.$row->deskripsi.'</p>';
            })
            ->addColumn('youtube', function ($row) {
    return '
        <a href="https://www.youtube.com/watch?v='.$row->youtube_id.'" target="_blank"
            style="display:block; position:relative; width:160px;">
            <img src="https://img.youtube.com/vi/'.$row->youtube_id.'/mqdefault.jpg"
                style="width:160px; height:90px; object-fit:cover; border-radius:6px;">
            <div style="position:absolute; inset:0; display:flex; align-items:center; justify-content:center;">
                <div style="width:36px; height:36px; background:rgba(255,0,0,0.9); border-radius:50%; display:flex; align-items:center; justify-content:center;">
                    <div style="border-left:12px solid #fff; border-top:7px solid transparent; border-bottom:7px solid transparent; margin-left:3px;"></div>
                </div>
            </div>
        </a>';
})
            ->addColumn('berkas', function ($row) {
                return $row->file
                    ? '<a href="#"><i class="feather icon-download-cloud"></i></a>'
                    : '-';
            })
            ->addColumn('tanggalposting', function ($row) {
                return '<p class="ndrparagraf">'.Gudangfungsi::tanggalindoshort($row->tanggal_publikasi).'</p>';
            })
            ->addColumn('action', function ($row) {
                return '
                    <button type="button" onclick="showFormedit(\''.$row->id_galerivideo.'\')"
                        title="Edit" class="btn btn-sm waves-effect waves-light btn-info m-b-0"
                        style="padding-bottom:0px;padding-top:0px;">
                        <i class="feather icon-edit-2"></i>
                    </button>
                    <button type="button" onclick="hapus(\''.$row->id_galerivideo.'\')"
                        title="Hapus" class="btn btn-sm waves-effect waves-light btn-danger m-b-0"
                        style="padding-bottom:0px;padding-top:0px;">
                        <i class="feather icon-trash-2"></i>
                    </button>';
            })
            ->rawColumns(['judulvideo', 'desk', 'youtube', 'berkas', 'tanggalposting', 'action'])
            ->make(true);
    }

    public function add()
    {
        return view('dapur.galerivideo.add', [
            'judulmodal' => 'Tambah Galeri Video',
        ]);
    }

    public function save(Request $req)
    {
        try {
            $video = Galerivideo::create([
                'judul' => $req->judul_video,
                'deskripsi' => $req->deskripsi,
                'youtube_id' => $req->youtube_id,
                'tanggal_publikasi' => $req->tanggal_publikasi,
            ]);

            audit_log('Tambah Galeri Video: '.$video->judul, 'GaleriVideo');

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
        $data = Galerivideo::findOrFail($req->id);

        return view('dapur.galerivideo.edit', [
            'judulmodal' => 'Edit Galeri Video',
            'data' => $data,
        ]);
    }

    public function saveupdate(Request $req)
    {
        try {
            $video = Galerivideo::findOrFail($req->id_galerivideo);

            $video->update([
                'judul' => $req->judul_video,
                'deskripsi' => $req->deskripsi,
                'youtube_id' => $req->youtube_id,
                'tanggal_publikasi' => $req->tanggal_publikasi,
            ]);

            audit_log('Update Galeri Video: '.$video->judul, 'GaleriVideo');

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
        $video = Galerivideo::findOrFail($req->id);
        $judul = $video->judul;
        $video->delete();

        audit_log('Hapus Galeri Video: '.$judul, 'GaleriVideo');

        return response()->json([
            'result' => 'success',
            'message' => 'Deleting data successfully',
        ]);
    }
}
