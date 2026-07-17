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
                        <p class="text-red-100 text-lg">Anda login sebagai Guru pada Semester {{ $semesterAktif ? $semesterAktif->nama_semester : 'Belum Ada Semester Aktif' }}.</p>
                    </div>
                    <div class="hidden md:block">
                        <svg class="w-24 h-24 text-white opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                    </div>
                </div>
            </div>

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
                        <a href="{{ asset('panduan.pdf') }}" target="_blank" class="block w-full text-left bg-gray-50 p-4 rounded-lg border border-gray-200 hover:border-indigo-300 hover:bg-indigo-50 hover:shadow-md transition-all duration-200 group">
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
