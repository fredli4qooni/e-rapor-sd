<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class LegerRaporExport implements FromView, ShouldAutoSize
{
    protected $rombel_id;
    protected $semua_semester;

    public function __construct($rombel_id, $semua_semester = false)
    {
        $this->rombel_id = $rombel_id;
        $this->semua_semester = $semua_semester;
    }

    public function view(): View
    {
        $rombel = \App\Models\Rombel::findOrFail($this->rombel_id);
        
        $siswas = \App\Models\Siswa::whereHas('rombels', function($q) use ($rombel) {
            $q->where('rombels.id', $rombel->id);
        })->orderBy('nama_lengkap')->get();

        // Get mapel
        $mapels = \App\Models\MataPelajaran::orderBy('kelompok_mapel_id')->orderBy('nomor_urut')->orderBy('nama_mapel')->get();

        $siswaIds = $siswas->pluck('id')->toArray();
        $mapelIds = $mapels->pluck('id')->toArray();

        // Active semester / all semesters logic
        $semesterAktif = \App\Models\Semester::where('is_aktif', true)->first();
        
        $queryNilai = \App\Models\NilaiRapor::whereIn('siswa_id', $siswaIds)
                                            ->whereIn('mata_pelajaran_id', $mapelIds);
        
        $querySikap = \App\Models\Sikap::whereIn('siswa_id', $siswaIds)->where('rombel_id', $rombel->id);
        $queryKehadiran = \App\Models\Kehadiran::whereIn('siswa_id', $siswaIds);

        if (!$this->semua_semester && $semesterAktif) {
            $queryNilai->where('semester_id', $semesterAktif->id);
            $queryKehadiran->where('semester_id', $semesterAktif->id);
        }

        $semuaNilai = $queryNilai->get()->groupBy('siswa_id');
        
        // Asumsi sikap & kehadiran kita ratakan dari semua input yg ada untuk simplifikasi
        $semuaSikap = $querySikap->get()->groupBy('siswa_id');
        $semuaKehadiran = $queryKehadiran->get()->groupBy('siswa_id');

        return view('admin.cetak_nilai.excel.leger', [
            'rombel' => $rombel,
            'siswas' => $siswas,
            'mapels' => $mapels,
            'semuaNilai' => $semuaNilai,
            'semuaSikap' => $semuaSikap,
            'semuaKehadiran' => $semuaKehadiran,
            'semesterAktif' => $semesterAktif,
            'semua_semester' => $this->semua_semester
        ]);
    }
}
