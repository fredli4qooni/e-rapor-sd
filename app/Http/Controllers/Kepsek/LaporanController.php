<?php

namespace App\Http\Controllers\Kepsek;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Rombel;
use App\Models\Siswa;
use App\Models\Sekolah;
use App\Models\SettingRapor;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\LegerRaporExport;

class LaporanController extends Controller
{
    // --- 1. LEGER RAPOR ---
    public function leger(Request $request)
    {
        $rombel_id = $request->get('rombel_id');
        $rombels = Rombel::orderBy('nama_rombel')->get();
        
        $siswas = [];
        if ($rombel_id) {
            $siswas = Siswa::whereHas('rombels', function($q) use ($rombel_id) {
                $q->where('rombels.id', $rombel_id);
            })->orderBy('nama_lengkap')->get();
        }

        return view('kepsek.laporan.leger', compact('rombels', 'rombel_id', 'siswas'));
    }

    public function legerDownload($rombel_id)
    {
        $rombel = Rombel::findOrFail($rombel_id);
        return Excel::download(new LegerRaporExport($rombel_id, false), 'Leger_Nilai_'.$rombel->nama_rombel.'.xlsx');
    }

    // --- 2. NILAI RAPOR ---
    public function rapor(Request $request)
    {
        $rombel_id = $request->get('rombel_id');
        $rombels = Rombel::orderBy('nama_rombel')->get();
        
        $siswas = [];
        if ($rombel_id) {
            $siswas = Siswa::whereHas('rombels', function($q) use ($rombel_id) {
                $q->where('rombels.id', $rombel_id);
            })->orderBy('nama_lengkap')->get();
        }

        return view('kepsek.laporan.rapor', compact('rombels', 'rombel_id', 'siswas'));
    }

    public function raporSiswa(Request $request, $siswa_id)
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

    public function raporKelas(Request $request, $rombel_id)
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

    // --- 3. RAPOR P5 ---
    public function raporP5(Request $request)
    {
        $rombel_id = $request->get('rombel_id');
        $rombels = Rombel::orderBy('nama_rombel')->get();
        
        $siswas = [];
        if ($rombel_id) {
            $siswas = Siswa::whereHas('rombels', function($q) use ($rombel_id) {
                $q->where('rombels.id', $rombel_id);
            })->orderBy('nama_lengkap')->get();
        }

        return view('kepsek.laporan.rapor_p5', compact('rombels', 'rombel_id', 'siswas'));
    }

    public function raporP5Siswa(Request $request, $siswa_id)
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

    public function raporP5Kelas(Request $request, $rombel_id)
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
