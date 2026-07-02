<?php

namespace App\Http\Controllers\WaliKelas;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Rombel;
use App\Models\Siswa;
use App\Models\Sikap;
use App\Models\Semester;

class DeskripsiP3Controller extends Controller
{
    public function index(Request $request)
    {
        $semesterAktif = Semester::where('is_aktif', true)->first();
        $tahunParts = explode('/', $semesterAktif->tahun_ajaran ?? '2024/2025');
        $startYear = (int) $tahunParts[0];

        if ($startYear >= 2025) {
            return redirect()->route('walikelas.dashboard')->with('error', 'Menu Deskripsi P3 hanya aktif sebelum tahun ajaran 2025/2026.');
        }

        $rombel = Rombel::where('wali_kelas_id', auth()->user()->guru->id ?? 1)->first(); 
        if (!$rombel) {
            return redirect()->route('walikelas.dashboard')->with('error', 'Anda belum ditugaskan sebagai Wali Kelas.');
        }

        $siswas = $rombel->siswas()->orderBy('nama_lengkap')->get();
        $sikaps = Sikap::where('rombel_id', $rombel->id)->get()->keyBy('siswa_id');

        return view('walikelas.deskripsi_p3.index', compact('rombel', 'siswas', 'sikaps'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'rombel_id' => 'required|exists:rombels,id',
            'data' => 'required|array',
        ]);

        foreach ($data['data'] as $siswa_id => $input) {
            if (!empty($input['p3'])) {
                $jsonP3 = json_encode($input['p3']);
                Sikap::updateOrCreate(
                    ['siswa_id' => $siswa_id, 'rombel_id' => $data['rombel_id']],
                    ['deskripsi_p3' => $jsonP3]
                );
            }
        }

        return redirect()->back()->with('success', 'Deskripsi P3 berhasil disimpan!');
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
            $deskripsiArray = [
                'beriman' => 'Dalam penguatan dimensi Beriman, Bertakwa Kepada Tuhan Yang Maha Esa, dan Berakhlak Mulia, ' . $siswa->nama_lengkap . ' menunjukkan sangat berkembang.',
                'berkebinekaan' => 'Dalam penguatan dimensi berkebinekaan global, ' . $siswa->nama_lengkap . ' menunjukkan berkembang sesuai harapan.',
                'bergotong_royong' => 'Dalam penguatan dimensi bergotong royong, ' . $siswa->nama_lengkap . ' menunjukkan mulai berkembang.',
                'mandiri' => 'Dalam penguatan dimensi mandiri, ' . $siswa->nama_lengkap . ' menunjukkan sedang berkembang.',
                'bernalar_kritis' => 'Dalam penguatan dimensi bernalar kritis, ' . $siswa->nama_lengkap . ' menunjukkan berkembang sesuai harapan.',
                'kreatif' => 'Dalam penguatan dimensi kreatif, ' . $siswa->nama_lengkap . ' menunjukkan sangat berkembang.'
            ];
            $jsonP3 = json_encode($deskripsiArray);
            
            Sikap::updateOrCreate(
                ['siswa_id' => $siswa->id, 'rombel_id' => $rombel->id],
                ['deskripsi_p3' => $jsonP3]
            );
        }

        return redirect()->back()->with('success', 'Deskripsi P3 berhasil digenerate secara otomatis!');
    }
}
