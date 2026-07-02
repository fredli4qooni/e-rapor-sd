<?php
namespace Database\Factories;
use Illuminate\Database\Eloquent\Factories\Factory;

class GuruFactory extends Factory
{
    public function definition(): array
    {
        return [
            'sekolah_id' => 1,
            'nip' => fake()->unique()->numerify('198########'),
            'nama_lengkap' => fake()->name(),
            'is_kepsek' => false,
        ];
    }
}