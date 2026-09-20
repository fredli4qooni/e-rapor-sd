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
        Schema::table('semesters', function (Blueprint $table) {
            $table->date('tanggal_mulai')->nullable()->after('kurikulum');
            $table->date('tanggal_selesai')->nullable()->after('tanggal_mulai');
            $table->date('tanggal_mulai_input')->nullable()->after('tanggal_selesai');
            $table->date('tanggal_akhir_input')->nullable()->after('tanggal_mulai_input');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('semesters', function (Blueprint $table) {
            $table->dropColumn([
                'tanggal_mulai',
                'tanggal_selesai',
                'tanggal_mulai_input',
                'tanggal_akhir_input',
            ]);
        });
    }
};
