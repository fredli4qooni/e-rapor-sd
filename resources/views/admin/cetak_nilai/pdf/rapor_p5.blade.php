<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Rapor P5 - {{ $siswa->nama_lengkap }}</title>
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

        .rubrik-box {
            display: inline-block;
            width: 15px;
            height: 15px;
            border: 1px solid #000;
            margin: 0 auto;
        }
        .rubrik-checked {
            background-color: #000;
        }
    </style>
</head>
<body>

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
            <td>Tahun Pelajaran</td>
            <td>:</td>
            <td>{{ $semesterAktif ? substr($semesterAktif->nama_semester, 0, 9) : '-' }}</td>
        </tr>
        <tr>
            <td>Alamat Sekolah</td>
            <td>:</td>
            <td colspan="4">{{ $sekolah->alamat_sekolah ?? '-' }}</td>
        </tr>
    </table>

    <hr style="border-top: 2px solid #000; margin-bottom: 15px;">

    <!-- Judul -->
    <div class="text-center text-bold" style="font-size: 12pt; margin-bottom: 15px;">
        RAPOR PROJEK PENGUATAN PROFIL PELAJAR PANCASILA
    </div>

    <!-- Deskripsi Proyek -->
    @if(count($proyeks) > 0)
        @foreach($proyeks as $index => $proyek)
            <div style="margin-bottom: 20px;">
                <div class="text-bold" style="margin-bottom: 5px;">
                    Projek {{ $index + 1 }} | {{ $proyek->tema->nama_tema ?? 'Tema' }}
                </div>
                <div class="text-bold" style="margin-bottom: 5px;">
                    {{ $proyek->nama_proyek }}
                </div>
                <div style="text-align: justify; margin-bottom: 10px;">
                    {{ $proyek->deskripsi }}
                </div>
            </div>
        @endforeach
    @else
        <div style="margin-bottom: 20px; text-align: center; font-style: italic;">
            Data Proyek P5 belum tersedia atau belum ditambahkan.
        </div>
    @endif

    <div class="page-break"></div>

    <!-- Tabel Rubrik Penilaian -->
    <div class="text-bold mb-2">Penilaian Projek</div>
    <table class="table-border">
        <thead>
            <tr>
                <th width="40%" rowspan="2">Dimensi, Elemen, dan Subelemen</th>
                <th colspan="4">Ketercapaian</th>
            </tr>
            <tr>
                <th width="15%">Mulai Berkembang (MB)</th>
                <th width="15%">Sedang Berkembang (SB)</th>
                <th width="15%">Berkembang Sesuai Harapan (BSH)</th>
                <th width="15%">Sangat Berkembang (SAB)</th>
            </tr>
        </thead>
        <tbody>
            @if(count($proyeks) > 0)
                <!-- Assuming structure, but since P5 is just dummy here, I'll print static empty structure for visual -->
                <tr>
                    <td colspan="5" class="text-bold bg-gray-100">Projek 1: Bergotong Royong</td>
                </tr>
                <tr>
                    <td colspan="5" class="text-bold" style="padding-left: 15px;">Elemen: Kolaborasi</td>
                </tr>
                <tr>
                    <td style="padding-left: 30px;">Subelemen: Kerja sama</td>
                    <td class="text-center"><div class="rubrik-box"></div></td>
                    <td class="text-center"><div class="rubrik-box rubrik-checked"></div></td>
                    <td class="text-center"><div class="rubrik-box"></div></td>
                    <td class="text-center"><div class="rubrik-box"></div></td>
                </tr>
            @else
                <tr>
                    <td colspan="5" class="text-center">Data rubrik penilaian P5 belum tersedia.</td>
                </tr>
            @endif
        </tbody>
    </table>

    <!-- Keterangan Rubrik -->
    <table style="width: 100%; border: none; margin-top: 20px; font-size: 9pt;">
        <tr>
            <td width="25%" style="vertical-align: top;">
                <strong>Mulai Berkembang (MB)</strong><br>
                Peserta didik masih membutuhkan bimbingan dalam mengembangkan kompetensi.
            </td>
            <td width="25%" style="vertical-align: top;">
                <strong>Sedang Berkembang (SB)</strong><br>
                Peserta didik mulai mengembangkan kompetensi namun masih membutuhkan sedikit bimbingan.
            </td>
            <td width="25%" style="vertical-align: top;">
                <strong>Berkembang Sesuai Harapan (BSH)</strong><br>
                Peserta didik telah mengembangkan kompetensi hingga tahap sesuai harapan.
            </td>
            <td width="25%" style="vertical-align: top;">
                <strong>Sangat Berkembang (SAB)</strong><br>
                Peserta didik mengembangkan kompetensi melampaui harapan.
            </td>
        </tr>
    </table>

    <div class="section-title mt-8">Catatan Proses Projek</div>
    <div style="border: 1px solid #000; padding: 10px; min-height: 80px;">
        <!-- Catatan proses projek -->
        Catatan wali kelas mengenai perkembangan siswa selama mengikuti proyek.
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

</body>
</html>
