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
        Schema::create('p5_sub_elemens', function (Blueprint $table) {
            $table->id();
            $table->foreignId('p5_elemen_id')->constrained()->cascadeOnDelete();
            $table->string('nama_sub_elemen');
            $table->text('capaian_fase_a')->nullable();
            $table->text('capaian_fase_b')->nullable();
            $table->text('capaian_fase_c')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('p5_sub_elemens');
    }
};
