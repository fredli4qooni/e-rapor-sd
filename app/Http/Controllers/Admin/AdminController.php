<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Guru;
use App\Models\Siswa;
use App\Models\Rombel;
use App\Models\Pembelajaran;
use App\Models\Semester;

class AdminController extends Controller
{
    public function dashboard()
    {
        $sekolah = \App\Models\Sekolah::first();
        $semester_aktif = Semester::where('is_aktif', true)->first();
        $semester_teks = $semester_aktif ? $semester_aktif->tahun_ajaran . ' ' . ($semester_aktif->semester == 1 ? 'Ganjil' : 'Genap') : '2025/2026 Ganjil';

        // Hitung Rekap Data
        $counts = [
            'pengguna' => User::count(),
            'online' => DB::table('sessions')
                            ->whereNotNull('user_id')
                            ->where('last_activity', '>=', time() - 900)
                            ->count(),
            'guru' => Guru::count(),
            'siswa' => Siswa::count(),
            'rombel' => $semester_aktif ? Rombel::where('semester_id', $semester_aktif->id)->count() : 0,
            'pembelajaran' => $semester_aktif ? Pembelajaran::where('semester_id', $semester_aktif->id)->count() : 0,
        ];

        // Hitung Progress Kegiatan Administrator
        $progress = [];

        // 1. Setup Data Sekolah
        $progress[] = [
            'nama' => 'Setup Data Sekolah',
            'persen' => $sekolah ? 100 : 0,
            'status' => $sekolah ? 'Sudah dikerjakan' : 'Belum dikerjakan',
            'warna' => $sekolah ? 'green' : 'red',
        ];

        // 2. Pengaturan Semester Aktif
        $progress[] = [
            'nama' => 'Pengaturan Semester Aktif',
            'persen' => $semester_aktif ? 100 : 0,
            'status' => $semester_aktif ? 'Sudah dikerjakan' : 'Belum dikerjakan',
            'warna' => $semester_aktif ? 'green' : 'red',
        ];

        // 3. Menambah Data Administrator
        $adminCount = User::where('role', 'admin')->count();
        $progress[] = [
            'nama' => 'Menambah Data Administrator',
            'persen' => $adminCount > 0 ? 100 : 0,
            'status' => $adminCount > 0 ? 'Sudah dikerjakan' : 'Belum dikerjakan',
            'warna' => $adminCount > 0 ? 'green' : 'red',
        ];

        // 4. Generate User Guru dan Siswa
        $totalSiswaGuru = $counts['guru'] + $counts['siswa'];
        $userSiswaGuru = Guru::whereNotNull('user_id')->count() + Siswa::whereNotNull('user_id')->count();
        $persenGenUser = $totalSiswaGuru > 0 ? round(($userSiswaGuru / $totalSiswaGuru) * 100, 2) : 0;
        $statusGenUser = 'Belum dikerjakan';
        $warnaGenUser = 'red';
        if ($persenGenUser == 100) {
            $statusGenUser = 'Sudah dikerjakan';
            $warnaGenUser = 'green';
        } elseif ($persenGenUser > 0) {
            $statusGenUser = 'Proses';
            $warnaGenUser = 'yellow';
        }

        $progress[] = [
            'nama' => 'Generate User Guru dan Siswa',
            'persen' => $persenGenUser,
            'status' => $statusGenUser,
            'warna' => $warnaGenUser,
        ];

        return view('admin.dashboard', compact('sekolah', 'semester_aktif', 'semester_teks', 'counts', 'progress'));
    }

    public function toggleInputNilai()
    {
        $semester = Semester::where('is_aktif', true)->first();
        if ($semester) {
            $semester->status_input_nilai = !$semester->status_input_nilai;
            $semester->save();

            $status = $semester->status_input_nilai ? 'dibuka' : 'ditutup';
            return redirect()->back()->with('status', "Akses input nilai berhasil $status.");
        }

        return redirect()->back()->with('error', 'Tidak ada semester aktif ditemukan.');
    }

    public function sync()
    {
        return redirect()->route('admin.dashboard')->with('status', 'Fitur Sinkronisasi Dapodik belum diimplementasikan di versi V1.0.');
    }
}
