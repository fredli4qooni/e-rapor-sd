<?php

namespace App\Http\Controllers\WaliKelas;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Rombel;
use App\Models\Pembelajaran;
use App\Models\NilaiRapor;
use App\Models\MataPelajaran;
use App\Models\DeskripsiMapel;

class CekPenilaianKelasController extends Controller
{
    public function status(Request $request)
    {
        $rombel = Rombel::where('wali_kelas_id', auth()->user()->guru->id ?? 1)->first();
        if (!$rombel) return redirect()->back()->with('error', 'Anda belum ditugaskan sebagai Wali Kelas.');

        $semesterAktif = \App\Models\Semester::where('is_aktif', true)->first();
        if (!$semesterAktif) return redirect()->back();

        // Get total siswas in this rombel
        $siswa_ids = $rombel->siswas()->pluck('siswas.id');
        $total_siswas = $siswa_ids->count();

        // Get mapels for this rombel
        $pembelajarans = Pembelajaran::with(['mapel', 'guru'])
                            ->where('rombel_id', $rombel->id)
                            ->where('semester_id', $semesterAktif->id)
                            ->orderBy('mata_pelajaran_id')
                            ->get();

        $data_status = [];
        foreach ($pembelajarans as $pembelajaran) {
            $mapel = $pembelajaran->mapel;
            if (!$mapel) continue;
            
            $nilais = NilaiRapor::whereIn('siswa_id', $siswa_ids)
                                ->where('mata_pelajaran_id', $mapel->id)
                                ->where('semester_id', $semesterAktif->id)
                                ->get();
                                
            $count_nilai = $nilais->whereNotNull('nilai_akhir')->count();
            
            // Count distinct siswas that have Deskripsi for this mapel in this rombel
            $nilai_ids = $nilais->pluck('id');
            $count_deskripsi = \App\Models\DeskripsiRapor::whereIn('nilai_rapor_id', $nilai_ids)->count();

            $data_status[] = [
                'mapel' => $mapel,
                'guru' => $pembelajaran->guru,
                'count_nilai' => $count_nilai,
                'count_deskripsi' => $count_deskripsi,
            ];
        }

        return view('walikelas.cek_penilaian.status', compact('rombel', 'data_status', 'total_siswas'));
    }

    public function statistikRapor(Request $request)
    {
        $rombel = Rombel::where('wali_kelas_id', auth()->user()->guru->id ?? 1)->first();
        if (!$rombel) return redirect()->back()->with('error', 'Anda belum ditugaskan sebagai Wali Kelas.');

        $semesterAktif = \App\Models\Semester::where('is_aktif', true)->first();
        if (!$semesterAktif) return redirect()->back();

        $siswa_ids = $rombel->siswas()->pluck('siswas.id');

        $pembelajarans = Pembelajaran::with(['mapel', 'guru'])
                            ->where('rombel_id', $rombel->id)
                            ->where('semester_id', $semesterAktif->id)
                            ->orderBy('mata_pelajaran_id')
                            ->get();

        $statistik = [];
        $chart_labels = [];
        $chart_tertinggi = [];
        $chart_terendah = [];
        $chart_rata_rata = [];

        foreach ($pembelajarans as $pembelajaran) {
            $mapel = $pembelajaran->mapel;
            if (!$mapel) continue;
            
            $nilais = NilaiRapor::whereIn('siswa_id', $siswa_ids)
                                ->where('mata_pelajaran_id', $mapel->id)
                                ->where('semester_id', $semesterAktif->id)
                                ->get();
                                
            $jumlah_data = $nilais->count();
            if ($jumlah_data > 0) {
                $tertinggi = $nilais->max('nilai_akhir');
                $terendah = $nilais->min('nilai_akhir');
                $rata_rata = round($nilais->avg('nilai_akhir'), 2);
            } else {
                $tertinggi = 0;
                $terendah = 0;
                $rata_rata = 0;
            }

            $statistik[] = [
                'mapel' => $mapel,
                'guru' => $pembelajaran->guru,
                'jumlah_data' => $jumlah_data,
                'tertinggi' => $tertinggi,
                'terendah' => $terendah,
                'rata_rata' => $rata_rata,
            ];

            // For chart
            $chart_labels[] = $mapel->singkatan ?? $mapel->nama_mapel;
            $chart_tertinggi[] = $tertinggi;
            $chart_terendah[] = $terendah;
            $chart_rata_rata[] = $rata_rata;
        }

        return view('walikelas.cek_penilaian.statistik_rapor', compact(
            'rombel', 'statistik', 'chart_labels', 'chart_tertinggi', 'chart_terendah', 'chart_rata_rata'
        ));
    }

