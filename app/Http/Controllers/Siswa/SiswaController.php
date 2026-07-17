<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Siswa;
use App\Models\Rombel;
use App\Models\NilaiRapor;

class SiswaController extends Controller
{
    public function dashboard()
    {
        $user = auth()->user();
        $siswa = Siswa::where('user_id', $user->id)->first();
        
        if (!$siswa) {
            return "Profil siswa belum dikaitkan dengan akun ini.";
        }

        $semesterAktif = \App\Models\Semester::where('is_aktif', true)->first();
        $sekolah = \App\Models\Sekolah::where('id', $siswa->sekolah_id)->first() ?? \App\Models\Sekolah::first();
        
        // Find active rombel
        $rombel = \App\Models\Rombel::whereHas('siswas', function($q) use ($siswa) {
            $q->where('siswa_id', $siswa->id);
        })->first(); // Idealnya difilter berdasarkan semester juga, ini fallback simple
        
        $kurikulum = $rombel ? $rombel->kurikulum : 'MERDEKA';

        // Data Ekstrakurikuler
        $ekskuls = \App\Models\NilaiEkstrakurikuler::with('ekstrakurikuler')
            ->where('siswa_id', $siswa->id)
            ->get();
            
        // Data Kokurikuler / P5 Kelompok
        $kelompokP5s = \App\Models\P5Kelompok::whereHas('siswas', function($q) use ($siswa) {
            $q->where('siswa_id', $siswa->id);
        })->where('semester_id', $semesterAktif ? $semesterAktif->id : 0)->get();

        // Data Grafik Analitik Nilai Siswa
        $grafik_nilai = [];
        if ($semesterAktif && $siswa) {
            $grafik_nilai = \Illuminate\Support\Facades\DB::table('nilai_rapors')
                ->join('mata_pelajarans', 'nilai_rapors.mata_pelajaran_id', '=', 'mata_pelajarans.id')
                ->select(\Illuminate\Support\Facades\DB::raw('COALESCE(NULLIF(mata_pelajarans.nama_singkat, ""), mata_pelajarans.nama_mapel) as mapel_label'), 'nilai_rapors.nilai_akhir as rata_rata')
                ->where('nilai_rapors.semester_id', $semesterAktif->id)
                ->where('nilai_rapors.siswa_id', $siswa->id)
                ->get();
        }
        $chart_nilai_labels = $grafik_nilai ? collect($grafik_nilai)->pluck('mapel_label')->toJson() : '[]';
        $chart_nilai_data = $grafik_nilai ? collect($grafik_nilai)->pluck('rata_rata')->map(fn($v) => round($v, 2))->toJson() : '[]';

        return view('siswa.dashboard', compact('siswa', 'sekolah', 'semesterAktif', 'rombel', 'kurikulum', 'ekskuls', 'kelompokP5s', 'chart_nilai_labels', 'chart_nilai_data'));
    }

