<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight uppercase">
            {{ __('Statistik Nilai Rapor Siswa') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-t-4 border-red-800">
                <div class="p-6 text-gray-900">
                    <div class="mb-6 p-4 border border-gray-200 rounded-md bg-white">
                        <div class="flex flex-col md:flex-row md:items-center gap-4">
                            <label class="w-full md:w-48 font-bold text-gray-700 text-sm">Pilih Kelas :</label>
                            <input type="text" value="{{ $rombel->tingkat . ' ' . strtoupper($rombel->nama_rombel) }}" class="flex-1 bg-gray-50 border border-blue-300 text-gray-900 text-sm rounded-md p-2 max-w-3xl" readonly>
                        </div>
                    </div>

                    <!-- Chart Container -->
                    <div class="mb-8 p-6 border border-gray-200 rounded-md bg-white shadow-sm">
                        <h3 class="text-lg font-bold text-gray-800 mb-4">Statistik Nilai Mata Pelajaran</h3>
                        <div class="relative w-full h-[400px]">
                            <canvas id="barChart"></canvas>
                        </div>
                    </div>

                    <!-- Table -->
                    <div class="overflow-x-auto border border-gray-300 rounded-t-md">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-red-900 text-white shadow">
                                <tr class="text-center text-xs uppercase">
                                    <th class="px-4 py-3 border border-red-800 w-12 align-middle" rowspan="2">No</th>
                                    <th class="px-4 py-3 border border-red-800 align-middle text-left" rowspan="2">Nama Mapel</th>
                                    <th class="px-4 py-3 border border-red-800 w-32 align-middle" rowspan="2">Rombel</th>
                                    <th class="px-4 py-3 border border-red-800 align-middle text-left" rowspan="2">Guru Mapel</th>
                                    <th class="px-4 py-2 border border-red-800 text-center" colspan="4">Statistik Nilai Rapor</th>
                                </tr>
                                <tr class="text-center bg-red-800 text-xs">
                                    <th class="px-2 py-2 border border-red-700 w-24">Jumlah Data</th>
                                    <th class="px-2 py-2 border border-red-700 w-24">Nilai Tertinggi</th>
                                    <th class="px-2 py-2 border border-red-700 w-24">Nilai Terendah</th>
                                    <th class="px-2 py-2 border border-red-700 w-24">Rata-Rata Nilai</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($statistik as $index => $item)
                                <tr class="hover:bg-red-50/50 transition-colors {{ $index % 2 == 0 ? 'bg-red-50/20' : 'bg-white' }}">
                                    <td class="px-4 py-3 text-sm text-center border border-gray-300">{{ $loop->iteration }}</td>
                                    <td class="px-4 py-3 text-sm border border-gray-300 font-medium">{{ $item['mapel']->nama_mapel }}</td>
                                    <td class="px-4 py-3 text-sm text-center border border-gray-300 uppercase">{{ $rombel->tingkat . ' ' . $rombel->nama_rombel }}</td>
                                    <td class="px-4 py-3 text-sm border border-gray-300">{{ $item['guru']->nama_lengkap ?? '-' }}</td>
                                    <td class="px-4 py-3 text-sm text-center border border-gray-300">{{ $item['jumlah_data'] }}</td>
                                    <td class="px-4 py-3 text-sm text-center border border-gray-300">{{ $item['tertinggi'] }}</td>
                                    <td class="px-4 py-3 text-sm text-center border border-gray-300">{{ $item['terendah'] }}</td>
                                    <td class="px-4 py-3 text-sm text-center border border-gray-300">{{ number_format($item['rata_rata'], 2) }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="px-6 py-8 whitespace-nowrap text-sm text-gray-500 text-center">
                                        Belum ada data mata pelajaran atau nilai.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>

        </div>
    </div>

    <!-- Chart.js Setup -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const ctx = document.getElementById('barChart').getContext('2d');
            
            const labels = @json($chart_labels);
            const dataTertinggi = @json($chart_tertinggi);
            const dataTerendah = @json($chart_terendah);
            const dataRataRata = @json($chart_rata_rata);

            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [
                        {
                            label: 'Tertinggi',
                            data: dataTertinggi,
                            backgroundColor: 'rgba(216, 114, 114, 1)', // Light Red
                            borderColor: 'rgba(216, 114, 114, 1)',
                            borderWidth: 1
                        },
                        {
                            label: 'Terendah',
                            data: dataTerendah,
                            backgroundColor: 'rgba(66, 133, 244, 1)', // Blue
                            borderColor: 'rgba(66, 133, 244, 1)',
                            borderWidth: 1
                        },
                        {
                            label: 'Rata-Rata',
                            data: dataRataRata,
                            backgroundColor: 'rgba(0, 0, 0, 0.8)', // Dark Grey/Black
                            borderColor: 'rgba(0, 0, 0, 0.8)',
                            borderWidth: 1,
                            hidden: true // Based on mockup, mostly highest and lowest are visible, but let's include it
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            max: 100
                        }
                    },
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                usePointStyle: true,
                                boxWidth: 8
                            }
                        }
                    }
                }
            });
        });
    </script>
</x-app-layout>
