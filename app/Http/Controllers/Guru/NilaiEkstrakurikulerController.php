<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Ekstrakurikuler;
use App\Models\NilaiEkstrakurikuler;
use App\Models\Rombel;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\NilaiEkstrakurikulerExport;
use App\Imports\NilaiEkstrakurikulerImport;

class NilaiEkstrakurikulerController extends Controller
{
    public function index(Request $request)
    {
        $guru = Auth::user()->guru;

        // Get ekskul taught by this guru
        $guruEkskuls = Ekstrakurikuler::where('pembina_id', $guru->id)
            ->where('is_aktif', true)
            ->get();

        $ekstrakurikuler_id = $request->query('ekstrakurikuler_id');
        $rombel_id = $request->query('rombel_id');

        // To populate rombel dropdown, we need to find which rombels have students assigned to this ekskul
        // Or simply list all rombels. The system usually shows all active rombels.
        // Let's list all active rombels or just rombels that have members in this ekskul.
        // E-rapor usually shows all rombels, but showing those with members is safer.
        $guruRombels = collect();
        if ($ekstrakurikuler_id) {
            $rombelIds = NilaiEkstrakurikuler::where('ekstrakurikuler_id', $ekstrakurikuler_id)
                ->pluck('rombel_id')
                ->unique();
            $guruRombels = Rombel::whereIn('id', $rombelIds)->get();
        }

        $ekskul = $ekstrakurikuler_id ? Ekstrakurikuler::find($ekstrakurikuler_id) : null;
        $rombel = $rombel_id ? Rombel::find($rombel_id) : null;

        $anggotaEkskul = collect();

        if ($ekstrakurikuler_id && $rombel_id) {
            $anggotaEkskul = NilaiEkstrakurikuler::with(['siswa'])
                ->where('ekstrakurikuler_id', $ekstrakurikuler_id)
                ->where('rombel_id', $rombel_id)
                ->get()
                ->sortBy('siswa.nama_lengkap');
        }

        return view('guru.nilai_ekskul.index', compact('guruEkskuls', 'guruRombels', 'ekstrakurikuler_id', 'rombel_id', 'ekskul', 'rombel', 'anggotaEkskul'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'ekstrakurikuler_id' => 'required|exists:ekstrakurikulers,id',
            'rombel_id' => 'required|exists:rombels,id',
            'nilai' => 'required|array',
        ]);

        foreach ($request->nilai as $nilai_id => $data) {
            $nilaiEkskul = NilaiEkstrakurikuler::find($nilai_id);
            if ($nilaiEkskul) {
                $nilaiEkskul->update([
                    'predikat' => $data['predikat'] ?? null,
                    'keterangan' => $data['keterangan'] ?? null,
                ]);
            }
        }

        return back()->with('success', 'Data Nilai Ekstrakurikuler berhasil disimpan!');
    }

    public function destroy($id)
    {
        $nilai = NilaiEkstrakurikuler::findOrFail($id);
        $nilai->delete();

        return back()->with('success', 'Data siswa berhasil dihapus dari daftar ekstrakurikuler.');
    }

    public function downloadFormat(Request $request)
    {
        $request->validate([
            'ekstrakurikuler_id' => 'required|exists:ekstrakurikulers,id',
            'rombel_id' => 'required|exists:rombels,id',
        ]);

        $ekskul = Ekstrakurikuler::find($request->ekstrakurikuler_id);
        $rombel = Rombel::find($request->rombel_id);

        $fileName = 'Format_Nilai_Ekskul_' . str_replace(' ', '_', $ekskul->nama_ekskul) . '_' . str_replace(' ', '_', $rombel->nama_rombel) . '.xlsx';

        return Excel::download(new NilaiEkstrakurikulerExport($request->ekstrakurikuler_id, $request->rombel_id), $fileName);
    }

    public function importStore(Request $request)
    {
        $request->validate([
            'ekstrakurikuler_id' => 'required|exists:ekstrakurikulers,id',
            'rombel_id' => 'required|exists:rombels,id',
            'file_nilai' => 'required|mimes:xls,xlsx'
        ]);

        try {
            Excel::import(new NilaiEkstrakurikulerImport($request->ekstrakurikuler_id, $request->rombel_id), $request->file('file_nilai'));
            return back()->with('success', 'Data Nilai Ekstrakurikuler berhasil diimport!');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal mengimport data. Pastikan format file sesuai. Pesan: ' . $e->getMessage());
        }
    }
}
