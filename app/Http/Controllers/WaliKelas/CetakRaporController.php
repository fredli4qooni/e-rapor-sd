<?php

namespace App\Http\Controllers\WaliKelas;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;

use App\Models\Siswa;
use App\Models\NilaiRapor;
use App\Models\Rombel;
use App\Models\Kehadiran;
use App\Models\CatatanWaliKelas;
use App\Models\KenaikanKelas;
use App\Models\Sikap;
use App\Models\NilaiEkstrakurikuler;
use App\Models\Sekolah;

use App\Exports\LegerExport;

class CetakRaporController extends Controller
{
    private function getRombel()
    {
        return Rombel::where('wali_kelas_id', auth()->user()->guru->id ?? 1)->first();
    }

    // --- LEGER RAPOR ---
    public function leger()
    {
        $rombel = $this->getRombel();
        if (!$rombel) return redirect()->back()->with('error', 'Anda belum ditugaskan sebagai Wali Kelas.');

        return view('walikelas.cetak_nilai.leger', compact('rombel'));
    }

    public function downloadLeger(Request $request)
    {
        // Parameter 'tipe' = 'semester' atau 'semua'
        $tipe = $request->get('tipe', 'semester');
        $rombel = $this->getRombel();
        if (!$rombel) return redirect()->back()->with('error', 'Anda belum ditugaskan sebagai Wali Kelas.');

        $filename = 'Leger_Nilai_'.$rombel->nama_rombel.'_'.($tipe == 'semua' ? 'Semua_Semester' : 'Semester_Ini').'.xlsx';
        // Note: Asumsi LegerExport mendukung handling data. Sesuaikan dengan implementasi asli jika perlu parameter.
        return Excel::download(new LegerExport($rombel, $tipe), $filename);
    }

    // --- PELENGKAP RAPOR ---
    public function pelengkapIndex()
    {
        $rombel = $this->getRombel();
        if (!$rombel) return redirect()->back()->with('error', 'Anda belum ditugaskan sebagai Wali Kelas.');

        $siswas = $rombel->siswas()->orderBy('nama_lengkap')->get();

        // Default setting
        $setting = (object)[
            'ukuran_kertas' => 'A4',
            'margin_kiri' => 20,
            'margin_kanan' => 20,
            'margin_atas' => 20,
            'margin_bawah' => 20,
            'isi_tanda_tangan' => 'Tanpa Tanda Tangan'
        ];

        return view('walikelas.cetak_nilai.pelengkap', compact('rombel', 'siswas', 'setting'));
    }

    public function generatePelengkap(Request $request, $siswa_id = null)
    {
        $rombel = $this->getRombel();
        if (!$rombel) return abort(403);

        $sekolah = Sekolah::first();
        $setting = (object) $request->all();
        // Fallbacks
        if (!isset($setting->ukuran_kertas)) $setting->ukuran_kertas = 'A4';
        
        $paper = $setting->ukuran_kertas === 'F4' ? [0, 0, 609.44, 935.43] : strtolower($setting->ukuran_kertas);

        if ($siswa_id) {
            $siswa = Siswa::findOrFail($siswa_id);
            if (!$rombel->siswas->contains($siswa->id)) return abort(403);
            
            $siswas = collect([$siswa]);
            $filename = 'Pelengkap_Rapor_'.$siswa->nisn.'.pdf';
        } else {
            $siswas = $rombel->siswas()->orderBy('nama_lengkap')->get();
            $filename = 'Pelengkap_Rapor_Kelas_'.$rombel->nama_rombel.'.pdf';
        }

        $pdf = Pdf::loadView('walikelas.cetak_nilai.pdf_pelengkap', compact('siswas', 'sekolah', 'setting', 'rombel'));
        $pdf->setPaper($paper, 'portrait');

        return $pdf->stream($filename);
    }

    public function togglePelengkap(Request $request)
    {
        return $this->togglePublikasi($request, 'is_pelengkap_published');
    }

    // --- NILAI RAPOR ---
    public function raporIndex()
    {
        $rombel = $this->getRombel();
        if (!$rombel) return redirect()->back()->with('error', 'Anda belum ditugaskan sebagai Wali Kelas.');

        $siswas = $rombel->siswas()->orderBy('nama_lengkap')->get();

        $setting = (object)[
            'ukuran_kertas' => 'A4',
            'margin_kiri' => 20,
            'margin_kanan' => 20,
            'margin_atas' => 20,
            'margin_bawah' => 10,
            'halaman_pertama' => 1,
            'isi_tanda_tangan' => 'Tanpa Tanda Tangan',
            'posisi_ttd_ks' => 'Sejajar Wali Kelas',
            'tampil_nama_wali' => 'Isi Nama Wali Kelas'
        ];

        return view('walikelas.cetak_nilai.rapor', compact('rombel', 'siswas', 'setting'));
    }

