<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use App\Models\Siswa;
use App\Models\Rombel;
use App\Models\DplDimensi;
use App\Models\NilaiDpl;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class NilaiDplExport implements FromCollection, WithHeadings
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
        $this->subdimensis = $dimensi->subdimensis;
    }

    public function collection()
    {
        $siswaIds = DB::table('anggota_rombels')->where('rombel_id', $this->rombel_id)->pluck('siswa_id');
        $siswas = Siswa::whereIn('id', $siswaIds)->orderBy('nama_lengkap')->get();

        $data = collect();
        foreach ($siswas as $siswa) {
            $row = [
                'siswa_id' => $siswa->id,
                'nama_siswa' => $siswa->nama_lengkap,
            ];

            foreach ($this->subdimensis as $sub) {
                // Skala: 1 (Berkembang), 2 (Cakap), 3 (Mahir)
                $row['nilai_sub_' . $sub->id] = ''; 
            }

            $data->push((object)$row);
        }

        return $data;
    }

    public function headings(): array
    {
        $headings = [
            'ID Siswa (JANGAN DIUBAH)',
            'Nama Siswa',
        ];

        foreach ($this->subdimensis as $sub) {
            $headings[] = 'Subdimensi [ID:'.$sub->id.'] (Isi 1/2/3)';
        }

        return $headings;
    }
}
