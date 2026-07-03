<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('kuesioner_responden', function (Blueprint $table) {
            $table->string('kuesioner_responden_instansi_asal_lainnya', 255)->nullable()->after('kuesioner_responden_pemda_uuid');
        });
    }

    public function down(): void
    {
        Schema::table('kuesioner_responden', function (Blueprint $table) {
            $table->dropColumn('kuesioner_responden_instansi_asal_lainnya');
        });
    }
};
