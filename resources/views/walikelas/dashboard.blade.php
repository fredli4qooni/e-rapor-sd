<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Wali Kelas Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-bold mb-4">Aksi Wali Kelas</h3>
                    <div class="flex flex-wrap gap-4">
                        <a href="{{ route('walikelas.cetak_nilai.pelengkap_index') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded shadow">
                            Pelengkap Rapor
                        </a>
                        <a href="{{ route('walikelas.cetak_nilai.rapor_index') }}" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded shadow">
                            Cetak Rapor Siswa
                        </a>
                        
                        <a href="{{ route('walikelas.cetak_nilai.leger') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded shadow">
                            Leger Rapor Kelas
                        </a>

                        <a href="{{ route('walikelas.presensi.index') }}" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2 px-4 rounded shadow flex items-center gap-1.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            Presensi Harian
                        </a>
                        <a href="{{ route('walikelas.kehadiran.index') }}" class="bg-teal-600 hover:bg-teal-700 text-white font-bold py-2 px-4 rounded shadow">
                            Rekap Kehadiran
                        </a>
                        <a href="{{ route('walikelas.catatan.index') }}" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded shadow">
                            Catatan Wali Kelas
                        </a>
                        <a href="{{ route('walikelas.ekskul.index') }}" class="bg-purple-600 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded shadow">
                            Ekstrakurikuler
                        </a>
                    </div>
                </div>
            </div>
            <div class="mt-6 bg-white rounded-md shadow-md">
                <div class="bg-[#8B1515] text-white px-4 py-2 rounded-t-md font-bold text-sm uppercase tracking-wider">
                    Grafik Rata-rata Nilai per Mapel di Kelas
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
