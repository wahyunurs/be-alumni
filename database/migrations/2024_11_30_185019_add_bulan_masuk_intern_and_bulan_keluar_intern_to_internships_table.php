<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBulanMasukInternAndBulanKeluarInternToInternshipsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('internships', function (Blueprint $table) {
            $table->string('bulan_masuk_intern')->after('nama_intern');
            $table->string('bulan_keluar_intern')->after('periode_masuk_intern');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('internships', function (Blueprint $table) {
            $table->dropColumn(['bulan_masuk_intern', 'bulan_keluar_intern']);
        });
    }
};
