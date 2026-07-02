<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

use App\Models\Rombel;
use App\Models\Siswa;
use App\Models\NilaiRapor;

class LegerExport implements FromCollection, WithHeadings
{
    protected $rombel;
    protected $tipe;

    public function __construct($rombel, $tipe)
    {
        $this->rombel = $rombel;
        $this->tipe = $tipe;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $rombel = $this->rombel;
        if (!$rombel) return collect([]);
        
        $siswas = Siswa::where('sekolah_id', $rombel->sekolah_id)->get(); // Should be filtered by rombel too
        // Filter siswas to just this rombel
        $siswas = $rombel->siswas()->get();
        
        $data = [];
        foreach ($siswas as $siswa) {
            $nilaiRecords = NilaiRapor::with('mapel')
                                      ->where('siswa_id', $siswa->id)
                                      ->get();
            
            $row = [
                'nis' => $siswa->nis,
                'nama' => $siswa->nama_lengkap,
            ];
            
            $total = 0;
            $count = 0;
            
            // Note: the headings match specific mapels in MVP, but here we just list the ones available dynamically if we can, 
            // but since WithHeadings is hardcoded to specific subjects in MVP, we map them manually for demo.
            $mapels = ['Pendidikan Agama Islam' => 'pai', 'Pendidikan Pancasila' => 'pkn', 'Bahasa Indonesia' => 'bindo', 'Matematika' => 'mtk', 'IPAS' => 'ipa', 'PJOK' => 'pjok', 'Seni Budaya' => 'sbdp'];
            
            foreach ($mapels as $name => $key) {
                $nilaiMapel = $nilaiRecords->firstWhere('mapel.nama_mapel', $name);
                $n = $nilaiMapel ? $nilaiMapel->nilai_akhir : 0;
                $row[$key] = $n;
                $total += $n;
                if ($n > 0) $count++;
            }
            
            $row['total'] = $total;
            $row['rata_rata'] = $count > 0 ? round($total / $count, 2) : 0;
            $row['peringkat'] = '-'; // Pending logic
            
            $data[] = $row;
        }

        return collect($data)->sortByDesc('total')->values()->map(function($item, $key) {
            $item['peringkat'] = $key + 1;
            return $item;
        });
    }

    public function headings(): array
    {
        return [
            'NIS',
            'Nama Lengkap',
            'PAI',
            'PKn',
            'B. Indo',
            'MTK',
            'IPA',
            'IPS',
            'SBdP',
            'PJOK',
            'Total Nilai',
            'Rata-rata',
            'Peringkat'
        ];
    }
}
