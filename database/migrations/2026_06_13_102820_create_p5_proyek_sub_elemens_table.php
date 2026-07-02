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
        Schema::create('p5_proyek_sub_elemens', function (Blueprint $table) {
            $table->id();
            $table->foreignId('p5_proyek_id')->constrained()->cascadeOnDelete();
            $table->foreignId('p5_sub_elemen_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('p5_proyek_sub_elemens');
    }
};
