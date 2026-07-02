<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\KelompokMapel;
use App\Models\MataPelajaran;

class MappingRaporController extends Controller
{
    public function index()
    {
        $kelompoks = KelompokMapel::orderBy('nama_kelompok')->get();
        // Get all subjects that should be displayed in transcript
        $mapels = MataPelajaran::where('is_transkrip', true)
            ->orderBy('kelompok_mapel_id')
            ->orderBy('nomor_urut')
            ->orderBy('nama_mapel')
            ->get();

        return view('admin.mapping_rapor.index', compact('kelompoks', 'mapels'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'mapel' => 'array',
            'mapel.*.kelompok_mapel_id' => 'nullable|exists:kelompok_mapels,id',
            'mapel.*.nomor_urut' => 'nullable|integer',
        ]);

        if ($request->has('mapel')) {
            foreach ($request->mapel as $id => $data) {
                MataPelajaran::where('id', $id)->update([
                    'kelompok_mapel_id' => $data['kelompok_mapel_id'],
                    'nomor_urut' => $data['nomor_urut']
                ]);
            }
        }

        return redirect()->route('admin.mapping_rapor.index')
            ->with('status', 'Mapping Rapor berhasil disimpan.');
    }
}
