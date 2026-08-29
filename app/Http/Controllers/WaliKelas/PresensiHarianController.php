<?php

namespace App\Http\Controllers\WaliKelas;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Rombel;
use App\Models\Siswa;
use App\Models\Semester;
use App\Models\PresensiHarian;
use Carbon\Carbon;

class PresensiHarianController extends Controller
{
    public function index(Request $request)
    {
        $guruId = auth()->user()->guru->id ?? null;
        $semesterAktif = Semester::where('is_aktif', true)->first();

        if (!$semesterAktif) {
            return redirect()->route('walikelas.dashboard')->with('error', 'Tidak ada semester aktif.');
        }

        $rombel = Rombel::where('wali_kelas_id', $guruId)
            ->where('semester_id', $semesterAktif->id)
            ->first();

        if (!$rombel) {
            return redirect()->route('walikelas.dashboard')->with('error', 'Anda belum ditugaskan sebagai Wali Kelas pada semester ini.');
        }

        $tanggal = $request->query('tanggal', date('Y-m-d'));
        $siswas = $rombel->siswas()->orderBy('nama_lengkap')->get();

        $presensis = PresensiHarian::where('rombel_id', $rombel->id)
            ->where('tanggal', $tanggal)
            ->get()
            ->keyBy('siswa_id');

        // Statistik hari yang dipilih
        $countHadir = $presensis->where('status', 'H')->count();
        $countSakit = $presensis->where('status', 'S')->count();
        $countIzin = $presensis->where('status', 'I')->count();
        $countAlpa = $presensis->where('status', 'A')->count();
        $countBelum = $siswas->count() - $presensis->count();

        return view('walikelas.presensi_harian.index', compact(
            'rombel',
            'semesterAktif',
            'tanggal',
            'siswas',
            'presensis',
            'countHadir',
            'countSakit',
            'countIzin',
            'countAlpa',
            'countBelum'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'rombel_id' => 'required|exists:rombels,id',
            'tanggal' => 'required|date',
            'presensi' => 'required|array',
        ]);

        $semesterAktif = Semester::where('is_aktif', true)->first();
        $tanggal = $request->tanggal;

        foreach ($request->presensi as $siswaId => $data) {
            $status = $data['status'] ?? 'H';
            $keterangan = $data['keterangan'] ?? null;

            PresensiHarian::updateOrCreate(
                [
                    'siswa_id' => $siswaId,
                    'tanggal' => $tanggal,
                ],
                [
                    'rombel_id' => $request->rombel_id,
                    'semester_id' => $semesterAktif->id,
                    'status' => $status,
                    'keterangan' => $keterangan,
                ]
            );
        }

        $formattedDate = Carbon::parse($tanggal)->translatedFormat('d F Y');
        return redirect()->route('walikelas.presensi.index', ['tanggal' => $tanggal])
            ->with('success', "Data presensi harian tanggal {$formattedDate} berhasil disimpan!");
    }
}
