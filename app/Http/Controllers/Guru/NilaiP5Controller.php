<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\P5Kelompok;
use App\Models\P5Proyek;
use App\Models\P5Dimensi;
use App\Models\P5Elemen;
use App\Models\P5SubElemen;
use App\Models\P5Nilai;
use App\Models\P5Catatan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NilaiP5Controller extends Controller
{
    public function inputCapaian(Request $request)
    {
        $user_id = Auth::id();
        $semester_id = \App\Models\Semester::where('is_aktif', true)->first()->id ?? 1;

        $kelompoks = P5Kelompok::where('guru_id', $user_id)
            ->where('semester_id', $semester_id)
            ->get();

        $kelompok_id = $request->kelompok_id;
        $proyek_id = $request->proyek_id;
        $dimensi_id = $request->dimensi_id;
        $elemen_id = $request->elemen_id;
        $sub_elemen_id = $request->sub_elemen_id;

        $proyeks = collect();
        $dimensis = collect();
        $elemens = collect();
        $sub_elemens = collect();
        $siswas = collect();
        $nilais = collect();

        if ($kelompok_id) {
            $kelompok = P5Kelompok::find($kelompok_id);
            if ($kelompok) {
                $proyeks = $kelompok->proyeks;
            }
        }

        if ($proyek_id) {
            $proyek = P5Proyek::with('targetSubElemens.elemen')->find($proyek_id);
            if ($proyek) {
                // Get sub-elements for this project
                $sub_elemens_target = $proyek->targetSubElemens;
                
                // Get distinct dimensi
                $dimensi_ids = $sub_elemens_target->pluck('elemen.p5_dimensi_id')->filter()->unique();
                $dimensis = P5Dimensi::whereIn('id', $dimensi_ids)->get();
                
                if ($dimensi_id) {
                    $elemen_ids = $sub_elemens_target->where('elemen.p5_dimensi_id', $dimensi_id)->pluck('p5_elemen_id')->unique();
                    $elemens = P5Elemen::whereIn('id', $elemen_ids)->get();
                    
                    if ($elemen_id) {
                        $sub_elemens = $sub_elemens_target->where('p5_elemen_id', $elemen_id);
                        
                        if ($sub_elemen_id) {
                            $kelompok = P5Kelompok::find($kelompok_id);
                            $siswas = $kelompok->siswas()->orderBy('nama_lengkap')->get();
                            
                            $nilais = P5Nilai::whereIn('siswa_id', $siswas->pluck('id'))
                                ->where('p5_proyek_id', $proyek_id)
                                ->where('p5_sub_elemen_id', $sub_elemen_id)
                                ->get()
                                ->keyBy('siswa_id');
                        }
                    }
                }
            }
        }

        return view('guru.nilai_p5.input_capaian', compact(
            'kelompoks', 'proyeks', 'dimensis', 'elemens', 'sub_elemens', 
            'siswas', 'nilais', 
            'kelompok_id', 'proyek_id', 'dimensi_id', 'elemen_id', 'sub_elemen_id'
        ));
    }

    public function storeCapaian(Request $request)
    {
        $request->validate([
            'proyek_id' => 'required',
            'sub_elemen_id' => 'required',
            'nilai' => 'required|array'
        ]);

        $proyek_id = $request->proyek_id;
        $sub_elemen_id = $request->sub_elemen_id;

        foreach ($request->nilai as $siswa_id => $capaian) {
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
            }
        }

        return redirect()->back()->with('success', 'Nilai capaian P5 berhasil disimpan.');
    }

    public function importCapaian(Request $request)
    {
        $user_id = Auth::id();
        $semester_id = \App\Models\Semester::where('is_aktif', true)->first()->id ?? 1;

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

        return view('guru.nilai_p5.import_capaian', compact(
            'kelompoks', 'proyeks', 'kelompok_id', 'proyek_id'
        ));
    }

    public function downloadFormatImport(Request $request)
    {
        $kelompok_id = $request->kelompok_id;
        $proyek_id = $request->proyek_id;
        
        $kelompok = P5Kelompok::find($kelompok_id);
        if (!$kelompok || !$proyek_id) {
            return redirect()->back()->with('error', 'Silakan pilih Kelompok dan Projek terlebih dahulu.');
        }

        $nama_file = "FORMAT_NILAI_P5_{$kelompok->nama_kelompok}.xlsx";
        return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\FormatImportP5Export($kelompok_id, $proyek_id), $nama_file);
    }

    public function storeImportCapaian(Request $request)
    {
        $request->validate([
            'proyek_id' => 'required',
            'file_nilai' => 'required|mimes:xls,xlsx'
        ]);

        try {
            \Maatwebsite\Excel\Facades\Excel::import(new \App\Imports\CapaianP5Import($request->proyek_id), $request->file('file_nilai'));
            return redirect()->route('guru.nilai_p5.input_capaian', ['kelompok_id' => $request->kelompok_id, 'proyek_id' => $request->proyek_id])->with('success', 'Data capaian P5 berhasil diimport.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal melakukan import: ' . $e->getMessage());
        }
    }

    public function inputCatatan(Request $request)
    {
        $user_id = Auth::id();
        $semester_id = \App\Models\Semester::where('is_aktif', true)->first()->id ?? 1;

        $kelompoks = P5Kelompok::where('guru_id', $user_id)
            ->where('semester_id', $semester_id)
            ->get();

        $kelompok_id = $request->kelompok_id;
        $proyek_id = $request->proyek_id;

        $proyeks = collect();
        $siswas = collect();
        $catatans = collect();
        $auto_catatans = [];

        if ($kelompok_id) {
            $kelompok = P5Kelompok::find($kelompok_id);
            if ($kelompok) {
                $proyeks = $kelompok->proyeks;
            }

            if ($proyek_id) {
                $siswas = $kelompok->siswas()->orderBy('nama_lengkap')->get();
                $catatans = P5Catatan::whereIn('siswa_id', $siswas->pluck('id'))
                    ->where('p5_proyek_id', $proyek_id)
                    ->get()
                    ->keyBy('siswa_id');
                
                // Auto generate
                foreach ($siswas as $siswa) {
                    if (!$catatans->has($siswa->id)) {
                        $auto_catatans[$siswa->id] = $this->generateCatatan($siswa->id, $proyek_id);
                    }
                }
            }
        }

        return view('guru.nilai_p5.input_catatan', compact(
            'kelompoks', 'proyeks', 'siswas', 'catatans', 'auto_catatans',
            'kelompok_id', 'proyek_id'
        ));
    }

    private function generateCatatan($siswa_id, $proyek_id)
    {
        $nilais = P5Nilai::where('siswa_id', $siswa_id)
            ->where('p5_proyek_id', $proyek_id)
            ->with('subElemen')
            ->get();

        if ($nilais->isEmpty()) {
            return '';
        }

        $sab = [];
        $bsh = [];
        $sb = [];
        $mb = [];

        foreach ($nilais as $n) {
            $name = $n->subElemen->nama_sub_elemen ?? '';
            if ($n->capaian == 'SAB') $sab[] = $name;
            elseif ($n->capaian == 'BSH') $bsh[] = $name;
            elseif ($n->capaian == 'SB') $sb[] = $name;
            elseif ($n->capaian == 'MB') $mb[] = $name;
        }

        $text = "";
        $siswa = \App\Models\Siswa::find($siswa_id);
        $nama = $siswa ? $siswa->nama_lengkap : 'Siswa';

        if (count($sab) > 0 || count($bsh) > 0) {
            $good = array_merge($sab, $bsh);
            $text .= "Dalam mengerjakan projek ini, $nama sudah mampu dan berkembang baik, terutama dalam hal " . strtolower(implode(', ', $good)) . ". ";
        }

        if (count($mb) > 0) {
            $text .= "Namun demikian, $nama masih perlu mendapatkan bimbingan lebih lanjut dalam hal " . strtolower(implode(', ', $mb)) . ".";
        } elseif (count($sb) > 0) {
            $text .= "$nama sedang berkembang dalam hal " . strtolower(implode(', ', $sb)) . ".";
        }

        return trim($text);
    }

    public function storeCatatan(Request $request)
    {
        $request->validate([
            'proyek_id' => 'required',
            'catatan' => 'required|array'
        ]);

        $proyek_id = $request->proyek_id;

        foreach ($request->catatan as $siswa_id => $text) {
            if (!empty(trim($text))) {
                P5Catatan::updateOrCreate(
                    [
                        'siswa_id' => $siswa_id,
                        'p5_proyek_id' => $proyek_id
                    ],
                    [
                        'catatan' => trim($text)
                    ]
                );
            }
        }

        return redirect()->back()->with('success', 'Catatan proses berhasil disimpan.');
    }

    public function resetCatatan(Request $request)
    {
        $request->validate([
            'proyek_id' => 'required'
        ]);

        P5Catatan::where('p5_proyek_id', $request->proyek_id)->delete();

        return redirect()->back()->with('success', 'Catatan berhasil di-reset. Sistem akan me-regenerate catatan otomatis.');
    }

    public function downloadCapaian(Request $request)
    {
        $user_id = Auth::id();
        $semester_id = \App\Models\Semester::where('is_aktif', true)->first()->id ?? 1;

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

        return view('guru.nilai_p5.download_capaian', compact(
            'kelompoks', 'proyeks', 'kelompok_id', 'proyek_id'
        ));
    }

    public function exportCapaian(Request $request)
    {
        $kelompok_id = $request->kelompok_id;
        $proyek_id = $request->proyek_id;
        
        $kelompok = P5Kelompok::find($kelompok_id);
        if (!$kelompok || !$proyek_id) {
            return redirect()->back()->with('error', 'Silakan pilih Kelompok dan Projek terlebih dahulu.');
        }

        $nama_file = "NILAI_P5_{$kelompok->nama_kelompok}.xlsx";
        return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\ArsipCapaianP5Export($kelompok_id, $proyek_id), $nama_file);
    }
}
