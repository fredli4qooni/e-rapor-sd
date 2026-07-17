<?php

namespace App\Http\Controllers\WaliKelas;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Maatwebsite\Excel\Facades\Excel;
use App\Exports\LegerExport;

class WaliKelasController extends Controller
{
    public function dashboard()
    {
        $semester_aktif = \App\Models\Semester::where('is_aktif', true)->first();
        $guru = \Illuminate\Support\Facades\Auth::user()->guru;
        $rombel = null;
        if ($guru && $semester_aktif) {
            $rombel = \App\Models\Rombel::where('wali_kelas_id', $guru->id)->where('semester_id', $semester_aktif->id)->first();
        }

        $grafik_nilai = [];
        if ($semester_aktif && $rombel) {
            $siswaIds = \Illuminate\Support\Facades\DB::table('anggota_rombels')->where('rombel_id', $rombel->id)->pluck('siswa_id');
            if ($siswaIds->count() > 0) {
                $grafik_nilai = \Illuminate\Support\Facades\DB::table('nilai_rapors')
                    ->join('mata_pelajarans', 'nilai_rapors.mata_pelajaran_id', '=', 'mata_pelajarans.id')
                    ->select('mata_pelajarans.nama_mapel', \Illuminate\Support\Facades\DB::raw('AVG(nilai_rapors.nilai_akhir) as rata_rata'))
                    ->where('nilai_rapors.semester_id', $semester_aktif->id)
                    ->whereIn('nilai_rapors.siswa_id', $siswaIds)
                    ->groupBy('mata_pelajarans.id', 'mata_pelajarans.nama_mapel')
                    ->get();
            }
        }
        $chart_nilai_labels = $grafik_nilai ? collect($grafik_nilai)->pluck('nama_mapel')->toJson() : '[]';
        $chart_nilai_data = $grafik_nilai ? collect($grafik_nilai)->pluck('rata_rata')->map(fn($v) => round($v, 2))->toJson() : '[]';

        return view('walikelas.dashboard', compact('chart_nilai_labels', 'chart_nilai_data'));
    }

    public function exportLeger()
    {
        return Excel::download(new LegerExport, 'leger_nilai_kelas.xlsx');
    }
}
