<?php

namespace App\Http\Controllers\WaliKelas;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Rombel;
use App\Models\Siswa;
use App\Models\Sikap;

class SikapController extends Controller
{
    public function index(Request $request)
    {
        $rombel = Rombel::where('wali_kelas_id', auth()->user()->guru->id ?? 1)->first(); 
        if (!$rombel) return redirect()->back()->with('error', 'Anda belum ditugaskan sebagai Wali Kelas.');

        $siswas = Siswa::where('sekolah_id', $rombel->sekolah_id)->get();
        $sikaps = Sikap::where('rombel_id', $rombel->id)->get()->keyBy('siswa_id');

        return view('walikelas.sikap.index', compact('rombel', 'siswas', 'sikaps'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'rombel_id' => 'required|exists:rombels,id',
            'data' => 'required|array',
        ]);

        foreach ($data['data'] as $siswa_id => $input) {
            Sikap::updateOrCreate(
                ['siswa_id' => $siswa_id, 'rombel_id' => $data['rombel_id']],
                [
                    'predikat_spiritual' => $input['predikat_spiritual'] ?? null,
                    'deskripsi_spiritual' => $input['deskripsi_spiritual'] ?? null,
                    'predikat_sosial' => $input['predikat_sosial'] ?? null,
                    'deskripsi_sosial' => $input['deskripsi_sosial'] ?? null,
                ]
            );
        }

        return redirect()->back()->with('status', 'Nilai Sikap berhasil disimpan!');
    }
}
