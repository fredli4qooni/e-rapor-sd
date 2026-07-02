<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Cetak Rapor Projek Profil Pelajar Pancasila</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 10pt; color: #000; }
        @page {
            margin-top: {{ $setting->margin_atas ?? 15 }}mm;
            margin-bottom: {{ $setting->margin_bawah ?? 15 }}mm;
            margin-left: {{ $setting->margin_kiri ?? 15 }}mm;
            margin-right: {{ $setting->margin_kanan ?? 15 }}mm;
        }
        .page-break { page-break-after: always; }
        .text-center { text-align: center; }
        .text-bold { font-weight: bold; }
        .uppercase { text-transform: uppercase; }
        
        table { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
        .table-border th, .table-border td { border: 1px solid #000; padding: 5px; vertical-align: top; }
        .table-border th { background-color: #f2f2f2; text-align: center; }
        
        .header-table td { padding: 3px; vertical-align: top; border: none; }
        .section-title { font-size: 11pt; font-weight: bold; margin-bottom: 5px; margin-top: 15px; }
        
        .signature-table { width: 100%; margin-top: 30px; border: none; }
        .signature-table td { border: none; text-align: center; vertical-align: top; }

        .rubrik-box { display: inline-block; width: 15px; height: 15px; border: 1px solid #000; margin: 0 auto; }
        .rubrik-checked { background-color: #000; }
        
        .title-kop { font-size: 16pt; font-weight: bold; text-align: center; margin-bottom: 5px; }
    </style>
</head>
<body>
    @php
        $kelompok = \App\Models\P5Kelompok::where('rombel_id', $rombel->id)->first();
        $proyeks = $kelompok ? $kelompok->proyeks()->with(['tema', 'subElemens.elemen.dimensi'])->get() : collect();
        $semesterAktif = \App\Models\Semester::where('is_aktif', true)->first();
    @endphp

    @foreach($siswas as $index => $siswa)
        @php
            $nilais = \App\Models\P5Nilai::where('siswa_id', $siswa->id)->get()->keyBy('sub_elemen_id');
            $catatanP5 = \App\Models\P5Catatan::where('siswa_id', $siswa->id)->where('rombel_id', $rombel->id)->first();
        @endphp

        <!-- KOP SEKOLAH -->
        <table style="width: 100%; border-bottom: 3px solid black; margin-bottom: 20px; border-left: none; border-right: none; border-top: none; padding-bottom: 10px;">
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
                    <h3 style="margin: 0; font-size: 14pt;">PEMERINTAH KABUPATEN/KOTA</h3>
                    <h2 style="margin: 0; font-size: 16pt;">DINAS PENDIDIKAN DAN KEBUDAYAAN</h2>
                    <h1 style="margin: 0; font-size: 18pt; text-transform:uppercase;">{{ $sekolah->nama_sekolah ?? 'SD NEGERI 1 PERCONTOHAN' }}</h1>
                    <p style="margin: 5px 0 0 0; font-size: 10pt;">{{ $sekolah->alamat ?? 'Jl. Pendidikan No. 123' }}</p>
                    <p style="margin: 0; font-size: 10pt;">Email: {{ $sekolah->email ?? '-' }} | Website: {{ $sekolah->website ?? '-' }}</p>
                </td>
            </tr>
        </table>

        <!-- Judul -->
        <div class="title-kop uppercase" style="margin-bottom: 20px;">
            RAPOR PROJEK PENGUATAN PROFIL PELAJAR PANCASILA
        </div>

        <!-- Identitas Siswa -->
        <table class="header-table">
            <tr>
                <td width="20%">Nama Peserta Didik</td>
                <td width="2%">:</td>
                <td width="38%"><strong>{{ $siswa->nama_lengkap }}</strong></td>
                <td width="15%">Kelas</td>
                <td width="2%">:</td>
                <td width="23%">{{ $rombel->nama_rombel ?? '-' }}</td>
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

        <hr style="border-top: 1px solid #000; margin-bottom: 15px;">

        <!-- Deskripsi Proyek -->
        @if($proyeks->count() > 0)
            @foreach($proyeks as $index => $proyek)
                <div style="margin-bottom: 15px;">
                    <div class="text-bold" style="margin-bottom: 3px;">
                        Projek {{ $index + 1 }} | {{ $proyek->tema->nama_tema ?? 'Tema' }}
                    </div>
                    <div class="text-bold" style="margin-bottom: 3px;">
                        {{ $proyek->nama_proyek }}
                    </div>
                    <div style="text-align: justify; margin-bottom: 10px;">
                        {{ $proyek->deskripsi }}
                    </div>
                </div>
            @endforeach
        @else
            <div style="margin-bottom: 20px; text-align: center; font-style: italic;">
                Data Proyek P5 belum tersedia atau belum ditambahkan pada kelompok ini.
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
                @if($proyeks->count() > 0)
                    @foreach($proyeks as $index => $proyek)
                        <tr>
                            <td colspan="5" class="text-bold" style="background-color: #e2e8f0;">Projek {{ $index + 1 }}: {{ $proyek->nama_proyek }}</td>
                        </tr>
                        @php
                            // Group by elemen
                            $subElemensByElemen = $proyek->subElemens->groupBy('elemen_id');
                        @endphp
                        @foreach($subElemensByElemen as $elemenId => $subElemens)
                            @php $elemen = $subElemens->first()->elemen; @endphp
                            <tr>
                                <td colspan="5" class="text-bold" style="background-color: #f8fafc; padding-left: 10px;">Elemen: {{ $elemen->nama_elemen ?? '-' }}</td>
                            </tr>
                            @foreach($subElemens as $subElemen)
                                @php 
                                    $nilaiSiswa = $nilais->get($subElemen->id);
                                    $predikat = $nilaiSiswa ? $nilaiSiswa->nilai : '';
                                @endphp
                                <tr>
                                    <td style="padding-left: 20px;">Subelemen: {{ $subElemen->nama_sub_elemen }}</td>
                                    <td class="text-center"><div class="rubrik-box {{ $predikat == 'MB' ? 'rubrik-checked' : '' }}"></div></td>
                                    <td class="text-center"><div class="rubrik-box {{ $predikat == 'SB' ? 'rubrik-checked' : '' }}"></div></td>
                                    <td class="text-center"><div class="rubrik-box {{ $predikat == 'BSH' ? 'rubrik-checked' : '' }}"></div></td>
                                    <td class="text-center"><div class="rubrik-box {{ $predikat == 'SAB' ? 'rubrik-checked' : '' }}"></div></td>
                                </tr>
                            @endforeach
                        @endforeach
                    @endforeach
                @else
                    <tr>
                        <td colspan="5" class="text-center">Data rubrik penilaian P5 belum tersedia.</td>
                    </tr>
                @endif
            </tbody>
        </table>

        <!-- Keterangan Rubrik -->
        <table style="width: 100%; border: none; margin-top: 10px; font-size: 8pt;">
            <tr>
                <td width="25%" style="vertical-align: top; border: none; padding-right: 5px;">
                    <strong>Mulai Berkembang (MB)</strong><br>
                    Peserta didik masih membutuhkan bimbingan dalam mengembangkan kompetensi.
                </td>
                <td width="25%" style="vertical-align: top; border: none; padding-right: 5px;">
                    <strong>Sedang Berkembang (SB)</strong><br>
                    Peserta didik mulai mengembangkan kompetensi namun masih membutuhkan sedikit bimbingan.
                </td>
                <td width="25%" style="vertical-align: top; border: none; padding-right: 5px;">
                    <strong>Berkembang Sesuai Harapan (BSH)</strong><br>
                    Peserta didik telah mengembangkan kompetensi hingga tahap sesuai harapan.
                </td>
                <td width="25%" style="vertical-align: top; border: none;">
                    <strong>Sangat Berkembang (SAB)</strong><br>
                    Peserta didik mengembangkan kompetensi melampaui harapan.
                </td>
            </tr>
        </table>

        <div class="section-title" style="margin-top: 15px;">Catatan Proses Projek</div>
        <div style="border: 1px solid #000; padding: 10px; min-height: 80px;">
            {!! nl2br(e($catatanP5->catatan ?? 'Catatan wali kelas mengenai perkembangan siswa selama mengikuti proyek belum diisi.')) !!}
        </div>

        <!-- Tanda Tangan -->
        @if(($setting->isi_tanda_tangan ?? 'Dengan Tanda Tangan') === 'Dengan Tanda Tangan')
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
                            <strong><u>{{ $sekolah->kepala_sekolah ?? '...........................' }}</u></strong><br>
                            NIP. {{ $sekolah->nip_kepala_sekolah ?? '-' }}
                        @endif
                    </td>
                    <td width="33%">
                        {{ $sekolah->kabupaten ?? '..............' }}, {{ \Carbon\Carbon::parse($semesterAktif->tanggal_rapor ?? date('Y-m-d'))->translatedFormat('d F Y') }}<br>
                        Wali Kelas<br>
                        <br><br><br><br>
                        @if($setting->tampilkan_nama_wali ?? true)
                            <strong><u>{{ $rombel->guru->nama_lengkap ?? '...................................' }}</u></strong><br>
                            NIP. {{ $rombel->guru->nip ?? '-' }}
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
                            <strong><u>{{ $sekolah->kepala_sekolah ?? '...........................' }}</u></strong><br>
                            NIP. {{ $sekolah->nip_kepala_sekolah ?? '-' }}
                        </td>
                    </tr>
                </table>
            @endif
        @endif

        @if(!$loop->last)
            <div class="page-break"></div>
        @endif
    @endforeach
</body>
</html>
