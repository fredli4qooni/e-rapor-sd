<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            Dashboard Kepala Sekolah | {{ $semester_teks ?? '2025/2026 Ganjil' }}
        </h2>
    </x-slot>

    <div class="py-6 space-y-6 max-w-7xl mx-auto sm:px-6 lg:px-8">
        
        <!-- Welcome Banner -->
        <div class="grid grid-cols-1 gap-6">
            <div class="bg-gradient-to-r from-[#8B1515] to-[#a31c1c] rounded-md text-white p-5 flex items-start gap-4 shadow-md">
                <div class="mt-1 bg-white/20 p-2 rounded-full">
                    <svg class="w-6 h-6 text-yellow-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                </div>
                <div class="flex-1">
                    <h3 class="font-bold text-lg border-b border-red-500/50 pb-1 mb-1">Selamat Datang di Halaman Kepala Sekolah, e-Rapor SD</h3>
                    <p class="text-sm text-red-100">Anda sedang mengakses dashboard pemantauan akademik dan kinerja guru pada <strong>{{ $sekolah->nama_sekolah ?? 'SD Contoh Rapor Dapo' }}</strong> untuk Semester <strong>{{ $semester_teks ?? '2025/2026 Ganjil' }}</strong>.</p>
                    @if($semester_aktif)
                        <div class="mt-2 flex flex-wrap items-center gap-3 text-xs">
                            @if($semester_aktif->periode_semester_teks !== '-')
                                <span class="bg-black/25 px-2.5 py-1 rounded border border-white/20">
                                    📅 Periode: <strong>{{ $semester_aktif->periode_semester_teks }}</strong>
                                </span>
                            @endif
                            @if($semester_aktif->deadline_input_nilai)
                                <span class="bg-black/25 px-2.5 py-1 rounded border border-white/20">
                                    ⏰ Batas Input Nilai Guru: <strong>{{ $semester_aktif->deadline_input_nilai->translatedFormat('d M Y') }}</strong>
                                    @if($semester_aktif->sisa_hari_input !== null)
                                        @if($semester_aktif->sisa_hari_input < 0)
                                            <span class="text-red-300 font-bold">(Lewat {{ abs($semester_aktif->sisa_hari_input) }} hari)</span>
                                        @elseif($semester_aktif->sisa_hari_input === 0)
                                            <span class="text-yellow-300 font-bold">(Hari ini terakhir!)</span>
                                        @else
                                            <span class="text-yellow-300 font-bold">(Tersisa {{ $semester_aktif->sisa_hari_input }} hari)</span>
                                        @endif
                                    @endif
                                </span>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Rekap Data Sekolah (Counter Cards) -->
        <div class="bg-white rounded-md shadow-md overflow-hidden">
            <div class="bg-[#8B1515] text-white px-4 py-2 font-bold text-sm uppercase tracking-wider flex justify-between items-center">
                <span>Rekap Data Sekolah</span>
                <span class="text-xs font-normal text-red-200">Semester Aktif: {{ $semester_teks }}</span>
            </div>
            <div class="p-4 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
                
                <!-- Guru & KS -->
                <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-md p-4 text-white flex justify-between items-center shadow-sm">
                    <div>
                        <div class="font-semibold text-xs uppercase tracking-wider text-green-100">Total Guru</div>
                        <div class="text-3xl font-extrabold mt-1">{{ $counts['guru'] ?? 0 }}</div>
                        <div class="text-xs text-green-200 mt-1">Tenaga Pengajar</div>
                    </div>
                    <div class="bg-white/20 p-3 rounded-full">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    </div>
                </div>

                <!-- Siswa -->
                <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-md p-4 text-white flex justify-between items-center shadow-sm">
                    <div>
                        <div class="font-semibold text-xs uppercase tracking-wider text-blue-100">Total Siswa</div>
                        <div class="text-3xl font-extrabold mt-1">{{ $counts['siswa'] ?? 0 }}</div>
                        <div class="text-xs text-blue-200 mt-1">Peserta Didik Aktif</div>
                    </div>
                    <div class="bg-white/20 p-3 rounded-full">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"></path></svg>
                    </div>
                </div>

                <!-- Rombel -->
                <div class="bg-gradient-to-r from-orange-500 to-orange-600 rounded-md p-4 text-white flex justify-between items-center shadow-sm">
                    <div>
                        <div class="font-semibold text-xs uppercase tracking-wider text-orange-100">Rombel Aktif</div>
                        <div class="text-3xl font-extrabold mt-1">{{ $counts['rombel'] ?? 0 }}</div>
                        <div class="text-xs text-orange-200 mt-1">Rombongan Belajar</div>
                    </div>
                    <div class="bg-white/20 p-3 rounded-full">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                    </div>
                </div>

                <!-- Pembelajaran -->
                <div class="bg-gradient-to-r from-purple-500 to-purple-600 rounded-md p-4 text-white flex justify-between items-center shadow-sm">
                    <div>
                        <div class="font-semibold text-xs uppercase tracking-wider text-purple-100">Pembelajaran</div>
                        <div class="text-3xl font-extrabold mt-1">{{ $counts['pembelajaran'] ?? 0 }}</div>
                        <div class="text-xs text-purple-200 mt-1">Mapel Terjadwal</div>
                    </div>
                    <div class="bg-white/20 p-3 rounded-full">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                    </div>
                </div>

            </div>
        </div>

        <!-- Section: Monitoring Progres Kesiapan Rapor Sekolah (Teacher Performance Summary Widget) -->
        <div class="bg-white rounded-md shadow-md overflow-hidden border border-gray-100">
            <div class="bg-[#8B1515] text-white px-4 py-3 font-bold text-sm uppercase tracking-wider flex flex-wrap justify-between items-center gap-2">
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-yellow-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span>Pemantauan Progres Pengisian Rapor Guru</span>
                </div>
                <a href="{{ route('kepsek.monitoring.kinerja_guru') }}" class="text-xs bg-white text-[#8B1515] hover:bg-gray-100 font-bold px-3 py-1 rounded transition-colors flex items-center gap-1 shadow-sm">
                    Detail Kinerja Guru Lengkap &rarr;
                </a>
            </div>
            
            <div class="p-5">
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-center">
                    <!-- Progress Bar & Big Percentage -->
                    <div class="lg:col-span-4 border-b lg:border-b-0 lg:border-r border-gray-200 pb-4 lg:pb-0 lg:pr-6">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-xs font-bold text-gray-600 uppercase">Kesiapan Rapor Sekolah</span>
                            <span class="text-lg font-black text-[#8B1515]">{{ $rekapKinerjaSekolah['persen_global'] ?? 0 }}%</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-3.5 overflow-hidden shadow-inner">
                            <div class="h-3.5 rounded-full bg-gradient-to-r from-red-500 to-green-500 transition-all duration-500" 
                                 style="width: {{ $rekapKinerjaSekolah['persen_global'] ?? 0 }}%"></div>
                        </div>
                        <p class="text-xs text-gray-500 mt-2">
                            <strong>{{ $rekapKinerjaSekolah['tuntas'] ?? 0 }}</strong> dari <strong>{{ $rekapKinerjaSekolah['total_pembelajaran'] ?? 0 }}</strong> pembelajaran telah selesai diisi nilai & deskripsinya 100%.
                        </p>
                    </div>

                    <!-- Mini Stat Counters -->
                    <div class="lg:col-span-8 grid grid-cols-1 sm:grid-cols-3 gap-3">
                        <div class="bg-green-50 border border-green-200 rounded-md p-3 flex items-center gap-3">
                            <div class="p-2 bg-green-500 text-white rounded-md">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            </div>
                            <div>
                                <span class="text-xs font-semibold text-green-800 uppercase block">Penilaian Tuntas</span>
                                <span class="text-xl font-black text-green-700">{{ $rekapKinerjaSekolah['tuntas'] ?? 0 }} Mapel</span>
                            </div>
                        </div>

                        <div class="bg-yellow-50 border border-yellow-200 rounded-md p-3 flex items-center gap-3">
                            <div class="p-2 bg-yellow-500 text-white rounded-md">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                            <div>
                                <span class="text-xs font-semibold text-yellow-800 uppercase block">Sedang Mengisi</span>
                                <span class="text-xl font-black text-yellow-700">{{ $rekapKinerjaSekolah['sebagian'] ?? 0 }} Mapel</span>
                            </div>
                        </div>

                        <div class="bg-red-50 border border-red-200 rounded-md p-3 flex items-center gap-3">
                            <div class="p-2 bg-red-500 text-white rounded-md">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </div>
                            <div>
                                <span class="text-xs font-semibold text-red-800 uppercase block">Belum Mengisi</span>
                                <span class="text-xl font-black text-red-700">{{ $rekapKinerjaSekolah['belum'] ?? 0 }} Mapel</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter Bar: Per Kelas & Per Mata Pelajaran -->
        <div class="bg-white rounded-md shadow-md p-4 border border-gray-200">
            <form method="GET" action="{{ route('kepsek.dashboard') }}" class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-4 items-end">
                <!-- Filter Kelas -->
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">
                        Filter Kelas / Rombel:
                    </label>
                    <select name="rombel_id" class="w-full text-sm border-gray-300 rounded-md shadow-sm focus:border-red-500 focus:ring-red-500">
                        <option value="">-- Semua Kelas / Rombel --</option>
                        @foreach($filterRombels as $r)
                            <option value="{{ $r->id }}" {{ $selected_rombel_id == $r->id ? 'selected' : '' }}>
                                {{ $r->nama_rombel }} (Tingkat {{ $r->tingkat }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Filter Mapel -->
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">
                        Filter Mata Pelajaran:
                    </label>
                    <select name="mata_pelajaran_id" class="w-full text-sm border-gray-300 rounded-md shadow-sm focus:border-red-500 focus:ring-red-500">
                        <option value="">-- Semua Mata Pelajaran --</option>
                        @foreach($filterMapels as $m)
                            <option value="{{ $m->id }}" {{ $selected_mapel_id == $m->id ? 'selected' : '' }}>
                                {{ $m->nama_mapel }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Buttons -->
                <div class="flex gap-2">
                    <button type="submit" class="flex-1 bg-[#8B1515] hover:bg-red-800 text-white font-semibold py-2 px-4 rounded-md text-sm transition-colors shadow-sm flex items-center justify-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                        Filter Nilai
                    </button>
                    @if($selected_rombel_id || $selected_mapel_id)
                        <a href="{{ route('kepsek.dashboard') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold py-2 px-3 rounded-md text-sm transition-colors flex items-center justify-center">
                            Reset
                        </a>
                    @endif
                </div>

                <!-- Active Filter Status Indicator -->
                <div class="text-xs text-gray-500 self-center">
                    @if($selected_rombel_id && $selected_mapel_id)
                        <span class="inline-block bg-blue-100 text-blue-800 font-medium px-2 py-1 rounded">Filter: Kelas & Mapel Spesifik</span>
                    @elseif($selected_rombel_id)
                        <span class="inline-block bg-yellow-100 text-yellow-800 font-medium px-2 py-1 rounded">Filter: Kelas Tertentu</span>
                    @elseif($selected_mapel_id)
                        <span class="inline-block bg-purple-100 text-purple-800 font-medium px-2 py-1 rounded">Filter: Mapel Tertentu</span>
                    @else
                        <span class="inline-block bg-gray-100 text-gray-600 font-medium px-2 py-1 rounded">Tampilan: Seluruh Sekolah</span>
                    @endif
                </div>
            </form>
        </div>

        <!-- Stat Cards Analitik Nilai Sesuai Filter -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Rata-rata Nilai -->
            <div class="bg-white p-4 rounded-md shadow-md border-t-4 border-blue-500">
                <div class="flex justify-between items-center">
                    <div>
                        <span class="text-xs font-bold text-gray-500 uppercase">Rata-rata Nilai</span>
                        <div class="text-2xl font-black text-blue-600 mt-1">{{ $stat_nilai['rata_rata'] }}</div>
                        <span class="text-xs text-gray-400">Skala 0 - 100</span>
                    </div>
                    <div class="p-3 bg-blue-50 rounded-full text-blue-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                    </div>
                </div>
            </div>

            <!-- Nilai Tertinggi -->
            <div class="bg-white p-4 rounded-md shadow-md border-t-4 border-green-500">
                <div class="flex justify-between items-center">
                    <div>
                        <span class="text-xs font-bold text-gray-500 uppercase">Nilai Tertinggi</span>
                        <div class="text-2xl font-black text-green-600 mt-1">{{ $stat_nilai['tertinggi'] }}</div>
                        <span class="text-xs text-gray-400">Pencapaian Maksimal</span>
                    </div>
                    <div class="p-3 bg-green-50 rounded-full text-green-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                    </div>
                </div>
            </div>

            <!-- Nilai Terendah -->
            <div class="bg-white p-4 rounded-md shadow-md border-t-4 border-yellow-500">
                <div class="flex justify-between items-center">
                    <div>
                        <span class="text-xs font-bold text-gray-500 uppercase">Nilai Terendah</span>
                        <div class="text-2xl font-black text-yellow-600 mt-1">{{ $stat_nilai['terendah'] }}</div>
                        <span class="text-xs text-gray-400">Pencapaian Minimal</span>
                    </div>
                    <div class="p-3 bg-yellow-50 rounded-full text-yellow-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"></path></svg>
                    </div>
                </div>
            </div>

            <!-- Total Data Dinilai -->
            <div class="bg-white p-4 rounded-md shadow-md border-t-4 border-[#8B1515]">
                <div class="flex justify-between items-center">
                    <div>
                        <span class="text-xs font-bold text-gray-500 uppercase">Total Data Dinilai</span>
                        <div class="text-2xl font-black text-gray-800 mt-1">{{ $stat_nilai['total_dinilai'] }}</div>
                        <span class="text-xs text-gray-400">Nilai Siswa Masuk</span>
                    </div>
                    <div class="p-3 bg-red-50 rounded-full text-[#8B1515]">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Chart 1: Persebaran Siswa -->
            <div class="bg-white rounded-md shadow-md overflow-hidden">
                <div class="bg-[#8B1515] text-white px-4 py-2 font-bold text-sm uppercase tracking-wider">
                    Grafik Persebaran Siswa Per Rombel
                </div>
                <div class="p-5">
                    <canvas id="siswaChart" style="max-height: 320px;"></canvas>
                </div>
            </div>

            <!-- Chart 2: Analitik Nilai Adaptif -->
            <div class="bg-white rounded-md shadow-md overflow-hidden">
                <div class="bg-[#8B1515] text-white px-4 py-2 font-bold text-sm uppercase tracking-wider flex justify-between items-center">
                    <span class="truncate">{{ $chart_nilai_title }}</span>
                </div>
                <div class="p-5">
                    <canvas id="nilaiChart" style="max-height: 320px;"></canvas>
                </div>
            </div>
        </div>

        <!-- Detail Table Siswa (Hanya Tampil Jika Filter Kelas & Mapel Spesifik Aktif) -->
        @if($chart_nilai_type === 'siswa' && $rincian_siswa->isNotEmpty())
            <div class="bg-white rounded-md shadow-md overflow-hidden border border-gray-200">
                <div class="bg-[#8B1515] text-white px-6 py-4 font-bold tracking-wider uppercase text-sm border-b border-red-800 flex justify-between items-center">
                    <span>Rincian Nilai & Capaian Kompetensi Siswa</span>
                    <span class="text-xs bg-red-900/80 px-3 py-1 rounded-full text-red-100 font-normal">Total: {{ $rincian_siswa->count() }} Siswa</span>
                </div>
                <div class="p-6 overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-700 border">
                        <thead class="text-xs text-white bg-gray-800 uppercase border-b">
                            <tr>
                                <th class="px-3 py-3 w-12 text-center">No</th>
                                <th class="px-4 py-3">Nama Lengkap Siswa</th>
                                <th class="px-4 py-3 text-center">NISN / NIS</th>
                                <th class="px-4 py-3 text-center w-28">Nilai Akhir</th>
                                <th class="px-4 py-3">Capaian Kompetensi (Deskripsi)</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($rincian_siswa as $item)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-3 py-3 text-center text-gray-500 font-medium">{{ $loop->iteration }}</td>
                                    <td class="px-4 py-3 font-semibold text-gray-900">
                                        {{ $item['siswa']->nama_lengkap ?? '-' }}
                                    </td>
                                    <td class="px-4 py-3 text-center text-gray-600 font-mono text-xs">
                                        {{ $item['siswa']->nisn ?? '-' }} / {{ $item['siswa']->nis ?? '-' }}
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        @if($item['nilai_akhir'] !== null)
                                            <span class="inline-block px-3 py-1 rounded-full font-bold text-sm {{ $item['nilai_akhir'] >= 75 ? 'bg-green-100 text-green-800 border border-green-300' : 'bg-yellow-100 text-yellow-800 border border-yellow-300' }}">
                                                {{ $item['nilai_akhir'] }}
                                            </span>
                                        @else
                                            <span class="text-xs text-gray-400 italic">Belum Dinilai</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-xs text-gray-700">
                                        @if($item['nilai'] && $item['nilai']->deskripsi)
                                            <div class="space-y-1">
                                                @if($item['nilai']->deskripsi->capaian_tertinggi)
                                                    <div><strong class="text-green-700">Capaian Tertinggi:</strong> {{ $item['nilai']->deskripsi->capaian_tertinggi }}</div>
                                                @endif
                                                @if($item['nilai']->deskripsi->capaian_terendah)
                                                    <div><strong class="text-yellow-700">Perlu Peningkatan:</strong> {{ $item['nilai']->deskripsi->capaian_terendah }}</div>
                                                @endif
                                            </div>
                                        @elseif($item['nilai'] && $item['nilai']->capaian_kompetensi)
                                            {{ $item['nilai']->capaian_kompetensi }}
                                        @else
                                            <span class="text-gray-400 italic">Deskripsi belum dibuat oleh guru pengampu.</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

        <!-- Quick Glance: Tabel Progres Pengisian Nilai Guru -->
        <div class="bg-white rounded-md shadow-md overflow-hidden border border-gray-200">
            <div class="bg-[#8B1515] text-white px-6 py-4 font-bold tracking-wider uppercase text-sm border-b border-red-800 flex justify-between items-center">
                <span>Pemantauan Progres Pengisian Guru (Ringkasan)</span>
                <a href="{{ route('kepsek.monitoring.kinerja_guru') }}" class="text-xs text-red-200 hover:text-white underline">
                    Lihat Semua &rarr;
                </a>
            </div>
            
            <div class="p-6 overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-700 border">
                    <thead class="text-xs text-white bg-gray-800 uppercase border-b">
                        <tr>
                            <th class="px-3 py-3 w-12 text-center">No</th>
                            <th class="px-4 py-3">Guru Pengampu</th>
                            <th class="px-4 py-3">Mata Pelajaran</th>
                            <th class="px-4 py-3 text-center">Kelas</th>
                            <th class="px-4 py-3 text-center">Jml Siswa</th>
                            <th class="px-4 py-3 w-40">Progres Nilai</th>
                            <th class="px-4 py-3 w-40">Progres Deskripsi</th>
                            <th class="px-4 py-3 text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($progresKinerja->take(10) as $index => $item)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-3 py-3 text-center text-gray-500 font-medium">{{ $index + 1 }}</td>
                                <td class="px-4 py-3 font-semibold text-gray-900">
                                    {{ $item['guru']->nama_lengkap ?? '-' }}
                                </td>
                                <td class="px-4 py-3 font-medium text-gray-800">
                                    {{ $item['mapel']->nama_mapel ?? '-' }}
                                </td>
                                <td class="px-4 py-3 text-center font-bold text-gray-700">
                                    {{ $item['rombel']->nama_rombel ?? '-' }}
                                </td>
                                <td class="px-4 py-3 text-center font-semibold text-gray-800">
                                    {{ $item['total_siswa'] }}
                                </td>
                                
                                <!-- Progres Nilai -->
                                <td class="px-4 py-3">
                                    <div class="flex justify-between items-center text-xs mb-1">
                                        <span class="font-semibold">{{ $item['terisi_nilai'] }}/{{ $item['total_siswa'] }}</span>
                                        <span class="font-bold">{{ $item['persen_nilai'] }}%</span>
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-2 overflow-hidden">
                                        <div class="h-2 rounded-full {{ $item['persen_nilai'] == 100 ? 'bg-green-500' : ($item['persen_nilai'] > 0 ? 'bg-yellow-500' : 'bg-transparent') }}" 
                                             style="width: {{ $item['persen_nilai'] }}%"></div>
                                    </div>
                                </td>

                                <!-- Progres Deskripsi -->
                                <td class="px-4 py-3">
                                    <div class="flex justify-between items-center text-xs mb-1">
                                        <span class="font-semibold">{{ $item['terisi_deskripsi'] }}/{{ $item['total_siswa'] }}</span>
                                        <span class="font-bold">{{ $item['persen_deskripsi'] }}%</span>
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-2 overflow-hidden">
                                        <div class="h-2 rounded-full {{ $item['persen_deskripsi'] == 100 ? 'bg-green-500' : ($item['persen_deskripsi'] > 0 ? 'bg-yellow-500' : 'bg-transparent') }}" 
                                             style="width: {{ $item['persen_deskripsi'] }}%"></div>
                                    </div>
                                </td>

                                <!-- Status Badge -->
                                <td class="px-4 py-3 text-center">
                                    @if($item['status'] === 'Tuntas')
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-bold bg-green-100 text-green-800">
                                            Tuntas
                                        </span>
                                    @elseif($item['status'] === 'Sebagian')
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-bold bg-yellow-100 text-yellow-800">
                                            Sebagian
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-bold bg-red-100 text-red-800">
                                            Belum Mengisi
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-4 py-4 text-center text-gray-500">Belum ada data pembelajaran.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($progresKinerja->count() > 10)
                <div class="p-3 bg-gray-50 border-t text-center">
                    <a href="{{ route('kepsek.monitoring.kinerja_guru') }}" class="text-sm font-semibold text-[#8B1515] hover:underline">
                        Lihat {{ $progresKinerja->count() - 10 }} data pembelajaran lainnya &rarr;
                    </a>
                </div>
            @endif
        </div>

    </div>

    <!-- Chart.js Script -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Chart Persebaran Siswa
            var ctxSiswa = document.getElementById('siswaChart').getContext('2d');
            var labelsSiswa = {!! $chart_labels ?? '[]' !!};
            var dataSiswa = {!! $chart_data ?? '[]' !!};

            new Chart(ctxSiswa, {
                type: 'bar',
                data: {
                    labels: labelsSiswa,
                    datasets: [{
                        label: 'Jumlah Siswa',
                        data: dataSiswa,
                        backgroundColor: 'rgba(54, 162, 235, 0.75)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1.5,
                        borderRadius: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: { stepSize: 1 }
                        }
                    },
                    plugins: { legend: { display: false } }
                }
            });

            // Chart Analitik Nilai Adaptif
            var ctxNilai = document.getElementById('nilaiChart').getContext('2d');
            var labelsNilai = {!! $chart_nilai_labels ?? '[]' !!};
            var dataNilai = {!! $chart_nilai_data ?? '[]' !!};

            var chartColor = 'rgba(139, 21, 21, 0.75)';
            var chartBorderColor = 'rgba(139, 21, 21, 1)';
            @if($chart_nilai_type === 'rombel')
                chartColor = 'rgba(245, 158, 11, 0.75)';
                chartBorderColor = 'rgba(245, 158, 11, 1)';
            @elseif($chart_nilai_type === 'siswa')
                chartColor = 'rgba(16, 185, 129, 0.75)';
                chartBorderColor = 'rgba(16, 185, 129, 1)';
            @endif

            new Chart(ctxNilai, {
                type: 'bar',
                data: {
                    labels: labelsNilai,
                    datasets: [{
                        label: 'Nilai',
                        data: dataNilai,
                        backgroundColor: chartColor,
                        borderColor: chartBorderColor,
                        borderWidth: 1.5,
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
                    plugins: { 
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return 'Nilai: ' + context.parsed.y;
                                }
                            }
                        }
                    }
                }
            });
        });
    </script>
</x-app-layout>
