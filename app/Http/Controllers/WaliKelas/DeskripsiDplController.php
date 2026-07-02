<?php

namespace App\Http\Controllers\WaliKelas;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Rombel;
use App\Models\Siswa;
use App\Models\Sikap;
use App\Models\Semester;

class DeskripsiDplController extends Controller
{
    public function index(Request $request)
    {
        $semesterAktif = Semester::where('is_aktif', true)->first();
        $tahunParts = explode('/', $semesterAktif->tahun_ajaran ?? '2025/2026');
        $startYear = (int) $tahunParts[0];

        if ($startYear < 2025) {
            return redirect()->route('walikelas.dashboard')->with('error', 'Menu Deskripsi DPL hanya aktif mulai tahun ajaran 2025/2026.');
        }

        $rombel = Rombel::where('wali_kelas_id', auth()->user()->guru->id ?? 1)->first(); 
        if (!$rombel) {
            return redirect()->route('walikelas.dashboard')->with('error', 'Anda belum ditugaskan sebagai Wali Kelas.');
        }

        $siswas = $rombel->siswas()->orderBy('nama_lengkap')->get();
        $sikaps = Sikap::where('rombel_id', $rombel->id)->get()->keyBy('siswa_id');

        return view('walikelas.deskripsi_dpl.index', compact('rombel', 'siswas', 'sikaps'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'rombel_id' => 'required|exists:rombels,id',
            'data' => 'required|array',
        ]);

        foreach ($data['data'] as $siswa_id => $input) {
            if (!empty($input['deskripsi_dpl'])) {
                Sikap::updateOrCreate(
                    ['siswa_id' => $siswa_id, 'rombel_id' => $data['rombel_id']],
                    ['deskripsi_dpl' => $input['deskripsi_dpl']]
                );
            }
        }

        return redirect()->back()->with('success', 'Deskripsi DPL berhasil disimpan!');
    }

    public function generate(Request $request)
    {
        $data = $request->validate([
            'rombel_id' => 'required|exists:rombels,id',
        ]);

        $rombel = Rombel::find($data['rombel_id']);
        $siswas = $rombel->siswas;

        foreach ($siswas as $siswa) {
            // Generate dummy deskripsi based on some logic or leave it as basic for now
            $deskripsi = "Siswa menunjukkan perkembangan karakter yang positif sesuai dengan Dimensi Profil Lulusan (DPL), meliputi aspek beriman, bertakwa kepada Tuhan YME, berakhlak mulia, serta mandiri dan peduli terhadap lingkungan sekitar.";
            
            Sikap::updateOrCreate(
                ['siswa_id' => $siswa->id, 'rombel_id' => $rombel->id],
                ['deskripsi_dpl' => $deskripsi]
            );
        }

        return redirect()->back()->with('success', 'Deskripsi DPL berhasil digenerate secara otomatis!');
    }
}
