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
            ->where('tingkat', $rombel->tingkat)
            ->where('semester_id', $semester_id)
            ->where('is_aktif', true)
            ->get()
            ->keyBy('id');
    }

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            $siswa_id = $row['id_siswa_jangan_diubah'];
            $nilai_akhir = $row['nilai_akhir'];

            if (!$siswa_id || !is_numeric($nilai_akhir)) {
                continue;
            }

            $tpTertinggiIds = [];
            $tpTerendahIds = [];

            foreach ($this->tps as $tp) {
                // Construct the expected column keys based on headings logic
                // The headings generate: "Capaian Tertinggi TP 1 [ID:X] (Isi T)"
                // Maatwebsite Excel converts this to snake_case usually, or removes symbols
                // It's safer to loop over $row keys to find the one containing the ID
                foreach ($row as $key => $val) {
                    if (str_contains($key, 'capaian_tertinggi_tp') && str_contains($key, 'id'.$tp->id)) {
                        if (strtoupper(trim($val)) === 'T') {
                            $tpTertinggiIds[] = $tp->id;
                        }
                    }
                    if (str_contains($key, 'capaian_terendah_tp') && str_contains($key, 'id'.$tp->id)) {
                        if (strtoupper(trim($val)) === 'R') {
                            $tpTerendahIds[] = $tp->id;
                        }
                    }
                }
            }

            $nilaiRapor = NilaiRapor::updateOrCreate(
                [
                    'siswa_id' => $siswa_id,
                    'mata_pelajaran_id' => $this->mata_pelajaran_id,
                    'semester_id' => $this->semester_id,
                ],
                [
                    'nilai_akhir' => $nilai_akhir,
                    'tp_tertinggi' => $tpTertinggiIds,
                    'tp_terendah' => $tpTerendahIds,
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
        }
    }
}
