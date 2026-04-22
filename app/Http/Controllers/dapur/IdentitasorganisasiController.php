<?php

namespace App\Http\Controllers\dapur;

use Illuminate\Http\Request;
use App\Helpers\Gudangfungsi;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Yajra\DataTables\Facades\DataTables;

class IdentitasorganisasiController extends Controller
{
    public function index(){
        $data['judulhalaman'] = 'Identitas Organisasi';

        return view('dapur.identitasorganisasi.index', $data);
    }

    public function getList(){
        $data = DB::table('identitas_organisasi')->limit(1)->get();

        $datatable = DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('namaorg', function($row){
                return '<p class="ndrparagraf">'.$row->nama_organisasi.'</p>';
            })
            ->addColumn('address', function($row){
                return ($row->alamat != '' ? '<p class="ndrparagraf">'.$row->alamat.'</p>' : '-');
            })
            ->addColumn('gambar', function($row){
                if ($row->logo != ''){
                    return '<img src="'.asset('uploads/logo/'.$row->logo).'" width="70px" style="margin-top:10px;border-radius:5px;border:1px solid #cdcdcd;">';
                }else{
                    return '<img src="'.asset('mainpro/images/no-image.jpg').'" width="70px" style="margin-top:10px;border-radius:5px;border:1px solid #cdcdcd;">';
                }
            })
            ->addColumn('kontak', function($row){
                return '<p class="ndrparagraf">
                            Telp: '.$row->telpon.'<hr class="mt-1 mb-1">
                            Fax: '.$row->fax.'<hr class="mt-1 mb-1">
                            Email: '.$row->email.'
                        </p>';
            })
            ->addColumn('action', function($row){
                $actionBtn = '<button type="button" onclick="showFormedit(\''.$row->id_identitas_organisasi.'\')" title="Edit" class="btn btn-sm waves-effect waves-light btn-info m-b-0 " style="padding-bottom: 0px;padding-top:0px;">
                                <i class="feather icon-edit-2"></i>
                            </button>
                            <button type="button" onclick="hapus(\''.$row->id_identitas_organisasi.'\')" title="Hapus" class="btn btn-sm waves-effect waves-light btn-danger m-b-0 " style="padding-bottom: 0px;padding-top:0px;">
                                <i class="feather icon-trash-2"></i>
                            </button>';
                return $actionBtn;
            })
            ->rawColumns(['namaorg', 'address', 'gambar', 'kontak', 'action'])
            ->make(true);
        
        return $datatable;
    }

    public function edit(Request $req){
        $id = $req->get('id');

        $data['judulmodal'] = 'Edit Identitas Organisasi';
        $data['data'] = DB::table('identitas_organisasi')->where('id_identitas_organisasi', '1')->limit(1)->first();

        return view('dapur.identitasorganisasi.edit', $data);
    }

    public function saveupdate(Request $req){
        $id_identitas_organisasi = $req->post('id_identitas_organisasi');
        $nama_organisasi = $req->post('nama_organisasi');
        $nama_organisasi_en = $req->post('nama_organisasi_en');
        $alamat = $req->post('alamat');
        $alamat_en = $req->post('alamat_en');
        $telpon = $req->post('telpon');
        $fax = $req->post('fax');
        $email = $req->post('email');
        $instagram = $req->post('instagram');
        $facebook = $req->post('facebook');
        $twitter = $req->post('twitter');
        $youtube = $req->post('youtube');
        $logo_current = $req->post('logo_current');


        if ($req->hasFile('logo')){
            $file = $req->file('logo');
            $namafileFull = $file->getClientOriginalName();
            $namafileOri = pathinfo($namafileFull, PATHINFO_FILENAME);
            $ekstensi = $file->getClientOriginalExtension();
            $namafile = $namafileOri.'_'.time().'.'.$ekstensi;

            $file->move("public/uploads/logo", "{$namafile}");
        }else{
            $namafile = $logo_current;
        }

        $data = ['nama_organisasi' => $nama_organisasi,
                'nama_organisasi_en' => $nama_organisasi_en,
                'logo' => $namafile,
                'alamat' => $alamat,
                'alamat_en' => $alamat_en,
                'telpon' => $telpon,
                'fax' => $fax,
                'email' => $email,
                'instagram' => $instagram,
                'facebook' => $facebook,
                'twitter' => $twitter,
                'youtube' => $youtube,
                ];
        
        try{
            if (File::exists("public/uploads/logo/".$logo_current) == true){
                File::delete("public/uploads/logo/".$logo_current);
            }

            $simpan = DB::table('identitas_organisasi')->where('id_identitas_organisasi', $id_identitas_organisasi)->update($data);

            if ($simpan){
                $response = ['result'=>'success', 'message'=>'Save successfully'];
            }else{
                $response = ['result'=>'failed', 'message'=>'Save failed'];
            }
        }catch(\Illuminate\Database\QueryException $e){
            $errorCode = $e->errorInfo[1];
            if ($errorCode == 1062){
                $response = ['result'=>'failed', 'message'=>'Duplicate key found.']; 
            }else{
                $response = ['result'=>'failed', 'message'=>'Nothing'];
            }
        }

        return response()->json($response);
    }

    public function delete(Request $req){
        $id = $req->post('id');
        
        $data = DB::table('identitas_organisasi')->where('id_identitas_organisasi', $id);
        if ($data->count() != 0 ){
            $namafile = $data->first()->logo;

            if (File::exists("public/uploads/logo/".$namafile) == true){
                File::delete("public/uploads/logo/".$namafile);
            }
        }

        $dataupdate = ['nama_organisasi' => '-',
                        'nama_organisasi_en' => '-',
                        'logo' => '',
                        'alamat' => '-',
                        'alamat_en' => '-',
                        'telpon' => '-',
                        'fax' => '-',
                        'email' => '-',
                        'instagram' => '-',
                        'facebook' => '-',
                        'twitter' => '-',
                        'youtube' => '-',
                        ];
        $hapus = DB::table('identitas_organisasi')->where('id_identitas_organisasi', $id)->update($dataupdate);

        if ($hapus){
            $response = ['result'=>'success', 'message'=>'Deleting data successfully'];
        }else{
            $response = ['result'=>'failed', 'message'=>'Deleteting data failed'];
        }

        return response()->json($response);
    }

}
