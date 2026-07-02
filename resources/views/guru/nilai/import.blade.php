<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Import Nilai Rapor') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-4">
                <a href="{{ route('guru.nilai.index', $rombel && $mapel ? ['rombel_id' => $rombel->id, 'mata_pelajaran_id' => $mapel->id] : []) }}" class="inline-flex items-center text-sm font-medium text-gray-500 hover:text-gray-700 transition-colors">
                    <svg class="mr-2 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Kembali ke Input Nilai
                </a>
            </div>
            
            @if(session('error'))
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 rounded shadow-sm" role="alert">
                <p class="font-bold">Gagal</p>
                <p>{{ session('error') }}</p>
            </div>
            @endif

            @if(session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 rounded shadow-sm">
                    <p class="font-bold">Berhasil</p>
                    <p>{{ session('success') }}</p>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 border-b border-gray-200">
                    <form method="GET" action="{{ route('guru.nilai.import_index') }}" class="flex flex-col md:flex-row gap-4 items-end">
                        <div class="flex-1 w-full">
                            <label for="rombel_id" class="block text-sm font-bold text-gray-700 mb-2">Pilih Kelas</label>
                            <select name="rombel_id" id="rombel_id" onchange="this.form.submit()" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm bg-gray-50">
                                <option value="">-- Pilih Kelas --</option>
                                @foreach($guruRombels as $r)
                                    <option value="{{ $r->id }}" {{ $rombel_id == $r->id ? 'selected' : '' }}>
                                        Kelas {{ $r->nama_rombel }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="flex-1 w-full">
                            <label for="mata_pelajaran_id" class="block text-sm font-bold text-gray-700 mb-2">Pilih Mata Pelajaran</label>
                            <select name="mata_pelajaran_id" id="mata_pelajaran_id" onchange="this.form.submit()" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm bg-gray-50" {{ !$rombel_id ? 'disabled' : '' }}>
                                <option value="">-- Pilih Mata Pelajaran --</option>
                                @foreach($guruMapels as $m)
                                    <option value="{{ $m->id }}" {{ $mata_pelajaran_id == $m->id ? 'selected' : '' }}>
                                        {{ $m->nama_mapel }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </form>
                </div>
            </div>

            @if($rombel && $mapel)
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 border-b border-gray-200">
                    
                    <div class="mb-6 bg-blue-50 border-l-4 border-blue-500 p-4 rounded-r-lg flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                        <div>
                            <h4 class="font-bold text-blue-800 mb-2">Instruksi Import: Kelas {{ $rombel->nama_rombel }} - {{ $mapel->nama_mapel }}</h4>
                            <ol class="list-decimal list-inside text-sm text-blue-700 space-y-1">
                                <li>Unduh format template Excel khusus kelas ini.</li>
                                <li>Isi kolom <strong>Nilai Akhir</strong> dengan angka 0-100.</li>
                                <li>Isi kolom <strong>Capaian Tertinggi</strong> dengan huruf <strong>T</strong> (untuk TP yang dikuasai secara optimal).</li>
                                <li>Isi kolom <strong>Capaian Terendah</strong> dengan huruf <strong>R</strong> (untuk TP yang perlu pendampingan).</li>
                                <li>Biarkan kosong untuk TP yang capaiannya biasa/standar saja.</li>
                                <li>Unggah file Excel yang telah diisi.</li>
                            </ol>
                        </div>
                        <a href="{{ route('guru.nilai.download_format', ['rombel_id' => $rombel->id, 'mata_pelajaran_id' => $mapel->id]) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md font-semibold text-xs shadow transition-colors text-center whitespace-nowrap">
                            <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                            Download Format<br>Kelas Ini
                        </a>
                    </div>

                    <form action="{{ route('guru.nilai.import_store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="rombel_id" value="{{ $rombel->id }}">
                        <input type="hidden" name="mata_pelajaran_id" value="{{ $mapel->id }}">

                        <div class="mb-8">
                            <label for="file_nilai" class="block text-sm font-bold text-gray-700 mb-2">Upload File Excel (.xls, .xlsx) <span class="text-red-500">*</span></label>
                            <input type="file" name="file_nilai" id="file_nilai" accept=".xls,.xlsx" required class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-red-50 file:text-red-700 hover:file:bg-red-100 transition-colors border border-gray-300 rounded-md p-2">
                            @error('file_nilai')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="pt-5 border-t border-gray-200 flex justify-end">
                            <button type="submit" class="bg-red-800 hover:bg-red-700 text-white font-bold py-2 px-6 rounded shadow transition-colors inline-flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
                                Import Data Nilai
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            @endif
        </div>
    </div>
</x-app-layout>
