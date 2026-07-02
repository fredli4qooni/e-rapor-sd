<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dpl_dimensis', function (Blueprint $table) {
            $table->id();
            $table->string('nama_dimensi');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dpl_dimensis');
    }
};
