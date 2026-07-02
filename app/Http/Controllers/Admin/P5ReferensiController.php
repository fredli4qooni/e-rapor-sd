<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\P5Dimensi;
use App\Models\P5Elemen;
use App\Models\P5SubElemen;

class P5ReferensiController extends Controller
{
    public function index()
    {
        $dimensis = P5Dimensi::with('elemens.subElemens')->get();
        return view('admin.p5.referensi.index', compact('dimensis'));
    }

    // --- DIMENSI ---
    public function storeDimensi(Request $request)
    {
        $request->validate([
            'nama_dimensi' => 'required|string|max:255',
            'deskripsi' => 'nullable|string'
        ]);

        P5Dimensi::create($request->only('nama_dimensi', 'deskripsi'));
        return back()->with('status', 'Dimensi berhasil ditambahkan!');
    }

    public function updateDimensi(Request $request, P5Dimensi $dimensi)
    {
        $request->validate([
            'nama_dimensi' => 'required|string|max:255',
            'deskripsi' => 'nullable|string'
        ]);

        $dimensi->update($request->only('nama_dimensi', 'deskripsi'));
        return back()->with('status', 'Dimensi berhasil diperbarui!');
    }

    public function destroyDimensi(P5Dimensi $dimensi)
    {
        $dimensi->delete();
        return back()->with('status', 'Dimensi berhasil dihapus!');
    }

    // --- ELEMEN ---
    public function storeElemen(Request $request)
    {
        $request->validate([
            'p5_dimensi_id' => 'required|exists:p5_dimensis,id',
            'nama_elemen' => 'required|string|max:255'
        ]);

        P5Elemen::create($request->only('p5_dimensi_id', 'nama_elemen'));
        return back()->with('status', 'Elemen berhasil ditambahkan!');
    }

    public function updateElemen(Request $request, P5Elemen $elemen)
    {
        $request->validate([
            'p5_dimensi_id' => 'required|exists:p5_dimensis,id',
            'nama_elemen' => 'required|string|max:255'
        ]);

        $elemen->update($request->only('p5_dimensi_id', 'nama_elemen'));
        return back()->with('status', 'Elemen berhasil diperbarui!');
    }

    public function destroyElemen(P5Elemen $elemen)
    {
        $elemen->delete();
        return back()->with('status', 'Elemen berhasil dihapus!');
    }

    // --- SUB ELEMEN ---
    public function storeSubElemen(Request $request)
    {
        $request->validate([
            'p5_elemen_id' => 'required|exists:p5_elemens,id',
            'nama_sub_elemen' => 'required|string|max:255',
            'capaian_fase_a' => 'nullable|string',
            'capaian_fase_b' => 'nullable|string',
            'capaian_fase_c' => 'nullable|string'
        ]);

        P5SubElemen::create($request->only('p5_elemen_id', 'nama_sub_elemen', 'capaian_fase_a', 'capaian_fase_b', 'capaian_fase_c'));
        return back()->with('status', 'Sub Elemen berhasil ditambahkan!');
    }

    public function updateSubElemen(Request $request, P5SubElemen $sub_elemen)
    {
        $request->validate([
            'p5_elemen_id' => 'required|exists:p5_elemens,id',
            'nama_sub_elemen' => 'required|string|max:255',
            'capaian_fase_a' => 'nullable|string',
            'capaian_fase_b' => 'nullable|string',
            'capaian_fase_c' => 'nullable|string'
        ]);

        $sub_elemen->update($request->only('p5_elemen_id', 'nama_sub_elemen', 'capaian_fase_a', 'capaian_fase_b', 'capaian_fase_c'));
        return back()->with('status', 'Sub Elemen berhasil diperbarui!');
    }

    public function destroySubElemen(P5SubElemen $sub_elemen)
    {
        $sub_elemen->delete();
        return back()->with('status', 'Sub Elemen berhasil dihapus!');
    }
}
