<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight uppercase">
            {{ __('Grafik Nilai Rapor') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Filter Section Card -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-t-4 border-red-800 mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-bold text-gray-800 uppercase mb-4 border-b pb-2">PERKEMBANGAN NILAI RAPOR SISWA</h3>
                    <form method="GET" action="{{ route('guru.cek_penilaian.grafik') }}" class="space-y-4">
                        <div class="flex items-center gap-4">
                            <label class="w-1/4 text-sm font-bold text-gray-700 whitespace-nowrap">Pilih Kelas</label>
                            <select name="rombel_id" onchange="this.form.submit()" class="block w-3/4 rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm bg-gray-50">
                                <option value="">Pilih Data Kelas</option>
                                @foreach($rombels as $r)
                                    <option value="{{ $r->id }}" {{ $rombel_id == $r->id ? 'selected' : '' }}>
                                        {{ $r->nama_rombel }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="flex items-center gap-4">
                            <label class="w-1/4 text-sm font-bold text-gray-700 whitespace-nowrap">Pilih Mapel</label>
                            <select name="mapel_id" onchange="this.form.submit()" class="block w-3/4 rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm bg-gray-50" {{ !$rombel_id ? 'disabled' : '' }}>
                                <option value="">Pilih Data Mapel</option>
                                @foreach($mapels as $m)
                                    <option value="{{ $m->id }}" {{ $mapel_id == $m->id ? 'selected' : '' }}>
                                        {{ $m->nama_mapel }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="flex items-center gap-4">
                            <label class="w-1/4 text-sm font-bold text-gray-700 whitespace-nowrap">Pilih Siswa</label>
                            <select name="siswa_id" onchange="this.form.submit()" class="block w-3/4 rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm bg-gray-50" {{ !$mapel_id ? 'disabled' : '' }}>
                                <option value="">Pilih Siswa</option>
                                <option value="all" {{ $siswa_id_req === 'all' ? 'selected' : '' }}>SEMUA SISWA</option>
                                @foreach($siswas as $s)
                                    <option value="{{ $s->id }}" {{ $siswa_id_req == $s->id ? 'selected' : '' }}>
                                        {{ $s->nama_lengkap }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </form>
                </div>
            </div>

            @if($rombel_id && $mapel_id && $siswa_id_req)
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-6 border-b pb-2">{{ $sub_title }}</h3>
                    
                    <!-- Grafik Container -->
                    <div class="relative h-96 w-full mb-8">
                        <canvas id="grafikNilaiCanvas"></canvas>
                    </div>

                    <!-- Export Options Placeholder (Visual only to match design) -->
                    <div class="flex items-center justify-between mb-4 mt-8">
                        <div class="flex gap-1">
                            <button class="bg-red-600 hover:bg-red-700 text-white text-xs font-bold py-1 px-3 rounded shadow">Excel</button>
                            <button class="bg-red-600 hover:bg-red-700 text-white text-xs font-bold py-1 px-3 rounded shadow">Pdf</button>
                            <button class="bg-red-600 hover:bg-red-700 text-white text-xs font-bold py-1 px-3 rounded shadow">Print</button>
                        </div>
                    </div>

                    <div class="overflow-x-auto border rounded border-red-800">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-red-900 text-white">
                                <tr>
                                    <th rowspan="2" class="px-4 py-3 text-center text-xs font-bold tracking-wider w-16 border-r border-red-800">No</th>
                                    <th rowspan="2" class="px-4 py-3 text-left text-xs font-bold tracking-wider border-r border-red-800">Nama Mapel</th>
                                    <th colspan="12" class="px-4 py-2 text-center text-xs font-bold tracking-wider border-b border-r border-red-800">Statistik Nilai Rapor</th>
                                    <th rowspan="2" class="px-4 py-3 text-center text-xs font-bold tracking-wider">Rata-Rata</th>
                                </tr>
                                <tr class="bg-red-800 text-white">
                                    @for($i = 1; $i <= 12; $i++)
                                        <th class="px-2 py-2 text-center text-xs font-bold tracking-wider border-r border-red-700">Smt. {{ $i }}</th>
                                    @endfor
                                </tr>
                            </thead>
                            <tbody class="bg-red-50/20">
                                <tr>
                                    <td class="px-4 py-3 text-sm text-center text-gray-700 border-r border-red-200">1</td>
                                    <td class="px-4 py-3 text-sm font-medium text-gray-800 border-r border-red-200">{{ $mapel_nama }}</td>
                                    @for($i = 1; $i <= 12; $i++)
                                        <td class="px-2 py-3 text-sm text-center text-gray-700 border-r border-red-200">
                                            {{ $table_data["smt_$i"] ?? '' }}
                                        </td>
                                    @endfor
                                    <td class="px-4 py-3 text-sm text-center font-bold text-gray-900">
                                        {{ $table_data['rata_rata'] ?? '' }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Include Chart.js -->
            <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const ctx = document.getElementById('grafikNilaiCanvas').getContext('2d');
                    
                    const labels = @json($chart_labels);
                    const data = @json(array_values($chart_data));
                    
                    // Generate different colors for each bar
                    const colors = [
                        '#3B82F6', '#EF4444', '#10B981', '#F59E0B', '#6366F1', '#EC4899',
                        '#8B5CF6', '#14B8A6', '#F97316', '#06B6D4', '#84CC16', '#64748B'
                    ];

                    new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: labels,
                            datasets: [{
                                label: 'Capaian Nilai',
                                data: data,
                                backgroundColor: colors,
                                borderRadius: 4,
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    display: false // Hide legend as we have labels on X axis
                                },
                                tooltip: {
                                    callbacks: {
                                        label: function(context) {
                                            return 'Nilai: ' + context.parsed.y;
                                        }
                                    }
                                }
                            },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    max: 100,
                                    title: {
                                        display: true,
                                        text: 'Capaian Nilai'
                                    }
                                }
                            }
                        }
                    });
                });
            </script>
            @endif

        </div>
    </div>
</x-app-layout>
