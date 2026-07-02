<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SiswaController extends Controller
{
    public function index()
    {
        $siswas = \App\Models\Siswa::with('user')->get();
        return view('admin.siswa.index', compact('siswas'));
    }

    public function create()
    {
        return view('admin.siswa.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nisn' => 'required|string|max:20|unique:siswas,nisn',
            'nis' => 'nullable|string|max:20',
            'nama_lengkap' => 'required|string|max:200',
            'jenis_kelamin' => 'required|in:L,P',
            'tanggal_lahir' => 'nullable|date',
            'tempat_lahir' => 'nullable|string|max:100',
            'foto' => 'nullable|image|max:2048',
        ]);

        $sekolah = \App\Models\Sekolah::first();

        $data = [
            'sekolah_id' => $sekolah ? $sekolah->id : 1,
            'nisn' => $request->nisn,
            'nis' => $request->nis,
            'nama_lengkap' => $request->nama_lengkap,
            'jenis_kelamin' => $request->jenis_kelamin,
            'tanggal_lahir' => $request->tanggal_lahir,
            'tempat_lahir' => $request->tempat_lahir,
        ];

        if ($request->hasFile('foto')) {
            $path = $request->file('foto')->store('siswa', 'public');
            $data['foto'] = $path;
        }

        \App\Models\Siswa::create($data);

        return redirect()->route('admin.siswa.index')->with('status', 'Data Siswa berhasil ditambahkan!');
    }

    public function edit(\App\Models\Siswa $siswa)
    {
        return view('admin.siswa.edit', compact('siswa'));
    }

    public function update(Request $request, \App\Models\Siswa $siswa)
    {
        $request->validate([
            'nisn' => 'required|string|max:20|unique:siswas,nisn,' . $siswa->id,
            'nis' => 'nullable|string|max:20',
            'nama_lengkap' => 'required|string|max:200',
            'jenis_kelamin' => 'required|in:L,P',
            'tanggal_lahir' => 'nullable|date',
            'tempat_lahir' => 'nullable|string|max:100',
            'nama_ayah' => 'nullable|string|max:200',
            'nama_ibu' => 'nullable|string|max:200',
            'no_ijazah' => 'nullable|string|max:50',
            'no_transkrip' => 'nullable|string|max:50',
            'tgl_lulus' => 'nullable|date',
            'foto' => 'nullable|image|max:2048',
        ]);

        $data = $request->only([
            'nisn', 'nis', 'nama_lengkap', 'jenis_kelamin', 'tanggal_lahir', 'tempat_lahir',
            'nama_ayah', 'nama_ibu', 'no_ijazah', 'no_transkrip', 'tgl_lulus'
        ]);

        if ($request->hasFile('foto')) {
            $path = $request->file('foto')->store('siswa', 'public');
            $data['foto'] = $path;
        }

        $siswa->update($data);

        return redirect()->route('admin.siswa.index')->with('status', 'Data Siswa berhasil diperbarui!');
    }

    public function destroy(\App\Models\Siswa $siswa)
    {
        $siswa->delete();
        return redirect()->route('admin.siswa.index')->with('status', 'Data Siswa berhasil dihapus!');
    }

    public function generateUser()
    {
        $siswas = \App\Models\Siswa::whereNull('user_id')->get();
        $count = 0;

        foreach ($siswas as $siswa) {
            $username = $siswa->nisn;
            
            // Cek apakah username sudah dipakai
            $checkUser = \App\Models\User::where('username', $username)->first();
            if ($checkUser) {
                $username = $username . rand(10, 99);
            }

            $user = \App\Models\User::create([
                'name' => $siswa->nama_lengkap,
                'username' => $username,
                'email' => $username . '@erapor.local',
                'password' => \Illuminate\Support\Facades\Hash::make('password123'),
                'role' => 'siswa',
            ]);

            $siswa->update(['user_id' => $user->id]);
            $count++;
        }

        return redirect()->route('admin.siswa.index')->with('status', "Berhasil me-generate $count akun User untuk Siswa (Password default: password123)!");
    }
}
