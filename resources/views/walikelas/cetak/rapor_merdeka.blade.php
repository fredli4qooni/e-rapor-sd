
    <table style="width: 100%; border-bottom: 3px solid black; margin-bottom: 20px; border: none; padding-bottom: 10px;">
        <tr>
            <td style="width: 15%; text-align: center; border: none;">
                @php
                    $logoData = null;
                    if(isset($sekolah->logo_sekolah) && file_exists(public_path('storage/'.$sekolah->logo_sekolah))) {
                        $path = public_path('storage/'.$sekolah->logo_sekolah);
                        $type = pathinfo($path, PATHINFO_EXTENSION);
                        $data = file_get_contents($path);
                        $logoData = 'data:image/' . $type . ';base64,' . base64_encode($data);
                    }
                @endphp
                @if($logoData)
                    <img src="{{ $logoData }}" style="width: 80px; height: 80px; border-radius: 50%;">
                @else
                    <div style="width: 80px; height: 80px; border: 1px dashed black; border-radius: 50%; line-height: 80px; font-weight: bold; margin: 0 auto;">LOGO</div>
                @endif
            </td>
            <td style="width: 85%; text-align: center; border: none;">
                <h3 style="margin: 0; font-size: 18px;">PEMERINTAH KABUPATEN/KOTA</h3>
                <h2 style="margin: 0; font-size: 22px;">DINAS PENDIDIKAN DAN KEBUDAYAAN</h2>
                <h1 style="margin: 0; font-size: 26px; text-transform:uppercase;">{{ $sekolah->nama_sekolah ?? 'SD NEGERI 1 PERCONTOHAN' }}</h1>
                <p style="margin: 5px 0 0 0; font-size: 12px;">{{ $sekolah->alamat ?? 'Jl. Pendidikan No. 123, Kecamatan Ilmu' }}</p>
                <p style="margin: 0; font-size: 12px;">Email: {{ $sekolah->email ?? '-' }} | Website: {{ $sekolah->website ?? '-' }}</p>
            </td>
        </tr>
    </table>

    <div class="text-center title">LAPORAN HASIL BELAJAR<br>(KURIKULUM MERDEKA)</div>
    
    <p>Nama Siswa: {{ $siswa->nama_lengkap }} (NIS: {{ $siswa->nis }})<br>
    Kelas / Fase: {{ $siswa->kelas }} / {{ $siswa->fase }}</p>
    
    <table>
        <tr>
            <th width="5%">No</th>
            <th width="25%">Mata Pelajaran</th>
            <th width="10%">Nilai Akhir</th>
            <th>Capaian Kompetensi</th>
        </tr>
        @if(isset($nilaiRapors) && $nilaiRapors->count() > 0)
            @foreach($nilaiRapors as $index => $nilai)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>{{ $nilai->mapel->nama_mapel ?? 'Unknown' }}</td>
                <td class="text-center">{{ $nilai->nilai_akhir }}</td>
                <td>{{ $nilai->capaian_kompetensi }}</td>
            </tr>
            @endforeach
        @else
            <tr>
                <td colspan="4" class="text-center">Belum ada data nilai.</td>
            </tr>
        @endif
    </table>
    
    <h4>C. Ekstrakurikuler</h4>
    <table class="table">
        <tr>
            <th width="5%">No</th>
            <th width="35%">Kegiatan Ekstrakurikuler</th>
            <th>Keterangan</th>
        </tr>
        @if(isset($ekskuls) && $ekskuls->count() > 0)
            @foreach($ekskuls as $index => $ekskul)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>{{ $ekskul->ekstrakurikuler->nama_ekskul ?? 'Unknown' }}</td>
                <td>{{ $ekskul->predikat }} - {{ $ekskul->keterangan }}</td>
            </tr>
            @endforeach
        @else
        <tr>
            <td class="text-center" colspan="3">-</td>
        </tr>
        @endif
    </table>

    <h4>D. Ketidakhadiran</h4>
    <table class="table" style="width: 50%;">
        <tr>
            <td width="50%">Sakit</td>
            <td>: {{ $kehadiran->sakit ?? 0 }} hari</td>
        </tr>
        <tr>
            <td>Izin</td>
            <td>: {{ $kehadiran->izin ?? 0 }} hari</td>
        </tr>
        <tr>
            <td>Tanpa Keterangan</td>
            <td>: {{ $kehadiran->tanpa_keterangan ?? 0 }} hari</td>
        </tr>
    </table>

    <h4>E. Catatan Wali Kelas</h4>
    <div style="border: 1px solid #000; padding: 10px; min-height: 50px;">
        {{ $catatan->catatan ?? '-' }}
    </div>

    @if(isset($kenaikan))
    <div style="margin-top: 15px; border: 1px solid #000; padding: 10px; font-weight: bold;">
        Keputusan: Berdasarkan pencapaian seluruh kompetensi, peserta didik dinyatakan: 
        <span style="text-decoration: underline; font-size: 14pt;">{{ strtoupper($kenaikan->status_kenaikan ?? 'NAIK KELAS') }}</span>
    </div>
    @endif
