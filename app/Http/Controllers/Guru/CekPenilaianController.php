<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Pembelajaran;
use App\Models\Rombel;
use App\Models\Semester;
use App\Models\NilaiRapor;
use App\Models\DeskripsiRapor;
use App\Models\Siswa;

class CekPenilaianController extends Controller
{
    public function status(Request $request)
    {
        $guru_id = Auth::user()->guru->id ?? null;
        $semester_id = session('semester_id', Semester::where('is_aktif', true)->first()->id ?? 1);

        $pembelajarans = Pembelajaran::where('guru_id', $guru_id)
            ->where('semester_id', $semester_id)
            ->with('rombel')
            ->get();

        $rombels = $pembelajarans->pluck('rombel')->unique('id');
        $rombel_id = $request->rombel_id;

        $data_status = collect();

        if ($rombel_id) {
            $mapels = Pembelajaran::where('guru_id', $guru_id)
                ->where('semester_id', $semester_id)
                ->where('rombel_id', $rombel_id)
                ->with('mapel')
                ->get();

            $rombel = Rombel::with('siswas')->find($rombel_id);
            if ($rombel) {
                $siswa_ids = $rombel->siswas->pluck('id');

                foreach ($mapels as $p) {
                    $count_nilai = NilaiRapor::whereIn('siswa_id', $siswa_ids)
                        ->where('semester_id', $semester_id)
                        ->where('mata_pelajaran_id', $p->mata_pelajaran_id)
                        ->count();

                    $count_deskripsi = DeskripsiRapor::whereHas('nilaiRapor', function ($q) use ($siswa_ids, $semester_id, $p) {
                        $q->whereIn('siswa_id', $siswa_ids)
                            ->where('semester_id', $semester_id)
                            ->where('mata_pelajaran_id', $p->mata_pelajaran_id);
                    })->count();

                    $data_status->push([
                        'nama_mapel' => $p->mapel->nama_mapel ?? '-',
                        'rombel' => $rombel->nama_rombel,
                        'nilai_rapor' => $count_nilai,
                        'deskripsi' => $count_deskripsi,
                    ]);
                }
            }
        }

        return view('guru.cek_penilaian.status', compact('rombels', 'rombel_id', 'data_status'));
    }

    public function capaian(Request $request)
    {
        $guru_id = Auth::user()->guru->id ?? null;
        $semester_id = session('semester_id', Semester::where('is_aktif', true)->first()->id ?? 1);

        $pembelajarans = Pembelajaran::where('guru_id', $guru_id)
            ->where('semester_id', $semester_id)
            ->with(['rombel', 'mapel'])
            ->get();

        $rombels = $pembelajarans->pluck('rombel')->unique('id');
        $rombel_id = $request->rombel_id;
        $mapel_id = $request->mapel_id;
        $jenis_data = $request->jenis_data ?? 'nilai';

        $mapels = collect();
        if ($rombel_id) {
            $mapels = $pembelajarans->where('rombel_id', $rombel_id)->pluck('mapel')->unique('id');
        }

        $siswas = collect();
        $data_capaian = [];

        if ($rombel_id && $mapel_id) {
            $rombel = Rombel::with('siswas')->find($rombel_id);
            if ($rombel) {
                $siswas = $rombel->siswas()->orderBy('nama_lengkap')->get();

                // Dapatkan seluruh riwayat NilaiRapor untuk siswa-siswa ini pada mapel ini di semua semester
                $riwayat_nilai = NilaiRapor::with(['semester', 'deskripsi'])
                    ->whereIn('siswa_id', $siswas->pluck('id'))
                    ->where('mata_pelajaran_id', $mapel_id)
                    ->get();

                // Kita juga perlu mengetahui `tingkat` kelas siswa pada setiap semester.
                // Pendekatan terbaik adalah mengambil semua Rombel siswa dari anggota_rombels
                $siswa_rombels = \DB::table('anggota_rombels')
                    ->join('rombels', 'anggota_rombels.rombel_id', '=', 'rombels.id')
                    ->join('semesters', 'rombels.semester_id', '=', 'semesters.id')
                    ->whereIn('anggota_rombels.siswa_id', $siswas->pluck('id'))
                    ->select('anggota_rombels.siswa_id', 'rombels.tingkat', 'semesters.semester', 'semesters.id as smt_id')
                    ->get();

                $smt_map = [];
                foreach ($siswa_rombels as $sr) {
                    // Hitung Smt 1..12
                    $tingkat = (int) $sr->tingkat;
                    $ganjil_genap = strtolower($sr->semester) == 'ganjil' ? 1 : 2;
                    $smt_ke = ($tingkat - 1) * 2 + $ganjil_genap;
                    
                    $smt_map[$sr->siswa_id][$sr->smt_id] = $smt_ke;
                }

                foreach ($siswas as $siswa) {
                    $baris = array_fill(1, 12, '-');
                    $total_nilai = 0;
                    $count_nilai = 0;

                    $nilai_siswa = $riwayat_nilai->where('siswa_id', $siswa->id);
                    
                    foreach ($nilai_siswa as $ns) {
                        $smt_id = $ns->semester_id;
                        if (isset($smt_map[$siswa->id][$smt_id])) {
                            $smt_ke = $smt_map[$siswa->id][$smt_id];
                            if ($smt_ke >= 1 && $smt_ke <= 12) {
                                if ($jenis_data == 'nilai') {
                                    $baris[$smt_ke] = $ns->nilai_akhir;
                                    $total_nilai += $ns->nilai_akhir;
                                    $count_nilai++;
                                } else {
                                    // Deskripsi
                                    $deskripsi_tertinggi = $ns->deskripsi_tertinggi ?? '-';
                                    $baris[$smt_ke] = $deskripsi_tertinggi;
                                }
                            }
                        }
                    }

                    $rata_rata = $count_nilai > 0 ? round($total_nilai / $count_nilai) : '-';
                    $baris['rata_rata'] = $rata_rata;
                    
                    $data_capaian[$siswa->id] = $baris;
                }
            }
        }

        return view('guru.cek_penilaian.capaian', compact('rombels', 'mapels', 'siswas', 'data_capaian', 'rombel_id', 'mapel_id', 'jenis_data'));
    }