    public function cetak(\App\Services\CurriculumService $curriculumService, \Illuminate\Http\Request $request)
    {
        $user = auth()->user();
        $siswa = Siswa::where('user_id', $user->id)->firstOrFail();
        $semester_id = $request->get('semester_id');
        
        $rombelQuery = Rombel::whereHas('siswas', function($q) use ($siswa) { $q->where('siswa_id', $siswa->id); });
        if ($semester_id) {
            $rombelQuery->where('semester_id', $semester_id);
        } else {
            $activeSemester = \App\Models\Semester::where('is_aktif', true)->first();
            if ($activeSemester) $rombelQuery->where('semester_id', $activeSemester->id);
        }
        $rombel = $rombelQuery->first() ?? Rombel::where('sekolah_id', $siswa->sekolah_id)->first();
        
        $kurikulum = $rombel ? $rombel->kurikulum : 'MERDEKA';
        
        $siswa->kelas = $rombel ? $rombel->nama_rombel : '-';
        $siswa->fase = $rombel ? $rombel->fase : '-';

        $nilaiQuery = NilaiRapor::with('mapel')->where('siswa_id', $siswa->id);
        if ($semester_id) $nilaiQuery->where('semester_id', $semester_id);
        $siswa->nilaiRapors = $nilaiQuery->get();
        
        $siswa->kehadiran = \App\Models\Kehadiran::where('siswa_id', $siswa->id)->where('semester_id', $rombel->semester_id)->first();
        $siswa->catatan = \App\Models\CatatanWaliKelas::where('siswa_id', $siswa->id)->where('semester_id', $rombel->semester_id)->first();
        $siswa->kenaikan = \App\Models\KenaikanKelas::where('siswa_id', $siswa->id)->where('semester_id', $rombel->semester_id)->first();
        $siswa->sikap = \App\Models\Sikap::where('siswa_id', $siswa->id)->where('rombel_id', $rombel->id)->first();
        $siswa->ekskuls = \App\Models\NilaiEkstrakurikuler::with('ekstrakurikuler')->where('siswa_id', $siswa->id)->where('rombel_id', $rombel->id)->get();

        // Check Publikasi for Active Semester
        $activeSemester = \App\Models\Semester::where('is_aktif', true)->first();
        if (!$semester_id || ($activeSemester && $semester_id == $activeSemester->id)) {
            if (!$siswa->is_rapor_published) {
                return back()->with('error', 'Rapor belum dipublikasikan oleh Wali Kelas.');
            }
        }

        $sekolah = \App\Models\Sekolah::where('id', $siswa->sekolah_id)->first() ?? \App\Models\Sekolah::first();
        
        $viewName = $curriculumService->getRaporView($kurikulum);
        $setting = \App\Models\SettingRapor::where('sekolah_id', $sekolah->id)->first() ?? new \App\Models\SettingRapor();
        $siswas = collect([$siswa]);
        
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('walikelas.cetak_nilai.pdf_rapor_wrapper', compact('siswas', 'kurikulum', 'sekolah', 'setting', 'rombel', 'viewName'));
        
        $paper = $setting->ukuran_kertas === 'F4' ? [0, 0, 609.44, 935.43] : strtolower($setting->ukuran_kertas ?? 'a4');
        $pdf->setPaper($paper, 'portrait');

        return $pdf->stream('rapor_semester_'.$kurikulum.'_'.$siswa->nis.'.pdf');
    }

    public function cetakPelengkap(\Illuminate\Http\Request $request)
    {
        $user = auth()->user();
        $siswa = Siswa::where('user_id', $user->id)->firstOrFail();
        $semester_id = $request->get('semester_id');
        $activeSemester = \App\Models\Semester::where('is_aktif', true)->first();
        
        if (!$semester_id || ($activeSemester && $semester_id == $activeSemester->id)) {
            if (!$siswa->is_pelengkap_published) {
                return back()->with('error', 'Pelengkap Rapor belum dipublikasikan.');
            }
        }

        $sekolah = \App\Models\Sekolah::where('id', $siswa->sekolah_id)->first() ?? \App\Models\Sekolah::first();
        
        $rombelQuery = Rombel::whereHas('siswas', function($q) use ($siswa) { $q->where('siswa_id', $siswa->id); });
        if ($semester_id) $rombelQuery->where('semester_id', $semester_id);
        $rombel = $rombelQuery->first();
        
        $siswas = collect([$siswa]);
        $setting = \App\Models\SettingRapor::first() ?? new \App\Models\SettingRapor();
        
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('walikelas.cetak_nilai.pdf_pelengkap', compact('siswas', 'sekolah', 'setting', 'rombel'));
        return $pdf->stream('Pelengkap_Rapor_'.$siswa->nisn.'.pdf');
    }

    public function cetakP5(\Illuminate\Http\Request $request)
    {
        $user = auth()->user();
        $siswa = Siswa::where('user_id', $user->id)->firstOrFail();
        $semester_id = $request->get('semester_id');
        $activeSemester = \App\Models\Semester::where('is_aktif', true)->first();
        
        if (!$semester_id || ($activeSemester && $semester_id == $activeSemester->id)) {
            if (!$siswa->is_p5_published) {
                return back()->with('error', 'Rapor P5 belum dipublikasikan.');
            }
        }

        $sekolah = \App\Models\Sekolah::where('id', $siswa->sekolah_id)->first() ?? \App\Models\Sekolah::first();
        
        $rombelQuery = Rombel::whereHas('siswas', function($q) use ($siswa) { $q->where('siswa_id', $siswa->id); });
        if ($semester_id) $rombelQuery->where('semester_id', $semester_id);
        $rombel = $rombelQuery->first();
        
        $siswas = collect([$siswa]);
        $setting = \App\Models\SettingRapor::first() ?? new \App\Models\SettingRapor();

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('walikelas.cetak_nilai.pdf_rapor_p5', compact('siswas', 'sekolah', 'setting', 'rombel'));
        return $pdf->stream('Rapor_P5_'.$siswa->nisn.'.pdf');
    }

