<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\P5Kelompok;
use App\Models\P5Proyek;
use App\Models\P5Dimensi;
use App\Models\P5Elemen;
use App\Models\P5SubElemen;
use App\Models\P5Nilai;
use App\Models\P5Catatan;

class NilaiKokurikulerController extends Controller
{
    // 1. Input Capaian Kokurikuler
    public function index(Request $request)
    {
        $user_id = Auth::id();
        $semester_id = session('semester_id', \App\Models\Semester::where('is_aktif', true)->first()->id ?? 1);

        $kelompoks = P5Kelompok::where('guru_id', $user_id)
            ->where('semester_id', $semester_id)
            ->get();

        $kelompok_id = $request->kelompok_id;
        $proyek_id = $request->proyek_id;
        $dimensi_id = $request->dimensi_id;

        $proyeks = collect();
        $dimensis = collect();
        $sub_elemens = collect();
        $siswas = collect();
        $nilais = collect();

        if ($kelompok_id) {
            $kelompok = P5Kelompok::find($kelompok_id);
            if ($kelompok) {
                $proyeks = $kelompok->proyeks;
            }

            if ($proyek_id) {
                $proyek = P5Proyek::with('targetSubElemens.elemen')->find($proyek_id);
                if ($proyek) {
                    $sub_elemens_target = $proyek->targetSubElemens;
                    
                    // Get distinct dimensi
                    $dimensi_ids = $sub_elemens_target->pluck('elemen.p5_dimensi_id')->filter()->unique();
                    $dimensis = P5Dimensi::whereIn('id', $dimensi_ids)->get();
                    
                    if ($dimensi_id) {
                        // Get all sub-elemens for this dimensi targeted by this proyek
                        $sub_elemens = $sub_elemens_target->filter(function($se) use ($dimensi_id) {
                            return $se->elemen && $se->elemen->p5_dimensi_id == $dimensi_id;
                        });

                        $siswas = $kelompok->siswas()->orderBy('nama_lengkap')->get();

                        $nilais = P5Nilai::whereIn('siswa_id', $siswas->pluck('id'))
                            ->where('p5_proyek_id', $proyek_id)
                            ->whereIn('p5_sub_elemen_id', $sub_elemens->pluck('id'))
                            ->get()
                            ->groupBy('siswa_id');
                    }
                }
            }
        }

        return view('guru.nilai_kokurikuler.index', compact(
            'kelompoks', 'proyeks', 'dimensis', 'sub_elemens', 'siswas', 'nilais',
            'kelompok_id', 'proyek_id', 'dimensi_id'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'proyek_id' => 'required',
            'nilai' => 'array'
        ]);

        $proyek_id = $request->proyek_id;

        if ($request->has('nilai')) {
            foreach ($request->nilai as $siswa_id => $sub_elemens) {
                foreach ($sub_elemens as $sub_elemen_id => $capaian) {
                    if (!empty($capaian)) {
                        P5Nilai::updateOrCreate(
                            [
                                'siswa_id' => $siswa_id,
                                'p5_proyek_id' => $proyek_id,
                                'p5_sub_elemen_id' => $sub_elemen_id
                            ],
                            [
                                'capaian' => $capaian
                            ]
                        );
                    } else {
                        // Delete if empty
                        P5Nilai::where([
                            'siswa_id' => $siswa_id,
                            'p5_proyek_id' => $proyek_id,
                            'p5_sub_elemen_id' => $sub_elemen_id
                        ])->delete();
                    }
                }
            }
        }

        return redirect()->back()->with('success', 'Nilai kokurikuler berhasil disimpan.');
    }

    // 2. Import
    public function importIndex(Request $request)
    {
        $user_id = Auth::id();
        $semester_id = session('semester_id', \App\Models\Semester::where('is_aktif', true)->first()->id ?? 1);

        $kelompoks = P5Kelompok::where('guru_id', $user_id)
            ->where('semester_id', $semester_id)
            ->get();

        $kelompok_id = $request->kelompok_id;
        $proyek_id = $request->proyek_id;

        $proyeks = collect();

        if ($kelompok_id) {
            $kelompok = P5Kelompok::find($kelompok_id);
            if ($kelompok) {
                $proyeks = $kelompok->proyeks;
            }
        }

        return view('guru.nilai_kokurikuler.import', compact(
            'kelompoks', 'proyeks', 'kelompok_id', 'proyek_id'
        ));
    }

