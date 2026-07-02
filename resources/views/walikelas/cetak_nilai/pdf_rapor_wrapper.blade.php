<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Cetak Nilai Rapor</title>
    <style>
        @page {
            margin-top: {{ $setting->margin_atas ?? 20 }}mm;
            margin-bottom: {{ $setting->margin_bawah ?? 10 }}mm;
            margin-left: {{ $setting->margin_kiri ?? 20 }}mm;
            margin-right: {{ $setting->margin_kanan ?? 20 }}mm;
        }
        body { font-family: Arial, sans-serif; font-size: 12px; }
        .text-center { text-align: center; }
        .title { font-size: 16px; font-weight: bold; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid black; padding: 5px; }
        th { background-color: #f2f2f2; }
        .page-break { page-break-after: always; }
        .ttd-box { width: 100%; margin-top: 30px; }
        .ttd-box table { border: none; }
        .ttd-box th, .ttd-box td { border: none; padding: 0; background: transparent; }
    </style>
</head>
<body>
    @foreach($siswas as $index => $siswa)
        @include($viewName, [
            'siswa' => $siswa, 
            'kurikulum' => $kurikulum,
            'nilaiRapors' => $siswa->nilaiRapors,
            'kehadiran' => $siswa->kehadiran,
            'catatan' => $siswa->catatan,
            'kenaikan' => $siswa->kenaikan,
            'sikap' => $siswa->sikap,
            'ekskuls' => $siswa->ekskuls,
            'sekolah' => $sekolah
        ])
        
        @if(($setting->isi_tanda_tangan ?? 'Dengan Tanda Tangan') === 'Dengan Tanda Tangan')
        <div class="ttd-box">
            @if(($setting->posisi_ttd_ks ?? 'Sejajar Wali Kelas') === 'Sejajar Wali Kelas')
            <table style="width: 100%; border: none;">
                <tr>
                    <td style="width: 50%; text-align: center; vertical-align: top; border: none;">
                        <p>Mengetahui,</p>
                        <p>Kepala Sekolah</p>
                        <br><br><br><br>
                        <p><strong><u>{{ $sekolah->kepala_sekolah ?? '_____________________' }}</u></strong></p>
                        <p>NIP. {{ $sekolah->nip_kepala_sekolah ?? '-' }}</p>
                    </td>
                    <td style="width: 50%; text-align: center; vertical-align: top; border: none;">
                        <p>{{ $sekolah->kabupaten ?? 'Kabupaten' }}, ........................</p>
                        <p>Wali Kelas</p>
                        <br><br><br><br>
                        @if(($setting->tampil_nama_wali ?? 'Isi Nama Wali Kelas') === 'Isi Nama Wali Kelas')
                            <p><strong><u>{{ auth()->user()->guru->nama_lengkap ?? '_____________________' }}</u></strong></p>
                            <p>NIP. {{ auth()->user()->guru->nip ?? '-' }}</p>
                        @else
                            <p><strong><u>_____________________</u></strong></p>
                            <p>NIP. </p>
                        @endif
                    </td>
                </tr>
            </table>
            @else
            <!-- Bawah Wali Kelas -->
            <table style="width: 100%; border: none;">
                <tr>
                    <td style="width: 50%; border: none;"></td>
                    <td style="width: 50%; text-align: center; vertical-align: top; border: none;">
                        <p>{{ $sekolah->kabupaten ?? 'Kabupaten' }}, ........................</p>
                        <p>Wali Kelas</p>
                        <br><br><br><br>
                        @if(($setting->tampil_nama_wali ?? 'Isi Nama Wali Kelas') === 'Isi Nama Wali Kelas')
                            <p><strong><u>{{ auth()->user()->guru->nama_lengkap ?? '_____________________' }}</u></strong></p>
                            <p>NIP. {{ auth()->user()->guru->nip ?? '-' }}</p>
                        @else
                            <p><strong><u>_____________________</u></strong></p>
                            <p>NIP. </p>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td colspan="2" style="text-align: center; border: none; padding-top: 30px;">
                        <p>Mengetahui,</p>
                        <p>Kepala Sekolah</p>
                        <br><br><br><br>
                        <p><strong><u>{{ $sekolah->kepala_sekolah ?? '_____________________' }}</u></strong></p>
                        <p>NIP. {{ $sekolah->nip_kepala_sekolah ?? '-' }}</p>
                    </td>
                </tr>
            </table>
            @endif
        </div>
        @endif

        @if(!$loop->last)
            <div class="page-break"></div>
        @endif
    @endforeach
</body>
</html>
