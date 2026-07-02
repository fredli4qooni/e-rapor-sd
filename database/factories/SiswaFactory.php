<?php
namespace Database\Factories;
use Illuminate\Database\Eloquent\Factories\Factory;

class SiswaFactory extends Factory
{
    public function definition(): array
    {
        return [
            'sekolah_id' => 1,
            'nisn' => fake()->unique()->numerify('##########'),
            'nis' => fake()->unique()->numerify('####'),
            'nama_lengkap' => fake()->name(),
            'jenis_kelamin' => fake()->randomElement(['L', 'P']),
            'tempat_lahir' => fake()->city(),
            'tanggal_lahir' => fake()->date('Y-m-d', '2015-12-31'),
            'nama_ayah' => fake()->name('male'),
            'nama_ibu' => fake()->name('female'),
        ];
    }
}