<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Transkrip Nilai Ijazah Kelas</title>
    <style>
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 11pt;
            color: #000;
        }
        @page {
            margin-top: {{ $setting->margin_atas }}mm;
            margin-bottom: {{ $setting->margin_bawah }}mm;
            margin-left: {{ $setting->margin_kiri }}mm;
            margin-right: {{ $setting->margin_kanan }}mm;
        }
        .page-break {
            page-break-after: always;
        }
        .header {
            text-align: center;
            margin-bottom: {{ $setting->jarak_antar_identitas }}mm;
        }
        .kop-sekolah {
            width: {{ $setting->persentase_kop }}%;
            max-width: 100%;
        }
        .judul {
            text-align: center;
            font-weight: bold;
            font-size: 14pt;
            margin-bottom: {{ $setting->jarak_antar_identitas }}mm;
            height: {{ $setting->tinggi_judul }}mm;
            line-height: {{ $setting->tinggi_judul }}mm;
        }
        .identitas {
            width: 100%;
            margin-bottom: {{ $setting->jarak_antar_identitas }}mm;
        }
        .identitas td {
            vertical-align: top;
            padding: 2px 0;
        }
        .tabel-nilai {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: {{ $setting->jarak_antar_identitas }}mm;
        }
        .tabel-nilai th, .tabel-nilai td {
            border: 1px solid #000;
            padding: 4px;
            height: {{ $setting->tinggi_isi_tabel }}mm;
        }
        .tabel-nilai th {
            text-align: center;
            font-weight: bold;
        }
        .tabel-nilai .col-no { width: 5%; text-align: center; }
        .tabel-nilai .col-mapel { width: 75%; }
        .tabel-nilai .col-nilai { width: 20%; text-align: center; }
        .ttd-box {
            width: 100%;
            margin-top: 20px;
        }
        .ttd-box table {
            width: 100%;
        }
        .ttd-box td {
            width: 50%;
            vertical-align: bottom;
        }
        .ttd-kanan {
            text-align: left;
            padding-left: 20%;
        }
        .nama-siswa {
            @if($setting->tampilan_nama_siswa == 'huruf_kapital')
            text-transform: uppercase;
            @endif
        }
    </style>
</head>
<body>

    @foreach($siswas as $index => $siswa)
        @if($sekolah && $sekolah->kop_sekolah)
        <div class="header">
            <img src="{{ public_path('storage/' . $sekolah->kop_sekolah) }}" class="kop-sekolah" alt="Kop Sekolah">
        </div>
        @endif

        <div class="judul">
            TRANSKRIP NILAI IJAZAH
        </div>

        <table class="identitas">
            <tr>
                <td width="20%">Nama Siswa</td>
                <td width="2%">:</td>
                <td width="78%" class="nama-siswa font-bold">{{ $siswa->nama_lengkap }}</td>
            </tr>
            <tr>
                <td>Tempat, Tanggal Lahir</td>
                <td>:</td>
                <td>{{ $siswa->tempat_lahir }}, {{ $siswa->tanggal_lahir ? \Carbon\Carbon::parse($siswa->tanggal_lahir)->translatedFormat('d F Y') : '-' }}</td>
            </tr>
            <tr>
                <td>Nomor Induk Siswa</td>
                <td>:</td>
                <td>{{ $siswa->nis ?: '-' }} / {{ $siswa->nisn ?: '-' }}</td>
            </tr>
            <tr>
                <td>Nomor Ijazah</td>
                <td>:</td>
                <td>{{ $siswa->no_ijazah ?: '-' }}</td>
            </tr>
        </table>

        <table class="tabel-nilai">
            <thead>
                <tr>
                    <th class="col-no">No</th>
                    <th class="col-mapel">Mata Pelajaran</th>
                    <th class="col-nilai">Nilai Ijazah</th>
                </tr>
            </thead>
            <tbody>
                @php 
                    $totalNilai = 0; 
                    $jumlahMapel = 0;
                    $currentKelompok = '';
                    
                    // Get student grades
                    $nilaiList = collect($semuaNilai->get($siswa->id) ?? [])->keyBy('mata_pelajaran_id');
                @endphp
                
                @foreach($mappings as $map)
                    @if($map->kelompok && $map->kelompok != $currentKelompok)
                        <tr>
                            <td colspan="3" style="font-weight: bold; padding-left: 5px;">Kelompok {{ $map->kelompok }}</td>
                        </tr>
                        @php $currentKelompok = $map->kelompok; @endphp
                    @endif
                    
                    @php
                        $nilaiObj = $nilaiList->get($map->mata_pelajaran_id);
                        $nilaiAngka = $nilaiObj ? $nilaiObj->nilai : null;
                        if ($nilaiAngka !== null) {
                            $totalNilai += $nilaiAngka;
                            $jumlahMapel++;
                        }
                    @endphp
                    <tr>
                        <td class="col-no">{{ $map->no_urut }}</td>
                        <td class="col-mapel" style="padding-left: 5px;">{{ $map->nama_lokal ?: $map->mapel->nama_mapel }}</td>
                        <td class="col-nilai">
                            {{ $nilaiAngka !== null ? number_format($nilaiAngka, $setting->jumlah_angka_desimal, ',', '.') : '-' }}
                        </td>
                    </tr>
                @endforeach
                
                @if($setting->tampilkan_baris_rata_rata)
                    @php
                        $rataRata = $jumlahMapel > 0 ? $totalNilai / $jumlahMapel : 0;
                    @endphp
                    <tr>
                        <td colspan="2" style="text-align: center; font-weight: bold;">Rata-rata</td>
                        <td class="col-nilai" style="font-weight: bold;">
                            {{ number_format($rataRata, $setting->angka_desimal_rata_rata, ',', '.') }}
                        </td>
                    </tr>
                @endif
            </tbody>
        </table>

        <div class="ttd-box">
            <table>
                <tr>
                    <td></td>
                    <td class="ttd-kanan">
                        {{ $setting->tempat_tanggal_transkrip }}<br>
                        Kepala Sekolah,<br><br><br><br><br>
                        <strong>{{ $setting->nama_kepala_sekolah }}</strong><br>
                        @if($setting->nip_kepala_sekolah)
                            NIP. {{ $setting->nip_kepala_sekolah }}
                        @endif
                    </td>
                </tr>
            </table>
        </div>
        
        @if(!$loop->last)
            <div class="page-break"></div>
        @endif
    @endforeach

</body>
</html>