    public function downloadFormat(Request $request)
    {
        $kelompok_id = $request->kelompok_id;
        $proyek_id = $request->proyek_id;
        
        $kelompok = P5Kelompok::find($kelompok_id);
        if (!$kelompok || !$proyek_id) {
            return redirect()->back()->with('error', 'Silakan pilih Kelompok dan Kegiatan terlebih dahulu.');
        }

        $nama_file = "FORMAT_NILAI_KOKURIKULER_{$kelompok->nama_kelompok}.xlsx";
        return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\NilaiKokurikulerExport($kelompok_id, $proyek_id), $nama_file);
    }

    public function importStore(Request $request)
    {
        $request->validate([
            'proyek_id' => 'required',
            'file_nilai' => 'required|mimes:xls,xlsx'
        ]);

        try {
            \Maatwebsite\Excel\Facades\Excel::import(new \App\Imports\NilaiKokurikulerImport($request->proyek_id), $request->file('file_nilai'));
            return redirect()->route('guru.nilai_kokurikuler.index', ['kelompok_id' => $request->kelompok_id, 'proyek_id' => $request->proyek_id])->with('success', 'Data nilai kokurikuler berhasil diimport.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal melakukan import: ' . $e->getMessage());
        }
    }

    // 3. Deskripsi
    public function deskripsiIndex(Request $request)
    {
        $user_id = Auth::id();
        $semester_id = session('semester_id', \App\Models\Semester::where('is_aktif', true)->first()->id ?? 1);

        $kelompoks = P5Kelompok::where('guru_id', $user_id)
            ->where('semester_id', $semester_id)
            ->get();

        $kelompok_id = $request->kelompok_id;

        $siswas = collect();
        $catatans = collect();

        if ($kelompok_id) {
            $kelompok = P5Kelompok::find($kelompok_id);
            if ($kelompok) {
                // Untuk Kokurikuler, deskripsi biasanya per kelompok. Tapi P5Catatan disimpan per proyek.
                // Jika tidak ada filter proyek, kita ambil catatan dari proyek pertama kelompok ini, 
                // atau kita simpan/ambil per kelompok? Di UI tidak ada dropdown "Pilih Kegiatan", hanya "Pilih Kelompok".
                // Artinya catatan digabung semua untuk kelompok tersebut, atau proyek pertama.
                $proyek = $kelompok->proyeks->first();
                if ($proyek) {
                    $siswas = $kelompok->siswas()->orderBy('nama_lengkap')->get();
                    $catatans = P5Catatan::whereIn('siswa_id', $siswas->pluck('id'))
                        ->where('p5_proyek_id', $proyek->id)
                        ->get()
                        ->keyBy('siswa_id');
                }
            }
        }

        return view('guru.nilai_kokurikuler.deskripsi', compact(
            'kelompoks', 'siswas', 'catatans', 'kelompok_id'
        ));
    }

    public function generateDeskripsi(Request $request)
    {
        $kelompok_id = $request->kelompok_id;
        $kelompok = P5Kelompok::find($kelompok_id);
        if (!$kelompok) {
            return redirect()->back()->with('error', 'Kelompok tidak ditemukan.');
        }

        $siswas = $kelompok->siswas;
        $proyek = $kelompok->proyeks->first();
        if (!$proyek) {
            return redirect()->back()->with('error', 'Kelompok tidak memiliki kegiatan kokurikuler.');
        }

        foreach ($siswas as $siswa) {
            $text = $this->generateText($siswa->id, $proyek->id, $proyek->nama_proyek);
            
            if (!empty(trim($text))) {
                P5Catatan::updateOrCreate(
                    [
                        'siswa_id' => $siswa->id,
                        'p5_proyek_id' => $proyek->id
                    ],
                    [
                        'catatan' => trim($text)
                    ]
                );
            }
        }

        return redirect()->back()->with('success', 'Deskripsi berhasil digenerate.');
    }

    private function generateText($siswa_id, $proyek_id, $nama_proyek)
    {
        $nilais = P5Nilai::where('siswa_id', $siswa_id)
            ->where('p5_proyek_id', $proyek_id)
            ->with('subElemen.elemen.dimensi')
            ->get();

        if ($nilais->isEmpty()) {
            return '';
        }

        $text = "Pada semester ini, ananda menunjukkan capaian yang baik dalam penguatan profil lulusan, yang ditunjukkan melalui kegiatan kokurikuler $nama_proyek. ";

        // Group by dimensi
        $byDimensi = [];
        foreach ($nilais as $n) {
            if ($n->subElemen && $n->subElemen->elemen && $n->subElemen->elemen->dimensi) {
                $dimensi_nama = $n->subElemen->elemen->dimensi->nama_dimensi;
                $sub_nama = $n->subElemen->nama_sub_elemen;
                $predikat = '';
                if ($n->capaian == '1') $predikat = 'berkembang';
                elseif ($n->capaian == '2') $predikat = 'cakap';
                elseif ($n->capaian == '3') $predikat = 'mahir';
                
                if ($predikat) {
                    $byDimensi[$dimensi_nama][] = "$predikat dalam subdimensi $sub_nama";
                }
            }
        }

        foreach ($byDimensi as $dimensi => $items) {
            $text .= "Pada dimensi $dimensi, ananda " . implode(', dan ', $items) . ". ";
        }

        return trim($text);
    }

    public function storeDeskripsi(Request $request)
    {
        $request->validate([
            'kelompok_id' => 'required',
            'catatan' => 'required|array'
        ]);

        $kelompok = P5Kelompok::find($request->kelompok_id);
        $proyek = $kelompok ? $kelompok->proyeks->first() : null;

        if ($proyek) {
            foreach ($request->catatan as $siswa_id => $text) {
                if (!empty(trim($text))) {
                    P5Catatan::updateOrCreate(
                        [
                            'siswa_id' => $siswa_id,
                            'p5_proyek_id' => $proyek->id
                        ],
                        [
                            'catatan' => trim($text)
                        ]
                    );
                }
            }
            return redirect()->back()->with('success', 'Deskripsi berhasil disimpan.');
        }

        return redirect()->back()->with('error', 'Gagal menyimpan deskripsi.');
    }
}
