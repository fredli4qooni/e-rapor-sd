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
        Schema::create('setting_transkrips', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sekolah_id')->constrained()->cascadeOnDelete();
            $table->enum('tampilan_nama_siswa', ['huruf_kapital', 'sesuai_data'])->default('huruf_kapital');
            $table->integer('jumlah_angka_desimal')->default(0);
            $table->boolean('tampilkan_baris_rata_rata')->default(true);
            $table->integer('angka_desimal_rata_rata')->default(2);
            $table->string('tempat_tanggal_transkrip')->nullable();
            $table->string('nama_kepala_sekolah')->nullable();
            $table->string('nip_kepala_sekolah')->nullable();
            $table->boolean('tampilkan_ttd_kepala_sekolah')->default(true);
            
            // Print settings
            $table->string('ukuran_kertas')->default('A4');
            $table->integer('margin_kiri')->default(15);
            $table->integer('margin_kanan')->default(15);
            $table->integer('margin_atas')->default(15);
            $table->integer('margin_bawah')->default(15);
            $table->integer('jarak_antar_identitas')->default(5);
            $table->integer('tinggi_judul')->default(10);
            $table->integer('tinggi_isi_tabel')->default(8);
            $table->integer('persentase_kop')->default(100);
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('setting_transkrips');
    }
};
