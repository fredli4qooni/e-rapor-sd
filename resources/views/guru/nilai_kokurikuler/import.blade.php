<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight uppercase">
            {{ __('Import Nilai Kokurikuler') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            @if(session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded shadow-sm" role="alert">
                <p class="font-bold">Berhasil</p>
                <p>{{ session('success') }}</p>
            </div>
            @endif

            @if(session('error'))
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded shadow-sm" role="alert">
                <p class="font-bold">Gagal</p>
                <p>{{ session('error') }}</p>
            </div>
            @endif

            <!-- Filter Section Card -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6 border-t-4 border-red-800">
                <div class="p-6">
                    <form method="GET" action="{{ route('guru.nilai_kokurikuler.import_index') }}" class="space-y-4">
                        <div class="flex items-center gap-4">
                            <label class="w-1/4 text-sm font-bold text-gray-700 whitespace-nowrap">Pilih Kelompok</label>
                            <select name="kelompok_id" onchange="this.form.submit()" class="block w-3/4 rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm bg-gray-50">
                                <option value="">Pilih Kelompok</option>
                                @foreach($kelompoks as $k)
                                    <option value="{{ $k->id }}" {{ $kelompok_id == $k->id ? 'selected' : '' }}>
                                        {{ $k->nama_kelompok }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="flex items-center gap-4">
                            <label class="w-1/4 text-sm font-bold text-gray-700 whitespace-nowrap">Pilih Kegiatan Kokurikuler</label>
                            <select name="proyek_id" onchange="this.form.submit()" class="block w-3/4 rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm bg-gray-50" {{ !$kelompok_id ? 'disabled' : '' }}>
                                <option value="">Pilih Kegiatan Kokurikuler</option>
                                @foreach($proyeks as $p)
                                    <option value="{{ $p->id }}" {{ $proyek_id == $p->id ? 'selected' : '' }}>
                                        {{ $p->nama_proyek }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </form>
                </div>
            </div>

            @if($proyek_id)
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 border-b border-gray-200">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-bold text-gray-800">Import Nilai Kokurikuler :</h3>
                        <a href="{{ route('guru.nilai_kokurikuler.download_format', ['kelompok_id' => $kelompok_id, 'proyek_id' => $proyek_id]) }}" class="bg-red-800 hover:bg-red-900 text-white font-bold py-2 px-4 rounded text-sm shadow transition-colors inline-flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                            Download Format Kelompok ini
                        </a>
                    </div>

                    <form action="{{ route('guru.nilai_kokurikuler.import_store') }}" method="POST" enctype="multipart/form-data" class="flex flex-col md:flex-row items-center justify-between gap-4 border border-blue-200 bg-blue-50/50 p-4 rounded-md">
                        @csrf
                        <input type="hidden" name="proyek_id" value="{{ $proyek_id }}">
                        <input type="hidden" name="kelompok_id" value="{{ $kelompok_id }}">
                        
                        <div class="flex-1 flex items-center gap-4 w-full">
                            <label for="file_nilai" class="text-sm font-bold text-gray-700 whitespace-nowrap">Pilih File Nilai Kokurikuler</label>
                            <input type="file" name="file_nilai" id="file_nilai" accept=".xls,.xlsx" required class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-100 file:text-blue-700 hover:file:bg-blue-200 transition-colors border border-gray-300 rounded-md p-1 bg-white">
                        </div>
                        
                        <button type="submit" class="bg-blue-700 hover:bg-blue-800 text-white font-bold py-2 px-6 rounded shadow transition-colors inline-flex items-center whitespace-nowrap">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                            Import Nilai Kokurikuler
                        </button>
                    </form>

                    <div class="mt-8 bg-blue-50 border-l-4 border-blue-400 p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-blue-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-blue-800">Petunjuk Import</h3>
                                <div class="mt-2 text-sm text-blue-700">
                                    <ul class="list-disc pl-5 space-y-1">
                                        <li>Data yang diinput pada format nilai tidak boleh berupa formula/rumus !!!</li>
                                        <li>Data dalam bentuk formula/rumus tidak dapat disimpan dalam sistem e-rapor</li>
                                        <li>Gunakan kode angka untuk capaian:
                                            <ul class="list-none pl-5 mt-1">
                                                <li><span class="font-bold text-gray-800">1</span> : Berkembang</li>
                                                <li><span class="font-bold text-gray-800">2</span> : Cakap</li>
                                                <li><span class="font-bold text-gray-800">3</span> : Mahir</li>
                                            </ul>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            @endif

        </div>
    </div>
</x-app-layout>