    public function statistikP3(Request $request)
    {
        $rombel = Rombel::where('wali_kelas_id', auth()->user()->guru->id ?? 1)->first();
        if (!$rombel) return redirect()->back()->with('error', 'Anda belum ditugaskan sebagai Wali Kelas.');

        $semesterAktif = \App\Models\Semester::where('is_aktif', true)->first();
        if (!$semesterAktif) return redirect()->back();

        $dimensis = \App\Models\P5Dimensi::with('elemens.subElemens')->get();
        $statistik = [];
        
        $chart_labels = [];
        $chart_data_mb = [];
        $chart_data_sb = [];
        $chart_data_bsh = [];
        $chart_data_sangatb = [];
        $radar_data = [];

        foreach ($dimensis as $dimensi) {
            $subElemenIds = [];
            foreach ($dimensi->elemens as $elemen) {
                foreach ($elemen->subElemens as $sub) {
                    $subElemenIds[] = $sub->id;
                }
            }

            // Get all NilaiP3 for this rombel and dimensi
            $nilais = \App\Models\NilaiP3::where('rombel_id', $rombel->id)
                            ->where('semester_id', $semesterAktif->id)
                            ->whereIn('p5_sub_elemen_id', $subElemenIds)
                            ->get();
                            
            // Group by siswa to get average predikat per siswa
            $nilaisBySiswa = $nilais->groupBy('siswa_id');
            $jumlah_data = $nilaisBySiswa->count();
            
            $count_mb = 0;
            $count_sb = 0;
            $count_bsh = 0;
            $count_sangatb = 0;
            $total_score_sum = 0;

            foreach ($nilaisBySiswa as $siswa_id => $siswa_nilais) {
                // Assuming 'nilai' is stored as integer 1,2,3,4
                $avg = $siswa_nilais->avg('nilai');
                $total_score_sum += $avg;
                
                $rounded_avg = round($avg);
                if ($rounded_avg == 1) $count_mb++;
                elseif ($rounded_avg == 2) $count_sb++;
                elseif ($rounded_avg == 3) $count_bsh++;
                elseif ($rounded_avg == 4) $count_sangatb++;
            }

            $pct_mb = $jumlah_data > 0 ? round(($count_mb / $jumlah_data) * 100) : 0;
            $pct_sb = $jumlah_data > 0 ? round(($count_sb / $jumlah_data) * 100) : 0;
            $pct_bsh = $jumlah_data > 0 ? round(($count_bsh / $jumlah_data) * 100) : 0;
            $pct_sangatb = $jumlah_data > 0 ? round(($count_sangatb / $jumlah_data) * 100) : 0;

            $statistik[] = [
                'dimensi' => $dimensi,
                'jumlah_data' => $jumlah_data,
                'pct_mb' => $pct_mb,
                'pct_sb' => $pct_sb,
                'pct_bsh' => $pct_bsh,
                'pct_sangatb' => $pct_sangatb,
            ];

            // Chart data
            $chart_labels[] = $dimensi->nama_dimensi;
            $chart_data_mb[] = $pct_mb;
            $chart_data_sb[] = $pct_sb;
            $chart_data_bsh[] = $pct_bsh;
            $chart_data_sangatb[] = $pct_sangatb;
            
            // Radar Data: average score 1-4 for the dimension
            $radar_data[] = $jumlah_data > 0 ? round($total_score_sum / $jumlah_data, 2) : 0;
        }

        return view('walikelas.cek_penilaian.statistik_p3', compact(
            'rombel', 'statistik', 'chart_labels', 'chart_data_mb', 'chart_data_sb', 'chart_data_bsh', 'chart_data_sangatb', 'radar_data'
        ));
    }
}
