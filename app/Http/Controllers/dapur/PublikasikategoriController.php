<?php

namespace App\Http\Controllers\dapur;

use App\Helpers\Gudangfungsi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;
// use Yajra\DataTables\DataTables;

class PublikasikategoriController extends Controller
{
    public function index(){
        $data['judulhalaman'] = 'Kategori Publikasi';

        return view('dapur.publikasikategori.index', $data);
    }

    public function getParent($id_publikasi_kategori){
        $data = DB::table('publikasi_kategori')->where('id_publikasi_kategori', '=', $id_publikasi_kategori);

        if ($data->count() == 0){
            $kategori = '-';
        }else{
            $kategori = $data->first()->nama_kategori;
        }

        return $kategori;
    }

    public function getList(){
        $data = DB::table('publikasi_kategori')->orderBy('id_publikasi_kategori', 'asc')->get();

        $datatable = DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('namakategori', function($row){
                if ($row->id_parent == '0'){
                    return "<b>".$row->nama_kategori."</b>";
                }else{
                    return '<i class="feather icon-corner-down-right"></i> '.$row->nama_kategori;
                }
            })
            ->addColumn('kategoriinduk', function($row){
                return $this->getParent($row->id_parent);
            })
            ->addColumn('ispage', function($row){
                if ($row->is_page == 'yes'){
                    return '<span class="badge bg-success">Yes</span>';
                }else{
                    return '<span class="badge bg-danger">No</span>';
                }
            })
            ->addColumn('isactive', function($row){
                if ($row->is_active == 'yes'){
                    return '<span class="badge bg-success">Yes</span>';
                }else{
                    return '<span class="badge bg-danger">No</span>';
                }
            })
            ->addColumn('nourut', function($row){
                if ($row->id_parent == '0'){
                    return "<b>".$row->urutan."</b>";
                }else{
                    return '<i class="feather icon-corner-down-right"></i> '.$row->urutan;
                }
            })
            ->addColumn('action', function($row){
                $actionBtn = '<button type="button" onclick="showFormedit(\''.$row->id_publikasi_kategori.'\')" title="Edit" class="btn btn-sm waves-effect waves-light btn-info m-b-0 " style="padding-bottom: 0px;padding-top:0px;">
                                <i class="feather icon-edit-2"></i>
                            </button>
                            <button type="button" onclick="hapus(\''.$row->id_publikasi_kategori.'\')" title="Hapus" class="btn btn-sm waves-effect waves-light btn-danger m-b-0 " style="padding-bottom: 0px;padding-top:0px;">
                                <i class="feather icon-trash-2"></i>
                            </button>';
                return $actionBtn;
            })
            ->rawColumns(['namakategori','kategoriinduk','ispage', 'isactive','nourut','action'])
            ->make(true);
        
        return $datatable;
    }

    public function add(){
        $data['judulmodal'] = 'Tambah Kategori Publikasi';
        $data['kategoriinduk'] = DB::table('publikasi_kategori')->where('id_parent', '0');
        $data['groupinfopublik'] = DB::table('publikasi_informasipublik')
                                    ->where('is_active', 'yes')
                                    ->orderBy('urutan', 'asc');

        return view('dapur.publikasikategori.add', $data);
    }

    public function save(Request $req){
        $nama_kategori = $req->post('nama_kategori');
        $slug = Gudangfungsi::slug($nama_kategori);
        $is_parent = $req->post('is_parent');
        $id_parent = ($is_parent == "" ? $req->post('kategori_induk') : '0');
        $is_page = $req->post('is_page');
        $url = $req->post('url');
        $urutan = $req->post('urutan');
        $is_active = $req->post('is_active');
        $group_infopublik = $req->post('group_infopublik');

        $data = ['nama_kategori' => $nama_kategori, 
                 'slug' => $slug,
                 'id_parent' => $id_parent,
                 'is_page' => $is_page,
                 'url' => $url,
                 'urutan' => $urutan,
                 'is_active' => $is_active,
                 'id_informasipublik' => $group_infopublik,
                ];
        $simpan = DB::table('publikasi_kategori')->insert($data);

        if ($simpan){
            $response = ['result'=>'success', 'message'=>'Save successfully'];
        }else{
            $response = ['result'=>'failed', 'message'=>'Save failed'];
        }

        return response()->json($response);
    }

    public function edit(Request $req){
        $id = $req->get('id');

        $data['judulmodal'] = 'Edit Kategori Publikasi';
        $data['kategoriinduk'] = DB::table('publikasi_kategori')->where('id_parent', '0');
        $data['groupinfopublik'] = DB::table('publikasi_informasipublik')
                                    ->where('is_active', 'yes')
                                    ->orderBy('urutan', 'asc');
        $data['data'] = DB::table('publikasi_kategori')->where('id_publikasi_kategori', $id)->first();

        return view('dapur.publikasikategori.edit', $data);
    }

    public function saveupdate____(Request $req){
            $id_publikasi_kategori = $req->post('id_publikasi_kategori');
            $nama_kategori = $req->post('nama_kategori');
            $slug = Gudangfungsi::slug($nama_kategori);
            $is_parent = $req->post('is_parent');
            $id_parent = ($is_parent == "" ? $req->post('kategori_induk') : '0');
            $is_page = $req->post('is_page');
            $url = $req->post('url');
            $urutan = $req->post('urutan');
            $is_active = $req->post('is_active');

        echo 'isParent: '.$is_parent;
        $id_parent = ($is_parent == "" ? $req->post('kategori_induk') : '0');

        echo "<br>IDParent: ".$id_parent;
    }


    public function saveupdate(Request $req){
        $id_publikasi_kategori = $req->post('id_publikasi_kategori');
        $nama_kategori = $req->post('nama_kategori');
        $slug = Gudangfungsi::slug($nama_kategori);
        $is_parent = $req->post('is_parent');
        $id_parent = ($is_parent == "" ? $req->post('kategori_induk') : '0');
        $is_page = $req->post('is_page');
        $url = $req->post('url');
        $urutan = $req->post('urutan');
        $is_active = $req->post('is_active');
        $group_infopublik = $req->post('group_infopublik');

        $data = ['nama_kategori' => $nama_kategori, 
                 'slug' => $slug,
                 'id_parent' => $id_parent,
                 'is_page' => $is_page,
                 'url' => $url,
                 'urutan' => $urutan,
                 'is_active' => $is_active,
                 'id_informasipublik' => $group_infopublik,
                ];
        $simpan = DB::table('publikasi_kategori')->where('id_publikasi_kategori', $id_publikasi_kategori)->update($data);

        if ($simpan){
            $response = ['result'=>'success', 'message'=>'Save successfully'];
        }else{
            $response = ['result'=>'failed', 'message'=>'Save failed'];
        }

        return response()->json($response);
    }

    public function delete(Request $req){
        $id = $req->post('id');
        
        $hapus = DB::table('publikasi_kategori')->where('id_publikasi_kategori', $id)->delete();

        if ($hapus){
            $response = ['result'=>'success', 'message'=>'Deleting data successfully'];
        }else{
            $response = ['result'=>'failed', 'message'=>'Deleteting data failed'];
        }

        return response()->json($response);
    }
}
