<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\KelompokMapel;

class KelompokMapelController extends Controller
{
    public function index()
    {
        $kelompoks = KelompokMapel::orderBy('nama_kelompok')->get();
        return view('admin.kelompok_mapel.index', compact('kelompoks'));
    }

    public function create()
    {
        return view('admin.kelompok_mapel.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_kelompok' => 'required|string|max:255',
            'jenis_kelompok' => 'nullable|string|max:100',
        ]);

        KelompokMapel::create($request->all());

        return redirect()->route('admin.kelompok_mapel.index')
            ->with('status', 'Kelompok Mata Pelajaran berhasil ditambahkan.');
    }

    public function edit(KelompokMapel $kelompokMapel)
    {
        return view('admin.kelompok_mapel.edit', compact('kelompokMapel'));
    }

    public function update(Request $request, KelompokMapel $kelompokMapel)
    {
        $request->validate([
            'nama_kelompok' => 'required|string|max:255',
            'jenis_kelompok' => 'nullable|string|max:100',
        ]);

        $kelompokMapel->update($request->all());

        return redirect()->route('admin.kelompok_mapel.index')
            ->with('status', 'Kelompok Mata Pelajaran berhasil diperbarui.');
    }

    public function destroy(KelompokMapel $kelompokMapel)
    {
        // Set null for mapels that use this kelompok
        \App\Models\MataPelajaran::where('kelompok_mapel_id', $kelompokMapel->id)->update(['kelompok_mapel_id' => null]);
        
        $kelompokMapel->delete();

        return redirect()->route('admin.kelompok_mapel.index')
            ->with('status', 'Kelompok Mata Pelajaran berhasil dihapus.');
    }
}
