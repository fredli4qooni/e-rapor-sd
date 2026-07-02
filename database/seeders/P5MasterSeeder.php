<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class P5MasterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Temas
        $temas = [
            ['nama_tema' => 'Gaya Hidup Berkelanjutan', 'deskripsi' => 'Memahami dampak aktivitas manusia terhadap lingkungan.'],
            ['nama_tema' => 'Kearifan Lokal', 'deskripsi' => 'Mengeksplorasi dan memelihara budaya lokal.'],
            ['nama_tema' => 'Bhinneka Tunggal Ika', 'deskripsi' => 'Membangun dialog penuh hormat tentang keberagaman.'],
            ['nama_tema' => 'Bangunlah Jiwa dan Raganya', 'deskripsi' => 'Membangun kesadaran dan keterampilan memelihara kesehatan.'],
            ['nama_tema' => 'Suara Demokrasi', 'deskripsi' => 'Menumbuhkan jiwa demokratis.'],
            ['nama_tema' => 'Rekayasa dan Teknologi', 'deskripsi' => 'Berinovasi memecahkan masalah.'],
            ['nama_tema' => 'Kewirausahaan', 'deskripsi' => 'Menumbuhkan jiwa wirausaha.'],
        ];

        foreach ($temas as $tema) {
            \App\Models\P5Tema::create($tema);
        }

        // Dimensis
        $dimensis = [
            ['nama_dimensi' => 'Beriman, Bertakwa Kepada Tuhan Yang Maha Esa, dan Berakhlak Mulia', 'deskripsi' => 'Pelajar berakhlak dalam hubungannya dengan Tuhan Yang Maha Esa.'],
            ['nama_dimensi' => 'Berkebinekaan Global', 'deskripsi' => 'Pelajar mempertahankan budaya luhur, lokalitas dan identitasnya.'],
            ['nama_dimensi' => 'Bergotong Royong', 'deskripsi' => 'Pelajar memiliki kemampuan kolaborasi, kepedulian, dan berbagi.'],
            ['nama_dimensi' => 'Mandiri', 'deskripsi' => 'Pelajar yang bertanggung jawab atas proses dan hasil belajarnya.'],
            ['nama_dimensi' => 'Bernalar Kritis', 'deskripsi' => 'Pelajar yang mampu memproses informasi, mengevaluasi dan menyimpulkan.'],
            ['nama_dimensi' => 'Kreatif', 'deskripsi' => 'Pelajar yang mampu memodifikasi dan menghasilkan sesuatu yang orisinal.'],
        ];

        foreach ($dimensis as $dimensi) {
            \App\Models\P5Dimensi::create($dimensi);
        }

        // Elemen (Dummy data for Dimensi 1: Bergotong Royong -> id 3)
        $elemen_kolaborasi = \App\Models\P5Elemen::create([
            'p5_dimensi_id' => 3,
            'nama_elemen' => 'Kolaborasi'
        ]);

        $elemen_kepedulian = \App\Models\P5Elemen::create([
            'p5_dimensi_id' => 3,
            'nama_elemen' => 'Kepedulian'
        ]);

        // Sub Elemen
        \App\Models\P5SubElemen::create([
            'p5_elemen_id' => $elemen_kolaborasi->id,
            'nama_sub_elemen' => 'Kerja sama',
            'capaian_fase_a' => 'Menerima dan melaksanakan tugas serta peran yang diberikan kelompok dalam sebuah kegiatan bersama.',
            'capaian_fase_b' => 'Menampilkan tindakan yang sesuai dengan harapan dan tujuan kelompok.',
            'capaian_fase_c' => 'Menunjukkan ekspektasi (harapan) positif kepada orang lain dalam rangka mencapai tujuan kelompok.'
        ]);

        \App\Models\P5SubElemen::create([
            'p5_elemen_id' => $elemen_kolaborasi->id,
            'nama_sub_elemen' => 'Komunikasi untuk mencapai tujuan bersama',
            'capaian_fase_a' => 'Memahami informasi sederhana dari orang lain dan menyampaikan informasi sederhana kepada orang lain.',
            'capaian_fase_b' => 'Memahami informasi, gagasan, emosi, keterampilan dan keprihatinan yang diungkapkan oleh orang lain.',
            'capaian_fase_c' => 'Memahami informasi dari berbagai sumber dan menyampaikan pesan menggunakan berbagai simbol dan media.'
        ]);
        
        \App\Models\P5SubElemen::create([
            'p5_elemen_id' => $elemen_kepedulian->id,
            'nama_sub_elemen' => 'Tanggap terhadap lingkungan sosial',
            'capaian_fase_a' => 'Peka dan mengapresiasi orang-orang di lingkungan sekitar.',
            'capaian_fase_b' => 'Peka dan mengapresiasi orang-orang di lingkungan sekitar, kemudian melakukan tindakan untuk menjaga keselarasan.',
            'capaian_fase_c' => 'Tanggap terhadap lingkungan sosial sesuai dengan tuntutan peran sosialnya dan menjaga keselarasan.'
        ]);
    }
}
