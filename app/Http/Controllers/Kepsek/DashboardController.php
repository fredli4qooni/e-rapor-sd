<?php

namespace App\Http\Controllers\Kepsek;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Guru;
use App\Models\Siswa;
use App\Models\Rombel;
use App\Models\Pembelajaran;
use App\Models\Semester;

class DashboardController extends Controller
{
    public function index()
    {
        $sekolah = \App\Models\Sekolah::first();
        $semester_aktif = Semester::where('is_aktif', true)->first();
        $semester_teks = $semester_aktif ? $semester_aktif->tahun_ajaran . ' ' . ($semester_aktif->semester == 1 ? 'Ganjil' : 'Genap') : 'Belum disetup';

        // Hitung Rekap Data untuk Dashboard Eksekutif
        $counts = [
            'guru' => Guru::count(),
            'siswa' => Siswa::count(),
            'rombel' => $semester_aktif ? Rombel::where('semester_id', $semester_aktif->id)->count() : 0,
            'pembelajaran' => $semester_aktif ? Pembelajaran::where('semester_id', $semester_aktif->id)->count() : 0,
        ];

        // Data untuk Grafik Siswa per Rombel
        $rombels = $semester_aktif ? Rombel::where('semester_id', $semester_aktif->id)->withCount('siswas')->get() : collect();
        $chart_labels = $rombels->pluck('nama_rombel')->toJson();
        $chart_data = $rombels->pluck('siswas_count')->toJson();

        // Data Grafik Analitik Nilai Rata-rata per Mata Pelajaran
        $grafik_nilai = [];
        if ($semester_aktif) {
            $grafik_nilai = \Illuminate\Support\Facades\DB::table('nilai_rapors')
                ->join('mata_pelajarans', 'nilai_rapors.mata_pelajaran_id', '=', 'mata_pelajarans.id')
                ->select('mata_pelajarans.nama_mapel', \Illuminate\Support\Facades\DB::raw('AVG(nilai_rapors.nilai_akhir) as rata_rata'))
                ->where('nilai_rapors.semester_id', $semester_aktif->id)
                ->groupBy('mata_pelajarans.id', 'mata_pelajarans.nama_mapel')
                ->get();
        }
        $chart_nilai_labels = $grafik_nilai ? collect($grafik_nilai)->pluck('nama_mapel')->toJson() : '[]';
        $chart_nilai_data = $grafik_nilai ? collect($grafik_nilai)->pluck('rata_rata')->map(fn($v) => round($v, 2))->toJson() : '[]';

        return view('kepsek.dashboard', compact('sekolah', 'semester_aktif', 'semester_teks', 'counts', 'chart_labels', 'chart_data', 'chart_nilai_labels', 'chart_nilai_data'));
    }
}
