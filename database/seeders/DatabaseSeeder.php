<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Sekolah;
use App\Models\Semester;
use App\Models\MataPelajaran;
use App\Models\Guru;
use App\Models\Rombel;
use App\Models\Siswa;
use App\Models\Pembelajaran;
use App\Models\Ekstrakurikuler;
use App\Models\TujuanPembelajaran;
use App\Models\NilaiRapor;
use App\Models\Sikap;
use App\Models\Kehadiran;
use App\Models\NilaiEkstrakurikuler;
use App\Models\CatatanWaliKelas;
use App\Models\P5Tema;
use App\Models\P5Proyek;
use App\Models\P5Kelompok;
use App\Models\P5Dimensi;
use App\Models\P5Elemen;
use App\Models\P5SubElemen;
use App\Models\P5ProyekSubElemen;
use App\Models\P5Nilai;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Setup Admin & Kepsek
        User::create([
            'name' => 'Administrator',
            'username' => 'admin',
            'email' => 'admin@erapor.local',
            'role' => 'admin',
            'password' => Hash::make('password123'),
            'is_active' => true,
        ]);

        User::create([
            'name' => 'Kepala Sekolah',
            'username' => 'kepsek',
            'email' => 'kepsek@erapor.local',
            'role' => 'kepsek',
            'password' => Hash::make('password123'),
            'is_active' => true,
        ]);

        // 2. Setup Sekolah & Semester
        $sekolah = Sekolah::create([
            'npsn' => '20101234',
            'nama_sekolah' => 'SD Negeri 1 Percontohan',
            'alamat' => 'Jl. Pendidikan No. 123',
            'kecamatan' => 'Kec. Ilmu Raya',
            'kabupaten' => 'Kota Pelajar',
            'provinsi' => 'Jawa Tengah',
        ]);

        $semesterAktif = Semester::create([
            'sekolah_id' => $sekolah->id,
            'tahun_ajaran' => '2024/2025',
            'semester' => 1,
            'is_aktif' => true,
            'status_input_nilai' => true,
            'kurikulum' => 'Merdeka',
            'tanggal_rapor' => '2024-12-20',
            'tempat_terbit' => 'Kota Pelajar',
        ]);

        // 3. Setup Mata Pelajaran SD
        $mapelList = [
            ['nama_mapel' => 'Pendidikan Agama Islam dan Budi Pekerti', 'nama_singkat' => 'PAI'],
            ['nama_mapel' => 'Pendidikan Pancasila', 'nama_singkat' => 'PPKn'],
            ['nama_mapel' => 'Bahasa Indonesia', 'nama_singkat' => 'B. Indonesia'],
            ['nama_mapel' => 'Matematika', 'nama_singkat' => 'Matematika'],
            ['nama_mapel' => 'Ilmu Pengetahuan Alam dan Sosial (IPAS)', 'nama_singkat' => 'IPAS'],
            ['nama_mapel' => 'Pendidikan Jasmani, Olahraga, dan Kesehatan', 'nama_singkat' => 'PJOK'],
            ['nama_mapel' => 'Seni Budaya', 'nama_singkat' => 'Seni Budaya'],
            ['nama_mapel' => 'Muatan Lokal Bahasa Daerah', 'nama_singkat' => 'B. Daerah']
        ];
        $mapelIds = [];
        foreach ($mapelList as $m) {
            $mapelIds[] = MataPelajaran::create($m)->id;
        }

        // 4. Setup Guru dengan nama realistis
        $guruNames = [
            'Budi Santoso, S.Pd', 'Siti Aminah, M.Pd', 'Ahmad Rifai, S.Pd', 'Dewi Lestari, S.Pd',
            'Rini Yulianti, S.Pd', 'Eko Prasetyo, S.Pd.Or', 'Agus Supriyanto, S.Pd', 'Nita Sari, S.Ag',
            'Hendra Wijaya, S.Pd', 'Ayu Wandira, S.Pd'
        ];
        
        $gurus = [];
        foreach ($guruNames as $i => $name) {
            $username = 'guru' . strtolower(explode(' ', trim($name))[0]);
            if (User::where('username', $username)->exists()) $username .= $i;
            
            $userGuru = User::create([
                'name' => $name,
                'username' => $username,
                'email' => $username . '@erapor.local',
                'role' => 'guru',
                'password' => Hash::make('password123'),
                'is_active' => true,
            ]);
            $gurus[] = Guru::create([
                'user_id' => $userGuru->id,
                'sekolah_id' => $sekolah->id,
                'nama_lengkap' => $name,
                'nip' => '198' . rand(0,9) . '0' . rand(1,9) . '12201' . rand(0,9) . '0' . rand(1,9) . '100' . rand(1,9),
            ]);
        }

        // 5. Setup Ekstrakurikuler
        $ekskulList = ['Pramuka (Wajib)', 'PMR', 'Tari Tradisional', 'Futsal', 'Pencak Silat'];
        $ekskulIds = [];
        foreach ($ekskulList as $i => $eks) {
            $ekskulIds[] = Ekstrakurikuler::create([
                'nama_ekskul' => $eks,
                'pembina_id' => $gurus[$i % count($gurus)]->id,
                'is_aktif' => true,
            ])->id;
        }

        // 6. Setup Rombel (1A, 2A, 3A, 4A, 5A, 6A)
        $rombelNames = ['1A', '2A', '3A', '4A', '5A', '6A'];
        $rombels = [];
        foreach ($rombelNames as $index => $nama) {
            $rombels[] = Rombel::create([
                'sekolah_id' => $sekolah->id,
                'semester_id' => $semesterAktif->id,
                'nama_rombel' => 'Kelas ' . $nama,
                'tingkat' => $index + 1,
                'fase' => ($index + 1 <= 2) ? 'A' : (($index + 1 <= 4) ? 'B' : 'C'),
                'wali_kelas_id' => $gurus[$index]->id,
                'jenis_rombel' => 'REGULER',
                'kurikulum' => 'MERDEKA',
            ]);
        }

        // 7. Data Nama Siswa Dummy Realistis
        $siswaFirstNames = ['Andi', 'Budi', 'Citra', 'Dewi', 'Eka', 'Fajar', 'Gita', 'Hadi', 'Indah', 'Joko', 'Kirana', 'Luki', 'Maya', 'Nanda', 'Putri', 'Rizki', 'Siti', 'Tegar', 'Vina', 'Wawan', 'Aditya', 'Bagas', 'Cinta', 'Dimas', 'Erlangga', 'Farhan', 'Galih', 'Hana', 'Iqbal', 'Kevin', 'Lestari', 'Maulana', 'Nadia', 'Oktavia', 'Pandu', 'Ratna', 'Satria', 'Tiara', 'Wahyu', 'Yoga'];
        $siswaLastNames = ['Pratama', 'Santoso', 'Wijaya', 'Kusuma', 'Putra', 'Putri', 'Sari', 'Lestari', 'Setiawan', 'Nugroho', 'Wahyuni', 'Hidayat', 'Saputra', 'Permatasari', 'Wulandari', 'Ramadhan', 'Fadillah', 'Utami', 'Siregar', 'Harahap', 'Gunawan', 'Sanjaya', 'Mahendra', 'Kurniawan', 'Ramadani', 'Prasetyo', 'Syahputra', 'Agustina', 'Rahmawati', 'Sugiarto'];
        
        $generatedSiswaNames = [];
        $nisCounter = 2024001;

        foreach ($rombels as $rIndex => $rombel) {
            $siswaIds = [];
            // Buat 5 siswa per rombel
            for ($s = 1; $s <= 5; $s++) {
                do {
                    $first = $siswaFirstNames[array_rand($siswaFirstNames)];
                    $last = $siswaLastNames[array_rand($siswaLastNames)];
                    $namaSiswa = "$first $last";
                } while (in_array($namaSiswa, $generatedSiswaNames));
                $generatedSiswaNames[] = $namaSiswa;

                $usernameSiswa = strtolower($first) . strtolower(substr($last, 0, 3)) . rand(10,99);

                $userSiswa = User::create([
                    'name' => $namaSiswa,
                    'username' => $usernameSiswa,
                    'email' => $usernameSiswa . "@erapor.local",
                    'role' => 'siswa',
                    'password' => Hash::make('password123'),
                    'is_active' => true,
                ]);

                $nisn = '015' . rand(1000000, 9999999);
                
                $pekerjaanList = ['Wiraswasta', 'PNS', 'Karyawan Swasta', 'Petani', 'Buruh', 'TNI/Polri', 'Guru'];
                $ayahFirstNames = ['Agus', 'Bambang', 'Candra', 'Dedi', 'Eko', 'Fajar', 'Gunawan'];
                $ibuFirstNames = ['Ani', 'Budi', 'Citra', 'Dewi', 'Endang', 'Fitri', 'Gita'];
                
                $siswa = Siswa::create([
                    'user_id' => $userSiswa->id,
                    'sekolah_id' => $sekolah->id,
                    'nama_lengkap' => $namaSiswa,
                    'nisn' => $nisn,
                    'nis' => (string)$nisCounter++,
                    'jenis_kelamin' => in_array($first, ['Andi', 'Budi', 'Eka', 'Fajar', 'Hadi', 'Joko', 'Luki', 'Nanda', 'Rizki', 'Tegar', 'Wawan', 'Aditya', 'Bagas', 'Dimas', 'Erlangga', 'Farhan', 'Galih', 'Iqbal', 'Kevin', 'Maulana', 'Pandu', 'Satria', 'Wahyu', 'Yoga']) ? 'L' : 'P',
                    'tempat_lahir' => 'Kota Pelajar',
                    'tanggal_lahir' => date('Y-m-d', strtotime('-' . (6 + $rombel->tingkat) . ' years -' . rand(1, 300) . ' days')),
                    'nama_ayah' => $ayahFirstNames[array_rand($ayahFirstNames)] . ' ' . $last,
                    'nama_ibu' => $ibuFirstNames[array_rand($ibuFirstNames)] . ' ' . $siswaLastNames[array_rand($siswaLastNames)],
                    'pekerjaan_ayah' => $pekerjaanList[array_rand($pekerjaanList)],
                    'pekerjaan_ibu' => rand(0, 1) ? 'Ibu Rumah Tangga' : $pekerjaanList[array_rand($pekerjaanList)],
                    'alamat' => 'Jl. Merdeka No. ' . rand(1, 100) . ', Kota Pelajar',
                    'is_rapor_published' => true,
                    'is_pelengkap_published' => true,
                    'is_p5_published' => true,
                ]);
                $siswaIds[] = $siswa->id;

                DB::table('anggota_rombels')->insert([
                    'rombel_id' => $rombel->id,
                    'siswa_id' => $siswa->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                // Ekstrakurikuler wajib (Pramuka) + 1 acak
                NilaiEkstrakurikuler::create([
                    'siswa_id' => $siswa->id,
                    'ekstrakurikuler_id' => $ekskulIds[0], // Pramuka
                    'rombel_id' => $rombel->id,
                    'predikat' => 'Baik',
                    'keterangan' => 'Mengikuti kegiatan kepramukaan dengan disiplin.'
                ]);

                if (rand(0, 1)) {
                    NilaiEkstrakurikuler::create([
                        'siswa_id' => $siswa->id,
                        'ekstrakurikuler_id' => $ekskulIds[rand(1, 4)],
                        'rombel_id' => $rombel->id,
                        'predikat' => 'Sangat Baik',
                        'keterangan' => 'Sangat antusias dan menunjukkan bakat yang baik.'
                    ]);
                }

                // Kehadiran & Catatan
                Kehadiran::create([
                    'siswa_id' => $siswa->id,
                    'semester_id' => $semesterAktif->id,
                    'sakit' => rand(0, 2),
                    'izin' => rand(0, 1),
                    'tanpa_keterangan' => rand(0, 1) == 1 ? 0 : 1,
                ]);

                $catatanWali = [
                    'Pertahankan prestasimu dan tingkatkan terus semangat belajarmu!',
                    'Tingkatkan kedisiplinan dan jangan ragu bertanya jika ada materi yang kurang dipahami.',
                    'Semangat belajarmu sudah sangat baik, teruslah menjadi inspirasi bagi teman-temanmu.',
                    'Kamu adalah siswa yang aktif. Tingkatkan fokusmu di kelas agar hasilnya lebih maksimal.',
                    'Prestasi akademikmu sangat membanggakan. Terus asah potensimu!'
                ];

                CatatanWaliKelas::create([
                    'siswa_id' => $siswa->id,
                    'semester_id' => $semesterAktif->id,
                    'catatan' => $catatanWali[array_rand($catatanWali)],
                ]);

                Sikap::create([
                    'siswa_id' => $siswa->id,
                    'rombel_id' => $rombel->id,
                    'predikat_spiritual' => 'Sangat Baik',
                    'deskripsi_spiritual' => 'Selalu mengawali dan mengakhiri kegiatan dengan berdoa, serta saling menghargai teman.',
                    'predikat_sosial' => 'Baik',
                    'deskripsi_sosial' => 'Mampu bekerja sama dengan baik, sopan, dan suka menolong teman di kelas.',
                ]);
            }

            // Setup Pembelajaran per rombel
            foreach ($mapelIds as $mIndex => $mapelId) {
                $pembelajaran = Pembelajaran::create([
                    'sekolah_id' => $sekolah->id,
                    'semester_id' => $semesterAktif->id,
                    'rombel_id' => $rombel->id,
                    'mata_pelajaran_id' => $mapelId,
                    'guru_id' => $gurus[($rIndex + $mIndex) % count($gurus)]->id,
                    'is_aktif' => true,
                ]);

                // Setup Tujuan Pembelajaran (2 TP per mapel)
                $tp1 = TujuanPembelajaran::create([
                    'mata_pelajaran_id' => $mapelId,
                    'tingkat' => $rombel->tingkat,
                    'semester_id' => $semesterAktif->id,
                    'deskripsi' => 'Memahami konsep dasar secara teori pada bab pembelajaran utama.',
                    'is_aktif' => true,
                ]);
                $tp2 = TujuanPembelajaran::create([
                    'mata_pelajaran_id' => $mapelId,
                    'tingkat' => $rombel->tingkat,
                    'semester_id' => $semesterAktif->id,
                    'deskripsi' => 'Mampu mempraktikkan keterampilan inti secara mandiri dan percaya diri.',
                    'is_aktif' => true,
                ]);

                // Setup Nilai Rapor
                foreach ($siswaIds as $siswaId) {
                    $nilai = rand(78, 98); // Rentang nilai SD umumnya cukup tinggi (KKM tercapai)
                    NilaiRapor::create([
                        'siswa_id' => $siswaId,
                        'mata_pelajaran_id' => $mapelId,
                        'semester_id' => $semesterAktif->id,
                        'nilai_akhir' => $nilai,
                        'tp_tertinggi' => json_encode([['deskripsi' => strtolower($tp1->deskripsi)]]),
                        'tp_terendah' => json_encode([['deskripsi' => strtolower($tp2->deskripsi)]]),
                    ]);
                }
            }
        }

        // 8. Setup P5 (Projek Penguatan Profil Pelajar Pancasila)
        $tema = P5Tema::create(['nama_tema' => 'Gaya Hidup Berkelanjutan', 'deskripsi' => 'Meningkatkan kesadaran akan pentingnya menjaga lingkungan sekitar dari sampah plastik.']);
        $dimensi = P5Dimensi::create(['nama_dimensi' => 'Beriman, Bertakwa kepada Tuhan YME, dan Berakhlak Mulia']);
        $elemen = P5Elemen::create(['p5_dimensi_id' => $dimensi->id, 'nama_elemen' => 'Akhlak kepada alam']);
        $subelemen = P5SubElemen::create([
            'p5_elemen_id' => $elemen->id, 
            'nama_sub_elemen' => 'Memahami Keterhubungan Ekosistem Bumi',
            'capaian_fase_a' => 'Mulai mengenali bagian-bagian dari ekosistem di lingkungan sekolah.',
            'capaian_fase_b' => 'Memahami pentingnya menjaga kebersihan lingkungan sekolah.',
            'capaian_fase_c' => 'Mengambil tindakan nyata dalam mengelola sampah plastik secara mandiri.',
        ]);

        foreach ($rombels as $rombel) {
            $proyek = P5Proyek::create([
                'sekolah_id' => $sekolah->id,
                'semester_id' => $semesterAktif->id,
                'p5_tema_id' => $tema->id,
                'nama_proyek' => 'Sekolahku Bebas Plastik',
                'deskripsi' => 'Proyek mengurangi penggunaan plastik sekali pakai di kantin sekolah',
                'fase' => $rombel->fase,
            ]);
            P5ProyekSubElemen::create(['p5_proyek_id' => $proyek->id, 'p5_sub_elemen_id' => $subelemen->id]);

            $kelompok = P5Kelompok::create([
                'sekolah_id' => $sekolah->id,
                'semester_id' => $semesterAktif->id,
                'nama_kelompok' => 'Pejuang Bumi',
                'tingkat_pendidikan' => $rombel->tingkat,
                'fase' => $rombel->fase,
                'guru_id' => $rombel->wali_kelas_id,
            ]);

            DB::table('p5_kelompok_proyeks')->insert([
                'p5_kelompok_id' => $kelompok->id,
                'p5_proyek_id' => $proyek->id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $anggotaIds = DB::table('anggota_rombels')->where('rombel_id', $rombel->id)->pluck('siswa_id');
            foreach ($anggotaIds as $siswaId) {
                DB::table('p5_kelompok_siswas')->insert([
                    'p5_kelompok_id' => $kelompok->id,
                    'siswa_id' => $siswaId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                $predikats = ['MB', 'SB', 'BSH', 'SAB']; // Mulai Berkembang, Sedang Berkembang, Berkembang Sesuai Harapan, Sangat Berkembang
                P5Nilai::create([
                    'siswa_id' => $siswaId,
                    'p5_proyek_id' => $proyek->id,
                    'p5_sub_elemen_id' => $subelemen->id,
                    'capaian' => $predikats[array_rand($predikats)], 
                ]);
            }
        }
    }
}
