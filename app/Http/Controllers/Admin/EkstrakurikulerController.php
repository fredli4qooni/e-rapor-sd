<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class EkstrakurikulerController extends Controller
{
    public function index()
    {
        $ekskuls = \App\Models\Ekstrakurikuler::with('pembina')->orderBy('nama_ekskul')->get();
        return view('admin.ekskul.index', compact('ekskuls'));
    }

    public function create()
    {
        $gurus = \App\Models\Guru::orderBy('nama_lengkap')->get();
        return view('admin.ekskul.create', compact('gurus'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_ekskul' => 'required|string|max:100',
            'is_aktif' => 'required|boolean',
            'pembina_id' => 'nullable|exists:gurus,id',
        ]);

        \App\Models\Ekstrakurikuler::create([
            'nama_ekskul' => $request->nama_ekskul,
            'is_aktif' => $request->is_aktif,
            'pembina_id' => $request->pembina_id,
        ]);

        return redirect()->route('admin.ekskul.index')->with('status', 'Data Ekstrakurikuler berhasil ditambahkan!');
    }

    public function edit(\App\Models\Ekstrakurikuler $ekskul)
    {
        $gurus = \App\Models\Guru::orderBy('nama_lengkap')->get();
        return view('admin.ekskul.edit', compact('ekskul', 'gurus'));
    }

    public function update(Request $request, \App\Models\Ekstrakurikuler $ekskul)
    {
        $request->validate([
            'nama_ekskul' => 'required|string|max:100',
            'is_aktif' => 'required|boolean',
            'pembina_id' => 'nullable|exists:gurus,id',
        ]);

        $ekskul->update([
            'nama_ekskul' => $request->nama_ekskul,
            'is_aktif' => $request->is_aktif,
            'pembina_id' => $request->pembina_id,
        ]);

        return redirect()->route('admin.ekskul.index')->with('status', 'Data Ekstrakurikuler berhasil diperbarui!');
    }

    public function show(\App\Models\Ekstrakurikuler $ekskul)
    {
        $nilais = \App\Models\NilaiEkstrakurikuler::with(['siswa', 'rombel'])
            ->where('ekstrakurikuler_id', $ekskul->id)
            ->get();
            
        $rombels = \App\Models\Rombel::orderBy('tingkat')->orderBy('nama_rombel')->get();
        return view('admin.ekskul.show', compact('ekskul', 'nilais', 'rombels'));
    }

    public function tambahAnggota(Request $request, \App\Models\Ekstrakurikuler $ekskul)
    {
        $request->validate([
            'rombel_id' => 'required|exists:rombels,id',
        ]);

        $rombel = \App\Models\Rombel::with('siswas')->findOrFail($request->rombel_id);
        $count = 0;

        foreach ($rombel->siswas as $siswa) {
            $exists = \App\Models\NilaiEkstrakurikuler::where('ekstrakurikuler_id', $ekskul->id)
                ->where('siswa_id', $siswa->id)
                ->where('rombel_id', $rombel->id)
                ->exists();

            if (!$exists) {
                \App\Models\NilaiEkstrakurikuler::create([
                    'siswa_id' => $siswa->id,
                    'rombel_id' => $rombel->id,
                    'ekstrakurikuler_id' => $ekskul->id,
                ]);
                $count++;
            }
        }

        return back()->with('status', "$count Siswa dari Rombel {$rombel->nama_rombel} berhasil ditambahkan ke Ekstrakurikuler.");
    }

    public function hapusAnggota(\App\Models\Ekstrakurikuler $ekskul, \App\Models\NilaiEkstrakurikuler $nilai)
    {
        if ($nilai->ekstrakurikuler_id == $ekskul->id) {
            $nilai->delete();
        }
        return back()->with('status', 'Anggota berhasil dihapus dari Ekstrakurikuler.');
    }

    public function destroy(\App\Models\Ekstrakurikuler $ekskul)
    {
        $ekskul->delete();
        return redirect()->route('admin.ekskul.index')->with('status', 'Data Ekstrakurikuler berhasil dihapus!');
    }
}
