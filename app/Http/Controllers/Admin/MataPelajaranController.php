<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\MataPelajaran;

class MataPelajaranController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $mapels = MataPelajaran::orderBy('nama_mapel')->get();
        return view('admin.mapel.index', compact('mapels'));
    }

    public function create()
    {
        $mapels = MataPelajaran::where('is_lokal', false)->orderBy('nama_mapel')->get();
        return view('admin.mapel.create', compact('mapels'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_mapel' => 'required|string|max:200',
            'nama_singkat' => 'nullable|string|max:20',
            'is_transkrip' => 'required|boolean',
            'is_lokal' => 'required|boolean',
            'mapel_referensi_id' => 'nullable|exists:mata_pelajarans,id',
        ]);

        MataPelajaran::create($request->only(['nama_mapel', 'nama_singkat', 'is_transkrip', 'is_lokal', 'mapel_referensi_id']));

        return redirect()->route('admin.mapel.index')->with('status', 'Data Mata Pelajaran berhasil ditambahkan!');
    }

    public function edit(MataPelajaran $mapel)
    {
        $mapels = MataPelajaran::where('is_lokal', false)->orderBy('nama_mapel')->get();
        return view('admin.mapel.edit', compact('mapel', 'mapels'));
    }

    public function update(Request $request, MataPelajaran $mapel)
    {
        $request->validate([
            'nama_mapel' => 'required|string|max:200',
            'nama_singkat' => 'nullable|string|max:20',
            'is_transkrip' => 'required|boolean',
            'is_lokal' => 'required|boolean',
            'mapel_referensi_id' => 'nullable|exists:mata_pelajarans,id',
        ]);

        $mapel->update($request->only(['nama_mapel', 'nama_singkat', 'is_transkrip', 'is_lokal', 'mapel_referensi_id']));

        return redirect()->route('admin.mapel.index')->with('status', 'Data Mata Pelajaran berhasil diperbarui!');
    }

    public function destroy(MataPelajaran $mapel)
    {
        $mapel->delete();
        return redirect()->route('admin.mapel.index')->with('status', 'Data Mata Pelajaran berhasil dihapus!');
    }
}
