<?php

namespace App\Http\Controllers\dapur;

use App\Http\Controllers\Controller;
use App\Models\RuedpProvinsi;
use App\Models\RuedpStatus;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class RuedpProvinsiController extends Controller
{
    public function index()
    {
        return view('dapur.ruedpprovinsi.index', [
            'judulhalaman' => 'Data Provinsi RUED',
        ]);
    }

    public function getList()
    {
        $data = RuedpProvinsi::with('status')->orderBy('nama_provinsi')->get();

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('provinsi_col', function ($row) {
                return '<p class="ndrparagraf">'.$row->nama_provinsi.'</p>';
            })
            ->addColumn('status_col', function ($row) {
                $warna = $row->status->warna ?? '#ccc';
                $nama = $row->status->nama_status ?? '-';

                return '<span class="badge" style="background:'.$warna.'">'.$nama.'</span>';
            })
            ->addColumn('perda_col', function ($row) {
                return $row->nomor_perda ?? '-';
            })
            ->addColumn('tanggal_col', function ($row) {
                return $row->tanggal_update
                    ? \Carbon\Carbon::parse($row->tanggal_update)->isoFormat('D MMMM YYYY')
                    : '-';
            })
            ->addColumn('action', function ($row) {
                return '
                    <button type="button" onclick="showFormedit(\''.$row->id_ruedp_provinsi.'\')"
                        title="Edit" class="btn btn-sm waves-effect waves-light btn-info m-b-0"
                        style="padding-bottom:0px;padding-top:0px;">
                        <i class="feather icon-edit-2"></i>
                    </button>
                    <button type="button" onclick="hapus(\''.$row->id_ruedp_provinsi.'\')"
                        title="Hapus" class="btn btn-sm waves-effect waves-light btn-danger m-b-0"
                        style="padding-bottom:0px;padding-top:0px;">
                        <i class="feather icon-trash-2"></i>
                    </button>';
            })
            ->rawColumns(['provinsi_col', 'status_col', 'perda_col', 'tanggal_col', 'action'])
            ->make(true);
    }

    public function add()
    {
        return view('dapur.ruedpprovinsi.add', [
            'judulmodal' => 'Tambah Data Provinsi',
            'statusList' => RuedpStatus::orderBy('urutan')->get(),
            'provinsiList' => $this->getProvinsiList(),
        ]);
    }

    private function getProvinsiList(): array
    {
        return [
            'ID-AC' => 'Aceh',
            'ID-SU' => 'Sumatera Utara',
            'ID-SB' => 'Sumatera Barat',
            'ID-RI' => 'Riau',
            'ID-KR' => 'Kepulauan Riau',
            'ID-JA' => 'Jambi',
            'ID-SS' => 'Sumatera Selatan',
            'ID-BB' => 'Bangka Belitung',
            'ID-BE' => 'Bengkulu',
            'ID-LA' => 'Lampung',
            'ID-JK' => 'DKI Jakarta',
            'ID-JB' => 'Jawa Barat',
            'ID-BT' => 'Banten',
            'ID-JT' => 'Jawa Tengah',
            'ID-YO' => 'DI Yogyakarta',
            'ID-JI' => 'Jawa Timur',
            'ID-BA' => 'Bali',
            'ID-NB' => 'Nusa Tenggara Barat',
            'ID-NT' => 'Nusa Tenggara Timur',
            'ID-KB' => 'Kalimantan Barat',
            'ID-KT' => 'Kalimantan Tengah',
            'ID-KI' => 'Kalimantan Timur',
            'ID-KS' => 'Kalimantan Selatan',
            'ID-KU' => 'Kalimantan Utara',
            'ID-SA' => 'Sulawesi Utara',
            'ID-GO' => 'Gorontalo',
            'ID-ST' => 'Sulawesi Tengah',
            'ID-SR' => 'Sulawesi Barat',
            'ID-SN' => 'Sulawesi Selatan',
            'ID-SG' => 'Sulawesi Tenggara',
            'ID-MA' => 'Maluku',
            'ID-MU' => 'Maluku Utara',
            'ID-PA' => 'Papua',
            'ID-PB' => 'Papua Barat',
            'ID-PE' => 'Papua Pegunungan',
            'ID-PS' => 'Papua Selatan',
            'ID-PT' => 'Papua Tengah',
            'ID-PD' => 'Papua Barat Daya',
        ];
    }

    public function save(Request $req)
    {
        $provinsi = RuedpProvinsi::create([
            'id_ruedp_status' => $req->id_ruedp_status,
            'nama_provinsi' => $req->nama_provinsi,
            'kode_provinsi' => $req->kode_provinsi,
            'nomor_perda' => $req->nomor_perda,
            'tanggal_update' => $req->tanggal_update,
            'keterangan' => $req->keterangan,
        ]);

        audit_log('Tambah Provinsi RUED: '.$provinsi->nama_provinsi, 'RUEDPProvinsi');

        return response()->json(['result' => 'success', 'message' => 'Save successfully']);
    }

    public function edit(Request $req)
    {
        return view('dapur.ruedpprovinsi.edit', [
            'judulmodal' => 'Edit Data Provinsi',
            'data' => RuedpProvinsi::findOrFail($req->id),
            'statusList' => RuedpStatus::orderBy('urutan')->get(),
            'provinsiList' => $this->getProvinsiList(),
        ]);
    }

    public function saveupdate(Request $req)
    {
        $provinsi = RuedpProvinsi::findOrFail($req->id_ruedp_provinsi);
        $provinsi->update([
            'id_ruedp_status' => $req->id_ruedp_status,
            'nama_provinsi' => $req->nama_provinsi,
            'kode_provinsi' => $req->kode_provinsi,
            'nomor_perda' => $req->nomor_perda,
            'tanggal_update' => $req->tanggal_update,
            'keterangan' => $req->keterangan,
        ]);

        audit_log('Update Provinsi RUED: '.$provinsi->nama_provinsi, 'RUEDPProvinsi');

        return response()->json(['result' => 'success', 'message' => 'Update successfully']);
    }

    public function delete(Request $req)
    {
        $provinsi = RuedpProvinsi::findOrFail($req->id);
        $nama = $provinsi->nama_provinsi;
        $provinsi->delete();

        audit_log('Hapus Provinsi RUED: '.$nama, 'RUEDPProvinsi');

        return response()->json(['result' => 'success', 'message' => 'Deleting data successfully']);
    }
}
