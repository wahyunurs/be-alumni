<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddEmailAndProfilePhotoToAlumniTable extends Migration
{
    public function up()
    {
        Schema::table('alumni', function (Blueprint $table) {
            // Tambahkan kolom email jika belum ada
            if (!Schema::hasColumn('alumni', 'email')) {
                $table->string('email')->unique()->after('name'); // Menambahkan setelah nama
            }

            // Tambahkan kolom foto_profil jika belum ada
            if (!Schema::hasColumn('alumni', 'foto_profil')) {
                $table->string('foto_profil')->nullable()->after('email'); // Menambahkan setelah email
            }
        });     
    }

    public function down()
    {
        Schema::table('alumni', function (Blueprint $table) {
            if (Schema::hasColumn('alumni', 'email')) {
                $table->dropColumn('email');
            }

            if (Schema::hasColumn('alumni', 'foto_profil')) {
                $table->dropColumn('foto_profil');
            }
        });
    }
}
