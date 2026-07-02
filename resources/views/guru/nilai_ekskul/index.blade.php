<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight uppercase">
            {{ __('Input Nilai Ekstra Kurikuler Siswa') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50 min-h-screen" x-data="{ showManual: false }">
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

            <!-- Pilih Kelas dan Rombel Card -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6 border-t-4 border-red-800">
                <div class="p-6">
                    <form method="GET" action="{{ route('guru.nilai_ekskul.index') }}" class="space-y-4">
                        <div class="flex items-center gap-4">
                            <label for="ekstrakurikuler_id" class="w-1/4 text-sm font-bold text-gray-700 whitespace-nowrap">Pilih Kelas</label>
                            <select name="ekstrakurikuler_id" id="ekstrakurikuler_id" onchange="this.form.submit()" class="block w-3/4 rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm bg-gray-50">
                                <option value="">-- Pilih Ekstrakurikuler --</option>
                                @foreach($guruEkskuls as $e)
                                    <option value="{{ $e->id }}" {{ request('ekstrakurikuler_id') == $e->id ? 'selected' : '' }}>
                                        {{ $e->nama_ekskul }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="flex items-center gap-4">
                            <label for="rombel_id" class="w-1/4 text-sm font-bold text-gray-700 whitespace-nowrap">Pilih Rombel</label>
                            <select name="rombel_id" id="rombel_id" onchange="this.form.submit()" class="block w-3/4 rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm bg-gray-50" {{ !$ekstrakurikuler_id ? 'disabled' : '' }}>
                                <option value="">-- Pilih Rombel --</option>
                                @foreach($guruRombels as $r)
                                    <option value="{{ $r->id }}" {{ request('rombel_id') == $r->id ? 'selected' : '' }}>
                                        {{ $r->nama_rombel }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </form>
                </div>
            </div>

            @if($ekstrakurikuler_id && $rombel_id)
            <!-- Import Section Card -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <form action="{{ route('guru.nilai_ekskul.import_store') }}" method="POST" enctype="multipart/form-data" class="flex flex-col md:flex-row items-center justify-between gap-4">
                        @csrf
                        <input type="hidden" name="ekstrakurikuler_id" value="{{ $ekstrakurikuler_id }}">
                        <input type="hidden" name="rombel_id" value="{{ $rombel_id }}">
                        
                        <div class="flex items-center gap-4 w-full md:w-auto flex-1">
                            <label for="file_nilai" class="text-sm font-bold text-gray-700 whitespace-nowrap">Pilih File Nilai</label>
                            <input type="file" name="file_nilai" id="file_nilai" accept=".xls,.xlsx" required class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-gray-100 file:text-gray-700 hover:file:bg-gray-200 transition-colors border border-gray-300 rounded-md p-1">
                        </div>
                        
                        <div class="flex items-center gap-2 w-full md:w-auto">
                            <button type="submit" class="bg-red-800 hover:bg-red-900 text-white font-bold py-2 px-4 rounded text-sm shadow transition-colors inline-flex items-center whitespace-nowrap">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                                Import Nilai
                            </button>
                            <span class="text-gray-300 mx-1">|</span>
                            <a href="{{ route('guru.nilai_ekskul.download_format', ['ekstrakurikuler_id' => $ekstrakurikuler_id, 'rombel_id' => $rombel_id]) }}" class="bg-red-800 hover:bg-red-900 text-white font-bold py-2 px-4 rounded text-sm shadow transition-colors inline-flex items-center whitespace-nowrap">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                Download Format Import
                            </a>
                            <span class="text-gray-300 mx-1">|</span>
                            <button type="button" @click="showManual = !showManual" class="bg-red-800 hover:bg-red-900 text-white font-bold py-2 px-4 rounded text-sm shadow transition-colors inline-flex items-center whitespace-nowrap">
                                <svg x-show="!showManual" class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                <svg x-show="showManual" class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: none;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path></svg>
                                <span x-text="showManual ? 'Batal Tambah' : 'Input Manual Nilai'"></span>
                            </button>
                        </div>
                    </form>
                    
                    @error('file_nilai')
                        <p class="text-red-500 text-xs mt-2 ml-32">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Manual Input Form -->
            <div x-show="showManual" x-cloak style="display: none;" class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-4 border-b border-gray-200 flex justify-end bg-gray-50">
                    <button type="button" @click="showManual = false" class="bg-red-600 hover:bg-red-700 text-white font-bold py-1 px-4 rounded shadow transition-colors text-xs">
                        Batal Tambah
                    </button>
                </div>
                
                <form action="{{ route('guru.nilai_ekskul.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="ekstrakurikuler_id" value="{{ $ekstrakurikuler_id }}">
                    <input type="hidden" name="rombel_id" value="{{ $rombel_id }}">
                    
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 border-t border-gray-200">
                            <thead class="bg-red-800 text-white">
                                <tr>
                                    <th scope="col" class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wider w-12 border-r border-red-900">No</th>
                                    <th scope="col" class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wider w-48 border-r border-red-900">Nama Siswa</th>
                                    <th scope="col" class="px-4 py-3 text-center text-xs font-bold uppercase tracking-wider w-24 border-r border-red-900">NISN</th>
                                    <th scope="col" class="px-4 py-3 text-center text-xs font-bold uppercase tracking-wider w-24 border-r border-red-900">NIS</th>
                                    <th scope="col" class="px-4 py-3 text-center text-xs font-bold uppercase tracking-wider w-32 border-r border-red-900">Ekskul</th>
                                    <th scope="col" class="px-4 py-3 text-center text-xs font-bold uppercase tracking-wider w-32 border-r border-red-900">Nilai</th>
                                    <th scope="col" class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wider border-r border-red-900">Deskripsi</th>
                                    <th scope="col" class="px-4 py-3 text-center text-xs font-bold uppercase tracking-wider w-24">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($anggotaEkskul as $index => $anggota)
                                    <tr class="hover:bg-red-50/50 transition-colors">
                                        <td class="px-4 py-3 text-sm text-gray-500 font-medium border-r border-gray-200 align-top">{{ $index + 1 }}</td>
                                        <td class="px-4 py-3 text-sm font-bold text-gray-700 border-r border-gray-200 align-top">{{ $anggota->siswa->nama_lengkap }}</td>
                                        <td class="px-4 py-3 text-center text-sm text-gray-700 border-r border-gray-200 align-top">{{ $anggota->siswa->nisn }}</td>
                                        <td class="px-4 py-3 text-center text-sm text-gray-700 border-r border-gray-200 align-top">{{ $anggota->siswa->nis }}</td>
                                        <td class="px-4 py-3 text-center text-sm text-gray-700 border-r border-gray-200 align-top">{{ $ekskul->nama_ekskul }}</td>
                                        <td class="px-4 py-3 border-r border-gray-200 align-top">
                                            <select name="nilai[{{ $anggota->id }}][predikat]" class="block w-full rounded border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 text-xs py-1.5 px-2 bg-gray-50">
                                                <option value="">Pilih</option>
                                                <option value="Sangat Baik" {{ $anggota->predikat == 'Sangat Baik' ? 'selected' : '' }}>Sangat Baik</option>
                                                <option value="Baik" {{ $anggota->predikat == 'Baik' ? 'selected' : '' }}>Baik</option>
                                                <option value="Cukup" {{ $anggota->predikat == 'Cukup' ? 'selected' : '' }}>Cukup</option>
                                                <option value="Kurang" {{ $anggota->predikat == 'Kurang' ? 'selected' : '' }}>Kurang</option>
                                            </select>
                                        </td>
                                        <td class="px-4 py-3 border-r border-gray-200 align-top">
                                            <textarea name="nilai[{{ $anggota->id }}][keterangan]" rows="2" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm text-gray-700">{{ $anggota->keterangan }}</textarea>
                                        </td>
                                        <td class="px-4 py-3 text-center align-top">
                                            <a href="#" onclick="event.preventDefault(); if(confirm('Hapus siswa dari daftar ekstrakurikuler ini? Data nilainya juga akan hilang.')) document.getElementById('delete-form-{{ $anggota->id }}').submit();" class="text-red-600 hover:text-red-900 text-xs font-semibold underline">
                                                Hapus
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="px-6 py-8 whitespace-nowrap text-sm text-gray-500 text-center">
                                            Tidak ada siswa yang terdaftar pada Ekstrakurikuler ini untuk rombel yang dipilih.<br>
                                            <span class="text-xs text-red-500 mt-2 inline-block">Silakan berkoordinasi dengan Admin/Wali Kelas untuk memasukkan anggota.</span>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    @if($anggotaEkskul->count() > 0)
                    <div class="p-4 bg-gray-50 border-t border-gray-200 flex justify-end">
                        <button type="submit" class="bg-gray-800 hover:bg-gray-700 text-white font-bold py-2 px-6 rounded shadow transition-colors inline-flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
                            Simpan Nilai
                        </button>
                    </div>
                    @endif
                </form>

                <!-- Hidden Delete Forms for Each Row -->
                @foreach($anggotaEkskul as $anggota)
                    <form id="delete-form-{{ $anggota->id }}" action="{{ route('guru.nilai_ekskul.destroy', $anggota->id) }}" method="POST" style="display: none;">
                        @csrf
                        @method('DELETE')
                    </form>
                @endforeach
            </div>
            @endif

            @if(!$ekstrakurikuler_id || !$rombel_id)
            <div class="bg-white p-6 shadow-sm sm:rounded-lg text-sm text-gray-500">
                Data Kosong. Silakan pilih kelas (Ekstrakurikuler) dan Rombel terlebih dahulu.
            </div>
            @endif

        </div>
    </div>
</x-app-layout>
