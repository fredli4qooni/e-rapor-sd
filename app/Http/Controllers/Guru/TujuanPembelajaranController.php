<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TujuanPembelajaran;
use App\Models\Pembelajaran;
use App\Models\Semester;
use Illuminate\Support\Facades\Auth;

class TujuanPembelajaranController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $guru = Auth::user()->guru;
        $semesterAktif = Semester::where('is_aktif', true)->first();

        // Get Mapel and Tingkat taught by this Guru
        $pembelajarans = Pembelajaran::where('guru_id', $guru->id)
            ->where('semester_id', $semesterAktif->id)
            ->with(['mapel', 'rombel'])
            ->get();

        $mapelDiampu = $pembelajarans->map(function ($p) {
            return [
                'id' => $p->mata_pelajaran_id,
                'nama' => $p->mapel->nama_mapel,
            ];
        })->unique('id')->values();

        $tingkatDiampu = $pembelajarans->pluck('rombel.tingkat')->unique()->sort()->values();

        $mata_pelajaran_id = $request->query('mata_pelajaran_id');
        $isEdit = $request->query('edit') == 1;

        $tps = collect();
        if ($mata_pelajaran_id) {
            $tingkatsForThisMapel = $pembelajarans->where('mata_pelajaran_id', $mata_pelajaran_id)->pluck('rombel.tingkat')->unique();
            
            $tps = TujuanPembelajaran::with(['mataPelajaran', 'semester'])
                ->where('mata_pelajaran_id', $mata_pelajaran_id)
                ->where('semester_id', $semesterAktif->id)
                ->whereIn('tingkat', $tingkatsForThisMapel)
                ->orderBy('tingkat')
                ->orderBy('id')
                ->get();
        }

        return view('guru.tp.index', compact('mapelDiampu', 'tps', 'mata_pelajaran_id', 'isEdit', 'tingkatDiampu', 'semesterAktif'));
    }

    public function bulkStore(Request $request)
    {
        $request->validate([
            'mata_pelajaran_id' => 'required|exists:mata_pelajarans,id',
            'tp' => 'array',
            'tp.*.id' => 'nullable|exists:tujuan_pembelajarans,id',
            'tp.*.tingkat' => 'nullable|integer',
            'tp.*.deskripsi' => 'nullable|string',
            'tp.*.is_aktif' => 'nullable|boolean',
        ]);

        $semesterAktif = Semester::where('is_aktif', true)->first();
        $mapel_id = $request->mata_pelajaran_id;

        if ($request->has('tp')) {
            foreach ($request->tp as $data) {
                // If deskripsi is empty but has an ID, don't delete it here, deletion is separate via destroy.
                if (empty($data['deskripsi'])) {
                    continue;
                }

                if (!empty($data['id'])) {
                    // Update existing
                    $tp = TujuanPembelajaran::find($data['id']);
                    if ($tp) {
                        $tp->update([
                            'deskripsi' => $data['deskripsi'],
                            'is_aktif' => $data['is_aktif'] ?? true,
                        ]);
                    }
                } else {
                    // Create new
                    if (!empty($data['tingkat'])) {
                        TujuanPembelajaran::create([
                            'mata_pelajaran_id' => $mapel_id,
                            'tingkat' => $data['tingkat'],
                            'semester_id' => $semesterAktif->id,
                            'deskripsi' => $data['deskripsi'],
                            'is_aktif' => true,
                        ]);
                    }
                }
            }
        }

        return redirect()->route('guru.tujuan-pembelajaran.index', ['mata_pelajaran_id' => $mapel_id])->with('success', 'Data Tujuan Pembelajaran berhasil disimpan.');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $guru = Auth::user()->guru;
        $semesterAktif = Semester::where('is_aktif', true)->first();

        // Get unique Mapel and Tingkat taught by this Guru
        $pembelajarans = Pembelajaran::where('guru_id', $guru->id)
            ->where('semester_id', $semesterAktif->id)
            ->with(['mapel', 'rombel'])
            ->get();

        $mapelDiampu = $pembelajarans->map(function ($p) {
            return [
                'mata_pelajaran_id' => $p->mata_pelajaran_id,
                'nama_mapel' => $p->mapel->nama_mapel,
                'tingkat' => $p->rombel->tingkat,
            ];
        })->unique(function ($item) {
            return $item['mata_pelajaran_id'].$item['tingkat'];
        })->values();

        return view('guru.tp.create', compact('mapelDiampu'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'mapel_tingkat' => 'required',
            'deskripsi' => 'required|array|min:1|max:10',
            'deskripsi.*' => 'required|string|max:500'
        ]);

        $parts = explode('-', $request->mapel_tingkat);
        if (count($parts) != 2) {
            return back()->with('error', 'Format Mapel dan Tingkat tidak valid.');
        }

        $mata_pelajaran_id = $parts[0];
        $tingkat = $parts[1];
        $semesterAktif = Semester::where('is_aktif', true)->first();

        $insertedCount = 0;
        foreach ($request->deskripsi as $desc) {
            if (!empty(trim($desc))) {
                TujuanPembelajaran::create([
                    'mata_pelajaran_id' => $mata_pelajaran_id,
                    'tingkat' => $tingkat,
                    'semester_id' => $semesterAktif->id,
                    'deskripsi' => trim($desc),
                    'is_aktif' => true,
                ]);
                $insertedCount++;
            }
        }

        return redirect()->route('guru.tujuan-pembelajaran.index')->with('success', "$insertedCount Tujuan Pembelajaran berhasil ditambahkan.");
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $tp = TujuanPembelajaran::findOrFail($id);
        // ...
        // Note: For simplicity, edit might just be a single form or inline. Let's just do single edit.
        return view('guru.tp.edit', compact('tp'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'deskripsi' => 'required|string|max:500'
        ]);

        $tp = TujuanPembelajaran::findOrFail($id);
        $tp->update([
            'deskripsi' => trim($request->deskripsi)
        ]);

        return redirect()->route('guru.tujuan-pembelajaran.index')->with('success', 'Tujuan Pembelajaran berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $tp = TujuanPembelajaran::findOrFail($id);
        
        // Cek apakah TP sudah dipakai di nilai rapor
        $terpakai = \App\Models\NilaiRapor::whereJsonContains('tp_tertinggi', (string)$id)
            ->orWhereJsonContains('tp_tertinggi', $id)
            ->orWhereJsonContains('tp_terendah', (string)$id)
            ->orWhereJsonContains('tp_terendah', $id)
            ->exists();

        if ($terpakai) {
            return back()->with('error', 'Gagal menghapus! Tujuan Pembelajaran ini sudah digunakan pada penilaian capaian rapor siswa.');
        }

        $tp->delete();

        return back()->with('success', 'Tujuan Pembelajaran berhasil dihapus.');
    }

    public function importIndex()
    {
        $guru = Auth::user()->guru;
        $semesterAktif = Semester::where('is_aktif', true)->first();

        // Get unique Mapel taught by this Guru
        $pembelajarans = Pembelajaran::where('guru_id', $guru->id)
            ->where('semester_id', $semesterAktif->id)
            ->with(['mapel'])
            ->get();

        $mapelDiampu = $pembelajarans->map(function ($p) {
            return [
                'mata_pelajaran_id' => $p->mata_pelajaran_id,
                'nama_mapel' => $p->mapel->nama_mapel,
            ];
        })->unique('mata_pelajaran_id')->values();

        return view('guru.tp.import', compact('mapelDiampu'));
    }

    public function downloadFormat()
    {
        return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\TujuanPembelajaranExport, 'Format_Import_Tujuan_Pembelajaran.xlsx');
    }

    public function importStore(Request $request)
    {
        $request->validate([
            'mata_pelajaran_id' => 'required',
            'file_tp' => 'required|mimes:xls,xlsx|max:2048'
        ]);

        try {
            \Maatwebsite\Excel\Facades\Excel::import(
                new \App\Imports\TujuanPembelajaranImport($request->mata_pelajaran_id),
                $request->file('file_tp')
            );
            return redirect()->route('guru.tujuan-pembelajaran.index')->with('success', 'Tujuan Pembelajaran berhasil diimpor.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal mengimpor file! Pastikan format sesuai dengan template.');
        }
    }
}
