<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBulanLulusAndBulanMasukJobToAlumniTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('alumni', function (Blueprint $table) {
            Schema::table('alumni', function (Blueprint $table) {
                // Menambahkan kolom bulan_lulus dan bulan_wisuda (wajib diisi)
                $table->string('bulan_lulus')->after('tahun_lulus')->default('Tidak Diketahui');
                $table->string('wisuda')->after('bulan_lulus');
                
                // Menambahkan kolom bulan_masuk_job (nullable)
                $table->string('bulan_masuk_job')->nullable()->after('lingkup_job');
            });
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('alumni', function (Blueprint $table) {
            $table->dropColumn(['bulan_lulus', 'wisuda', 'bulan_masuk_job']);
        });
    }
};
