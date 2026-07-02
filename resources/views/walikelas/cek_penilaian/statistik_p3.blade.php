<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight uppercase">
            {{ __('Statistik Profil Pelajar Pancasila') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-t-4 border-red-800">
                <div class="p-6 text-gray-900">
                    <div class="mb-6 p-4 border border-gray-200 rounded-md bg-white">
                        <div class="flex flex-col md:flex-row md:items-center gap-4">
                            <label class="w-full md:w-48 font-bold text-gray-700 text-sm">Pilih Kelas :</label>
                            <input type="text" value="{{ strtoupper($rombel->nama_rombel) }}" class="flex-1 bg-gray-50 border border-blue-300 text-gray-900 text-sm rounded-md p-2 max-w-3xl" readonly>
                        </div>
                    </div>

                    <!-- Charts Container -->
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                        <!-- Bar Chart -->
                        <div class="p-4 border border-gray-200 rounded-md bg-white shadow-sm">
                            <h3 class="text-lg font-bold text-gray-800 mb-1">Statistik Capaian Profil Pelajar pancasila</h3>
                            <h4 class="text-sm text-gray-600 mb-4 text-center font-semibold">Statistik Capaian Profil Pelajar Pancasila, Kelas: {{ $rombel->nama_rombel }}</h4>
                            <div class="relative w-full h-[300px]">
                                <canvas id="barChart"></canvas>
                            </div>
                        </div>

                        <!-- Radar Chart -->
                        <div class="p-4 border border-gray-200 rounded-md bg-white shadow-sm">
                            <h3 class="text-lg font-bold text-gray-800 mb-1">Statistik Capaian Profil Pelajar pancasila</h3>
                            <h4 class="text-sm text-gray-600 mb-4 font-semibold">Perkembangan Dimensi P3</h4>
                            <div class="relative w-full h-[300px]">
                                <canvas id="radarChart"></canvas>
                            </div>
                        </div>
                    </div>

                    <!-- Export Buttons -->
                    <div class="flex items-center gap-2 mb-4">
                        <button class="bg-red-800 hover:bg-red-900 text-white font-bold py-1.5 px-3 rounded text-sm transition-colors">Excel</button>
                        <button class="bg-red-800 hover:bg-red-900 text-white font-bold py-1.5 px-3 rounded text-sm transition-colors">Pdf</button>
                        <button class="bg-red-800 hover:bg-red-900 text-white font-bold py-1.5 px-3 rounded text-sm transition-colors">Print</button>
                    </div>

                    <!-- Table -->
                    <div class="overflow-x-auto border border-gray-300 rounded-t-md">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-red-900 text-white shadow">
                                <tr class="text-center text-xs uppercase">
                                    <th class="px-4 py-3 border border-red-800 w-12 align-middle" rowspan="2">No</th>
                                    <th class="px-4 py-3 border border-red-800 align-middle text-left" rowspan="2">Dimensi</th>
                                    <th class="px-4 py-2 border border-red-800 text-center" colspan="5">Statistik Capaian P3</th>
                                </tr>
                                <tr class="text-center bg-red-800 text-xs">
                                    <th class="px-2 py-2 border border-red-700 w-24">Jumlah Data</th>
                                    <th class="px-2 py-2 border border-red-700 w-24">Jml Mulai Berkembang</th>
                                    <th class="px-2 py-2 border border-red-700 w-24">Jml Sedang Berkembang</th>
                                    <th class="px-2 py-2 border border-red-700 w-32">Jml Berkembang Sesuai Harapan</th>
                                    <th class="px-2 py-2 border border-red-700 w-24">Jml Sangat Berkembang</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($statistik as $index => $item)
                                <tr class="hover:bg-red-50/50 transition-colors {{ $index % 2 == 0 ? 'bg-red-50/20' : 'bg-white' }}">
                                    <td class="px-4 py-3 text-sm text-center border border-gray-300">{{ $loop->iteration }}</td>
                                    <td class="px-4 py-3 text-sm border border-gray-300 font-medium">{{ $item['dimensi']->nama_dimensi }}</td>
                                    <td class="px-4 py-3 text-sm text-center border border-gray-300">{{ $item['jumlah_data'] }}</td>
                                    <td class="px-4 py-3 text-sm text-center border border-gray-300">{{ $item['pct_mb'] }}%</td>
                                    <td class="px-4 py-3 text-sm text-center border border-gray-300">{{ $item['pct_sb'] }}%</td>
                                    <td class="px-4 py-3 text-sm text-center border border-gray-300">{{ $item['pct_bsh'] }}%</td>
                                    <td class="px-4 py-3 text-sm text-center border border-gray-300">{{ $item['pct_sangatb'] }}%</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-8 whitespace-nowrap text-sm text-gray-500 text-center">
                                        Belum ada data nilai P3.
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
            const labels = @json($chart_labels);
            const dataMB = @json($chart_data_mb);
            const dataSB = @json($chart_data_sb);
            const dataBSH = @json($chart_data_bsh);
            const dataSangatB = @json($chart_data_sangatb);
            const dataRadar = @json($radar_data);

            // Bar Chart
            const ctxBar = document.getElementById('barChart').getContext('2d');
            new Chart(ctxBar, {
                type: 'bar',
                data: {
                    labels: labels.map(l => {
                        // Shorten labels for chart to match mockup if they are too long
                        if(l.includes('Beriman')) return 'Beriman & Akhlak Mulia';
                        if(l.includes('Berkebinekaan')) return 'Berkebinekaan';
                        if(l.includes('Bergotong')) return 'Gt. Royong';
                        if(l.includes('Mandiri')) return 'Mandiri';
                        if(l.includes('Bernalar')) return 'Bnlr. Kritis';
                        if(l.includes('Kreatif')) return 'Kreatif';
                        return l;
                    }),
                    datasets: [
                        {
                            label: 'Mulai Berkembang',
                            data: dataMB,
                            backgroundColor: 'rgba(239, 68, 68, 1)' // Red
                        },
                        {
                            label: 'Sedang Berkembang',
                            data: dataSB,
                            backgroundColor: 'rgba(250, 204, 21, 1)' // Yellow
                        },
                        {
                            label: 'Berkembang Sesuai Harapan',
                            data: dataBSH,
                            backgroundColor: 'rgba(59, 130, 246, 1)' // Blue
                        },
                        {
                            label: 'Sangat Berkembang',
                            data: dataSangatB,
                            backgroundColor: 'rgba(34, 197, 94, 1)' // Green
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            max: 100,
                            title: {
                                display: true,
                                text: 'Persentase Capaian P3(%)'
                            }
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

            // Radar Chart
            const ctxRadar = document.getElementById('radarChart').getContext('2d');
            new Chart(ctxRadar, {
                type: 'radar',
                data: {
                    labels: labels.map(l => {
                        if(l.includes('Beriman')) return 'Beriman & Akhlak Mulia';
                        if(l.includes('Berkebinekaan')) return 'Berkebinekaan';
                        if(l.includes('Bergotong')) return 'Gt. Royong';
                        if(l.includes('Mandiri')) return 'Mandiri';
                        if(l.includes('Bernalar')) return 'Bnlr. Kritis';
                        if(l.includes('Kreatif')) return 'Kreatif';
                        return l;
                    }),
                    datasets: [{
                        label: 'Skor Rata-Rata',
                        data: dataRadar,
                        backgroundColor: 'rgba(59, 130, 246, 0.2)', // Light Blue Fill
                        borderColor: 'rgba(59, 130, 246, 1)', // Blue stroke
                        pointBackgroundColor: 'rgba(59, 130, 246, 1)',
                        pointBorderColor: '#fff',
                        pointHoverBackgroundColor: '#fff',
                        pointHoverBorderColor: 'rgba(59, 130, 246, 1)'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        r: {
                            angleLines: {
                                display: true
                            },
                            suggestedMin: 0,
                            suggestedMax: 4,
                            ticks: {
                                stepSize: 1
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: false // Radar chart in mockup doesn't seem to have a legend
                        }
                    }
                }
            });
        });
    </script>
</x-app-layout>
