<?php

namespace App\Http\Controllers\dapur;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables; // ← tambahkan ini

class GratifikasiController extends Controller
{
    public function index()
    {
        return view('dapur.gratifikasi.index');
    }

    public function show($id)
    {
        $data = DB::table('gratifikasi_reports')->where('id', $id)->first();

        return view('dapur.gratifikasi.show', compact('data'));
    }

    public function destroy($id)
    {
        DB::table('gratifikasi_reports')->where('id', $id)->delete();

        return response()->json([
            'result' => 'success',
            'message' => 'Data berhasil dihapus',
        ]);
    }

    public function getList()
    {
        $data = DB::table('gratifikasi_reports')
            ->orderBy('created_at', 'desc');

        return DataTables::of($data)

            ->addIndexColumn()

            ->addColumn('namalengkap', function ($row) {
                return '<p class="ndrparagraf">'.$row->namalengkap.'</p>';
            })

            ->addColumn('instansi', function ($row) {
                return '<p class="ndrparagraf">'.($row->instansi ?? '-').'</p>';
            })

            ->addColumn('jenispenerimaan', function ($row) {
                return '<p class="ndrparagraf">'.($row->jenispenerimaan ?? '-').'</p>';
            })

            ->addColumn('tanggal', function ($row) {
                return '<p class="ndrparagraf">'.($row->tanggal ?? '-').'</p>';
            })

            ->addColumn('file', function ($row) {
                if ($row->file_bukti) {
                    return '<a href="'.asset('storage/'.$row->file_bukti).'" target="_blank">Lihat</a>';
                }

                return '-';
            })

            ->addColumn('action', function ($row) {
                return '
    <button onclick="showDetail('.$row->id.')" class="btn btn-sm btn-info">
        <i class="fas fa-eye"></i>
    </button>
    <button onclick="hapus('.$row->id.')" class="btn btn-sm btn-danger">
        <i class="fas fa-trash"></i>
    </button>
    ';
            })

            ->rawColumns(['namalengkap', 'instansi', 'jenispenerimaan', 'tanggal', 'file', 'action'])

            ->blacklist(['DT_RowIndex']) // ← tambahkan ini

            ->make(true);
    }
}
