<?php

namespace App\Http\Controllers\dapur;

use Illuminate\Http\Request;
use App\Helpers\Gudangfungsi;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Yajra\DataTables\Facades\DataTables;

class OrganisasidenController extends Controller
{
    public function index(){
        $data['judulhalaman'] = 'Organisasi DEN';

        return view('dapur.organisasiden.index', $data);
    }

    public function getList(){
        $data = DB::table('organisasi_den')->orderBy('kategori_jabatan', 'desc')->orderBy('urutan', 'asc')->get();

        $datatable = DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('katjabatan', function($row){
                return '<p class="ndrparagraf">'.$row->kategori_jabatan.'</p>';
            })
            ->addColumn('namajabatan', function($row){
                return '<p class="ndrparagraf">'.$row->jabatan.'</p>';
            })
            ->addColumn('nama', function($row){
                return '<p class="ndrparagraf">'.$row->namalengkap.'</p>';
            })
            ->addColumn('gambar', function($row){
                if ($row->foto != ''){
                    return '<img src="'.asset('uploads/profilden/'.$row->foto).'" width="70px" style="margin-top:10px;border-radius:5px;border:1px solid #cdcdcd;">';
                }else{
                    return '<img src="'.asset('mainpro/images/no-image.jpg').'" width="70px" style="margin-top:10px;border-radius:5px;border:1px solid #cdcdcd;">';
                }
            })
            ->addColumn('isactive', function($row){
                if ($row->is_active == 'yes'){
                    $status = '<span class="badge bg-success">'.strtoupper($row->is_active).'</span>';
                }else{
                    $status = '<span class="badge bg-danger">'.strtoupper($row->is_active).'</span>';
                }
            
                return '<p class="ndrparagraf">'.$status.'</p>';
            })
            ->addColumn('action', function($row){
                $actionBtn = '<button type="button" onclick="showFormedit(\''.$row->id_organisasiden.'\')" title="Edit" class="btn btn-sm waves-effect waves-light btn-info m-b-0 " style="padding-bottom: 0px;padding-top:0px;">
                                <i class="feather icon-edit-2"></i>
                            </button>
                            <button type="button" onclick="hapus(\''.$row->id_organisasiden.'\')" title="Hapus" class="btn btn-sm waves-effect waves-light btn-danger m-b-0 " style="padding-bottom: 0px;padding-top:0px;">
                                <i class="feather icon-trash-2"></i>
                            </button>';
                return $actionBtn;
            })
            ->rawColumns(['katjabatan', 'namajabatan', 'nama', 'gambar', 'isactive', 'action'])
            ->make(true);
        
        return $datatable;
    }

    public function add(){
        $data['judulmodal'] = 'Tambah Organisasi DEN';
        $data['kategori'] = DB::table('organisasiden_kategori')->orderBy('id_kategori_organisasiden', 'asc');

        return view('dapur.organisasiden.add', $data);
    }

    public function save(Request $req){
        $kategori_jabatan = $req->post('kategori_jabatan');
        $namalengkap      = $req->post('namalengkap');
        $jabatan          = $req->post('jabatan');
        $jabatan_en       = $req->post('jabatan_en');
        $periode          = $req->post('periode');
        $profil           = $req->post('profil');
        $profil_en        = $req->post('profil_en');
        $urutan           = $req->post('urutan');
        $is_active        = $req->post('is_active');

        if ($req->hasFile('gambar')){
            $file        = $req->file('gambar');
            $namafileOri = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $ekstensi    = $file->getClientOriginalExtension();
            $namagambar  = $namafileOri . '_' . time() . '.' . $ekstensi;

            // ✅ public_path() langsung nunjuk ke folder /public, tidak ada double public
            $file->move(public_path('uploads/profilden'), $namagambar);
        }else{
            $namagambar = '';
        }
        
        $data = [
            'kategori_jabatan' => $kategori_jabatan, 
            'namalengkap'      => $namalengkap,
            'jabatan'          => $jabatan,
            'jabatan_en'       => $jabatan_en,
            'periode'          => $periode,
            'foto'             => $namagambar,
            'profil'           => $profil,
            'profil_en'        => $profil_en,
            'urutan'           => $urutan,
            'is_active'        => $is_active,
            'created_at'       => date('Y-m-d H:i:s'),
        ];
        
        try{
            $simpan = DB::table('organisasi_den')->insert($data);

            if ($simpan){
                $response = ['result' => 'success', 'message' => 'Save successfully'];
            }else{
                $response = ['result' => 'failed', 'message' => 'Save failed'];
            }
        }catch(\Illuminate\Database\QueryException $e){
            $errorCode = $e->errorInfo[1];
            if ($errorCode == 1062){
                $response = ['result' => 'failed', 'message' => 'Duplicate key found.']; 
            }
        }

        return response()->json($response);
    }

    public function edit(Request $req){
        $id = $req->get('id');

        $data['judulmodal'] = 'Edit Organisasi DEN';
        $data['kategori']   = DB::table('organisasiden_kategori')->orderBy('id_kategori_organisasiden', 'asc');
        $data['data']       = DB::table('organisasi_den')->where('id_organisasiden', $id)->first();

        return view('dapur.organisasiden.edit', $data);
    }

    public function saveupdate(Request $req){
        $id_organisasiden = $req->post('id_organisasiden');
        $kategori_jabatan = $req->post('kategori_jabatan');
        $namalengkap      = $req->post('namalengkap');
        $jabatan          = $req->post('jabatan');
        $jabatan_en       = $req->post('jabatan_en');
        $periode          = $req->post('periode');
        $profil           = $req->post('profil');
        $profil_en        = $req->post('profil_en');
        $urutan           = $req->post('urutan');
        $is_active        = $req->post('is_active');

        if ($req->hasFile('gambar')){
            $file        = $req->file('gambar');
            $namafileOri = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $ekstensi    = $file->getClientOriginalExtension();
            $namagambar  = $namafileOri . '_' . time() . '.' . $ekstensi;

            // ✅ public_path() langsung nunjuk ke folder /public, tidak ada double public
            $file->move(public_path('uploads/profilden'), $namagambar);
        }else{
            $namagambar = $req->post('gambar_current');
        }
        
        $data = [
            'kategori_jabatan' => $kategori_jabatan, 
            'namalengkap'      => $namalengkap,
            'jabatan'          => $jabatan,
            'jabatan_en'       => $jabatan_en,
            'periode'          => $periode,
            'foto'             => $namagambar,
            'profil'           => $profil,
            'profil_en'        => $profil_en,
            'urutan'           => $urutan,
            'is_active'        => $is_active,
            'updated_at'       => date('Y-m-d H:i:s'),
        ];
        
        try{
            $simpan = DB::table('organisasi_den')->where('id_organisasiden', $id_organisasiden)->update($data);

            if ($simpan){
                $response = ['result' => 'success', 'message' => 'Save successfully'];
            }else{
                $response = ['result' => 'failed', 'message' => 'Save failed'];
            }
        }catch(\Illuminate\Database\QueryException $e){
            $errorCode = $e->errorInfo[1];
            if ($errorCode == 1062){
                $response = ['result' => 'failed', 'message' => 'Duplicate key found.']; 
            }
        }

        return response()->json($response);
    }

    public function delete(Request $req){
        $id = $req->post('id');

        $hapus = DB::table('organisasi_den')->where('id_organisasiden', $id)->delete();

        if ($hapus){
            $response = ['result' => 'success', 'message' => 'Deleting data successfully'];
        }else{
            $response = ['result' => 'failed', 'message' => 'Deleteting data failed'];
        }

        return response()->json($response);
    }
}