    public function downloadRapor()
    {
        $user = auth()->user();
        $siswa = Siswa::where('user_id', $user->id)->firstOrFail();
        
        // Find all semesters where the student was enrolled in a Rombel
        $rombels = Rombel::with('semester')
            ->whereHas('siswas', function($q) use ($siswa) {
                $q->where('siswa_id', $siswa->id);
            })
            ->get();
            
        // Extract distinct semesters from the rombels
        $semesters = collect();
        foreach ($rombels as $rombel) {
            if ($rombel->semester) {
                // Ensure unique
                if (!$semesters->contains('id', $rombel->semester->id)) {
                    $semesters->push($rombel->semester);
                }
            }
        }
        
        // Sort from oldest to newest semester
        $semesters = $semesters->sortBy('id');
        $activeSemester = \App\Models\Semester::where('is_aktif', true)->first();

        return view('siswa.download_rapor', compact('siswa', 'semesters', 'activeSemester'));
    }

    private function getRekapData($siswa, $type = 'nilai')
    {
        $nilais = \App\Models\NilaiRapor::with(['mapel.kelompok', 'semester', 'deskripsi'])
            ->where('siswa_id', $siswa->id)
            ->get();
            
        // Riwayat rombel siswa untuk menentukan tingkat per semester
        $rombelHistory = \App\Models\Rombel::whereHas('siswas', function($q) use ($siswa) {
            $q->where('siswa_id', $siswa->id);
        })->get()->keyBy('semester_id');

        $activeSemester = \App\Models\Semester::where('is_aktif', true)->first();

        $matrix = [];
        $mapels = [];

        foreach ($nilais as $nilai) {
            $smtId = $nilai->semester_id;
            
            // Check publikasi
            if ($activeSemester && $smtId == $activeSemester->id) {
                if (!$siswa->is_rapor_published) {
                    continue; // Skip data ini karena belum dipublish
                }
            }
            
            $rombel = $rombelHistory->get($smtId);
            if (!$rombel) continue; 
            
            $tingkat = $rombel->tingkat;
            $semester = $nilai->semester->semester; // 'Ganjil' atau 'Genap'
            
            $smtIndex = ($tingkat - 1) * 2 + ($semester == 'Ganjil' ? 1 : 2);
            
            $mapelId = $nilai->mata_pelajaran_id;
            if (!isset($mapels[$mapelId])) {
                $mapels[$mapelId] = $nilai->mapel;
            }
            
            if ($type == 'nilai') {
                $matrix[$mapelId][$smtIndex] = $nilai->nilai_akhir;
            } else {
                $deskripsi = $nilai->deskripsi;
                $teksDeskripsi = '';
                if ($deskripsi) {
                    if ($deskripsi->deskripsi_tertinggi) {
                        $teksDeskripsi .= "Mencapai Kompetensi dengan sangat baik dalam hal " . $deskripsi->deskripsi_tertinggi . ". ";
                    }
                    if ($deskripsi->deskripsi_terendah) {
                        $teksDeskripsi .= "Perlu peningkatan dalam hal " . $deskripsi->deskripsi_terendah . ".";
                    }
                }
                $matrix[$mapelId][$smtIndex] = $teksDeskripsi ?: '-';
            }
        }
        
        $groupedMapels = collect($mapels)->sortBy('nomor_urut')->groupBy(function ($mapel) {
            return $mapel->kelompok ? $mapel->kelompok->nama_kelompok : 'Kelompok Umum';
        });
        
        return [
            'matrix' => $matrix,
            'groupedMapels' => $groupedMapels
        ];
    }

    public function rekapNilai()
    {
        $user = auth()->user();
        $siswa = Siswa::where('user_id', $user->id)->firstOrFail();
        $semesterAktif = \App\Models\Semester::where('is_aktif', true)->first();
        
        $data = $this->getRekapData($siswa, 'nilai');
        $matrix = $data['matrix'];
        $groupedMapels = $data['groupedMapels'];
        
        return view('siswa.rekap_nilai', compact('siswa', 'semesterAktif', 'matrix', 'groupedMapels'));
    }

    public function rekapDeskripsi()
    {
        $user = auth()->user();
        $siswa = Siswa::where('user_id', $user->id)->firstOrFail();
        $semesterAktif = \App\Models\Semester::where('is_aktif', true)->first();
        
        $data = $this->getRekapData($siswa, 'deskripsi');
        $matrix = $data['matrix'];
        $groupedMapels = $data['groupedMapels'];
        
        return view('siswa.rekap_deskripsi', compact('siswa', 'semesterAktif', 'matrix', 'groupedMapels'));
    }
}
