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

        return view('kepsek.dashboard', compact('sekolah', 'semester_aktif', 'semester_teks', 'counts', 'chart_labels', 'chart_data'));
    }
}
