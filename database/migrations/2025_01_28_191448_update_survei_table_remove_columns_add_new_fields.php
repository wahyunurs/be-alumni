<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateSurveiTableRemoveColumnsAddNewFields extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('survei', function (Blueprint $table) {
            // Menghapus kolom lama
            $table->dropColumn(['kepuasan', 'kesesuaian', 'komentar']);

            // Tambahkan kolom baru setelah created_at
            $table->string('kedisiplinan')->nullable()->after('name_alumni');
            $table->string('kejujuran')->nullable()->after('kedisiplinan');
            $table->string('motivasi')->nullable()->after('kejujuran');
            $table->string('etos')->nullable()->after('motivasi');
            $table->string('moral')->nullable()->after('etos');
            $table->string('etika')->nullable()->after('moral');
            $table->string('bidang_ilmu')->nullable()->after('etika');
            $table->string('produktif')->nullable()->after('bidang_ilmu');
            $table->string('masalah')->nullable()->after('produktif');
            $table->string('inisiatif')->nullable()->after('masalah');
            $table->string('menulis_asing')->nullable()->after('inisiatif');
            $table->string('komunikasi_asing')->nullable()->after('menulis_asing');
            $table->string('memahami_asing')->nullable()->after('komunikasi_asing');
            $table->string('alat_teknologi')->nullable()->after('memahami_asing');
            $table->string('adaptasi_teknologi')->nullable()->after('alat_teknologi');
            $table->string('penggunaan_teknologi')->nullable()->after('adaptasi_teknologi');
            $table->string('emosi')->nullable()->after('penggunaan_teknologi');
            $table->string('percaya_diri')->nullable()->after('emosi');
            $table->string('keterbukaan')->nullable()->after('percaya_diri');
            $table->string('kom_lisan')->nullable()->after('keterbukaan');
            $table->string('kom_tulisan')->nullable()->after('kom_lisan');
            $table->string('kepemimpinan')->nullable()->after('kom_tulisan');
            $table->string('manajerial')->nullable()->after('kepemimpinan');
            $table->string('masalah_kerja')->nullable()->after('manajerial');
            $table->string('motivasi_tempat_kerja')->nullable()->after('masalah_kerja');
            $table->string('motivasi_diri')->nullable()->after('motivasi_tempat_kerja');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('survei', function (Blueprint $table) {
            // Mengembalikan kolom lama
            $table->text('kepuasan')->nullable();
            $table->text('kesesuaian')->nullable();
            $table->text('komentar')->nullable();

            // Menghapus kolom baru
            $table->dropColumn([
                'kedisiplinan', 'kejujuran', 'motivasi', 'etos', 'moral', 'etika',
                'bidang_ilmu', 'produktif', 'masalah', 'inisiatif', 
                'menulis_asing','komunikasi_asing', 'memahami_asing', 
                'alat_teknologi', 'adaptasi_teknologi', 'penggunaan_teknologi', 
                'emosi', 'percaya_diri', 'keterbukaan','kom_lisan', 'kom_tulisan', 
                'kepemimpinan', 'manajerial','masalah_kerja', 
                'motivasi_tempat_kerja', 'motivasi_diri',
            ]);
        });
    }
};