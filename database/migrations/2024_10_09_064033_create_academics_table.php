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
        Schema::create('academics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('jenjang_pendidikan');
            $table->string('nama_studi');
            $table->string('prodi');
            $table->decimal('ipk', 10, 2)->default(0.00);
            $table->unsignedInteger('tahun_masuk');
            $table->unsignedInteger('tahun_lulus');
            $table->string('kota');
            $table->string('negara');
            $table->longText('catatan');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('academics');
    }
};