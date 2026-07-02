<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Pembelajaran;
use App\Models\Semester;
use App\Models\Rombel;
use App\Models\MataPelajaran;
use App\Models\NilaiRapor;
use App\Models\DeskripsiRapor;

class NilaiTersimpanController extends Controller
{
    private function getFilterData($request)
    {
        $guru = Auth::user()->guru;
        $semesterAktif = Semester::where('is_aktif', true)->first();

        $pembelajarans = Pembelajaran::where('guru_id', $guru->id)
            ->where('semester_id', $semesterAktif->id)
            ->with(['rombel', 'mapel'])
            ->get();

        $rombel_id = $request->query('rombel_id');
        $mata_pelajaran_id = $request->query('mata_pelajaran_id');

        $guruRombels = $pembelajarans->map->rombel->unique('id')->values();
        
        $guruMapels = collect();
        if ($rombel_id) {
             $guruMapels = $pembelajarans->where('rombel_id', $rombel_id)->map->mapel->unique('id')->values();
        } else {
             $guruMapels = $pembelajarans->map->mapel->unique('id')->values();
        }

        $rombel = $rombel_id ? Rombel::find($rombel_id) : null;
        $mapel = $mata_pelajaran_id ? MataPelajaran::find($mata_pelajaran_id) : null;

        $nilaiRapors = collect();

        if ($rombel_id && $mata_pelajaran_id) {
            // Dapatkan siswa yang ada di rombel ini
            $siswaIds = \DB::table('anggota_rombels')->where('rombel_id', $rombel_id)->pluck('siswa_id');
            
            $nilaiRapors = NilaiRapor::with(['siswa', 'deskripsi'])
                ->where('semester_id', $semesterAktif->id)
                ->where('mata_pelajaran_id', $mata_pelajaran_id)
                ->whereIn('siswa_id', $siswaIds)
                ->get()
                ->sortBy('siswa.nama_lengkap');
        }

        return compact('guruRombels', 'guruMapels', 'rombel_id', 'mata_pelajaran_id', 'rombel', 'mapel', 'nilaiRapors');
    }

    public function indexNilaiRapor(Request $request)
    {
        $data = $this->getFilterData($request);
        return view('guru.nilai_tersimpan.nilai_rapor', $data);
    }

    public function indexDeskripsiRapor(Request $request)
    {
        $data = $this->getFilterData($request);
        return view('guru.nilai_tersimpan.deskripsi_rapor', $data);
    }

    public function destroyNilaiRapor(Request $request, $id)
    {
        if ($id === 'all') {
            // Delete all for filtered rombel and mapel
            $request->validate([
                'rombel_id' => 'required',
                'mata_pelajaran_id' => 'required',
            ]);
            $semesterAktif = Semester::where('is_aktif', true)->first();
            $siswaIds = \DB::table('anggota_rombels')->where('rombel_id', $request->rombel_id)->pluck('siswa_id');
            
            NilaiRapor::where('semester_id', $semesterAktif->id)
                ->where('mata_pelajaran_id', $request->mata_pelajaran_id)
                ->whereIn('siswa_id', $siswaIds)
                ->delete();

            return back()->with('success', 'Semua Nilai Rapor untuk kelas ini berhasil dihapus.');
        }

        $nilai = NilaiRapor::findOrFail($id);
        $nilai->delete();

        return back()->with('success', 'Nilai Rapor berhasil dihapus.');
    }

    public function destroyDeskripsiRapor(Request $request, $id)
    {
        if ($id === 'all') {
            // Delete all deskripsi for filtered rombel and mapel
            $request->validate([
                'rombel_id' => 'required',
                'mata_pelajaran_id' => 'required',
            ]);
            $semesterAktif = Semester::where('is_aktif', true)->first();
            $siswaIds = \DB::table('anggota_rombels')->where('rombel_id', $request->rombel_id)->pluck('siswa_id');
            
            $nilaiRaporIds = NilaiRapor::where('semester_id', $semesterAktif->id)
                ->where('mata_pelajaran_id', $request->mata_pelajaran_id)
                ->whereIn('siswa_id', $siswaIds)
                ->pluck('id');
                
            DeskripsiRapor::whereIn('nilai_rapor_id', $nilaiRaporIds)->delete();

            return back()->with('success', 'Semua Deskripsi Rapor untuk kelas ini berhasil dihapus.');
        }

        $deskripsi = DeskripsiRapor::findOrFail($id);
        $deskripsi->delete();

        return back()->with('success', 'Deskripsi Rapor berhasil dihapus.');
    }
}
