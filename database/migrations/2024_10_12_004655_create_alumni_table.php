<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAlumniTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('alumni', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Menambahkan foreign key user_id
            $table->string('name'); // Nama alumni
            $table->enum('jns_kelamin', ['Laki-Laki', 'Perempuan']); // Jenis Kelamin
            $table->string('nim')->unique(); // NIM, harus unik
            $table->year('tahun_masuk'); // Tahun Masuk
            $table->year('tahun_lulus'); // Tahun Lulus
            $table->string('no_hp'); // Nomor HP
            $table->string('status'); // Status umum (misal: Bekerja, Melanjutkan Pendidikan, dll.)

            // Kolom tambahan untuk pekerjaan (jika status bekerja atau wiraswasta)
            $table->string('bidang_job')->nullable(); // Bidang pekerjaan
            $table->string('jns_job')->nullable(); // Kategori pekerjaan (BUMN, Perusahaan Swasta, dll.)
            $table->string('nama_job')->nullable(); // Nama instansi tempat kerja
            $table->string('jabatan_job')->nullable(); // Jabatan di tempat kerja
            $table->string('lingkup_job')->nullable(); // Lingkup pekerjaan (Lokal, Nasional, Internasional)

            // Kolom tambahan untuk pendidikan (jika status melanjutkan studi)
            $table->string('biaya_studi')->nullable(); // Sumber biaya studi
            $table->string('jenjang_pendidikan')->nullable(); // Jenjang pendidikan (S2, S3, dll.)
            $table->string('universitas')->nullable(); // Nama universitas
            $table->string('program_studi')->nullable(); // Program studi

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('alumni');
    }
}
