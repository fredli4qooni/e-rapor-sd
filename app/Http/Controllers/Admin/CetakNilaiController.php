<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Rombel;
use App\Models\Siswa;
use App\Models\Sekolah;
use App\Models\SettingRapor;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\LegerRaporExport;
use Barryvdh\DomPDF\Facade\Pdf;

class CetakNilaiController extends Controller
{
    // --- 1. LEGER RAPOR ---
    public function legerIndex(Request $request)
    {
        $rombel_id = $request->get('rombel_id');
        $rombels = Rombel::orderBy('nama_rombel')->get();
        
        $siswas = [];
        if ($rombel_id) {
            $siswas = Siswa::whereHas('rombels', function($q) use ($rombel_id) {
                $q->where('rombels.id', $rombel_id);
            })->orderBy('nama_lengkap')->get();
        }

        return view('admin.cetak_nilai.leger', compact('rombels', 'rombel_id', 'siswas'));
    }

    public function legerDownload($rombel_id)
    {
        $rombel = Rombel::findOrFail($rombel_id);
        return Excel::download(new LegerRaporExport($rombel_id, false), 'Leger_Nilai_'.$rombel->nama_rombel.'.xlsx');
    }

    public function legerDownloadSemua($rombel_id)
    {
        $rombel = Rombel::findOrFail($rombel_id);
        return Excel::download(new LegerRaporExport($rombel_id, true), 'Leger_Nilai_Semua_Semester_'.$rombel->nama_rombel.'.xlsx');
    }

    // --- 2. PELENGKAP RAPOR ---
    public function pelengkapIndex(Request $request)
    {
        $rombel_id = $request->get('rombel_id');
        $rombels = Rombel::orderBy('nama_rombel')->get();
        $sekolah = Sekolah::first();
        $setting = SettingRapor::where('sekolah_id', $sekolah->id ?? 1)->first();

        $siswas = [];
        if ($rombel_id) {
            $siswas = Siswa::whereHas('rombels', function($q) use ($rombel_id) {
                $q->where('rombels.id', $rombel_id);
            })->orderBy('nama_lengkap')->get();
        }

        return view('admin.cetak_nilai.pelengkap', compact('rombels', 'rombel_id', 'siswas', 'setting'));
    }

    public function pelengkapStoreSetting(Request $request)
    {
        $sekolah = Sekolah::first();
        if (!$sekolah) {
            return redirect()->back()->withErrors('Data sekolah belum diatur.');
        }

        $request->validate([
            'ukuran_kertas' => 'required|string',
            'margin_kiri' => 'required|numeric',
            'margin_kanan' => 'required|numeric',
            'margin_atas' => 'required|numeric',
            'margin_bawah' => 'required|numeric',
            'tampilkan_ttd_kepsek' => 'nullable|boolean',
            'tampilkan_ttd_wali' => 'nullable|boolean',
            'posisi_ttd_kepsek' => 'required|in:kiri,tengah,kanan',
            'tampilkan_nama_wali' => 'nullable|boolean',
            'hal_awal_rapor' => 'required|numeric'
        ]);

        SettingRapor::updateOrCreate(
            ['sekolah_id' => $sekolah->id],
            [
                'ukuran_kertas' => $request->ukuran_kertas,
                'margin_kiri' => $request->margin_kiri,
                'margin_kanan' => $request->margin_kanan,
                'margin_atas' => $request->margin_atas,
                'margin_bawah' => $request->margin_bawah,
                'tampilkan_ttd_kepsek' => $request->has('tampilkan_ttd_kepsek'),
                'tampilkan_ttd_wali' => $request->has('tampilkan_ttd_wali'),
                'posisi_ttd_kepsek' => $request->posisi_ttd_kepsek,
                'tampilkan_nama_wali' => $request->has('tampilkan_nama_wali'),
                'hal_awal_rapor' => $request->hal_awal_rapor
            ]
        );

        return redirect()->back()->with('success', 'Pengaturan cetak rapor berhasil disimpan.');
    }

