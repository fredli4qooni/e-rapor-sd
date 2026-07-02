<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Cetak Pelengkap Rapor</title>
    <style>
        @page {
            margin-top: {{ $setting->margin_atas ?? 20 }}mm;
            margin-bottom: {{ $setting->margin_bawah ?? 20 }}mm;
            margin-left: {{ $setting->margin_kiri ?? 20 }}mm;
            margin-right: {{ $setting->margin_kanan ?? 20 }}mm;
        }
        body { font-family: 'Times New Roman', Times, serif; font-size: 14px; }
        .text-center { text-align: center; }
        .title { font-size: 18px; font-weight: bold; margin-bottom: 30px; text-transform: uppercase; }
        .table-info { width: 100%; border-collapse: collapse; margin-top: 20px; }
        .table-info td { padding: 8px; vertical-align: top; }
        .td-label { width: 30%; font-weight: bold; }
        .td-colon { width: 2%; }
        .page-break { page-break-after: always; }
    </style>
</head>
<body>
    @foreach($siswas as $index => $siswa)
        <table style="width: 100%; border-bottom: 3px solid black; margin-bottom: 20px; border-left: none; border-right:none; border-top:none; padding-bottom: 10px;">
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
            <tr>
                <td class="td-label">6. Pekerjaan Orang Tua</td>
                <td class="td-colon"></td>
                <td></td>
            </tr>
            <tr>
                <td class="td-label">&nbsp;&nbsp;&nbsp;a. Ayah</td>
                <td class="td-colon">:</td>
                <td>{{ $siswa->pekerjaan_ayah }}</td>
            </tr>
            <tr>
                <td class="td-label">&nbsp;&nbsp;&nbsp;b. Ibu</td>
                <td class="td-colon">:</td>
                <td>{{ $siswa->pekerjaan_ibu }}</td>
            </tr>
            <tr>
                <td class="td-label">7. Alamat Orang Tua</td>
                <td class="td-colon">:</td>
                <td>{{ $siswa->alamat }}</td>
            </tr>
        </table>
        
        @if(($setting->isi_tanda_tangan ?? 'Dengan Tanda Tangan') === 'Dengan Tanda Tangan')
        <div style="margin-top: 80px; width: 100%;">
            <table style="width: 100%;">
                <tr>
                    <td style="width: 60%; vertical-align: top; text-align: center;">
                        @if(isset($siswa->foto) && file_exists(public_path('storage/'.$siswa->foto)))
                            <img src="{{ public_path('storage/'.$siswa->foto) }}" style="width: 3cm; height: 4cm; object-fit: cover;">
                        @else
                            <div style="width: 3cm; height: 4cm; border: 1px solid black; margin: 0 auto; line-height: 4cm; text-align: center;">Pas Foto 3x4</div>
                        @endif
                    </td>
                    <td style="width: 40%; text-align: center;">
                        <p>{{ $sekolah->kabupaten ?? 'Kabupaten' }}, ........................</p>
                        <p>Kepala Sekolah,</p>
                        <br><br><br><br>
                        <p><strong><u>{{ $sekolah->kepala_sekolah ?? '_____________________' }}</u></strong></p>
                        <p>NIP. {{ $sekolah->nip_kepala_sekolah ?? '-' }}</p>
                    </td>
                </tr>
            </table>
        </div>
        @endif

        @if(!$loop->last)
            <div class="page-break"></div>
        @endif
    @endforeach
</body>
</html>
