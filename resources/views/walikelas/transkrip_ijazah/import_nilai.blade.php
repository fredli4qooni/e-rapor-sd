<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight uppercase">
            {{ __('Import Nilai Ijazah') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-t-4 border-red-800">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-bold text-gray-800 mb-6 uppercase border-b pb-2">Import Nilai Ijazah</h3>

                    <!-- Select Mapel Form -->
                    <form method="GET" action="{{ route('walikelas.transkrip_ijazah.import_nilai') }}" class="mb-8 p-4 border border-gray-200 rounded-md bg-white">
                        <div class="space-y-4">
                            <div class="flex flex-col md:flex-row md:items-center gap-4">
                                <label class="w-full md:w-48 font-bold text-gray-700 text-sm">Pilih Kelas</label>
                                <input type="text" value="{{ $rombel->tingkat . ' ' . strtoupper($rombel->nama_rombel) }}" class="flex-1 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-md p-2 max-w-3xl" readonly>
                            </div>
                            <div class="flex flex-col md:flex-row md:items-center gap-4">
                                <label class="w-full md:w-48 font-bold text-gray-700 text-sm">Pilih Mapel</label>
                                <select name="mata_pelajaran_id" class="flex-1 border border-blue-300 bg-blue-50 text-gray-900 text-sm rounded-md p-2 max-w-3xl focus:ring-red-500 focus:border-red-500" onchange="this.form.submit()">
                                    <option value="">Pilih Mata Pelajaran</option>
                                    @foreach($mapels as $mapel)
                                        <option value="{{ $mapel->id }}" {{ request('mata_pelajaran_id') == $mapel->id ? 'selected' : '' }}>
                                            {{ $mapel->nama_mapel }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </form>

                    @if(request('mata_pelajaran_id'))
                    <!-- Import Area -->
                    <div class="mb-8 p-6 border border-gray-200 rounded-md bg-white shadow-sm">
                        <div class="flex justify-between items-center border-b pb-4 mb-4">
                            <h3 class="text-lg font-bold text-gray-800">Import Nilai Ijazah</h3>
                            <a href="{{ route('walikelas.transkrip_ijazah.download_format', ['mata_pelajaran_id' => request('mata_pelajaran_id')]) }}" class="inline-flex items-center px-4 py-2 bg-red-900 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-800 transition">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-3 3m0 0l-3-3m3 3V4"></path></svg>
                                Format Import Nilai Kelas {{ $rombel->nama_rombel }}
                            </a>
                        </div>

                        <form method="POST" action="{{ route('walikelas.transkrip_ijazah.process_import') }}" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="mata_pelajaran_id" value="{{ request('mata_pelajaran_id') }}">
                            
                            <div class="flex flex-col md:flex-row md:items-center gap-4 mb-6">
                                <label class="w-full md:w-48 font-bold text-gray-700 text-sm">Pilih File Nilai</label>
                                <input type="file" name="file_import" accept=".xls,.xlsx" required class="flex-1 border border-gray-300 text-gray-900 text-sm rounded-md p-1.5 max-w-3xl file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-gray-100 file:text-gray-700 hover:file:bg-gray-200">
                            </div>

                            <div class="flex justify-end mb-6">
                                <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-900 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-800 transition">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-3-3m0 0L8 8m4-4v12"></path></svg>
                                    Import Nilai
                                </button>
                            </div>
                        </form>

                        <div class="bg-cyan-50 border border-cyan-200 text-cyan-800 px-4 py-3 rounded-md text-sm mt-4">
                            <p class="font-medium">Data yang diinput pada format nilai tidak boleh berupa formula/rumus !!!</p>
                            <p>Data dalam bentuk formula/rumus tidak dapat disimpan dalam sistem e-Rapor</p>
                        </div>
                    </div>
                    @else
                        <div class="p-4 bg-yellow-50 border border-yellow-200 text-yellow-800 rounded">
                            Silakan pilih Mata Pelajaran terlebih dahulu.
                        </div>
                    @endif

                </div>
            </div>

        </div>
    </div>
</x-app-layout>
