<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Pelengkap Rapor - {{ $siswa->nama_lengkap }}</title>
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 11pt;
            color: #000;
        }
        @page {
            margin-top: {{ $setting->margin_atas ?? 15 }}mm;
            margin-bottom: {{ $setting->margin_bawah ?? 15 }}mm;
            margin-left: {{ $setting->margin_kiri ?? 15 }}mm;
            margin-right: {{ $setting->margin_kanan ?? 15 }}mm;
        }
        .page-break {
            page-break-after: always;
        }
        .text-center { text-align: center; }
        .text-bold { font-weight: bold; }
        .uppercase { text-transform: uppercase; }
        .m-0 { margin: 0; }
        .mt-2 { margin-top: 10px; }
        .mt-4 { margin-top: 20px; }
        .mt-8 { margin-top: 40px; }
        .mb-2 { margin-bottom: 10px; }
        .mb-4 { margin-bottom: 20px; }
        
        /* COVER PAGE */
        .cover-title {
            font-size: 24pt;
            margin-top: 50px;
            margin-bottom: 30px;
        }
        .cover-subtitle {
            font-size: 18pt;
            margin-bottom: 50px;
        }
        .cover-logo {
            width: 150px;
            height: auto;
            margin: 50px auto;
        }
        .cover-student-box {
            border: 2px solid #000;
            padding: 20px;
            width: 60%;
            margin: 0 auto;
            border-radius: 10px;
        }
        .cover-student-name {
            font-size: 16pt;
            margin-bottom: 10px;
        }
        .cover-student-nis {
            font-size: 14pt;
        }
        .cover-footer {
            margin-top: 80px;
            font-size: 16pt;
        }

        /* DATA SEKOLAH & SISWA */
        .section-title {
            font-size: 14pt;
            text-decoration: underline;
            margin-bottom: 20px;
        }
        table.identitas {
            width: 90%;
            margin: 0 auto;
            border-collapse: collapse;
        }
        table.identitas td {
            padding: 6px;
            vertical-align: top;
        }
        table.identitas td:nth-child(1) {
            width: 30%;
        }
        table.identitas td:nth-child(2) {
            width: 5%;
            text-align: center;
        }
        table.identitas td:nth-child(3) {
            width: 65%;
        }
    </style>
