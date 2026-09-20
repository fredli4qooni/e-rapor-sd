<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Semester;

class TanggalRaporController extends Controller
{
    public function index()
    {
        $semesters = Semester::orderBy('tahun_ajaran', 'desc')->orderBy('semester', 'desc')->get();
        return view('admin.tanggal_rapor.index', compact('semesters'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'semester' => 'array',
            'semester.*.tanggal_rapor' => 'nullable|date',
            'semester.*.tempat_terbit' => 'nullable|string|max:100',
            'semester.*.tanggal_mulai' => 'nullable|date',
            'semester.*.tanggal_selesai' => 'nullable|date',
            'semester.*.tanggal_mulai_input' => 'nullable|date',
            'semester.*.tanggal_akhir_input' => 'nullable|date',
        ]);

        if ($request->has('semester')) {
            foreach ($request->semester as $id => $data) {
                Semester::where('id', $id)->update([
                    'tanggal_rapor' => $data['tanggal_rapor'] ?? null,
                    'tempat_terbit' => $data['tempat_terbit'] ?? null,
                    'tanggal_mulai' => $data['tanggal_mulai'] ?? null,
                    'tanggal_selesai' => $data['tanggal_selesai'] ?? null,
                    'tanggal_mulai_input' => $data['tanggal_mulai_input'] ?? null,
                    'tanggal_akhir_input' => $data['tanggal_akhir_input'] ?? null,
                ]);
            }
        }

        return redirect()->route('admin.tanggal_rapor.index')->with('status', 'Pengaturan Tanggal Periode & Rapor berhasil disimpan.');
    }
}
