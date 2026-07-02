<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Guru;
use App\Models\Semester;
use App\Models\Pembelajaran;
use App\Models\Ekstrakurikuler;
use App\Models\P5Kelompok;
use Illuminate\Support\Facades\DB;

class GuruController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();
        $guru = Guru::where('user_id', $user->id)->first();
        $semesterAktif = Semester::where('is_aktif', true)->first();

        // Default stats
        $stats = [
            'jumlah_mapel' => 0,
            'jumlah_kelas' => 0,
            'jumlah_siswa' => 0,
            'jumlah_ekskul' => 0,
            'jumlah_proyek' => 0,
            'sebagai_walikelas' => null,
        ];

        if ($guru && $semesterAktif) {
            // Tugas Guru Mapel
            $pembelajarans = Pembelajaran::where('guru_id', $guru->id)
                ->where('semester_id', $semesterAktif->id)
                ->get();

            $stats['jumlah_mapel'] = $pembelajarans->unique('mata_pelajaran_id')->count();
            $stats['jumlah_kelas'] = $pembelajarans->unique('rombel_id')->count();
            
            $rombelIds = $pembelajarans->pluck('rombel_id')->unique();
            $stats['jumlah_siswa'] = DB::table('anggota_rombels')
                ->whereIn('rombel_id', $rombelIds)
                ->count();

            // Tugas Pembina Ekskul
            $stats['jumlah_ekskul'] = Ekstrakurikuler::where('pembina_id', $guru->id)->count();

            // Tugas Koordinator Projek P5
            $stats['jumlah_proyek'] = P5Kelompok::where('guru_id', $guru->id)
                ->where('semester_id', $semesterAktif->id)
                ->count();
                
            // Tugas Wali Kelas
            $walikelas = \App\Models\Rombel::where('wali_kelas_id', $guru->id)
                ->where('semester_id', $semesterAktif->id)
                ->first();
            
            if ($walikelas) {
                $stats['sebagai_walikelas'] = $walikelas->nama_rombel;
            }
        }

        return view('guru.dashboard', compact('guru', 'semesterAktif', 'stats'));
    }
}
