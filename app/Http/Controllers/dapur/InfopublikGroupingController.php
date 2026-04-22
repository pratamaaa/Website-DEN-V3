<?php

namespace App\Http\Controllers\dapur;

use App\Helpers\Gudangfungsi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;
// use Yajra\DataTables\DataTables;

class InfopublikGroupingController extends Controller
{
    public function index(){
        $data['judulhalaman'] = 'Grouping Informasi Publik';

        return view('dapur.infopublikgrouping.index', $data);
    }

    public function getList(){
        $data = DB::table('publikasi_informasipublik')->orderBy('urutan', 'asc')->get();

        $datatable = DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('url', function($row){
                return ($row->jenis == 'menu' ? '-' : '<a href="'.$row->alamat_url.'" target="_blank">'.$row->alamat_url.'</a>');
            })
            ->addColumn('tipe', function($row){
                return '<h6><span class="badge badge-primary">'.$row->jenis.'</h6>';
            })
            ->addColumn('kelompok', function($row){
                return '<h6><span class="badge badge-primary">'.$row->group.'</h6>';
            })
            ->addColumn('isactive', function($row){
                $warna = ($row->is_active == 'yes' ? 'success' : 'danger');
                return '<h6><span class="badge badge-'.$warna.'">'.strtoupper($row->is_active).'</h6>';
                // return '<p class="ndrparagraf">'.$row->retensi_arsip.'</p>';
            })
            ->addColumn('action', function($row){
                $actionBtn = '<button type="button" onclick="showFormedit(\''.$row->id_informasipublik.'\')" title="Edit" class="btn btn-sm waves-effect waves-light btn-info m-b-0 " style="padding-bottom: 0px;padding-top:0px;">
                                <i class="feather icon-edit-2"></i>
                            </button>
                            <button type="button" onclick="hapus(\''.$row->id_informasipublik.'\')" title="Hapus" class="btn btn-sm waves-effect waves-light btn-danger m-b-0 " style="padding-bottom: 0px;padding-top:0px;">
                                <i class="feather icon-trash-2"></i>
                            </button>';
                return $actionBtn;
            })
            ->rawColumns(['url', 'tipe', 'kelompok', 'isactive', 'action'])
            ->make(true);
        
        return $datatable;
    }

    public function add(){
        $data['judulmodal'] = 'Tambah Grouping Informasi Publik';

        return view('dapur.infopublikgrouping.add', $data);
    }

    public function save(Request $req){
        $informasipublik = $req->post('informasipublik');
        $urutan = $req->post('urutan');
        $jenis = $req->post('jenis');
        $alamat_url = $req->post('alamat_url');
        $group = $req->post('group');
        $is_active = ($req->post('is_active') != "" ? 'yes' : 'no');

        $data = ['informasipublik' => $informasipublik, 
                 'urutan' => $urutan,
                 'jenis' => $jenis,
                 'alamat_url' => $alamat_url,
                 'group' => $group,
                 'is_active' => $is_active,
                ];
        $simpan = DB::table('publikasi_informasipublik')->insert($data);

        if ($simpan){
            $response = ['result'=>'success', 'message'=>'Save successfully'];
        }else{
            $response = ['result'=>'failed', 'message'=>'Save failed'];
        }

        return response()->json($response);
    }

    public function edit(Request $req){
        $id = $req->get('id');

        $data['judulmodal'] = 'Edit Grouping Informasi Publik';
        $data['data'] = DB::table('publikasi_informasipublik')->where('id_informasipublik', $id)->first();

        return view('dapur.infopublikgrouping.edit', $data);
    }

    public function saveupdate(Request $req){
        $id_informasipublik = $req->post('id_informasipublik');
        $informasipublik = $req->post('informasipublik');
        $urutan = $req->post('urutan');
        $jenis = $req->post('jenis');
        $alamat_url = $req->post('alamat_url');
        $group = $req->post('group');
        $is_active = ($req->post('is_active') != "" ? 'yes' : 'no');

        $data = ['informasipublik' => $informasipublik, 
                 'urutan' => $urutan,
                 'jenis' => $jenis,
                 'alamat_url' => $alamat_url,
                 'group' => $group,
                 'is_active' => $is_active,
                ];
        $simpan = DB::table('publikasi_informasipublik')->where('id_informasipublik', $id_informasipublik)->update($data);

        if ($simpan){
            $response = ['result'=>'success', 'message'=>'Save successfully'];
        }else{
            $response = ['result'=>'failed', 'message'=>'Save failed'];
        }

        return response()->json($response);
    }

    public function delete(Request $req){
        $id = $req->post('id');
        
        $hapus = DB::table('publikasi_informasipublik')->where('id_informasipublik', $id)->delete();

        if ($hapus){
            $response = ['result'=>'success', 'message'=>'Deleting data successfully'];
        }else{
            $response = ['result'=>'failed', 'message'=>'Deleteting data failed'];
        }

        return response()->json($response);
    }
}
