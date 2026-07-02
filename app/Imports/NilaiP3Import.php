<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use App\Models\NilaiP3;
use App\Models\P5Dimensi;

class NilaiP3Import implements ToCollection, WithHeadingRow
{
    protected $rombel_id;
    protected $dimensi_id;
    protected $semester_id;
    protected $subElemens;

    public function __construct($rombel_id, $dimensi_id, $semester_id)
    {
        $this->rombel_id = $rombel_id;
        $this->dimensi_id = $dimensi_id;
        $this->semester_id = $semester_id;
        
        $dimensi = P5Dimensi::with('elemens.subElemens')->find($dimensi_id);
        
        $this->subElemens = collect();
        foreach ($dimensi->elemens as $elemen) {
            foreach ($elemen->subElemens as $sub) {
                $this->subElemens->push($sub);
            }
        }
        $this->subElemens = $this->subElemens->keyBy('id');
    }

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            $siswa_id = $row['id_siswa_jangan_diubah'];

            if (!$siswa_id) {
                continue;
            }

            foreach ($this->subElemens as $sub) {
                $nilaiVal = null;
                foreach ($row as $key => $val) {
                    if (str_contains($key, 'subelemen') && str_contains($key, 'id'.$sub->id)) {
                        $nilaiVal = trim($val);
                        break;
                    }
                }

                if (in_array($nilaiVal, ['1', '2', '3', '4'])) {
                    NilaiP3::updateOrCreate(
                        [
                            'siswa_id' => $siswa_id,
                            'rombel_id' => $this->rombel_id,
                            'semester_id' => $this->semester_id,
                            'p5_sub_elemen_id' => $sub->id,
                        ],
                        [
                            'nilai' => $nilaiVal
                        ]
                    );
                } elseif ($nilaiVal === '' || $nilaiVal === null) {
                    NilaiP3::where([
                        'siswa_id' => $siswa_id,
                        'rombel_id' => $this->rombel_id,
                        'semester_id' => $this->semester_id,
                        'p5_sub_elemen_id' => $sub->id,
                    ])->delete();
                }
            }
        }
    }
}
