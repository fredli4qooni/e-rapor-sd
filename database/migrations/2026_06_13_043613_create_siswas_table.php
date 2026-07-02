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
        Schema::create('siswas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('sekolah_id')->constrained()->cascadeOnDelete();
            $table->string('nisn', 20)->unique();
            $table->string('nis', 20)->nullable();
            $table->string('nama_lengkap', 200);
            $table->enum('jenis_kelamin', ['L', 'P']);
            $table->date('tanggal_lahir')->nullable();
            $table->string('tempat_lahir', 100)->nullable();
            $table->string('nama_ayah', 200)->nullable();
            $table->string('nama_ibu', 200)->nullable();
            $table->string('foto', 255)->nullable();
            $table->string('no_ijazah', 50)->nullable();
            $table->string('no_transkrip', 50)->nullable();
            $table->date('tgl_lulus')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('siswas');
    }
};
