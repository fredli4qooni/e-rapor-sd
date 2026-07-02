<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PembelajaranController extends Controller
{
    public function index(Request $request)
    {
        $query = \App\Models\Pembelajaran::with(['rombel', 'mapel', 'guru', 'semester']);
        
        if ($request->has('rombel_id') && $request->rombel_id != '') {
            $query->where('rombel_id', $request->rombel_id);
        }

        $pembelajarans = $query->get();
        $rombels = \App\Models\Rombel::orderBy('tingkat')->orderBy('nama_rombel')->get();

        return view('admin.pembelajaran.index', compact('pembelajarans', 'rombels'));
    }

    public function create(Request $request)
    {
        $rombels = \App\Models\Rombel::orderBy('tingkat')->orderBy('nama_rombel')->get();
        $gurus = \App\Models\Guru::orderBy('nama_lengkap')->get();
        $selected_rombel = $request->get('rombel_id');
        
        $parent_mapel_id = $request->get('parent_mapel_id');
        if ($parent_mapel_id) {
            $mapels = \App\Models\MataPelajaran::where('mapel_referensi_id', $parent_mapel_id)->orderBy('nama_mapel')->get();
        } else {
            $mapels = \App\Models\MataPelajaran::orderBy('nama_mapel')->get();
        }

        return view('admin.pembelajaran.create', compact('rombels', 'mapels', 'gurus', 'selected_rombel', 'parent_mapel_id'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'rombel_id' => 'required|exists:rombels,id',
            'mata_pelajaran_id' => 'required|exists:mata_pelajarans,id',
            'guru_id' => 'required|exists:gurus,id',
            'is_aktif' => 'required|boolean',
        ]);

        $sekolah = \App\Models\Sekolah::first();
        $semester = \App\Models\Semester::where('is_aktif', true)->first();

        // Check for duplicates
        $exists = \App\Models\Pembelajaran::where('semester_id', $semester ? $semester->id : 1)
            ->where('rombel_id', $request->rombel_id)
            ->where('mata_pelajaran_id', $request->mata_pelajaran_id)
            ->first();

        if ($exists) {
            return back()->withInput()->withErrors(['mata_pelajaran_id' => 'Mapel ini sudah di-mapping di rombel tersebut untuk semester aktif.']);
        }

        \App\Models\Pembelajaran::create([
            'sekolah_id' => $sekolah ? $sekolah->id : 1,
            'semester_id' => $semester ? $semester->id : 1,
            'rombel_id' => $request->rombel_id,
            'mata_pelajaran_id' => $request->mata_pelajaran_id,
            'guru_id' => $request->guru_id,
            'is_aktif' => $request->is_aktif,
        ]);

        return redirect()->route('admin.pembelajaran.index', ['rombel_id' => $request->rombel_id])
            ->with('status', 'Mapping Pembelajaran berhasil ditambahkan!');
    }

    public function edit(\App\Models\Pembelajaran $pembelajaran)
    {
        $rombels = \App\Models\Rombel::orderBy('tingkat')->orderBy('nama_rombel')->get();
        $mapels = \App\Models\MataPelajaran::orderBy('nama_mapel')->get();
        $gurus = \App\Models\Guru::orderBy('nama_lengkap')->get();

        return view('admin.pembelajaran.edit', compact('pembelajaran', 'rombels', 'mapels', 'gurus'));
    }

    public function update(Request $request, \App\Models\Pembelajaran $pembelajaran)
    {
        $request->validate([
            'rombel_id' => 'required|exists:rombels,id',
            'mata_pelajaran_id' => 'required|exists:mata_pelajarans,id',
            'guru_id' => 'required|exists:gurus,id',
            'is_aktif' => 'required|boolean',
        ]);

        // Check for duplicates if changing rombel or mapel
        if ($pembelajaran->rombel_id != $request->rombel_id || $pembelajaran->mata_pelajaran_id != $request->mata_pelajaran_id) {
            $exists = \App\Models\Pembelajaran::where('semester_id', $pembelajaran->semester_id)
                ->where('rombel_id', $request->rombel_id)
                ->where('mata_pelajaran_id', $request->mata_pelajaran_id)
                ->where('id', '!=', $pembelajaran->id)
                ->first();

            if ($exists) {
                return back()->withInput()->withErrors(['mata_pelajaran_id' => 'Mapel ini sudah di-mapping di rombel tersebut.']);
            }
        }

        $pembelajaran->update([
            'rombel_id' => $request->rombel_id,
            'mata_pelajaran_id' => $request->mata_pelajaran_id,
            'guru_id' => $request->guru_id,
            'is_aktif' => $request->is_aktif,
        ]);

        return redirect()->route('admin.pembelajaran.index', ['rombel_id' => $request->rombel_id])
            ->with('status', 'Mapping Pembelajaran berhasil diperbarui!');
    }

    public function destroy(\App\Models\Pembelajaran $pembelajaran)
    {
        $rombel_id = $pembelajaran->rombel_id;
        $pembelajaran->delete();
        return redirect()->route('admin.pembelajaran.index', ['rombel_id' => $rombel_id])
            ->with('status', 'Mapping Pembelajaran berhasil dihapus!');
    }
}
