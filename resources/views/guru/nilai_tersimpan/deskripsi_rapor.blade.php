<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight uppercase">
            {{ __('Data Deskripsi Ketercapaian Pembelajaran Yang Terkirim') }}
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

            <!-- Pilih Kelas dan Mapel Card -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6 border-t-4 border-red-800">
                <div class="p-6">
                    <form method="GET" action="{{ route('guru.nilai-tersimpan.deskripsi') }}" class="space-y-4">
                        <div class="flex items-center gap-4">
                            <label for="rombel_id" class="w-1/4 text-sm font-bold text-gray-700 whitespace-nowrap">Pilih Kelas</label>
                            <select name="rombel_id" id="rombel_id" onchange="this.form.submit()" class="block w-3/4 rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm bg-gray-50">
                                <option value="">-- Pilih Kelas --</option>
                                @foreach($guruRombels as $r)
                                    <option value="{{ $r->id }}" {{ request('rombel_id') == $r->id ? 'selected' : '' }}>
                                        {{ $r->nama_rombel }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="flex items-center gap-4">
                            <label for="mata_pelajaran_id" class="w-1/4 text-sm font-bold text-gray-700 whitespace-nowrap">Pilih Mapel</label>
                            <select name="mata_pelajaran_id" id="mata_pelajaran_id" onchange="this.form.submit()" class="block w-3/4 rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm bg-gray-50" {{ !$rombel_id ? 'disabled' : '' }}>
                                <option value="">-- Pilih Mapel --</option>
                                @foreach($guruMapels as $m)
                                    <option value="{{ $m->id }}" {{ request('mata_pelajaran_id') == $m->id ? 'selected' : '' }}>
                                        {{ $m->nama_mapel }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </form>
                </div>
            </div>

            @if($rombel_id && $mata_pelajaran_id)
            <!-- Deskripsi Rapor Table Card -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 border-t border-gray-200">
                        <thead class="bg-red-800 text-white">
                            <tr>
                                <th scope="col" class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wider w-12 border-r border-red-900">No</th>
                                <th scope="col" class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wider w-48 border-r border-red-900">Nama Siswa</th>
                                <th scope="col" class="px-4 py-3 text-center text-xs font-bold uppercase tracking-wider w-24 border-r border-red-900">NISN</th>
                                <th scope="col" class="px-4 py-3 text-center text-xs font-bold uppercase tracking-wider w-24 border-r border-red-900">NIS</th>
                                <th scope="col" class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wider border-r border-red-900">Deskripsi Ketercapaian Pembelajaran</th>
                                <th scope="col" class="px-4 py-3 text-center text-xs font-bold uppercase tracking-wider w-32">
                                    @if($nilaiRapors->whereNotNull('deskripsi')->count() > 0)
                                        <form action="{{ route('guru.nilai-tersimpan.deskripsi.destroy', 'all') }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus SEMUA deskripsi rapor untuk kelas dan mata pelajaran ini? Data tidak dapat dikembalikan.');">
                                            @csrf
                                            @method('DELETE')
                                            <input type="hidden" name="rombel_id" value="{{ $rombel_id }}">
                                            <input type="hidden" name="mata_pelajaran_id" value="{{ $mata_pelajaran_id }}">
                                            <button type="submit" class="bg-red-600 hover:bg-red-700 text-white p-1 rounded shadow transition-colors text-[10px] w-full" title="Hapus Semua">
                                                <svg class="w-3 h-3 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                Hapus Semua
                                            </button>
                                        </form>
                                    @else
                                        Hapus
                                    @endif
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($nilaiRapors as $index => $nilai)
                                @if($nilai->deskripsi)
                                <tr class="hover:bg-red-50/50 transition-colors">
                                    <td class="px-4 py-3 text-sm text-gray-500 font-medium border-r border-gray-200 align-top">{{ $index + 1 }}</td>
                                    <td class="px-4 py-3 text-sm font-bold text-gray-700 border-r border-gray-200 align-top">{{ $nilai->siswa->nama_lengkap }}</td>
                                    <td class="px-4 py-3 text-center text-sm text-gray-700 border-r border-gray-200 align-top">{{ $nilai->siswa->nisn }}</td>
                                    <td class="px-4 py-3 text-center text-sm text-gray-700 border-r border-gray-200 align-top">{{ $nilai->siswa->nis }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-700 border-r border-gray-200 align-top">
                                        @if($nilai->deskripsi->deskripsi_tertinggi)
                                            <p class="mb-2">{{ $nilai->deskripsi->deskripsi_tertinggi }}</p>
                                        @endif
                                        @if($nilai->deskripsi->deskripsi_terendah)
                                            <p>{{ $nilai->deskripsi->deskripsi_terendah }}</p>
                                        @endif
                                        
                                        @if(!$nilai->deskripsi->deskripsi_tertinggi && !$nilai->deskripsi->deskripsi_terendah)
                                            <span class="text-gray-400 italic">Deskripsi kosong</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-center text-sm align-top">
                                        <form action="{{ route('guru.nilai-tersimpan.deskripsi.destroy', $nilai->deskripsi->id) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus deskripsi rapor untuk siswa ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="bg-red-600 hover:bg-red-700 text-white py-1 px-3 rounded shadow transition-colors text-xs font-semibold mt-1" title="Hapus">
                                                <svg class="w-3 h-3 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                Hapus
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @endif
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-8 whitespace-nowrap text-sm text-gray-500 text-center">
                                        Belum ada nilai yang tersimpan untuk kelas dan mata pelajaran ini.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @endif

        </div>
    </div>
</x-app-layout>
