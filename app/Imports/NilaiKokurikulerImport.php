<?php

namespace App\Imports;

use App\Models\P5Nilai;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class NilaiKokurikulerImport implements ToCollection, WithHeadingRow
{
    protected $proyek_id;

    public function __construct($proyek_id)
    {
        $this->proyek_id = $proyek_id;
    }

    public function collection(Collection $rows)
    {
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
                    $predikat = trim($value);
                    
                    if (in_array($predikat, ['1', '2', '3'])) {
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
                    } elseif (empty($predikat)) {
                        P5Nilai::where([
                            'siswa_id' => $siswa_id,
                            'p5_proyek_id' => $this->proyek_id,
                            'p5_sub_elemen_id' => $sub_elemen_id
                        ])->delete();
                    }
                }
            }
        }
    }
}
