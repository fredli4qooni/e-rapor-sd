<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PerkembanganNilaiController extends Controller
{
    public function index(Request $request)
    {
        $rombels = \App\Models\Rombel::all();
        $selectedRombelId = $request->get('rombel_id');
        $siswas = [];

        if ($selectedRombelId) {
            $rombel = \App\Models\Rombel::with('siswas')->find($selectedRombelId);
            if ($rombel) {
                $siswas = $rombel->siswas;
            }
        }

        return view('admin.perkembangan_nilai.index', compact('rombels', 'selectedRombelId', 'siswas'));
    }

    public function capaian($siswa_id)
    {
        $siswa = \App\Models\Siswa::findOrFail($siswa_id);
        
        // Get all NilaiRapor for this student, ordered by semester
        $nilais = \App\Models\NilaiRapor::with(['semester', 'mapel'])
                    ->where('siswa_id', $siswa_id)
                    ->get();
        
        // Group by mapel, then pivot by semester
        $capaianData = [];
        $semesters = [];
        
        foreach ($nilais as $nilai) {
            if ($nilai->semester) {
                $semesters[$nilai->semester_id] = $nilai->semester->nama_semester . ' (' . $nilai->semester->tahun_ajaran . ')';
            }
            $mapelName = $nilai->mapel->nama_mapel ?? 'Unknown';
            $capaianData[$mapelName][$nilai->semester_id] = $nilai->nilai_akhir;
        }

        // Sort semesters
        asort($semesters);

        return view('admin.perkembangan_nilai.capaian', compact('siswa', 'capaianData', 'semesters'));
    }

    public function deskripsi($siswa_id)
    {
        $siswa = \App\Models\Siswa::findOrFail($siswa_id);
        
        // Get all NilaiRapor with DeskripsiRapor for this student
        $nilais = \App\Models\NilaiRapor::with(['semester', 'mapel'])
                    ->where('siswa_id', $siswa_id)
                    ->get();
        
        $deskripsiData = [];
        $semesters = [];
        
        foreach ($nilais as $nilai) {
            if ($nilai->semester) {
                $semesters[$nilai->semester_id] = $nilai->semester->nama_semester . ' (' . $nilai->semester->tahun_ajaran . ')';
            }
            $mapelName = $nilai->mapel->nama_mapel ?? 'Unknown';
            
            // Try to get from DeskripsiRapor if exists, otherwise fallback to tp_tertinggi/tp_terendah
            $deskRapor = \App\Models\DeskripsiRapor::where('nilai_rapor_id', $nilai->id)->first();
            
            $tertinggi = '';
            $terendah = '';
            
            if ($deskRapor) {
                $tertinggi = $deskRapor->deskripsi_tertinggi;
                $terendah = $deskRapor->deskripsi_terendah;
            } else {
                // If storing JSON in tp_tertinggi/tp_terendah
                if ($nilai->tp_tertinggi) {
                    $tps = is_array($nilai->tp_tertinggi) ? $nilai->tp_tertinggi : json_decode($nilai->tp_tertinggi, true);
                    if(is_array($tps)) {
                        $tertinggi = "Menunjukkan penguasaan yang sangat baik dalam: " . implode(', ', array_column($tps, 'deskripsi'));
                    }
                }
                if ($nilai->tp_terendah) {
                    $tps = is_array($nilai->tp_terendah) ? $nilai->tp_terendah : json_decode($nilai->tp_terendah, true);
                    if(is_array($tps)) {
                        $terendah = "Perlu bimbingan dalam: " . implode(', ', array_column($tps, 'deskripsi'));
                    }
                }
            }
            
            $deskripsiData[$mapelName][$nilai->semester_id] = [
                'tertinggi' => $tertinggi,
                'terendah' => $terendah
            ];
        }

        asort($semesters);

        return view('admin.perkembangan_nilai.deskripsi', compact('siswa', 'deskripsiData', 'semesters'));
    }

    public function grafik(Request $request)
    {
        $rombels = \App\Models\Rombel::all();
        $selectedRombelId = $request->get('rombel_id');
        $selectedSiswaId = $request->get('siswa_id'); // 'all' or specific id
        
        $siswas = [];
        $grafikData = [];
        $tabelData = [];
        $semesters = [];
        
        if ($selectedRombelId) {
            $rombel = \App\Models\Rombel::with('siswas')->find($selectedRombelId);
            if ($rombel) {
                $siswas = $rombel->siswas;
                
                $query = \App\Models\NilaiRapor::with(['semester', 'mapel']);
                
                if ($selectedSiswaId && $selectedSiswaId !== 'all') {
                    $query->where('siswa_id', $selectedSiswaId);
                } else {
                    $query->whereIn('siswa_id', $siswas->pluck('id'));
                }
                
                $nilais = $query->get();
                
                foreach ($nilais as $nilai) {
                    if ($nilai->semester) {
                        $semesters[$nilai->semester_id] = $nilai->semester->nama_semester . ' (' . $nilai->semester->tahun_ajaran . ')';
                    }
                    
                    if (!isset($grafikData[$nilai->semester_id])) {
                        $grafikData[$nilai->semester_id] = [
                            'total' => 0,
                            'count' => 0,
                            'mapels' => []
                        ];
                    }
                    
                    $grafikData[$nilai->semester_id]['total'] += $nilai->nilai_akhir;
                    $grafikData[$nilai->semester_id]['count'] += 1;
                    
                    $mapelName = $nilai->mapel->nama_mapel ?? 'Unknown';
                    if (!isset($grafikData[$nilai->semester_id]['mapels'][$mapelName])) {
                        $grafikData[$nilai->semester_id]['mapels'][$mapelName] = ['total' => 0, 'count' => 0];
                    }
                    $grafikData[$nilai->semester_id]['mapels'][$mapelName]['total'] += $nilai->nilai_akhir;
                    $grafikData[$nilai->semester_id]['mapels'][$mapelName]['count'] += 1;
                }
                
                asort($semesters);
                
                // Format for chart and table
                $chartLabels = [];
                $chartAverages = [];
                
                foreach ($semesters as $semId => $semName) {
                    $chartLabels[] = $semName;
                    $avg = 0;
                    if (isset($grafikData[$semId]) && $grafikData[$semId]['count'] > 0) {
                        $avg = round($grafikData[$semId]['total'] / $grafikData[$semId]['count'], 2);
                        
                        // Mapel detail for table
                        foreach ($grafikData[$semId]['mapels'] as $mName => $mData) {
                            $mAvg = round($mData['total'] / $mData['count'], 2);
                            $tabelData[$mName][$semId] = $mAvg;
                        }
                    }
                    $chartAverages[] = $avg;
                }
                
                $grafikData = [
                    'labels' => $chartLabels,
                    'data' => $chartAverages
                ];
            }
        }

        return view('admin.perkembangan_nilai.grafik', compact('rombels', 'selectedRombelId', 'siswas', 'selectedSiswaId', 'grafikData', 'tabelData', 'semesters'));
    }
}
