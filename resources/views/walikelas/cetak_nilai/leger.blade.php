<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight uppercase">
            {{ __('Cetak Leger Nilai Rapor Siswa') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if (session('error'))
                <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif
            @if (session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <div class="bg-white border border-gray-200 shadow-sm rounded-md mb-6">
                <div class="bg-white border-b px-4 py-3 rounded-t-md">
                    <h2 class="text-gray-800 font-bold uppercase text-sm">LEGER NILAI RAPOR SISWA PER KELAS</h2>
                </div>
                
                <div class="p-4">
                    <div class="flex items-center mb-6">
                        <label class="w-48 text-sm font-semibold text-gray-700">Pilih Kelas :</label>
                        <div class="flex-1">
                            <input type="text" value="{{ $rombel->tingkat . ' ' . $rombel->nama_rombel }}" readonly class="w-full bg-blue-50 border border-blue-300 text-gray-700 py-2 px-3 rounded-md text-sm cursor-not-allowed">
                        </div>
                    </div>

                    <!-- Leger Semester Ini -->
                    <div class="border-t border-gray-200 pt-6 mb-6">
                        <h3 class="text-lg font-bold text-gray-800 mb-2">Leger Nilai Rapor Semester</h3>
                        <p class="text-sm text-gray-600 mb-4">Ini berisi leger data nilai rapor siswa pada semester sekarang disajikan dalam bentuk file excel, silahkan klik tombol di bawah ini untuk membuat dan download leger nilai rapor semester sekarang.</p>
                        
                        <div class="flex justify-end border-t border-gray-100 pt-4">
                            <a href="{{ route('walikelas.cetak_nilai.leger.download', ['tipe' => 'semester']) }}" class="inline-flex items-center px-4 py-2 bg-red-900 border border-transparent rounded-md font-semibold text-xs text-white tracking-widest hover:bg-red-800 transition">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                Download Leger Semester ini
                            </a>
                        </div>
                    </div>

                    <!-- Leger Semua Semester -->
                    <div class="border-t border-gray-200 pt-6">
                        <h3 class="text-lg font-bold text-gray-800 mb-2">Leger Nilai Rapor Semua Semester</h3>
                        <p class="text-sm text-gray-600 mb-4">Ini berisi leger data nilai rapor siswa dari semester awal hingga semester akhir disajikan dalam bentuk file excel, silahkan klik tombol di bawah ini untuk membuat dan download leger nilai rapor semua semester.</p>
                        
                        <div class="flex justify-end border-t border-gray-100 pt-4">
                            <a href="{{ route('walikelas.cetak_nilai.leger.download', ['tipe' => 'semua']) }}" class="inline-flex items-center px-4 py-2 bg-red-900 border border-transparent rounded-md font-semibold text-xs text-white tracking-widest hover:bg-red-800 transition">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                Download Leger Semua Semester
                            </a>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
