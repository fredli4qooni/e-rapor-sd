<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('nilai_p3s', function (Blueprint $table) {
            $table->id();
            $table->foreignId('siswa_id')->constrained()->cascadeOnDelete();
            $table->foreignId('rombel_id')->constrained()->cascadeOnDelete();
            $table->foreignId('semester_id')->constrained()->cascadeOnDelete();
            $table->foreignId('p5_sub_elemen_id')->constrained('p5_sub_elemens')->cascadeOnDelete();
            $table->integer('nilai')->nullable(); // 1: MB, 2: SB, 3: BSH, 4: Sangat Berkembang
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('nilai_p3s');
    }
};
