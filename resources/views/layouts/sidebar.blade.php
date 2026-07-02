<!-- Sidebar -->
<aside class="bg-gradient-to-b from-[#8B1515] to-[#4A0808] shadow-2xl text-white w-64 flex-shrink-0 flex flex-col min-h-screen transition-all duration-300 hidden md:flex" id="sidebar">
    <!-- Header Sidebar -->
    <div class="h-16 flex items-center px-4 bg-red-800 border-b border-red-900/50">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 mr-2 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
        </svg>
        <span class="font-bold text-lg tracking-wider">e-Rapor SD</span>
    </div>

    <!-- Navigation Menu -->
    <div class="flex-1 overflow-y-auto py-4">
        <nav class="space-y-1">
            @if (Auth::user()->role === 'admin')
                <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'bg-red-800 border-l-4 border-white text-white' : 'border-l-4 border-transparent hover:bg-red-800 hover:text-white text-red-100' }} group flex items-center px-3 py-2 text-sm font-medium transition-colors">
                    <svg class="mr-3 flex-shrink-0 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    Dashboard
                </a>
                <a href="{{ route('admin.users.index') }}" class="{{ request()->routeIs('admin.users.*') ? 'bg-red-800 border-l-4 border-white text-white' : 'border-l-4 border-transparent hover:bg-red-800 hover:text-white text-red-100' }} group flex items-center px-3 py-2 text-sm font-medium transition-colors">
                    <svg class="mr-3 flex-shrink-0 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                    Data Pengguna
                </a>

                <!-- Data Referensi Dropdown -->
                <div x-data="{ open: {{ request()->routeIs('admin.referensi.*') || request()->routeIs('admin.mapel.*') || request()->routeIs('admin.sekolah.*') || request()->routeIs('admin.guru.*') || request()->routeIs('admin.siswa.*') || request()->routeIs('admin.rombel.*') || request()->routeIs('admin.ekskul.*') || request()->routeIs('admin.pembelajaran.*') || request()->routeIs('admin.kelompok_mapel.*') || request()->routeIs('admin.mapping_rapor.*') || request()->routeIs('admin.tanggal_rapor.*') ? 'true' : 'false' }} }" class="space-y-1">
                    <button @click="open = !open" type="button" class="{{ request()->routeIs('admin.referensi.*') || request()->routeIs('admin.mapel.*') || request()->routeIs('admin.sekolah.*') || request()->routeIs('admin.guru.*') || request()->routeIs('admin.siswa.*') || request()->routeIs('admin.rombel.*') || request()->routeIs('admin.ekskul.*') || request()->routeIs('admin.pembelajaran.*') || request()->routeIs('admin.kelompok_mapel.*') || request()->routeIs('admin.mapping_rapor.*') || request()->routeIs('admin.tanggal_rapor.*') ? 'bg-red-800 border-l-4 border-white text-white' : 'border-l-4 border-transparent text-red-100 hover:bg-red-800 hover:text-white' }} group w-full flex items-center px-3 py-2 text-sm font-medium transition-colors focus:outline-none">
                        <svg class="mr-3 flex-shrink-0 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                        </svg>
                        <span class="flex-1 text-left">Data Referensi</span>
                        <svg :class="{'rotate-90': open}" class="ml-3 flex-shrink-0 h-5 w-5 transform transition-colors ease-in-out duration-150" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                        </svg>
                    </button>
                    <div x-show="open" x-cloak class="space-y-1" style="display: none;">
                        <a href="{{ route('admin.sekolah.index') }}" class="{{ request()->routeIs('admin.sekolah.*') ? 'bg-red-800 text-white' : 'text-red-200 hover:text-white hover:bg-red-800' }} group w-full flex items-center pl-11 pr-2 py-2 text-sm font-medium">Data Sekolah</a>
                        <a href="{{ route('admin.guru.index') }}" class="{{ request()->routeIs('admin.guru.*') ? 'bg-red-800 text-white' : 'text-red-200 hover:text-white hover:bg-red-800' }} group w-full flex items-center pl-11 pr-2 py-2 text-sm font-medium">Data Guru</a>
                        <a href="{{ route('admin.siswa.index') }}" class="{{ request()->routeIs('admin.siswa.*') ? 'bg-red-800 text-white' : 'text-red-200 hover:text-white hover:bg-red-800' }} group w-full flex items-center pl-11 pr-2 py-2 text-sm font-medium">Data Siswa</a>
                        <a href="{{ route('admin.rombel.index') }}" class="{{ request()->routeIs('admin.rombel.*') ? 'bg-red-800 text-white' : 'text-red-200 hover:text-white hover:bg-red-800' }} group w-full flex items-center pl-11 pr-2 py-2 text-sm font-medium">Data Kelas/Rombel</a>
                        <a href="{{ route('admin.mapel.index') }}" class="{{ request()->routeIs('admin.mapel.*') ? 'bg-red-800 text-white' : 'text-red-200 hover:text-white hover:bg-red-800' }} group w-full flex items-center pl-11 pr-2 py-2 text-sm font-medium">Data Mata Pelajaran</a>
                        <a href="{{ route('admin.kelompok_mapel.index') }}" class="{{ request()->routeIs('admin.kelompok_mapel.*') ? 'bg-red-800 text-white' : 'text-red-200 hover:text-white hover:bg-red-800' }} group w-full flex items-center pl-11 pr-2 py-2 text-sm font-medium">Kelompok Mapel</a>
                        <a href="{{ route('admin.mapping_rapor.index') }}" class="{{ request()->routeIs('admin.mapping_rapor.*') ? 'bg-red-800 text-white' : 'text-red-200 hover:text-white hover:bg-red-800' }} group w-full flex items-center pl-11 pr-2 py-2 text-sm font-medium">Mapping Rapor</a>
                        <a href="{{ route('admin.tanggal_rapor.index') }}" class="{{ request()->routeIs('admin.tanggal_rapor.*') ? 'bg-red-800 text-white' : 'text-red-200 hover:text-white hover:bg-red-800' }} group w-full flex items-center pl-11 pr-2 py-2 text-sm font-medium">Tanggal Rapor</a>
                        <a href="{{ route('admin.pembelajaran.index') }}" class="{{ request()->routeIs('admin.pembelajaran.*') ? 'bg-red-800 text-white' : 'text-red-200 hover:text-white hover:bg-red-800' }} group w-full flex items-center pl-11 pr-2 py-2 text-sm font-medium">Data Pembelajaran</a>
                        <a href="{{ route('admin.ekskul.index') }}" class="{{ request()->routeIs('admin.ekskul.*') ? 'bg-red-800 text-white' : 'text-red-200 hover:text-white hover:bg-red-800' }} group w-full flex items-center pl-11 pr-2 py-2 text-sm font-medium">Data Ekstrakurikuler</a>
                    </div>
                </div>

                <!-- Data Kokurikuler -->
                <div x-data="{ open: {{ request()->routeIs('admin.p5.*') ? 'true' : 'false' }} }" class="space-y-1">
                    <button @click="open = !open" class="{{ request()->routeIs('admin.p5.*') ? 'bg-red-800 border-l-4 border-white text-white' : 'border-l-4 border-transparent text-red-100 hover:bg-red-800 hover:text-white' }} group w-full flex items-center px-3 py-2 text-sm font-medium transition-colors focus:outline-none">
                        <svg class="mr-3 flex-shrink-0 h-5 w-5 text-red-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10l-2 1m0 0l-2-1m2 1v2.5M20 7l-2 1m2-1l-2-1m2 1v2.5M14 4l-2-1-2 1M4 7l2-1M4 7l2 1M4 7v2.5M12 21l-2-1m2 1l2-1m-2 1v-2.5M6 18l-2-1v-2.5M18 18l2-1v-2.5"></path>
                        </svg>
                        <span class="flex-1 text-left">Data Kokurikuler</span>
                        <svg :class="{'rotate-90 text-white': open, 'text-red-200': !open}" class="ml-3 flex-shrink-0 h-5 w-5 transform transition-colors ease-in-out duration-150" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                        </svg>
                    </button>
                    <div x-show="open" x-cloak class="space-y-1" style="display: none;">
                        <a href="{{ route('admin.p5.referensi.index') }}" class="{{ request()->routeIs('admin.p5.referensi.*') ? 'bg-red-800 text-white' : 'text-red-200 hover:text-white hover:bg-red-800' }} group w-full flex items-center pl-11 pr-2 py-2 text-sm font-medium">Referensi Profil Lulusan</a>
                        <a href="{{ route('admin.p5.tema.index') }}" class="{{ request()->routeIs('admin.p5.tema.*') ? 'bg-red-800 text-white' : 'text-red-200 hover:text-white hover:bg-red-800' }} group w-full flex items-center pl-11 pr-2 py-2 text-sm font-medium">Daftar Tema</a>
                        <a href="{{ route('admin.p5.proyek.index') }}" class="{{ request()->routeIs('admin.p5.proyek.*') ? 'bg-red-800 text-white' : 'text-red-200 hover:text-white hover:bg-red-800' }} group w-full flex items-center pl-11 pr-2 py-2 text-sm font-medium">Kegiatan Kokurikuler</a>
                        <a href="{{ route('admin.p5.kelompok.index') }}" class="{{ request()->routeIs('admin.p5.kelompok.*') ? 'bg-red-800 text-white' : 'text-red-200 hover:text-white hover:bg-red-800' }} group w-full flex items-center pl-11 pr-2 py-2 text-sm font-medium">Kelompok Kokurikuler</a>
                    </div>
                </div>

                <!-- Referensi P5 -->
                <div x-data="{ open: {{ request()->routeIs('admin.referensi_p5.*') ? 'true' : 'false' }} }" class="space-y-1">
                    <button @click="open = !open" class="{{ request()->routeIs('admin.referensi_p5.*') ? 'bg-red-800 border-l-4 border-white text-white' : 'border-l-4 border-transparent text-red-100 hover:bg-red-800 hover:text-white' }} group w-full flex items-center px-3 py-2 text-sm font-medium transition-colors focus:outline-none">
                        <svg class="mr-3 flex-shrink-0 h-5 w-5 text-red-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                        </svg>
                        <span class="flex-1 text-left">Referensi P5</span>
                        <svg :class="{'rotate-90 text-white': open, 'text-red-200': !open}" class="ml-3 flex-shrink-0 h-5 w-5 transform transition-colors ease-in-out duration-150" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                        </svg>
                    </button>
                    <div x-show="open" x-cloak class="space-y-1" style="display: none;">
                        <!-- Menggunakan index referensi yang sama untuk Target Capaian -->
                        <a href="{{ route('admin.p5.referensi.index') }}" class="{{ request()->routeIs('admin.p5.referensi.*') ? 'bg-red-800 text-white' : 'text-red-200 hover:text-white hover:bg-red-800' }} group w-full flex items-center pl-11 pr-2 py-2 text-sm font-medium">Target Capaian Profil</a>
                        <a href="{{ route('admin.referensi_p5.proyek.index') }}" class="{{ request()->routeIs('admin.referensi_p5.proyek.*') ? 'bg-red-800 text-white' : 'text-red-200 hover:text-white hover:bg-red-800' }} group w-full flex items-center pl-11 pr-2 py-2 text-sm font-medium">Data Projek</a>
                        <a href="{{ route('admin.referensi_p5.kelompok.index') }}" class="{{ request()->routeIs('admin.referensi_p5.kelompok.*') ? 'bg-red-800 text-white' : 'text-red-200 hover:text-white hover:bg-red-800' }} group w-full flex items-center pl-11 pr-2 py-2 text-sm font-medium">Kelompok Projek</a>
                    </div>
                </div>

                <!-- Status Penilaian -->
                <div x-data="{ open: {{ request()->routeIs('admin.status_penilaian.*') ? 'true' : 'false' }} }" class="space-y-1">
                    <button @click="open = !open" class="{{ request()->routeIs('admin.status_penilaian.*') ? 'bg-red-800 border-l-4 border-white text-white' : 'border-l-4 border-transparent text-red-100 hover:bg-red-800 hover:text-white' }} group w-full flex items-center px-3 py-2 text-sm font-medium transition-colors focus:outline-none">
                        <svg class="mr-3 flex-shrink-0 h-5 w-5 text-red-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                        <span class="flex-1 text-left">Status Penilaian</span>
                        <svg :class="{'rotate-90 text-white': open, 'text-red-200': !open}" class="ml-3 flex-shrink-0 h-5 w-5 transform transition-colors ease-in-out duration-150" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                        </svg>
                    </button>
                    <div x-show="open" x-cloak class="space-y-1" style="display: none;">
                        <a href="{{ route('admin.status_penilaian.index') }}" class="{{ request()->routeIs('admin.status_penilaian.index') ? 'bg-red-800 text-white' : 'text-red-200 hover:text-white hover:bg-red-800' }} group w-full flex items-center pl-11 pr-2 py-2 text-sm font-medium">Status Penilaian</a>
                        <a href="{{ route('admin.status_penilaian.statistik_rapor') }}" class="{{ request()->routeIs('admin.status_penilaian.statistik_rapor') ? 'bg-red-800 text-white' : 'text-red-200 hover:text-white hover:bg-red-800' }} group w-full flex items-center pl-11 pr-2 py-2 text-sm font-medium">Statistik Nilai Rapor</a>
                        <a href="{{ route('admin.status_penilaian.statistik_p3') }}" class="{{ request()->routeIs('admin.status_penilaian.statistik_p3') ? 'bg-red-800 text-white' : 'text-red-200 hover:text-white hover:bg-red-800' }} group w-full flex items-center pl-11 pr-2 py-2 text-sm font-medium">Statistik Nilai P3</a>
                    </div>
                </div>

                <!-- Perkembangan Nilai -->
                <div x-data="{ open: {{ request()->routeIs('admin.perkembangan_nilai.*') ? 'true' : 'false' }} }" class="space-y-1">
                    <button @click="open = !open" class="{{ request()->routeIs('admin.perkembangan_nilai.*') ? 'bg-red-800 border-l-4 border-white text-white' : 'border-l-4 border-transparent text-red-100 hover:bg-red-800 hover:text-white' }} group w-full flex items-center px-3 py-2 text-sm font-medium transition-colors focus:outline-none">
                        <svg class="mr-3 flex-shrink-0 h-5 w-5 text-red-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                        </svg>
                        <span class="flex-1 text-left">Perkembangan Nilai</span>
                        <svg :class="{'rotate-90 text-white': open, 'text-red-200': !open}" class="ml-3 flex-shrink-0 h-5 w-5 transform transition-colors ease-in-out duration-150" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                        </svg>
                    </button>
                    <div x-show="open" x-cloak class="space-y-1" style="display: none;">
                        <a href="{{ route('admin.perkembangan_nilai.index') }}" class="{{ request()->routeIs('admin.perkembangan_nilai.index', 'admin.perkembangan_nilai.capaian', 'admin.perkembangan_nilai.deskripsi') ? 'bg-red-800 text-white' : 'text-red-200 hover:text-white hover:bg-red-800' }} group w-full flex items-center pl-11 pr-2 py-2 text-sm font-medium">Perkembangan Nilai</a>
                        <a href="{{ route('admin.perkembangan_nilai.grafik') }}" class="{{ request()->routeIs('admin.perkembangan_nilai.grafik') ? 'bg-red-800 text-white' : 'text-red-200 hover:text-white hover:bg-red-800' }} group w-full flex items-center pl-11 pr-2 py-2 text-sm font-medium">Grafik Nilai Rapor</a>
                    </div>
                </div>

                <!-- Transkrip Ijazah -->
                <div x-data="{ open: {{ request()->routeIs('admin.transkrip_ijazah.*') ? 'true' : 'false' }} }" class="space-y-1">
                    <button @click="open = !open" class="{{ request()->routeIs('admin.transkrip_ijazah.*') ? 'bg-red-800 border-l-4 border-white text-white' : 'border-l-4 border-transparent text-red-100 hover:bg-red-800 hover:text-white' }} group w-full flex items-center px-3 py-2 text-sm font-medium transition-colors focus:outline-none">
                        <svg class="mr-3 flex-shrink-0 h-5 w-5 text-red-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                        <span class="flex-1 text-left">Transkrip Ijazah</span>
                        <svg :class="{'rotate-90 text-white': open, 'text-red-200': !open}" class="ml-3 flex-shrink-0 h-5 w-5 transform transition-colors ease-in-out duration-150" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                        </svg>
                    </button>
                    <div x-show="open" x-cloak class="space-y-1" style="display: none;">
                        <a href="{{ route('admin.transkrip_ijazah.import_nomor.index') }}" class="{{ request()->routeIs('admin.transkrip_ijazah.import_nomor.*') ? 'bg-red-800 text-white' : 'text-red-200 hover:text-white hover:bg-red-800' }} group w-full flex items-center pl-11 pr-2 py-2 text-sm font-medium">Import Nomor Ijazah</a>
                        <a href="{{ route('admin.transkrip_ijazah.setting.index') }}" class="{{ request()->routeIs('admin.transkrip_ijazah.setting.*') ? 'bg-red-800 text-white' : 'text-red-200 hover:text-white hover:bg-red-800' }} group w-full flex items-center pl-11 pr-2 py-2 text-sm font-medium">Setting Transkrip</a>
                        <a href="{{ route('admin.transkrip_ijazah.mapping_mapel.index') }}" class="{{ request()->routeIs('admin.transkrip_ijazah.mapping_mapel.*') ? 'bg-red-800 text-white' : 'text-red-200 hover:text-white hover:bg-red-800' }} group w-full flex items-center pl-11 pr-2 py-2 text-sm font-medium">Mapping Mapel</a>
                        <a href="{{ route('admin.transkrip_ijazah.input_nilai.index') }}" class="{{ request()->routeIs('admin.transkrip_ijazah.input_nilai.*') ? 'bg-red-800 text-white' : 'text-red-200 hover:text-white hover:bg-red-800' }} group w-full flex items-center pl-11 pr-2 py-2 text-sm font-medium">Input Nilai Transkrip</a>
                        <a href="{{ route('admin.transkrip_ijazah.import_nilai.index') }}" class="{{ request()->routeIs('admin.transkrip_ijazah.import_nilai.*') ? 'bg-red-800 text-white' : 'text-red-200 hover:text-white hover:bg-red-800' }} group w-full flex items-center pl-11 pr-2 py-2 text-sm font-medium">Import Nilai Transkrip</a>
                        <a href="{{ route('admin.transkrip_ijazah.cetak.index') }}" class="{{ request()->routeIs('admin.transkrip_ijazah.cetak.*') ? 'bg-red-800 text-white' : 'text-red-200 hover:text-white hover:bg-red-800' }} group w-full flex items-center pl-11 pr-2 py-2 text-sm font-medium">Cetak Transkrip Nilai</a>
                    </div>
                </div>

                <!-- Cetak Nilai -->
                <div x-data="{ open: {{ request()->routeIs('admin.cetak_nilai.*') ? 'true' : 'false' }} }" class="space-y-1">
                    <button @click="open = !open" class="{{ request()->routeIs('admin.cetak_nilai.*') ? 'bg-red-800 border-l-4 border-white text-white' : 'border-l-4 border-transparent text-red-100 hover:bg-red-800 hover:text-white' }} group w-full flex items-center px-3 py-2 text-sm font-medium transition-colors focus:outline-none">
                        <svg class="mr-3 flex-shrink-0 h-5 w-5 text-red-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                        </svg>
                        <span class="flex-1 text-left">Cetak Nilai</span>
                        <svg :class="{'rotate-90 text-white': open, 'text-red-200': !open}" class="ml-3 flex-shrink-0 h-5 w-5 transform transition-colors ease-in-out duration-150" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                        </svg>
                    </button>
                    <div x-show="open" x-cloak class="space-y-1" style="display: none;">
                        <a href="{{ route('admin.cetak_nilai.leger_rapor.index') }}" class="{{ request()->routeIs('admin.cetak_nilai.leger_rapor.*') ? 'bg-red-800 text-white' : 'text-red-200 hover:text-white hover:bg-red-800' }} group w-full flex items-center pl-11 pr-2 py-2 text-sm font-medium">Leger Rapor</a>
                        <a href="{{ route('admin.cetak_nilai.pelengkap_rapor.index') }}" class="{{ request()->routeIs('admin.cetak_nilai.pelengkap_rapor.*') ? 'bg-red-800 text-white' : 'text-red-200 hover:text-white hover:bg-red-800' }} group w-full flex items-center pl-11 pr-2 py-2 text-sm font-medium">Pelengkap Rapor</a>
                        <a href="{{ route('admin.cetak_nilai.nilai_rapor.index') }}" class="{{ request()->routeIs('admin.cetak_nilai.nilai_rapor.*') ? 'bg-red-800 text-white' : 'text-red-200 hover:text-white hover:bg-red-800' }} group w-full flex items-center pl-11 pr-2 py-2 text-sm font-medium">Nilai Rapor</a>
                        <a href="{{ route('admin.cetak_nilai.rapor_p5.index') }}" class="{{ request()->routeIs('admin.cetak_nilai.rapor_p5.*') ? 'bg-red-800 text-white' : 'text-red-200 hover:text-white hover:bg-red-800' }} group w-full flex items-center pl-11 pr-2 py-2 text-sm font-medium">Rapor P5</a>
                    </div>
                </div>

                <!-- Backup & Restore -->
                <a href="{{ route('admin.backup_restore.index') }}" class="{{ request()->routeIs('admin.backup_restore.*') ? 'bg-red-800 border-l-4 border-white text-white' : 'border-l-4 border-transparent hover:bg-red-800 hover:text-white text-red-100' }} group flex items-center px-3 py-2 text-sm font-medium transition-colors">
                    <svg class="mr-3 flex-shrink-0 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
                    </svg>
                    Backup & Restore
                </a>
            @endif

            @if (Auth::user()->role === 'guru')
                <div class="px-3 mt-4 mb-2 text-xs font-semibold text-red-300 uppercase tracking-wider">
                    Menu Guru Mapel
                </div>
                <a href="{{ route('guru.dashboard') }}" class="{{ request()->routeIs('guru.dashboard') ? 'bg-red-800 border-l-4 border-white text-white' : 'border-l-4 border-transparent hover:bg-red-800 hover:text-white text-red-100' }} group flex items-center px-3 py-2 text-sm font-medium transition-colors">
                    <svg class="mr-3 flex-shrink-0 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    Dashboard Guru
                </a>
                <a href="{{ route('guru.tujuan-pembelajaran.index') }}" class="{{ request()->routeIs('guru.tujuan-pembelajaran.*') ? 'bg-red-800 border-l-4 border-white text-white' : 'border-l-4 border-transparent hover:bg-red-800 hover:text-white text-red-100' }} group flex items-center px-3 py-2 text-sm font-medium transition-colors">
                    <svg class="mr-3 flex-shrink-0 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                    </svg>
                    Tujuan Pembelajaran
                </a>
                <div x-data="{ open: {{ request()->routeIs('guru.nilai.*') ? 'true' : 'false' }} }" class="space-y-1">
                    <button @click="open = !open" class="{{ request()->routeIs('guru.nilai.*') ? 'bg-red-800 border-l-4 border-white text-white' : 'border-l-4 border-transparent hover:bg-red-800 hover:text-white text-red-100' }} group w-full flex items-center px-3 py-2 text-sm font-medium transition-colors focus:outline-none">
                        <svg class="mr-3 flex-shrink-0 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                        </svg>
                        <span class="flex-1 text-left">Input Nilai Rapor</span>
                        <svg :class="{'rotate-90 text-white': open, 'text-red-200': !open}" class="ml-3 flex-shrink-0 h-5 w-5 transform transition-colors ease-in-out duration-150" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                        </svg>
                    </button>
                    <div x-show="open" x-cloak class="space-y-1" style="display: none;">
                        <a href="{{ route('guru.nilai.index') }}" class="{{ request()->routeIs('guru.nilai.index', 'guru.nilai.store', 'guru.nilai.deskripsi', 'guru.nilai.update_deskripsi') ? 'bg-red-800 text-white' : 'text-red-200 hover:text-white hover:bg-red-800' }} group w-full flex items-center pl-11 pr-2 py-2 text-sm font-medium">Input Nilai Rapor</a>
                        <a href="{{ route('guru.nilai.import_index') }}" class="{{ request()->routeIs('guru.nilai.import*') ? 'bg-red-800 text-white' : 'text-red-200 hover:text-white hover:bg-red-800' }} group w-full flex items-center pl-11 pr-2 py-2 text-sm font-medium">Import Nilai Rapor</a>
                    </div>
                </div>
                
                <div x-data="{ open: {{ request()->routeIs('guru.nilai-tersimpan.*') ? 'true' : 'false' }} }" class="space-y-1">
                    <button @click="open = !open" class="{{ request()->routeIs('guru.nilai-tersimpan.*') ? 'bg-red-800 border-l-4 border-white text-white' : 'border-l-4 border-transparent hover:bg-red-800 hover:text-white text-red-100' }} group w-full flex items-center px-3 py-2 text-sm font-medium transition-colors focus:outline-none">
                        <svg class="mr-3 flex-shrink-0 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
                        <span class="flex-1 text-left">Nilai Tersimpan</span>
                        <svg :class="{'rotate-90 text-white': open, 'text-red-200': !open}" class="ml-3 flex-shrink-0 h-5 w-5 transform transition-colors ease-in-out duration-150" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                        </svg>
                    </button>
                    <div x-show="open" x-cloak class="space-y-1" style="display: none;">
                        <a href="{{ route('guru.nilai-tersimpan.rapor') }}" class="{{ request()->routeIs('guru.nilai-tersimpan.rapor') ? 'bg-red-800 text-white' : 'text-red-200 hover:text-white hover:bg-red-800' }} group w-full flex items-center pl-11 pr-2 py-2 text-sm font-medium">Cek Nilai Rapor</a>
                        <a href="{{ route('guru.nilai-tersimpan.deskripsi') }}" class="{{ request()->routeIs('guru.nilai-tersimpan.deskripsi') ? 'bg-red-800 text-white' : 'text-red-200 hover:text-white hover:bg-red-800' }} group w-full flex items-center pl-11 pr-2 py-2 text-sm font-medium">Cek Deskripsi Rapor</a>
                    </div>
                </div>

                <a href="{{ route('guru.nilai_ekskul.index') }}" class="{{ request()->routeIs('guru.nilai_ekskul.*') ? 'bg-red-800 border-l-4 border-white text-white' : 'border-l-4 border-transparent hover:bg-red-800 hover:text-white text-red-100' }} group w-full flex items-center px-3 py-2 text-sm font-medium transition-colors focus:outline-none">
                    <svg class="mr-3 flex-shrink-0 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    Input Nilai Ekskul
                </a>

                @php
                    $activeSemester = \App\Models\Semester::where('is_aktif', true)->first();
                    $tahunParts = explode('/', $activeSemester ? $activeSemester->tahun_ajaran : '2025/2026');
                    $startYear = (int) $tahunParts[0];
                @endphp

                @if($startYear >= 2025)
                <!-- Dropdown Input Kokurikuler -->
                <div x-data="{ open: {{ request()->routeIs('guru.nilai_kokurikuler.*') ? 'true' : 'false' }} }" class="space-y-1">
                    <button @click="open = !open" type="button" class="{{ request()->routeIs('guru.nilai_kokurikuler.*') ? 'bg-red-800 border-l-4 border-white text-white' : 'border-l-4 border-transparent hover:bg-red-800 hover:text-white text-red-100' }} group w-full flex items-center justify-between px-3 py-2 text-sm font-medium transition-colors focus:outline-none">
                        <div class="flex items-center">
                            <svg class="mr-3 flex-shrink-0 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path></svg>
                            Input Nilai Kokurikuler
                        </div>
                        <svg :class="{'rotate-180': open, 'rotate-0': !open}" class="ml-2 h-4 w-4 transform transition-transform duration-200 text-red-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </button>
                    <div x-show="open" x-collapse style="display: none;" class="bg-red-900/50">
                        <a href="{{ route('guru.nilai_kokurikuler.index') }}" class="{{ request()->routeIs('guru.nilai_kokurikuler.index') ? 'bg-red-800 text-white' : 'text-red-200 hover:text-white hover:bg-red-800' }} group w-full flex items-center pl-11 pr-2 py-2 text-sm font-medium">Input Nilai Kokurikuler</a>
                        <a href="{{ route('guru.nilai_kokurikuler.import_index') }}" class="{{ request()->routeIs('guru.nilai_kokurikuler.import*') ? 'bg-red-800 text-white' : 'text-red-200 hover:text-white hover:bg-red-800' }} group w-full flex items-center pl-11 pr-2 py-2 text-sm font-medium">Import Nilai Kokurikuler</a>
                        <a href="{{ route('guru.nilai_kokurikuler.deskripsi_index') }}" class="{{ request()->routeIs('guru.nilai_kokurikuler.deskripsi*') ? 'bg-red-800 text-white' : 'text-red-200 hover:text-white hover:bg-red-800' }} group w-full flex items-center pl-11 pr-2 py-2 text-sm font-medium">Deskripsi Kokurikuler</a>
                    </div>
                </div>
                @else
                <!-- Dropdown Input P5 -->
                <div x-data="{ open: {{ request()->routeIs('guru.nilai_p5.*') ? 'true' : 'false' }} }" class="space-y-1">
                    <button @click="open = !open" type="button" class="{{ request()->routeIs('guru.nilai_p5.*') ? 'bg-red-800 border-l-4 border-white text-white' : 'border-l-4 border-transparent hover:bg-red-800 hover:text-white text-red-100' }} group w-full flex items-center justify-between px-3 py-2 text-sm font-medium transition-colors focus:outline-none">
                        <div class="flex items-center">
                            <svg class="mr-3 flex-shrink-0 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path></svg>
                            Input P5
                        </div>
                        <svg :class="{'rotate-180': open, 'rotate-0': !open}" class="ml-2 h-4 w-4 transform transition-transform duration-200 text-red-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </button>
                    <div x-show="open" x-collapse style="display: none;" class="bg-red-900/50">
                        <a href="{{ route('guru.nilai_p5.input_capaian') }}" class="{{ request()->routeIs('guru.nilai_p5.input_capaian') ? 'bg-red-800 text-white' : 'text-red-200 hover:text-white hover:bg-red-800' }} group w-full flex items-center pl-11 pr-2 py-2 text-sm font-medium">Input Capaian P5</a>
                        <a href="{{ route('guru.nilai_p5.import_capaian') }}" class="{{ request()->routeIs('guru.nilai_p5.import_capaian') ? 'bg-red-800 text-white' : 'text-red-200 hover:text-white hover:bg-red-800' }} group w-full flex items-center pl-11 pr-2 py-2 text-sm font-medium">Import Capaian P5</a>
                        <a href="{{ route('guru.nilai_p5.input_catatan') }}" class="{{ request()->routeIs('guru.nilai_p5.input_catatan') ? 'bg-red-800 text-white' : 'text-red-200 hover:text-white hover:bg-red-800' }} group w-full flex items-center pl-11 pr-2 py-2 text-sm font-medium">Input Catatan Proses</a>
                        <a href="{{ route('guru.nilai_p5.download_capaian') }}" class="{{ request()->routeIs('guru.nilai_p5.download_capaian') ? 'bg-red-800 text-white' : 'text-red-200 hover:text-white hover:bg-red-800' }} group w-full flex items-center pl-11 pr-2 py-2 text-sm font-medium">Download Capaian P5</a>
                    </div>
                </div>
                @endif

                <!-- Dropdown Cek Penilaian -->
                <div x-data="{ open: {{ request()->routeIs('guru.cek_penilaian.*') ? 'true' : 'false' }} }" class="space-y-1">
                    <button @click="open = !open" type="button" class="{{ request()->routeIs('guru.cek_penilaian.*') ? 'bg-red-800 border-l-4 border-white text-white' : 'border-l-4 border-transparent hover:bg-red-800 hover:text-white text-red-100' }} group w-full flex items-center justify-between px-3 py-2 text-sm font-medium transition-colors focus:outline-none">
                        <div class="flex items-center">
                            <svg class="mr-3 flex-shrink-0 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422A12.083 12.083 0 0112 21.5a12.083 12.083 0 01-6.16-10.922L12 14z"></path></svg>
                            Cek Penilaian
                        </div>
                        <svg :class="{'rotate-180': open, 'rotate-0': !open}" class="ml-2 h-4 w-4 transform transition-transform duration-200 text-red-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </button>
                    <div x-show="open" x-collapse style="display: none;" class="bg-red-900/50">
                        <a href="{{ route('guru.cek_penilaian.status') }}" class="{{ request()->routeIs('guru.cek_penilaian.status') ? 'bg-red-800 text-white' : 'text-red-200 hover:text-white hover:bg-red-800' }} group w-full flex items-center pl-11 pr-2 py-2 text-sm font-medium">Status Penilaian</a>
                        <a href="{{ route('guru.cek_penilaian.capaian') }}" class="{{ request()->routeIs('guru.cek_penilaian.capaian') ? 'bg-red-800 text-white' : 'text-red-200 hover:text-white hover:bg-red-800' }} group w-full flex items-center pl-11 pr-2 py-2 text-sm font-medium">Capaian Nilai Rapor</a>
                        <a href="{{ route('guru.cek_penilaian.grafik') }}" class="{{ request()->routeIs('guru.cek_penilaian.grafik') ? 'bg-red-800 text-white' : 'text-red-200 hover:text-white hover:bg-red-800' }} group w-full flex items-center pl-11 pr-2 py-2 text-sm font-medium">Grafik Nilai Rapor</a>
                    </div>
                </div>

                <div class="px-3 mt-6 mb-2 text-xs font-semibold text-red-300 uppercase tracking-wider">
                    Menu Wali Kelas
                </div>
                <a href="{{ route('walikelas.dashboard') }}" class="{{ request()->routeIs('walikelas.dashboard') ? 'bg-red-800 border-l-4 border-white text-white' : 'border-l-4 border-transparent hover:bg-red-800 hover:text-white text-red-100' }} group flex items-center px-3 py-2 text-sm font-medium transition-colors">
                    <svg class="mr-3 flex-shrink-0 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    Dashboard Wali Kelas
                </a>

                <!-- Dropdown Input Kelengkapan -->
                <div x-data="{ open: {{ request()->routeIs('walikelas.data_siswa.*', 'walikelas.kehadiran.*', 'walikelas.ekskul.*', 'walikelas.catatan.*', 'walikelas.kenaikan.*', 'walikelas.deskripsi_p3.*', 'walikelas.deskripsi_dpl.*') ? 'true' : 'false' }} }" class="space-y-1">
                    <button @click="open = !open" type="button" class="{{ request()->routeIs('walikelas.data_siswa.*', 'walikelas.kehadiran.*', 'walikelas.ekskul.*', 'walikelas.catatan.*', 'walikelas.kenaikan.*', 'walikelas.deskripsi_p3.*', 'walikelas.deskripsi_dpl.*') ? 'bg-red-800 border-l-4 border-white text-white' : 'border-l-4 border-transparent hover:bg-red-800 hover:text-white text-red-100' }} group w-full flex items-center justify-between px-3 py-2 text-sm font-medium transition-colors focus:outline-none">
                        <div class="flex items-center">
                            <svg class="mr-3 flex-shrink-0 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            Input Kelengkapan
                        </div>
                        <svg :class="{'rotate-180': open, 'rotate-0': !open}" class="ml-2 h-4 w-4 transform transition-transform duration-200 text-red-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </button>
                    <div x-show="open" x-collapse style="display: none;" class="bg-red-900/50">
                        <a href="{{ route('walikelas.data_siswa.index') }}" class="{{ request()->routeIs('walikelas.data_siswa.*') ? 'bg-red-800 text-white' : 'text-red-200 hover:text-white hover:bg-red-800' }} group w-full flex items-center pl-11 pr-2 py-2 text-sm font-medium">Update Data Siswa</a>
                        <a href="{{ route('walikelas.kehadiran.index') }}" class="{{ request()->routeIs('walikelas.kehadiran.*') ? 'bg-red-800 text-white' : 'text-red-200 hover:text-white hover:bg-red-800' }} group w-full flex items-center pl-11 pr-2 py-2 text-sm font-medium">Input Kehadiran</a>
                        <a href="{{ route('walikelas.ekskul.index') }}" class="{{ request()->routeIs('walikelas.ekskul.*') ? 'bg-red-800 text-white' : 'text-red-200 hover:text-white hover:bg-red-800' }} group w-full flex items-center pl-11 pr-2 py-2 text-sm font-medium">Input Nilai Ekskul</a>
                        <a href="{{ route('walikelas.catatan.index') }}" class="{{ request()->routeIs('walikelas.catatan.*') ? 'bg-red-800 text-white' : 'text-red-200 hover:text-white hover:bg-red-800' }} group w-full flex items-center pl-11 pr-2 py-2 text-sm font-medium">Input Catatan Walas</a>
                        <a href="{{ route('walikelas.kenaikan.index') }}" class="{{ request()->routeIs('walikelas.kenaikan.*') ? 'bg-red-800 text-white' : 'text-red-200 hover:text-white hover:bg-red-800' }} group w-full flex items-center pl-11 pr-2 py-2 text-sm font-medium">Input Kenaikan Kelas</a>
                        
                        @php
                            $tahunParts = explode('/', \App\Models\Semester::where('is_aktif', true)->first()->tahun_ajaran ?? '2025/2026');
                            $startYear = (int) $tahunParts[0];
                        @endphp
                        
                        @if($startYear >= 2025)
                        <a href="{{ route('walikelas.deskripsi_dpl.index') }}" class="{{ request()->routeIs('walikelas.deskripsi_dpl.*') ? 'bg-red-800 text-white' : 'text-red-200 hover:text-white hover:bg-red-800' }} group w-full flex items-center pl-11 pr-2 py-2 text-sm font-medium">Deskripsi DPL K2013</a>
                        @else
                        <a href="{{ route('walikelas.deskripsi_p3.index') }}" class="{{ request()->routeIs('walikelas.deskripsi_p3.*') ? 'bg-red-800 text-white' : 'text-red-200 hover:text-white hover:bg-red-800' }} group w-full flex items-center pl-11 pr-2 py-2 text-sm font-medium">Input Deskripsi P3</a>
                        @endif
                    </div>
                </div>

                <!-- Dropdown Cek Penilaian Kelas -->
                <div x-data="{ open: {{ request()->routeIs('walikelas.cek_penilaian_kelas.*') ? 'true' : 'false' }} }" class="space-y-1">
                    <button @click="open = !open" type="button" class="{{ request()->routeIs('walikelas.cek_penilaian_kelas.*') ? 'bg-red-800 border-l-4 border-white text-white' : 'border-l-4 border-transparent hover:bg-red-800 hover:text-white text-red-100' }} group w-full flex items-center justify-between px-3 py-2 text-sm font-medium transition-colors focus:outline-none">
                        <div class="flex items-center">
                            <svg class="mr-3 flex-shrink-0 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422A12.083 12.083 0 0112 21.5a12.083 12.083 0 01-6.16-10.922L12 14z"></path></svg>
                            Cek Penilaian Kelas
                        </div>
                        <svg :class="{'rotate-180': open, 'rotate-0': !open}" class="ml-2 h-4 w-4 transform transition-transform duration-200 text-red-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </button>
                    <div x-show="open" x-collapse style="display: none;" class="bg-red-900/50">
                        <a href="{{ route('walikelas.cek_penilaian_kelas.status') }}" class="{{ request()->routeIs('walikelas.cek_penilaian_kelas.status') ? 'bg-red-800 text-white' : 'text-red-200 hover:text-white hover:bg-red-800' }} group w-full flex items-center pl-11 pr-2 py-2 text-sm font-medium">Status Penilaian</a>
                        <a href="{{ route('walikelas.cek_penilaian_kelas.statistik_rapor') }}" class="{{ request()->routeIs('walikelas.cek_penilaian_kelas.statistik_rapor') ? 'bg-red-800 text-white' : 'text-red-200 hover:text-white hover:bg-red-800' }} group w-full flex items-center pl-11 pr-2 py-2 text-sm font-medium">Statistik Nilai Rapor</a>
                        
                        @if($startYear < 2025)
                        <a href="{{ route('walikelas.cek_penilaian_kelas.statistik_p3') }}" class="{{ request()->routeIs('walikelas.cek_penilaian_kelas.statistik_p3') ? 'bg-red-800 text-white' : 'text-red-200 hover:text-white hover:bg-red-800' }} group w-full flex items-center pl-11 pr-2 py-2 text-sm font-medium">Statistik Nilai P3</a>
                        @endif
                    </div>
                </div>

                <!-- Dropdown Transkrip Ijazah -->
                <div x-data="{ open: {{ request()->routeIs('walikelas.transkrip_ijazah.*') ? 'true' : 'false' }} }" class="space-y-1">
                    <button @click="open = !open" type="button" class="{{ request()->routeIs('walikelas.transkrip_ijazah.*') ? 'bg-red-800 border-l-4 border-yellow-400 text-white' : 'border-l-4 border-transparent hover:bg-red-800 hover:text-white text-yellow-300 font-semibold' }} group w-full flex items-center justify-between px-3 py-2 text-sm transition-colors focus:outline-none">
                        <div class="flex items-center">
                            <svg class="mr-3 flex-shrink-0 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            Transkrip Ijazah
                        </div>
                        <svg :class="{'rotate-180': open, 'rotate-0': !open}" class="ml-2 h-4 w-4 transform transition-transform duration-200 text-yellow-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </button>
                    <div x-show="open" x-collapse style="display: none;" class="bg-red-900/50">
                        <a href="{{ route('walikelas.transkrip_ijazah.input_nilai') }}" class="{{ request()->routeIs('walikelas.transkrip_ijazah.input_nilai') ? 'bg-red-800 text-yellow-400' : 'text-yellow-200 hover:text-yellow-400 hover:bg-red-800' }} group w-full flex items-center pl-11 pr-2 py-2 text-sm font-medium">Input Nilai Transkrip</a>
                        <a href="{{ route('walikelas.transkrip_ijazah.import_nilai') }}" class="{{ request()->routeIs('walikelas.transkrip_ijazah.import_nilai') ? 'bg-red-800 text-yellow-400' : 'text-yellow-200 hover:text-yellow-400 hover:bg-red-800' }} group w-full flex items-center pl-11 pr-2 py-2 text-sm font-medium">Import Nilai Transkrip</a>
                        <a href="{{ route('walikelas.transkrip_ijazah.cetak') }}" class="{{ request()->routeIs('walikelas.transkrip_ijazah.cetak') ? 'bg-red-800 text-yellow-400' : 'text-yellow-200 hover:text-yellow-400 hover:bg-red-800' }} group w-full flex items-center pl-11 pr-2 py-2 text-sm font-medium">Cetak Transkrip Nilai</a>
                    </div>
                </div>

                <!-- Dropdown Cetak Nilai -->
                <div x-data="{ open: {{ request()->routeIs('walikelas.cetak_nilai.*') ? 'true' : 'false' }} }" class="space-y-1">
                    <button @click="open = !open" type="button" class="{{ request()->routeIs('walikelas.cetak_nilai.*') ? 'bg-red-800 border-l-4 border-yellow-400 text-white' : 'border-l-4 border-transparent hover:bg-red-800 hover:text-white text-yellow-300 font-semibold' }} group w-full flex items-center justify-between px-3 py-2 text-sm transition-colors focus:outline-none">
                        <div class="flex items-center">
                            <svg class="mr-3 flex-shrink-0 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                            Cetak Nilai
                        </div>
                        <svg :class="{'rotate-180': open, 'rotate-0': !open}" class="ml-2 h-4 w-4 transform transition-transform duration-200 text-yellow-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </button>
                    <div x-show="open" x-collapse style="display: none;" class="bg-red-900/50">
                        <a href="{{ route('walikelas.cetak_nilai.leger') }}" class="{{ request()->routeIs('walikelas.cetak_nilai.leger') ? 'bg-red-800 text-yellow-400' : 'text-yellow-200 hover:text-yellow-400 hover:bg-red-800' }} group w-full flex items-center pl-11 pr-2 py-2 text-sm font-medium">Leger Rapor</a>
                        <a href="{{ route('walikelas.cetak_nilai.pelengkap_index') }}" class="{{ request()->routeIs('walikelas.cetak_nilai.pelengkap_index') ? 'bg-red-800 text-yellow-400' : 'text-yellow-200 hover:text-yellow-400 hover:bg-red-800' }} group w-full flex items-center pl-11 pr-2 py-2 text-sm font-medium">Pelengkap Rapor</a>
                        <a href="{{ route('walikelas.cetak_nilai.rapor_index') }}" class="{{ request()->routeIs('walikelas.cetak_nilai.rapor_index') ? 'bg-red-800 text-yellow-400' : 'text-yellow-200 hover:text-yellow-400 hover:bg-red-800' }} group w-full flex items-center pl-11 pr-2 py-2 text-sm font-medium">Nilai Rapor</a>
                        
                        @if($startYear < 2025)
                        <a href="{{ route('walikelas.cetak_nilai.rapor_p5_index') }}" class="{{ request()->routeIs('walikelas.cetak_nilai.rapor_p5_index') ? 'bg-red-800 text-yellow-400' : 'text-yellow-200 hover:text-yellow-400 hover:bg-red-800' }} group w-full flex items-center pl-11 pr-2 py-2 text-sm font-medium">Rapor P5</a>
                        @endif
                    </div>
                </div>
            @endif

            @if (Auth::user()->role === 'siswa')
                <a href="{{ route('siswa.dashboard') }}" class="{{ request()->routeIs('siswa.dashboard') ? 'bg-red-800 border-l-4 border-white text-white' : 'border-l-4 border-transparent hover:bg-red-800 hover:text-white text-red-100' }} group flex items-center px-3 py-2 text-sm font-medium transition-colors">
                    <svg class="mr-3 flex-shrink-0 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    Dashboard Siswa
                </a>

                <!-- Dropdown Rekap Capaian -->
                <div x-data="{ open: {{ request()->routeIs('siswa.rekap_nilai', 'siswa.rekap_deskripsi') ? 'true' : 'false' }} }" class="space-y-1">
                    <button @click="open = !open" type="button" class="{{ request()->routeIs('siswa.rekap_nilai', 'siswa.rekap_deskripsi') ? 'bg-red-800 border-l-4 border-yellow-400 text-yellow-400' : 'border-l-4 border-transparent hover:bg-red-800 hover:text-yellow-400 text-red-100' }} group w-full flex items-center justify-between px-3 py-2 text-sm font-medium transition-colors focus:outline-none">
                        <div class="flex items-center">
                            <svg class="mr-3 flex-shrink-0 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222"></path></svg>
                            Rekap Capaian
                        </div>
                        <svg :class="{'rotate-180': open, 'rotate-0': !open}" class="ml-2 h-4 w-4 transform transition-transform duration-200 text-red-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </button>
                    <div x-show="open" x-collapse style="display: none;" class="bg-red-900/50">
                        <a href="{{ route('siswa.rekap_nilai') }}" class="{{ request()->routeIs('siswa.rekap_nilai') ? 'bg-red-800 text-yellow-400' : 'text-yellow-200 hover:text-yellow-400 hover:bg-red-800' }} group w-full flex items-center pl-11 pr-2 py-2 text-sm font-medium">Nilai Rapor</a>
                        <a href="{{ route('siswa.rekap_deskripsi') }}" class="{{ request()->routeIs('siswa.rekap_deskripsi') ? 'bg-red-800 text-yellow-400' : 'text-yellow-200 hover:text-yellow-400 hover:bg-red-800' }} group w-full flex items-center pl-11 pr-2 py-2 text-sm font-medium">Deskripsi Rapor</a>
                    </div>
                </div>

                <!-- Download Rapor -->
                <a href="{{ route('siswa.download_rapor') }}" class="{{ request()->routeIs('siswa.download_rapor') ? 'bg-red-800 border-l-4 border-yellow-400 text-yellow-400' : 'border-l-4 border-transparent hover:bg-red-800 hover:text-yellow-400 text-red-100' }} group flex items-center px-3 py-2 text-sm font-medium transition-colors">
                    <svg class="mr-3 flex-shrink-0 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    Download Rapor
                </a>
            @endif
        </nav>
    </div>

    <!-- Footer Sidebar / Keluar -->
    <div class="p-4 border-t border-red-900/50">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="w-full flex items-center px-4 py-2 text-sm font-medium text-red-100 hover:bg-red-800 hover:text-white rounded-md transition-colors">
                <svg class="mr-3 flex-shrink-0 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                </svg>
                Keluar
            </button>
        </form>
    </div>
</aside>

<!-- Mobile Overlay -->
<div id="mobile-overlay" class="fixed inset-0 bg-gray-600 bg-opacity-75 z-20 hidden md:hidden" onclick="document.getElementById('sidebar').classList.add('hidden'); document.getElementById('sidebar').classList.remove('absolute', 'z-30'); this.classList.add('hidden');"></div>
