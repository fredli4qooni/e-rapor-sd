<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Grafik Perkembangan Nilai Rapor') }}
        </h2>
    </x-slot>

    <div class="py-6 space-y-6 max-w-7xl mx-auto sm:px-6 lg:px-8">
        
        <!-- Filter -->
        <div class="bg-white p-4 rounded-md shadow flex flex-col md:flex-row gap-4 items-end">
            <form action="{{ route('admin.perkembangan_nilai.grafik') }}" method="GET" class="flex flex-col md:flex-row gap-4 w-full items-end" id="filterForm">
                <div class="w-full md:w-1/3">
                    <label for="rombel_id" class="block font-semibold text-gray-700 text-sm mb-1">Pilih Kelas:</label>
                    <select name="rombel_id" id="rombel_id" class="w-full border-gray-300 rounded-md shadow-sm focus:border-red-500 focus:ring-red-500 text-sm" onchange="if(document.getElementById('siswa_id')) { document.getElementById('siswa_id').value='all'; } this.form.submit()">
                        <option value="">-- Pilih Kelas --</option>
                        @foreach($rombels as $rombel)
                            <option value="{{ $rombel->id }}" {{ request('rombel_id') == $rombel->id ? 'selected' : '' }}>{{ $rombel->nama_rombel }}</option>
                        @endforeach
                    </select>
                </div>

                @if($selectedRombelId)
                <div class="w-full md:w-1/3">
                    <label for="siswa_id" class="block font-semibold text-gray-700 text-sm mb-1">Pilih Siswa:</label>
                    <select name="siswa_id" id="siswa_id" class="w-full border-gray-300 rounded-md shadow-sm focus:border-red-500 focus:ring-red-500 text-sm" onchange="this.form.submit()">
                        <option value="all" {{ request('siswa_id') == 'all' ? 'selected' : '' }}>-- Semua Siswa di Kelas Ini --</option>
                        @foreach($siswas as $siswa)
                            <option value="{{ $siswa->id }}" {{ request('siswa_id') == $siswa->id ? 'selected' : '' }}>{{ $siswa->nama_lengkap }}</option>
                        @endforeach
                    </select>
                </div>
                @endif
            </form>
        </div>

        @if($selectedRombelId)
        
        <div class="bg-white p-4 rounded-md shadow border-l-4 border-blue-500 mb-6">
            <p class="text-gray-700">
                Menampilkan data perkembangan nilai untuk: 
                <strong>{{ $selectedSiswaId && $selectedSiswaId !== 'all' ? $siswas->where('id', $selectedSiswaId)->first()->nama_lengkap ?? 'Siswa' : 'Semua Siswa (Rata-Rata Kelas)' }}</strong>
            </p>
        </div>

        <div class="grid grid-cols-1 gap-6">
            <!-- Grafik -->
            <div class="bg-white rounded-md shadow-md overflow-hidden">
                <div class="bg-[#8B1515] text-white px-6 py-4 font-bold tracking-wider uppercase text-sm border-b border-red-800">
                    Grafik Tren Rata-Rata Nilai Antar Semester
                </div>
                <div class="p-6 relative h-[350px]">
                    <canvas id="trenChart"></canvas>
                </div>
            </div>

            <!-- Tabel Statistik -->
            <div class="bg-white rounded-md shadow-md overflow-hidden">
                <div class="bg-gray-800 text-white px-6 py-4 font-bold tracking-wider uppercase text-sm border-b border-gray-900">
                    Tabel Rata-Rata Nilai per Mata Pelajaran
                </div>
                <div class="p-0 overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-700">
                        <thead class="text-xs text-gray-700 bg-gray-100 uppercase border-b">
                            <tr>
                                <th class="px-4 py-3 w-10 text-center border-r">No</th>
                                <th class="px-4 py-3 border-r">Mata Pelajaran</th>
                                @foreach($semesters as $semName)
                                    <th class="px-4 py-3 text-center border-r">{{ $semName }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @php $no = 1; @endphp
                            @forelse($tabelData as $mapel => $nilaiPerSemester)
                                <tr class="border-b hover:bg-gray-50">
                                    <td class="px-4 py-3 text-center font-bold border-r">{{ $no++ }}</td>
                                    <td class="px-4 py-3 font-semibold border-r">{{ $mapel }}</td>
                                    @foreach($semesters as $semId => $semName)
                                        <td class="px-4 py-3 text-center border-r {{ isset($nilaiPerSemester[$semId]) && $nilaiPerSemester[$semId] < 70 ? 'text-red-600 font-bold' : '' }}">
                                            {{ $nilaiPerSemester[$semId] ?? '-' }}
                                        </td>
                                    @endforeach
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="{{ count($semesters) + 2 }}" class="px-4 py-3 text-center text-gray-500 py-8">
                                        Belum ada data nilai.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Initialize Chart.js -->
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const ctx = document.getElementById('trenChart').getContext('2d');
                const chartData = @json($grafikData);
                
                if (chartData.labels && chartData.labels.length > 0) {
                    new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: chartData.labels,
                            datasets: [{
                                label: 'Rata-Rata Nilai Rapor',
                                data: chartData.data,
                                backgroundColor: 'rgba(220, 38, 38, 0.2)', // Red-600 with opacity
                                borderColor: 'rgb(220, 38, 38)', // Red-600
                                borderWidth: 3,
                                pointBackgroundColor: 'rgb(185, 28, 28)', // Red-700
                                pointRadius: 5,
                                pointHoverRadius: 8,
                                fill: true,
                                tension: 0.3 // makes line curvy
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            scales: {
                                y: {
                                    beginAtZero: false,
                                    suggestedMin: 50, // Usually grades don't drop to 0
                                    max: 100
                                }
                            },
                            plugins: {
                                tooltip: {
                                    callbacks: {
                                        label: function(context) {
                                            return 'Rata-rata: ' + context.parsed.y;
                                        }
                                    }
                                }
                            }
                        }
                    });
                }
            });
        </script>
        @else
        <div class="bg-white p-8 rounded-md shadow text-center text-gray-500 border border-gray-200">
            <svg class="mx-auto h-12 w-12 text-gray-400 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z" />
            </svg>
            <p class="text-lg font-medium text-gray-900">Pilih Kelas</p>
            <p class="mt-1 text-sm text-gray-500">Silakan pilih kelas terlebih dahulu untuk melihat grafik perkembangan nilai.</p>
        </div>
        @endif

    </div>
</x-app-layout>
