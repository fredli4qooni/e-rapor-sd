<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Aplikasi e-Rapor | ' . ($semesterAktif ? $semesterAktif->tahun_ajaran . ' ' . $semesterAktif->semester : 'Semester Aktif')) }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            <!-- Top Banner -->
            <div class="bg-[#8b0000] text-white overflow-hidden shadow-sm rounded-lg mb-6 flex items-center p-4">

                <div>
                    <h3 class="font-bold text-lg">Selamat Datang di Halaman Siswa, Aplikasi e-Rapor SD</h3>
                    <p class="text-sm">Anda sedang Login Sebagai Siswa pada {{ $sekolah->nama_sekolah ?? 'Sekolah' }}, Semester {{ $semesterAktif ? $semesterAktif->nama_semester : '-' }}</p>
                </div>
            </div>

            <!-- Status Presensi / Kehadiran Hari Ini (Untuk Siswa & Orang Tua) -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="bg-[#8b0000] text-white px-5 py-3 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-2">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-yellow-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        <h4 class="font-bold text-sm sm:text-base uppercase tracking-wider">Status Kehadiran Hari Ini</h4>
                    </div>
                    <span class="text-xs text-red-100 font-medium bg-red-950/40 px-3 py-1 rounded-full border border-red-800">
                        {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}
                    </span>
                </div>

                <div class="p-5 flex flex-col lg:flex-row items-center justify-between gap-6">
                    <!-- Left: Status Banner -->
                    <div class="flex items-center gap-4 w-full lg:w-auto">
                        @if($presensiHariIni)
                            @if($presensiHariIni->status === 'H')
                                <div class="w-14 h-14 rounded-2xl bg-emerald-100 text-emerald-700 flex items-center justify-center shrink-0 shadow-sm border border-emerald-200">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                </div>
                                <div>
                                    <div class="flex items-center gap-2">
                                        <span class="text-xs font-bold uppercase tracking-wider text-emerald-700 bg-emerald-50 px-2.5 py-0.5 rounded-full border border-emerald-300">Tercatat Masuk</span>
                                        <span class="text-xs text-gray-500 font-medium">{{ $presensiHariIni->updated_at->format('H:i') }} WIB</span>
                                    </div>
                                    <h3 class="text-xl font-extrabold text-emerald-800 mt-0.5">HADIR DI SEKOLAH</h3>
                                    <p class="text-xs text-gray-600">Peserta didik tercatat hadir dan mengikuti kegiatan belajar di sekolah hari ini.</p>
                                </div>
                            @elseif($presensiHariIni->status === 'S')
                                <div class="w-14 h-14 rounded-2xl bg-blue-100 text-blue-700 flex items-center justify-center shrink-0 shadow-sm border border-blue-200">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/></svg>
                                </div>
                                <div>
                                    <span class="text-xs font-bold uppercase tracking-wider text-blue-700 bg-blue-50 px-2.5 py-0.5 rounded-full border border-blue-300">Izin Sakit</span>
                                    <h3 class="text-xl font-extrabold text-blue-800 mt-0.5">SAKIT</h3>
                                    <p class="text-xs text-gray-600">{{ $presensiHariIni->keterangan ?? 'Peserta didik tercatat izin karena sakit hari ini.' }}</p>
                                </div>
                            @elseif($presensiHariIni->status === 'I')
                                <div class="w-14 h-14 rounded-2xl bg-amber-100 text-amber-700 flex items-center justify-center shrink-0 shadow-sm border border-amber-200">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                </div>
                                <div>
                                    <span class="text-xs font-bold uppercase tracking-wider text-amber-700 bg-amber-50 px-2.5 py-0.5 rounded-full border border-amber-300">Izin Keperluan</span>
                                    <h3 class="text-xl font-extrabold text-amber-800 mt-0.5">IZIN</h3>
                                    <p class="text-xs text-gray-600">{{ $presensiHariIni->keterangan ?? 'Peserta didik tercatat memiliki izin keperluan hari ini.' }}</p>
                                </div>
                            @elseif($presensiHariIni->status === 'A')
                                <div class="w-14 h-14 rounded-2xl bg-red-100 text-red-700 flex items-center justify-center shrink-0 shadow-sm border border-red-200">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                </div>
                                <div>
                                    <span class="text-xs font-bold uppercase tracking-wider text-red-700 bg-red-50 px-2.5 py-0.5 rounded-full border border-red-300">Tanpa Keterangan</span>
                                    <h3 class="text-xl font-extrabold text-red-800 mt-0.5">ALPA / TIDAK HADIR</h3>
                                    <p class="text-xs text-gray-600">{{ $presensiHariIni->keterangan ?? 'Peserta didik belum hadir tanpa konfirmasi keterangan ke pihak sekolah.' }}</p>
                                </div>
                            @endif
                        @else
                            <div class="w-14 h-14 rounded-2xl bg-gray-100 text-gray-400 flex items-center justify-center shrink-0 shadow-sm border border-gray-200">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>
                            <div>
                                <span class="text-xs font-bold uppercase tracking-wider text-gray-600 bg-gray-100 px-2.5 py-0.5 rounded-full border border-gray-300">Menunggu Presensi</span>
                                <h3 class="text-xl font-extrabold text-gray-700 mt-0.5">BELUM DIABSEN</h3>
                                <p class="text-xs text-gray-500">Wali kelas belum menginput data presensi harian untuk hari ini.</p>
                            </div>
                        @endif
                    </div>

                    <!-- Right: Semester Summary Stats -->
                    <div class="w-full lg:w-auto bg-gray-50 p-3.5 rounded-xl border border-gray-200 flex items-center justify-around sm:justify-between gap-3 sm:gap-4 shrink-0">
                        <div class="text-center px-2">
                            <span class="text-[10px] font-bold text-gray-500 uppercase tracking-wider block">Hadir</span>
                            <span class="text-lg font-black text-emerald-600">{{ $rekapHarian['hadir'] ?? 0 }}</span>
                        </div>
                        <div class="w-px h-8 bg-gray-200"></div>
                        <div class="text-center px-2">
                            <span class="text-[10px] font-bold text-gray-500 uppercase tracking-wider block">Sakit</span>
                            <span class="text-lg font-black text-blue-600">{{ $rekapHarian['sakit'] ?? 0 }}</span>
                        </div>
                        <div class="w-px h-8 bg-gray-200"></div>
                        <div class="text-center px-2">
                            <span class="text-[10px] font-bold text-gray-500 uppercase tracking-wider block">Izin</span>
                            <span class="text-lg font-black text-amber-600">{{ $rekapHarian['izin'] ?? 0 }}</span>
                        </div>
                        <div class="w-px h-8 bg-gray-200"></div>
                        <div class="text-center px-2">
                            <span class="text-[10px] font-bold text-gray-500 uppercase tracking-wider block">Alpa</span>
                            <span class="text-lg font-black text-red-600">{{ $rekapHarian['alpa'] ?? 0 }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Left Column -->
                <div>
                    <div class="bg-[#8b0000] text-white font-bold px-4 py-2 rounded-t-md text-sm">
                        DATA ROMBEL DAN KELOMPOK KOKURIKULER ANDA
                    </div>
                    <div class="bg-white p-4 rounded-b-md shadow-sm border border-gray-200 mb-6 flex flex-col gap-3">
                        
                        <!-- Rombel Card -->
                        <div class="bg-orange-500 text-white rounded-md p-4 flex justify-between items-center shadow-md">
                            <div>
                                <h4 class="font-bold text-xl">{{ $rombel->nama_rombel ?? 'Belum Masuk Kelas' }}</h4>
                                <p class="text-sm">{{ $kurikulum === 'K13' ? 'Kurikulum 2013' : 'Kurikulum Merdeka' }}</p>
                            </div>
                            <div>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 opacity-70" viewBox="0 0 20 20" fill="currentColor">
                                  <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z" />
                                </svg>
                            </div>
                        </div>

                        <!-- Ekskul Cards -->
                        @foreach($ekskuls as $ekskul)
                        <div class="bg-teal-700 text-white rounded-md p-4 flex justify-between items-center shadow-md">
                            <div>
                                <h4 class="font-bold text-xl">{{ $ekskul->ekstrakurikuler->nama_ekskul ?? 'Ekskul' }}</h4>
                                <p class="text-sm">Ekstrakurikuler</p>
                            </div>
                            <div>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 opacity-70" viewBox="0 0 20 20" fill="currentColor">
                                  <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </div>
                        @endforeach

                        <!-- P5 Kelompok Cards -->
                        @foreach($kelompokP5s as $index => $p5)
                        <div class="{{ $index % 2 == 0 ? 'bg-red-800' : 'bg-green-500' }} text-white rounded-md p-4 flex justify-between items-center shadow-md">
                            <div>
                                <h4 class="font-bold text-xl">{{ $p5->nama_kelompok }}</h4>
                                <p class="text-sm">{{ $p5->tingkat_pendidikan ?? 'Proyek P5' }} Fase {{ $p5->fase }}</p>
                            </div>
                            <div>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 opacity-70" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0-3.332.477-4.5 1.253" />
                                </svg>
                            </div>
                        </div>
                        @endforeach
                        
                        @if(count($ekskuls) == 0 && count($kelompokP5s) == 0)
                            <div class="text-gray-500 italic text-sm text-center py-2">Belum ada ekstrakurikuler atau kokurikuler yang diikuti.</div>
                        @endif
                    </div>
                </div>

                <!-- Right Column -->
                <div>
                    <div class="bg-[#8b0000] text-white font-bold px-4 py-2 rounded-t-md text-sm">
                        PANDUAN APLIKASI
                    </div>
                    <div class="bg-white p-4 rounded-b-md shadow-sm border border-gray-200 mb-6">
                        <a href="{{ route('panduan.index') }}" class="bg-blue-600 hover:bg-blue-700 text-white rounded-md p-4 flex justify-between items-center shadow-md transition-colors w-full">
                            <div>
                                <p class="text-sm font-semibold">Panduan Penggunaan Aplikasi e-Rapor SD</p>
                                <p class="text-xs text-blue-100 mt-0.5">Petunjuk lengkap akses nilai & presensi</p>
                            </div>
                            <div>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 opacity-80" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </div>
                        </a>
                    </div>

                    <div class="bg-[#8b0000] text-white font-bold px-4 py-2 rounded-t-md text-sm">
                        DATA FILE NILAI YANG TERSEDIA
                    </div>
                    <div class="bg-white p-4 rounded-b-md shadow-sm border border-gray-200 flex flex-col gap-3">
                        
                        @if($siswa->is_pelengkap_published)
                        <a href="{{ route('siswa.cetak_pelengkap') }}" target="_blank" class="bg-orange-500 hover:bg-orange-600 text-white rounded-md p-4 flex justify-between items-center shadow-md transition-colors w-full">
                            <div>
                                <h4 class="font-bold text-xl">Pelengkap Rapor</h4>
                                <p class="text-sm">File Pelengkap {{ $semesterAktif ? $semesterAktif->tahun_ajaran . ' ' . $semesterAktif->semester : '' }}</p>
                            </div>
                            <div>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 opacity-80" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </div>
                        </a>
                        @endif

                        @if($siswa->is_rapor_published)
                        <a href="{{ route('siswa.cetak') }}" target="_blank" class="bg-red-500 hover:bg-red-600 text-white rounded-md p-4 flex justify-between items-center shadow-md transition-colors w-full">
                            <div>
                                <h4 class="font-bold text-xl">File Nilai Rapor</h4>
                                <p class="text-sm">File Rapor {{ $semesterAktif ? $semesterAktif->tahun_ajaran . ' ' . $semesterAktif->semester : '' }}</p>
                            </div>
                            <div>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 opacity-80" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </div>
                        </a>
                        @endif

                        @if($siswa->is_p5_published)
                        <a href="{{ route('siswa.cetak_p5') }}" target="_blank" class="bg-green-500 hover:bg-green-600 text-white rounded-md p-4 flex justify-between items-center shadow-md transition-colors w-full">
                            <div>
                                <h4 class="font-bold text-xl">File Rapor P5</h4>
                                <p class="text-sm">File P5 {{ $semesterAktif ? $semesterAktif->tahun_ajaran . ' ' . $semesterAktif->semester : '' }}</p>
                            </div>
                            <div>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 opacity-80" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </div>
                        </a>
                        @endif

                        @if(!$siswa->is_pelengkap_published && !$siswa->is_rapor_published && !$siswa->is_p5_published)
                            <div class="text-gray-500 italic text-sm text-center py-4">Belum ada file rapor yang dipublikasikan oleh sekolah.</div>
                        @endif

                    </div>
                </div>
            </div>

            </div>

            <!-- Chart Analitik Nilai -->
            <div class="mt-6 bg-white rounded-md shadow-sm border border-gray-200">
                <div class="bg-[#8b0000] text-white px-4 py-2 rounded-t-md font-bold text-sm uppercase tracking-wider">
                    Grafik Perkembangan Nilai Anda
                </div>
                <div class="p-6">
                    <canvas id="nilaiChart" style="max-height: 350px;"></canvas>
                </div>
            </div>

        </div>
    </div>

    <!-- Chart.js Script -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var ctxNilai = document.getElementById('nilaiChart').getContext('2d');
            var labelsNilai = {!! $chart_nilai_labels ?? '[]' !!};
            var dataNilai = {!! $chart_nilai_data ?? '[]' !!};

            new Chart(ctxNilai, {
                type: 'bar',
                data: {
                    labels: labelsNilai,
                    datasets: [{
                        label: 'Nilai Akhir',
                        data: dataNilai,
                        backgroundColor: 'rgba(54, 162, 235, 0.7)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1,
                        borderRadius: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            max: 100,
                            ticks: { stepSize: 10 }
                        }
                    },
                    plugins: { legend: { display: false } }
                }
            });
        });
    </script>
</x-app-layout>
