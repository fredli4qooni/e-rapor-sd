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
        Schema::table('siswas', function (Blueprint $table) {
            if (!Schema::hasColumn('siswas', 'pekerjaan_ayah')) {
                $table->string('pekerjaan_ayah')->nullable()->after('nama_ayah');
            }
            if (!Schema::hasColumn('siswas', 'pekerjaan_ibu')) {
                $table->string('pekerjaan_ibu')->nullable()->after('nama_ibu');
            }
            if (!Schema::hasColumn('siswas', 'alamat')) {
                $table->string('alamat')->nullable()->after('pekerjaan_ibu');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('siswas', function (Blueprint $table) {
            $table->dropColumn(['pekerjaan_ayah', 'pekerjaan_ibu', 'alamat']);
        });
    }
};
