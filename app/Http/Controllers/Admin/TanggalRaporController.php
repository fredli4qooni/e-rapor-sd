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
        ]);

        if ($request->has('semester')) {
            foreach ($request->semester as $id => $data) {
                Semester::where('id', $id)->update([
                    'tanggal_rapor' => $data['tanggal_rapor'],
                    'tempat_terbit' => $data['tempat_terbit']
                ]);
            }
        }

        return redirect()->route('admin.tanggal_rapor.index')->with('status', 'Tanggal Rapor berhasil disimpan.');
    }
}
