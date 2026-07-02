<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight uppercase">
            {{ __('Input Deskripsi Kokurikuler') }}
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
                    <form method="GET" action="{{ route('guru.nilai_kokurikuler.deskripsi_index') }}" class="space-y-4">
                        <div class="flex items-center gap-4">
                            <label class="w-1/4 text-sm font-bold text-gray-700 whitespace-nowrap">Pilih Kelompok Kokurikuler</label>
                            <select name="kelompok_id" onchange="this.form.submit()" class="block w-3/4 rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm bg-gray-50">
                                <option value="">Pilih Kelompok</option>
                                @foreach($kelompoks as $k)
                                    <option value="{{ $k->id }}" {{ $kelompok_id == $k->id ? 'selected' : '' }}>
                                        {{ $k->nama_kelompok }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </form>
                </div>
            </div>

            @if($kelompok_id)
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 border-b border-gray-200">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-bold text-gray-800">Deskripsi Ketercapaian Profil Lulusan pada Kegiatan Kokurikuler</h3>
                        
                        <div class="flex gap-2">
                            <form action="{{ route('guru.nilai_kokurikuler.generate_deskripsi') }}" method="POST">
                                @csrf
                                <input type="hidden" name="kelompok_id" value="{{ $kelompok_id }}">
                                <button type="submit" class="bg-red-800 hover:bg-red-900 text-white font-bold py-2 px-4 rounded text-sm shadow transition-colors inline-flex items-center" onclick="return confirm('Generate otomatis akan menimpa deskripsi yang belum disimpan. Lanjutkan?')">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
                                    Generate Otomatis dari Nilai Kokurikuler
                                </button>
                            </form>
                        </div>
                    </div>

                    <form action="{{ route('guru.nilai_kokurikuler.store_deskripsi') }}" method="POST">
                        @csrf
                        <input type="hidden" name="kelompok_id" value="{{ $kelompok_id }}">

                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 border border-gray-300">
                                <thead class="bg-red-900 text-white">
                                    <tr>
                                        <th scope="col" class="px-4 py-3 text-left text-xs font-bold tracking-wider w-64 border border-red-800">Nama Siswa</th>
                                        <th scope="col" class="px-4 py-3 text-left text-xs font-bold tracking-wider border border-red-800">Deskripsi</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @forelse($siswas as $index => $siswa)
                                        @php
                                            $catatan = '';
                                            if (isset($catatans[$siswa->id])) {
                                                $catatan = $catatans[$siswa->id]->catatan;
                                            }
                                        @endphp
                                        <tr class="hover:bg-red-50/50 transition-colors">
                                            <td class="px-4 py-3 text-sm text-gray-700 border border-gray-300 align-top">
                                                <div class="font-bold">
                                                    {{ $index + 1 }} {{ $siswa->nama_lengkap }}
                                                </div>
                                                <div class="text-xs text-gray-500">NISN : {{ $siswa->nisn }}</div>
                                                <div class="text-xs text-gray-500">NIS : {{ $siswa->nis ?? '-' }}</div>
                                            </td>
                                            <td class="px-4 py-3 border border-gray-300">
                                                <textarea name="catatan[{{ $siswa->id }}]" rows="4" class="block w-full rounded border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm bg-gray-50 resize-y p-2">{{ $catatan }}</textarea>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="2" class="px-6 py-8 whitespace-nowrap text-sm text-gray-500 text-center">
                                                Tidak ada data siswa pada kelompok ini.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        
                        @if($siswas->count() > 0)
                        <div class="mt-4 flex justify-end">
                            <button type="submit" class="bg-gray-800 hover:bg-gray-700 text-white font-bold py-2 px-6 rounded shadow transition-colors inline-flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
                                Simpan Data
                            </button>
                        </div>
                        @endif
                    </form>
                </div>
            </div>
            @endif

        </div>
    </div>
</x-app-layout>
