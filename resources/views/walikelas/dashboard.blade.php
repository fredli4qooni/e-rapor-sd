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
                        <a href="{{ route('walikelas.cetak.pelengkap', 1) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" target="_blank">
                            Cetak Pelengkap Rapor (Demo Siswa ID: 1)
                        </a>
                        <form action="{{ route('walikelas.cetak.semester', 1) }}" method="GET" target="_blank" class="inline">
                            <input type="hidden" name="kurikulum" value="MERDEKA">
                            <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                                Cetak Rapor Merdeka (Demo Siswa 1)
                            </button>
                        </form>
                        
                        <a href="{{ route('walikelas.export.leger') }}" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">
                            Download Leger Excel
                        </a>

                        <a href="{{ route('walikelas.kehadiran.index') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Input Kehadiran & Catatan
                        </a>
                        <a href="{{ route('walikelas.sikap.index') }}" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                            Input Nilai Sikap
                        </a>
                        <a href="{{ route('walikelas.ekskul.index') }}" class="bg-purple-500 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded">
                            Input Ekstrakurikuler
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
