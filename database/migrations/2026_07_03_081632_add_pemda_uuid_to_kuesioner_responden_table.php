<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('kuesioner_responden', function (Blueprint $table) {
            $table->char('kuesioner_responden_pemda_uuid', 36)->nullable()->after('kuesioner_responden_kementerian_lembaga_uuid');
        });
    }

    public function down(): void
    {
        Schema::table('kuesioner_responden', function (Blueprint $table) {
            $table->dropColumn('kuesioner_responden_pemda_uuid');
        });
    }
};
