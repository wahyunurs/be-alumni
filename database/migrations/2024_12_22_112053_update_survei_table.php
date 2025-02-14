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
        Schema::table('survei', function (Blueprint $table) {
            // Drop the existing 'survei' column
            $table->dropColumn('survei');
            
            // Add new columns 'kepuasan' and 'kesesuaian' with integer data type for range 1-5
            $table->unsignedTinyInteger('kepuasan')->after('name_alumni')->comment('nilai dalam rentang 1-5');
            $table->unsignedTinyInteger('kesesuaian')->after('kepuasan')->comment('nilai dalam rentang 1-5');
            $table->text('komentar')->after('kesesuaian')->nullable;
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('survei', function (Blueprint $table) {
            // Revert changes: add 'survei' column back
            $table->enum('survei', ['Ya', 'Tidak'])->nullable();
            
            // Drop the newly added columns
            $table->dropColumn(['kepuasan', 'kesesuaian', 'komentar']);
        });
    }
};
