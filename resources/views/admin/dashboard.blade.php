<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            Aplikasi e-Rapor | {{ $semester_teks ?? '2025/2026 Ganjil' }}
        </h2>
    </x-slot>

    <div class="py-6 space-y-6 max-w-7xl mx-auto sm:px-6 lg:px-8">
        
        <!-- Welcome & Status Banners -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 bg-[#8B1515] rounded-md text-white p-4 flex items-start gap-4 shadow-md">
                <div class="mt-1">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <div>
                    <h3 class="font-bold text-lg border-b border-red-500/50 pb-1 mb-1">Selamat Datang di Halaman Admin, Aplikasi e-Rapor SD</h3>
                    <p class="text-sm text-red-100">Anda sedang Login Sebagai Admin pada {{ $sekolah->nama_sekolah ?? 'SD Contoh Rapor Dapo' }}, Semester {{ $semester_teks ?? '2025/2026 Ganjil' }}</p>
                </div>
            </div>
            
            <div class="bg-[#8B1515] rounded-md text-white p-4 flex flex-col justify-center items-start gap-2 shadow-md">
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span class="font-semibold text-sm">Input Nilai Oleh Guru dan Wali {{ $semester_aktif && $semester_aktif->status_input_nilai ? 'dibuka' : 'ditutup' }}</span>
                </div>
                <form action="{{ route('admin.toggle_input_nilai') }}" method="POST">
                    @csrf
                    @if($semester_aktif && $semester_aktif->status_input_nilai)
                    <button type="submit" class="bg-red-600 hover:bg-red-500 text-white text-xs font-bold py-1.5 px-3 rounded flex items-center gap-1 border border-red-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path></svg> Tutup Status Input Nilai
                    </button>
                    @else
                    <button type="submit" class="bg-green-600 hover:bg-green-500 text-white text-xs font-bold py-1.5 px-3 rounded flex items-center gap-1 border border-green-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Buka Status Input Nilai
                    </button>
                    @endif
                </form>
            </div>
        </div>

        <!-- Main Dashboard Content Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            <!-- Rekap Data (Left Column) -->
            <div class="lg:col-span-2">
                <div class="bg-[#8B1515] text-white px-4 py-2 rounded-t-md font-bold text-sm uppercase tracking-wider">
                    Rekap Data
                </div>
                <div class="bg-white p-4 rounded-b-md shadow-md grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                    
                    <!-- Pengguna -->
                    <div class="bg-gradient-to-r from-purple-600 to-purple-400 rounded-md p-4 text-white flex justify-between items-center shadow-sm">
                        <div>
                            <div class="font-bold text-sm">Pengguna</div>
                            <div class="text-3xl font-extrabold mt-1">{{ $counts['pengguna'] ?? 0 }}</div>
                        </div>
                        <svg class="w-10 h-10 text-white/50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    </div>

                    <!-- Online -->
                    <div class="bg-gradient-to-r from-teal-500 to-teal-400 rounded-md p-4 text-white flex justify-between items-center shadow-sm">
                        <div>
                            <div class="font-bold text-sm">Online</div>
                            <div class="text-3xl font-extrabold mt-1">{{ $counts['online'] ?? 0 }}</div>
                        </div>
                        <svg class="w-10 h-10 text-white/50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    </div>

                    <!-- Guru & KS -->
                    <div class="bg-gradient-to-r from-green-500 to-green-400 rounded-md p-4 text-white flex justify-between items-center shadow-sm">
                        <div>
                            <div class="font-bold text-sm">Guru & KS</div>
                            <div class="text-3xl font-extrabold mt-1">{{ $counts['guru'] ?? 0 }}</div>
                        </div>
                        <svg class="w-10 h-10 text-white/50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    </div>

                    <!-- Siswa -->
                    <div class="bg-gradient-to-r from-blue-500 to-blue-400 rounded-md p-4 text-white flex justify-between items-center shadow-sm">
                        <div>
                            <div class="font-bold text-sm">Siswa</div>
                            <div class="text-3xl font-extrabold mt-1">{{ $counts['siswa'] ?? 0 }}</div>
                        </div>
                        <svg class="w-10 h-10 text-white/50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"></path></svg>
                    </div>

                    <!-- Rombel -->
                    <div class="bg-gradient-to-r from-orange-500 to-orange-400 rounded-md p-4 text-white flex justify-between items-center shadow-sm">
                        <div>
                            <div class="font-bold text-sm">Rombel</div>
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

            <!-- Panduan (Right Column) -->
            <div class="lg:col-span-1">
                <div class="bg-[#8B1515] text-white px-4 py-2 rounded-t-md font-bold text-sm uppercase tracking-wider">
                    Link Form Pendataan dan Panduan Aplikasi
                </div>
                <div class="bg-white p-4 rounded-b-md shadow-md space-y-4">
                    <a href="#" class="block bg-gradient-to-r from-red-500 to-red-400 rounded-md p-4 text-white shadow-sm hover:shadow-md transition-shadow">
                        <div class="flex justify-between items-center">
                            <span class="font-semibold text-sm">Form Pendataan Pengguna e-Rapor SD</span>
                            <div class="w-6 h-6 rounded-full border-2 border-white flex items-center justify-center">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            </div>
                        </div>
                    </a>

                    <a href="#" class="block bg-gray-800 rounded-md p-4 text-white shadow-sm hover:bg-gray-700 transition-colors">
                        <div class="flex justify-between items-center">
                            <span class="font-semibold text-sm">Panduan Aplikasi e-Rapor SD</span>
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        </div>
                    </a>
                </div>
            </div>
        </div>

        <!-- Status Kerja Administrator -->
        <div class="mt-2">
            <div class="bg-[#8B1515] text-white px-4 py-2 rounded-t-md font-bold text-sm uppercase tracking-wider">
                Status Kerja Administrator
            </div>
            <div class="bg-white rounded-b-md shadow-md overflow-hidden">
                <div class="p-4 text-sm text-gray-700 font-semibold mb-2">
                    Rincian Kerja Utama Administrator :
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-700">
                        <thead class="text-xs text-white bg-[#8B1515] uppercase border-b border-red-800">
                            <tr>
                                <th scope="col" class="px-6 py-3 w-16">No</th>
                                <th scope="col" class="px-6 py-3">Jenis Kegiatan Administrator</th>
                                <th scope="col" class="px-6 py-3 w-48 text-center">Status Pekerjaan</th>
                                <th scope="col" class="px-6 py-3 w-48 text-center">Progress</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(isset($progress) && count($progress) > 0)
                                @foreach($progress as $index => $item)
                                <tr class="{{ $index % 2 == 0 ? 'bg-red-50 border-b border-red-100' : 'bg-white border-b border-gray-100' }}">
                                    <td class="px-6 py-3 font-semibold">{{ $index + 1 }}</td>
                                    <td class="px-6 py-3 text-red-900 font-medium">{{ $item['nama'] }}</td>
                                    <td class="px-6 py-3">
                                        <div class="bg-{{ $item['warna'] }}-600 text-white text-xs py-1 px-2 rounded-sm text-center">{{ $item['status'] }}</div>
                                    </td>
                                    <td class="px-6 py-3">
                                        <div class="w-full bg-gray-200 rounded-full h-4 relative">
                                            <div class="bg-teal-600 h-4 rounded-full" style="width: {{ $item['persen'] }}%"></div>
                                            <span class="absolute inset-0 flex items-center justify-center text-[10px] font-bold {{ $item['persen'] >= 50 ? 'text-white text-shadow-sm' : 'text-gray-700' }}">{{ number_format($item['persen'], 2) }}%</span>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="4" class="px-6 py-4 text-center text-gray-500">Data progress tidak tersedia.</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Chart Analitik Nilai -->
        <div class="mt-6 bg-white rounded-md shadow-md">
            <div class="bg-[#8B1515] text-white px-4 py-2 rounded-t-md font-bold text-sm uppercase tracking-wider">
                Grafik Rata-rata Nilai per Mata Pelajaran
            </div>
            <div class="p-6">
                <canvas id="nilaiChart" style="max-height: 400px;"></canvas>
            </div>
        </div>

    </div>

    <!-- Chart.js Script -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var ctx = document.getElementById('nilaiChart').getContext('2d');
            var labels = {!! $chart_nilai_labels ?? '[]' !!};
            var dataValues = {!! $chart_nilai_data ?? '[]' !!};

            var nilaiChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Rata-rata Nilai',
                        data: dataValues,
                        backgroundColor: 'rgba(75, 192, 192, 0.7)',
                        borderColor: 'rgba(75, 192, 192, 1)',
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
                            ticks: {
                                stepSize: 10
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: false
                        }
                    }
                }
            });
        });
    </script>
</x-app-layout>
