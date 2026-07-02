<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Nilai Rapor Kelas</title>
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 10pt;
            color: #000;
        }
        @page {
            margin-top: {{ $setting->margin_atas ?? 15 }}mm;
            margin-bottom: {{ $setting->margin_bawah ?? 15 }}mm;
            margin-left: {{ $setting->margin_kiri ?? 15 }}mm;
            margin-right: {{ $setting->margin_kanan ?? 15 }}mm;
        }
        .page-break { page-break-after: always; }
        .text-center { text-align: center; }
        .text-bold { font-weight: bold; }
        .text-left { text-align: left; }
        .text-right { text-align: right; }
        .uppercase { text-transform: uppercase; }
        
        table { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
        .table-border th, .table-border td {
            border: 1px solid #000;
            padding: 5px;
            vertical-align: top;
        }
        .table-border th { background-color: #f2f2f2; text-align: center; }
        
        .header-table td { padding: 3px; vertical-align: top; border: none; }
        .section-title { font-size: 11pt; font-weight: bold; margin-bottom: 5px; margin-top: 15px;}
        
        .signature-table { width: 100%; margin-top: 30px; border: none; }
        .signature-table td { border: none; text-align: center; vertical-align: top; }
    </style>
</head>
<body>

    @foreach($allData as $data)
        @php
            $siswa = $data['siswa'];
            $rombel = $data['rombel'];
            $sekolah = $data['sekolah'];
            $semesterAktif = $data['semesterAktif'];
            $kelompokA = $data['kelompokA'];
            $kelompokB = $data['kelompokB'];
            $kelompokC = $data['kelompokC'];
            $nilaiRapor = $data['nilaiRapor'];
            $ekskul = $data['ekskul'];
            $kehadiran = $data['kehadiran'];
            $catatanWali = $data['catatanWali'];
            $waliKelas = $data['waliKelas'];
        @endphp
        
        <!-- Header / Identitas -->
        <table class="header-table">
            <tr>
                <td width="15%">Nama Peserta Didik</td>
                <td width="2%">:</td>
                <td width="33%"><strong>{{ $siswa->nama_lengkap }}</strong></td>
                <td width="15%">Kelas</td>
                <td width="2%">:</td>
                <td width="33%">{{ $rombel->nama_rombel ?? '-' }}</td>
            </tr>
            <tr>
                <td>NISN/NIS</td>
                <td>:</td>
                <td>{{ $siswa->nisn ?: '-' }} / {{ $siswa->nis ?: '-' }}</td>
                <td>Fase</td>
                <td>:</td>
                <td>{{ $rombel->fase ?? '-' }}</td>
            </tr>
            <tr>
                <td>Nama Sekolah</td>
                <td>:</td>
                <td>{{ $sekolah->nama_sekolah ?? '-' }}</td>
                <td>Semester</td>
                <td>:</td>
                <td>{{ $semesterAktif->nama_semester ?? '-' }}</td>
            </tr>
            <tr>
                <td>Alamat Sekolah</td>
                <td>:</td>
                <td>{{ $sekolah->alamat_sekolah ?? '-' }}</td>
                <td>Tahun Pelajaran</td>
                <td>:</td>
                <td>{{ $semesterAktif ? substr($semesterAktif->nama_semester, 0, 9) : '-' }}</td>
            </tr>
        </table>

        <hr style="border-top: 2px solid #000; margin-bottom: 15px;">

        <!-- Capaian Kompetensi -->
        <div class="text-center text-bold" style="font-size: 12pt; margin-bottom: 15px;">
            LAPORAN HASIL BELAJAR (RAPOR)
        </div>

        <table class="table-border">
            <thead>
                <tr>
                    <th width="5%">No</th>
                    <th width="25%">Mata Pelajaran</th>
                    <th width="10%">Nilai Akhir</th>
                    <th width="60%">Capaian Kompetensi</th>
                </tr>
            </thead>
            <tbody>
                @php $no = 1; @endphp
                
                @if(count($kelompokA) > 0)
                    <tr>
                        <td colspan="4" class="text-bold">Kelompok A (Muatan Nasional)</td>
                    </tr>
                    @foreach($kelompokA as $mapel)
                        @php 
                            $nilai = $nilaiRapor->get($mapel->id); 
                        @endphp
                        <tr>
                            <td class="text-center">{{ $no++ }}</td>
                            <td>{{ $mapel->nama_mapel }}</td>
                            <td class="text-center font-bold">{{ $nilai ? round($nilai->nilai_akhir) : '-' }}</td>
                            <td style="font-size: 9pt;">
                                @if($nilai)
                                    <strong>Mencapai Kompetensi dengan Sangat Baik:</strong><br>
                                    {{ $nilai->deskripsi_tertinggi ?: '-' }}
                                    <br><br>
                                    <strong>Perlu Peningkatan dalam Kompetensi:</strong><br>
                                    {{ $nilai->deskripsi_terendah ?: '-' }}
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                    @endforeach
                @endif

                @if(count($kelompokB) > 0)
                    <tr>
                        <td colspan="4" class="text-bold">Kelompok B (Muatan Kewilayahan)</td>
                    </tr>
                    @foreach($kelompokB as $mapel)
                        @php 
                            $nilai = $nilaiRapor->get($mapel->id); 
                        @endphp
                        <tr>
                            <td class="text-center">{{ $no++ }}</td>
                            <td>{{ $mapel->nama_mapel }}</td>
                            <td class="text-center font-bold">{{ $nilai ? round($nilai->nilai_akhir) : '-' }}</td>
                            <td style="font-size: 9pt;">
                                @if($nilai)
                                    <strong>Mencapai Kompetensi dengan Sangat Baik:</strong><br>
                                    {{ $nilai->deskripsi_tertinggi ?: '-' }}
                                    <br><br>
                                    <strong>Perlu Peningkatan dalam Kompetensi:</strong><br>
                                    {{ $nilai->deskripsi_terendah ?: '-' }}
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                    @endforeach
                @endif

                @if(count($kelompokC) > 0)
                    <tr>
                        <td colspan="4" class="text-bold">Kelompok C (Muatan Lokal)</td>
                    </tr>
                    @foreach($kelompokC as $mapel)
                        @php 
                            $nilai = $nilaiRapor->get($mapel->id); 
                        @endphp
                        <tr>
                            <td class="text-center">{{ $no++ }}</td>
                            <td>{{ $mapel->nama_mapel }}</td>
                            <td class="text-center font-bold">{{ $nilai ? round($nilai->nilai_akhir) : '-' }}</td>
                            <td style="font-size: 9pt;">
                                @if($nilai)
                                    <strong>Mencapai Kompetensi dengan Sangat Baik:</strong><br>
                                    {{ $nilai->deskripsi_tertinggi ?: '-' }}
                                    <br><br>
                                    <strong>Perlu Peningkatan dalam Kompetensi:</strong><br>
                                    {{ $nilai->deskripsi_terendah ?: '-' }}
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>

        <div class="page-break"></div>

        <div class="section-title">Ekstrakurikuler</div>
        <table class="table-border">
            <thead>
                <tr>
                    <th width="5%">No</th>
                    <th width="30%">Kegiatan Ekstrakurikuler</th>
                    <th width="65%">Keterangan / Deskripsi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($ekskul as $idx => $eks)
                    <tr>
                        <td class="text-center">{{ $idx + 1 }}</td>
                        <td>{{ $eks->ekstrakurikuler->nama_ekstrakurikuler }}</td>
                        <td>{{ $eks->deskripsi }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="text-center">Tidak ada kegiatan ekstrakurikuler yang diikuti.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="section-title">Ketidakhadiran</div>
        <table class="table-border" style="width: 50%;">
            <tbody>
                <tr>
                    <td width="50%">Sakit</td>
                    <td width="50%" class="text-center">{{ $kehadiran ? $kehadiran->sakit : 0 }} hari</td>
                </tr>
                <tr>
                    <td>Izin</td>
                    <td class="text-center">{{ $kehadiran ? $kehadiran->izin : 0 }} hari</td>
                </tr>
                <tr>
                    <td>Tanpa Keterangan</td>
                    <td class="text-center">{{ $kehadiran ? $kehadiran->tanpa_keterangan : 0 }} hari</td>
                </tr>
            </tbody>
        </table>

        <div class="section-title">Catatan Wali Kelas</div>
        <div style="border: 1px solid #000; padding: 10px; min-height: 50px;">
            {{ $catatanWali ? $catatanWali->catatan : '-' }}
        </div>

        <!-- Tanda Tangan -->
        <table class="signature-table">
            <tr>
                <td width="33%">
                    Mengetahui,<br>
                    Orang Tua/Wali<br>
                    <br><br><br><br>
                    <strong>...................................</strong>
                </td>
                <td width="34%">
                    @if(($setting->tampilkan_ttd_kepsek ?? true) && ($setting->posisi_ttd_kepsek ?? 'kanan') == 'tengah')
                        Mengetahui,<br>
                        Kepala Sekolah<br>
                        <br><br><br><br>
                        <strong>{{ $sekolah->nama_kepala_sekolah ?? '...........................' }}</strong><br>
                        NIP. {{ $sekolah->nip_kepala_sekolah ?? '-' }}
                    @endif
                </td>
                <td width="33%">
                    {{ $sekolah->kabupaten_kota ?? '..............' }}, {{ date('d F Y') }}<br>
                    Wali Kelas<br>
                    <br><br><br><br>
                    @if($setting->tampilkan_nama_wali ?? true)
                        <strong>{{ $waliKelas->nama_lengkap ?? '...................................' }}</strong><br>
                        NIP. {{ $waliKelas->nip ?? '-' }}
                    @else
                        <strong>...................................</strong>
                    @endif
                </td>
            </tr>
        </table>

        @if(($setting->tampilkan_ttd_kepsek ?? true) && ($setting->posisi_ttd_kepsek ?? 'kanan') != 'tengah')
            <table class="signature-table" style="margin-top: 10px;">
                <tr>
                    <td width="100%" class="text-center">
                        Mengetahui,<br>
                        Kepala Sekolah<br>
                        <br><br><br><br>
                        <strong>{{ $sekolah->nama_kepala_sekolah ?? '...........................' }}</strong><br>
                        NIP. {{ $sekolah->nip_kepala_sekolah ?? '-' }}
                    </td>
                </tr>
            </table>
        @endif

        @if(!$loop->last)
            <div class="page-break"></div>
        @endif
    @endforeach

</body>
</html>
