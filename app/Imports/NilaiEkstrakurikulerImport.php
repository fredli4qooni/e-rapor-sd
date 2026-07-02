<?php

namespace App\Imports;

use App\Models\NilaiEkstrakurikuler;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class NilaiEkstrakurikulerImport implements ToCollection, WithHeadingRow
{
    protected $ekstrakurikuler_id;
    protected $rombel_id;

    public function __construct($ekstrakurikuler_id, $rombel_id)
    {
        $this->ekstrakurikuler_id = $ekstrakurikuler_id;
        $this->rombel_id = $rombel_id;
    }

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            $id_nilai = $row['id_nilai_ekskul_jangan_diubah'];
            $predikat = $row['predikat'];
            $keterangan = $row['keterangan'];

            if (!$id_nilai) {
                continue;
            }

            // Only update if it belongs to the selected ekskul & rombel
            $nilaiEkskul = NilaiEkstrakurikuler::where('id', $id_nilai)
                ->where('ekstrakurikuler_id', $this->ekstrakurikuler_id)
                ->where('rombel_id', $this->rombel_id)
                ->first();

            if ($nilaiEkskul) {
                $nilaiEkskul->update([
                    'predikat' => $predikat,
                    'keterangan' => $keterangan,
                ]);
            }
        }
    }
}
