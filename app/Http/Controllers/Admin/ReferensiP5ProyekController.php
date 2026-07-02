<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ReferensiP5ProyekController extends Controller
{
    public function index(Request $request)
    {
        $fase = $request->get('fase');
        $p5_tema_id = $request->get('p5_tema_id');

        $query = \App\Models\P5Proyek::with(['tema', 'targetSubElemens']);
        
        if ($fase) {
            $query->where('fase', $fase);
        }
        if ($p5_tema_id) {
            $query->where('p5_tema_id', $p5_tema_id);
        }

        $proyeks = $query->orderBy('no_urut', 'asc')->get();
        $temas = \App\Models\P5Tema::where('is_aktif', true)->get();

        return view('admin.referensi_p5.proyek.index', compact('proyeks', 'fase', 'p5_tema_id', 'temas'));
    }

    public function create()
    {
        $temas = \App\Models\P5Tema::where('is_aktif', true)->get();
        $dimensis = \App\Models\P5Dimensi::with('elemens.subElemens')->get();

        return view('admin.referensi_p5.proyek.create', compact('temas', 'dimensis'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'no_urut' => 'required|integer|min:1',
            'p5_tema_id' => 'required|exists:p5_temas,id',
            'nama_proyek' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'fase' => 'required|in:A,B,C',
            'sub_elemens' => 'required|array',
            'sub_elemens.*' => 'exists:p5_sub_elemens,id'
        ]);

        $sekolah = \App\Models\Sekolah::first();
        $semester = \App\Models\Semester::where('is_aktif', true)->first();

        $proyek = \App\Models\P5Proyek::create([
            'sekolah_id' => $sekolah ? $sekolah->id : 1,
            'semester_id' => $semester ? $semester->id : 1,
            'no_urut' => $request->no_urut,
            'p5_tema_id' => $request->p5_tema_id,
            'nama_proyek' => $request->nama_proyek,
            'deskripsi' => $request->deskripsi,
            'fase' => $request->fase,
        ]);

        $proyek->targetSubElemens()->attach($request->sub_elemens);

        return redirect()->route('admin.referensi_p5.proyek.index', ['fase' => $request->fase, 'p5_tema_id' => $request->p5_tema_id])->with('status', 'Projek P5 berhasil ditambahkan!');
    }

    public function edit(\App\Models\P5Proyek $proyek)
    {
        $temas = \App\Models\P5Tema::where('is_aktif', true)->get();
        $dimensis = \App\Models\P5Dimensi::with('elemens.subElemens')->get();
        $selectedSubElemens = $proyek->targetSubElemens->pluck('id')->toArray();

        return view('admin.referensi_p5.proyek.edit', compact('proyek', 'temas', 'dimensis', 'selectedSubElemens'));
    }

    public function update(Request $request, \App\Models\P5Proyek $proyek)
    {
        $request->validate([
            'no_urut' => 'required|integer|min:1',
            'p5_tema_id' => 'required|exists:p5_temas,id',
            'nama_proyek' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'fase' => 'required|in:A,B,C',
            'sub_elemens' => 'required|array',
            'sub_elemens.*' => 'exists:p5_sub_elemens,id'
        ]);

        $proyek->update([
            'no_urut' => $request->no_urut,
            'p5_tema_id' => $request->p5_tema_id,
            'nama_proyek' => $request->nama_proyek,
            'deskripsi' => $request->deskripsi,
            'fase' => $request->fase,
        ]);

        $proyek->targetSubElemens()->sync($request->sub_elemens);

        return redirect()->route('admin.referensi_p5.proyek.index', ['fase' => $request->fase, 'p5_tema_id' => $request->p5_tema_id])->with('status', 'Projek P5 berhasil diperbarui!');
    }

    public function destroy(\App\Models\P5Proyek $proyek)
    {
        $fase = $proyek->fase;
        $proyek->delete();
        return redirect()->route('admin.referensi_p5.proyek.index', ['fase' => $fase])->with('status', 'Proyek P5 berhasil dihapus!');
    }
}
