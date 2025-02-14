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
        Schema::table('jobs', function (Blueprint $table) {
            $table->string('kota')->nullable()->change();
            $table->string('negara')->nullable()->change();
            $table->longText('catatan')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jobs', function (Blueprint $table) {
            $table->string('kota')->nullable(value: false)->change();
            $table->string('negara')->nullable(false)->change();
            $table->longText('catatan')->nullable(false)->change();
        });
    }
};
