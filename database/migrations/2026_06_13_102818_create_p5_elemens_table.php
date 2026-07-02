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
        Schema::create('p5_elemens', function (Blueprint $table) {
            $table->id();
            $table->foreignId('p5_dimensi_id')->constrained()->cascadeOnDelete();
            $table->string('nama_elemen');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('p5_elemens');
    }
};
