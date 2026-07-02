<?php

namespace App\Http\Controllers\WaliKelas;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Rombel;
use App\Models\Siswa;

class DataSiswaController extends Controller
{
    public function index(Request $request)
    {
        $rombel = Rombel::where('wali_kelas_id', auth()->user()->guru->id ?? 1)->first();
        if (!$rombel) {
            return redirect()->route('walikelas.dashboard')->with('error', 'Anda belum ditugaskan sebagai Wali Kelas.');
        }

        $siswas = Siswa::where('sekolah_id', $rombel->sekolah_id)->orderBy('nama_lengkap')->get();
        // Since we want to update data siswa specifically for the ones in this class, we only fetch the ones in this rombel.
        // Wait, siswas above was querying by sekolah_id. We should probably query by rombel.
        $siswas = $rombel->siswas()->orderBy('nama_lengkap')->get();

        return view('walikelas.data_siswa.index', compact('rombel', 'siswas'));
    }

    public function update(Request $request, $id)
    {
        $siswa = Siswa::findOrFail($id);

        $request->validate([
            'nis' => 'nullable|string',
            'nisn' => 'required|string',
            'nama_lengkap' => 'required|string',
            'jenis_kelamin' => 'required|in:L,P',
            'tempat_lahir' => 'nullable|string',
            'tanggal_lahir' => 'nullable|date',
            'nama_ayah' => 'nullable|string',
            'nama_ibu' => 'nullable|string',
        ]);

        $siswa->update($request->only([
            'nis', 'nisn', 'nama_lengkap', 'jenis_kelamin', 
            'tempat_lahir', 'tanggal_lahir', 'nama_ayah', 'nama_ibu'
        ]));

        return redirect()->route('walikelas.data_siswa.index')->with('success', 'Data siswa berhasil diperbarui!');
    }
}
