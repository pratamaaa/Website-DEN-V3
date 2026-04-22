<?php

namespace App\Http\Controllers\dapur;

use App\Helpers\Gudangfungsi;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Yajra\DataTables\DataTables;

class BeritaController extends Controller
{
    public function index()
    {
        return view('dapur.berita.index', [
            'judulhalaman' => 'Berita',
        ]);
    }

    public function getlist()
    {
        $data = Cache::remember('berita:getlist', 300, function () {
            return DB::table('berita as br')
                ->join('berita_kategori as kat', 'br.id_berita_kategori', '=', 'kat.id_berita_kategori')
                ->join('berita_status as st', 'br.id_status_berita', '=', 'st.id_status_berita')
                ->select('br.*', 'kat.kategori_berita', 'st.status_berita')
                ->orderBy('br.created_at', 'desc')
                ->get();
        });

        return DataTables::of($data)
            ->addIndexColumn()

            ->addColumn('judulberita', fn ($row) => '<p class="ndrparagraf">'.$row->judul.'</p>')

            ->addColumn('posting', fn ($row) => '<p class="ndrparagraf">'.Gudangfungsi::tanggalindoshort($row->tanggal_publikasi).'</p>')

            ->addColumn('status', function ($row) {
                return match ($row->status_berita) {
                    'draft' => '<span class="badge bg-warning">DRAFT</span>',
                    'terbit' => '<span class="badge bg-success">TERBIT</span>',
                    default => '<span class="badge bg-danger">DITOLAK</span>',
                };
            })

            ->addColumn('action', function ($row) {
                return '
            <button onclick="showFormedit(\''.$row->id_berita.'\')" class="btn btn-sm btn-info">
                <i class="feather icon-edit-2"></i>
            </button>

            <button onclick="hapus(\''.$row->id_berita.'\')" class="btn btn-sm btn-danger">
                <i class="feather icon-trash-2"></i>
            </button>';
            })

            ->rawColumns(['judulberita', 'status', 'action'])
            ->make(true);
    }

    public function add()
    {
        return view('dapur.berita.add', [
            'judulmodal' => 'Tambah Berita',
            'kategori' => DB::table('berita_kategori')->orderBy('kategori_berita'),
            'status' => DB::table('berita_status')->orderBy('id_status_berita'),
        ]);
    }

    public function save(Request $req)
    {
        $data = $this->collectData($req);

        DB::table('berita')->insert($data);

        Cache::forget('berita:getlist');
        Cache::forget('frontend:home:index');
        Cache::forget('frontend:berita:detail:'.$data['slug']);
        Cache::forget('sidebar:recentnews');
        Cache::forget('sidebar:popularnews');

        audit_log('Tambah berita: '.$data['judul'], 'Berita');

        return response()->json([
            'result' => 'success',
            'message' => 'Save successfully',
        ]);
    }

    public function edit(Request $req)
    {
        $id = $req->get('id');

        return view('dapur.berita.edit', [
            'judulmodal' => 'Edit Berita',
            'kategori' => DB::table('berita_kategori')->orderBy('kategori_berita'),
            'status' => DB::table('berita_status')->orderBy('id_status_berita'),
            'data' => DB::table('berita')->where('id_berita', $id)->first(),
        ]);
    }

    public function saveupdate(Request $req)
    {
        $old = DB::table('berita')
            ->where('id_berita', $req->id_berita)
            ->first();

        $data = $this->collectData($req, $req->gambar_current);

        DB::table('berita')
            ->where('id_berita', $req->id_berita)
            ->update($data);

        Cache::forget('berita:getlist');
        Cache::forget('frontend:home:index');

        // clear slug lama & baru
        Cache::forget('frontend:berita:detail:'.$old->slug);
        Cache::forget('frontend:berita:detail:'.$data['slug']);

        Cache::forget('sidebar:recentnews');
        Cache::forget('sidebar:popularnews');

        audit_log('Update berita: '.$data['judul'], 'Berita');

        return response()->json([
            'result' => 'success',
            'message' => 'Update successfully',
        ]);
    }

    public function delete(Request $req)
    {
        $berita = DB::table('berita')
            ->where('id_berita', $req->id)
            ->first();

        if ($berita) {

            if ($berita->gambar && File::exists('public/uploads/berita/'.$berita->gambar)) {
                File::delete('public/uploads/berita/'.$berita->gambar);
            }

            DB::table('berita')
                ->where('id_berita', $req->id)
                ->delete();

            Cache::forget('berita:getlist');
            Cache::forget('frontend:home:index');
            Cache::forget('frontend:berita:detail:'.$berita->slug);
            Cache::forget('sidebar:recentnews');
            Cache::forget('sidebar:popularnews');

            audit_log('Hapus berita: '.$berita->judul, 'Berita');
        }

        return response()->json([
            'result' => 'success',
            'message' => 'Deleting data successfully',
        ]);
    }

    private function collectData($req, $gambarCurrent = null)
    {

        $gambar = $gambarCurrent;

        if ($req->hasFile('gambar')) {

            $file = $req->file('gambar');

            $nama = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);

            $ext = $file->getClientOriginalExtension();

            $gambar = $nama.'_'.time().'.'.$ext;

            $file->move('public/uploads/berita', $gambar);

        }

        return [

            'id_berita_kategori' => $req->kategori_berita,
            'id_pengguna' => Auth::id(),
            'judul' => $req->judul,
            'slug' => Gudangfungsi::slug($req->judul),
            'judul_en' => $req->judul_en,
            'slug_en' => Gudangfungsi::slug($req->judul_en),
            'isi_berita' => $req->isi_berita,
            'isi_berita_en' => $req->isi_berita_en,
            'gambar' => $gambar,
            'id_status_berita' => $req->status,
            'is_headline' => $req->is_headline,
            'tanggal_publikasi' => $req->tanggal_publikasi,
            'created_at' => now(),

        ];

    }
}
