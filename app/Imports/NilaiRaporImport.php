<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use App\Models\NilaiRapor;
use App\Models\DeskripsiRapor;
use App\Models\TujuanPembelajaran;
use App\Models\Rombel;

class NilaiRaporImport implements ToCollection, WithHeadingRow
{
    protected $rombel_id;
    protected $mata_pelajaran_id;
    protected $semester_id;
    protected $tps;

    public function __construct($rombel_id, $mata_pelajaran_id, $semester_id)
    {
        $this->rombel_id = $rombel_id;
        $this->mata_pelajaran_id = $mata_pelajaran_id;
        $this->semester_id = $semester_id;
        
        $rombel = Rombel::find($rombel_id);
        $this->tps = TujuanPembelajaran::where('mata_pelajaran_id', $mata_pelajaran_id)
            ->where('tingkat', $rombel->tingkat ?? null)
            ->where('semester_id', $semester_id)
            ->where('is_aktif', true)
            ->get()
            ->keyBy('id');
    }

    public function collection(Collection $rows)
    {
        if ($rows->isEmpty()) {
            throw new \Exception("File Excel yang diunggah kosong! Pastikan file berisi data siswa.");
        }

        // Cek baris pertama untuk validasi keberadaan kolom esensial
        $firstRow = $rows->first()->toArray();
        $keys = array_keys($firstRow);

        $hasIdSiswa = false;
        $hasNilaiAkhir = false;

        foreach ($keys as $k) {
            $normKey = strtolower(str_replace([' ', '_', '-', '(', ')'], '', $k));
            if (str_contains($normKey, 'idsiswajangandiubah') || str_contains($normKey, 'idsiswa') || str_contains($normKey, 'siswaid')) {
                $hasIdSiswa = true;
            }
            if (str_contains($normKey, 'nilaiakhir') || str_contains($normKey, 'nilai')) {
                $hasNilaiAkhir = true;
            }
        }

        if (!$hasIdSiswa || !$hasNilaiAkhir) {
            throw new \Exception("Format file Excel tidak sesuai dengan template! Pastikan kolom 'ID Siswa (JANGAN DIUBAH)' dan 'Nilai Akhir' ada dan tidak diubah.");
        }

        $importedCount = 0;

        foreach ($rows as $row) {
            // Find siswa_id and nilai_akhir dynamically
            $siswa_id = null;
            $nilai_akhir = null;

            foreach ($row as $key => $val) {
                $normKey = strtolower(str_replace([' ', '_', '-', '(', ')'], '', $key));
                if (str_contains($normKey, 'idsiswajangandiubah') || str_contains($normKey, 'idsiswa') || str_contains($normKey, 'siswaid')) {
                    $siswa_id = $val;
                }
                if (str_contains($normKey, 'nilaiakhir')) {
                    $nilai_akhir = $val;
                }
            }

            if (!$siswa_id || !is_numeric($nilai_akhir)) {
                continue;
            }

            $tpTertinggiIds = [];
            $tpTerendahIds = [];

            foreach ($this->tps as $tp) {
                foreach ($row as $key => $val) {
                    $valClean = strtoupper(trim((string)$val));
                    
                    // Format baru: 1 kolom TP (berisi 'T' atau 'R')
                    if (str_contains($key, 'tp') && str_contains($key, 'id'.$tp->id)) {
                        if ($valClean === 'T') {
                            $tpTertinggiIds[] = $tp->id;
                        } elseif ($valClean === 'R') {
                            $tpTerendahIds[] = $tp->id;
                        }
                    }

                    // Backward compatibility untuk format lama (2 kolom)
                    if (str_contains($key, 'capaian_tertinggi_tp') && str_contains($key, 'id'.$tp->id)) {
                        if ($valClean === 'T') {
                            $tpTertinggiIds[] = $tp->id;
                        }
                    }
                    if (str_contains($key, 'capaian_terendah_tp') && str_contains($key, 'id'.$tp->id)) {
                        if ($valClean === 'R') {
                            $tpTerendahIds[] = $tp->id;
                        }
                    }
                }
            }

            // Pastikan TP Tertinggi dan Terendah saling eksklusif
            $tpTerendahIds = array_diff($tpTerendahIds, $tpTertinggiIds);

            $nilaiRapor = NilaiRapor::updateOrCreate(
                [
                    'siswa_id' => $siswa_id,
                    'mata_pelajaran_id' => $this->mata_pelajaran_id,
                    'semester_id' => $this->semester_id,
                ],
                [
                    'nilai_akhir' => $nilai_akhir,
                    'tp_tertinggi' => array_values($tpTertinggiIds),
                    'tp_terendah' => array_values($tpTerendahIds),
                ]
            );

            // Generate Auto Deskripsi
            $deskripsiTertinggi = '';
            $deskripsiTerendah = '';

            if (count($tpTertinggiIds) > 0) {
                $descTexts = [];
                foreach ($tpTertinggiIds as $tId) {
                    if (isset($this->tps[$tId])) {
                        $descTexts[] = $this->tps[$tId]->deskripsi;
                    }
                }
                if (count($descTexts) > 0) {
                    $deskripsiTertinggi = "Menunjukkan penguasaan yang sangat baik dalam " . implode(", ", $descTexts) . ".";
                }
            }

            if (count($tpTerendahIds) > 0) {
                $descTexts = [];
                foreach ($tpTerendahIds as $tId) {
                    if (isset($this->tps[$tId])) {
                        $descTexts[] = $this->tps[$tId]->deskripsi;
                    }
                }
                if (count($descTexts) > 0) {
                    $deskripsiTerendah = "Perlu pendampingan dalam " . implode(", ", $descTexts) . ".";
                }
            }

            DeskripsiRapor::updateOrCreate(
                ['nilai_rapor_id' => $nilaiRapor->id],
                [
                    'deskripsi_tertinggi' => $deskripsiTertinggi,
                    'deskripsi_terendah' => $deskripsiTerendah,
                ]
            );

            $importedCount++;
        }

        if ($importedCount === 0) {
            throw new \Exception("Tidak ada data nilai yang berhasil diimpor! Pastikan nilai akhir telah diisi dengan angka yang valid.");
        }
    }
}
