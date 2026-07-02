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
            $table->boolean('is_pelengkap_published')->default(false)->after('is_transkrip_published');
            $table->boolean('is_rapor_published')->default(false)->after('is_pelengkap_published');
            $table->boolean('is_p5_published')->default(false)->after('is_rapor_published');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('siswas', function (Blueprint $table) {
            $table->dropColumn(['is_pelengkap_published', 'is_rapor_published', 'is_p5_published']);
        });
    }
};
