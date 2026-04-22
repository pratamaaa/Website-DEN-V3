<?php

namespace App\Http\Controllers\dapur;

use App\Helpers\Gudangfungsi;
use App\Http\Controllers\Controller;
use App\Models\Publikasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Yajra\DataTables\Facades\DataTables;

class PublikasiController extends Controller
{
    public function index()
    {
        return view('dapur.publikasi.index', [
            'judulhalaman' => 'Publikasi',
        ]);
    }

    public function getList()
    {

        $data = Publikasi::select(
            'publikasi.*',
            'publikasi_kategori.nama_kategori'
        )
            ->join('publikasi_kategori', 'publikasi.id_publikasi_kategori', '=', 'publikasi_kategori.id_publikasi_kategori')
            ->orderBy('publikasi.created_at', 'desc');

        return DataTables::of($data)

            ->addIndexColumn()

            ->addColumn('judulpublikasi', fn ($row) => '<p class="ndrparagraf">'.$row->judul_publikasi.'</p>'
            )

            ->addColumn('kategori', fn ($row) => '<p class="ndrparagraf">'.$row->nama_kategori.'</p>'
            )

            ->addColumn('tanggalposting', fn ($row) => '<p class="ndrparagraf">'.Gudangfungsi::tanggalindoshort($row->tanggal_publikasi).'</p>'
            )

            ->addColumn('counter', fn ($row) => '<p class="ndrparagraf">'.$row->hits.'</p>'
            )

            ->addColumn('filedownload', function ($row) {

                if ($row->berkas) {

                    return '<a href="javascript:void(0)"
                    data-bs-toggle="modal"
                    data-bs-target="#modalku"
                    onclick="showFormRead(\''.$row->id_publikasi.'\',\'publikasi\')">
                    <i class="feather icon-download-cloud"></i></a>';

                }

                return '-';

            })

            ->addColumn('action', function ($row) {

                return '
                <button onclick="showFormedit(\''.$row->id_publikasi.'\')" class="btn btn-sm btn-info">
                    <i class="feather icon-edit-2"></i>
                </button>

                <button onclick="hapus(\''.$row->id_publikasi.'\')" class="btn btn-sm btn-danger">
                    <i class="feather icon-trash-2"></i>
                </button>';

            })

            ->rawColumns([
                'judulpublikasi',
                'kategori',
                'tanggalposting',
                'counter',
                'filedownload',
                'action',
            ])

            ->make(true);

    }

    public function add()
    {

        return view('dapur.publikasi.add', [
            'judulmodal' => 'Tambah Publikasi',
            'kategori' => DB::table('publikasi_kategori')
                ->orderBy('nama_kategori')
                ->get(),
        ]);

    }

    public function save(Request $req)
    {

        $req->validate([
            'kategori_publikasi' => 'required',
            'judul_publikasi' => 'required|max:255',
            'tanggal_publikasi' => 'required',
        ]);

        $tanggal = Gudangfungsi::tanggal_mysql($req->tanggal_publikasi);

        $gambar = $this->uploadImage($req);

        $file = $this->uploadFile($req);

        if ($req->is_internal) {

            $berkas = $file;
            $sumber = 'internal';

        } else {

            $berkas = $req->berkas_url;
            $sumber = 'eksternal';

        }

        $publikasi = Publikasi::create([

            'id_publikasi_kategori' => $req->kategori_publikasi,
            'judul_publikasi' => $req->judul_publikasi,
            'judul_publikasi_en' => $req->judul_publikasi_en,

            'deskripsi' => $req->deskripsi,
            'deskripsi_en' => $req->deskripsi_en,

            'gambar_sampul' => $gambar,

            'berkas' => $berkas,
            'berkas_sumber' => $sumber,

            'tanggal_publikasi' => $tanggal,

            'created_at' => now(),

        ]);

        audit_log('Tambah publikasi: '.$publikasi->judul_publikasi, 'Publikasi');

        return response()->json([
            'result' => 'success',
            'message' => 'Publikasi berhasil disimpan',
        ]);

    }

    public function edit(Request $req)
    {

        $data = Publikasi::findOrFail($req->id);

        return view('dapur.publikasi.edit', [
            'judulmodal' => 'Edit Publikasi',
            'kategori' => DB::table('publikasi_kategori')->orderBy('nama_kategori')->get(),
            'data' => $data,
        ]);

    }

    public function saveupdate(Request $req)
    {

        $publikasi = Publikasi::findOrFail($req->id_publikasi);

        $gambar = $this->uploadImage($req, $req->gambar_current);

        $file = $this->uploadFile($req, $req->berkas_current);

        if ($req->is_internal) {

            $berkas = $file;
            $sumber = 'internal';

        } else {

            $berkas = $req->berkas_url;
            $sumber = 'eksternal';

        }

        $publikasi->update([

            'id_publikasi_kategori' => $req->kategori_publikasi,

            'judul_publikasi' => $req->judul_publikasi,
            'judul_publikasi_en' => $req->judul_publikasi_en,

            'deskripsi' => $req->deskripsi,
            'deskripsi_en' => $req->deskripsi_en,

            'gambar_sampul' => $gambar,

            'berkas' => $berkas,
            'berkas_sumber' => $sumber,

            'tanggal_publikasi' => $req->tanggal_publikasi,

            'updated_at' => now(),

        ]);

        audit_log('Update publikasi: '.$publikasi->judul_publikasi, 'Publikasi');

        return response()->json([
            'result' => 'success',
            'message' => 'Update successfully',
        ]);

    }

    public function delete(Request $req)
    {

        $publikasi = Publikasi::findOrFail($req->id);

        if (File::exists(public_path('uploads/publikasi-image/'.$publikasi->gambar_sampul))) {
            File::delete(public_path('uploads/publikasi-image/'.$publikasi->gambar_sampul));
        }

        if (File::exists(public_path('uploads/publikasi/'.$publikasi->berkas))) {
            File::delete(public_path('uploads/publikasi/'.$publikasi->berkas));
        }

        $judul = $publikasi->judul_publikasi;

        $publikasi->delete();

        audit_log('Hapus publikasi: '.$judul, 'Publikasi');

        return response()->json([
            'result' => 'success',
            'message' => 'Deleting data successfully',
        ]);

    }

    private function uploadImage($req, $current = null)
    {

        if ($req->hasFile('gambar')) {

            $file = $req->file('gambar');

            $name = time().'_'.$file->getClientOriginalName();

            $file->move(public_path('uploads/publikasi-image'), $name);

            return $name;

        }

        return $current;

    }

    private function uploadFile($req, $current = null)
    {

        if ($req->hasFile('berkas')) {

            $file = $req->file('berkas');

            $name = time().'_'.$file->getClientOriginalName();

            $file->move(public_path('uploads/publikasi'), $name);

            return $name;

        }

        return $current;

    }
}
