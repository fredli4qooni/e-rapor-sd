<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TranskripIjazahController extends Controller
{
    // --- 1. IMPORT NOMOR IJAZAH ---
    public function importNomorIndex()
    {
        return view('admin.transkrip_ijazah.import_nomor');
    }

    public function downloadFormatNomor()
    {
        $headers = [
            'Content-type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename=format_import_nomor_ijazah.csv',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0'
        ];

        $columns = ['NISN', 'Nama Siswa', 'Nomor Ijazah Nasional', 'Nomor Transkrip Nilai', 'Tanggal Lulus (YYYY-MM-DD)'];

        $callback = function() use ($columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);
            
            // Get sample class 6 students if any
            $siswas = \App\Models\Siswa::whereHas('rombels', function($q) {
                $q->where('nama_rombel', 'like', '6%');
            })->take(5)->get();

            foreach ($siswas as $siswa) {
                fputcsv($file, [
                    $siswa->nisn,
                    $siswa->nama_lengkap,
                    $siswa->no_ijazah,
                    $siswa->no_transkrip,
                    $siswa->tgl_lulus
                ]);
            }
            
            if ($siswas->isEmpty()) {
                fputcsv($file, ['1234567890', 'Contoh Siswa', 'DN-01/D-SD/123456', 'TR-001', '2026-06-15']);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function importNomorStore(Request $request)
    {
        $request->validate([
            'file_import' => 'required|file|mimes:csv,txt'
        ]);

        $path = $request->file('file_import')->getRealPath();
        $data = array_map('str_getcsv', file($path));

        // Skip header
        array_shift($data);

        $successCount = 0;
        foreach ($data as $row) {
            if (count($row) >= 5) {
                $nisn = $row[0];
                $no_ijazah = $row[2];
                $no_transkrip = $row[3];
                $tgl_lulus = $row[4];

                if (!empty($nisn)) {
                    \App\Models\Siswa::where('nisn', $nisn)->update([
                        'no_ijazah' => $no_ijazah,
                        'no_transkrip' => $no_transkrip,
                        'tgl_lulus' => $tgl_lulus ?: null
                    ]);
                    $successCount++;
                }
            }
        }

        return redirect()->back()->with('success', "$successCount data nomor ijazah berhasil diimpor.");
    }

    // --- 2. SETTING TAMPILAN TRANSKRIP ---
    public function settingIndex()
    {
        // For simplicity, we assume one sekolah_id = 1
        $sekolah = \App\Models\Sekolah::first();
        if (!$sekolah) abort(404, 'Data Sekolah tidak ditemukan');

        $setting = \App\Models\SettingTranskrip::firstOrCreate(
            ['sekolah_id' => $sekolah->id],
            [
                'tampilan_nama_siswa' => 'huruf_kapital',
                'jumlah_angka_desimal' => 0,
                'tampilkan_baris_rata_rata' => true,
                'angka_desimal_rata_rata' => 2,
                'tempat_tanggal_transkrip' => 'Jakarta, ' . date('d M Y'),
                'nama_kepala_sekolah' => 'Nama Kepsek S.Pd',
                'nip_kepala_sekolah' => '1234567890',
                'tampilkan_ttd_kepala_sekolah' => true,
                'ukuran_kertas' => 'A4',
                'margin_kiri' => 15,
                'margin_kanan' => 15,
                'margin_atas' => 15,
                'margin_bawah' => 15,
                'jarak_antar_identitas' => 5,
                'tinggi_judul' => 10,
                'tinggi_isi_tabel' => 8,
                'persentase_kop' => 100
            ]
        );

        return view('admin.transkrip_ijazah.setting', compact('setting'));
    }

    public function settingStore(Request $request)
    {
        $request->validate([
            'tampilan_nama_siswa' => 'required',
            'jumlah_angka_desimal' => 'required|integer',
            'angka_desimal_rata_rata' => 'required|integer',
            'tempat_tanggal_transkrip' => 'required',
            'nama_kepala_sekolah' => 'required',
            'ukuran_kertas' => 'required',
            'margin_kiri' => 'required|integer',
            'margin_kanan' => 'required|integer',
            'margin_atas' => 'required|integer',
            'margin_bawah' => 'required|integer',
        ]);

        $sekolah = \App\Models\Sekolah::first();
        $setting = \App\Models\SettingTranskrip::where('sekolah_id', $sekolah->id)->first();
        
        $setting->update([
            'tampilan_nama_siswa' => $request->tampilan_nama_siswa,
            'jumlah_angka_desimal' => $request->jumlah_angka_desimal,
            'tampilkan_baris_rata_rata' => $request->has('tampilkan_baris_rata_rata'),
            'angka_desimal_rata_rata' => $request->angka_desimal_rata_rata,
            'tempat_tanggal_transkrip' => $request->tempat_tanggal_transkrip,
            'nama_kepala_sekolah' => $request->nama_kepala_sekolah,
            'nip_kepala_sekolah' => $request->nip_kepala_sekolah,
            'tampilkan_ttd_kepala_sekolah' => $request->has('tampilkan_ttd_kepala_sekolah'),
            'ukuran_kertas' => $request->ukuran_kertas,
            'margin_kiri' => $request->margin_kiri,
            'margin_kanan' => $request->margin_kanan,
            'margin_atas' => $request->margin_atas,
            'margin_bawah' => $request->margin_bawah,
            'jarak_antar_identitas' => $request->jarak_antar_identitas ?? 5,
            'tinggi_judul' => $request->tinggi_judul ?? 10,
            'tinggi_isi_tabel' => $request->tinggi_isi_tabel ?? 8,
            'persentase_kop' => $request->persentase_kop ?? 100
        ]);

        return redirect()->back()->with('success', 'Setting Tampilan Transkrip berhasil disimpan.');
    }

    // --- 3. MAPPING MATA PELAJARAN ---
    public function mappingIndex(Request $request)
    {
        $kurikulum = $request->get('kurikulum', 'Merdeka');
        $tingkat = $request->get('tingkat', 6);

        $mapels = \App\Models\MataPelajaran::where('is_transkrip', true)->get();
        
        $mappings = \App\Models\MappingTranskrip::with('mapel')
                        ->where('kurikulum', $kurikulum)
                        ->where('tingkat', $tingkat)
                        ->orderBy('kelompok')
                        ->orderBy('no_urut')
                        ->get();

        return view('admin.transkrip_ijazah.mapping_mapel', compact('kurikulum', 'tingkat', 'mapels', 'mappings'));
    }

    public function mappingStore(Request $request)
    {
        $request->validate([
            'mata_pelajaran_id' => 'required|exists:mata_pelajarans,id',
            'tingkat' => 'required|integer',
            'kurikulum' => 'required|string',
            'nama_lokal' => 'nullable|string',
            'kelompok' => 'nullable|string',
            'no_urut' => 'required|integer'
        ]);

        \App\Models\MappingTranskrip::updateOrCreate(
            [
                'mata_pelajaran_id' => $request->mata_pelajaran_id,
                'tingkat' => $request->tingkat,
                'kurikulum' => $request->kurikulum
            ],
            [
                'nama_lokal' => $request->nama_lokal,
                'kelompok' => $request->kelompok,
                'no_urut' => $request->no_urut
            ]
        );

        return redirect()->back()->with('success', 'Mapping Mata Pelajaran berhasil disimpan.');
    }

    public function mappingDestroy($id)
    {
        $mapping = \App\Models\MappingTranskrip::findOrFail($id);
        $mapping->delete();

        return redirect()->back()->with('success', 'Mapping Mata Pelajaran berhasil dihapus.');
    }

    // --- 4. INPUT / IMPORT NILAI ---
   public function inputNilaiIndex(Request $request)
    {
        $rombel_id = $request->get('rombel_id');
        $mata_pelajaran_id = $request->get('mata_pelajaran_id');

        $rombels = \App\Models\Rombel::where('nama_rombel', 'like', '6%')->get();
        $mapels = \App\Models\MataPelajaran::where('is_transkrip', true)->get();

        $siswas = [];
        $nilaiTranskrip = [];

        if ($rombel_id && $mata_pelajaran_id) {
            $siswas = \App\Models\Siswa::whereHas('rombels', function($q) use ($rombel_id) {
                $q->where('rombels.id', $rombel_id);
            })->orderBy('nama_lengkap')->get();

            $siswaIds = $siswas->pluck('id')->toArray();
            
            $nilaiList = \App\Models\NilaiTranskrip::whereIn('siswa_id', $siswaIds)
                            ->where('mata_pelajaran_id', $mata_pelajaran_id)
                            ->get()
                            ->keyBy('siswa_id');

            foreach ($siswas as $siswa) {
                $nilaiTranskrip[$siswa->id] = isset($nilaiList[$siswa->id]) ? $nilaiList[$siswa->id]->nilai : null;
            }
        }

        return view('admin.transkrip_ijazah.input_nilai', compact('rombels', 'mapels', 'rombel_id', 'mata_pelajaran_id', 'siswas', 'nilaiTranskrip'));
    }

    public function inputNilaiStore(Request $request)
    {
        $request->validate([
            'mata_pelajaran_id' => 'required|exists:mata_pelajarans,id',
            'nilai' => 'required|array',
            'nilai.*' => 'nullable|integer|min:0|max:100',
        ]);

        $mata_pelajaran_id = $request->mata_pelajaran_id;

        foreach ($request->nilai as $siswa_id => $nilai) {
            if ($nilai !== null) {
                \App\Models\NilaiTranskrip::updateOrCreate(
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

    public function importNilaiIndex(Request $request)
    {
        $rombels = \App\Models\Rombel::where('nama_rombel', 'like', '6%')->get();
        $mapels = \App\Models\MataPelajaran::where('is_transkrip', true)->get();

        return view('admin.transkrip_ijazah.import_nilai', compact('rombels', 'mapels'));
    }

    public function downloadFormatNilai(Request $request)
    {
        $request->validate([
            'rombel_id' => 'required|exists:rombels,id',
            'mata_pelajaran_id' => 'required|exists:mata_pelajarans,id'
        ]);

        $rombel = \App\Models\Rombel::findOrFail($request->rombel_id);
        $mapel = \App\Models\MataPelajaran::findOrFail($request->mata_pelajaran_id);

        $headers = [
            'Content-type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename=Format_Nilai_Transkrip_'.$rombel->nama_rombel.'_'.str_replace(' ', '_', $mapel->nama_mapel).'.csv',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0'
        ];

        $columns = ['ID Siswa (JANGAN DIUBAH)', 'NISN', 'Nama Siswa', 'Nilai (0-100)'];

        $callback = function() use ($columns, $rombel, $mapel) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);
            
            $siswas = \App\Models\Siswa::whereHas('rombels', function($q) use ($rombel) {
                $q->where('rombels.id', $rombel->id);
            })->orderBy('nama_lengkap')->get();

            $nilaiList = \App\Models\NilaiTranskrip::whereIn('siswa_id', $siswas->pluck('id'))
                            ->where('mata_pelajaran_id', $mapel->id)
                            ->get()
                            ->keyBy('siswa_id');

            foreach ($siswas as $siswa) {
                $nilai = isset($nilaiList[$siswa->id]) ? $nilaiList[$siswa->id]->nilai : '';
                fputcsv($file, [
                    $siswa->id,
                    $siswa->nisn,
                    $siswa->nama_lengkap,
                    $nilai
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function importNilaiStore(Request $request)
    {
        $request->validate([
            'mata_pelajaran_id' => 'required|exists:mata_pelajarans,id',
            'file_import' => 'required|file|mimes:csv,txt'
        ]);

        $mata_pelajaran_id = $request->mata_pelajaran_id;
        $path = $request->file('file_import')->getRealPath();
        $data = array_map('str_getcsv', file($path));

        // Skip header
        array_shift($data);

        $successCount = 0;
        foreach ($data as $row) {
            if (count($row) >= 4) {
                $siswa_id = $row[0];
                $nilai = $row[3];

                if (!empty($siswa_id) && is_numeric($siswa_id) && is_numeric($nilai) && $nilai >= 0 && $nilai <= 100) {
                    \App\Models\NilaiTranskrip::updateOrCreate(
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

    // --- 5. CETAK TRANSKRIP ---
    public function cetakIndex(Request $request)
    {
        $rombel_id = $request->get('rombel_id');
        $rombels = \App\Models\Rombel::where('nama_rombel', 'like', '6%')->get();
        $sekolah = \App\Models\Sekolah::first();
        
        $setting = \App\Models\SettingTranskrip::where('sekolah_id', $sekolah->id ?? 1)->first();

        $siswas = [];
        if ($rombel_id) {
            $siswas = \App\Models\Siswa::whereHas('rombels', function($q) use ($rombel_id) {
                $q->where('rombels.id', $rombel_id);
            })->orderBy('nama_lengkap')->get();
        }

        return view('admin.transkrip_ijazah.cetak', compact('rombels', 'rombel_id', 'siswas', 'setting'));
    }

    public function generateSiswa($siswa_id)
    {
        $siswa = \App\Models\Siswa::findOrFail($siswa_id);
        $sekolah = \App\Models\Sekolah::first();
        $setting = \App\Models\SettingTranskrip::where('sekolah_id', $sekolah->id ?? 1)->first();
        
        // Asumsi kurikulum kelas 6 adalah merdeka
        $mappings = \App\Models\MappingTranskrip::with('mapel')
                        ->where('tingkat', 6)
                        ->orderBy('kelompok')
                        ->orderBy('no_urut')
                        ->get();

        $nilaiTranskrip = \App\Models\NilaiTranskrip::where('siswa_id', $siswa_id)
                            ->get()
                            ->keyBy('mata_pelajaran_id');

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.transkrip_ijazah.pdf', compact('siswa', 'sekolah', 'setting', 'mappings', 'nilaiTranskrip'));
        
        $paper = $setting->ukuran_kertas === 'F4' ? [0, 0, 609.44, 935.43] : 'a4'; // F4 custom size, A4 is standard
        $pdf->setPaper($paper, 'portrait');

        return $pdf->stream('Transkrip_Ijazah_'.$siswa->nisn.'_'.str_replace(' ', '_', $siswa->nama_lengkap).'.pdf');
    }

    public function generateKelas($rombel_id)
    {
        $rombel = \App\Models\Rombel::findOrFail($rombel_id);
        $siswas = \App\Models\Siswa::whereHas('rombels', function($q) use ($rombel_id) {
            $q->where('rombels.id', $rombel_id);
        })->orderBy('nama_lengkap')->get();

        $sekolah = \App\Models\Sekolah::first();
        $setting = \App\Models\SettingTranskrip::where('sekolah_id', $sekolah->id ?? 1)->first();
        
        $mappings = \App\Models\MappingTranskrip::with('mapel')
                        ->where('tingkat', 6)
                        ->orderBy('kelompok')
                        ->orderBy('no_urut')
                        ->get();

        $siswaIds = $siswas->pluck('id')->toArray();
        $semuaNilai = \App\Models\NilaiTranskrip::whereIn('siswa_id', $siswaIds)
                            ->get()
                            ->groupBy('siswa_id');

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.transkrip_ijazah.pdf_kelas', compact('siswas', 'sekolah', 'setting', 'mappings', 'semuaNilai'));
        
        $paper = $setting->ukuran_kertas === 'F4' ? [0, 0, 609.44, 935.43] : 'a4';
        $pdf->setPaper($paper, 'portrait');

        return $pdf->stream('Transkrip_Ijazah_Kelas_'.$rombel->nama_rombel.'.pdf');
    }
}
