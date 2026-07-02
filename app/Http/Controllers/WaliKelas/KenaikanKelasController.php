<?php

namespace App\Http\Controllers\WaliKelas;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Rombel;
use App\Models\Siswa;
use App\Models\KenaikanKelas;
use App\Models\Semester;

class KenaikanKelasController extends Controller
{
    public function index(Request $request)
    {
        $rombel = Rombel::where('wali_kelas_id', auth()->user()->guru->id ?? 1)->first(); 
        if (!$rombel) {
            return redirect()->route('walikelas.dashboard')->with('error', 'Anda belum ditugaskan sebagai Wali Kelas.');
        }

        $semesterAktif = Semester::where('is_aktif', true)->first();
        // Kenaikan kelas biasanya hanya di akhir semester genap
        $isGenap = $semesterAktif && strtolower($semesterAktif->semester) === 'genap';

        $siswas = $rombel->siswas()->orderBy('nama_lengkap')->get();
        $kenaikans = KenaikanKelas::where('semester_id', $semesterAktif->id)->get()->keyBy('siswa_id');

        return view('walikelas.kenaikan.index', compact('rombel', 'siswas', 'kenaikans', 'isGenap'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'rombel_id' => 'required|exists:rombels,id',
            'data' => 'required|array',
        ]);

        $semesterAktif = Semester::where('is_aktif', true)->first();

        foreach ($data['data'] as $siswa_id => $input) {
            $status_kenaikan = '';
            if (!empty($input['status']) && !empty($input['kelas_tujuan'])) {
                $status_kenaikan = $input['status'] . ' ' . $input['kelas_tujuan'];
            } elseif (!empty($input['status'])) {
                $status_kenaikan = $input['status'];
            }

            if (!empty($status_kenaikan)) {
                KenaikanKelas::updateOrCreate(
                    ['siswa_id' => $siswa_id, 'semester_id' => $semesterAktif->id],
                    ['status_kenaikan' => $status_kenaikan]
                );
            } else {
                KenaikanKelas::where([
                    'siswa_id' => $siswa_id,
                    'semester_id' => $semesterAktif->id
                ])->delete();
            }
        }

        return redirect()->back()->with('success', 'Status Kenaikan Kelas berhasil disimpan!');
    }
}
