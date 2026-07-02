<?php
namespace Database\Factories;
use Illuminate\Database\Eloquent\Factories\Factory;

class MataPelajaranFactory extends Factory
{
    public function definition(): array
    {
        return [
            'nama_mapel' => fake()->randomElement(['Pendidikan Agama Islam', 'Pendidikan Pancasila', 'Bahasa Indonesia', 'Matematika', 'IPAS', 'PJOK', 'Seni Budaya']),
            'is_transkrip' => true,
            'is_lokal' => false,
        ];
    }
}