<?php

namespace App\Http\Controllers\Kepsek;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Guru;
use App\Models\Siswa;
use App\Models\Rombel;
use App\Models\Semester;
use App\Models\Pembelajaran;
use App\Models\MataPelajaran;
use App\Models\NilaiRapor;
use App\Models\DeskripsiRapor;

class MonitoringController extends Controller
{
    public function guru()
    {
        $gurus = Guru::with('user')->orderBy('nama_lengkap')->get();
        return view('kepsek.monitoring.guru', compact('gurus'));
    }

    public function siswa(Request $request)
    {
        $query = Siswa::with(['user', 'rombels' => function($q) {
            $semester_aktif = Semester::where('is_aktif', true)->first();
            if ($semester_aktif) {
                $q->where('semester_id', $semester_aktif->id);
            }
        }]);

        if ($request->has('search')) {
            $search = $request->search;
            $query->where('nama_lengkap', 'like', "%{$search}%")
                  ->orWhere('nisn', 'like', "%{$search}%");
        }

        $siswas = $query->orderBy('nama_lengkap')->paginate(50);
        return view('kepsek.monitoring.siswa', compact('siswas'));
    }

    public function rombel()
    {
        $semester_aktif = Semester::where('is_aktif', true)->first();
        $rombels = collect();
        if ($semester_aktif) {
            $rombels = Rombel::with(['waliKelas', 'siswas'])
                ->where('semester_id', $semester_aktif->id)
                ->orderBy('tingkat')
                ->orderBy('nama_rombel')
                ->get();
        }
        return view('kepsek.monitoring.rombel', compact('rombels', 'semester_aktif'));
    }

    public function kinerjaGuru(Request $request)
    {
        $semester_aktif = Semester::where('is_aktif', true)->first();
        $semester_teks = $semester_aktif ? $semester_aktif->tahun_ajaran . ' ' . ($semester_aktif->semester == 1 ? 'Ganjil' : 'Genap') : 'Belum disetup';

        $filterRombels = $semester_aktif ? Rombel::where('semester_id', $semester_aktif->id)->orderBy('tingkat')->orderBy('nama_rombel')->get() : collect();
        $filterGurus = Guru::orderBy('nama_lengkap')->get();

        $selected_rombel_id = $request->query('rombel_id');
        $selected_guru_id = $request->query('guru_id');
        $selected_status = $request->query('status'); // 'all', 'tuntas', 'sebagian', 'belum'
        $search = $request->query('search');

        $pembelajaranQuery = $semester_aktif
            ? Pembelajaran::where('semester_id', $semester_aktif->id)->with(['guru', 'mapel', 'rombel.siswas'])
            : Pembelajaran::whereRaw('1 = 0');

        if ($selected_rombel_id) {
            $pembelajaranQuery->where('rombel_id', $selected_rombel_id);
        }

        if ($selected_guru_id) {
            $pembelajaranQuery->where('guru_id', $selected_guru_id);
        }

        if ($search) {
            $pembelajaranQuery->where(function ($q) use ($search) {
                $q->whereHas('guru', function ($g) use ($search) {
                    $g->where('nama_lengkap', 'like', "%{$search}%");
                })->orWhereHas('mapel', function ($m) use ($search) {
                    $m->where('nama_mapel', 'like', "%{$search}%");
                });
            });
        }

        $pembelajarans = $pembelajaranQuery->get();

        $allProgres = collect();
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

            $allProgres->push([
                'id' => $p->id,
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

        $totalPembelajaran = $allProgres->count();
        $persenGlobalSekolah = $totalPembelajaran > 0 ? round(($tuntasCount / $totalPembelajaran) * 100) : 0;

        $rekapKinerja = [
            'total_pembelajaran' => $totalPembelajaran,
            'tuntas' => $tuntasCount,
            'sebagian' => $sebagianCount,
            'belum' => $belumCount,
            'persen_global' => $persenGlobalSekolah,
        ];

        // Filter status jika dipilih
        $progresKinerja = $allProgres;
        if ($selected_status === 'tuntas') {
            $progresKinerja = $progresKinerja->where('status', 'Tuntas');
        } elseif ($selected_status === 'sebagian') {
            $progresKinerja = $progresKinerja->where('status', 'Sebagian');
        } elseif ($selected_status === 'belum') {
            $progresKinerja = $progresKinerja->where('status', 'Belum Mengisi');
        }

        return view('kepsek.monitoring.kinerja_guru', compact(
            'semester_aktif', 'semester_teks', 'filterRombels', 'filterGurus',
            'selected_rombel_id', 'selected_guru_id', 'selected_status', 'search',
            'progresKinerja', 'rekapKinerja'
        ));
    }
}
