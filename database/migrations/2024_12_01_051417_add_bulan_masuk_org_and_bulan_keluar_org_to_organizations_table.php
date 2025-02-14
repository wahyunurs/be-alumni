<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBulanMasukOrgAndBulanKeluarOrgToOrganizationsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('organizations', function (Blueprint $table) {
            $table->string('bulan_masuk_org')->after('nama_org');
            $table->string('bulan_keluar_org')->after('periode_masuk_org');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('organizations', function (Blueprint $table) {
            $table->dropColumn(['bulan_masuk_org', 'bulan_keluar_org']);
        });
    }
};
