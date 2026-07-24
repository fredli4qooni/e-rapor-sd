<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Semester;
use App\Models\Sekolah;

class SemesterController extends Controller
{
    public function index()
    {
        $semesters = Semester::orderBy('tahun_ajaran', 'desc')->orderBy('semester', 'desc')->get();
        return view('admin.semester.index', compact('semesters'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tahun_ajaran' => 'required|string|max:9|regex:/^[0-9]{4}\/[0-9]{4}$/',
            'semester' => 'required|in:1,2',
            'kurikulum' => 'required|string|max:50',
            'is_aktif' => 'nullable|boolean'
        ], [
            'tahun_ajaran.regex' => 'Format Tahun Ajaran harus seperti 2024/2025'
        ]);

        $sekolah = Sekolah::first();
        
        $semester = new Semester();
        $semester->sekolah_id = $sekolah ? $sekolah->id : 1;
        $semester->tahun_ajaran = $request->tahun_ajaran;
        $semester->semester = $request->semester;
        $semester->kurikulum = $request->kurikulum;
        $semester->is_aktif = $request->has('is_aktif') ? true : false;
        $semester->status_input_nilai = true;
        
        // If this one is set to active, deactivate others
        if ($semester->is_aktif) {
            Semester::where('is_aktif', true)->update(['is_aktif' => false]);
        }
        
        $semester->save();

        return redirect()->route('admin.semester.index')->with('success', 'Data semester berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'tahun_ajaran' => 'required|string|max:9|regex:/^[0-9]{4}\/[0-9]{4}$/',
            'semester' => 'required|in:1,2',
            'kurikulum' => 'required|string|max:50'
        ], [
            'tahun_ajaran.regex' => 'Format Tahun Ajaran harus seperti 2024/2025'
        ]);

        $semester = Semester::findOrFail($id);
        $semester->tahun_ajaran = $request->tahun_ajaran;
        $semester->semester = $request->semester;
        $semester->kurikulum = $request->kurikulum;
        $semester->save();

        return redirect()->route('admin.semester.index')->with('success', 'Data semester berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $semester = Semester::findOrFail($id);
        
        if ($semester->is_aktif) {
            return redirect()->route('admin.semester.index')->with('error', 'Tidak dapat menghapus semester yang sedang aktif.');
        }

        $semester->delete();
        return redirect()->route('admin.semester.index')->with('success', 'Data semester berhasil dihapus.');
    }

    public function setAktif($id)
    {
        $semester = Semester::findOrFail($id);

        if (!$semester->is_aktif) {
            // Nonaktifkan semua semester lain
            \App\Models\Semester::where('id', '!=', $semester->id)->update(['is_aktif' => false]);
            
            $semester->is_aktif = true;
            $semester->save();

            return redirect()->route('admin.semester.index')->with('success', 'Semester berhasil diaktifkan.');
        }

        return redirect()->route('admin.semester.index')->with('info', 'Semester tersebut memang sudah aktif.');
    }
}
