<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight uppercase">
            {{ __('Input Catatan Proses Projek') }}
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

            <!-- Filter Section Card -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6 border-t-4 border-red-800">
                <div class="p-6">
                    <form method="GET" action="{{ route('guru.nilai_p5.input_catatan') }}" class="space-y-4">
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
                <div class="p-4 border-b border-gray-200 flex justify-end bg-gray-50">
                    <form action="{{ route('guru.nilai_p5.reset_catatan') }}" method="POST" onsubmit="return confirm('Anda yakin ingin mereset catatan kembali ke bawaan sistem? Catatan yang diubah manual akan hilang.')">
                        @csrf
                        <input type="hidden" name="proyek_id" value="{{ $proyek_id }}">
                        <button type="submit" class="bg-red-800 hover:bg-red-900 text-white font-bold py-2 px-4 rounded text-sm shadow transition-colors inline-flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                            Reset Catatan
                        </button>
                    </form>
                </div>
                
                <form action="{{ route('guru.nilai_p5.store_catatan') }}" method="POST">
                    @csrf
                    <input type="hidden" name="proyek_id" value="{{ $proyek_id }}">

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 border-t border-gray-200">
                            <thead class="bg-red-800 text-white">
                                <tr>
                                    <th scope="col" class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wider w-12 border-r border-red-900">No</th>
                                    <th scope="col" class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wider w-64 border-r border-red-900">Nama Siswa</th>
                                    <th scope="col" class="px-4 py-3 text-center text-xs font-bold uppercase tracking-wider w-24 border-r border-red-900">NISN</th>
                                    <th scope="col" class="px-4 py-3 text-center text-xs font-bold uppercase tracking-wider w-24 border-r border-red-900">NIS</th>
                                    <th scope="col" class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wider">Catatan</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($siswas as $index => $siswa)
                                    @php
                                        // Use manual catatan if exists, otherwise use auto generated
                                        $catatan_text = isset($catatans[$siswa->id]) ? $catatans[$siswa->id]->catatan : ($auto_catatans[$siswa->id] ?? '');
                                    @endphp
                                    <tr class="hover:bg-red-50/50 transition-colors">
                                        <td class="px-4 py-3 text-sm text-gray-500 font-medium border-r border-gray-200 align-top">{{ $index + 1 }}</td>
                                        <td class="px-4 py-3 text-sm font-bold text-gray-700 border-r border-gray-200 uppercase align-top">{{ $siswa->nama_lengkap }}</td>
                                        <td class="px-4 py-3 text-center text-sm text-gray-700 border-r border-gray-200 align-top">{{ $siswa->nisn }}</td>
                                        <td class="px-4 py-3 text-center text-sm text-gray-700 border-r border-gray-200 align-top">{{ $siswa->nis }}</td>
                                        <td class="px-4 py-3 border-r border-gray-200">
                                            <textarea name="catatan[{{ $siswa->id }}]" rows="3" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm text-gray-700">{{ $catatan_text }}</textarea>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-8 whitespace-nowrap text-sm text-gray-500 text-center">
                                            Tidak ada data siswa pada kelompok ini.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    @if($siswas->count() > 0)
                    <div class="p-4 flex justify-end bg-gray-50 border-t border-gray-200">
                        <button type="submit" class="bg-gray-800 hover:bg-gray-700 text-white font-bold py-2 px-6 rounded shadow transition-colors inline-flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
                            Simpan Data
                        </button>
                    </div>
                    @endif
                </form>
            </div>
            @endif

        </div>
    </div>
</x-app-layout>
