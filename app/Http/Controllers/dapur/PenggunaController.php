<?php

namespace App\Http\Controllers\dapur;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Yajra\DataTables\Facades\DataTables;

class PenggunaController extends Controller
{
    public function index()
    {
        $data['judulhalaman'] = 'Pengguna';

        return view('dapur.pengguna.index', $data);
    }

    public function getList()
    {
        $data = DB::table('users as us')->join('users_level as lv', 'us.id_user_level', '=', 'lv.id_user_level')
            ->orderBy('username', 'asc')->get();

        $datatable = DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('uname', function ($row) {
                return '<p class="ndrparagraf">'.$row->username.'</p>';
            })
            ->addColumn('nama', function ($row) {
                return '<p class="ndrparagraf">'.$row->name.'</p>';
            })
            ->addColumn('mail', function ($row) {
                return '<p class="ndrparagraf">'.$row->email.'</p>';
            })
            ->addColumn('ulevel', function ($row) {
                return '<p class="ndrparagraf">'.$row->level.'</p>';
            })
            ->addColumn('action', function ($row) {
                $actionBtn = '<button type="button" onclick="showFormedit(\''.$row->id.'\')" title="Edit" class="btn btn-sm waves-effect waves-light btn-info m-b-0 " style="padding-bottom: 0px;padding-top:0px;">
                                <i class="feather icon-edit-2"></i>
                            </button>
                            <button type="button" onclick="hapus(\''.$row->id.'\')" title="Hapus" class="btn btn-sm waves-effect waves-light btn-danger m-b-0 " style="padding-bottom: 0px;padding-top:0px;">
                                <i class="feather icon-trash-2"></i>
                            </button>';

                return $actionBtn;
            })
            ->rawColumns(['uname', 'nama', 'mail', 'ulevel', 'action'])
            ->make(true);

        return $datatable;
    }

    public function add()
    {
        $data['judulmodal'] = 'Tambah Pengguna';
        $data['kategori'] = DB::table('users_level')->orderBy('id_user_level', 'asc');

        return view('dapur.pengguna.add', $data);
    }

    public function save(Request $req)
    {

        $req->validate([
            'username' => 'required|string|max:50|unique:users,username',
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email',
            'id_user_level' => 'required',
            'password' => [
                'required',
                'confirmed',
                Password::min(12)
                    ->mixedCase()
                    ->numbers()
                    ->symbols(),
            ],
        ]);

        $data = [
            'username' => $req->username,
            'name' => $req->name,
            'email' => $req->email,
            'password' => Hash::make($req->password),
            'id_user_level' => $req->id_user_level,
            'created_at' => now(),
        ];

        DB::table('users')->insert($data);

        return response()->json([
            'result' => 'success',
            'message' => 'User berhasil dibuat',
        ]);
    }

    public function edit(Request $req)
    {
        $id = $req->get('id');

        $data['judulmodal'] = 'Edit Pengguna';
        $data['kategori'] = DB::table('users_level')->orderBy('id_user_level', 'asc');
        $data['data'] = DB::table('users')->where('id', $id)->first();

        return view('dapur.pengguna.edit', $data);
    }

    public function saveupdate(Request $req)
    {

        $rules = [
            'username' => 'required|string|max:50|unique:users,username,'.$req->id_pengguna,
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email,'.$req->id_pengguna,
            'id_user_level' => 'required',
        ];

        if ($req->filled('password')) {
            $rules['password'] = [
                'confirmed',
                Password::min(12)
                    ->mixedCase()
                    ->numbers()
                    ->symbols(),
            ];
        }

        $req->validate($rules);

        $data = [
            'username' => $req->username,
            'name' => $req->name,
            'email' => $req->email,
            'id_user_level' => $req->id_user_level,
            'updated_at' => now(),
        ];

        // kalau password diisi
        if ($req->filled('password')) {

            $user = DB::table('users')->where('id', $req->id_pengguna)->first();

            // ❗ cek password tidak boleh sama
            if (Hash::check($req->password, $user->password)) {
                return response()->json([
                    'result' => 'failed',
                    'message' => 'Password tidak boleh sama dengan sebelumnya',
                ]);
            }

            $data['password'] = Hash::make($req->password);
        }

        DB::table('users')->where('id', $req->id_pengguna)->update($data);

        return response()->json([
            'result' => 'success',
            'message' => 'User berhasil diupdate',
        ]);
    }

