<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SekolahController extends Controller
{
    public function index()
    {
        $sekolah = \App\Models\Sekolah::first();
        if (!$sekolah) {
            $sekolah = new \App\Models\Sekolah();
        }
        
        $kepsek = \App\Models\Guru::where('is_kepsek', true)->first();

        return view('admin.sekolah.index', compact('sekolah', 'kepsek'));
    }

    public function updateKepsek(\Illuminate\Http\Request $request)
    {
        $request->validate([
            'nama_lengkap' => 'required|string|max:200',
            'gelar_depan' => 'nullable|string|max:50',
            'gelar_belakang' => 'nullable|string|max:50',
            'nip_tampil' => 'nullable|string|max:30',
        ]);

        $kepsek = \App\Models\Guru::where('is_kepsek', true)->first();
        if ($kepsek) {
            $kepsek->update([
                'nama_lengkap' => $request->nama_lengkap,
                'gelar_depan' => $request->gelar_depan,
                'gelar_belakang' => $request->gelar_belakang,
                'nip_tampil' => $request->nip_tampil,
            ]);
        }

        return redirect()->route('admin.sekolah.index')->with('status', 'Data Kepala Sekolah berhasil diperbarui!');
    }

    public function update(\Illuminate\Http\Request $request)
    {
        $request->validate([
            'npsn' => 'required|string|max:10',
            'nama_sekolah' => 'required|string|max:200',
            'alamat' => 'nullable|string',
            'kecamatan' => 'nullable|string|max:100',
            'kabupaten' => 'nullable|string|max:100',
            'provinsi' => 'nullable|string|max:100',
            'logo_sekolah' => 'nullable|image|max:2048',
            'kop_sekolah' => 'nullable|image|max:2048',
            'logo_pemda' => 'nullable|image|max:2048',
            'ttd_kepsek' => 'nullable|image|max:2048',
        ]);

        $sekolah = \App\Models\Sekolah::first() ?? new \App\Models\Sekolah();
        
        $sekolah->npsn = $request->npsn;
        $sekolah->nama_sekolah = $request->nama_sekolah;
        $sekolah->alamat = $request->alamat;
        $sekolah->kecamatan = $request->kecamatan;
        $sekolah->kabupaten = $request->kabupaten;
        $sekolah->provinsi = $request->provinsi;

        if ($request->hasFile('logo_sekolah')) {
            $path = $request->file('logo_sekolah')->store('sekolah', 'public');
            $sekolah->logo_sekolah = $path;
        }

        if ($request->hasFile('kop_sekolah')) {
            $path = $request->file('kop_sekolah')->store('sekolah', 'public');
            $sekolah->kop_sekolah = $path;
        }

        if ($request->hasFile('logo_pemda')) {
            $path = $request->file('logo_pemda')->store('sekolah', 'public');
            $sekolah->logo_pemda = $path;
        }

        if ($request->hasFile('ttd_kepsek')) {
            $path = $request->file('ttd_kepsek')->store('sekolah', 'public');
            $sekolah->ttd_kepsek = $path;
        }

        $sekolah->save();

        return redirect()->route('admin.sekolah.index')->with('status', 'Data sekolah berhasil diperbarui!');
    }
}
