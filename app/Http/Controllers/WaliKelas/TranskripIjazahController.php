<?php

namespace App\Http\Controllers\WaliKelas;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Rombel;
use App\Models\Siswa;
use App\Models\MataPelajaran;
use App\Models\NilaiTranskrip;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;

class TranskripIjazahController extends Controller
{
    private function getRombel()
    {
        $rombel = Rombel::where('wali_kelas_id', auth()->user()->guru->id ?? 1)->first();
        return $rombel;
    }

    // --- 1. INPUT NILAI TRANSKRIP ---
    public function inputNilai(Request $request)
    {
        $rombel = $this->getRombel();
        if (!$rombel) return redirect()->back()->with('error', 'Anda belum ditugaskan sebagai Wali Kelas.');
        
        // Only for kelas 6 based on guidance "wali kelas tingkat akhir"
        if (!str_starts_with($rombel->nama_rombel, '6')) {
            return redirect()->back()->with('error', 'Fitur Transkrip Ijazah hanya untuk Wali Kelas tingkat akhir (Kelas 6).');
        }

        $mata_pelajaran_id = $request->get('mata_pelajaran_id');

        $mapels = MataPelajaran::where('is_transkrip', true)->get();

        $siswas = [];
        $nilaiTranskrip = [];

        if ($mata_pelajaran_id) {
            $siswas = $rombel->siswas()->orderBy('nama_lengkap')->get();
            $siswaIds = $siswas->pluck('id')->toArray();
            
            $nilaiList = NilaiTranskrip::whereIn('siswa_id', $siswaIds)
                            ->where('mata_pelajaran_id', $mata_pelajaran_id)
                            ->get()
                            ->keyBy('siswa_id');

            foreach ($siswas as $siswa) {
                $nilaiTranskrip[$siswa->id] = isset($nilaiList[$siswa->id]) ? $nilaiList[$siswa->id]->nilai : null;
            }
        }

        return view('walikelas.transkrip_ijazah.input_nilai', compact('rombel', 'mapels', 'mata_pelajaran_id', 'siswas', 'nilaiTranskrip'));
    }

    public function storeNilai(Request $request)
    {
        $request->validate([
            'mata_pelajaran_id' => 'required|exists:mata_pelajarans,id',
            'nilai' => 'required|array',
            'nilai.*' => 'nullable|integer|min:0|max:100',
        ]);

        $mata_pelajaran_id = $request->mata_pelajaran_id;

        foreach ($request->nilai as $siswa_id => $nilai) {
            if ($nilai !== null) {
                NilaiTranskrip::updateOrCreate(
                    [
                        'siswa_id' => $siswa_id,
                        'mata_pelajaran_id' => $mata_pelajaran_id
                    ],
                    [
                        'nilai' => $nilai
                    ]
                );
            }
        }

        return redirect()->back()->with('success', 'Nilai transkrip berhasil disimpan.');
    }

    // --- 2. IMPORT NILAI TRANSKRIP ---
    public function importNilai(Request $request)
    {
        $rombel = $this->getRombel();
        if (!$rombel) return redirect()->back()->with('error', 'Anda belum ditugaskan sebagai Wali Kelas.');

        if (!str_starts_with($rombel->nama_rombel, '6')) {
            return redirect()->back()->with('error', 'Fitur Transkrip Ijazah hanya untuk Wali Kelas tingkat akhir (Kelas 6).');
        }

        $mapels = MataPelajaran::where('is_transkrip', true)->get();

        return view('walikelas.transkrip_ijazah.import_nilai', compact('rombel', 'mapels'));
    }

