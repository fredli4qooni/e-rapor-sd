<?php

namespace App\Http\Controllers\Kepsek;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Guru;
use App\Models\Siswa;
use App\Models\Rombel;
use App\Models\Pembelajaran;
use App\Models\Semester;
use App\Models\MataPelajaran;
use App\Models\NilaiRapor;
use App\Models\DeskripsiRapor;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $sekolah = \App\Models\Sekolah::first();
        $semester_aktif = Semester::where('is_aktif', true)->first();
        $semester_teks = $semester_aktif ? $semester_aktif->tahun_ajaran . ' ' . ($semester_aktif->semester == 1 ? 'Ganjil' : 'Genap') : 'Belum disetup';

        // Hitung Rekap Data untuk Dashboard Eksekutif
        $counts = [
            'guru' => Guru::count(),
            'siswa' => Siswa::count(),
            'rombel' => $semester_aktif ? Rombel::where('semester_id', $semester_aktif->id)->count() : 0,
            'pembelajaran' => $semester_aktif ? Pembelajaran::where('semester_id', $semester_aktif->id)->count() : 0,
        ];

        // Filter Options
        $filterRombels = $semester_aktif ? Rombel::where('semester_id', $semester_aktif->id)->orderBy('tingkat')->orderBy('nama_rombel')->get() : collect();
        $filterMapels = MataPelajaran::orderBy('nama_mapel')->get();

        $selected_rombel_id = $request->query('rombel_id');
        $selected_mapel_id = $request->query('mata_pelajaran_id');

        // Data untuk Grafik Siswa per Rombel
        $chart_labels = $filterRombels->pluck('nama_rombel')->toJson();
        $chart_data = $filterRombels->map(fn($r) => $r->siswas()->count())->toJson();

        // --- Data Analitik Nilai Adaptif (Per Kelas / Per Mapel / Global) ---
        $chart_nilai_title = 'Grafik Rata-rata Nilai per Mata Pelajaran (Seluruh Sekolah)';
        $chart_nilai_type = 'mapel'; // 'mapel', 'rombel', 'siswa'
        $chart_nilai_labels = [];
        $chart_nilai_data = [];
        $rincian_siswa = collect();
        $stat_nilai = [
            'rata_rata' => '-',
            'tertinggi' => '-',
            'terendah' => '-',
            'total_dinilai' => 0,
        ];

        if ($semester_aktif) {
            if ($selected_rombel_id && $selected_mapel_id) {
                // KASUS 1: Filter Kelas & Mapel Spesifik (Detail Siswa)
                $rombel = Rombel::with('siswas')->find($selected_rombel_id);
                $mapel = MataPelajaran::find($selected_mapel_id);
                $chart_nilai_title = 'Daftar Nilai Siswa ' . ($mapel->nama_mapel ?? '') . ' - ' . ($rombel->nama_rombel ?? '');
                $chart_nilai_type = 'siswa';

                if ($rombel && $mapel) {
                    $siswaIds = $rombel->siswas->pluck('id');
                    $nilaiList = NilaiRapor::with(['siswa', 'deskripsi'])
                        ->whereIn('siswa_id', $siswaIds)
                        ->where('mata_pelajaran_id', $selected_mapel_id)
                        ->where('semester_id', $semester_aktif->id)
                        ->get()
                        ->keyBy('siswa_id');

                    $chartLabelsArr = [];
                    $chartDataArr = [];
                    $totalNilai = 0;
                    $countNilai = 0;
                    $maxNilai = 0;
                    $minNilai = 100;

                    foreach ($rombel->siswas()->orderBy('nama_lengkap')->get() as $s) {
                        $n = $nilaiList->get($s->id);
                        $nilaiAkhir = $n ? round($n->nilai_akhir) : null;

                        $chartLabelsArr[] = explode(' ', $s->nama_lengkap)[0];
                        $chartDataArr[] = $nilaiAkhir ?? 0;

                        if ($nilaiAkhir !== null) {
                            $totalNilai += $nilaiAkhir;
                            $countNilai++;
                            if ($nilaiAkhir > $maxNilai) $maxNilai = $nilaiAkhir;
                            if ($nilaiAkhir < $minNilai) $minNilai = $nilaiAkhir;
                        }

                        $rincian_siswa->push([
                            'siswa' => $s,
                            'nilai' => $n,
                            'nilai_akhir' => $nilaiAkhir,
                            'capaian' => $n ? $n->capaian_kompetensi : '-',
                        ]);
                    }

                    $chart_nilai_labels = collect($chartLabelsArr)->toJson();
                    $chart_nilai_data = collect($chartDataArr)->toJson();

                    $stat_nilai['total_dinilai'] = $countNilai;
                    $stat_nilai['rata_rata'] = $countNilai > 0 ? round($totalNilai / $countNilai, 2) : '-';
                    $stat_nilai['tertinggi'] = $countNilai > 0 ? $maxNilai : '-';
                    $stat_nilai['terendah'] = $countNilai > 0 ? $minNilai : '-';
                }
            } elseif ($selected_mapel_id) {
                // KASUS 2: Filter Mapel Spesifik (Bandingkan Antar Kelas)
                $mapel = MataPelajaran::find($selected_mapel_id);
                $chart_nilai_title = 'Perbandingan Rata-rata Nilai ' . ($mapel->nama_mapel ?? '') . ' Antar Rombel / Kelas';
                $chart_nilai_type = 'rombel';

                $chartLabelsArr = [];
                $chartDataArr = [];
                $totalAll = 0;
                $countAll = 0;
                $maxAll = 0;
                $minAll = 100;

                foreach ($filterRombels as $r) {
                    $siswaIds = $r->siswas()->pluck('siswas.id');
                    $avg = DB::table('nilai_rapors')
                        ->whereIn('siswa_id', $siswaIds)
                        ->where('mata_pelajaran_id', $selected_mapel_id)
                        ->where('semester_id', $semester_aktif->id)
                        ->avg('nilai_akhir');

                    $chartLabelsArr[] = $r->nama_rombel;
                    $val = $avg ? round($avg, 2) : 0;
                    $chartDataArr[] = $val;

                    if ($avg) {
                        $totalAll += $avg;
                        $countAll++;
                        if ($val > $maxAll) $maxAll = $val;
                        if ($val < $minAll) $minAll = $val;
                    }
                }

                $chart_nilai_labels = collect($chartLabelsArr)->toJson();
                $chart_nilai_data = collect($chartDataArr)->toJson();

                $stat_nilai['total_dinilai'] = DB::table('nilai_rapors')
                    ->where('mata_pelajaran_id', $selected_mapel_id)
                    ->where('semester_id', $semester_aktif->id)
                    ->count();
                $stat_nilai['rata_rata'] = $countAll > 0 ? round($totalAll / $countAll, 2) : '-';
                $stat_nilai['tertinggi'] = $countAll > 0 ? $maxAll : '-';
                $stat_nilai['terendah'] = $countAll > 0 ? $minAll : '-';

            } elseif ($selected_rombel_id) {
                // KASUS 3: Filter Kelas Tertentu (Rata-rata Mapel di Kelas Ini)
                $rombel = Rombel::with('siswas')->find($selected_rombel_id);
                $chart_nilai_title = 'Rata-rata Nilai per Mata Pelajaran di ' . ($rombel->nama_rombel ?? 'Kelas Terpilih');
                $chart_nilai_type = 'mapel';

                $siswaIds = $rombel ? $rombel->siswas->pluck('id') : collect();

                $grafik_nilai = DB::table('nilai_rapors')
                    ->join('mata_pelajarans', 'nilai_rapors.mata_pelajaran_id', '=', 'mata_pelajarans.id')
                    ->select(
                        DB::raw('COALESCE(NULLIF(mata_pelajarans.nama_singkat, ""), mata_pelajarans.nama_mapel) as mapel_label'),
                        DB::raw('AVG(nilai_rapors.nilai_akhir) as rata_rata'),
                        DB::raw('MAX(nilai_rapors.nilai_akhir) as max_nilai'),
                        DB::raw('MIN(nilai_rapors.nilai_akhir) as min_nilai'),
                        DB::raw('COUNT(nilai_rapors.id) as count_nilai')
                    )
                    ->whereIn('nilai_rapors.siswa_id', $siswaIds)
                    ->where('nilai_rapors.semester_id', $semester_aktif->id)
                    ->groupBy('mata_pelajarans.id', 'mata_pelajarans.nama_singkat', 'mata_pelajarans.nama_mapel')
                    ->get();

                $chart_nilai_labels = $grafik_nilai ? collect($grafik_nilai)->pluck('mapel_label')->toJson() : '[]';
                $chart_nilai_data = $grafik_nilai ? collect($grafik_nilai)->pluck('rata_rata')->map(fn($v) => round($v, 2))->toJson() : '[]';

                if ($grafik_nilai->count() > 0) {
                    $stat_nilai['total_dinilai'] = $grafik_nilai->sum('count_nilai');
                    $stat_nilai['rata_rata'] = round($grafik_nilai->avg('rata_rata'), 2);
                    $stat_nilai['tertinggi'] = round($grafik_nilai->max('max_nilai'), 2);
                    $stat_nilai['terendah'] = round($grafik_nilai->min('min_nilai'), 2);
                }
            } else {
                // KASUS 4: Default (Semua Mapel di Seluruh Sekolah)
                $grafik_nilai = DB::table('nilai_rapors')
                    ->join('mata_pelajarans', 'nilai_rapors.mata_pelajaran_id', '=', 'mata_pelajarans.id')
                    ->select(
                        DB::raw('COALESCE(NULLIF(mata_pelajarans.nama_singkat, ""), mata_pelajarans.nama_mapel) as mapel_label'),
                        DB::raw('AVG(nilai_rapors.nilai_akhir) as rata_rata'),
                        DB::raw('MAX(nilai_rapors.nilai_akhir) as max_nilai'),
                        DB::raw('MIN(nilai_rapors.nilai_akhir) as min_nilai'),
                        DB::raw('COUNT(nilai_rapors.id) as count_nilai')
                    )
                    ->where('nilai_rapors.semester_id', $semester_aktif->id)
                    ->groupBy('mata_pelajarans.id', 'mata_pelajarans.nama_singkat', 'mata_pelajarans.nama_mapel')
                    ->get();

                $chart_nilai_labels = $grafik_nilai ? collect($grafik_nilai)->pluck('mapel_label')->toJson() : '[]';
                $chart_nilai_data = $grafik_nilai ? collect($grafik_nilai)->pluck('rata_rata')->map(fn($v) => round($v, 2))->toJson() : '[]';

                if ($grafik_nilai->count() > 0) {
                    $stat_nilai['total_dinilai'] = $grafik_nilai->sum('count_nilai');
                    $stat_nilai['rata_rata'] = round($grafik_nilai->avg('rata_rata'), 2);
                    $stat_nilai['tertinggi'] = round($grafik_nilai->max('max_nilai'), 2);
                    $stat_nilai['terendah'] = round($grafik_nilai->min('min_nilai'), 2);
                }
            }
        }

        // --- PEMANTAUAN KINERJA & PROGRES PENILAIAN GURU ---
        $pembelajarans = $semester_aktif 
            ? Pembelajaran::where('semester_id', $semester_aktif->id)
                ->with(['guru', 'mapel', 'rombel.siswas'])
                ->get()
            : collect();

        $progresKinerja = collect();
        $tuntasCount = 0;
        $sebagianCount = 0;
        $belumCount = 0;

        foreach ($pembelajarans as $p) {
            $rombel = $p->rombel;
            $totalSiswa = $rombel ? $rombel->siswas->count() : 0;
            $siswaIds = $rombel ? $rombel->siswas->pluck('id') : collect();

            $terisiNilai = NilaiRapor::whereIn('siswa_id', $siswaIds)
                ->where('mata_pelajaran_id', $p->mata_pelajaran_id)
                ->where('semester_id', $semester_aktif->id)
                ->count();

            $terisiDeskripsi = DeskripsiRapor::whereHas('nilaiRapor', function ($q) use ($siswaIds, $semester_aktif, $p) {
                $q->whereIn('siswa_id', $siswaIds)
                  ->where('mata_pelajaran_id', $p->mata_pelajaran_id)
                  ->where('semester_id', $semester_aktif->id);
            })->count();

            $persenNilai = $totalSiswa > 0 ? round(($terisiNilai / $totalSiswa) * 100) : 0;
            $persenDeskripsi = $totalSiswa > 0 ? round(($terisiDeskripsi / $totalSiswa) * 100) : 0;

            if ($persenNilai >= 100 && $persenDeskripsi >= 100) {
                $status = 'Tuntas';
                $tuntasCount++;
            } elseif ($persenNilai > 0 || $persenDeskripsi > 0) {
                $status = 'Sebagian';
                $sebagianCount++;
            } else {
                $status = 'Belum Mengisi';
                $belumCount++;
            }

            $progresKinerja->push([
                'guru' => $p->guru,
                'mapel' => $p->mapel,
                'rombel' => $p->rombel,
                'total_siswa' => $totalSiswa,
                'terisi_nilai' => $terisiNilai,
                'terisi_deskripsi' => $terisiDeskripsi,
                'persen_nilai' => $persenNilai,
                'persen_deskripsi' => $persenDeskripsi,
                'status' => $status,
            ]);
        }

        $totalPembelajaran = $progresKinerja->count();
        $persenGlobalSekolah = $totalPembelajaran > 0 ? round(($tuntasCount / $totalPembelajaran) * 100) : 0;

        $rekapKinerjaSekolah = [
            'total_pembelajaran' => $totalPembelajaran,
            'tuntas' => $tuntasCount,
            'sebagian' => $sebagianCount,
            'belum' => $belumCount,
            'persen_global' => $persenGlobalSekolah,
        ];

        return view('kepsek.dashboard', compact(
            'sekolah', 'semester_aktif', 'semester_teks', 'counts', 
            'filterRombels', 'filterMapels', 'selected_rombel_id', 'selected_mapel_id',
            'chart_labels', 'chart_data', 
            'chart_nilai_title', 'chart_nilai_type', 'chart_nilai_labels', 'chart_nilai_data',
            'stat_nilai', 'rincian_siswa',
            'progresKinerja', 'rekapKinerjaSekolah'
        ));
    }
}

