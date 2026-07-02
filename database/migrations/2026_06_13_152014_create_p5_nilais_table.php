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
        Schema::create('p5_nilais', function (Blueprint $table) {
            $table->id();
            $table->foreignId('siswa_id')->constrained('siswas')->onDelete('cascade');
            $table->foreignId('p5_proyek_id')->constrained('p5_proyeks')->onDelete('cascade');
            $table->foreignId('p5_sub_elemen_id')->constrained('p5_sub_elemens')->onDelete('cascade');
            $table->string('capaian', 10)->nullable(); // e.g. BB, MB, BSH, SB
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('p5_nilais');
    }
};
