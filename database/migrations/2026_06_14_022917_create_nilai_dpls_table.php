<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('nilai_dpls', function (Blueprint $table) {
            $table->id();
            $table->foreignId('siswa_id')->constrained()->cascadeOnDelete();
            $table->foreignId('rombel_id')->constrained()->cascadeOnDelete();
            $table->foreignId('semester_id')->constrained()->cascadeOnDelete();
            $table->foreignId('dpl_subdimensi_id')->constrained('dpl_subdimensis')->cascadeOnDelete();
            $table->integer('nilai')->nullable(); // 1: Berkembang, 2: Cakap, 3: Mahir
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('nilai_dpls');
    }
};
