<?php

namespace App\Imports;

use App\Models\P5Nilai;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class CapaianP5Import implements ToCollection, WithHeadingRow
{
    protected $proyek_id;

    public function __construct($proyek_id)
    {
        $this->proyek_id = $proyek_id;
    }

    public function collection(Collection $rows)
    {
        // Heading keys in WithHeadingRow are slugged by default (e.g. "subelemen_abc_id_sub_1").
        // But we need the exact ID. So we iterate row keys.
        
        foreach ($rows as $row) {
            $siswa_id = null;
            if (isset($row['id_siswa_jangan_diubah'])) {
                $siswa_id = $row['id_siswa_jangan_diubah'];
            }

            if (!$siswa_id) {
                continue;
            }

            foreach ($row as $key => $value) {
                // Look for "id_sub_X" in the key string
                // Since headings are slugified, "[ID_SUB: 1]" becomes something like "id_sub_1"
                if (preg_match('/id_sub_(\d+)/', $key, $matches)) {
                    $sub_elemen_id = $matches[1];
                    $predikat = trim(strtoupper($value));
                    
                    if (in_array($predikat, ['MB', 'SB', 'BSH', 'SAB'])) {
                        P5Nilai::updateOrCreate(
                            [
                                'siswa_id' => $siswa_id,
                                'p5_proyek_id' => $this->proyek_id,
                                'p5_sub_elemen_id' => $sub_elemen_id
                            ],
                            [
                                'capaian' => $predikat
                            ]
                        );
                    }
                }
            }
        }
    }
}