    public function generateRapor(Request $request, $siswa_id = null, \App\Services\CurriculumService $curriculumService)
    {
        $rombel = $this->getRombel();
        if (!$rombel) return abort(403);

        $sekolah = Sekolah::first();
        $setting = (object) $request->all();
        if (!isset($setting->ukuran_kertas)) $setting->ukuran_kertas = 'A4';

        $kurikulum = $rombel->kurikulum ?? 'MERDEKA';
        $viewName = $curriculumService->getRaporView($kurikulum);

        // We will pass $siswas collection to a wrapper view, or just pass to the existing view if it handles collection
        // But the original view handles single $siswa. We might need a wrapper view `pdf_rapor_wrapper` that loops.
        
        if ($siswa_id) {
            $siswa = Siswa::findOrFail($siswa_id);
            if (!$rombel->siswas->contains($siswa->id)) return abort(403);
            $siswas = collect([$siswa]);
            $filename = 'Rapor_'.$kurikulum.'_'.$siswa->nisn.'.pdf';
        } else {
            $siswas = $rombel->siswas()->orderBy('nama_lengkap')->get();
            $filename = 'Rapor_Kelas_'.$rombel->nama_rombel.'.pdf';
        }

        // Preload data for each siswa
        foreach ($siswas as $siswa) {
            $siswa->kelas = $rombel->nama_rombel;
            $siswa->fase = $rombel->fase;
            $siswa->nilaiRapors = NilaiRapor::with('mapel')->where('siswa_id', $siswa->id)->where('semester_id', $rombel->semester_id)->get();
            $siswa->kehadiran = Kehadiran::where('siswa_id', $siswa->id)->where('semester_id', $rombel->semester_id)->first();
            $siswa->catatan = CatatanWaliKelas::where('siswa_id', $siswa->id)->where('semester_id', $rombel->semester_id)->first();
            $siswa->kenaikan = KenaikanKelas::where('siswa_id', $siswa->id)->where('semester_id', $rombel->semester_id)->first();
            $siswa->sikap = Sikap::where('siswa_id', $siswa->id)->where('rombel_id', $rombel->id)->first();
            $siswa->ekskuls = NilaiEkstrakurikuler::with('ekstrakurikuler')->where('siswa_id', $siswa->id)->where('rombel_id', $rombel->id)->get();
        }

        $pdf = Pdf::loadView('walikelas.cetak_nilai.pdf_rapor_wrapper', compact('siswas', 'kurikulum', 'sekolah', 'setting', 'rombel', 'viewName'));
        $paper = $setting->ukuran_kertas === 'F4' ? [0, 0, 609.44, 935.43] : strtolower($setting->ukuran_kertas);
        $pdf->setPaper($paper, 'portrait');

        return $pdf->stream($filename);
    }

    public function toggleRapor(Request $request)
    {
        return $this->togglePublikasi($request, 'is_rapor_published');
    }

    // --- RAPOR P5 ---
    public function raporP5Index()
    {
        $rombel = $this->getRombel();
        if (!$rombel) return redirect()->back()->with('error', 'Anda belum ditugaskan sebagai Wali Kelas.');

        $siswas = $rombel->siswas()->orderBy('nama_lengkap')->get();

        $setting = (object)[
            'ukuran_kertas' => 'A4',
            'margin_kiri' => 20,
            'margin_kanan' => 20,
            'margin_atas' => 20,
            'margin_bawah' => 10,
            'halaman_pertama' => 1,
            'isi_tanda_tangan' => 'Tanpa Tanda Tangan'
        ];

        return view('walikelas.cetak_nilai.rapor_p5', compact('rombel', 'siswas', 'setting'));
    }

    public function generateRaporP5(Request $request, $siswa_id = null)
    {
        $rombel = $this->getRombel();
        if (!$rombel) return abort(403);

        $sekolah = Sekolah::first();
        $setting = (object) $request->all();
        if (!isset($setting->ukuran_kertas)) $setting->ukuran_kertas = 'A4';

        if ($siswa_id) {
            $siswa = Siswa::findOrFail($siswa_id);
            if (!$rombel->siswas->contains($siswa->id)) return abort(403);
            $siswas = collect([$siswa]);
            $filename = 'Rapor_P5_'.$siswa->nisn.'.pdf';
        } else {
            $siswas = $rombel->siswas()->orderBy('nama_lengkap')->get();
            $filename = 'Rapor_P5_Kelas_'.$rombel->nama_rombel.'.pdf';
        }

        // We assume there's a view for P5 or we create one wrapper
        $pdf = Pdf::loadView('walikelas.cetak_nilai.pdf_rapor_p5', compact('siswas', 'sekolah', 'setting', 'rombel'));
        $paper = $setting->ukuran_kertas === 'F4' ? [0, 0, 609.44, 935.43] : strtolower($setting->ukuran_kertas);
        $pdf->setPaper($paper, 'portrait');

        return $pdf->stream($filename);
    }

    public function toggleRaporP5(Request $request)
    {
        return $this->togglePublikasi($request, 'is_p5_published');
    }

    // --- HELPER PUBLIKASI ---
    private function togglePublikasi(Request $request, $column)
    {
        $rombel = $this->getRombel();
        if (!$rombel) return redirect()->back()->with('error', 'Anda belum ditugaskan sebagai Wali Kelas.');

        $action = $request->get('action'); 
        $siswa_id = $request->get('siswa_id');

        if ($action === 'show_all') {
            $siswaIds = $rombel->siswas()->pluck('siswas.id')->toArray();
            Siswa::whereIn('id', $siswaIds)->update([$column => true]);
            return redirect()->back()->with('success', 'Semua file berhasil ditampilkan pada siswa.');
        } 
        
        if ($action === 'hide_all') {
            $siswaIds = $rombel->siswas()->pluck('siswas.id')->toArray();
            Siswa::whereIn('id', $siswaIds)->update([$column => false]);
            return redirect()->back()->with('success', 'Semua file berhasil disembunyikan dari siswa.');
        }

        if ($action === 'toggle' && $siswa_id) {
            $siswa = Siswa::findOrFail($siswa_id);
            if ($rombel->siswas->contains($siswa->id)) {
                $siswa->update([$column => !$siswa->$column]);
                $status = $siswa->$column ? 'ditampilkan' : 'disembunyikan';
                return redirect()->back()->with('success', "File siswa {$siswa->nama_lengkap} berhasil {$status}.");
            }
        }

        return redirect()->back();
    }
}
