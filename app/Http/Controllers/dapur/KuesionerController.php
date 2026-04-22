<?php

namespace App\Http\Controllers\dapur;

use App\Helpers\Gudangfungsi;
use App\Http\Controllers\Controller;
use App\Models\KuesionerJawaban;
use App\Models\KuesionerLayanan;
use App\Models\KuesionerParameter;
use App\Models\KuesionerPertanyaan;
use App\Models\ViewKuesionerLayanan;
use App\Models\ViewKuesionerTemplateJawabanGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class KuesionerController extends Controller
{
    public function index()
    {
        $data['judulhalaman'] = 'Reformasi Birokrasi';

        return view('dapur.rb.index', $data);
    }

    public function manajemen_template_jawaban()
    {
        $data['judulhalaman'] = 'Manajement Template Jawaban';

        return view('dapur.kuesioner.manajemen.templatejawaban.index', $data);
    }

    public function manajemen_template_jawaban_list()
    {
        $data = ViewKuesionerTemplateJawabanGroup::orderBy('kuesioner_template_jawaban_group_created_date', 'desc')
            ->get();

        $datatable = DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('kuesioner_template_jawaban_group_nama', function ($row) {
                return $row->kuesioner_template_jawaban_group_nama;
            })
            ->addColumn('kuesioner_template_jawaban_group_keterangan', function ($row) {
                return $row->kuesioner_template_jawaban_group_keterangan;
            })
            ->addColumn('kuesioner_template_jawaban_group_jawaban', function ($row) {
                return $row->kuesioner_template_jawaban_group_jawaban;
            })
            ->addColumn('kuesioner_template_jawaban_group_created_date', function ($row) {
                return Gudangfungsi::tanggalindoshort($row->kuesioner_template_jawaban_group_created_date);
            })
            ->addColumn('kuesioner_template_jawaban_group_status_name', function ($row) {
                if ($row->kuesioner_template_jawaban_group_status == 1) {
                    return '<span class="badge badge-success">Aktif</span>';
                }

                return '<span class="badge badge-danger">Tidak Aktif</span>';
            })
            ->addColumn('action', function ($row) {
                $editUrl = url('/kuesioner/manajemen-template-jawaban-edit/'.$row->kuesioner_template_jawaban_group_uuid);

                $actionBtn = '<a href="'.$editUrl.'" class="btn btn-sm btn-info mr-1" title="Edit">
                                <i class="fa fa-edit"></i>
                              </a>';

                $actionBtn .= '<button type="button" onclick="hapus(\''.$row->kuesioner_template_jawaban_group_uuid.'\')" class="btn btn-sm btn-danger" title="Hapus">
                                <i class="fa fa-trash"></i>
                               </button>';

                return $actionBtn;
            })
            // --- BAGIAN INI YANG WAJIB DIPERBAIKI ---
            ->rawColumns([
                'kuesioner_template_jawaban_group_jawaban', // <-- Tambahkan ini agar HTML List dirender
                'kuesioner_template_jawaban_group_status_name',
                'action',
            ])
            ->make(true);

        return $datatable;
    }

    public function overview()
    {
        $data['judulhalaman'] = 'Overview Kuesioner & Demografi';

        // 1. Total Responden
        $data['total_responden'] = DB::table('kuesioner_responden')->count();

        // 2. Data Instansi Asal (Untuk Chart & Kartu Atas)
        $instansiStats = DB::table('kuesioner_referensi as ref')
            ->leftJoin('kuesioner_responden as resp', 'ref.referensi_uuid', '=', 'resp.kuesioner_responden_instansi_asal_uuid')
            ->select('ref.referensi_nama', DB::raw('COUNT(resp.kuesioner_responden_uuid) as total'))
            ->where('ref.referensi_kategori', 'INSTANSI')
            ->where('ref.referensi_status', 1)
            ->groupBy('ref.referensi_nama', 'ref.referensi_urutan')
            ->orderBy('ref.referensi_urutan', 'asc')
            ->get();

        $data['chart_instansi_label'] = $instansiStats->pluck('referensi_nama');
        $data['chart_instansi_data'] = $instansiStats->pluck('total');

        // Kirim raw object untuk dibuat kartu (looping di view)
        $data['summary_instansi'] = $instansiStats;

        // 3. Data Chart: Detail APK
        $apkStats = DB::table('kuesioner_referensi as ref')
            ->leftJoin('kuesioner_responden as resp', 'ref.referensi_uuid', '=', 'resp.kuesioner_responden_pemangku_kepentingan_uuid')
            ->select('ref.referensi_nama', DB::raw('COUNT(resp.kuesioner_responden_uuid) as total'))
            ->where('ref.referensi_kategori', 'APK')
            ->where('ref.referensi_status', 1)
            ->groupBy('ref.referensi_nama', 'ref.referensi_urutan')
            ->orderBy('ref.referensi_urutan', 'asc')
            ->get();

        $data['chart_apk_label'] = $apkStats->pluck('referensi_nama');
        $data['chart_apk_data'] = $apkStats->pluck('total');

        // 4. Data Chart: Detail Kementerian
        $apStats = DB::table('kuesioner_referensi as ref')
            ->leftJoin('kuesioner_responden as resp', 'ref.referensi_uuid', '=', 'resp.kuesioner_responden_kementerian_lembaga_uuid')
            ->select('ref.referensi_nama', DB::raw('COUNT(resp.kuesioner_responden_uuid) as total'))
            ->where('ref.referensi_kategori', 'KEMENTERIAN')
            ->where('ref.referensi_status', 1)
            ->groupBy('ref.referensi_nama', 'ref.referensi_urutan')
            ->orderBy('ref.referensi_urutan', 'asc')
            ->get();

        $data['chart_ap_label'] = $apStats->pluck('referensi_nama');
        $data['chart_ap_data'] = $apStats->pluck('total');

        // 5. Data Tabel
        $layananStats = DB::table('kuesioner_layanan as l')
            ->leftJoin('kuesioner_responden as r', 'l.kuesioner_layanan_uuid', '=', 'r.kuesioner_layanan_uuid')
            ->select('l.kuesioner_layanan_nama', DB::raw('COUNT(r.kuesioner_responden_uuid) as total'))
            ->where('l.kuesioner_layanan_status', 1)
            ->groupBy('l.kuesioner_layanan_nama', 'l.kuesioner_layanan_created_date')
            ->orderBy('l.kuesioner_layanan_created_date', 'desc')
            ->get();

        $data['layanan_stats'] = $layananStats;

        return view('dapur.kuesioner.overview.index', $data);
    }

    public function manajemen_layanan()
    {
        $data['judulhalaman'] = 'Manajement Layanan';

        return view('dapur.kuesioner.manajemen.layanan.index', $data);
    }

    public function hasil_analisa()
    {
        $data['judulhalaman'] = 'Hasil & Analisa';
        $data['daftarLayanan'] = KuesionerLayanan::all();

        return view('dapur.kuesioner.hasil_analisa.index', $data);
    }

    public function data_responden()
    {
        $data['judulhalaman'] = 'Data Responden';
        $data['daftarLayanan'] = \App\Models\KuesionerLayanan::all(); // Untuk Filter

        return view('dapur.kuesioner.data_responden.index', $data);
    }

    public function data_responden_list(Request $request)
    {
        $layananId = $request->layanan_id;

        if (! $layananId) {
            return response()->json(['headers' => [], 'rows' => []]);
        }

        // A. AMBIL DAFTAR RESPONDEN (Mapping Nama dan UUID untuk Icon Detail)
        $respondens = DB::table('kuesioner_responden')
            ->where('kuesioner_layanan_uuid', $layananId)
            ->orderBy('kuesioner_responden_created_date', 'asc')
            ->get();

        $headers = $respondens->map(function ($r) {
            return [
                'nama' => $r->kuesioner_responden_nama,
                'uuid' => $r->kuesioner_responden_uuid,
            ];
        });

        // B. AMBIL PARAMETER
        $parameterList = KuesionerParameter::where('kuesioner_parameter_status', 1)
            ->whereNull('kuesioner_parameter_log_uuid')
            ->orderBy('kuesioner_parameter_code', 'asc')
            ->get();

        // C. AMBIL SEMUA JAWABAN (Eager Loading Manual)
        $rawAnswers = DB::table('kuesioner_jawaban_responden as jr')
            ->join('kuesioner_responden as r', 'jr.kuesioner_responden_uuid', '=', 'r.kuesioner_responden_uuid')
            ->join('kuesioner_jawaban as j', 'jr.kuesioner_jawaban_uuid', '=', 'j.kuesioner_jawaban_uuid')
            ->join('kuesioner_pertanyaan as p', 'jr.kuesioner_pertanyaan_uuid', '=', 'p.kuesioner_pertanyaan_uuid')
            ->join('kuesioner_pertanyaan as p_parent', 'p.kuesioner_pertanyaan_parent_uuid', '=', 'p_parent.kuesioner_pertanyaan_uuid')
            ->where('r.kuesioner_layanan_uuid', $layananId)
            ->select(
                'r.kuesioner_responden_uuid',
                'p_parent.kuesioner_pertanyaan_parameter_uuid as param_id',
                'p.kuesioner_pertanyaan_aspect as aspek',
                'j.kuesioner_jawaban_bobot'
            )
            ->get();

        $mapAnswers = [];
        foreach ($rawAnswers as $row) {
            $mapAnswers[$row->kuesioner_responden_uuid][$row->param_id][$row->aspek] = $row->kuesioner_jawaban_bobot;
        }

        // D. BUILD STRUKTUR DATA TABLE
        $tableRows = [];
        $no = 1;

        foreach ($parameterList as $param) {
            $pId = $param->kuesioner_parameter_uuid;
            $types = ['Importance', 'Performance', 'Gap'];

            foreach ($types as $type) {
                $rowData = [
                    'no' => ($type == 'Importance') ? $no : '',
                    'kode' => ($type == 'Importance') ? $param->kuesioner_parameter_code : '',
                    'parameter' => ($type == 'Importance') ? $param->kuesioner_parameter_nama : '',
                    'aspek' => $type,
                    'scores' => [],
                    'rata_rata' => 0,
                ];

                $sumScore = 0;
                $countScore = 0;

                foreach ($respondens as $resp) {
                    $rId = $resp->kuesioner_responden_uuid;
                    $val = 0;

                    $imp = isset($mapAnswers[$rId][$pId][1]) ? (float) $mapAnswers[$rId][$pId][1] : 0;
                    $perf = isset($mapAnswers[$rId][$pId][2]) ? (float) $mapAnswers[$rId][$pId][2] : 0;

                    if ($type == 'Importance') {
                        $val = $imp;
                    } elseif ($type == 'Performance') {
                        $val = $perf;
                    } else {
                        $val = $perf - $imp;
                    }

                    $rowData['scores'][] = $val;

                    if ($val != 0 || $type == 'Gap') {
                        $sumScore += $val;
                        $countScore++;
                    }
                }

                $avg = ($countScore > 0) ? ($sumScore / $countScore) : 0;
                $rowData['rata_rata'] = number_format($avg, 2);
                $tableRows[] = $rowData;
            }
            $no++;
        }

        return response()->json([
            'headers' => $headers,
            'rows' => $tableRows,
        ]);
    }

    public function data_responden_detail($uuid)
    {
        $data['judulhalaman'] = 'Detail Isian Responden';

        // 1. Ambil Profil Responden
        $data['responden'] = DB::table('kuesioner_responden as r')
            ->join('kuesioner_layanan as l', 'r.kuesioner_layanan_uuid', '=', 'l.kuesioner_layanan_uuid')
            ->leftJoin('kuesioner_referensi as ref1', 'r.kuesioner_responden_instansi_asal_uuid', '=', 'ref1.referensi_uuid')
            ->leftJoin('kuesioner_referensi as ref2', 'r.kuesioner_responden_pemangku_kepentingan_uuid', '=', 'ref2.referensi_uuid')
            ->leftJoin('kuesioner_referensi as ref3', 'r.kuesioner_responden_kementerian_lembaga_uuid', '=', 'ref3.referensi_uuid')
            ->select(
                'r.*',
                'l.kuesioner_layanan_nama',
                'ref1.referensi_nama as instansi_asal',
                'ref2.referensi_nama as pemangku_kepentingan',
                'ref3.referensi_nama as kementerian_lembaga'
            )
            ->where('r.kuesioner_responden_uuid', $uuid)
            ->first();

        if (! $data['responden']) {
            return redirect()->back()->with('error', 'Data responden tidak ditemukan.');
        }

        // SIMPAN UUID LAYANAN UNTUK URL KEMBALI/BREADCRUMB
        $data['layanan_terpilih'] = $data['responden']->kuesioner_layanan_uuid;

        // 2. Ambil Pertanyaan & Jawaban (Sama seperti sebelumnya)
        $data['pertanyaan'] = KuesionerPertanyaan::with(['children' => function ($query) {
            $query->with(['jawaban']);
        }])
            ->where('kuesioner_pertanyaan_layanan_uuid', $data['responden']->kuesioner_layanan_uuid)
            ->whereNull('kuesioner_pertanyaan_parent_uuid')
            ->orderBy('kuesioner_pertanyaan_kode', 'asc')
            ->get();

        $data['jawaban_terpilih'] = DB::table('kuesioner_jawaban_responden')
            ->where('kuesioner_responden_uuid', $uuid)
            ->pluck('kuesioner_jawaban_uuid')
            ->toArray();

        return view('dapur.kuesioner.data_responden.detail', $data);
    }

    public function hasil_analisa_list(Request $request)
    {
        // --- BAGIAN 1: QUERY DATA MATRIKS (INPUT/GAP) ---
        // Ini logika lama yang wajib ada agar tabel besar terisi

        $parameterList = \App\Models\KuesionerParameter::orderBy('kuesioner_parameter_code', 'asc')->get();
        $layananList = \App\Models\KuesionerLayanan::all();

        $data = [];

        foreach ($parameterList as $param) {
            $kodeParam = $param->kuesioner_parameter_code;
            $namaParam = $param->kuesioner_parameter_nama;
            $uuidParam = $param->kuesioner_parameter_uuid;

            // Siapkan 3 Baris Struktur
            $rowImp = ['kode' => $kodeParam, 'parameter' => $namaParam, 'tipe' => 'Importance'];
            $rowPerf = ['kode' => $kodeParam, 'parameter' => $namaParam, 'tipe' => 'Performance'];
            $rowGap = ['kode' => $kodeParam, 'parameter' => $namaParam, 'tipe' => 'Gap'];

            $sumImp = 0;
            $sumPerf = 0;
            $countLayanan = 0;

            foreach ($layananList as $l) {
                $key = 'layanan_'.$l->kuesioner_layanan_uuid; // Key untuk kolom dinamis
                $uuidLayanan = $l->kuesioner_layanan_uuid;

                // Query Nilai Rata2 Importance
                $avgImp = DB::table('kuesioner_jawaban_responden as jr')
                    ->join('kuesioner_pertanyaan as p_child', 'jr.kuesioner_pertanyaan_uuid', '=', 'p_child.kuesioner_pertanyaan_uuid')
                    ->join('kuesioner_pertanyaan as p_parent', 'p_child.kuesioner_pertanyaan_parent_uuid', '=', 'p_parent.kuesioner_pertanyaan_uuid')
                    ->join('kuesioner_jawaban as j', 'jr.kuesioner_jawaban_uuid', '=', 'j.kuesioner_jawaban_uuid')
                    ->join('kuesioner_responden as r', 'jr.kuesioner_responden_uuid', '=', 'r.kuesioner_responden_uuid')
                    ->where('p_parent.kuesioner_pertanyaan_parameter_uuid', $uuidParam)
                    ->where('p_child.kuesioner_pertanyaan_aspect', 1) // 1 = Importance
                    ->where('r.kuesioner_layanan_uuid', $uuidLayanan)
                    ->avg('j.kuesioner_jawaban_bobot');

                // Query Nilai Rata2 Performance
                $avgPerf = DB::table('kuesioner_jawaban_responden as jr')
                    ->join('kuesioner_pertanyaan as p_child', 'jr.kuesioner_pertanyaan_uuid', '=', 'p_child.kuesioner_pertanyaan_uuid')
                    ->join('kuesioner_pertanyaan as p_parent', 'p_child.kuesioner_pertanyaan_parent_uuid', '=', 'p_parent.kuesioner_pertanyaan_uuid')
                    ->join('kuesioner_jawaban as j', 'jr.kuesioner_jawaban_uuid', '=', 'j.kuesioner_jawaban_uuid')
                    ->join('kuesioner_responden as r', 'jr.kuesioner_responden_uuid', '=', 'r.kuesioner_responden_uuid')
                    ->where('p_parent.kuesioner_pertanyaan_parameter_uuid', $uuidParam)
                    ->where('p_child.kuesioner_pertanyaan_aspect', 2) // 2 = Performance
                    ->where('r.kuesioner_layanan_uuid', $uuidLayanan)
                    ->avg('j.kuesioner_jawaban_bobot');

                $avgImp = $avgImp ? floatval($avgImp) : 0;
                $avgPerf = $avgPerf ? floatval($avgPerf) : 0;
                $gap = $avgPerf - $avgImp;

                // Masukkan ke array baris
                $rowImp[$key] = number_format($avgImp, 2);
                $rowPerf[$key] = number_format($avgPerf, 2);
                $rowGap[$key] = number_format($gap, 2);

                $sumImp += $avgImp;
                $sumPerf += $avgPerf;
                $countLayanan++;
            }

            // Hitung Rata-rata Horizontal
            $totalAvgImp = $countLayanan > 0 ? ($sumImp / $countLayanan) : 0;
            $totalAvgPerf = $countLayanan > 0 ? ($sumPerf / $countLayanan) : 0;
            $totalGap = $totalAvgPerf - $totalAvgImp;

            $rowImp['rata_rata'] = number_format($totalAvgImp, 2);
            $rowPerf['rata_rata'] = number_format($totalAvgPerf, 2);
            $rowGap['rata_rata'] = number_format($totalGap, 2);

            // Push 3 baris sekaligus
            $data[] = $rowImp;
            $data[] = $rowPerf;
            $data[] = $rowGap;
        }

        // --- BAGIAN 2: HITUNG RESPONDEN (LOGIKA BARU) ---
        $respondenStats = DB::table('kuesioner_responden as r')
            ->join('kuesioner_layanan as l', 'r.kuesioner_layanan_uuid', '=', 'l.kuesioner_layanan_uuid')
            ->select('l.kuesioner_layanan_nama', DB::raw('count(*) as total'))
            ->groupBy('l.kuesioner_layanan_nama')
            ->get();

        $totalResponden = $respondenStats->sum('total');

        return response()->json([
            'data' => $data, // Array data matriks yang sudah diisi
            'responden_per_layanan' => $respondenStats,
            'total_responden' => $totalResponden,
        ]);
    }

    public function hasil_analisa_list_ikl(Request $request)
    {
        // --- 1. Hitung Responden (Total Sample) ---
        $jumlahResponden = \Illuminate\Support\Facades\DB::table('kuesioner_responden')->count();

        // --- 2. Ambil Data Parameter ---
        $parameterList = \App\Models\KuesionerParameter::orderBy('kuesioner_parameter_code', 'asc')->get();

        $tempData = [];
        $totalImportance = 0;

        // Loop hitung rata-rata
        foreach ($parameterList as $param) {
            $uuidParam = $param->kuesioner_parameter_uuid;

            // Query Rata-rata Importance
            $avgImp = \Illuminate\Support\Facades\DB::table('kuesioner_jawaban_responden as jr')
                ->join('kuesioner_pertanyaan as p_child', 'jr.kuesioner_pertanyaan_uuid', '=', 'p_child.kuesioner_pertanyaan_uuid')
                ->join('kuesioner_pertanyaan as p_parent', 'p_child.kuesioner_pertanyaan_parent_uuid', '=', 'p_parent.kuesioner_pertanyaan_uuid')
                ->join('kuesioner_jawaban as j', 'jr.kuesioner_jawaban_uuid', '=', 'j.kuesioner_jawaban_uuid')
                ->where('p_parent.kuesioner_pertanyaan_parameter_uuid', $uuidParam)
                ->where('p_child.kuesioner_pertanyaan_aspect', 1)
                ->avg('j.kuesioner_jawaban_bobot');

            // Query Rata-rata Performance
            $avgPerf = \Illuminate\Support\Facades\DB::table('kuesioner_jawaban_responden as jr')
                ->join('kuesioner_pertanyaan as p_child', 'jr.kuesioner_pertanyaan_uuid', '=', 'p_child.kuesioner_pertanyaan_uuid')
                ->join('kuesioner_pertanyaan as p_parent', 'p_child.kuesioner_pertanyaan_parent_uuid', '=', 'p_parent.kuesioner_pertanyaan_uuid')
                ->join('kuesioner_jawaban as j', 'jr.kuesioner_jawaban_uuid', '=', 'j.kuesioner_jawaban_uuid')
                ->where('p_parent.kuesioner_pertanyaan_parameter_uuid', $uuidParam)
                ->where('p_child.kuesioner_pertanyaan_aspect', 2)
                ->avg('j.kuesioner_jawaban_bobot');

            $valImp = $avgImp ? floatval($avgImp) : 0;
            $valPerf = $avgPerf ? floatval($avgPerf) : 0;

            $tempData[] = [
                'kode' => $param->kuesioner_parameter_code,
                'parameter' => $param->kuesioner_parameter_nama,
                'importance' => $valImp,
                'performance' => $valPerf,
            ];

            $totalImportance += $valImp;
        }

        // Hitung Weight & Index
        $finalData = [];
        foreach ($tempData as $item) {
            $weight = ($totalImportance > 0) ? ($item['importance'] / $totalImportance) : 0;
            $weightIndex = $weight * $item['performance'];

            $finalData[] = [
                'kode' => $item['kode'],
                'parameter' => $item['parameter'],
                'importance' => $item['importance'],
                'weight' => $weight,
                'performance' => $item['performance'],
                'weight_index' => $weightIndex,
            ];
        }

        return response()->json([
            'data' => $finalData,
            'total_responden' => $jumlahResponden,
            // Kita kirim breakdown layanan juga jika perlu ditampilkan di tab ini
            'responden_per_layanan' => \Illuminate\Support\Facades\DB::table('kuesioner_responden as r')
                ->join('kuesioner_layanan as l', 'r.kuesioner_layanan_uuid', '=', 'l.kuesioner_layanan_uuid')
                ->select('l.kuesioner_layanan_nama', \Illuminate\Support\Facades\DB::raw('count(*) as total'))
                ->groupBy('l.kuesioner_layanan_nama')
                ->get(),
        ]);
    }

    public function hasil_analisa_list_matriks(Request $request)
    {
        $parameterList = \App\Models\KuesionerParameter::orderBy('kuesioner_parameter_code', 'asc')->get();

        $data = [];
        $totalImp = 0;
        $totalPerf = 0;
        $count = 0;

        foreach ($parameterList as $param) {
            $uuidParam = $param->kuesioner_parameter_uuid;

            // Query Importance & Performance (Sama seperti sebelumnya)
            // ... (Kode Query avgImp dan avgPerf tetapkan sama) ...

            // SAYA TULIS ULANG BAGIAN BAWAHNYA SAJA:
            $avgImp = \Illuminate\Support\Facades\DB::table('kuesioner_jawaban_responden as jr')
                ->join('kuesioner_pertanyaan as p_child', 'jr.kuesioner_pertanyaan_uuid', '=', 'p_child.kuesioner_pertanyaan_uuid')
                ->join('kuesioner_pertanyaan as p_parent', 'p_child.kuesioner_pertanyaan_parent_uuid', '=', 'p_parent.kuesioner_pertanyaan_uuid')
                ->join('kuesioner_jawaban as j', 'jr.kuesioner_jawaban_uuid', '=', 'j.kuesioner_jawaban_uuid')
                ->where('p_parent.kuesioner_pertanyaan_parameter_uuid', $uuidParam)
                ->where('p_child.kuesioner_pertanyaan_aspect', 1)
                ->avg('j.kuesioner_jawaban_bobot');

            $avgPerf = \Illuminate\Support\Facades\DB::table('kuesioner_jawaban_responden as jr')
                ->join('kuesioner_pertanyaan as p_child', 'jr.kuesioner_pertanyaan_uuid', '=', 'p_child.kuesioner_pertanyaan_uuid')
                ->join('kuesioner_pertanyaan as p_parent', 'p_child.kuesioner_pertanyaan_parent_uuid', '=', 'p_parent.kuesioner_pertanyaan_uuid')
                ->join('kuesioner_jawaban as j', 'jr.kuesioner_jawaban_uuid', '=', 'j.kuesioner_jawaban_uuid')
                ->where('p_parent.kuesioner_pertanyaan_parameter_uuid', $uuidParam)
                ->where('p_child.kuesioner_pertanyaan_aspect', 2)
                ->avg('j.kuesioner_jawaban_bobot');

            $valImp = $avgImp ? floatval($avgImp) : 0;
            $valPerf = $avgPerf ? floatval($avgPerf) : 0;
            $gapScore = $valPerf - $valImp;

            $data[] = [
                'kode' => $param->kuesioner_parameter_code,
                'parameter' => $param->kuesioner_parameter_nama,
                'importance' => $valImp,
                'performance' => $valPerf,
                'gap_score' => $gapScore,
            ];

            // --- FIX LOGIC AXIS ---
            // Hanya hitung rata-rata jika nilai ADA (bukan 0)
            if ($valImp > 0 || $valPerf > 0) {
                $totalImp += $valImp;
                $totalPerf += $valPerf;
                $count++;
            }
        }

        // Hitung Axis
        $axisY = $count > 0 ? ($totalImp / $count) : 0;
        $axisX = $count > 0 ? ($totalPerf / $count) : 0;

        return response()->json([
            'data' => $data,
            'axis_y' => round($axisY, 2),
            'axis_x' => round($axisX, 2),
        ]);
    }

    public function manajemen_layanan_create()
    {
        $data['judulhalaman'] = 'Tambah Data Layanan';
        $data['templateGroups'] = ViewKuesionerTemplateJawabanGroup::orderBy('kuesioner_template_jawaban_group_created_date', 'desc')->get();
        $data['KuesionerParameter'] = KuesionerParameter::where('kuesioner_parameter_status', 1)->whereNull('kuesioner_parameter_log_uuid')->orderBy('kuesioner_parameter_code', 'asc')->get();

        return view('dapur.kuesioner.manajemen.layanan.create', $data);
    }

    public function manajemen_layanan_edit($uuid)
    {
        $data['judulhalaman'] = 'Edit Kuesioner Layanan';

        // 1. Ambil data utama Layanan berdasarkan UUID
        // Sertakan pengecekan status agar data yang sudah di-log (old version) tidak ikut terbawa
        $data['layanan'] = KuesionerLayanan::where('kuesioner_layanan_uuid', $uuid)
            ->where('kuesioner_layanan_status', 1)
            ->whereNull('kuesioner_layanan_log_uuid')
            ->firstOrFail();

        // 2. Ambil data Pertanyaan bersarang (Recursive Relationship)
        // - children: Untuk mengambil sub-pertanyaan
        // - jawaban: Untuk mengambil opsi jawaban kustom (jika ada)
        $data['pertanyaan'] = KuesionerPertanyaan::with(['children', 'jawaban'])
            ->where('kuesioner_pertanyaan_layanan_uuid', $uuid)
            ->whereNull('kuesioner_pertanyaan_parent_uuid') // Mulai dari level Header (Parameter)
            ->where('kuesioner_pertanyaan_status', 1)
            ->whereNull('kuesioner_pertanyaan_log_uuid')
            ->orderBy('kuesioner_pertanyaan_kode', 'asc')
            ->get();

        // 3. Ambil data referensi untuk Dropdown (sama seperti di fungsi Create)
        $data['templateGroups'] = ViewKuesionerTemplateJawabanGroup::orderBy(
            'kuesioner_template_jawaban_group_created_date',
            'desc'
        )->get();

        $data['KuesionerParameter'] = KuesionerParameter::where('kuesioner_parameter_status', 1)
            ->whereNull('kuesioner_parameter_log_uuid')
            ->orderBy('kuesioner_parameter_code', 'asc')
            ->get();

        // 4. Kirim data ke view edit
        return view('dapur.kuesioner.manajemen.layanan.edit', $data);
    }

    public function manajemen_layanan_save(Request $request)
    {
        // 1. Validasi Sederhana
        $request->validate([
            'kuesioner_layanan_tahun' => 'required|integer',
            'kuesioner_layanan_nama' => 'required|string|max:255',
            'kuesioner_layanan_tanggal_mulai' => 'required|date',
            'kuesioner_layanan_tanggal_selesai' => 'required|date|after_or_equal:kuesioner_layanan_tanggal_mulai',
        ]);

        try {
            // Gunakan Transaksi Database
            $result = DB::transaction(function () use ($request) {

                $userId = Auth::check() ? Auth::id() : 0;
                $now = now();
                $layananUuid = Str::uuid()->toString();
                $uuidMapping = [];

                // ===============================================
                // A. SIMPAN KUESIONER LAYANAN
                // ===============================================
                $layanan = KuesionerLayanan::create([
                    'kuesioner_layanan_tahun' => $request->kuesioner_layanan_tahun,
                    'kuesioner_layanan_nama' => $request->kuesioner_layanan_nama,
                    'kuesioner_layanan_tanggal_mulai' => $request->kuesioner_layanan_tanggal_mulai,
                    'kuesioner_layanan_tanggal_selesai' => $request->kuesioner_layanan_tanggal_selesai,
                    'kuesioner_layanan_is_saran' => $request->kuesioner_layanan_is_saran ?? 0,
                    'kuesioner_layanan_status' => 1,
                    'kuesioner_layanan_created_by' => $userId,
                    'kuesioner_layanan_created_date' => $now,
                    'kuesioner_layanan_uuid' => $layananUuid,
                ]);

                // ===============================================
                // B. SIMPAN PERTANYAAN (HEADER & SUB-PERTANYAAN)
                // ===============================================
                if ($request->has('questions')) {
                    foreach ($request->questions as $tempId => $qData) {

                        $pertanyaanUuid = Str::uuid()->toString();
                        $isHeader = filter_var($qData['is_header'], FILTER_VALIDATE_BOOLEAN);

                        $parentUuid = null;
                        if (! $isHeader && isset($qData['kuesioner_pertanyaan_parent_uuid'])) {
                            $parentTempId = $qData['kuesioner_pertanyaan_parent_uuid'];
                            $parentUuid = $uuidMapping[$parentTempId] ?? null;
                        }

                        $pertanyaanData = [
                            'kuesioner_pertanyaan_layanan_uuid' => $layananUuid,
                            'kuesioner_pertanyaan_uuid' => $pertanyaanUuid,
                            'kuesioner_pertanyaan_parent_uuid' => $parentUuid,
                            'kuesioner_pertanyaan_kode' => $qData['kuesioner_pertanyaan_kode'],
                            'kuesioner_pertanyaan_nama' => $qData['kuesioner_pertanyaan_nama'],

                            // PERBAIKAN UTAMA: Tambahkan ?? null
                            'kuesioner_pertanyaan_aspect' => $qData['kuesioner_pertanyaan_aspect'] ?? null,
                            'kuesioner_pertanyaan_is_icon' => $qData['kuesioner_pertanyaan_is_icon'] ?? 0,
                            'kuesioner_pertanyaan_created_by' => $userId,
                            'kuesioner_pertanyaan_created_date' => $now,
                            'kuesioner_pertanyaan_status' => 1,
                        ];

                        if (! $isHeader) {
                            // Menggunakan ?? null juga disarankan disini untuk keamanan ekstra
                            $pertanyaanType = $qData['kuesioner_pertanyaan_template_jawaban_type'] ?? null;
                            $pertanyaanData['kuesioner_pertanyaan_template_jawaban_type'] = $pertanyaanType;

                            if ($pertanyaanType == 1) {
                                $pertanyaanData['kuesioner_pertanyaan_template_jawaban_group_uuid'] =
                                    $qData['kuesioner_pertanyaan_template_jawaban_group_uuid'] ?? null;
                            }
                        }

                        if ($isHeader) {
                            $pertanyaanData['kuesioner_pertanyaan_parameter_uuid'] = $qData['kuesioner_pertanyaan_parameter_uuid'] ?? null;
                        }

                        $pertanyaan = KuesionerPertanyaan::create($pertanyaanData);

                        if ($isHeader) {
                            $uuidMapping[$qData['temp_uuid']] = $pertanyaanUuid;
                        }

                        // C. SIMPAN OPSI JAWABAN CUSTOM (Type 2 dan 3)
                        // Tambahkan validasi isset sebelum akses type
                        $jawabanType = $qData['kuesioner_pertanyaan_template_jawaban_type'] ?? null;

                        if (isset($qData['answers'])) {
                            $jawabanRecords = [];
                            foreach ($qData['answers'] as $aData) {
                                $jawabanRecords[] = [
                                    'kuesioner_jawaban_uuid' => Str::uuid()->toString(),
                                    'kuesioner_jawaban_kuesioner_pertanyaan_uuid' => $pertanyaanUuid,
                                    'kuesioner_jawaban_type' => $jawabanType,
                                    'kuesioner_jawaban_code' => $aData['kuesioner_jawaban_code'],
                                    'kuesioner_jawaban_nama' => $aData['kuesioner_jawaban_nama'],
                                    'kuesioner_jawaban_bobot' => $aData['kuesioner_jawaban_bobot'],
                                    'kuesioner_jawaban_icon' => $aData['kuesioner_jawaban_icon'] ?? null,
                                    'kuesioner_jawaban_created_by' => $userId,
                                    'kuesioner_jawaban_created_date' => $now,
                                    'kuesioner_jawaban_status' => 1,
                                ];
                            }
                            KuesionerJawaban::insert($jawabanRecords);
                        }
                    }
                }

                return ['success' => true, 'message' => 'Kuesioner berhasil disimpan!'];
            });

            // Jika transaksi berhasil
            return redirect('/kuesioner/manajemen-layanan')->with('success', 'Kuesioner berhasil disimpan!');
            // return redirect('/kuesioner/manajemen_layanan')->with('success', $result['message']);

        } catch (\Exception $e) {

            echo "<pre>🚨 ERROR FATAL TERJADI:\n";
            echo 'Message: '.$e->getMessage()."\n";
            echo 'File: '.$e->getFile().' (Line: '.$e->getLine().')</pre>';
            exit();
        }
    }

    public function manajemen_layanan_update(Request $request, $uuid)
    {
        // 1. Validasi Data Utama
        $request->validate([
            'kuesioner_layanan_tahun' => 'required|integer',
            'kuesioner_layanan_nama' => 'required|string|max:255',
            'kuesioner_layanan_tanggal_mulai' => 'required|date',
            'kuesioner_layanan_tanggal_selesai' => 'nullable|date|after_or_equal:kuesioner_layanan_tanggal_mulai',
        ]);

        DB::beginTransaction();
        try {
            $userId = Auth::id() ?? 0;
            $now = now();
            $uuidMapping = [];

            // A. UPDATE DATA UTAMA LAYANAN
            $layanan = KuesionerLayanan::where('kuesioner_layanan_uuid', $uuid)->firstOrFail();
            $layanan->update([
                'kuesioner_layanan_tahun' => $request->kuesioner_layanan_tahun,
                'kuesioner_layanan_nama' => $request->kuesioner_layanan_nama,
                'kuesioner_layanan_tanggal_mulai' => $request->kuesioner_layanan_tanggal_mulai,
                'kuesioner_layanan_tanggal_selesai' => $request->kuesioner_layanan_tanggal_selesai,
                'kuesioner_layanan_is_saran' => $request->kuesioner_layanan_is_saran ?? 0,
                'kuesioner_layanan_update_by' => $userId,
                'kuesioner_layanan_update_date' => $now,
            ]);

            // B. HAPUS DATA PERTANYAAN & JAWABAN LAMA
            // Ambil semua UUID pertanyaan yang terhubung dengan layanan ini
            $oldQuestionUuids = DB::table('kuesioner_pertanyaan')
                ->where('kuesioner_pertanyaan_layanan_uuid', $uuid)
                ->pluck('kuesioner_pertanyaan_uuid');

            // Hapus jawaban responden (opsional, tergantung kebijakan data Anda)
            // Hapus Opsi Jawaban Custom
            DB::table('kuesioner_jawaban')->whereIn('kuesioner_jawaban_kuesioner_pertanyaan_uuid', $oldQuestionUuids)->delete();
            // Hapus Pertanyaan
            DB::table('kuesioner_pertanyaan')->where('kuesioner_pertanyaan_layanan_uuid', $uuid)->delete();

            // C. RE-INSERT PERTANYAAN & JAWABAN (Logika sama dengan Save)
            if ($request->has('questions')) {
                // Urutan iterasi sangat penting: Header harus diproses lebih dulu agar Mapping UUID tersedia untuk Child
                foreach ($request->questions as $tempId => $qData) {
                    $pertanyaanUuid = Str::uuid()->toString();
                    $isHeader = filter_var($qData['is_header'], FILTER_VALIDATE_BOOLEAN);

                    $parentUuid = null;
                    // Jika ini bukan header, cari parent_uuid-nya dari mapping temp_uuid
                    if (! $isHeader && isset($qData['kuesioner_pertanyaan_parent_uuid'])) {
                        $parentTempId = $qData['kuesioner_pertanyaan_parent_uuid'];
                        $parentUuid = $uuidMapping[$parentTempId] ?? null;
                    }

                    $pertanyaanData = [
                        'kuesioner_pertanyaan_layanan_uuid' => $uuid,
                        'kuesioner_pertanyaan_uuid' => $pertanyaanUuid,
                        'kuesioner_pertanyaan_parent_uuid' => $parentUuid,
                        'kuesioner_pertanyaan_kode' => $qData['kuesioner_pertanyaan_kode'],
                        'kuesioner_pertanyaan_nama' => $qData['kuesioner_pertanyaan_nama'],
                        'kuesioner_pertanyaan_aspect' => $qData['kuesioner_pertanyaan_aspect'] ?? null,
                        'kuesioner_pertanyaan_is_icon' => $qData['kuesioner_pertanyaan_is_icon'] ?? 0,
                        'kuesioner_pertanyaan_created_by' => $userId,
                        'kuesioner_pertanyaan_created_date' => $now,
                        'kuesioner_pertanyaan_status' => 1,
                    ];

                    if (! $isHeader) {
                        $pType = $qData['kuesioner_pertanyaan_template_jawaban_type'] ?? null;
                        $pertanyaanData['kuesioner_pertanyaan_template_jawaban_type'] = $pType;

                        if ($pType == 1) {
                            $pertanyaanData['kuesioner_pertanyaan_template_jawaban_group_uuid'] =
                                $qData['kuesioner_pertanyaan_template_jawaban_group_uuid'] ?? null;
                        }
                    }

                    if ($isHeader) {
                        $pertanyaanData['kuesioner_pertanyaan_parameter_uuid'] = $qData['kuesioner_pertanyaan_parameter_uuid'] ?? null;
                        // Simpan mapping antara ID sementara dari form dengan UUID baru di DB
                        if (isset($qData['temp_uuid'])) {
                            $uuidMapping[$qData['temp_uuid']] = $pertanyaanUuid;
                        }
                    }

                    DB::table('kuesioner_pertanyaan')->insert($pertanyaanData);

                    // SIMPAN OPSI JAWABAN (Jika Tipe 1, 2, atau 3)
                    if (isset($qData['answers']) && ! empty($qData['answers'])) {
                        $jawabanRecords = [];
                        foreach ($qData['answers'] as $aData) {
                            $jawabanRecords[] = [
                                'kuesioner_jawaban_uuid' => Str::uuid()->toString(),
                                'kuesioner_jawaban_kuesioner_pertanyaan_uuid' => $pertanyaanUuid,
                                'kuesioner_jawaban_type' => $qData['kuesioner_pertanyaan_template_jawaban_type'] ?? null,
                                'kuesioner_jawaban_code' => $aData['kuesioner_jawaban_code'],
                                'kuesioner_jawaban_nama' => $aData['kuesioner_jawaban_nama'],
                                'kuesioner_jawaban_bobot' => $aData['kuesioner_jawaban_bobot'],
                                'kuesioner_jawaban_icon' => $aData['kuesioner_jawaban_icon'] ?? null,
                                'kuesioner_jawaban_created_by' => $userId,
                                'kuesioner_jawaban_created_date' => $now,
                                'kuesioner_jawaban_status' => 1,
                            ];
                        }
                        DB::table('kuesioner_jawaban')->insert($jawabanRecords);
                    }
                }
            }

            DB::commit();

            return redirect('/kuesioner/manajemen-layanan')->with('success', 'Layanan Kuesioner Berhasil Diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->with('error', 'Gagal memperbarui data: '.$e->getMessage())->withInput();
        }
    }

    public function gettemplateoptions($groupUuid)
    {
        // Note: Sesuaikan query jika kolom uuid di child table tipe varbinary/varchar
        $options = DB::table('kuesioner_template_jawaban')
            ->where('kuesioner_template_jawaban_group_uuid', $groupUuid)
            ->where('kuesioner_template_jawaban_status', 1)
            ->orderBy('kuesioner_template_jawaban_code', 'asc') // Atau urutan lain
            ->get();

        return response()->json($options);
    }

    public function manajemen_layanan_list()
    {
        $data = ViewKuesionerLayanan::orderBy('kuesioner_layanan_created_date', 'desc')
            ->get();

        $datatable = DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('kuesioner_layanan_kode', function ($row) {
                return $row->kuesioner_layanan_kode;
            })
            ->addColumn('kuesioner_layanan_tahun', function ($row) {
                return $row->kuesioner_layanan_tahun;
            })

            ->addColumn('kuesioner_layanan_nama', function ($row) {
                return $row->kuesioner_layanan_nama;
            })

            ->addColumn('kuesioner_layanan_jumlah_pertanyaan', function ($row) {
                return $row->kuesioner_layanan_jumlah_pertanyaan;
            })
            ->addColumn('periode', function ($row) {
                return Gudangfungsi::tanggalindoshort($row->kuesioner_layanan_tanggal_mulai).(($row->kuesioner_layanan_tanggal_selesai != null) ? ' - '.Gudangfungsi::tanggalindoshort($row->kuesioner_layanan_tanggal_selesai) : '');
            })
            ->addColumn('kuesioner_layanan_created_date', function ($row) {
                return Gudangfungsi::tanggalindoshort($row->kuesioner_layanan_created_date);
            })

            ->addColumn('kuesioner_layanan_publish_date', function ($row) {
                return Gudangfungsi::tanggalindoshort($row->kuesioner_layanan_publish_date);
            })
            ->addColumn('kuesioner_layanan_status_name', function ($row) {
                return $row->kuesioner_layanan_status_name;
            })
            ->addColumn('action', function ($row) {
                $actionBtn = '
    <a href="javascript:void(0);" onclick="copy_url(this)" title="Copy Url" class="btn btn-sm waves-effect waves-light btn-primary m-b-0" data-uuid="'.$row->kuesioner_layanan_uuid.'" style="padding-bottom:0px;padding-top:0px;">
        <i class="feather icon-copy"></i>
    </a>
    <a href="'.url('/kuesioner/manajemen-layanan-edit/'.$row->kuesioner_layanan_uuid).'" title="Edit" class="btn btn-sm waves-effect waves-light btn-info m-b-0" style="padding-bottom:0px;padding-top:0px;">
        <i class="feather icon-edit-2"></i>
    </a>
    <button type="button" onclick="hapus(\''.$row->kuesioner_layanan_uuid.'\')" title="Hapus" class="btn btn-sm waves-effect waves-light btn-danger m-b-0" style="padding-bottom:0px;padding-top:0px;">
        <i class="feather icon-trash-2"></i>
    </button>';

                return $actionBtn;
            })
            ->rawColumns(['kuesioner_layanan_kode', 'kuesioner_layanan_tahun', 'kuesioner_layanan_nama', 'kuesioner_layanan_jumlah_pertanyaan', 'kuesioner_layanan_tanggal_mulai', 'kuesioner_layanan_publish_date', 'kuesioner_layanan_created_date', 'kuesioner_layanan_status_name', 'action'])
            ->make(true);

        return $datatable;
    }

    public function add()
    {
        $data['judulmodal'] = 'Tambah Reformasi Birokrasi';
        $data['kategori'] = DB::table('rb_kategori')->where('slug', '<>', 'berita-rb')->orderBy('urutan', 'asc');

        return view('dapur.rb.add', $data);
    }

    public function save(Request $req)
    {
        $kategori_rb = $req->post('kategori_rb');
        $judul = $req->post('judul');
        $judul_en = $req->post('judul_en');
        $deskripsi = $req->post('deskripsi');
        $deskripsi_en = $req->post('deskripsi_en');
        $tanggal_publikasi = $req->post('tanggal_publikasi');

        if ($req->hasFile('gambar')) {
            $file = $req->file('gambar');
            $namafileFull = $file->getClientOriginalName();
            $namafileOri = pathinfo($namafileFull, PATHINFO_FILENAME);
            $ekstensi = $file->getClientOriginalExtension();
            $namafile = $namafileOri.'_'.time().'.'.$ekstensi;

            $file->move('public/uploads/rb-image', "{$namafile}");
        } else {
            $namafile = '';
        }

        if ($req->hasFile('berkas')) {
            $file = $req->file('berkas');
            $namafileFull = $file->getClientOriginalName();
            $namafileOri = pathinfo($namafileFull, PATHINFO_FILENAME);
            $ekstensi = $file->getClientOriginalExtension();
            $namaberkas = $namafileOri.'_'.time().'.'.$ekstensi;

            $file->move('public/uploads/rb', "{$namaberkas}");
        } else {
            $namaberkas = '';
        }

        $data = [
            'id_rbkategori' => $kategori_rb,
            'judul' => $judul,
            'judul_en' => $judul_en,
            'deskripsi' => $deskripsi,
            'deskripsi_en' => $deskripsi_en,
            'gambar_sampul' => $namafile,
            'berkas' => $namaberkas,
            'tanggal_publikasi' => $tanggal_publikasi,
            'created_at' => date('Y-m-d H:i:s'),
        ];

        try {
            $simpan = DB::table('rb')->insert($data);

            if ($simpan) {
                $response = ['result' => 'success', 'message' => 'Save successfully'];
            } else {
                $response = ['result' => 'failed', 'message' => 'Save failed'];
            }
        } catch (\Illuminate\Database\QueryException $e) {
            $errorCode = $e->errorInfo[1];
            if ($errorCode == 1062) {
                $response = ['result' => 'failed', 'message' => 'Duplicate key found.'];
            }
        }

        return response()->json($response);
    }

    public function edit(Request $req)
    {
        $id = $req->get('id');

        $data['judulmodal'] = 'Edit Reformasi Birokrasi';
        $data['kategori'] = DB::table('rb_kategori')->where('slug', '<>', 'berita-rb')->orderBy('urutan', 'asc');
        $data['data'] = DB::table('rb as re')
            ->join('rb_kategori as kat', 're.id_rbkategori', '=', 'kat.id_rbkategori')
            ->where('re.id_rb', $id)->first();

        return view('dapur.rb.edit', $data);
    }

    public function saveupdate(Request $req)
    {
        $id_rb = $req->post('id_rb');
        $kategori_rb = $req->post('kategori_rb');
        $judul = $req->post('judul');
        $judul_en = $req->post('judul_en');
        $deskripsi = $req->post('deskripsi');
        $deskripsi_en = $req->post('deskripsi_en');
        $tanggal_publikasi = $req->post('tanggal_publikasi');

        if ($req->hasFile('gambar')) {
            $file = $req->file('gambar');
            $namafileFull = $file->getClientOriginalName();
            $namafileOri = pathinfo($namafileFull, PATHINFO_FILENAME);
            $ekstensi = $file->getClientOriginalExtension();
            $namafile = $namafileOri.'_'.time().'.'.$ekstensi;

            $file->move('public/uploads/rb-image', "{$namafile}");
        } else {
            $namafile = $req->post('gambar_current');
        }

        if ($req->hasFile('berkas')) {
            $file = $req->file('berkas');
            $namafileFull = $file->getClientOriginalName();
            $namafileOri = pathinfo($namafileFull, PATHINFO_FILENAME);
            $ekstensi = $file->getClientOriginalExtension();
            $namaberkas = $namafileOri.'_'.time().'.'.$ekstensi;

            $file->move('public/uploads/rb', "{$namaberkas}");
        } else {
            $namaberkas = $req->post('berkas_current');
        }

        $data = [
            'id_rbkategori' => $kategori_rb,
            'judul' => $judul,
            'judul_en' => $judul_en,
            'deskripsi' => $deskripsi,
            'deskripsi_en' => $deskripsi_en,
            'gambar_sampul' => $namafile,
            'berkas' => $namaberkas,
            'tanggal_publikasi' => $tanggal_publikasi,
            'updated_at' => date('Y-m-d H:i:s'),
        ];

        try {
            $simpan = DB::table('rb')->where('id_rb', $id_rb)->update($data);

            if ($simpan) {
                $response = ['result' => 'success', 'message' => 'Save successfully'];
            } else {
                $response = ['result' => 'failed', 'message' => 'Save failed'];
            }
        } catch (\Illuminate\Database\QueryException $e) {
            $errorCode = $e->errorInfo[1];
            if ($errorCode == 1062) {
                $response = ['result' => 'failed', 'message' => 'Duplicate key found.'];
            }
        }

        return response()->json($response);
    }

    public function delete(Request $req)
    {
        $id = $req->post('id');

        $data = DB::table('rb')->where('id_rb', $id);
        if ($data->count() != 0) {
            $gambarsampul = $data->first()->gambar_sampul;
            $berkas = $data->first()->berkas;

            if (File::exists('public/uploads/rb-image/'.$gambarsampul) == true) {
                File::delete('public/uploads/rb-image/'.$gambarsampul);
            }

            if (File::exists('public/uploads/rb/'.$berkas) == true) {
                File::delete('public/uploads/rb'.$berkas);
            }
        }

        $hapus = DB::table('rb')->where('id_rb', $id)->delete();

        if ($hapus) {
            $response = ['result' => 'success', 'message' => 'Deleting data successfully'];
        } else {
            $response = ['result' => 'failed', 'message' => 'Deleteting data failed'];
        }

        return response()->json($response);
    }

    // ... (kode index dan list yang sudah ada) ...

    // ---------------------------------------------------------
    // MANAJEMEN TEMPLATE JAWABAN (CRUD)
    // ---------------------------------------------------------

    public function manajemen_template_jawaban_create()
    {
        $data['judulhalaman'] = 'Tambah Template Jawaban';
        $data['act'] = 'store';

        return view('dapur.kuesioner.manajemen.templatejawaban.create', $data);
    }

    public function manajemen_template_jawaban_save(Request $request)
    {
        DB::beginTransaction();
        try {
            $groupUuid = Str::uuid();
            $isIcon = $request->has('is_icon') ? 1 : 0;
            $userId = Auth::id() ?? 0;
            $now = date('Y-m-d H:i:s');

            // 1. Simpan Group (Parent)
            DB::table('kuesioner_template_jawaban_group')->insert([
                'kuesioner_template_jawaban_group_uuid' => $groupUuid,
                'kuesioner_template_jawaban_group_nama' => $request->nama_group,
                'kuesioner_template_jawaban_group_keterangan' => $request->keterangan,
                'kuesioner_template_jawaban_group_is_icon' => $isIcon,
                'kuesioner_template_jawaban_group_created_date' => $now,
                'kuesioner_template_jawaban_group_created_by' => $userId,
                'kuesioner_template_jawaban_group_status' => 1,
            ]);

            // 2. Simpan Jawaban (Child)
            if ($request->has('jawaban')) {
                foreach ($request->jawaban as $key => $row) {
                    $iconName = null;

                    // Handle Upload Icon
                    if ($isIcon == 1 && $request->hasFile("jawaban.$key.icon")) {
                        $file = $request->file("jawaban.$key.icon");
                        $ext = $file->getClientOriginalExtension();

                        // Nama Unik
                        $iconName = time().'_'.Str::random(10).'.'.$ext;

                        // --- FIX: GUNAKAN public_path() ---
                        // Ini memastikan file masuk ke folder: public/survey
                        $file->move(public_path('assets_survey'), $iconName);
                    }

                    DB::table('kuesioner_template_jawaban')->insert([
                        'kuesioner_template_jawaban_uuid' => Str::uuid(),
                        'kuesioner_template_jawaban_group_uuid' => $groupUuid,
                        'kuesioner_template_jawaban_code' => $row['code'],
                        'kuesioner_template_jawaban_nama' => $row['nama'],
                        'kuesioner_template_jawaban_bobot' => $row['bobot'],
                        'kuesioner_template_jawaban_icon' => $iconName,
                        'kuesioner_template_jawaban_created_date' => $now,
                        'kuesioner_template_jawaban_created_by' => $userId,
                        'kuesioner_template_jawaban_status' => 1,
                    ]);
                }
            }

            DB::commit();

            return redirect('/kuesioner/manajemen-template-jawaban')->with('success', 'Data berhasil disimpan');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->with('error', 'Gagal menyimpan: '.$e->getMessage())->withInput();
        }
    }

    public function survey($uuid)
    {
        $data['layanan'] = KuesionerLayanan::where('kuesioner_layanan_uuid', $uuid)
            ->where('kuesioner_layanan_status', 1)
            ->firstOrFail();

        $data['pertanyaan'] = KuesionerPertanyaan::with(['children.jawaban'])
            ->where('kuesioner_pertanyaan_layanan_uuid', $uuid)
            ->whereNull('kuesioner_pertanyaan_parent_uuid')
            ->where('kuesioner_pertanyaan_status', 1)
            ->orderBy('kuesioner_pertanyaan_kode', 'asc')
            ->get();

        // Tambah ini
        $data['ref_instansi'] = DB::table('kuesioner_referensi')
            ->where('referensi_kategori', 'INSTANSI')
            ->where('referensi_status', 1)
            ->orderBy('referensi_urutan', 'asc')
            ->get();

        $data['ref_apk'] = DB::table('kuesioner_referensi')
            ->where('referensi_kategori', 'APK')
            ->where('referensi_status', 1)
            ->orderBy('referensi_urutan', 'asc')
            ->get();

        $data['ref_kementerian'] = DB::table('kuesioner_referensi')
            ->where('referensi_kategori', 'KEMENTERIAN')
            ->where('referensi_status', 1)
            ->orderBy('referensi_urutan', 'asc')
            ->get();

        return view('depan.survey_create', $data);
    }

    public function manajemen_template_jawaban_update(Request $request, $uuid)
    {
        DB::beginTransaction();
        try {
            $isIcon = $request->has('is_icon') ? 1 : 0;
            $userId = Auth::id() ?? 0;
            $now = date('Y-m-d H:i:s');

            // 1. Update Group
            DB::table('kuesioner_template_jawaban_group')
                ->where('kuesioner_template_jawaban_group_uuid', $uuid)
                ->update([
                    'kuesioner_template_jawaban_group_nama' => $request->nama_group,
                    'kuesioner_template_jawaban_group_keterangan' => $request->keterangan,
                    'kuesioner_template_jawaban_group_is_icon' => $isIcon,
                    'kuesioner_template_jawaban_group_update_date' => $now,
                    'kuesioner_template_jawaban_group_update_by' => $userId,
                ]);

            // 2. Handle Jawaban
            $submittedIds = [];

            if ($request->has('jawaban')) {
                foreach ($request->jawaban as $key => $row) {
                    $childUuid = isset($row['uuid']) && ! empty($row['uuid']) ? $row['uuid'] : Str::uuid()->toString();
                    $iconName = $row['old_icon'] ?? null;

                    // Handle New Upload
                    if ($isIcon == 1 && $request->hasFile("jawaban.$key.icon")) {
                        $file = $request->file("jawaban.$key.icon");
                        $ext = $file->getClientOriginalExtension();
                        $newIconName = time().'_'.Str::random(10).'.'.$ext;

                        // Hapus file lama jika ada (Cek fisik file dulu)
                        if (! empty($iconName) && File::exists(public_path('survey/'.$iconName))) {
                            File::delete(public_path('assets_survey/'.$iconName));
                        }

                        // --- FIX: GUNAKAN public_path() ---
                        $file->move(public_path('assets_survey'), $newIconName);
                        $iconName = $newIconName; // Update nama file untuk DB
                    }

                    // Cek DB
                    $exist = DB::table('kuesioner_template_jawaban')
                        ->where('kuesioner_template_jawaban_uuid', $childUuid)
                        ->first();

                    if ($exist) {
                        DB::table('kuesioner_template_jawaban')
                            ->where('kuesioner_template_jawaban_uuid', $childUuid)
                            ->update([
                                'kuesioner_template_jawaban_code' => $row['code'],
                                'kuesioner_template_jawaban_nama' => $row['nama'],
                                'kuesioner_template_jawaban_bobot' => $row['bobot'],
                                'kuesioner_template_jawaban_icon' => $iconName,
                                'kuesioner_template_jawaban_update_date' => $now,
                                'kuesioner_template_jawaban_update_by' => $userId,
                                'kuesioner_template_jawaban_status' => 1,
                            ]);
                    } else {
                        DB::table('kuesioner_template_jawaban')->insert([
                            'kuesioner_template_jawaban_uuid' => $childUuid,
                            'kuesioner_template_jawaban_group_uuid' => $uuid,
                            'kuesioner_template_jawaban_code' => $row['code'],
                            'kuesioner_template_jawaban_nama' => $row['nama'],
                            'kuesioner_template_jawaban_bobot' => $row['bobot'],
                            'kuesioner_template_jawaban_icon' => $iconName,
                            'kuesioner_template_jawaban_created_date' => $now,
                            'kuesioner_template_jawaban_created_by' => $userId,
                            'kuesioner_template_jawaban_status' => 1,
                        ]);
                    }
                    $submittedIds[] = $childUuid;
                }
            }

            // 3. Soft Delete
            DB::table('kuesioner_template_jawaban')
                ->where('kuesioner_template_jawaban_group_uuid', $uuid)
                ->whereNotIn('kuesioner_template_jawaban_uuid', $submittedIds)
                ->update([
                    'kuesioner_template_jawaban_status' => 0,
                    'kuesioner_template_jawaban_update_date' => $now,
                    'kuesioner_template_jawaban_update_by' => $userId,
                ]);

            DB::commit();

            return redirect('/kuesioner/manajemen-template-jawaban')->with('success', 'Data berhasil diperbarui');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->with('error', 'Gagal update: '.$e->getMessage())->withInput();
        }
    }

    public function manajemen_template_jawaban_edit($uuid)
    {
        $data['judulhalaman'] = 'Edit Template Jawaban';
        $data['act'] = 'update';

        $data['group'] = DB::table('kuesioner_template_jawaban_group')
            ->where('kuesioner_template_jawaban_group_uuid', $uuid)
            ->first();

        if (! $data['group']) {
            return redirect('/kuesioner/manajemen-template-jawaban')->with('error', 'Data tidak ditemukan');
        }

        $data['jawaban'] = DB::table('kuesioner_template_jawaban')
            ->where('kuesioner_template_jawaban_group_uuid', $uuid)
            ->where('kuesioner_template_jawaban_status', 1)
            ->orderBy('kuesioner_template_jawaban_bobot', 'asc') // Opsional: urutkan
            ->get();

        return view('dapur.kuesioner.manajemen.templatejawaban.edit', $data);
    }

    public function manajemen_template_jawaban_delete(Request $request)
    {
        $uuid = $request->uuid; // Tangkap UUID

        DB::beginTransaction();
        try {
            // 1. Cek Data
            $group = DB::table('kuesioner_template_jawaban_group')
                ->where('kuesioner_template_jawaban_group_uuid', $uuid)
                ->first();

            if (! $group) {
                return response()->json(['result' => 'failed', 'message' => 'Data tidak ditemukan']);
            }

            // 2. Hapus Icon Fisik (Jika Ada)
            if ($group->kuesioner_template_jawaban_group_is_icon == 1) {
                $childs = DB::table('kuesioner_template_jawaban')
                    ->where('kuesioner_template_jawaban_group_uuid', $uuid)
                    ->get();

                foreach ($childs as $child) {
                    if (! empty($child->kuesioner_template_jawaban_icon)) {
                        $path = public_path('survey/'.$child->kuesioner_template_jawaban_icon);
                        if (File::exists($path)) {
                            File::delete($path);
                        }
                    }
                }
            }

            // 3. Hapus Data Child & Parent (Hard Delete)
            DB::table('kuesioner_template_jawaban')->where('kuesioner_template_jawaban_group_uuid', $uuid)->delete();
            DB::table('kuesioner_template_jawaban_group')->where('kuesioner_template_jawaban_group_uuid', $uuid)->delete();

            DB::commit();

            return response()->json(['result' => 'success', 'message' => 'Data berhasil dihapus']);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json(['result' => 'failed', 'message' => 'Gagal menghapus: '.$e->getMessage()]);
        }
    }

    public function manajemen_layanan_delete($uuid)
    {
        DB::beginTransaction();
        try {
            $layanan = KuesionerLayanan::where('kuesioner_layanan_uuid', $uuid)->first();
            if (! $layanan) {
                return response()->json(['result' => 'failed', 'message' => 'Data tidak ditemukan']);
            }

            // Hapus pertanyaan & jawaban terkait
            $questionUuids = DB::table('kuesioner_pertanyaan')
                ->where('kuesioner_pertanyaan_layanan_uuid', $uuid)
                ->pluck('kuesioner_pertanyaan_uuid');

            DB::table('kuesioner_jawaban')->whereIn('kuesioner_jawaban_kuesioner_pertanyaan_uuid', $questionUuids)->delete();
            DB::table('kuesioner_pertanyaan')->where('kuesioner_pertanyaan_layanan_uuid', $uuid)->delete();
            $layanan->delete();

            DB::commit();

            return response()->json(['result' => 'success', 'message' => 'Data berhasil dihapus']);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json(['result' => 'failed', 'message' => $e->getMessage()]);
        }
    }
    // ---------------------------------------------------------
    // END MANAJEMEN TEMPLATE JAWABAN
    // ---------------------------------------------------------
}
