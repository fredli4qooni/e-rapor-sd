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
        Schema::create('setting_rapors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sekolah_id')->constrained()->cascadeOnDelete();
            
            // Print settings
            $table->string('ukuran_kertas')->default('A4');
            $table->integer('margin_kiri')->default(15);
            $table->integer('margin_kanan')->default(15);
            $table->integer('margin_atas')->default(15);
            $table->integer('margin_bawah')->default(15);
            
            // Halaman Awal Rapor
            $table->integer('hal_awal_rapor')->default(1);
            
            // Pengaturan TTD
            $table->boolean('tampilkan_ttd_kepsek')->default(true);
            $table->boolean('tampilkan_ttd_wali')->default(true);
            $table->enum('posisi_ttd_kepsek', ['kiri', 'tengah', 'kanan'])->default('kanan');
            $table->boolean('tampilkan_nama_wali')->default(true);
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('setting_rapors');
    }
};
