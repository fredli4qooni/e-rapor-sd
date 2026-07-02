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
        Schema::create('sikaps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('siswa_id')->constrained()->cascadeOnDelete();
            $table->foreignId('rombel_id')->constrained()->cascadeOnDelete();
            $table->string('predikat_spiritual')->nullable();
            $table->text('deskripsi_spiritual')->nullable();
            $table->string('predikat_sosial')->nullable();
            $table->text('deskripsi_sosial')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sikaps');
    }
};
