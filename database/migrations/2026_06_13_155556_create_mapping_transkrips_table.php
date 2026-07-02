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
        Schema::create('mapping_transkrips', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mata_pelajaran_id')->constrained()->cascadeOnDelete();
            $table->integer('tingkat')->default(6);
            $table->string('kurikulum')->default('Merdeka');
            $table->string('nama_lokal')->nullable();
            $table->string('kelompok')->nullable(); // Kelompok A, B, C dll
            $table->integer('no_urut')->default(1);
            $table->timestamps();
            
            $table->unique(['mata_pelajaran_id', 'tingkat', 'kurikulum'], 'mapel_tingkat_kurikulum_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mapping_transkrips');
    }
};
