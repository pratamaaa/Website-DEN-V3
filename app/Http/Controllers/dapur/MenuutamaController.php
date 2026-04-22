<?php

namespace App\Http\Controllers\dapur;

use App\Helpers\Gudangfungsi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;
// use Yajra\DataTables\DataTables;

class MenuutamaController extends Controller
{
    public function index(){
        $data['judulhalaman'] = 'Menu Utama...';

        return view('dapur.menuutama.index', $data);
    }

    public function getParent($id_menuutama){
        $data = DB::table('menu_utama')->where('id_menuutama', '=', $id_menuutama);

        if ($data->count() == 0){
            $kategori = '-';
        }else{
            $kategori = $data->first()->nama_menu;
        }

        return $kategori;
    }

    public function getList(){
        // $data = DB::table('menu_utama')->orderBy('id_menuutama', 'asc')->orderBy('id_parent', 'asc')->orderBy('urutan', 'asc')->get();
        $data = DB::table('menu_utama')->orderBy('id_menuutama', 'asc')->orderBy('urutan', 'asc')->get();

        $datatable = DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('namamenu', function($row){
                if ($row->id_parent == '0'){
                    return "<b>".$row->nama_menu."</b>";
                }else{
                    return '<i class="feather icon-corner-down-right"></i> '.$row->nama_menu;
                }
            })
            ->addColumn('menuinduk', function($row){
                return $this->getParent($row->id_parent);
            })
            ->addColumn('link', function($row){
                return '<p class="ndrparagraf">'.$row->url.'</p>';
            })
            ->addColumn('nourut', function($row){
                if ($row->id_parent == '0'){
                    return "<b>".$row->urutan."</b>";
                }else{
                    return '<i class="feather icon-corner-down-right"></i> '.$row->urutan;
                }
            })
            ->addColumn('action', function($row){
                $actionBtn = '<button type="button" onclick="showFormedit(\''.$row->id_menuutama.'\')" title="Edit" class="btn btn-sm waves-effect waves-light btn-info m-b-0 " style="padding-bottom: 0px;padding-top:0px;">
                                <i class="feather icon-edit-2"></i>
                            </button>
                            <button type="button" onclick="hapus(\''.$row->id_menuutama.'\')" title="Hapus" class="btn btn-sm waves-effect waves-light btn-danger m-b-0 " style="padding-bottom: 0px;padding-top:0px;">
                                <i class="feather icon-trash-2"></i>
                            </button>';
                return $actionBtn;
            })
            ->rawColumns(['namamenu','menuinduk','link','nourut','action'])
            ->make(true);
        
        return $datatable;
    }

    public function add(){
        $data['judulmodal'] = 'Tambah Menu Utama';
        $data['menuinduk'] = DB::table('menu_utama')->where('id_parent', '0');

        return view('dapur.menuutama.add', $data);
    }

    public function save(Request $req){
        $nama_menu = $req->post('nama_menu');
        $is_parent = $req->post('is_parent');
        $id_parent = ($is_parent == "" ? $req->post('menu_induk') : '0');
        $url = $req->post('url');
        $urutan = $req->post('urutan');

        $data = ['nama_menu' => $nama_menu, 
                 'id_parent' => $id_parent,
                 'url' => $url,
                 'urutan' => $urutan,
                ];
        $simpan = DB::table('menu_utama')->insert($data);

        if ($simpan){
            $response = ['result'=>'success', 'message'=>'Save successfully'];
        }else{
            $response = ['result'=>'failed', 'message'=>'Save failed'];
        }

        return response()->json($response);
    }

    public function edit(Request $req){
        $id = $req->get('id');

        $data['judulmodal'] = 'Edit Menu Utama';
        $data['menuinduk'] = DB::table('menu_utama')->where('id_parent', '0');
        $data['data'] = DB::table('menu_utama')->where('id_menuutama', $id)->first();

        return view('dapur.menuutama.edit', $data);
    }

    public function saveupdate(Request $req){
        $id_menuutama = $req->post('id_menuutama');
        $nama_menu = $req->post('nama_menu');
        $is_parent = $req->post('is_parent');
        $id_parent = ($is_parent == "" ? $req->post('menu_induk') : '0');
        $url = $req->post('url');
        $urutan = $req->post('urutan');

        $data = ['nama_menu' => $nama_menu, 
                 'id_parent' => $id_parent,
                 'url' => $url,
                 'urutan' => $urutan,
                ];
       
        $simpan = DB::table('menu_utama')->where('id_menuutama', $id_menuutama)->update($data);

        if ($simpan){
            $response = ['result'=>'success', 'message'=>'Save successfully'];
        }else{
            $response = ['result'=>'failed', 'message'=>'Save failed'];
        }

        return response()->json($response);
    }

    public function delete(Request $req){
        $id = $req->post('id');
        
        $hapus = DB::table('menu_utama')->where('id_menuutama', $id)->delete();

        if ($hapus){
            $response = ['result'=>'success', 'message'=>'Deleting data successfully'];
        }else{
            $response = ['result'=>'failed', 'message'=>'Deleteting data failed'];
        }

        return response()->json($response);
    }
}
