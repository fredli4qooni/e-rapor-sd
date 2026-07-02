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
        Schema::table('sikaps', function (Blueprint $table) {
            $table->text('deskripsi_p3')->nullable();
            $table->text('deskripsi_dpl')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sikaps', function (Blueprint $table) {
            $table->dropColumn(['deskripsi_p3', 'deskripsi_dpl']);
        });
    }
};
