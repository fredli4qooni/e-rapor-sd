<?php
namespace Database\Factories;
use Illuminate\Database\Eloquent\Factories\Factory;

class SemesterFactory extends Factory
{
    public function definition(): array
    {
        return [
            'sekolah_id' => 1,
            'tahun_ajaran' => '2025/2026',
            'semester' => 1,
            'is_aktif' => true,
            'kurikulum' => 'MERDEKA',
        ];
    }
}