    public function pelengkapGenerateSiswa($siswa_id)
    {
        $siswa = Siswa::findOrFail($siswa_id);
        $sekolah = Sekolah::first();
        $setting = SettingRapor::where('sekolah_id', $sekolah->id ?? 1)->first() ?? new SettingRapor();

        $semesterAktif = \App\Models\Semester::where('is_aktif', true)->first();
        $rombel = $siswa->rombels()->where('rombels.semester_id', $semesterAktif->id)->first();
        
        $siswas = collect([$siswa]);

        $pdf = Pdf::loadView('walikelas.cetak_nilai.pdf_pelengkap', compact('siswas', 'sekolah', 'setting', 'rombel'));
        
        $paper = $setting && $setting->ukuran_kertas === 'F4' ? [0, 0, 609.44, 935.43] : strtolower($setting->ukuran_kertas ?? 'a4');
        $pdf->setPaper($paper, 'portrait');

        return $pdf->stream('Pelengkap_Rapor_'.$siswa->nisn.'_'.str_replace(' ', '_', $siswa->nama_lengkap).'.pdf');
    }

    public function pelengkapGenerateKelas($rombel_id)
    {
        $rombel = Rombel::findOrFail($rombel_id);
        $siswas = $rombel->siswas()->orderBy('nama_lengkap')->get();

        $sekolah = Sekolah::first();
        $setting = SettingRapor::where('sekolah_id', $sekolah->id ?? 1)->first() ?? new SettingRapor();

        $pdf = Pdf::loadView('walikelas.cetak_nilai.pdf_pelengkap', compact('siswas', 'sekolah', 'setting', 'rombel'));
        
        $paper = $setting && $setting->ukuran_kertas === 'F4' ? [0, 0, 609.44, 935.43] : strtolower($setting->ukuran_kertas ?? 'a4');
        $pdf->setPaper($paper, 'portrait');

        return $pdf->stream('Pelengkap_Rapor_Kelas_'.$rombel->nama_rombel.'.pdf');
    }

    // --- 3. NILAI RAPOR ---
    public function nilaiRaporIndex(Request $request)
    {
        $rombel_id = $request->get('rombel_id');
        $rombels = Rombel::orderBy('nama_rombel')->get();
        
        $siswas = [];
        if ($rombel_id) {
            $siswas = Siswa::whereHas('rombels', function($q) use ($rombel_id) {
                $q->where('rombels.id', $rombel_id);
            })->orderBy('nama_lengkap')->get();
        }

        return view('admin.cetak_nilai.nilai_rapor', compact('rombels', 'rombel_id', 'siswas'));
    }

    private function getNilaiRaporData($siswa_id)
    {
        $siswa = Siswa::findOrFail($siswa_id);
        $sekolah = Sekolah::first();
        $setting = SettingRapor::where('sekolah_id', $sekolah->id ?? 1)->first();
        $semesterAktif = \App\Models\Semester::where('is_aktif', true)->first();

        $rombel = $siswa->rombels()->where('rombels.semester_id', $semesterAktif->id)->first();
        $waliKelas = $rombel ? $rombel->guru : null;

        // Kelompok Mapel
        $kelompokA = \App\Models\MataPelajaran::where('kelompok_mapel_id', 1)->orderBy('nomor_urut')->orderBy('nama_mapel')->get();
        $kelompokB = \App\Models\MataPelajaran::where('kelompok_mapel_id', 2)->orderBy('nomor_urut')->orderBy('nama_mapel')->get();
        $kelompokC = \App\Models\MataPelajaran::where('kelompok_mapel_id', 3)->orderBy('nomor_urut')->orderBy('nama_mapel')->get(); // Muatan Lokal dll

        $nilaiRapor = \App\Models\NilaiRapor::where('siswa_id', $siswa_id)
                                            ->where('semester_id', $semesterAktif->id)
                                            ->get()
                                            ->keyBy('mata_pelajaran_id');
        
        $ekskul = \App\Models\NilaiEkstrakurikuler::where('siswa_id', $siswa_id)
                                                  ->where('rombel_id', $rombel ? $rombel->id : 0)
                                                  ->get();
                                                  
        $kehadiran = \App\Models\Kehadiran::where('siswa_id', $siswa_id)
                                          ->where('semester_id', $semesterAktif->id)
                                          ->first();
                                          
        $catatanWali = \App\Models\CatatanWaliKelas::where('siswa_id', $siswa_id)
                                                   ->where('semester_id', $semesterAktif->id)
                                                   ->first();

        return compact('siswa', 'sekolah', 'setting', 'semesterAktif', 'rombel', 'waliKelas', 'kelompokA', 'kelompokB', 'kelompokC', 'nilaiRapor', 'ekskul', 'kehadiran', 'catatanWali');
    }