    public function downloadFormat(Request $request)
    {
        $request->validate([
            'mata_pelajaran_id' => 'required|exists:mata_pelajarans,id'
        ]);

        $rombel = $this->getRombel();
        if (!$rombel) return redirect()->back()->with('error', 'Akses ditolak.');

        $mapel = MataPelajaran::findOrFail($request->mata_pelajaran_id);

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        $sheet->setCellValue('A1', 'ID Siswa (JANGAN DIUBAH)');
        $sheet->setCellValue('B1', 'NISN');
        $sheet->setCellValue('C1', 'Nama Siswa');
        $sheet->setCellValue('D1', 'Nilai (0-100)');

        $siswas = $rombel->siswas()->orderBy('nama_lengkap')->get();
        $nilaiList = NilaiTranskrip::whereIn('siswa_id', $siswas->pluck('id'))
                        ->where('mata_pelajaran_id', $mapel->id)
                        ->get()
                        ->keyBy('siswa_id');

        $row = 2;
        foreach ($siswas as $siswa) {
            $nilai = isset($nilaiList[$siswa->id]) ? $nilaiList[$siswa->id]->nilai : '';
            $sheet->setCellValue('A' . $row, $siswa->id);
            $sheet->setCellValue('B' . $row, $siswa->nisn);
            $sheet->setCellValue('C' . $row, $siswa->nama_lengkap);
            $sheet->setCellValue('D' . $row, $nilai);
            $row++;
        }

        $writer = new Xlsx($spreadsheet);
        $filename = 'Format_Nilai_Transkrip_'.$rombel->nama_rombel.'_'.str_replace(' ', '_', $mapel->nama_mapel).'.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="'. $filename .'"');
        header('Cache-Control: max-age=0');
        
