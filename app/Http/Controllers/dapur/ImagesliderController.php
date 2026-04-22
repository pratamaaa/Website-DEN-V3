<?php

namespace App\Http\Controllers\dapur;

use App\Helpers\Gudangfungsi;
use App\Http\Controllers\Controller;
use App\Models\Imageslider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Yajra\DataTables\Facades\DataTables;

class ImagesliderController extends Controller
{
    public function index()
    {
        return view('dapur.imageslider.index', [
            'judulhalaman' => 'Image Slider',
        ]);
    }

    public function getList()
    {
        $data = Imageslider::orderBy('created_at', 'desc')->get();

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('gambarslider', function ($row) {
                return '<img src="'.asset('uploads/imagesliders/'.$row->gambar).'" width="100px" style="margin-top:10px;border-radius:5px;border:1px solid #cdcdcd;">';
            })
            ->addColumn('isactive', function ($row) {
                return $row->is_active == 'yes'
                    ? '<span class="badge bg-success">Yes</span>'
                    : '<span class="badge bg-primary">No</span>';
            })
            ->addColumn('tanggalposting', function ($row) {
                return '<p class="ndrparagraf">'.Gudangfungsi::tanggalindoshort($row->tanggal_publikasi).'</p>';
            })
            ->addColumn('action', function ($row) {
                return '
                    <button type="button" onclick="showFormedit(\''.$row->id_image_slider.'\')"
                        title="Edit" class="btn btn-sm waves-effect waves-light btn-info m-b-0"
                        style="padding-bottom:0px;padding-top:0px;">
                        <i class="feather icon-edit-2"></i>
                    </button>
                    <button type="button" onclick="hapus(\''.$row->id_image_slider.'\')"
                        title="Hapus" class="btn btn-sm waves-effect waves-light btn-danger m-b-0"
                        style="padding-bottom:0px;padding-top:0px;">
                        <i class="feather icon-trash-2"></i>
                    </button>';
            })
            ->rawColumns(['gambarslider', 'isactive', 'tanggalposting', 'action'])
            ->make(true);
    }

    public function add()
    {
        return view('dapur.imageslider.add', [
            'judulmodal' => 'Tambah Image Slider',
        ]);
    }

    public function save(Request $req)
    {
        try {
            $namagambar = $this->uploadGambar($req);

            $slider = Imageslider::create([
                'judul_slider' => $req->judul_slider,
                'alamat_url' => $req->alamat_url,
                'gambar' => $namagambar,
                'is_active' => $req->is_active != '' ? 'yes' : 'no',
                'tanggal_publikasi' => $req->tanggal_publikasi,
            ]);

            audit_log('Tambah Image Slider: '.$slider->judul_slider, 'ImageSlider');

            return response()->json([
                'result' => 'success',
                'message' => 'Save successfully',
            ]);

        } catch (\Illuminate\Database\QueryException $e) {
            if ($e->errorInfo[1] == 1062) {
                return response()->json([
                    'result' => 'failed',
                    'message' => 'Duplicate key found.',
                ]);
            }

            return response()->json([
                'result' => 'failed',
                'message' => 'Save failed.',
            ]);
        }
    }

    public function edit(Request $req)
    {
        $data = Imageslider::findOrFail($req->id);

        return view('dapur.imageslider.edit', [
            'judulmodal' => 'Edit Image Slider',
            'data' => $data,
        ]);
    }

    public function saveupdate(Request $req)
    {
        try {
            $slider = Imageslider::findOrFail($req->id_imageslider);

            // Pakai gambar baru jika ada upload, atau tetap gambar lama
            $namagambar = $req->hasFile('gambar')
                ? $this->uploadGambar($req)
                : $req->gambar_current;

            $slider->update([
                'judul_slider' => $req->judul_slider,
                'alamat_url' => $req->alamat_url,
                'gambar' => $namagambar,
                'is_active' => $req->is_active != '' ? 'yes' : 'no',
                'tanggal_publikasi' => $req->tanggal_publikasi,
            ]);

            audit_log('Update Image Slider: '.$slider->judul_slider, 'ImageSlider');

            return response()->json([
                'result' => 'success',
                'message' => 'Update successfully',
            ]);

        } catch (\Illuminate\Database\QueryException $e) {
            if ($e->errorInfo[1] == 1062) {
                return response()->json([
                    'result' => 'failed',
                    'message' => 'Duplicate key found.',
                ]);
            }

            return response()->json([
                'result' => 'failed',
                'message' => 'Save failed.',
            ]);
        }
    }

    public function delete(Request $req)
    {
        $slider = Imageslider::findOrFail($req->id);

        // Hapus file gambar dari storage jika ada
        // $pathGambar = 'public/uploads/imagesliders/'.$slider->gambar;
        $pathGambar = public_path('uploads/imagesliders/'.$slider->gambar);
        if ($slider->gambar && File::exists($pathGambar)) {
            File::delete($pathGambar);
        }

        $judul = $slider->judul_slider;
        $slider->delete();

        audit_log('Hapus Image Slider: '.$judul, 'ImageSlider');

        return response()->json([
            'result' => 'success',
            'message' => 'Deleting data successfully',
        ]);
    }

    // ✅ Private helper untuk upload gambar, menghindari duplikasi kode
    private function uploadGambar(Request $req): string
    {
        if ($req->hasFile('gambar')) {
            $file = $req->file('gambar');
            $namafileOri = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $ekstensi = $file->getClientOriginalExtension();
            $namagambar = $namafileOri.'_'.time().'.'.$ekstensi;

            $destination = public_path('uploads/imagesliders');

            // bikin folder kalau belum ada
            if (! File::exists($destination)) {
                File::makeDirectory($destination, 0755, true);
            }

            $file->move($destination, $namagambar);

            return $namagambar;
        }

        return '';
    }
}
