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
        Schema::table('mata_pelajarans', function (Blueprint $table) {
            $table->foreignId('kelompok_mapel_id')->nullable()->constrained('kelompok_mapels')->nullOnDelete();
            $table->integer('nomor_urut')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mata_pelajarans', function (Blueprint $table) {
            $table->dropForeign(['kelompok_mapel_id']);
            $table->dropColumn('kelompok_mapel_id');
            $table->dropColumn('nomor_urut');
        });
    }
};
