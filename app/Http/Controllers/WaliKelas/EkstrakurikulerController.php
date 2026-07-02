<?php

namespace App\Http\Controllers\WaliKelas;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Rombel;
use App\Models\Siswa;
use App\Models\Ekstrakurikuler;
use App\Models\NilaiEkstrakurikuler;

class EkstrakurikulerController extends Controller
{
    public function index(Request $request)
    {
        $rombel = Rombel::where('wali_kelas_id', auth()->user()->guru->id ?? 1)->first(); 
        if (!$rombel) return redirect()->back()->with('error', 'Anda belum ditugaskan sebagai Wali Kelas.');

        $siswas = $rombel->siswas()->orderBy('nama_lengkap')->get();
        $ekskuls = Ekstrakurikuler::all();
        $nilai_ekskuls = NilaiEkstrakurikuler::where('rombel_id', $rombel->id)->get()->groupBy('siswa_id');

        return view('walikelas.ekskul.index', compact('rombel', 'siswas', 'ekskuls', 'nilai_ekskuls'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'rombel_id' => 'required|exists:rombels,id',
            'data' => 'required|array',
        ]);

        foreach ($data['data'] as $siswa_id => $input) {
            if (!empty($input['ekskul_id']) && !empty($input['predikat'])) {
                NilaiEkstrakurikuler::updateOrCreate(
                    [
                        'siswa_id' => $siswa_id, 
                        'rombel_id' => $data['rombel_id'],
                        'ekstrakurikuler_id' => $input['ekskul_id']
                    ],
                    [
                        'predikat' => $input['predikat'],
                        'keterangan' => $input['keterangan'] ?? null,
                    ]
                );
            }
        }

        return redirect()->back()->with('success', 'Nilai Ekstrakurikuler berhasil disimpan!');
    }

    public function destroy($id)
    {
        $nilai = NilaiEkstrakurikuler::findOrFail($id);
        
        // Ensure the deleted record belongs to the rombel handled by this Wali Kelas
        $rombel = Rombel::where('wali_kelas_id', auth()->user()->guru->id ?? 1)->first();
        if ($rombel && $nilai->rombel_id == $rombel->id) {
            $nilai->delete();
            return redirect()->back()->with('success', 'Data Nilai Ekstrakurikuler berhasil dihapus.');
        }

        return redirect()->back()->with('error', 'Akses ditolak.');
    }

    public function importIndex(Request $request)
    {
        $rombel = Rombel::where('wali_kelas_id', auth()->user()->guru->id ?? 1)->first(); 
        if (!$rombel) return redirect()->route('walikelas.dashboard')->with('error', 'Anda belum ditugaskan sebagai Wali Kelas.');

        return view('walikelas.ekskul.import', compact('rombel'));
    }

    public function downloadFormat(Request $request)
    {
        $rombel = Rombel::where('wali_kelas_id', auth()->user()->guru->id ?? 1)->first(); 
        if (!$rombel) return redirect()->back();

        $namaFile = 'Format_Nilai_Ekskul_' . str_replace(' ', '_', $rombel->nama_rombel) . '.xlsx';
        return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\NilaiEkstrakurikulerExport($rombel->id), $namaFile);
    }

    public function importStore(Request $request)
    {
        $request->validate([
            'rombel_id' => 'required',
            'file_nilai' => 'required|mimes:xls,xlsx|max:2048'
        ]);

        try {
            \Maatwebsite\Excel\Facades\Excel::import(
                new \App\Imports\NilaiEkstrakurikulerImport($request->rombel_id),
                $request->file('file_nilai')
            );
            return redirect()->route('walikelas.ekskul.index')->with('success', 'Nilai Ekstrakurikuler berhasil diimpor.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal mengimpor file! Pastikan format sesuai dengan template.');
        }
    }
}
