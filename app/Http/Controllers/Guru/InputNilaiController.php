<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Rombel;
use App\Models\MataPelajaran;
use App\Models\NilaiRapor;
use App\Models\Siswa;
use App\Models\Semester;
use App\Models\Pembelajaran;
use App\Models\TujuanPembelajaran;
use App\Models\DeskripsiRapor;

class InputNilaiController extends Controller
{
    public function index(Request $request)
    {
        $guru = Auth::user()->guru;
        $semesterAktif = Semester::where('is_aktif', true)->first();

        // Ambil kelas & mapel yang diajarkan guru ini
        $pembelajarans = Pembelajaran::where('guru_id', $guru->id)
            ->where('semester_id', $semesterAktif->id)
            ->with(['rombel', 'mapel'])
            ->get();

        $rombel_id = $request->query('rombel_id');
        $mata_pelajaran_id = $request->query('mata_pelajaran_id');

        // Extract unique rombels and mapels from pembelajarans
        $guruRombels = $pembelajarans->map(function ($p) {
            return $p->rombel;
        })->unique('id')->values();

        $guruMapels = collect();
        if ($rombel_id) {
             // Show only mapels taught by this guru in the selected rombel
             $guruMapels = $pembelajarans->where('rombel_id', $rombel_id)->map(function ($p) {
                 return $p->mapel;
             })->unique('id')->values();
        } else {
             // Or show all mapels they teach
             $guruMapels = $pembelajarans->map(function ($p) {
                 return $p->mapel;
             })->unique('id')->values();
        }

        $rombel = null;
        $mapel = null;
        $siswas = collect();
        $tps = collect();
        $nilai = collect();

        if ($rombel_id && $mata_pelajaran_id) {
            $rombel = Rombel::find($rombel_id);
            $mapel = MataPelajaran::find($mata_pelajaran_id);

            // Get Siswa from anggota_rombels
            $siswaIds = DB::table('anggota_rombels')->where('rombel_id', $rombel_id)->pluck('siswa_id');
            $siswas = Siswa::whereIn('id', $siswaIds)->orderBy('nama_lengkap')->get();

            // Get TP for this mapel and tingkat
            $tps = TujuanPembelajaran::where('mata_pelajaran_id', $mata_pelajaran_id)
                ->where('tingkat', $rombel->tingkat)
                ->where('semester_id', $semesterAktif->id)
                ->where('is_aktif', true)
                ->get();

            // Get existing Nilai
            $nilai = NilaiRapor::whereIn('siswa_id', $siswaIds)
                ->where('mata_pelajaran_id', $mata_pelajaran_id)
                ->where('semester_id', $semesterAktif->id)
                ->with('deskripsi')
                ->get()
                ->keyBy('siswa_id');
        }

        return view('guru.nilai.index', compact(
            'guruRombels', 'guruMapels', 'rombel_id', 'mata_pelajaran_id', 'rombel', 'mapel', 'siswas', 'tps', 'nilai', 'semesterAktif'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'rombel_id' => 'required|exists:rombels,id',
            'mata_pelajaran_id' => 'required|exists:mata_pelajarans,id',
            'nilai' => 'required|array',
        ]);

        $semesterAktif = Semester::where('is_aktif', true)->first();
        $tps = TujuanPembelajaran::whereIn('id', $request->input('all_tp_ids', []))->get()->keyBy('id');

        foreach ($request->nilai as $siswa_id => $data) {
            // Check if user filled nilai_akhir
            if (isset($data['nilai_akhir']) && $data['nilai_akhir'] !== null) {
                
                $tpTertinggiIds = $data['tp_tertinggi'] ?? [];
                $tpTerendahIds = $data['tp_terendah'] ?? [];

                // Format arrays for JSON storage
                $tpTertinggiJson = array_values($tpTertinggiIds);
                $tpTerendahJson = array_values($tpTerendahIds);

                $nilaiRapor = NilaiRapor::updateOrCreate(
                    [
                        'siswa_id' => $siswa_id,
                        'mata_pelajaran_id' => $request->mata_pelajaran_id,
                        'semester_id' => $semesterAktif->id,
                    ],
                    [
                        'nilai_akhir' => $data['nilai_akhir'],
                        'tp_tertinggi' => $tpTertinggiJson,
                        'tp_terendah' => $tpTerendahJson,
                    ]
                );

                // Generate Auto Deskripsi
                $deskripsiTertinggi = '';
                $deskripsiTerendah = '';

                if (count($tpTertinggiIds) > 0) {
                    $descTexts = [];
                    foreach ($tpTertinggiIds as $tId) {
                        if (isset($tps[$tId])) {
                            $descTexts[] = $tps[$tId]->deskripsi;
                        }
                    }
                    if (count($descTexts) > 0) {
                        $deskripsiTertinggi = "Menunjukkan penguasaan yang sangat baik dalam " . implode(", ", $descTexts) . ".";
                    }
                }

                if (count($tpTerendahIds) > 0) {
                    $descTexts = [];
                    foreach ($tpTerendahIds as $tId) {
                        if (isset($tps[$tId])) {
                            $descTexts[] = $tps[$tId]->deskripsi;
                        }
                    }
                    if (count($descTexts) > 0) {
                        $deskripsiTerendah = "Perlu pendampingan dalam " . implode(", ", $descTexts) . ".";
                    }
                }

                // Cek apakah deskripsi rapor sudah ada, jika ada biarkan (tidak dioverride manual jika guru sudah edit)
                // Kecuali kita mau selalu regenerasi? Aturan e-rapor: Auto-generate, guru bisa edit.
                // Jika ingin selalu regenerate saat simpan nilai, hapus komentar:
                DeskripsiRapor::updateOrCreate(
                    ['nilai_rapor_id' => $nilaiRapor->id],
                    [
                        'deskripsi_tertinggi' => $deskripsiTertinggi,
                        'deskripsi_terendah' => $deskripsiTerendah,
                    ]
                );
            }
        }

        return redirect()->back()->with('success', 'Nilai dan Deskripsi Rapor berhasil disimpan!');
    }

