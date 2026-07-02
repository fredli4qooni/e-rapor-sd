<table>
    <thead>
        <tr>
            <th colspan="{{ 4 + count($mapels) + 3 }}" style="font-weight: bold; text-align: center; font-size: 14pt;">
                LEGER NILAI RAPOR KELAS {{ $rombel->nama_rombel }}
            </th>
        </tr>
        <tr>
            <th colspan="{{ 4 + count($mapels) + 3 }}" style="font-weight: bold; text-align: center;">
                {{ $semua_semester ? 'SEMUA SEMESTER' : 'SEMESTER ' . ($semesterAktif ? $semesterAktif->nama_semester : '-') }}
            </th>
        </tr>
        <tr>
            <th colspan="{{ 4 + count($mapels) + 3 }}"></th>
        </tr>
        <tr>
            <th rowspan="2" style="font-weight: bold; text-align: center; border: 1px solid #000; vertical-align: middle;">No</th>
            <th rowspan="2" style="font-weight: bold; text-align: center; border: 1px solid #000; vertical-align: middle;">NISN</th>
            <th rowspan="2" style="font-weight: bold; text-align: center; border: 1px solid #000; vertical-align: middle;">NIS</th>
            <th rowspan="2" style="font-weight: bold; text-align: center; border: 1px solid #000; vertical-align: middle;">Nama Siswa</th>
            <th colspan="{{ count($mapels) }}" style="font-weight: bold; text-align: center; border: 1px solid #000;">Mata Pelajaran</th>
            <th colspan="3" style="font-weight: bold; text-align: center; border: 1px solid #000;">Kehadiran</th>
        </tr>
        <tr>
            @foreach($mapels as $mapel)
                <th style="font-weight: bold; text-align: center; border: 1px solid #000;">{{ $mapel->singkatan ?? $mapel->nama_mapel }}</th>
            @endforeach
            <th style="font-weight: bold; text-align: center; border: 1px solid #000;">S</th>
            <th style="font-weight: bold; text-align: center; border: 1px solid #000;">I</th>
            <th style="font-weight: bold; text-align: center; border: 1px solid #000;">A</th>
        </tr>
    </thead>
    <tbody>
        @foreach($siswas as $index => $siswa)
            @php
                $nilaiList = collect($semuaNilai->get($siswa->id) ?? [])->groupBy('mata_pelajaran_id');
                // Calculate average for all semesters if needed, or just sum it if it's 1 semester
                $kehadiranSiswa = collect($semuaKehadiran->get($siswa->id) ?? []);
                
                $sakit = $kehadiranSiswa->sum('sakit');
                $izin = $kehadiranSiswa->sum('izin');
                $alpa = $kehadiranSiswa->sum('alpa');
            @endphp
            <tr>
                <td style="border: 1px solid #000; text-align: center;">{{ $index + 1 }}</td>
                <td style="border: 1px solid #000; text-align: center;">{{ $siswa->nisn }}</td>
                <td style="border: 1px solid #000; text-align: center;">{{ $siswa->nis }}</td>
                <td style="border: 1px solid #000;">{{ $siswa->nama_lengkap }}</td>
                
                @foreach($mapels as $mapel)
                    @php
                        $nilaiMapel = $nilaiList->get($mapel->id);
                        $avgNilai = 0;
                        if($nilaiMapel && count($nilaiMapel) > 0) {
                            $avgNilai = $nilaiMapel->avg('nilai_akhir');
                        }
                    @endphp
                    <td style="border: 1px solid #000; text-align: center;">
                        {{ $avgNilai > 0 ? round($avgNilai) : '-' }}
                    </td>
                @endforeach
                
                <td style="border: 1px solid #000; text-align: center;">{{ $sakit > 0 ? $sakit : '-' }}</td>
                <td style="border: 1px solid #000; text-align: center;">{{ $izin > 0 ? $izin : '-' }}</td>
                <td style="border: 1px solid #000; text-align: center;">{{ $alpa > 0 ? $alpa : '-' }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
