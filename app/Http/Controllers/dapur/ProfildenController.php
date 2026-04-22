<?php

namespace App\Http\Controllers\dapur;

use Illuminate\Http\Request;
use App\Helpers\Gudangfungsi;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Yajra\DataTables\Facades\DataTables;

class ProfildenController extends Controller
{
    public function index(){
        $data['judulhalaman'] = 'Profil DEN';

        return view('dapur.profilden.index', $data);
    }

    public function getList(){
        $data = DB::table('profil')->where('id_profil_kategori', '1')->orderBy('urutan', 'asc')->get();

        $datatable = DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('judul_halaman', function($row){
                return '<p class="ndrparagraf">'.$row->judul.'</p>';
            })
            ->addColumn('judul_menu', function($row){
                return '<p class="ndrparagraf">'.$row->judulmenu.'</p>';
            })
            ->addColumn('ispage', function($row){
                if ($row->is_page == 'no'){
                    $status = '<span class="badge bg-success">'.strtoupper($row->is_page).'</span>';
                }else{
                    $status = '<span class="badge bg-danger">'.strtoupper($row->is_page).'</span>';
                }

                return '<p class="ndrparagraf">'.$status.'</p>';
            })
            ->addColumn('namapage', function($row){
                if ($row->nama_page == ''){
                    return '-';
                }else{
                    return $row->nama_page;
                }
            })
            ->addColumn('action', function($row){
                $actionBtn = '<button type="button" onclick="showFormedit(\''.$row->id_profil.'\')" title="Edit" class="btn btn-sm waves-effect waves-light btn-info m-b-0 " style="padding-bottom: 0px;padding-top:0px;">
                                <i class="feather icon-edit-2"></i>
                            </button>
                            <button type="button" onclick="hapus(\''.$row->id_profil.'\')" title="Hapus" class="btn btn-sm waves-effect waves-light btn-danger m-b-0 " style="padding-bottom: 0px;padding-top:0px;">
                                <i class="feather icon-trash-2"></i>
                            </button>';
                return $actionBtn;
            })
            ->rawColumns(['judul_halaman', 'judul_menu', 'ispage', 'namapage', 'action'])
            ->make(true);
        
        return $datatable;
    }

    public function add(){
        $data['judulmodal'] = 'Tambah Profil DEN';
        $data['kategori'] = DB::table('profil_level')->where('id_profil_kategori', '1')->orderBy('id_profil_kategori', 'asc');

        return view('dapur.profilden.add', $data);
    }

    public function save(Request $req){
        $id_profil_kategori = $req->post('id_profil_kategori');
        $judul = $req->post('judul');
        $judulmenu = $req->post('judulmenu');
        $slug = Gudangfungsi::slug($judul);
        
        $judul_en = $req->post('judul_en');
        $judulmenu_en = $req->post('judulmenu_en');
        $slug_en = Gudangfungsi::slug($judul_en);

        $konten = $req->post('konten');
        $konten_en = $req->post('konten_en');
        $is_page = $req->post('is_page');
        $nama_page = $req->post('nama_page');
        $urutan = $req->post('urutan');

        $data = ['id_profil_kategori' => $id_profil_kategori, 
                 'judul' => $judul,
                 'judulmenu' => $judulmenu,
                 'slug' => $slug,
                 'judul_en' => $judul_en,
                 'judulmenu_en' => $judulmenu_en,
                 'slug_en' => $slug_en,
                 'konten' => $konten,
                 'konten_en' => $konten_en,
                 'is_page' => $is_page,
                 'nama_page' => $nama_page,
                 'urutan' => $urutan,
                 'created_at' => date('Y-m-d H:i:s'),
                ];
        
        try{
            $simpan = DB::table('profil')->insert($data);

            if ($simpan){
                $response = ['result'=>'success', 'message'=>'Save successfully'];
            }else{
                $response = ['result'=>'failed', 'message'=>'Save failed'];
            }
        }catch(\Illuminate\Database\QueryException $e){
            $errorCode = $e->errorInfo[1];
            if ($errorCode == 1062){
                $response = ['result'=>'failed', 'message'=>'Duplicate key found.']; 
            }
        }

        return response()->json($response);
    }

    public function edit(Request $req){
        $id = $req->get('id');

        $data['judulmodal'] = 'Edit Profil DEN';
        $data['kategori'] = DB::table('profil_level')->where('id_profil_kategori', '1')->orderBy('id_profil_kategori', 'asc');
        $data['data'] = DB::table('profil')
                        ->where('id_profil', $id)->first();

        return view('dapur.profilden.edit', $data);
    }

    public function saveupdate(Request $req){
        $id_profil = $req->post('id_profil');
        $id_profil_kategori = $req->post('id_profil_kategori');
        $judul = $req->post('judul');
        $judulmenu = $req->post('judulmenu');
        $slug = Gudangfungsi::slug($judul);

        $judul_en = $req->post('judul_en');
        $judulmenu_en = $req->post('judulmenu_en');
        $slug_en = Gudangfungsi::slug($judul_en);

        $konten = $req->post('konten');
        $konten_en = $req->post('konten_en');
        $is_page = $req->post('is_page');
        $nama_page = $req->post('nama_page');
        $urutan = $req->post('urutan');

        $data = ['id_profil_kategori' => $id_profil_kategori, 
                'judul' => $judul,
                'judulmenu' => $judulmenu,
                'slug' => $slug,
                'judul_en' => $judul_en,
                'judulmenu_en' => $judulmenu_en,
                'slug_en' => $slug_en,
                'konten' => $konten,
                'konten_en' => $konten_en,
                'is_page' => $is_page,
                'nama_page' => $nama_page,
                'urutan' => $urutan,
                'updated_at' => date('Y-m-d H:i:s'),
                ];
        
        try{
            $simpan = DB::table('profil')->where('id_profil', $id_profil)->update($data);

            if ($simpan){
                $response = ['result'=>'success', 'message'=>'Save successfully'];
            }else{
                $response = ['result'=>'failed', 'message'=>'Save failed'];
            }
        }catch(\Illuminate\Database\QueryException $e){
            $errorCode = $e->errorInfo[1];
            if ($errorCode == 1062){
                $response = ['result'=>'failed', 'message'=>'Duplicate key found.']; 
            }
        }

        return response()->json($response);
    }

    public function delete(Request $req){
        $id = $req->post('id');

        $hapus = DB::table('profil')->where('id_profil', $id)->delete();

        if ($hapus){
            $response = ['result'=>'success', 'message'=>'Deleting data successfully'];
        }else{
            $response = ['result'=>'failed', 'message'=>'Deleteting data failed'];
        }

        return response()->json($response);
    }
}
