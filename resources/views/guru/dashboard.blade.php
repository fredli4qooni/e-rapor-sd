<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Dashboard Guru') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <!-- Welcome Header -->
            <div class="bg-gradient-to-r from-red-800 to-red-600 rounded-2xl shadow-xl overflow-hidden relative">
                <div class="absolute inset-0 bg-black opacity-10"></div>
                <div class="relative p-8 flex items-center justify-between z-10">
                    <div class="text-white">
                        <h3 class="text-3xl font-bold mb-2">Selamat Datang, {{ $guru->nama_lengkap ?? Auth::user()->name }}!</h3>
                        <p class="text-red-100 text-lg">Anda login sebagai Guru pada Semester {{ $semesterAktif ? $semesterAktif->tahun_ajaran . ' ' . ($semesterAktif->semester == 1 ? 'Ganjil' : 'Genap') : 'Belum Ada Semester Aktif' }}.</p>
                        @if($semesterAktif && $semesterAktif->periode_semester_teks !== '-')
                            <div class="mt-3 inline-flex items-center gap-2 bg-black/25 px-3 py-1 rounded-full text-xs text-red-100 border border-white/20">
                                <svg class="w-4 h-4 text-yellow-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                <span>Periode Semester: <strong>{{ $semesterAktif->periode_semester_teks }}</strong></span>
                            </div>
                        @endif
                    </div>
                    <div class="hidden md:block">
                        <svg class="w-24 h-24 text-white opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Interactive Notification: Pengingat Periode & Batas Waktu Input Nilai -->
            @if($notifikasiDeadline)
                @if($notifikasiDeadline['is_tuntas'])
                    <!-- 1. Kondisi TUNTAS 100% -->
                    <div class="bg-gradient-to-r from-emerald-600 to-green-600 text-white rounded-xl shadow-lg p-5 flex flex-col md:flex-row items-start md:items-center justify-between gap-4 border border-green-400">
                        <div class="flex items-center gap-4">
                            <div class="bg-white/20 p-3 rounded-full flex-shrink-0">
                                <svg class="w-8 h-8 text-yellow-300" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                            </div>
                            <div>
                                <div class="flex items-center gap-2">
                                    <h4 class="text-lg font-bold">Luar Biasa! Penilaian Anda Sudah Tuntas 100%</h4>
                                    <span class="bg-white text-green-700 text-xs px-2.5 py-0.5 rounded-full font-extrabold uppercase">Tuntas</span>
                                </div>
                                <p class="text-sm text-green-100 mt-1">
                                    Seluruh <strong>{{ $notifikasiDeadline['total_siswa_target'] }} siswa</strong> pada semua mata pelajaran yang Anda ampu telah terisi Nilai Rapor dan Deskripsi Capaian Kompetensinya.
                                </p>
                            </div>
                        </div>
                        <a href="{{ route('guru.nilai-tersimpan.rapor') }}" class="bg-white hover:bg-green-50 text-green-800 font-bold text-xs uppercase tracking-wider py-2.5 px-4 rounded-lg shadow transition-all flex items-center gap-1.5 flex-shrink-0">
                            <span>Lihat Rekap Nilai</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                        </a>
                    </div>

                @elseif($notifikasiDeadline['status'] === 'hari_h')
                    <!-- 2. Kondisi HARI-H (Batas Terakhir Hari Ini!) -->
                    <div class="bg-gradient-to-r from-red-600 to-rose-700 text-white rounded-xl shadow-xl p-5 border-2 border-red-300 animate-pulse">
                        <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
                            <div class="flex items-start gap-4">
                                <div class="bg-white/20 p-3 rounded-full flex-shrink-0">
                                    <svg class="w-8 h-8 text-yellow-300 animate-bounce" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                </div>
                                <div>
                                    <div class="flex items-center gap-2">
                                        <span class="bg-yellow-400 text-red-900 text-xs px-2.5 py-0.5 rounded font-black uppercase tracking-wider">Perhatian Darurat</span>
                                        <h4 class="text-xl font-black">HARI INI ADALAH BATAS TERAKHIR INPUT NILAI!</h4>
                                    </div>
                                    <p class="text-sm text-red-100 mt-1">
                                        Batas waktu pengisian nilai semester berakhir <strong>hari ini ({{ $notifikasiDeadline['deadline_teks'] }})</strong>. Anda masih memiliki <strong>{{ $notifikasiDeadline['belum_dinilai'] }} siswa</strong> yang belum selesai dinilai.
                                    </p>
                                    <div class="mt-3 flex items-center gap-3">
                                        <div class="w-48 bg-black/30 rounded-full h-2.5 overflow-hidden">
                                            <div class="bg-yellow-400 h-2.5 rounded-full" style="width: {{ $notifikasiDeadline['persen_terisi'] }}%"></div>
                                        </div>
                                        <span class="text-xs font-bold">{{ $notifikasiDeadline['persen_terisi'] }}% Selesai ({{ $notifikasiDeadline['total_nilai_terisi'] }}/{{ $notifikasiDeadline['total_siswa_target'] }} Siswa)</span>
                                    </div>
                                </div>
                            </div>
                            <a href="{{ route('guru.nilai.index') }}" class="bg-yellow-400 hover:bg-yellow-300 text-red-950 font-black text-sm py-3 px-6 rounded-lg shadow-lg transition-transform hover:scale-105 flex items-center gap-2 flex-shrink-0">
                                <span>Input Nilai Sekarang</span>
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path></svg>
                            </a>
                        </div>
                    </div>

                @elseif($notifikasiDeadline['status'] === 'mendekati_deadline')
                    <!-- 3. Kondisi MENDEKATI DEADLINE (<= 14 Hari) -->
                    <div class="bg-gradient-to-r from-amber-500 to-orange-600 text-white rounded-xl shadow-lg p-5 border border-amber-300">
                        <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
                            <div class="flex items-start gap-4">
                                <div class="bg-white/20 p-3 rounded-full flex-shrink-0">
                                    <svg class="w-8 h-8 text-yellow-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                </div>
                                <div>
                                    <div class="flex flex-wrap items-center gap-2">
                                        <h4 class="text-lg font-bold">Pengingat: Waktu Input Nilai Tersisa {{ $notifikasiDeadline['sisa_hari'] }} Hari Lagi!</h4>
                                        <span class="bg-red-700/80 text-white text-xs px-2.5 py-0.5 rounded-full font-bold">
                                            Batas: {{ $notifikasiDeadline['deadline_teks'] }}
                                        </span>
                                    </div>
                                    <p class="text-sm text-amber-100 mt-1">
                                        Periode pengisian nilai mendekati batas akhir. Mohon segera melengkapi nilai rapor dan deskripsi capaian kompetensi siswa sebelum akses ditutup.
                                    </p>
                                    <div class="mt-3 flex items-center gap-3">
                                        <div class="w-48 bg-black/20 rounded-full h-2.5 overflow-hidden">
                                            <div class="bg-white h-2.5 rounded-full" style="width: {{ $notifikasiDeadline['persen_terisi'] }}%"></div>
                                        </div>
                                        <span class="text-xs font-semibold text-amber-100">
                                            Progres Anda: <strong>{{ $notifikasiDeadline['total_nilai_terisi'] }}/{{ $notifikasiDeadline['total_siswa_target'] }} siswa</strong> ({{ $notifikasiDeadline['persen_terisi'] }}%)
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <a href="{{ route('guru.nilai.index') }}" class="bg-white hover:bg-amber-50 text-orange-900 font-bold text-xs uppercase tracking-wider py-2.5 px-5 rounded-lg shadow transition-all flex items-center gap-1.5 flex-shrink-0">
                                <span>Input Nilai Sekarang</span>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                            </a>
                        </div>
                    </div>

                @elseif($notifikasiDeadline['status'] === 'lewat_deadline')
                    <!-- 4. Kondisi LEWAT DEADLINE -->
                    <div class="bg-red-50 border-l-4 border-red-600 rounded-xl p-4 shadow-sm flex items-start gap-4">
                        <div class="p-2 bg-red-100 text-red-600 rounded-full flex-shrink-0">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                        </div>
                        <div class="flex-1">
                            <h4 class="text-base font-bold text-red-800">Batas Waktu Pengisian Nilai Semester Telah Berakhir</h4>
                            <p class="text-sm text-red-700 mt-0.5">
                                Batas waktu pengisian nilai telah lewat pada tanggal <strong>{{ $notifikasiDeadline['deadline_teks'] }}</strong> (lewat {{ abs($notifikasiDeadline['sisa_hari']) }} hari lalu).
                                @if(!$notifikasiDeadline['is_tuntas'])
                                    Masih terdapat <strong>{{ $notifikasiDeadline['belum_dinilai'] }} siswa</strong> yang belum lengkap nilainya. Hubungi Administrator jika membutuhkan perpanjangan waktu.
                                @endif
                            </p>
                        </div>
                    </div>

                @elseif($notifikasiDeadline['status'] === 'ditutup')
                    <!-- 5. Kondisi DITUTUP ADMIN -->
                    <div class="bg-gray-100 border-l-4 border-gray-600 rounded-xl p-4 shadow-sm flex items-start gap-4">
                        <div class="p-2 bg-gray-200 text-gray-700 rounded-full flex-shrink-0">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                        </div>
                        <div class="flex-1">
                            <h4 class="text-base font-bold text-gray-800">Akses Pengisian Nilai Sedang Ditutup</h4>
                            <p class="text-sm text-gray-600 mt-0.5">
                                Administrator saat ini sedang menutup akses pengisian nilai oleh guru dan wali kelas.
                            </p>
                        </div>
                    </div>

                @elseif($notifikasiDeadline['status'] === 'belum_buka')
                    <!-- 6. Kondisi BELUM BUKA -->
                    <div class="bg-blue-50 border-l-4 border-blue-600 rounded-xl p-4 shadow-sm flex items-start gap-4">
                        <div class="p-2 bg-blue-100 text-blue-600 rounded-full flex-shrink-0">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        </div>
                        <div class="flex-1">
                            <h4 class="text-base font-bold text-blue-800">Periode Pengisian Nilai Belum Dibuka</h4>
                            <p class="text-sm text-blue-700 mt-0.5">
                                Pengisian nilai rapor semester akan dibuka mulai tanggal <strong>{{ $notifikasiDeadline['tanggal_mulai_input'] }}</strong>.
                            </p>
                        </div>
                    </div>

                @elseif($notifikasiDeadline['ada_deadline'])
                    <!-- 7. Kondisi AKTIF (> 14 Hari Tersisa) -->
                    <div class="bg-white rounded-xl shadow-md p-5 border-l-4 border-[#8B1515] flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
                        <div class="flex items-start gap-4">
                            <div class="p-2.5 bg-red-50 text-[#8B1515] rounded-full flex-shrink-0">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            </div>
                            <div>
                                <div class="flex flex-wrap items-center gap-2">
                                    <h4 class="text-base font-bold text-gray-800">Periode Pengisian Nilai Semester Aktif</h4>
                                    <span class="bg-green-100 text-green-800 text-xs px-2.5 py-0.5 rounded font-semibold">Tersisa {{ $notifikasiDeadline['sisa_hari'] }} hari</span>
                                </div>
                                <p class="text-xs text-gray-600 mt-0.5">
                                    Batas akhir pengisian nilai rapor: <strong>{{ $notifikasiDeadline['deadline_teks'] }}</strong>.
                                    Progres pengisian Anda saat ini: <strong>{{ $notifikasiDeadline['total_nilai_terisi'] }}/{{ $notifikasiDeadline['total_siswa_target'] }} siswa</strong> ({{ $notifikasiDeadline['persen_terisi'] }}%).
                                </p>
                            </div>
                        </div>
                        <a href="{{ route('guru.nilai.index') }}" class="bg-[#8B1515] hover:bg-red-700 text-white font-semibold text-xs py-2 px-4 rounded-md shadow transition-colors flex items-center gap-1 flex-shrink-0">
                            <span>Input Nilai</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                        </a>
                    </div>
                @endif
            @endif

            <!-- Rekap Data Section -->
            <h3 class="text-2xl font-bold text-gray-800 border-l-4 border-red-600 pl-3">Statistik Mengajar Anda</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Card 1 -->
                <div class="bg-white rounded-xl shadow-md p-6 border-b-4 border-blue-500 hover:shadow-lg transition-shadow duration-300 transform hover:-translate-y-1">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-blue-100 text-blue-500 mr-4">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 font-semibold uppercase">Mapel Diampu</p>
                            <p class="text-3xl font-bold text-gray-800">{{ $stats['jumlah_mapel'] }}</p>
                        </div>
                    </div>
                </div>

                <!-- Card 2 -->
                <div class="bg-white rounded-xl shadow-md p-6 border-b-4 border-green-500 hover:shadow-lg transition-shadow duration-300 transform hover:-translate-y-1">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-green-100 text-green-500 mr-4">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 font-semibold uppercase">Kelas Diampu</p>
                            <p class="text-3xl font-bold text-gray-800">{{ $stats['jumlah_kelas'] }}</p>
                        </div>
                    </div>
                </div>

                <!-- Card 3 -->
                <div class="bg-white rounded-xl shadow-md p-6 border-b-4 border-yellow-500 hover:shadow-lg transition-shadow duration-300 transform hover:-translate-y-1">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-yellow-100 text-yellow-500 mr-4">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 font-semibold uppercase">Siswa Diajar</p>
                            <p class="text-3xl font-bold text-gray-800">{{ $stats['jumlah_siswa'] }}</p>
                        </div>
                    </div>
                </div>

                <!-- Card 4 (Peran Khusus) -->
                <div class="bg-white rounded-xl shadow-md p-6 border-b-4 border-purple-500 hover:shadow-lg transition-shadow duration-300 transform hover:-translate-y-1">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-purple-100 text-purple-500 mr-4">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path></svg>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm text-gray-500 font-semibold uppercase">Tugas Tambahan</p>
                            @if($stats['sebagai_walikelas'])
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 mt-1">
                                    Wali Kelas: {{ $stats['sebagai_walikelas'] }}
                                </span>
                            @endif
                            @if($stats['jumlah_ekskul'] > 0)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 mt-1">
                                    Pembina {{ $stats['jumlah_ekskul'] }} Ekskul
                                </span>
                            @endif
                            @if($stats['jumlah_proyek'] > 0)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 mt-1">
                                    Koordinator {{ $stats['jumlah_proyek'] }} Proyek
                                </span>
                            @endif
                            @if(!$stats['sebagai_walikelas'] && $stats['jumlah_ekskul'] == 0 && $stats['jumlah_proyek'] == 0)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 mt-1">
                                    Tidak Ada
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Chart Analitik Nilai -->
            <div class="mt-6 bg-white rounded-xl shadow-md p-6 border-t-4 border-green-600">
                <div class="flex items-center mb-4">
                    <svg class="w-6 h-6 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                    <h3 class="text-xl font-bold text-gray-800">Grafik Rata-rata Nilai per Mapel</h3>
                </div>
                <div style="position: relative; height:350px;">
                    <canvas id="nilaiChart"></canvas>
                </div>
            </div>

            <!-- Panduan Aplikasi & Info e-Rapor -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-6">
                <!-- Info Aplikasi -->
                <div class="bg-white rounded-xl shadow-md p-6 border-t-4 border-red-800">
                    <div class="flex items-center mb-4">
                        <svg class="w-6 h-6 text-red-800 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <h3 class="text-xl font-bold text-gray-800">Informasi Aplikasi e-Rapor SD</h3>
                    </div>
                    <div class="prose text-gray-600 max-w-none text-justify">
                        <p>
                            Aplikasi e-Rapor SD merupakan perangkat lunak berbasis web yang dikembangkan untuk membantu 
                            guru dan sekolah dalam melakukan pelaporan hasil belajar peserta didik secara digital.
                        </p>
                        <p class="mt-2">
                            Aplikasi ini memfasilitasi pendidik dalam merencanakan, mengolah, dan melaporkan hasil asesmen sesuai dengan panduan Kurikulum Merdeka.
                        </p>
                    </div>
                    <div class="mt-6 flex flex-wrap gap-3">
                        <a href="{{ route('guru.tujuan-pembelajaran.index') }}" class="inline-flex items-center px-4 py-2 bg-red-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 active:bg-red-900 focus:outline-none focus:border-red-900 focus:ring ring-red-300 disabled:opacity-25 transition ease-in-out duration-150">
                            Kelola Tujuan Pembelajaran
                        </a>
                        <a href="{{ route('guru.nilai.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:ring ring-blue-200 active:text-gray-800 active:bg-gray-50 disabled:opacity-25 transition ease-in-out duration-150">
                            Input Nilai Siswa
                        </a>
                    </div>
                </div>

                <!-- Panduan Aplikasi -->
                <div class="bg-white rounded-xl shadow-md p-6 border-t-4 border-indigo-600">
                    <div class="flex items-center mb-4">
                        <svg class="w-6 h-6 text-indigo-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                        <h3 class="text-xl font-bold text-gray-800">Panduan Aplikasi</h3>
                    </div>
                    <div class="space-y-4">
                        <a href="{{ route('panduan.index') }}" class="block w-full text-left bg-gray-50 p-4 rounded-lg border border-gray-200 hover:border-indigo-300 hover:bg-indigo-50 hover:shadow-md transition-all duration-200 group">
                            <div class="flex justify-between items-center">
                                <div>
                                    <h4 class="text-lg font-bold text-indigo-700 group-hover:text-indigo-800">Panduan Aplikasi e-Rapor</h4>
                                    <p class="text-sm text-gray-600">Buku petunjuk operasional tata cara pengisian e-Rapor SD.</p>
                                </div>
                                <svg class="w-8 h-8 text-indigo-400 group-hover:text-indigo-600 transform group-hover:translate-x-1 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                            </div>
                        </a>

                        <a href="https://kurikulum.kemdikbud.go.id/wp-content/uploads/2022/06/Panduan-Pembelajaran-dan-Asesmen.pdf" target="_blank" class="block w-full text-left bg-gray-50 p-4 rounded-lg border border-gray-200 hover:border-indigo-300 hover:bg-indigo-50 hover:shadow-md transition-all duration-200 group">
                            <div class="flex justify-between items-center">
                                <div>
                                    <h4 class="text-lg font-bold text-indigo-700 group-hover:text-indigo-800">Panduan Pembelajaran & Asesmen</h4>
                                    <p class="text-sm text-gray-600">Referensi implementasi Kurikulum Merdeka dari Kemdikbud.</p>
                                </div>
                                <svg class="w-8 h-8 text-indigo-400 group-hover:text-indigo-600 transform group-hover:translate-x-1 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                            </div>
                        </a>
                    </div>
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
                        label: 'Rata-rata Nilai',
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