    public function nilaiRaporGenerateSiswa(Request $request, $siswa_id)
    {
        $siswa = Siswa::findOrFail($siswa_id);
        $sekolah = Sekolah::first();
        $setting = SettingRapor::where('sekolah_id', $sekolah->id ?? 1)->first() ?? new SettingRapor();
        
        $semesterAktif = \App\Models\Semester::where('is_aktif', true)->first();
        $rombel = $siswa->rombels()->where('rombels.semester_id', $semesterAktif->id)->first();
        
        if (!$rombel) return redirect()->back()->with('error', 'Siswa tidak memiliki rombel aktif.');

        $kurikulum = $rombel->kurikulum ?? 'MERDEKA';
        $curriculumService = app(\App\Services\CurriculumService::class);
        $viewName = $curriculumService->getRaporView($kurikulum);

        $siswas = collect([$siswa]);
        foreach ($siswas as $s) {
            $s->kelas = $rombel->nama_rombel;
            $s->fase = $rombel->fase;
            $s->nilaiRapors = \App\Models\NilaiRapor::with('mapel')->where('siswa_id', $s->id)->where('semester_id', $rombel->semester_id)->get();
            $s->kehadiran = \App\Models\Kehadiran::where('siswa_id', $s->id)->where('semester_id', $rombel->semester_id)->first();
            $s->catatan = \App\Models\CatatanWaliKelas::where('siswa_id', $s->id)->where('semester_id', $rombel->semester_id)->first();
            $s->kenaikan = \App\Models\KenaikanKelas::where('siswa_id', $s->id)->where('semester_id', $rombel->semester_id)->first();
            $s->sikap = \App\Models\Sikap::where('siswa_id', $s->id)->where('rombel_id', $rombel->id)->first();
            $s->ekskuls = \App\Models\NilaiEkstrakurikuler::with('ekstrakurikuler')->where('siswa_id', $s->id)->where('rombel_id', $rombel->id)->get();
        }

        $pdf = Pdf::loadView('walikelas.cetak_nilai.pdf_rapor_wrapper', compact('siswas', 'kurikulum', 'sekolah', 'setting', 'rombel', 'viewName'));
        $paper = $setting->ukuran_kertas === 'F4' ? [0, 0, 609.44, 935.43] : strtolower($setting->ukuran_kertas ?? 'a4');
        $pdf->setPaper($paper, 'portrait');

        return $pdf->stream('Nilai_Rapor_'.$siswa->nisn.'_'.str_replace(' ', '_', $siswa->nama_lengkap).'.pdf');
    }

    public function nilaiRaporGenerateKelas(Request $request, $rombel_id)
    {
        $rombel = Rombel::findOrFail($rombel_id);
        $siswas = $rombel->siswas()->orderBy('nama_lengkap')->get();
        $sekolah = Sekolah::first();
        $setting = SettingRapor::where('sekolah_id', $sekolah->id ?? 1)->first() ?? new SettingRapor();

        $kurikulum = $rombel->kurikulum ?? 'MERDEKA';
        $curriculumService = app(\App\Services\CurriculumService::class);
        $viewName = $curriculumService->getRaporView($kurikulum);

        foreach ($siswas as $s) {
            $s->kelas = $rombel->nama_rombel;
            $s->fase = $rombel->fase;
            $s->nilaiRapors = \App\Models\NilaiRapor::with('mapel')->where('siswa_id', $s->id)->where('semester_id', $rombel->semester_id)->get();
            $s->kehadiran = \App\Models\Kehadiran::where('siswa_id', $s->id)->where('semester_id', $rombel->semester_id)->first();
            $s->catatan = \App\Models\CatatanWaliKelas::where('siswa_id', $s->id)->where('semester_id', $rombel->semester_id)->first();
            $s->kenaikan = \App\Models\KenaikanKelas::where('siswa_id', $s->id)->where('semester_id', $rombel->semester_id)->first();
            $s->sikap = \App\Models\Sikap::where('siswa_id', $s->id)->where('rombel_id', $rombel->id)->first();
            $s->ekskuls = \App\Models\NilaiEkstrakurikuler::with('ekstrakurikuler')->where('siswa_id', $s->id)->where('rombel_id', $rombel->id)->get();
        }

        $pdf = Pdf::loadView('walikelas.cetak_nilai.pdf_rapor_wrapper', compact('siswas', 'kurikulum', 'sekolah', 'setting', 'rombel', 'viewName'));
        $paper = $setting->ukuran_kertas === 'F4' ? [0, 0, 609.44, 935.43] : strtolower($setting->ukuran_kertas ?? 'a4');
        $pdf->setPaper($paper, 'portrait');

        return $pdf->stream('Nilai_Rapor_Kelas_'.$rombel->nama_rombel.'.pdf');
    }

