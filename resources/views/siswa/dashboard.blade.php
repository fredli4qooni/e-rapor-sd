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
                <div class="bg-white text-[#8b0000] rounded-md p-2 mr-4 font-bold text-xl">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </div>
                <div>
                    <h3 class="font-bold text-lg">Selamat Datang di Halaman Siswa, Aplikasi e-Rapor SD</h3>
                    <p class="text-sm">Anda sedang Login Sebagai Siswa pada {{ $sekolah->nama_sekolah ?? 'Sekolah' }}, Semester {{ $semesterAktif ? $semesterAktif->tahun_ajaran . ' ' . $semesterAktif->semester : '-' }}</p>
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
                        <a href="#" class="bg-blue-600 hover:bg-blue-700 text-white rounded-md p-4 flex justify-between items-center shadow-md transition-colors w-full">
                            <div>
                                <p class="text-sm">Panduan penggunaan aplikasi e-Rapor SD</p>
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
