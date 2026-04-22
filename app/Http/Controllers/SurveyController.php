<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\Gudangfungsi;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;
use App\Models\ViewKuesionerTemplateJawabanGroup;
use App\Models\ViewKuesionerLayanan;
use App\Models\KuesionerLayanan;
use App\Models\KuesionerPertanyaan;
use App\Models\KuesionerTemplateJawabanGroup;
use App\Models\KuesionerJawaban;
use Illuminate\Database\Eloquent\MassAssignmentException;
use Illuminate\Database\QueryException;
use App\Models\KuesionerResponden;
use App\Models\KuesionerJawabanResponden;
use App\Models\KuesionerReferensi; // <--- JANGAN LUPA INI

class SurveyController extends Controller
{
    public function index()
    {
        $data['judulhalaman'] = 'Reformasi Birokrasi';
        return view('depan.survey_index', $data);
    }

    public function create($uuid)
    {
        // 1. Ambil Data Layanan
        $layanan = KuesionerLayanan::where('kuesioner_layanan_uuid', $uuid)
            ->where('kuesioner_layanan_status', 1)
            ->whereNull('kuesioner_layanan_log_uuid')
            ->firstOrFail();

        // 2. Ambil Template Jawaban (Opsional, jika dipakai di view)
        $templateGroups = ViewKuesionerTemplateJawabanGroup::orderBy(
            'kuesioner_template_jawaban_group_created_date',
            'desc'
        )->get();

        // 3. Ambil Pertanyaan & Jawaban
        $pertanyaan = KuesionerPertanyaan::with(['children', 'jawaban'])
            ->where('kuesioner_pertanyaan_layanan_uuid', $uuid)
            ->whereNull('kuesioner_pertanyaan_parent_uuid')
            ->where('kuesioner_pertanyaan_status', 1)
            ->whereNull('kuesioner_pertanyaan_log_uuid')
            ->orderBy('kuesioner_pertanyaan_kode', 'asc')
            ->get();

        // 4. AMBIL DATA REFERENSI (BARU)
        $data['ref_instansi'] = KuesionerReferensi::where('referensi_kategori', 'INSTANSI')
                                ->where('referensi_status', 1)
                                ->orderBy('referensi_urutan', 'asc')
                                ->get();

        $data['ref_apk'] = KuesionerReferensi::where('referensi_kategori', 'APK')
                                ->where('referensi_status', 1)
                                ->orderBy('referensi_urutan', 'asc')
                                ->get();

        $data['ref_kementerian'] = KuesionerReferensi::where('referensi_kategori', 'KEMENTERIAN')
                                ->where('referensi_status', 1)
                                ->orderBy('referensi_urutan', 'asc')
                                ->get();

        $data['judulhalaman'] = 'Reformasi Birokrasi';
        $data['layanan'] = $layanan;
        $data['templateGroups'] = $templateGroups;
        $data['pertanyaan'] = $pertanyaan;
        $data['totalPertanyaan'] = $pertanyaan->sum(fn($p) => $p->children->count());

        return view('depan.survey_create', $data);
    }

    public function success()
    {
        $data['judulhalaman'] = 'Survei Berhasil Disimpan';
        if (!session()->has('responden_nama') || !session()->has('layanan_nama')) {
            abort(404);
        }
        return view('depan.survey_success', $data);
    }

    // ======================================================
    // SAVE SURVEY (MATCH FORM & DB)
    // ======================================================
   public function save(Request $request)
    {
        // ================= VALIDASI =================
        $request->validate([
            'kuesioner_responden_uuid' => 'required|uuid|unique:kuesioner_responden,kuesioner_responden_uuid',
            'kuesioner_layanan_uuid'   => 'required|uuid|exists:kuesioner_layanan,kuesioner_layanan_uuid',
            'kuesioner_responden_nama'  => 'required|string|max:255',
            'kuesioner_responden_email' => 'required|email|max:255',
            'kuesioner_responden_telp'  => 'required|string|max:255',
            
            // Validasi Input Baru (UUID)
            'kuesioner_responden_instansi_asal_uuid' => 'required', // Bisa tambah |exists:kuesioner_referensi,referensi_uuid
            
            'jawaban' => 'required|array',
            'jawaban.*.kuesioner_jawaban_uuid' => 'required|uuid',
        ]);

        try {
            DB::transaction(function () use ($request) {

                // ================= SIMPAN RESPONDEN =================
                KuesionerResponden::create([
                    'kuesioner_responden_uuid' => $request->kuesioner_responden_uuid,
                    'kuesioner_layanan_uuid'   => $request->kuesioner_layanan_uuid,
                    'kuesioner_responden_nama' => $request->kuesioner_responden_nama,
                    'kuesioner_responden_email' => $request->kuesioner_responden_email,
                    'kuesioner_responden_telp' => $request->kuesioner_responden_telp,
                    'kuesioner_responden_saran' => $request->kuesioner_responden_saran ?: '',
                    
                    // --- UPDATE SAVE KE KOLOM UUID ---
                    'kuesioner_responden_instansi_asal_uuid' 
                        => $request->kuesioner_responden_instansi_asal_uuid,
                        
                    'kuesioner_responden_pemangku_kepentingan_uuid' 
                        => $request->kuesioner_responden_pemangku_kepentingan_uuid,
                        
                    'kuesioner_responden_kementerian_lembaga_uuid' 
                        => $request->kuesioner_responden_kementerian_lembaga_uuid,
                    
                    // Kolom teks lama boleh dikosongkan atau dihapus dari create
                    // 'kuesioner_responden_instansi_asal' => ..., (HAPUS)
                    
                    'kuesioner_responden_created_by' => null,
                    'kuesioner_responden_created_date' => now(),
                ]);

                // ... (Kode simpan jawaban tetap sama) ...
                foreach ($request->jawaban as $pertanyaanUuid => $data) {
                    KuesionerJawabanResponden::create([
                        'kuesioner_jawaban_responden_uuid' => Str::uuid(),
                        'kuesioner_responden_uuid'         => $request->kuesioner_responden_uuid,
                        'kuesioner_pertanyaan_uuid'        => $pertanyaanUuid,
                        'kuesioner_jawaban_uuid'           => $data['kuesioner_jawaban_uuid'],
                        'kuesioner_jawaban_responden_created_by' => null,
                        'kuesioner_jawaban_responden_created_date' => now(),
                    ]);
                }
            });
        } catch (QueryException $e) {
            echo '<pre>';
            print_r($e->getMessage());
            echo '</pre>';
            die();
            return back()->withErrors([
                'error' => 'Gagal menyimpan survei. ' . $e->getMessage()
            ]);
        }
        
        return redirect()
            ->route('survey.success')
            ->with('responden_nama', $request->kuesioner_responden_nama)
            ->with('layanan_nama', $request->kuesioner_layanan_nama);
    }
}