    public function deskripsi(Request $request)
    {
        $rombel_id = $request->query('rombel_id');
        $mata_pelajaran_id = $request->query('mata_pelajaran_id');

        if (!$rombel_id || !$mata_pelajaran_id) {
            return redirect()->route('guru.nilai.index')->with('error', 'Silakan pilih Kelas dan Mata Pelajaran terlebih dahulu.');
        }

        $rombel = Rombel::find($rombel_id);
        $mapel = MataPelajaran::find($mata_pelajaran_id);
        $semesterAktif = Semester::where('is_aktif', true)->first();

        $siswaIds = DB::table('anggota_rombels')->where('rombel_id', $rombel_id)->pluck('siswa_id');
        $siswas = Siswa::whereIn('id', $siswaIds)->orderBy('nama_lengkap')->get();

        $nilai = NilaiRapor::whereIn('siswa_id', $siswaIds)
            ->where('mata_pelajaran_id', $mata_pelajaran_id)
            ->where('semester_id', $semesterAktif->id)
            ->with('deskripsi')
            ->get()
            ->keyBy('siswa_id');

        return view('guru.nilai.deskripsi', compact('rombel', 'mapel', 'siswas', 'nilai', 'semesterAktif'));
    }

    public function updateDeskripsi(Request $request)
    {
        $request->validate([
            'deskripsi' => 'required|array',
        ]);

        foreach ($request->deskripsi as $nilaiRaporId => $data) {
            DeskripsiRapor::updateOrCreate(
                ['nilai_rapor_id' => $nilaiRaporId],
                [
                    'deskripsi_tertinggi' => $data['tertinggi'] ?? '-',
                    'deskripsi_terendah' => $data['terendah'] ?? '-',
                ]
            );
        }
        return redirect()->back()->with('success', 'Perubahan deskripsi berhasil disimpan!');
    }

    public function importIndex(Request $request)
    {
        $guru = Auth::user()->guru;
        $semesterAktif = Semester::where('is_aktif', true)->first();

        // Ambil kelas & mapel yang diajarkan guru ini
        $pembelajarans = Pembelajaran::where('guru_id', $guru->id)
            ->where('semester_id', $semesterAktif->id)
            ->with(['rombel', 'mapel'])
            ->get();

        $rombel_id = $request->query('rombel_id');
        $mata_pelajaran_id = $request->query('mata_pelajaran_id');

        // Extract unique rombels and mapels from pembelajarans
        $guruRombels = $pembelajarans->map(function ($p) {
            return $p->rombel;
        })->unique('id')->values();

        $guruMapels = collect();
        if ($rombel_id) {
             $guruMapels = $pembelajarans->where('rombel_id', $rombel_id)->map(function ($p) {
                 return $p->mapel;
             })->unique('id')->values();
        } else {
             $guruMapels = $pembelajarans->map(function ($p) {
                 return $p->mapel;
             })->unique('id')->values();
        }

        $rombel = null;
        $mapel = null;

        if ($rombel_id && $mata_pelajaran_id) {
            $rombel = Rombel::find($rombel_id);
            $mapel = MataPelajaran::find($mata_pelajaran_id);
        }

        return view('guru.nilai.import', compact('guruRombels', 'guruMapels', 'rombel_id', 'mata_pelajaran_id', 'rombel', 'mapel'));
    }

    public function downloadFormat(Request $request)
    {
        $rombel_id = $request->query('rombel_id');
        $mata_pelajaran_id = $request->query('mata_pelajaran_id');
        $semesterAktif = Semester::where('is_aktif', true)->first();

        $rombel = Rombel::find($rombel_id);
        $mapel = MataPelajaran::find($mata_pelajaran_id);

        $namaFile = 'Format_Nilai_' . str_replace(' ', '_', $rombel->nama_rombel) . '_' . str_replace(' ', '_', $mapel->nama_mapel) . '.xlsx';

        return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\NilaiRaporExport($rombel_id, $mata_pelajaran_id, $semesterAktif->id), $namaFile);
    }

    public function importStore(Request $request)
    {
        $request->validate([
            'rombel_id' => 'required',
            'mata_pelajaran_id' => 'required',
            'file_nilai' => 'required|mimes:xls,xlsx|max:2048'
        ]);

        $semesterAktif = Semester::where('is_aktif', true)->first();

        try {
            \Maatwebsite\Excel\Facades\Excel::import(
                new \App\Imports\NilaiRaporImport($request->rombel_id, $request->mata_pelajaran_id, $semesterAktif->id),
                $request->file('file_nilai')
            );
            return redirect()->route('guru.nilai.index', ['rombel_id' => $request->rombel_id, 'mata_pelajaran_id' => $request->mata_pelajaran_id])->with('success', 'Nilai Rapor berhasil diimpor dan deskripsi otomatis telah digenerate.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal mengimpor file! Pastikan format sesuai dengan template.');
        }
    }
}
