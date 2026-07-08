<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class RecoverJawabanResponden extends Command
{
    protected $signature = 'kuesioner:recover
                            {layanan_uuid : UUID layanan}
                            {csv=recovery.csv : nama file csv di storage/app}
                            {--commit : jalankan beneran (default dry-run)}';

    protected $description = 'Rekonstruksi jawaban responden yang UUID pertanyaannya sudah berubah';

    public function handle()
    {
        $layananUuid = $this->argument('layanan_uuid');
        $csvPath = storage_path('app/'.$this->argument('csv'));
        $commit = $this->option('commit');

        $this->info("DEBUG: Layanan UUID diterima = [$layananUuid]");
        $this->info('DEBUG: Database aktif = '.DB::connection()->getDatabaseName());
        $this->info('DEBUG: Host = '.config('database.connections.mysql.host').':'.config('database.connections.mysql.port'));

        $totalCek = DB::table('kuesioner_responden')->where('kuesioner_layanan_uuid', $layananUuid)->count();
        $this->info("DEBUG: Total responden ditemukan Laravel = $totalCek");

        if (! file_exists($csvPath)) {
            $this->error("File tidak ditemukan: $csvPath");

            return 1;
        }

        // ==========================================================
        // 1. PARSE CSV
        // ==========================================================
        $rows = [];
        if (($handle = fopen($csvPath, 'r')) !== false) {
            while (($data = fgetcsv($handle)) !== false) {
                $rows[] = $data;
            }
            fclose($handle);
        }

        $header = $rows[0]; // No, Kode, Parameter, Aspek, Nama1, Nama2, ..., Rata-Rata
        $namaKolom = array_slice($header, 4, count($header) - 5); // exclude 4 depan & Rata-Rata belakang
        $jumlahResponden = count($namaKolom);

        $this->info("Ditemukan {$jumlahResponden} kolom responden di CSV.");

        // Kelompokkan baris jadi per parameter (tiap parameter = 3 baris: Importance, Performance, Gap)
        $parameters = [];
        $currentKode = null;
        for ($i = 1; $i < count($rows); $i++) {
            $row = $rows[$i];
            if (! empty($row[1])) {
                $currentKode = trim($row[1]);
                $parameters[$currentKode] = ['importance' => [], 'performance' => []];
            }
            $aspek = trim($row[3]);
            $values = array_slice($row, 4, $jumlahResponden);

            if ($aspek === 'Importance') {
                $parameters[$currentKode]['importance'] = $values;
            } elseif ($aspek === 'Performance') {
                $parameters[$currentKode]['performance'] = $values;
            }
        }

        // ==========================================================
        // 2. DETEKSI RESPONDEN YANG "PUTUS" (jawaban nunjuk pertanyaan mati)
        // ==========================================================
        $brokenRespondens = DB::select('
            SELECT r.kuesioner_responden_uuid, r.kuesioner_responden_nama, r.kuesioner_responden_created_date
            FROM kuesioner_responden r
            WHERE r.kuesioner_layanan_uuid = ?
            AND EXISTS (
                SELECT 1 FROM kuesioner_jawaban_responden jr
                LEFT JOIN kuesioner_pertanyaan p ON jr.kuesioner_pertanyaan_uuid = p.kuesioner_pertanyaan_uuid
                WHERE jr.kuesioner_responden_uuid = r.kuesioner_responden_uuid AND p.kuesioner_pertanyaan_uuid IS NULL
            )
            ORDER BY r.kuesioner_responden_created_date ASC
        ', [$layananUuid]);

        $this->info('Ditemukan '.count($brokenRespondens).' responden yang datanya putus.');

        // ==========================================================
        // 3. MATCHING NAMA (dengan handle duplikat nama via urutan relatif)
        // ==========================================================
        // Index posisi tiap nama di CSV (bisa lebih dari 1 kalau nama sama)
        $csvNameIndexes = [];
        foreach ($namaKolom as $idx => $nama) {
            $key = strtolower(trim($nama));
            $csvNameIndexes[$key][] = $idx;
        }

        // Hitung urutan kemunculan nama yang sama di broken responden (by created_date asc)
        $nameOccurrence = [];
        $mapping = []; // brokenRespondenUuid => csv column index

        foreach ($brokenRespondens as $r) {
            $key = strtolower(trim($r->kuesioner_responden_nama));
            $occIndex = $nameOccurrence[$key] ?? 0;

            if (! isset($csvNameIndexes[$key]) || ! isset($csvNameIndexes[$key][$occIndex])) {
                $this->warn("Tidak ketemu kolom CSV untuk: {$r->kuesioner_responden_nama} (urutan ke-".($occIndex + 1).')');
                $nameOccurrence[$key] = $occIndex + 1;

                continue;
            }

            $mapping[$r->kuesioner_responden_uuid] = [
                'nama' => $r->kuesioner_responden_nama,
                'csv_index' => $csvNameIndexes[$key][$occIndex],
            ];
            $nameOccurrence[$key] = $occIndex + 1;
        }

        $this->info('Berhasil matching '.count($mapping).' dari '.count($brokenRespondens).' responden.');

        // ==========================================================
        // 4. AMBIL STRUKTUR PERTANYAAN BARU (parent + child + bobot jawaban)
        // ==========================================================
        $parents = DB::select('
            SELECT kuesioner_pertanyaan_uuid, kuesioner_pertanyaan_kode
            FROM kuesioner_pertanyaan
            WHERE kuesioner_pertanyaan_layanan_uuid = ? AND kuesioner_pertanyaan_parent_uuid IS NULL
            ORDER BY kuesioner_pertanyaan_kode ASC
        ', [$layananUuid]);

        $questionMap = []; // kode => ['importance' => childUuid, 'performance' => childUuid]
        foreach ($parents as $parent) {
            $children = DB::select('
                SELECT kuesioner_pertanyaan_uuid, kuesioner_pertanyaan_aspect
                FROM kuesioner_pertanyaan
                WHERE kuesioner_pertanyaan_parent_uuid = ?
            ', [$parent->kuesioner_pertanyaan_uuid]);

            foreach ($children as $child) {
                $tipe = $child->kuesioner_pertanyaan_aspect == 1 ? 'importance' : 'performance';
                $questionMap[$parent->kuesioner_pertanyaan_kode][$tipe] = $child->kuesioner_pertanyaan_uuid;
            }
        }

        // Bobot => jawaban_uuid, per pertanyaan_uuid
        $bobotMap = [];
        $allChildUuids = [];
        foreach ($questionMap as $kode => $tipes) {
            foreach ($tipes as $childUuid) {
                $allChildUuids[] = $childUuid;
            }
        }
        $jawabanOptions = DB::table('kuesioner_jawaban')
            ->whereIn('kuesioner_jawaban_kuesioner_pertanyaan_uuid', $allChildUuids)
            ->get();

        foreach ($jawabanOptions as $opt) {
            $bobotMap[$opt->kuesioner_jawaban_kuesioner_pertanyaan_uuid][(string) (int) $opt->kuesioner_jawaban_bobot] = $opt->kuesioner_jawaban_uuid;
        }

        // ==========================================================
        // 5. BUILD RENCANA INSERT
        // ==========================================================
        $toInsert = [];
        $missingCount = 0;

        foreach ($mapping as $respondenUuid => $info) {
            $csvIdx = $info['csv_index'];

            foreach ($questionMap as $kode => $tipes) {
                foreach (['importance', 'performance'] as $tipe) {
                    if (! isset($parameters[$kode][$tipe][$csvIdx])) {
                        continue;
                    }
                    $bobotVal = trim($parameters[$kode][$tipe][$csvIdx]);
                    if ($bobotVal === '' || $bobotVal === '-') {
                        continue;
                    }

                    $childUuid = $tipes[$tipe] ?? null;
                    if (! $childUuid) {
                        continue;
                    }

                    $bobotKey = (string) (int) $bobotVal;
                    $jawabanUuid = $bobotMap[$childUuid][$bobotKey] ?? null;

                    if (! $jawabanUuid) {
                        $missingCount++;
                        $this->warn("Tidak ketemu opsi jawaban bobot={$bobotVal} untuk {$info['nama']} - {$kode} ({$tipe})");

                        continue;
                    }

                    $toInsert[] = [
                        'kuesioner_jawaban_responden_uuid' => Str::uuid()->toString(),
                        'kuesioner_responden_uuid' => $respondenUuid,
                        'kuesioner_pertanyaan_uuid' => $childUuid,
                        'kuesioner_jawaban_uuid' => $jawabanUuid,
                        'kuesioner_jawaban_responden_created_by' => null,
                        'kuesioner_jawaban_responden_created_date' => now(),
                    ];
                }
            }
        }

        $this->info('Total baris jawaban siap direkonstruksi: '.count($toInsert));
        $this->info('Total gagal matching bobot: '.$missingCount);

        if (! $commit) {
            $this->warn('DRY RUN - tidak ada yang ditulis ke DB. Jalankan ulang dengan --commit untuk eksekusi.');

            return 0;
        }

        // ==========================================================
        // 6. EKSEKUSI (hapus baris lama yang putus, insert yang baru)
        // ==========================================================
        DB::transaction(function () use ($mapping, $toInsert) {
            $uuids = array_keys($mapping);
            DB::table('kuesioner_jawaban_responden')->whereIn('kuesioner_responden_uuid', $uuids)->delete();

            foreach (array_chunk($toInsert, 200) as $chunk) {
                DB::table('kuesioner_jawaban_responden')->insert($chunk);
            }
        });

        $this->info('Selesai! Data berhasil direkonstruksi.');

        return 0;
    }
}
