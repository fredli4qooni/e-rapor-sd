<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use App\Models\NilaiDpl;
use App\Models\DplDimensi;

class NilaiDplImport implements ToCollection, WithHeadingRow
{
    protected $rombel_id;
    protected $dimensi_id;
    protected $semester_id;
    protected $subdimensis;

    public function __construct($rombel_id, $dimensi_id, $semester_id)
    {
        $this->rombel_id = $rombel_id;
        $this->dimensi_id = $dimensi_id;
        $this->semester_id = $semester_id;
        
        $dimensi = DplDimensi::with('subdimensis')->find($dimensi_id);
        $this->subdimensis = $dimensi->subdimensis->keyBy('id');
    }

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            $siswa_id = $row['id_siswa_jangan_diubah'];

            if (!$siswa_id) {
                continue;
            }

            foreach ($this->subdimensis as $sub) {
                $nilaiVal = null;
                foreach ($row as $key => $val) {
                    if (str_contains($key, 'subdimensi') && str_contains($key, 'id'.$sub->id)) {
                        $nilaiVal = trim($val);
                        break;
                    }
                }

                if (in_array($nilaiVal, ['1', '2', '3'])) {
                    NilaiDpl::updateOrCreate(
                        [
                            'siswa_id' => $siswa_id,
                            'rombel_id' => $this->rombel_id,
                            'semester_id' => $this->semester_id,
                            'dpl_subdimensi_id' => $sub->id,
                        ],
                        [
                            'nilai' => $nilaiVal
                        ]
                    );
                } elseif ($nilaiVal === '' || $nilaiVal === null) {
                    NilaiDpl::where([
                        'siswa_id' => $siswa_id,
                        'rombel_id' => $this->rombel_id,
                        'semester_id' => $this->semester_id,
                        'dpl_subdimensi_id' => $sub->id,
                    ])->delete();
                }
            }
        }
    }
}
