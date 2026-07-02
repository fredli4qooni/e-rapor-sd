<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Cetak Pelengkap Rapor - {{ $siswa->nama_lengkap }}</title>
    <style>
        body { font-family: 'Times New Roman', Times, serif; margin: 40px; font-size: 14px; }
        .text-center { text-align: center; }
        .title { font-size: 18px; font-weight: bold; margin-bottom: 30px; text-transform: uppercase; }
        .table-info { width: 100%; border-collapse: collapse; margin-top: 20px; }
        .table-info td { padding: 8px; vertical-align: top; }
        .td-label { width: 30%; font-weight: bold; }
        .td-colon { width: 2%; }
    </style>
</head>
<body>
    <table style="width: 100%; border-bottom: 3px solid black; margin-bottom: 20px; border: none; padding-bottom: 10px;">
        <tr>
            <td style="width: 15%; text-align: center; border: none;">
                <!-- Dummy Logo -->
                <div style="width: 80px; height: 80px; border: 1px dashed black; border-radius: 50%; line-height: 80px; font-weight: bold; margin: 0 auto;">LOGO</div>
            </td>
            <td style="width: 85%; text-align: center; border: none;">
                <h3 style="margin: 0; font-size: 18px;">PEMERINTAH KABUPATEN DEMO</h3>
                <h2 style="margin: 0; font-size: 22px;">DINAS PENDIDIKAN DAN KEBUDAYAAN</h2>
                <h1 style="margin: 0; font-size: 26px;">SD NEGERI 1 PERCONTOHAN</h1>
                <p style="margin: 5px 0 0 0; font-size: 12px;">Jl. Pendidikan No. 123, Kecamatan Ilmu, Kabupaten Demo. Kode Pos: 12345</p>
                <p style="margin: 0; font-size: 12px;">Email: sdn1percontohan@demo.sch.id | Website: www.sdn1percontohan.sch.id</p>
            </td>
        </tr>
    </table>

    <div class="text-center title">Keterangan Tentang Diri Peserta Didik</div>
    
    <table class="table-info">
        <tr>
            <td class="td-label">1. Nama Lengkap</td>
            <td class="td-colon">:</td>
            <td>{{ $siswa->nama_lengkap }}</td>
        </tr>
        <tr>
            <td class="td-label">2. NIS / NISN</td>
            <td class="td-colon">:</td>
            <td>{{ $siswa->nis }} / {{ $siswa->nisn }}</td>
        </tr>
        <tr>
            <td class="td-label">3. Tempat, Tanggal Lahir</td>
            <td class="td-colon">:</td>
            <td>{{ $siswa->tempat_lahir }}, {{ $siswa->tanggal_lahir }}</td>
        </tr>
        <tr>
            <td class="td-label">4. Jenis Kelamin</td>
            <td class="td-colon">:</td>
            <td>{{ $siswa->jenis_kelamin == 'L' ? 'Laki-Laki' : 'Perempuan' }}</td>
        </tr>
        <tr>
            <td class="td-label">5. Nama Orang Tua</td>
            <td class="td-colon"></td>
            <td></td>
        </tr>
        <tr>
            <td class="td-label">&nbsp;&nbsp;&nbsp;a. Ayah</td>
            <td class="td-colon">:</td>
            <td>{{ $siswa->nama_ayah }}</td>
        </tr>
        <tr>
            <td class="td-label">&nbsp;&nbsp;&nbsp;b. Ibu</td>
            <td class="td-colon">:</td>
            <td>{{ $siswa->nama_ibu }}</td>
        </tr>
    </table>
    
    <div style="margin-top: 80px; width: 100%;">
        <table style="width: 100%;">
            <tr>
                <td style="width: 60%;"></td>
                <td style="width: 40%; text-align: center;">
                    <p>Kepala Sekolah,</p>
                    <br><br><br><br>
                    <p><strong>_____________________</strong></p>
                    <p>NIP. </p>
                </td>
            </tr>
        </table>
    </div>
</body>
</html>
