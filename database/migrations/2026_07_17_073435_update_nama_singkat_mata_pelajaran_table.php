<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $mapels = [
            'Pendidikan Agama Islam dan Budi Pekerti' => 'PAI',
            'Pendidikan Pancasila' => 'PPKn',
            'Bahasa Indonesia' => 'B. Indonesia',
            'Matematika' => 'Matematika',
            'Ilmu Pengetahuan Alam dan Sosial (IPAS)' => 'IPAS',
            'Pendidikan Jasmani, Olahraga, dan Kesehatan' => 'PJOK',
            'Seni Budaya' => 'Seni Budaya',
            'Muatan Lokal Bahasa Daerah' => 'B. Daerah'
        ];

        foreach ($mapels as $nama_mapel => $nama_singkat) {
            DB::table('mata_pelajarans')
                ->where('nama_mapel', $nama_mapel)
                ->whereNull('nama_singkat')
                ->update(['nama_singkat' => $nama_singkat]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No need to reverse the data update
    }
};
