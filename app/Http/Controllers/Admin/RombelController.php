<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RombelController extends Controller
{
    public function index()
    {
        $rombels = \App\Models\Rombel::with(['semester', 'waliKelas'])->get();
        return view('admin.rombel.index', compact('rombels'));
    }

    public function create()
    {
        $gurus = \App\Models\Guru::orderBy('nama_lengkap')->get();
        return view('admin.rombel.create', compact('gurus'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_rombel' => 'required|string|max:100',
            'tingkat' => 'required|integer|min:1|max:6',
            'fase' => 'nullable|string|max:5',
            'jenis_rombel' => 'required|in:REGULER,PILIHAN,EKSKUL',
            'wali_kelas_id' => 'nullable|exists:gurus,id',
            'kurikulum' => 'required|string|max:20',
        ]);

        $sekolah = \App\Models\Sekolah::first();
        $semester = \App\Models\Semester::where('is_aktif', true)->first();

        \App\Models\Rombel::create([
            'semester_id' => $semester ? $semester->id : 1,
            'sekolah_id' => $sekolah ? $sekolah->id : 1,
            'nama_rombel' => $request->nama_rombel,
            'tingkat' => $request->tingkat,
            'fase' => $request->fase,
            'jenis_rombel' => $request->jenis_rombel,
            'wali_kelas_id' => $request->wali_kelas_id,
            'kurikulum' => $request->kurikulum,
        ]);

        return redirect()->route('admin.rombel.index')->with('status', 'Data Kelas/Rombel berhasil ditambahkan!');
    }

    public function edit(\App\Models\Rombel $rombel)
    {
        $gurus = \App\Models\Guru::orderBy('nama_lengkap')->get();
        return view('admin.rombel.edit', compact('rombel', 'gurus'));
    }

    public function update(Request $request, \App\Models\Rombel $rombel)
    {
        $request->validate([
            'nama_rombel' => 'required|string|max:100',
            'tingkat' => 'required|integer|min:1|max:6',
            'fase' => 'nullable|string|max:5',
            'jenis_rombel' => 'required|in:REGULER,PILIHAN,EKSKUL',
            'wali_kelas_id' => 'nullable|exists:gurus,id',
            'kurikulum' => 'required|string|max:20',
        ]);

        $rombel->update($request->only(['nama_rombel', 'tingkat', 'fase', 'jenis_rombel', 'wali_kelas_id', 'kurikulum']));

        return redirect()->route('admin.rombel.index')->with('status', 'Data Kelas/Rombel berhasil diperbarui!');
    }

    public function show(\App\Models\Rombel $rombel)
    {
        $rombel->load('siswas');
        $availableSiswas = \App\Models\Siswa::whereNotIn('id', $rombel->siswas->pluck('id'))->orderBy('nama_lengkap')->get();
        return view('admin.rombel.show', compact('rombel', 'availableSiswas'));
    }

    public function destroy(\App\Models\Rombel $rombel)
    {
        $rombel->delete();
        return redirect()->route('admin.rombel.index')->with('status', 'Data Kelas/Rombel berhasil dihapus!');
    }

    public function tambahAnggota(Request $request, \App\Models\Rombel $rombel)
    {
        $request->validate([
            'siswa_ids' => 'required|array',
            'siswa_ids.*' => 'exists:siswas,id'
        ]);

        // Cegah duplikasi siswa di tabel pivot (meski sudah disaring di view)
        $rombel->siswas()->syncWithoutDetaching($request->siswa_ids);
        
        return back()->with('status', 'Anggota rombel berhasil ditambahkan!');
    }

    public function hapusAnggota(\App\Models\Rombel $rombel, \App\Models\Siswa $siswa)
    {
        $rombel->siswas()->detach($siswa->id);
        return back()->with('status', 'Anggota rombel berhasil dihapus!');
    }
}
