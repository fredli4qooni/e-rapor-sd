<?php

namespace App\Http\Controllers\Kepsek;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Guru;
use App\Models\Siswa;
use App\Models\Rombel;
use App\Models\Semester;

class MonitoringController extends Controller
{
    public function guru()
    {
        $gurus = Guru::with('user')->orderBy('nama_lengkap')->get();
        return view('kepsek.monitoring.guru', compact('gurus'));
    }

    public function siswa(Request $request)
    {
        $query = Siswa::with(['user', 'rombels' => function($q) {
            $semester_aktif = Semester::where('is_aktif', true)->first();
            if ($semester_aktif) {
                $q->where('semester_id', $semester_aktif->id);
            }
        }]);

        if ($request->has('search')) {
            $search = $request->search;
            $query->where('nama_lengkap', 'like', "%{$search}%")
                  ->orWhere('nisn', 'like', "%{$search}%");
        }

        $siswas = $query->orderBy('nama_lengkap')->paginate(50);
        return view('kepsek.monitoring.siswa', compact('siswas'));
    }

    public function rombel()
    {
        $semester_aktif = Semester::where('is_aktif', true)->first();
        $rombels = collect();
        if ($semester_aktif) {
            $rombels = Rombel::with(['waliKelas', 'siswas'])
                ->where('semester_id', $semester_aktif->id)
                ->orderBy('tingkat')
                ->orderBy('nama_rombel')
                ->get();
        }
        return view('kepsek.monitoring.rombel', compact('rombels', 'semester_aktif'));
    }
}
