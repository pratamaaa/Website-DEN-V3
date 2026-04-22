<?php

namespace App\Http\Controllers\dapur;

use Illuminate\Http\Request;
use App\Helpers\Gudangfungsi;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Yajra\DataTables\Facades\DataTables;

class PenggunalevelController extends Controller
{
    public function index(){
        $data['judulhalaman'] = 'Level Pengguna';

        return view('dapur.penggunalevel.index', $data);
    }

    public function getList(){
        $data = DB::table('users_level')->orderBy('id_user_level', 'asc')->get();

        $datatable = DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('userlevel', function($row){
                return '<p class="ndrparagraf">'.$row->level.'</p>';
            })
            ->addColumn('ket', function($row){
                if ($row->keterangan == ''){
                    return '-';
                }else{
                    return $row->keterangan;
                }
            })
            ->addColumn('action', function($row){
                $actionBtn = '<button type="button" onclick="showFormedit(\''.$row->id_user_level.'\')" title="Edit" class="btn btn-sm waves-effect waves-light btn-info m-b-0 " style="padding-bottom: 0px;padding-top:0px;">
                                <i class="feather icon-edit-2"></i>
                            </button>
                            <button type="button" onclick="hapus(\''.$row->id_user_level.'\')" title="Hapus" class="btn btn-sm waves-effect waves-light btn-danger m-b-0 " style="padding-bottom: 0px;padding-top:0px;">
                                <i class="feather icon-trash-2"></i>
                            </button>';
                return $actionBtn;
            })
            ->rawColumns(['userlevel', 'ket', 'action'])
            ->make(true);
        
        return $datatable;
    }

    public function add(){
        $data['judulmodal'] = 'Level Pengguna';

        return view('dapur.penggunalevel.add', $data);
    }

    public function save(Request $req){
        $level = $req->post('level');
        $keterangan = $req->post('keterangan');

        $data = ['level' => $level, 
                 'keterangan' => $keterangan,
                ];
        
        try{
            $simpan = DB::table('users_level')->insert($data);

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

        $data['judulmodal'] = 'Edit Level Pengguna';
        $data['data'] = DB::table('users_level')->where('id_user_level', $id)->first();

        return view('dapur.penggunalevel.edit', $data);
    }

    public function saveupdate(Request $req){
        $id = $req->post('id_user_level');
        $level = $req->post('level');
        $keterangan = $req->post('keterangan');

        $data = ['level' => $level, 
                'keterangan' => $keterangan,
                ];
        
        try{
            $simpan = DB::table('users_level')->where('id_user_level', $id)->update($data);

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

        $hapus = DB::table('users_level')->where('id_user_level', $id)->delete();

        if ($hapus){
            $response = ['result'=>'success', 'message'=>'Deleting data successfully'];
        }else{
            $response = ['result'=>'failed', 'message'=>'Deleteting data failed'];
        }

        return response()->json($response);
    }
}
