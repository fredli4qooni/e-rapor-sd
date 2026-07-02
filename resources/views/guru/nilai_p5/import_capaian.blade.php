<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight uppercase">
            {{ __('Import Nilai Projek P5') }}
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
                    <form method="GET" action="{{ route('guru.nilai_p5.import_capaian') }}" class="space-y-4">
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
                            <label class="w-1/4 text-sm font-bold text-gray-700 whitespace-nowrap">Pilih Projek</label>
                            <select name="proyek_id" onchange="this.form.submit()" class="block w-3/4 rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm bg-gray-50" {{ !$kelompok_id ? 'disabled' : '' }}>
                                <option value="">Pilih Data Projek</option>
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
                        <h3 class="text-lg font-bold text-gray-800">Import Nilai P5 :</h3>
                        <a href="{{ route('guru.nilai_p5.download_format', ['kelompok_id' => $kelompok_id, 'proyek_id' => $proyek_id]) }}" class="bg-red-800 hover:bg-red-900 text-white font-bold py-2 px-4 rounded text-sm shadow transition-colors inline-flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                            Download Format Import
                        </a>
                    </div>

                    <form action="{{ route('guru.nilai_p5.store_import_capaian') }}" method="POST" enctype="multipart/form-data" class="flex flex-col md:flex-row items-center justify-between gap-4 border border-blue-200 bg-blue-50/50 p-4 rounded-md">
                        @csrf
                        <input type="hidden" name="proyek_id" value="{{ $proyek_id }}">
                        
                        <div class="flex items-center gap-4 w-full md:w-auto flex-1">
                            <label for="file_nilai" class="text-sm font-bold text-gray-700 whitespace-nowrap">Pilih File Nilai P5</label>
                            <input type="file" name="file_nilai" id="file_nilai" accept=".xls,.xlsx" required class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-100 file:text-blue-700 hover:file:bg-blue-200 transition-colors border border-gray-300 rounded-md p-1 bg-white">
                        </div>
                        
                        <div class="flex items-center gap-2 w-full md:w-auto">
                            <button type="submit" class="bg-red-800 hover:bg-red-900 text-white font-bold py-2 px-6 rounded text-sm shadow transition-colors inline-flex items-center whitespace-nowrap">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                                Import Nilai
                            </button>
                        </div>
                    </form>
                    
                    @error('file_nilai')
                        <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                    @enderror

                    <div class="mt-6 text-sm text-gray-600 bg-gray-100 p-4 rounded-md border border-gray-200">
                        <p class="font-bold mb-2">Petunjuk Pengisian:</p>
                        <ul class="list-disc pl-5 space-y-1">
                            <li>Isi pada kolom dengan nama Subelemen (Sub X).</li>
                            <li>Gunakan kode predikat berikut:
                                <ul class="list-none pl-5 mt-1">
                                    <li><span class="font-bold text-gray-800">SAB</span> : Sangat Berkembang</li>
                                    <li><span class="font-bold text-gray-800">BSH</span> : Berkembang Sesuai Harapan</li>
                                    <li><span class="font-bold text-gray-800">SB</span> : Sedang Berkembang</li>
                                    <li><span class="font-bold text-gray-800">MB</span> : Mulai Berkembang</li>
                                </ul>
                            </li>
                            <li class="text-red-600 font-semibold">Jangan mengubah ID siswa atau struktur kolom yang ada.</li>
                        </ul>
                    </div>
                </div>
            </div>
            @endif

        </div>
    </div>
</x-app-layout>