</head>
<body>

    <!-- HALAMAN COVER -->
    <div class="text-center">
        <h1 class="cover-title uppercase">LAPORAN HASIL BELAJAR<br>(RAPOR)</h1>
        
        <h2 class="cover-subtitle uppercase">SEKOLAH DASAR<br>(SD)</h2>

        @if($sekolah && $sekolah->logo_sekolah)
            <img src="{{ public_path('storage/' . $sekolah->logo_sekolah) }}" class="cover-logo" alt="Logo Sekolah">
        @else
            <div style="height: 150px; margin: 50px auto; width: 150px; border: 1px dashed #ccc; line-height: 150px;">LOGO</div>
        @endif

        <div class="mt-8 mb-4 uppercase text-bold" style="font-size: 14pt;">NAMA PESERTA DIDIK</div>
        
        <div class="cover-student-box">
            <div class="cover-student-name text-bold uppercase">{{ $siswa->nama_lengkap }}</div>
            <div class="cover-student-nis uppercase">NISN: {{ $siswa->nisn ?: '-' }}</div>
        </div>

        <div class="cover-footer uppercase text-bold">
            KEMENTERIAN PENDIDIKAN, KEBUDAYAAN, RISET, DAN TEKNOLOGI<br>
            REPUBLIK INDONESIA
        </div>
    </div>

    <div class="page-break"></div>

    <!-- HALAMAN IDENTITAS SEKOLAH -->
    <div class="text-center section-title text-bold uppercase mt-8">
        RAPOR PESERTA DIDIK DAN PROFIL PESERTA DIDIK
    </div>

    <table class="identitas mt-4">
        <tr>
            <td>Nama Sekolah</td>
            <td>:</td>
            <td class="text-bold">{{ $sekolah->nama_sekolah ?? '-' }}</td>
        </tr>
        <tr>
            <td>NPSN</td>
            <td>:</td>
            <td>{{ $sekolah->npsn ?? '-' }}</td>
        </tr>
        <tr>
            <td>Alamat Sekolah</td>
            <td>:</td>
            <td>{{ $sekolah->alamat_sekolah ?? '-' }}</td>
        </tr>
        <tr>
            <td>Kode Pos</td>
            <td>:</td>
            <td>{{ $sekolah->kode_pos ?? '-' }}</td>
        </tr>
        <tr>
            <td>Desa/Kelurahan</td>
            <td>:</td>
            <td>{{ $sekolah->desa_kelurahan ?? '-' }}</td>
        </tr>
        <tr>
            <td>Kecamatan</td>
            <td>:</td>
            <td>{{ $sekolah->kecamatan ?? '-' }}</td>
        </tr>
        <tr>
            <td>Kabupaten/Kota</td>
            <td>:</td>
            <td>{{ $sekolah->kabupaten_kota ?? '-' }}</td>
        </tr>
        <tr>
            <td>Provinsi</td>
            <td>:</td>
            <td>{{ $sekolah->provinsi ?? '-' }}</td>
        </tr>
        <tr>
            <td>Website</td>
            <td>:</td>
            <td>{{ $sekolah->website ?? '-' }}</td>
        </tr>
        <tr>
            <td>Email</td>
            <td>:</td>
            <td>{{ $sekolah->email ?? '-' }}</td>
        </tr>
    </table>

    <div class="page-break"></div>

    <!-- HALAMAN IDENTITAS SISWA -->
    <div class="text-center section-title text-bold uppercase mt-8">
        KETERANGAN TENTANG DIRI PESERTA DIDIK
    </div>

    <table class="identitas mt-4">
        <tr>
            <td>1. Nama Peserta Didik (Lengkap)</td>
            <td>:</td>
            <td class="text-bold uppercase">{{ $siswa->nama_lengkap }}</td>
        </tr>
        <tr>
            <td>2. Nomor Induk/NISN</td>
            <td>:</td>
            <td>{{ $siswa->nis ?: '-' }} / {{ $siswa->nisn ?: '-' }}</td>
        </tr>
        <tr>
            <td>3. Tempat, Tanggal Lahir</td>
            <td>:</td>
            <td>{{ $siswa->tempat_lahir }}, {{ $siswa->tanggal_lahir ? \Carbon\Carbon::parse($siswa->tanggal_lahir)->translatedFormat('d F Y') : '-' }}</td>
        </tr>
        <tr>
            <td>4. Jenis Kelamin</td>
            <td>:</td>
            <td>{{ $siswa->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
        </tr>
        <tr>
            <td>5. Agama</td>
            <td>:</td>
            <td>{{ $siswa->agama }}</td>
        </tr>
        <tr>
            <td>6. Alamat Peserta Didik</td>
            <td>:</td>
            <td>{{ $siswa->alamat }}</td>
        </tr>
        <tr>
            <td>7. Nama Orang Tua</td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td style="padding-left: 20px;">a. Ayah</td>
            <td>:</td>
            <td>{{ $siswa->nama_ayah ?: '-' }}</td>
        </tr>
        <tr>
            <td style="padding-left: 20px;">b. Ibu</td>
            <td>:</td>
            <td>{{ $siswa->nama_ibu ?: '-' }}</td>
        </tr>
        <tr>
            <td>8. Pekerjaan Orang Tua</td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td style="padding-left: 20px;">a. Ayah</td>
            <td>:</td>
            <td>{{ $siswa->pekerjaan_ayah ?: '-' }}</td>
        </tr>
        <tr>
            <td style="padding-left: 20px;">b. Ibu</td>
            <td>:</td>
            <td>{{ $siswa->pekerjaan_ibu ?: '-' }}</td>
        </tr>
        <tr>
            <td>9. Nama Wali Peserta Didik</td>
            <td>:</td>
            <td>{{ $siswa->nama_wali ?: '-' }}</td>
        </tr>
        <tr>
            <td>10. Pekerjaan Wali</td>
            <td>:</td>
            <td>{{ $siswa->pekerjaan_wali ?: '-' }}</td>
        </tr>
    </table>

    <table style="width: 100%; margin-top: 50px;">
        <tr>
            <td style="width: 30%; text-align: center;">
                <div style="border: 1px dashed #000; width: 3cm; height: 4cm; margin: 0 auto; line-height: 4cm;">
                    Pas Foto 3x4
                </div>
            </td>
            <td style="width: 20%;"></td>
            <td style="width: 50%; text-align: left; padding-left: 10%;">
                {{ $sekolah->kabupaten_kota ?? '..............' }}, ..........................<br>
                Kepala Sekolah,<br><br><br><br><br>
                <strong>{{ $sekolah->nama_kepala_sekolah ?? '..............................' }}</strong><br>
                NIP. {{ $sekolah->nip_kepala_sekolah ?? '-' }}
            </td>
        </tr>
    </table>

</body>
</html>
