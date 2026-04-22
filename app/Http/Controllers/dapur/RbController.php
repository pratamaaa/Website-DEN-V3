<?php

namespace App\Http\Controllers\dapur;

use App\Helpers\Gudangfungsi;
use App\Http\Controllers\Controller;
use App\Models\Rb;
use App\Models\Rbkategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Yajra\DataTables\Facades\DataTables;

class RbController extends Controller
{
    public function index()
    {
        return view('dapur.rb.index', [
            'judulhalaman' => 'Reformasi Birokrasi',
        ]);
    }

    public function getList()
    {
        $data = Rb::with('kategori')
            ->orderBy('created_at', 'desc')
            ->get();

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('judul', function ($row) {
                return '<p class="ndrparagraf">'.$row->judul.'</p>';
            })
            ->addColumn('judulen', function ($row) {
                return $row->judul_en != ''
                    ? '<p class="ndrparagraf">'.$row->judul_en.'</p>'
                    : '-';
            })
            ->addColumn('gambar', function ($row) {
                $src = $row->gambar_sampul != ''
                    ? asset('uploads/rb-image/'.$row->gambar_sampul)
                    : asset('uploads/default-image/sampul-rb.jpg');

                return '<img src="'.$src.'" width="70px" style="margin-top:10px;border-radius:5px;border:1px solid #cdcdcd;">';
            })
            ->addColumn('file', function ($row) {
                if ($row->berkas != '') {
                    $cat = 'reformasi-birokrasi';

                    return '<a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#modalku" onclick="showFormRead(\''.$row->id_rb.'\', \''.$cat.'\')"><i class="feather icon-download-cloud"></i></a>';
                }

                return '-';
            })
            ->addColumn('posting', function ($row) {
                return '<p class="ndrparagraf">'.Gudangfungsi::tanggalindoshort($row->tanggal_publikasi).'</p>';
            })
            ->addColumn('counter', function ($row) {
                return '<p class="ndrparagraf">'.$row->hits.'</p>';
            })
            ->addColumn('action', function ($row) {
                return '
                    <button type="button" onclick="showFormedit(\''.$row->id_rb.'\')"
                        title="Edit" class="btn btn-sm waves-effect waves-light btn-info m-b-0"
                        style="padding-bottom:0px;padding-top:0px;">
                        <i class="feather icon-edit-2"></i>
                    </button>
                    <button type="button" onclick="hapus(\''.$row->id_rb.'\')"
                        title="Hapus" class="btn btn-sm waves-effect waves-light btn-danger m-b-0"
                        style="padding-bottom:0px;padding-top:0px;">
                        <i class="feather icon-trash-2"></i>
                    </button>';
            })
            ->rawColumns(['judul', 'judulen', 'gambar', 'file', 'posting', 'counter', 'action'])
            ->make(true);
    }

    public function add()
    {
        return view('dapur.rb.add', [
            'judulmodal' => 'Tambah Reformasi Birokrasi',
            'kategori' => Rbkategori::where('slug', '<>', 'berita-rb')->orderBy('urutan', 'asc')->get(),
        ]);
    }

    public function save(Request $req)
    {
        try {
            $namagambar = $this->uploadFile($req, 'gambar', 'rb-image');
            $namaberkas = $this->uploadFile($req, 'berkas', 'rb');

            $rb = Rb::create([
                'id_rbkategori' => $req->kategori_rb,
                'judul' => $req->judul,
                'judul_en' => $req->judul_en,
                'deskripsi' => $req->deskripsi,
                'deskripsi_en' => $req->deskripsi_en,
                'gambar_sampul' => $namagambar,
                'berkas' => $namaberkas,
                'tanggal_publikasi' => $req->tanggal_publikasi,
            ]);

            audit_log('Tambah Reformasi Birokrasi: '.$rb->judul, 'RB');

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
        $data = Rb::with('kategori')->findOrFail($req->id);

        return view('dapur.rb.edit', [
            'judulmodal' => 'Edit Reformasi Birokrasi',
            'kategori' => Rbkategori::where('slug', '<>', 'berita-rb')->orderBy('urutan', 'asc')->get(),
            'data' => $data,
        ]);
    }

    public function saveupdate(Request $req)
    {
        try {
            $rb = Rb::findOrFail($req->id_rb);

            $namagambar = $req->hasFile('gambar')
                ? $this->uploadFile($req, 'gambar', 'rb-image')
                : $req->gambar_current;

            $namaberkas = $req->hasFile('berkas')
                ? $this->uploadFile($req, 'berkas', 'rb')
                : $req->berkas_current;

            $rb->update([
                'id_rbkategori' => $req->kategori_rb,
                'judul' => $req->judul,
                'judul_en' => $req->judul_en,
                'deskripsi' => $req->deskripsi,
                'deskripsi_en' => $req->deskripsi_en,
                'gambar_sampul' => $namagambar,
                'berkas' => $namaberkas,
                'tanggal_publikasi' => $req->tanggal_publikasi,
            ]);

            audit_log('Update Reformasi Birokrasi: '.$rb->judul, 'RB');

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
        $rb = Rb::findOrFail($req->id);

        // Hapus file dari storage jika ada
        $this->deleteFile($rb->gambar_sampul, 'rb-image');
        $this->deleteFile($rb->berkas, 'rb');

        $judul = $rb->judul;
        $rb->delete();

        audit_log('Hapus Reformasi Birokrasi: '.$judul, 'RB');

        return response()->json([
            'result' => 'success',
            'message' => 'Deleting data successfully',
        ]);
    }

    // ✅ Upload file ke folder tertentu, return nama file
    private function uploadFile(Request $req, string $fieldName, string $folder): string
    {
        if ($req->hasFile($fieldName)) {
            $file = $req->file($fieldName);
            $namafileOri = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $ekstensi = $file->getClientOriginalExtension();
            $namafile = $namafileOri.'_'.time().'.'.$ekstensi;

            $file->move("public/uploads/{$folder}", $namafile);

            return $namafile;
        }

        return '';
    }

    // ✅ Hapus file dari folder tertentu jika ada
    private function deleteFile(?string $namafile, string $folder): void
    {
        $path = "public/uploads/{$folder}/{$namafile}";
        if ($namafile && File::exists($path)) {
            File::delete($path);
        }
    }
}
