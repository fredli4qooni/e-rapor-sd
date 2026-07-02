<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class P5TemaController extends Controller
{
    public function index()
    {
        $temas = \App\Models\P5Tema::latest()->get();
        return view('admin.p5.tema.index', compact('temas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_tema' => 'required|string|max:255',
            'deskripsi' => 'nullable|string'
        ]);

        \App\Models\P5Tema::create([
            'nama_tema' => $request->nama_tema,
            'deskripsi' => $request->deskripsi,
            'is_aktif' => true
        ]);

        return redirect()->route('admin.p5.tema.index')->with('status', 'Tema Kokurikuler berhasil ditambahkan!');
    }

    public function update(Request $request, \App\Models\P5Tema $tema)
    {
        $request->validate([
            'nama_tema' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'is_aktif' => 'required|boolean'
        ]);

        $tema->update([
            'nama_tema' => $request->nama_tema,
            'deskripsi' => $request->deskripsi,
            'is_aktif' => $request->is_aktif
        ]);

        return redirect()->route('admin.p5.tema.index')->with('status', 'Tema Kokurikuler berhasil diperbarui!');
    }
}