    public function grafik(Request $request)
    {
        $guru_id = Auth::user()->guru->id ?? null;
        $semester_id = session('semester_id', Semester::where('is_aktif', true)->first()->id ?? 1);

        $pembelajarans = Pembelajaran::where('guru_id', $guru_id)
            ->where('semester_id', $semester_id)
            ->with(['rombel', 'mapel'])
            ->get();

        $rombels = $pembelajarans->pluck('rombel')->unique('id');
        $rombel_id = $request->rombel_id;
        $mapel_id = $request->mapel_id;
        $siswa_id_req = $request->siswa_id; // Bisa 'all' atau ID siswa

        $mapels = collect();
        $siswas = collect();
        if ($rombel_id) {
            $mapels = $pembelajarans->where('rombel_id', $rombel_id)->pluck('mapel')->unique('id');
            $rombel = Rombel::with('siswas')->find($rombel_id);
            if ($rombel) {
                $siswas = $rombel->siswas()->orderBy('nama_lengkap')->get();
            }
        }

        $chart_data = array_fill(1, 12, 0);
        $chart_labels = [];
        for ($i = 1; $i <= 12; $i++) {
            $chart_labels[] = "Smt $i";
        }
        
        $table_data = [];
        $mapel_nama = '';
        $sub_title = '';

        if ($rombel_id && $mapel_id && $siswa_id_req) {
            $mapel_nama = $mapels->where('id', $mapel_id)->first()->nama_mapel ?? '';
            $siswa_list = $siswas;
            
            if ($siswa_id_req != 'all') {
                $siswa_list = $siswas->where('id', $siswa_id_req);
                $siswa_nama = $siswa_list->first()->nama_lengkap ?? '';
                $sub_title = "Riwayat Nilai $mapel_nama $siswa_nama";
            } else {
                $rombel_nama = $rombels->where('id', $rombel_id)->first()->nama_rombel ?? '';
                $sub_title = "Riwayat Nilai $mapel_nama Kls $rombel_nama";
            }

            $riwayat_nilai = NilaiRapor::with('semester')
                ->whereIn('siswa_id', $siswa_list->pluck('id'))
                ->where('mata_pelajaran_id', $mapel_id)
                ->get();

            $siswa_rombels = \DB::table('anggota_rombels')
                ->join('rombels', 'anggota_rombels.rombel_id', '=', 'rombels.id')
                ->join('semesters', 'rombels.semester_id', '=', 'semesters.id')
                ->whereIn('anggota_rombels.siswa_id', $siswa_list->pluck('id'))
                ->select('anggota_rombels.siswa_id', 'rombels.tingkat', 'semesters.semester', 'semesters.id as smt_id')
                ->get();

            $smt_map = [];
            foreach ($siswa_rombels as $sr) {
                $tingkat = (int) $sr->tingkat;
                $ganjil_genap = strtolower($sr->semester) == 'ganjil' ? 1 : 2;
                $smt_ke = ($tingkat - 1) * 2 + $ganjil_genap;
                $smt_map[$sr->siswa_id][$sr->smt_id] = $smt_ke;
            }

            $akumulasi_smt = array_fill(1, 12, ['total' => 0, 'count' => 0]);

            foreach ($riwayat_nilai as $ns) {
                $smt_id = $ns->semester_id;
                $s_id = $ns->siswa_id;
                
                if (isset($smt_map[$s_id][$smt_id])) {
                    $smt_ke = $smt_map[$s_id][$smt_id];
                    if ($smt_ke >= 1 && $smt_ke <= 12) {
                        $akumulasi_smt[$smt_ke]['total'] += $ns->nilai_akhir;
                        $akumulasi_smt[$smt_ke]['count']++;
                    }
                }
            }

            $total_all = 0;
            $count_all = 0;

            for ($i = 1; $i <= 12; $i++) {
                if ($akumulasi_smt[$i]['count'] > 0) {
                    $avg = round($akumulasi_smt[$i]['total'] / $akumulasi_smt[$i]['count'], 2);
                    $chart_data[$i] = $avg;
                    $table_data["smt_$i"] = $avg;
                    $total_all += $avg;
                    $count_all++;
                } else {
                    $chart_data[$i] = 0;
                    $table_data["smt_$i"] = '';
                }
            }
            
            $table_data['rata_rata'] = $count_all > 0 ? round($total_all / $count_all, 2) : '';
        }

        return view('guru.cek_penilaian.grafik', compact(
            'rombels', 'mapels', 'siswas', 'rombel_id', 'mapel_id', 'siswa_id_req',
            'chart_labels', 'chart_data', 'table_data', 'mapel_nama', 'sub_title'
        ));
    }
}
