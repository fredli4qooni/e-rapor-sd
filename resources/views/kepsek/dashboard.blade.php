<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            Dashboard Kepala Sekolah | {{ $semester_teks ?? '2025/2026 Ganjil' }}
        </h2>
    </x-slot>

    <div class="py-6 space-y-6 max-w-7xl mx-auto sm:px-6 lg:px-8">
        
        <!-- Welcome Banners -->
        <div class="grid grid-cols-1 gap-6">
            <div class="bg-[#8B1515] rounded-md text-white p-4 flex items-start gap-4 shadow-md">
                <div class="mt-1">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <div>
                    <h3 class="font-bold text-lg border-b border-red-500/50 pb-1 mb-1">Selamat Datang di Halaman Kepala Sekolah, Aplikasi e-Rapor SD</h3>
                    <p class="text-sm text-red-100">Anda sedang Login Sebagai Kepala Sekolah pada {{ $sekolah->nama_sekolah ?? 'SD Contoh Rapor Dapo' }}, Semester {{ $semester_teks ?? '2025/2026 Ganjil' }}</p>
                </div>
            </div>
        </div>

        <!-- Main Dashboard Content Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            <!-- Rekap Data (Left Column) -->
            <div class="lg:col-span-3">
                <div class="bg-[#8B1515] text-white px-4 py-2 rounded-t-md font-bold text-sm uppercase tracking-wider">
                    Rekap Data Sekolah
                </div>
                <div class="bg-white p-4 rounded-b-md shadow-md grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
                    
                    <!-- Guru & KS -->
                    <div class="bg-gradient-to-r from-green-500 to-green-400 rounded-md p-4 text-white flex justify-between items-center shadow-sm">
                        <div>
                            <div class="font-bold text-sm">Total Guru</div>
                            <div class="text-3xl font-extrabold mt-1">{{ $counts['guru'] ?? 0 }}</div>
                        </div>
                        <svg class="w-10 h-10 text-white/50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    </div>

                    <!-- Siswa -->
                    <div class="bg-gradient-to-r from-blue-500 to-blue-400 rounded-md p-4 text-white flex justify-between items-center shadow-sm">
                        <div>
                            <div class="font-bold text-sm">Total Siswa</div>
                            <div class="text-3xl font-extrabold mt-1">{{ $counts['siswa'] ?? 0 }}</div>
                        </div>
                        <svg class="w-10 h-10 text-white/50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"></path></svg>
                    </div>

                    <!-- Rombel -->
                    <div class="bg-gradient-to-r from-orange-500 to-orange-400 rounded-md p-4 text-white flex justify-between items-center shadow-sm">
                        <div>
                            <div class="font-bold text-sm">Rombel Aktif</div>
                            <div class="text-3xl font-extrabold mt-1">{{ $counts['rombel'] ?? 0 }}</div>
                        </div>
                        <svg class="w-10 h-10 text-white/50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                    </div>

                    <!-- Pembelajaran -->
                    <div class="bg-gradient-to-r from-pink-500 to-pink-400 rounded-md p-4 text-white flex justify-between items-center shadow-sm">
                        <div>
                            <div class="font-bold text-sm">Pembelajaran</div>
                            <div class="text-3xl font-extrabold mt-1">{{ $counts['pembelajaran'] ?? 0 }}</div>
                        </div>
                        <svg class="w-10 h-10 text-white/50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                    </div>

                </div>
            </div>
        </div>

    </div>
</x-app-layout>
