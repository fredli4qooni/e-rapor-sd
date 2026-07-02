<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\DplDimensi;
use App\Models\DplSubdimensi;

class DplSeeder extends Seeder
{
    public function run(): void
    {
        $dplData = [
            'Sikap Spiritual' => [
                'Berdoa sebelum dan sesudah melakukan kegiatan',
                'Menjalankan ibadah sesuai dengan agama yang dianut',
                'Memberi salam pada saat awal dan akhir kegiatan',
                'Bersyukur atas nikmat dan karunia Tuhan Yang Maha Esa',
                'Mensyukuri kemampuan manusia dalam mengendalikan diri',
                'Mengucapkan syukur ketika berhasil mengerjakan sesuatu'
            ],
            'Sikap Sosial' => [
                'Jujur',
                'Disiplin',
                'Tanggung Jawab',
                'Santun',
                'Peduli',
                'Percaya Diri'
            ],
            'Pengetahuan & Keterampilan Profil Lulusan' => [
                'Memiliki pengetahuan faktual, konseptual, prosedural, dan metakognitif tingkat dasar',
                'Memiliki keterampilan berpikir dan bertindak kreatif, produktif, kritis, mandiri, kolaboratif, dan komunikatif'
            ]
        ];

        foreach ($dplData as $dimensiName => $subdimensis) {
            $dimensi = DplDimensi::firstOrCreate(['nama_dimensi' => $dimensiName]);

            foreach ($subdimensis as $subName) {
                DplSubdimensi::firstOrCreate([
                    'dpl_dimensi_id' => $dimensi->id,
                    'nama_subdimensi' => $subName
                ]);
            }
        }
    }
}
