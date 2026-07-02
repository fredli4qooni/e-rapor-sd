<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class P5KelompokController extends Controller
{
    public function index(Request $request)
    {
        $fase = $request->get('fase');
        $query = \App\Models\P5Kelompok::with(['koordinator']);

        if ($fase) {
            $query->where('fase', $fase);
        }

        $kelompoks = $query->latest()->get();

        return view('admin.p5.kelompok.index', compact('kelompoks', 'fase'));
    }

    public function create()
    {
        $gurus = \App\Models\User::where('role', 'guru')->get();
        return view('admin.p5.kelompok.create', compact('gurus'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_kelompok' => 'required|string|max:255',
            'tingkat_pendidikan' => 'nullable|string|max:50',
            'fase' => 'required|in:A,B,C',
            'guru_id' => 'required|exists:users,id'
        ]);

        $sekolah = \App\Models\Sekolah::first();
        $semester = \App\Models\Semester::where('is_aktif', true)->first();

        \App\Models\P5Kelompok::create([
            'sekolah_id' => $sekolah ? $sekolah->id : 1,
            'semester_id' => $semester ? $semester->id : 1,
            'nama_kelompok' => $request->nama_kelompok,
            'tingkat_pendidikan' => $request->tingkat_pendidikan,
            'fase' => $request->fase,
            'guru_id' => $request->guru_id
        ]);

        return redirect()->route('admin.p5.kelompok.index')->with('status', 'Kelompok Kokurikuler berhasil ditambahkan!');
    }

    public function edit(\App\Models\P5Kelompok $kelompok)
    {
        $gurus = \App\Models\User::where('role', 'guru')->get();
        return view('admin.p5.kelompok.edit', compact('kelompok', 'gurus'));
    }

    public function update(Request $request, \App\Models\P5Kelompok $kelompok)
    {
        $request->validate([
            'nama_kelompok' => 'required|string|max:255',
            'tingkat_pendidikan' => 'nullable|string|max:50',
            'fase' => 'required|in:A,B,C',
            'guru_id' => 'required|exists:users,id'
        ]);

        $kelompok->update([
            'nama_kelompok' => $request->nama_kelompok,
            'tingkat_pendidikan' => $request->tingkat_pendidikan,
            'fase' => $request->fase,
            'guru_id' => $request->guru_id
        ]);

        return redirect()->route('admin.p5.kelompok.index')->with('status', 'Kelompok Kokurikuler berhasil diperbarui!');
    }

    public function show(\App\Models\P5Kelompok $kelompok)
    {
        $kelompok->load(['siswas', 'proyeks']);
        $semuaSiswa = \App\Models\Siswa::all(); // Nanti filter berdasarkan tingkat atau rombel jika perlu
        $semuaKegiatan = \App\Models\P5Proyek::where('fase', $kelompok->fase)->get();
        $rombels = \App\Models\Rombel::all();

        return view('admin.p5.kelompok.show', compact('kelompok', 'semuaSiswa', 'semuaKegiatan', 'rombels'));
    }

    public function destroy(\App\Models\P5Kelompok $kelompok)
    {
        $kelompok->delete();
        return redirect()->route('admin.p5.kelompok.index')->with('status', 'Kelompok Kokurikuler berhasil dihapus!');
    }

    public function tambahAnggota(Request $request, \App\Models\P5Kelompok $kelompok)
    {
        $request->validate([
            'siswa_id' => 'nullable|exists:siswas,id',
            'rombel_id' => 'nullable|exists:rombels,id'
        ]);

        if ($request->filled('rombel_id')) {
            $rombel = \App\Models\Rombel::with('siswas')->find($request->rombel_id);
            if ($rombel && $rombel->siswas) {
                $siswaIds = $rombel->siswas->pluck('id')->toArray();
                $kelompok->siswas()->syncWithoutDetaching($siswaIds);
                return back()->with('status', 'Semua siswa di rombel berhasil ditambahkan ke kelompok!');
            }
        } elseif ($request->filled('siswa_id')) {
            $kelompok->siswas()->syncWithoutDetaching([$request->siswa_id]);
            return back()->with('status', 'Siswa berhasil ditambahkan ke kelompok!');
        }

        return back()->withErrors('Pilih siswa atau rombel yang akan ditambahkan.');
    }

    public function hapusAnggota(\App\Models\P5Kelompok $kelompok, \App\Models\Siswa $siswa)
    {
        $kelompok->siswas()->detach($siswa->id);
        return back()->with('status', 'Siswa berhasil dihapus dari kelompok!');
    }

    public function tambahKegiatan(Request $request, \App\Models\P5Kelompok $kelompok)
    {
        $request->validate([
            'p5_proyek_id' => 'required|exists:p5_proyeks,id'
        ]);

        $kelompok->proyeks()->syncWithoutDetaching([$request->p5_proyek_id]);
        return back()->with('status', 'Kegiatan berhasil ditambahkan ke kelompok!');
    }

    public function hapusKegiatan(\App\Models\P5Kelompok $kelompok, \App\Models\P5Proyek $kegiatan)
    {
        $kelompok->proyeks()->detach($kegiatan->id);
        return back()->with('status', 'Kegiatan berhasil dihapus dari kelompok!');
    }
}
