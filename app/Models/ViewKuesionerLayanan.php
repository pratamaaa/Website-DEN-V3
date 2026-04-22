<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ViewKuesionerLayanan extends Model
{
    protected $primaryKey = null;
    public $incrementing = false;
    public $timestamps = false;

    public function getTable()
    {
        $sub = DB::table('kuesioner_layanan as aa')
            ->whereNull('aa.kuesioner_layanan_log_uuid')
            ->selectRaw("
                aa.*,
                (
                    SELECT COUNT(xx.kuesioner_pertanyaan_id)
                    FROM kuesioner_pertanyaan xx
                    WHERE xx.kuesioner_pertanyaan_layanan_uuid = aa.kuesioner_layanan_uuid
                      AND xx.kuesioner_pertanyaan_log_uuid IS NULL
                      AND xx.kuesioner_pertanyaan_parent_uuid IS NOT NULL
                      AND xx.kuesioner_pertanyaan_status = 1
                ) as kuesioner_layanan_jumlah_pertanyaan,
                CASE
                    WHEN aa.kuesioner_layanan_status = 1 THEN 'Aktif'
                    WHEN aa.kuesioner_layanan_status = 0 THEN 'Tidak Aktif'
                    ELSE NULL
                END as kuesioner_layanan_status_name
            ");

        return DB::raw('(' . $sub->toSql() . ') as view_kuesioner_layanan');
    }

    public function save(array $options = [])
    {
        throw new \Exception("Cannot save to a database view.");
    }

    public function delete()
    {
        throw new \Exception("Cannot delete from a database view.");
    }
}