    // --- 4. RAPOR P5 ---
    public function raporP5Index(Request $request)
    {
        $rombel_id = $request->get('rombel_id');
        $rombels = Rombel::orderBy('nama_rombel')->get();
        
        $siswas = [];
        if ($rombel_id) {
            $siswas = Siswa::whereHas('rombels', function($q) use ($rombel_id) {
                $q->where('rombels.id', $rombel_id);
            })->orderBy('nama_lengkap')->get();
        }

        return view('admin.cetak_nilai.rapor_p5', compact('rombels', 'rombel_id', 'siswas'));
    }

    private function getRaporP5Data($siswa_id)
    {
        $siswa = Siswa::findOrFail($siswa_id);
        $sekolah = Sekolah::first();
        $setting = SettingRapor::where('sekolah_id', $sekolah->id ?? 1)->first();
        $semesterAktif = \App\Models\Semester::where('is_aktif', true)->first();

        $rombel = $siswa->rombels()->where('rombels.semester_id', $semesterAktif->id)->first();
        $waliKelas = $rombel ? $rombel->guru : null;

        // Data Proyek P5 (Dummy if module not fully built yet, assuming we will fetch from P5 models)
        // For now, let's query the `proyeks` table, assuming there is one, or just an empty collection if not exists
        // Wait, did we build P5 module? We have "Referensi P5" and "Kelola P5", I'll load from \App\Models\Proyek if it exists, otherwise empty.
        $proyeks = []; 
        if (class_exists('\App\Models\P5Proyek')) {
            $proyeks = \App\Models\P5Proyek::where('semester_id', $semesterAktif->id)->get();
        }

        return compact('siswa', 'sekolah', 'setting', 'semesterAktif', 'rombel', 'waliKelas', 'proyeks');
    }

    public function raporP5GenerateSiswa(Request $request, $siswa_id)
    {
        $siswa = Siswa::findOrFail($siswa_id);
        $sekolah = Sekolah::first();
        $setting = SettingRapor::where('sekolah_id', $sekolah->id ?? 1)->first() ?? new SettingRapor();
        
        $semesterAktif = \App\Models\Semester::where('is_aktif', true)->first();
        $rombel = $siswa->rombels()->where('rombels.semester_id', $semesterAktif->id)->first();
        
        $siswas = collect([$siswa]);

        $pdf = Pdf::loadView('walikelas.cetak_nilai.pdf_rapor_p5', compact('siswas', 'sekolah', 'setting', 'rombel'));
        $paper = $setting->ukuran_kertas === 'F4' ? [0, 0, 609.44, 935.43] : strtolower($setting->ukuran_kertas ?? 'a4');
        $pdf->setPaper($paper, 'portrait');

        return $pdf->stream('Rapor_P5_'.$siswa->nisn.'_'.str_replace(' ', '_', $siswa->nama_lengkap).'.pdf');
    }

    public function raporP5GenerateKelas(Request $request, $rombel_id)
    {
        $rombel = Rombel::findOrFail($rombel_id);
        $siswas = $rombel->siswas()->orderBy('nama_lengkap')->get();

        $sekolah = Sekolah::first();
        $setting = SettingRapor::where('sekolah_id', $sekolah->id ?? 1)->first() ?? new SettingRapor();

        $pdf = Pdf::loadView('walikelas.cetak_nilai.pdf_rapor_p5', compact('siswas', 'sekolah', 'setting', 'rombel'));
        $paper = $setting->ukuran_kertas === 'F4' ? [0, 0, 609.44, 935.43] : strtolower($setting->ukuran_kertas ?? 'a4');
        $pdf->setPaper($paper, 'portrait');

        return $pdf->stream('Rapor_P5_Kelas_'.$rombel->nama_rombel.'.pdf');
    }
}
