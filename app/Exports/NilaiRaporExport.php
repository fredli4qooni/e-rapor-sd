<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use App\Models\Siswa;
use App\Models\TujuanPembelajaran;
use App\Models\Rombel;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class NilaiRaporExport implements FromCollection, WithHeadings
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
            ->get();
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
                'nilai_akhir' => '',
            ];

            foreach ($this->tps as $index => $tp) {
                $row['tp_tertinggi_' . $tp->id] = ''; // Format: T / Kosong
                $row['tp_terendah_' . $tp->id] = ''; // Format: R / Kosong
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
            'Nilai Akhir',
        ];

        foreach ($this->tps as $index => $tp) {
            $headings[] = 'Capaian Tertinggi TP ' . ($index + 1) . ' [ID:'.$tp->id.'] (Isi T)';
            $headings[] = 'Capaian Terendah TP ' . ($index + 1) . ' [ID:'.$tp->id.'] (Isi R)';
        }

        return $headings;
    }
}