        $writer->save('php://output');
        exit;
    }

    public function processImport(Request $request)
    {
        $request->validate([
            'mata_pelajaran_id' => 'required|exists:mata_pelajarans,id',
            'file_import' => 'required|file|mimes:xlsx,xls'
        ]);

        $mata_pelajaran_id = $request->mata_pelajaran_id;
        $path = $request->file('file_import')->getRealPath();
        
        $spreadsheet = IOFactory::load($path);
        $sheet = $spreadsheet->getActiveSheet();
        $rows = $sheet->toArray();

        // Skip header
        array_shift($rows);

        $successCount = 0;
        foreach ($rows as $row) {
            if (count($row) >= 4) {
                $siswa_id = $row[0];
                $nilai = $row[3];

                if (!empty($siswa_id) && is_numeric($siswa_id) && is_numeric($nilai) && $nilai >= 0 && $nilai <= 100) {
                    NilaiTranskrip::updateOrCreate(
                        [
                            'siswa_id' => $siswa_id,
                            'mata_pelajaran_id' => $mata_pelajaran_id
                        ],
                        [
                            'nilai' => $nilai
                        ]
                    );
                    $successCount++;
                }
            }
        }

        return redirect()->back()->with('success', "$successCount data nilai transkrip berhasil diimpor.");
    }

    // --- 3. CETAK TRANSKRIP ---
    public function cetak(Request $request)
    {
        $rombel = $this->getRombel();
        if (!$rombel) return redirect()->back()->with('error', 'Anda belum ditugaskan sebagai Wali Kelas.');

        if (!str_starts_with($rombel->nama_rombel, '6')) {
            return redirect()->back()->with('error', 'Fitur Transkrip Ijazah hanya untuk Wali Kelas tingkat akhir (Kelas 6).');
        }

        $sekolah = \App\Models\Sekolah::first();
        $setting = \App\Models\SettingTranskrip::where('sekolah_id', $sekolah->id ?? 1)->first();

        // Fallback setting if not exist
        if (!$setting) {
            $setting = (object)[
                'ukuran_kertas' => 'A4',
                'margin_kiri' => 20,
                'margin_kanan' => 20,
                'margin_atas' => 20,
                'margin_bawah' => 10,
                'jarak_antar_identitas' => 7,
                'tinggi_judul' => 8,
                'tinggi_isi_tabel' => 6,
                'persentase_kop' => 100
            ];
        }

        $siswas = $rombel->siswas()->orderBy('nama_lengkap')->get();

        return view('walikelas.transkrip_ijazah.cetak', compact('rombel', 'siswas', 'setting'));
    }

    public function generatePdf(Request $request, $siswa_id = null)
    {
        $rombel = $this->getRombel();
        if (!$rombel) return abort(403);

        $sekolah = \App\Models\Sekolah::first();
        $setting = \App\Models\SettingTranskrip::where('sekolah_id', $sekolah->id ?? 1)->first();
        
        // Save the setting overrides from print form if any, or use existing
        if ($request->has('ukuran_kertas')) {
            $setting = \App\Models\SettingTranskrip::updateOrCreate(
                ['sekolah_id' => $sekolah->id ?? 1],
                [
                    'ukuran_kertas' => $request->ukuran_kertas,
                    'margin_kiri' => $request->margin_kiri,
                    'margin_kanan' => $request->margin_kanan,
                    'margin_atas' => $request->margin_atas,
                    'margin_bawah' => $request->margin_bawah,
                    'jarak_antar_identitas' => $request->jarak_antar_identitas,
                    'tinggi_judul' => $request->tinggi_judul,
                    'tinggi_isi_tabel' => $request->tinggi_isi_tabel,
                    'persentase_kop' => $request->persentase_kop,
                ]
            );
        }

        $mappings = \App\Models\MappingTranskrip::with('mapel')
                        ->where('tingkat', 6)
                        ->orderBy('kelompok')
                        ->orderBy('no_urut')
                        ->get();

        if ($mappings->isEmpty()) {
            $mapels = \App\Models\MataPelajaran::where('is_transkrip', true)->get();
            $mappings = $mapels->map(function ($mapel, $index) {
                return (object)[
                    'mata_pelajaran_id' => $mapel->id,
                    'mapel' => $mapel,
                    'kelompok' => 'Semua Mata Pelajaran',
                    'no_urut' => $index + 1,
                    'nama_lokal' => null,
                ];
            });
        }

        if ($siswa_id) {
            $siswa = Siswa::findOrFail($siswa_id);
            // Verify siswa belongs to this rombel
            if (!$rombel->siswas->contains($siswa->id)) return abort(403);

            $nilaiTranskrip = NilaiTranskrip::where('siswa_id', $siswa_id)
                                ->get()
                                ->keyBy('mata_pelajaran_id');

            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.transkrip_ijazah.pdf', compact('siswa', 'sekolah', 'setting', 'mappings', 'nilaiTranskrip'));
            $paper = $setting->ukuran_kertas === 'F4' ? [0, 0, 609.44, 935.43] : 'a4';
            $pdf->setPaper($paper, 'portrait');

            return $pdf->stream('Transkrip_Ijazah_'.$siswa->nisn.'_'.str_replace(' ', '_', $siswa->nama_lengkap).'.pdf');
        } else {
            // Mass generate
            $siswas = $rombel->siswas()->orderBy('nama_lengkap')->get();
            $siswaIds = $siswas->pluck('id')->toArray();
            $semuaNilai = NilaiTranskrip::whereIn('siswa_id', $siswaIds)
                                ->get()
                                ->groupBy('siswa_id');

            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.transkrip_ijazah.pdf_kelas', compact('siswas', 'sekolah', 'setting', 'mappings', 'semuaNilai'));
            $paper = $setting->ukuran_kertas === 'F4' ? [0, 0, 609.44, 935.43] : 'a4';
            $pdf->setPaper($paper, 'portrait');

            return $pdf->stream('Transkrip_Ijazah_Kelas_'.$rombel->nama_rombel.'.pdf');
        }
    }

    public function togglePublikasi(Request $request)
    {
        $rombel = $this->getRombel();
        if (!$rombel) return redirect()->back()->with('error', 'Anda belum ditugaskan sebagai Wali Kelas.');

        $action = $request->get('action'); // 'show_all', 'hide_all', 'toggle'
        $siswa_id = $request->get('siswa_id');

        if ($action === 'show_all') {
            $siswaIds = $rombel->siswas()->pluck('siswas.id')->toArray();
            Siswa::whereIn('id', $siswaIds)->update(['is_transkrip_published' => true]);
            return redirect()->back()->with('success', 'Semua transkrip kelas berhasil ditampilkan pada siswa.');
        } 
        
        if ($action === 'hide_all') {
            $siswaIds = $rombel->siswas()->pluck('siswas.id')->toArray();
            Siswa::whereIn('id', $siswaIds)->update(['is_transkrip_published' => false]);
            return redirect()->back()->with('success', 'Semua transkrip kelas berhasil disembunyikan dari siswa.');
        }

        if ($action === 'toggle' && $siswa_id) {
            $siswa = Siswa::findOrFail($siswa_id);
            if ($rombel->siswas->contains($siswa->id)) {
                $siswa->update(['is_transkrip_published' => !$siswa->is_transkrip_published]);
                $status = $siswa->is_transkrip_published ? 'ditampilkan' : 'disembunyikan';
                return redirect()->back()->with('success', "Transkrip nilai siswa {$siswa->nama_lengkap} berhasil {$status}.");
            }
        }

        return redirect()->back();
    }
}
