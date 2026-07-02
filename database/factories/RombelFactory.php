<?php
namespace Database\Factories;
use Illuminate\Database\Eloquent\Factories\Factory;

class RombelFactory extends Factory
{
    public function definition(): array
    {
        return [
            'sekolah_id' => 1,
            'semester_id' => 1,
            'nama_rombel' => 'Kelas 1A',
            'tingkat' => 1,
            'fase' => 'A',
            'jenis_rombel' => 'REGULER',
            'kurikulum' => 'MERDEKA',
        ];
    }
}