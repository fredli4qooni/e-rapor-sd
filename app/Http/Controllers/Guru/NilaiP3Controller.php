<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Rombel;
use App\Models\P5Dimensi;
use App\Models\Semester;
use App\Models\NilaiP3;

class NilaiP3Controller extends Controller
{
    public function index(Request $request)
    {
        $guru = Auth::user()->guru;
        $semesterAktif = Semester::where('is_aktif', true)->first();

        // Check if P3 is active for this semester (< 2025/2026)
        $tahunParts = explode('/', $semesterAktif->tahun_ajaran);
        $startYear = (int) $tahunParts[0];

        if ($startYear >= 2025) {
            return redirect()->route('guru.dashboard')->with('error', 'Menu Nilai P3 hanya aktif pada tahun ajaran sebelum 2025/2026.');
        }

        $rombels = Rombel::where('kurikulum', '2013')->where(function($q) use ($guru) {
            $q->where('wali_kelas_id', $guru->id)
              ->orWhereHas('pembelajarans', function($q2) use ($guru) {
                  $q2->where('guru_id', $guru->id);
              });
        })->get()->unique('id');

        $dimensis = P5Dimensi::all();

        $rombel_id = $request->query('rombel_id');
        $dimensi_id = $request->query('dimensi_id');

        $rombel = null;
        $dimensi = null;
        $siswas = collect();
        $elemens = collect();
        $nilai = collect();

        if ($rombel_id && $dimensi_id) {
            $rombel = Rombel::find($rombel_id);
            $dimensi = P5Dimensi::with('elemens.subElemens')->find($dimensi_id);
            $siswas = $rombel->siswas->sortBy('nama_lengkap');
            $elemens = $dimensi->elemens;

            $subElemenIds = [];
            foreach ($elemens as $elemen) {
                foreach ($elemen->subElemens as $sub) {
                    $subElemenIds[] = $sub->id;
                }
            }

            $nilai = NilaiP3::where('rombel_id', $rombel_id)
                ->where('semester_id', $semesterAktif->id)
                ->whereIn('p5_sub_elemen_id', $subElemenIds)
                ->get()
                ->groupBy('siswa_id');
        }

        return view('guru.nilai_p3.index', compact('rombels', 'dimensis', 'rombel_id', 'dimensi_id', 'rombel', 'dimensi', 'siswas', 'elemens', 'nilai'));
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
            foreach ($request->nilai as $siswaId => $subElemens) {
                foreach ($subElemens as $subId => $val) {
                    if ($val) {
                        NilaiP3::updateOrCreate(
                            [
                                'siswa_id' => $siswaId,
                                'rombel_id' => $request->rombel_id,
                                'semester_id' => $semesterAktif->id,
                                'p5_sub_elemen_id' => $subId,
                            ],
                            [
                                'nilai' => $val
                            ]
                        );
                    } else {
                        NilaiP3::where([
                            'siswa_id' => $siswaId,
                            'rombel_id' => $request->rombel_id,
                            'semester_id' => $semesterAktif->id,
                            'p5_sub_elemen_id' => $subId,
                        ])->delete();
                    }
                }
            }
        }

        return redirect()->back()->with('success', 'Nilai P3 berhasil disimpan!');
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

        $dimensis = P5Dimensi::all();

        $rombel_id = $request->query('rombel_id');
        $dimensi_id = $request->query('dimensi_id');

        $rombel = null;
        $dimensi = null;

        if ($rombel_id && $dimensi_id) {
            $rombel = Rombel::find($rombel_id);
            $dimensi = P5Dimensi::find($dimensi_id);
        }

        return view('guru.nilai_p3.import', compact('rombels', 'dimensis', 'rombel_id', 'dimensi_id', 'rombel', 'dimensi'));
    }

    public function downloadFormat(Request $request)
    {
        $rombel_id = $request->query('rombel_id');
        $dimensi_id = $request->query('dimensi_id');
        $semesterAktif = Semester::where('is_aktif', true)->first();

        $rombel = Rombel::find($rombel_id);
        $dimensi = P5Dimensi::find($dimensi_id);

        $namaFile = 'Format_Nilai_P3_' . str_replace(' ', '_', $rombel->nama_rombel) . '.xlsx';

        return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\NilaiP3Export($rombel_id, $dimensi_id, $semesterAktif->id), $namaFile);
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
                new \App\Imports\NilaiP3Import($request->rombel_id, $request->dimensi_id, $semesterAktif->id),
                $request->file('file_nilai')
            );
            return redirect()->route('guru.nilai_p3.index', ['rombel_id' => $request->rombel_id, 'dimensi_id' => $request->dimensi_id])->with('success', 'Nilai P3 berhasil diimpor.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal mengimpor file! Pastikan format sesuai dengan template.');
        }
    }
}
