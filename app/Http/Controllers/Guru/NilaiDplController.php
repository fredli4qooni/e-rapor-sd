<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Rombel;
use App\Models\DplDimensi;
use App\Models\Semester;
use App\Models\NilaiDpl;

class NilaiDplController extends Controller
{
    public function index(Request $request)
    {
        $guru = Auth::user()->guru;
        $semesterAktif = Semester::where('is_aktif', true)->first();

        // Check if DPL is active for this semester (>= 2025/2026)
        $tahunParts = explode('/', $semesterAktif->tahun_ajaran);
        $startYear = (int) $tahunParts[0];

        if ($startYear < 2025) {
            return redirect()->route('guru.dashboard')->with('error', 'Menu Nilai DPL hanya aktif mulai tahun ajaran 2025/2026.');
        }

        // Get rombels where this guru is teaching (or wali kelas), specifically K13
        // If this guru teaches subject in K13, or is wali kelas.
        // Assuming Nilai Sikap DPL is usually inputted by Wali Kelas or Guru who taught them.
        // But the user guideline says "Fitur ini aktif... untuk guru yang mengajar pada kelas dengan Kurikulum 2013."
        // We will fetch all rombels taught by this guru that are Kurikulum 2013
        $rombels = Rombel::where('kurikulum', '2013')->where(function($q) use ($guru) {
            $q->where('wali_kelas_id', $guru->id)
              ->orWhereHas('pembelajarans', function($q2) use ($guru) {
                  $q2->where('guru_id', $guru->id);
              });
        })->get()->unique('id');

        $dimensis = DplDimensi::all();

        $rombel_id = $request->query('rombel_id');
        $dimensi_id = $request->query('dimensi_id');

        $rombel = null;
        $dimensi = null;
        $siswas = collect();
        $subdimensis = collect();
        $nilai = collect();

        if ($rombel_id && $dimensi_id) {
            $rombel = Rombel::find($rombel_id);
            $dimensi = DplDimensi::with('subdimensis')->find($dimensi_id);
            $siswas = $rombel->siswas->sortBy('nama_lengkap');
            $subdimensis = $dimensi->subdimensis;

            $nilai = NilaiDpl::where('rombel_id', $rombel_id)
                ->where('semester_id', $semesterAktif->id)
                ->whereIn('dpl_subdimensi_id', $subdimensis->pluck('id'))
                ->get()
                ->groupBy('siswa_id');
        }

        return view('guru.nilai_dpl.index', compact('rombels', 'dimensis', 'rombel_id', 'dimensi_id', 'rombel', 'dimensi', 'siswas', 'subdimensis', 'nilai'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'rombel_id' => 'required',
            'dimensi_id' => 'required',
            'nilai' => 'array'
        ]);

        $semesterAktif = Semester::where('is_aktif', true)->first();

        if ($request->has('nilai')) {
            foreach ($request->nilai as $siswaId => $subdimensis) {
                foreach ($subdimensis as $subdimensiId => $val) {
                    if ($val) {
                        NilaiDpl::updateOrCreate(
                            [
                                'siswa_id' => $siswaId,
                                'rombel_id' => $request->rombel_id,
                                'semester_id' => $semesterAktif->id,
                                'dpl_subdimensi_id' => $subdimensiId,
                            ],
                            [
                                'nilai' => $val
                            ]
                        );
                    } else {
                        // If empty, delete existing record if any
                        NilaiDpl::where([
                            'siswa_id' => $siswaId,
                            'rombel_id' => $request->rombel_id,
                            'semester_id' => $semesterAktif->id,
                            'dpl_subdimensi_id' => $subdimensiId,
                        ])->delete();
                    }
                }
            }
        }

        return redirect()->back()->with('success', 'Nilai DPL berhasil disimpan!');
    }

    public function importIndex(Request $request)
    {
        $guru = Auth::user()->guru;
        $rombels = Rombel::where('kurikulum', '2013')->where(function($q) use ($guru) {
            $q->where('wali_kelas_id', $guru->id)
              ->orWhereHas('pembelajarans', function($q2) use ($guru) {
                  $q2->where('guru_id', $guru->id);
              });
        })->get()->unique('id');

        $dimensis = DplDimensi::all();

        $rombel_id = $request->query('rombel_id');
        $dimensi_id = $request->query('dimensi_id');

        $rombel = null;
        $dimensi = null;

        if ($rombel_id && $dimensi_id) {
            $rombel = Rombel::find($rombel_id);
            $dimensi = DplDimensi::find($dimensi_id);
        }

        return view('guru.nilai_dpl.import', compact('rombels', 'dimensis', 'rombel_id', 'dimensi_id', 'rombel', 'dimensi'));
    }

    public function downloadFormat(Request $request)
    {
        $rombel_id = $request->query('rombel_id');
        $dimensi_id = $request->query('dimensi_id');
        $semesterAktif = Semester::where('is_aktif', true)->first();

        $rombel = Rombel::find($rombel_id);
        $dimensi = DplDimensi::find($dimensi_id);

        $namaFile = 'Format_Nilai_DPL_' . str_replace(' ', '_', $rombel->nama_rombel) . '.xlsx';

        return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\NilaiDplExport($rombel_id, $dimensi_id, $semesterAktif->id), $namaFile);
    }

    public function importStore(Request $request)
    {
        $request->validate([
            'rombel_id' => 'required',
            'dimensi_id' => 'required',
            'file_nilai' => 'required|mimes:xls,xlsx|max:2048'
        ]);

        $semesterAktif = Semester::where('is_aktif', true)->first();

        try {
            \Maatwebsite\Excel\Facades\Excel::import(
                new \App\Imports\NilaiDplImport($request->rombel_id, $request->dimensi_id, $semesterAktif->id),
                $request->file('file_nilai')
            );
            return redirect()->route('guru.nilai_dpl.index', ['rombel_id' => $request->rombel_id, 'dimensi_id' => $request->dimensi_id])->with('success', 'Nilai DPL berhasil diimpor.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal mengimpor file! Pastikan format sesuai dengan template.');
        }
    }
}
