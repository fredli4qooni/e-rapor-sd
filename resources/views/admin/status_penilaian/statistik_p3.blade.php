<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Statistik Capaian Profil Pelajar Pancasila (P3)') }}
        </h2>
    </x-slot>

    <div class="py-6 space-y-6 max-w-7xl mx-auto sm:px-6 lg:px-8">
        
        <!-- Peringatan Kurikulum Lama -->
        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded-md shadow-sm">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-yellow-700">
                        Fitur ini hanya aktif dan relevan untuk tahun ajaran sebelum <strong>2025/2026</strong>.
                    </p>
                </div>
            </div>
        </div>

        <!-- Filter -->
        <div class="bg-white p-4 rounded-md shadow flex justify-between items-center">
            <form action="{{ route('admin.status_penilaian.statistik_p3') }}" method="GET" class="flex items-center space-x-2">
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
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Grafik Distribusi -->
            <div class="bg-white rounded-md shadow-md overflow-hidden lg:col-span-1">
                <div class="bg-[#8B1515] text-white px-6 py-4 font-bold tracking-wider uppercase text-sm border-b border-red-800">
                    Distribusi Predikat Capaian
                </div>
                <div class="p-6">
                    <canvas id="p3Chart" height="250"></canvas>
                </div>
            </div>

            <!-- Tabel Statistik per Sub Elemen -->
            <div class="bg-white rounded-md shadow-md overflow-hidden lg:col-span-2">
                <div class="bg-gray-800 text-white px-6 py-4 font-bold tracking-wider uppercase text-sm border-b border-gray-900">
                    Tabel Statistik Capaian P3 (Per Sub-Elemen)
                </div>
                <div class="p-0 overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-700">
                        <thead class="text-xs text-gray-700 bg-gray-100 uppercase border-b">
                            <tr>
                                <th class="px-4 py-3">Sub Elemen Profil</th>
                                <th class="px-2 py-3 text-center">BB</th>
                                <th class="px-2 py-3 text-center">MB</th>
                                <th class="px-2 py-3 text-center">BSH</th>
                                <th class="px-2 py-3 text-center">SB</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($statistik as $stat)
                                <tr class="border-b hover:bg-gray-50">
                                    <td class="px-4 py-3 text-xs leading-tight">
                                        <div class="font-medium line-clamp-2" title="{{ $stat->sub_elemen }}">{{ $stat->sub_elemen }}</div>
                                    </td>
                                    <td class="px-2 py-3 text-center font-bold text-gray-500">{{ $stat->bb }}</td>
                                    <td class="px-2 py-3 text-center font-bold text-yellow-600">{{ $stat->mb }}</td>
                                    <td class="px-2 py-3 text-center font-bold text-blue-600">{{ $stat->bsh }}</td>
                                    <td class="px-2 py-3 text-center font-bold text-green-600">{{ $stat->sb }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-4 py-3 text-center text-gray-500">Belum ada data capaian P5.</td>
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
                const ctx = document.getElementById('p3Chart').getContext('2d');
                const chartData = @json($chartData);
                
                if (chartData.labels && chartData.labels.length > 0) {
                    new Chart(ctx, {
                        type: 'pie',
                        data: {
                            labels: chartData.labels,
                            datasets: [{
                                data: chartData.data,
                                backgroundColor: [
                                    '#9CA3AF', // BB - Gray
                                    '#F59E0B', // MB - Yellow
                                    '#3B82F6', // BSH - Blue
                                    '#10B981'  // SB - Green
                                ],
                                borderWidth: 1
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    position: 'bottom',
                                    labels: {
                                        boxWidth: 12
                                    }
                                }
                            }
                        }
                    });
                }
            });
        </script>
        @else
        <div class="bg-white p-6 rounded-md shadow text-center text-gray-500">
            Silakan pilih kelas terlebih dahulu untuk melihat statistik capaian P3.
        </div>
        @endif

    </div>
</x-app-layout>
