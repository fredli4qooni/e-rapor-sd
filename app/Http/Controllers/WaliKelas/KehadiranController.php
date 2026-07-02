<?php

namespace App\Http\Controllers\WaliKelas;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Rombel;
use App\Models\Siswa;
use App\Models\Kehadiran;

class KehadiranController extends Controller
{
    public function index(Request $request)
    {
        $rombel = Rombel::where('wali_kelas_id', auth()->user()->guru->id ?? 1)->first(); 
        if (!$rombel) {
            return redirect()->route('walikelas.dashboard')->with('error', 'Anda belum ditugaskan sebagai Wali Kelas.');
        }

        $semesterAktif = \App\Models\Semester::where('is_aktif', true)->first();
        if (!$semesterAktif) {
            return redirect()->route('walikelas.dashboard')->with('error', 'Tidak ada semester aktif.');
        }

        $siswas = $rombel->siswas()->orderBy('nama_lengkap')->get();
        $kehadirans = Kehadiran::where('semester_id', $semesterAktif->id)->get()->keyBy('siswa_id');

        return view('walikelas.kehadiran.index', compact('rombel', 'siswas', 'kehadirans'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'rombel_id' => 'required|exists:rombels,id',
            'data' => 'required|array',
        ]);

        $semesterAktif = \App\Models\Semester::where('is_aktif', true)->first();

        foreach ($data['data'] as $siswa_id => $input) {
            Kehadiran::updateOrCreate(
                ['siswa_id' => $siswa_id, 'semester_id' => $semesterAktif->id],
                [
                    'sakit' => $input['sakit'] ?? 0,
                    'izin' => $input['izin'] ?? 0,
                    'tanpa_keterangan' => $input['alpa'] ?? 0,
                ]
            );
        }

        return redirect()->back()->with('success', 'Data Kehadiran berhasil disimpan!');
    }
}
