<?php

// app/Http/Controllers/dapur/StrukturOrganisasiController.php

namespace App\Http\Controllers\dapur;

use App\Http\Controllers\Controller;
use App\Models\StrukturOrganisasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;
use Yajra\DataTables\Facades\DataTables;

class StrukturOrganisasiController extends Controller
{
    public function index()
    {
        return view('dapur.strukturorganisasi.index', [
            'judulhalaman' => 'Struktur Organisasi',
        ]);
    }

    public function getList()
    {
        $data = StrukturOrganisasi::with('parent')
            ->orderBy('id_parent')
            ->orderBy('urutan')
            ->get();

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('foto_preview', function ($row) {
                $src = $row->foto
                    ? asset('uploads/strukturorganisasi/'.$row->foto)
                    : asset('uploads/default-image/default-avatar.png');

                return '<img src="'.$src.'" width="45px" height="45px" style="border-radius:50%;object-fit:cover;border:2px solid #ddd;">';
            })
            ->addColumn('nama', function ($row) {
                return '<p class="ndrparagraf">'.$row->nama_lengkap.'</p>';
            })
            ->addColumn('jabatan_col', function ($row) {
                return '<p class="ndrparagraf">'.$row->jabatan.'</p>';
            })
            ->addColumn('parent_col', function ($row) {
                return $row->id_parent == 0
                    ? '<span class="badge bg-success">Top Level</span>'
                    : '<p class="ndrparagraf">'.optional($row->parent)->jabatan.'</p>';
            })
            ->addColumn('isactive', function ($row) {
                return $row->is_active == 'yes'
                    ? '<span class="badge bg-success">YES</span>'
                    : '<span class="badge bg-danger">NO</span>';
            })
            ->addColumn('action', function ($row) {
                return '
                    <button type="button" onclick="showFormedit(\''.$row->id_so.'\')"
                        title="Edit" class="btn btn-sm waves-effect waves-light btn-info m-b-0"
                        style="padding-bottom:0px;padding-top:0px;">
                        <i class="feather icon-edit-2"></i>
                    </button>
                    <button type="button" onclick="hapus(\''.$row->id_so.'\')"
                        title="Hapus" class="btn btn-sm waves-effect waves-light btn-danger m-b-0"
                        style="padding-bottom:0px;padding-top:0px;">
                        <i class="feather icon-trash-2"></i>
                    </button>';
            })
            ->rawColumns(['foto_preview', 'nama', 'jabatan_col', 'parent_col', 'isactive', 'action'])
            ->make(true);
    }

    public function add()
    {
        return view('dapur.strukturorganisasi.add', [
            'judulmodal' => 'Tambah Pejabat',
            'parents' => StrukturOrganisasi::where('is_active', 'yes')
                ->orderBy('urutan')->get(),
        ]);
    }

    public function save(Request $req)
    {
        $namafile = $this->uploadFoto($req);

        $so = StrukturOrganisasi::create([
            'id_parent' => $req->id_parent ?? 0,
            'nama_lengkap' => $req->nama_lengkap,
            'jabatan' => $req->jabatan,
            'foto' => $namafile,
            'urutan' => $req->urutan ?? 0,
            'is_active' => $req->is_active ?? 'yes',
        ]);

        audit_log('Tambah Struktur Organisasi: '.$so->jabatan, 'StrukturOrganisasi');

        return response()->json(['result' => 'success', 'message' => 'Save successfully']);
    }

    public function edit(Request $req)
    {
        $data = StrukturOrganisasi::findOrFail($req->id);

        return view('dapur.strukturorganisasi.edit', [
            'judulmodal' => 'Edit Pejabat',
            'data' => $data,
            'parents' => StrukturOrganisasi::where('is_active', 'yes')
                ->where('id_so', '!=', $req->id)
                ->orderBy('urutan')->get(),
        ]);
    }

    public function saveupdate(Request $req)
    {
        $so = StrukturOrganisasi::findOrFail($req->id_so);

        $namafile = $req->hasFile('foto')
            ? $this->uploadFoto($req)
            : $req->foto_current;

        $so->update([
            'id_parent' => $req->id_parent ?? 0,
            'nama_lengkap' => $req->nama_lengkap,
            'jabatan' => $req->jabatan,
            'foto' => $namafile,
            'urutan' => $req->urutan ?? 0,
            'is_active' => $req->is_active ?? 'yes',
        ]);

        audit_log('Update Struktur Organisasi: '.$so->jabatan, 'StrukturOrganisasi');

        return response()->json(['result' => 'success', 'message' => 'Update successfully']);
    }

    public function delete(Request $req)
    {
        $so = StrukturOrganisasi::findOrFail($req->id);

        $this->deleteFoto($so->foto);

        $jabatan = $so->jabatan;
        $so->delete();

        audit_log('Hapus Struktur Organisasi: '.$jabatan, 'StrukturOrganisasi');

        return response()->json(['result' => 'success', 'message' => 'Deleting data successfully']);
    }

    private function uploadFoto(Request $req): string
    {
        if ($req->hasFile('foto')) {
            $file = $req->file('foto');
            $namafileOri = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $ekstensi = $file->getClientOriginalExtension();
            $namafile = $namafileOri.'_'.time().'.'.$ekstensi;

            $destination = public_path('uploads/strukturorganisasi');
            if (! file_exists($destination)) {
                mkdir($destination, 0755, true);
            }

            // ✅ Intervention Image v3
            $manager = new ImageManager(new Driver);
            $manager->read($file->getRealPath())
                ->cover(300, 300)
                ->save($destination.'/'.$namafile);

            return $namafile;
        }

        return '';
    }

    private function deleteFoto(?string $namafile): void
    {
        $path = public_path('uploads/strukturorganisasi/'.$namafile); // ✅ pakai public_path()
        if ($namafile && File::exists($path)) {
            File::delete($path);
        }
    }
}
