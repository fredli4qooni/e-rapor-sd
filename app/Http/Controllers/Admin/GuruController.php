<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class GuruController extends Controller
{
    public function index()
    {
        $gurus = \App\Models\Guru::with('user')->get();
        return view('admin.guru.index', compact('gurus'));
    }

    public function create()
    {
        return view('admin.guru.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nip' => 'nullable|string|max:30',
            'nama_lengkap' => 'required|string|max:200',
            'gelar_depan' => 'nullable|string|max:50',
            'gelar_belakang' => 'nullable|string|max:50',
        ]);

        $sekolah = \App\Models\Sekolah::first();

        \App\Models\Guru::create([
            'sekolah_id' => $sekolah ? $sekolah->id : 1,
            'nip' => $request->nip,
            'nama_lengkap' => $request->nama_lengkap,
            'gelar_depan' => $request->gelar_depan,
            'gelar_belakang' => $request->gelar_belakang,
        ]);

        return redirect()->route('admin.guru.index')->with('status', 'Data Guru berhasil ditambahkan!');
    }

    public function edit(\App\Models\Guru $guru)
    {
        return view('admin.guru.edit', compact('guru'));
    }

    public function update(Request $request, \App\Models\Guru $guru)
    {
        $request->validate([
            'nip' => 'nullable|string|max:30',
            'nip_tampil' => 'nullable|string|max:30',
            'nama_lengkap' => 'required|string|max:200',
            'gelar_depan' => 'nullable|string|max:50',
            'gelar_belakang' => 'nullable|string|max:50',
        ]);

        $guru->update($request->only(['nip', 'nip_tampil', 'nama_lengkap', 'gelar_depan', 'gelar_belakang']));

        return redirect()->route('admin.guru.index')->with('status', 'Data Guru berhasil diperbarui!');
    }

    public function destroy(\App\Models\Guru $guru)
    {
        $guru->delete();
        return redirect()->route('admin.guru.index')->with('status', 'Data Guru berhasil dihapus!');
    }

    public function generateUser()
    {
        $gurus = \App\Models\Guru::whereNull('user_id')->get();
        $count = 0;

        foreach ($gurus as $guru) {
            // Coba gunakan NIP sebagai username, jika tidak ada gunakan nip_tampil atau sanitasi nama
            $username = $guru->nip ?: strtolower(preg_replace('/[^a-zA-Z0-9]/', '', $guru->nama_lengkap));
            
            // Cek apakah username sudah dipakai
            $checkUser = \App\Models\User::where('username', $username)->first();
            if ($checkUser) {
                $username = $username . rand(10, 99);
            }

            $user = \App\Models\User::create([
                'name' => $guru->nama_lengkap,
                'username' => $username,
                'email' => $username . '@erapor.local',
                'password' => \Illuminate\Support\Facades\Hash::make('password123'),
                'role' => 'guru',
            ]);

            $guru->update(['user_id' => $user->id]);
            $count++;
        }

        return redirect()->route('admin.guru.index')->with('status', "Berhasil me-generate $count akun User untuk Guru (Password default: password123)!");
    }
}
