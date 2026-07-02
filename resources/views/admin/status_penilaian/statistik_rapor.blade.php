<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Statistik Nilai Rapor') }}
        </h2>
    </x-slot>

    <div class="py-6 space-y-6 max-w-7xl mx-auto sm:px-6 lg:px-8">
        
        <!-- Filter -->
        <div class="bg-white p-4 rounded-md shadow flex justify-between items-center">
            <form action="{{ route('admin.status_penilaian.statistik_rapor') }}" method="GET" class="flex items-center space-x-2">
                <label for="rombel_id" class="font-semibold text-gray-700 text-sm">Pilih Kelas:</label>
                <select name="rombel_id" id="rombel_id" class="border-gray-300 rounded-md shadow-sm focus:border-red-500 focus:ring-red-500 text-sm" onchange="this.form.submit()">
                    <option value="">-- Pilih Kelas --</option>
                    @foreach($rombels as $rombel)
                        <option value="{{ $rombel->id }}" {{ request('rombel_id') == $rombel->id ? 'selected' : '' }}>{{ $rombel->nama_rombel }}</option>
                    @endforeach
                </select>
            </form>
        </div>

        @if($selectedRombelId)
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Grafik -->
            <div class="bg-white rounded-md shadow-md overflow-hidden">
                <div class="bg-[#8B1515] text-white px-6 py-4 font-bold tracking-wider uppercase text-sm border-b border-red-800">
                    Grafik Rata-Rata Nilai Rapor
                </div>
                <div class="p-6">
                    <canvas id="raporChart" height="250"></canvas>
                </div>
            </div>

            <!-- Tabel Statistik -->
            <div class="bg-white rounded-md shadow-md overflow-hidden">
                <div class="bg-gray-800 text-white px-6 py-4 font-bold tracking-wider uppercase text-sm border-b border-gray-900">
                    Rekapitulasi Statistik
                </div>
                <div class="p-0 overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-700">
                        <thead class="text-xs text-gray-700 bg-gray-100 uppercase border-b">
                            <tr>
                                <th class="px-4 py-3">Mata Pelajaran</th>
                                <th class="px-4 py-3 text-center">Rata-Rata</th>
                                <th class="px-4 py-3 text-center">Tertinggi</th>
                                <th class="px-4 py-3 text-center">Terendah</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($statistik as $stat)
                                <tr class="border-b hover:bg-gray-50">
                                    <td class="px-4 py-3 font-semibold">{{ $stat->nama_mapel }}</td>
                                    <td class="px-4 py-3 text-center text-blue-600 font-bold">{{ $stat->rata_rata }}</td>
                                    <td class="px-4 py-3 text-center text-green-600 font-bold">{{ $stat->tertinggi }}</td>
                                    <td class="px-4 py-3 text-center text-red-600 font-bold">{{ $stat->terendah }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-4 py-3 text-center text-gray-500">Belum ada data nilai.</td>
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
                const ctx = document.getElementById('raporChart').getContext('2d');
                const chartData = @json($chartData);
                
                if (chartData.labels && chartData.labels.length > 0) {
                    new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: chartData.labels,
                            datasets: [{
                                label: 'Rata-Rata Nilai',
                                data: chartData.data,
                                backgroundColor: 'rgba(59, 130, 246, 0.5)',
                                borderColor: 'rgb(59, 130, 246)',
                                borderWidth: 1
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    max: 100
                                }
                            }
                        }
                    });
                }
            });
        </script>
        @else
        <div class="bg-white p-6 rounded-md shadow text-center text-gray-500">
            Silakan pilih kelas terlebih dahulu untuk melihat statistik nilai.
        </div>
        @endif

    </div>
</x-app-layout>
