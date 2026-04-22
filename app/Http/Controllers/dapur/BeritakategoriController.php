<?php

namespace App\Http\Controllers\dapur;

use App\Helpers\Gudangfungsi;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

// use Yajra\DataTables\DataTables;

class BeritakategoriController extends Controller
{
    public function index()
    {
        $data['judulhalaman'] = 'Kategori Berita';

        return view('dapur.beritakategori.index', $data);
    }

    public function getlist()
    {
        $data = DB::table('berita_kategori')->orderBy('created_at', 'asc')->get();

        $datatable = DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                $actionBtn = '<button type="button" onclick="showFormedit(\''.$row->id_berita_kategori.'\')" title="Edit" class="btn btn-sm waves-effect waves-light btn-info m-b-0 " style="padding-bottom: 0px;padding-top:0px;">
                                <i class="feather icon-edit-2"></i>
                            </button>
                            <button type="button" onclick="hapus(\''.$row->id_berita_kategori.'\')" title="Hapus" class="btn btn-sm waves-effect waves-light btn-danger m-b-0 " style="padding-bottom: 0px;padding-top:0px;">
                                <i class="feather icon-trash-2"></i>
                            </button>';

                return $actionBtn;
            })
            ->rawColumns(['action'])
            ->make(true);

        return $datatable;
    }

    public function add()
    {
        $data['judulmodal'] = 'Tambah Kategori Berita';

        return view('dapur.beritakategori.add', $data);
    }

    public function save(Request $req)
    {
        $kategori_berita = $req->post('kategori_berita');
        $kategori_berita_en = $req->post('kategori_berita_en');
        $slug = Gudangfungsi::slug($kategori_berita);

        $data = ['kategori_berita' => $kategori_berita,
            'kategori_berita_en' => $kategori_berita_en,
            'kategori_slug' => $slug,
            'created_at' => date('Y-m-d H:i:s'),
        ];
        $simpan = DB::table('berita_kategori')->insert($data);

        if ($simpan) {
            $response = ['result' => 'success', 'message' => 'Save successfully'];
        } else {
            $response = ['result' => 'failed', 'message' => 'Save failed'];
        }

        return response()->json($response);
    }

    public function edit(Request $req)
    {
        $id = $req->get('id');

        $data['judulmodal'] = 'Edit Kategori Berita';
        $data['data'] = DB::table('berita_kategori')->where('id_berita_kategori', $id)->first();

        return view('dapur.beritakategori.edit', $data);
    }

    public function saveupdate(Request $req)
    {
        $id_kategori_berita = $req->post('id_kategori_berita');
        $kategori_berita = $req->post('kategori_berita');
        $kategori_berita_en = $req->post('kategori_berita_en');
        $slug = Gudangfungsi::slug($kategori_berita);

        $data = ['kategori_berita' => $kategori_berita,
            'kategori_berita_en' => $kategori_berita_en,
            'kategori_slug' => $slug,
            'updated_at' => date('Y-m-d H:i:s'),
        ];
        $simpan = DB::table('berita_kategori')->where('id_berita_kategori', $id_kategori_berita)->update($data);

        if ($simpan) {
            $response = ['result' => 'success', 'message' => 'Save successfully'];
        } else {
            $response = ['result' => 'failed', 'message' => 'Save failed'];
        }

        return response()->json($response);
    }

    public function delete(Request $req)
    {
        $id = $req->post('id');

        $hapus = DB::table('berita_kategori')->where('id_berita_kategori', $id)->delete();

        if ($hapus) {
            $response = ['result' => 'success', 'message' => 'Deleting data successfully'];
        } else {
            $response = ['result' => 'failed', 'message' => 'Deleteting data failed'];
        }

        return response()->json($response);
    }
}