    public function delete(Request $req)
    {
        $id = $req->post('id');

        $hapus = DB::table('users')->where('id', $id)->delete();

        if ($hapus) {
            $response = ['result' => 'success', 'message' => 'Deleting data successfully'];
        } else {
            $response = ['result' => 'failed', 'message' => 'Deleteting data failed'];
        }

        return response()->json($response);
    }

    public function gantipassword(Request $req)
    {
        // dd(Auth::user()); dd(Auth::id());
        $id = Auth::id();

        $data['judulhalaman'] = 'Ganti Password Saya';

        return view('dapur.pengguna.gantipassword', $data);

        // echo "password: ".Hash::make('admin123');
    }

    public function passwordupdate(Request $req)
    {

        $req->validate([
            'password_sekarang' => 'required',
            'password_baru' => [
                'required',
                'confirmed',
                Password::min(12)
                    ->mixedCase()
                    ->numbers()
                    ->symbols(),
            ],
        ]);

        $user = Auth::user();

        // cek password lama
        if (! Hash::check($req->password_sekarang, $user->password)) {
            return response()->json([
                'result' => 'failed',
                'message' => 'Password sekarang tidak sesuai',
            ]);
        }

        // ❗ tidak boleh sama dengan sebelumnya
        if (Hash::check($req->password_baru, $user->password)) {
            return response()->json([
                'result' => 'failed',
                'message' => 'Password baru tidak boleh sama dengan sebelumnya',
            ]);
        }

        DB::table('users')->where('id', $user->id)->update([
            'password' => Hash::make($req->password_baru),
            'updated_at' => now(),
        ]);

        return response()->json([
            'result' => 'success',
            'message' => 'Password berhasil diubah',
        ]);
    }

    public function forcePasswordForm()
    {
        if (! session()->has('force_password_reset_user')) {
            return redirect()->route('login');
        }

        return view('auth.force-change-password');
    }

    public function forcePasswordUpdate(Request $req)
    {
        $req->validate([
            'password_baru' => [
                'required',
                'confirmed',
                \Illuminate\Validation\Rules\Password::min(12)
                    ->mixedCase()
                    ->numbers()
                    ->symbols(),
            ],
        ]);

        $userId = session('force_password_reset_user');

        if (! $userId) {
            return redirect('/login');
        }

        $user = DB::table('users')->where('id', $userId)->first();

        if (Hash::check($req->password_baru, $user->password)) {
            return back()->withErrors([
                'password_baru' => 'Password tidak boleh sama dengan sebelumnya',
            ]);
        }

        // ✅ update password
        $result = DB::table('users')->where('id', $userId)->update([
            'password' => Hash::make($req->password_baru),
            'password_changed_at' => now(),
        ]);

        // ✅ LOG
        DB::table('audit_logs')->insert([
            'user_id' => $userId,
            'activity' => 'PASSWORD_CHANGED_FORCE',
            'module' => 'auth',
            'ip_address' => $req->ip(),
            'user_agent' => $req->userAgent(),
            'created_at' => now(),
        ]);

        // ✅ LOGIN ULANG
        Auth::loginUsingId($userId);

        // 🔥 RESET MFA
        session([
            'mfa_verified' => false,
        ]);

        // 🔥 HAPUS FLAG FORCE RESET
        session()->forget('force_password_reset_user');

        session()->flash('success', 'Password berhasil diubah');

        return redirect()->route('mfa.verify');
    }
}
