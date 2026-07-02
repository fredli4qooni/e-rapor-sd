<?php
namespace Database\Factories;
use Illuminate\Database\Eloquent\Factories\Factory;

class SekolahFactory extends Factory
{
    public function definition(): array
    {
        return [
            'npsn' => fake()->unique()->numerify('########'),
            'nama_sekolah' => 'SDN ' . fake()->city(),
            'alamat' => fake()->address(),
            'kecamatan' => fake()->citySuffix(),
            'kabupaten' => fake()->city(),
            'provinsi' => fake()->state(),
        ];
    }
}