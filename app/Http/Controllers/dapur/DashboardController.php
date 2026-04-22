<?php

namespace App\Http\Controllers\dapur;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;

class DashboardController extends Controller
{
    public function index(){
        $data['judulhalaman'] = 'Dashboard';
        $data['cberita'] = DB::table('berita')->count();
        $data['cinfografis'] = DB::table('infografis')->count();
        $data['cpublikasi'] = DB::table('publikasi')->count();
        $data['cvideo'] = DB::table('galerivideo')->count();

        return view('dapur/dashboard', $data);
        // echo "Cek: ".Session::get('sesNamalengkap').'-'.Session::get('sesLevel');
    }
}
