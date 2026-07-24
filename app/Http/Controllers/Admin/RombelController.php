<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RombelController extends Controller
{
    public function index()
    {
        $active_semester_id = session('semester_id', \App\Models\Semester::where('is_aktif', true)->first()->id ?? 1);
        $rombels = \App\Models\Rombel::with(['semester', 'waliKelas'])
                    ->where('semester_id', $active_semester_id)
                    ->get();
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
        $active_semester_id = session('semester_id', \App\Models\Semester::where('is_aktif', true)->first()->id ?? 1);

        \App\Models\Rombel::create([
            'semester_id' => $active_semester_id,
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
        
        if ($rombel->jenis_rombel === 'REGULER') {
            // Ambil ID siswa yang sudah ada di rombel REGULER pada semester ini
            $siswaSudahAda = \Illuminate\Support\Facades\DB::table('anggota_rombels')
                ->join('rombels', 'anggota_rombels.rombel_id', '=', 'rombels.id')
                ->where('rombels.semester_id', $rombel->semester_id)
                ->where('rombels.jenis_rombel', 'REGULER')
                ->pluck('anggota_rombels.siswa_id')
                ->toArray();
                
            $availableSiswas = \App\Models\Siswa::whereNotIn('id', $siswaSudahAda)->orderBy('nama_lengkap')->get();
        } else {
            // Untuk Ekskul/Pilihan, hanya kecualikan siswa yang sudah ada di rombel INI
            $availableSiswas = \App\Models\Siswa::whereNotIn('id', $rombel->siswas->pluck('id'))->orderBy('nama_lengkap')->get();
        }

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

        if ($rombel->jenis_rombel === 'REGULER') {
            // Pastikan siswa dilepas dari rombel reguler lain pada semester yang sama jika berpindah
            $rombelRegulerLain = \App\Models\Rombel::where('semester_id', $rombel->semester_id)
                ->where('jenis_rombel', 'REGULER')
                ->pluck('id');
                
            if ($rombelRegulerLain->count() > 0) {
                \Illuminate\Support\Facades\DB::table('anggota_rombels')
                    ->whereIn('rombel_id', $rombelRegulerLain)
                    ->whereIn('siswa_id', $request->siswa_ids)
                    ->delete();
            }
        }

        // Cegah duplikasi siswa di tabel pivot
        $rombel->siswas()->syncWithoutDetaching($request->siswa_ids);
        
        return back()->with('status', 'Anggota rombel berhasil ditambahkan!');
    }

    public function hapusAnggota(\App\Models\Rombel $rombel, \App\Models\Siswa $siswa)
    {
        $rombel->siswas()->detach($siswa->id);
        return back()->with('status', 'Anggota rombel berhasil dihapus!');
    }
}
