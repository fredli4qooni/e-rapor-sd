<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class StatusPenilaianController extends Controller
{
    public function index(Request $request)
    {
        $rombels = \App\Models\Rombel::all();
        $selectedRombelId = $request->get('rombel_id');
        
        $mapels = [];
        if ($selectedRombelId) {
            $rombel = \App\Models\Rombel::with('siswas')->find($selectedRombelId);
            if ($rombel) {
                // Assuming all mapels for simplicity, or we can use mapel_rombels pivot if it exists
                // Let's get all Mapel that are taught in this Rombel's grade level.
                // In e-Rapor, typically mapels are linked to rombel via Pembelajaran.
                $mapels = \App\Models\Pembelajaran::with('mapel', 'guru')
                            ->where('rombel_id', $selectedRombelId)
                            ->get()
                            ->map(function ($pembelajaran) use ($rombel) {
                                $totalSiswa = $rombel->siswas->count();
                                $siswaDinilai = \App\Models\NilaiRapor::where('mata_pelajaran_id', $pembelajaran->mata_pelajaran_id)
                                    ->whereIn('siswa_id', $rombel->siswas->pluck('id'))
                                    ->count();
                                    
                                return (object)[
                                    'id' => $pembelajaran->mata_pelajaran_id,
                                    'nama_mapel' => $pembelajaran->mapel->nama_mapel ?? 'Unknown',
                                    'guru' => $pembelajaran->guru->name ?? 'Unknown',
                                    'total_siswa' => $totalSiswa,
                                    'siswa_dinilai' => $siswaDinilai,
                                    'status' => ($totalSiswa > 0 && $siswaDinilai >= $totalSiswa) ? 'Lengkap' : 'Belum Lengkap'
                                ];
                            });
            }
        }

        return view('admin.status_penilaian.index', compact('rombels', 'selectedRombelId', 'mapels'));
    }

    public function statistikRapor(Request $request)
    {
        $rombels = \App\Models\Rombel::all();
        $selectedRombelId = $request->get('rombel_id');
        
        $statistik = [];
        $chartData = [];
        if ($selectedRombelId) {
            $rombel = \App\Models\Rombel::with('siswas')->find($selectedRombelId);
            if ($rombel) {
                $pembelajarans = \App\Models\Pembelajaran::with('mapel')
                                    ->where('rombel_id', $selectedRombelId)
                                    ->get();
                                    
                $siswaIds = $rombel->siswas->pluck('id');

                foreach ($pembelajarans as $pembelajaran) {
                    $nilais = \App\Models\NilaiRapor::where('mata_pelajaran_id', $pembelajaran->mata_pelajaran_id)
                                ->whereIn('siswa_id', $siswaIds)
                                ->pluck('nilai_akhir');

                    if ($nilais->count() > 0) {
                        $avg = round($nilais->avg(), 2);
                        $max = $nilais->max();
                        $min = $nilais->min();
                        
                        $statistik[] = (object)[
                            'nama_mapel' => $pembelajaran->mapel->nama_mapel ?? 'Unknown',
                            'rata_rata' => $avg,
                            'tertinggi' => $max,
                            'terendah' => $min
                        ];
                        
                        $chartData['labels'][] = $pembelajaran->mapel->nama_mapel ?? 'Unknown';
                        $chartData['data'][] = $avg;
                    }
                }
            }
        }

        return view('admin.status_penilaian.statistik_rapor', compact('rombels', 'selectedRombelId', 'statistik', 'chartData'));
    }

    public function statistikP3(Request $request)
    {
        $rombels = \App\Models\Rombel::all();
        $selectedRombelId = $request->get('rombel_id');
        
        $statistik = [];
        $chartData = [
            'labels' => ['BB (Belum Berkembang)', 'MB (Mulai Berkembang)', 'BSH (Berkembang Sesuai Harapan)', 'SB (Sangat Berkembang)'],
            'data' => [0, 0, 0, 0]
        ];

        if ($selectedRombelId) {
            $rombel = \App\Models\Rombel::with('siswas')->find($selectedRombelId);
            if ($rombel) {
                $siswaIds = $rombel->siswas->pluck('id');
                
                // Get all P5 scores for these students
                $nilais = \App\Models\P5Nilai::whereIn('siswa_id', $siswaIds)->get();
                
                $chartData['data'][0] = $nilais->where('capaian', 'BB')->count();
                $chartData['data'][1] = $nilais->where('capaian', 'MB')->count();
                $chartData['data'][2] = $nilais->where('capaian', 'BSH')->count();
                $chartData['data'][3] = $nilais->where('capaian', 'SB')->count();
                
                // Group by Sub Elemen
                $subElemens = \App\Models\P5SubElemen::whereIn('id', $nilais->pluck('p5_sub_elemen_id')->unique())->get();
                foreach($subElemens as $sub) {
                    $subNilais = $nilais->where('p5_sub_elemen_id', $sub->id);
                    $statistik[] = (object)[
                        'sub_elemen' => $sub->deskripsi,
                        'bb' => $subNilais->where('capaian', 'BB')->count(),
                        'mb' => $subNilais->where('capaian', 'MB')->count(),
                        'bsh' => $subNilais->where('capaian', 'BSH')->count(),
                        'sb' => $subNilais->where('capaian', 'SB')->count(),
                    ];
                }
            }
        }

        return view('admin.status_penilaian.statistik_p3', compact('rombels', 'selectedRombelId', 'statistik', 'chartData'));
    }
}
