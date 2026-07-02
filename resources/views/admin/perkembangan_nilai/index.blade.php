<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Perkembangan Nilai Siswa') }}
        </h2>
    </x-slot>

    <div class="py-6 space-y-6 max-w-7xl mx-auto sm:px-6 lg:px-8">
        
        <!-- Filter Kelas -->
        <div class="bg-white p-4 rounded-md shadow flex justify-between items-center">
            <form action="{{ route('admin.perkembangan_nilai.index') }}" method="GET" class="flex items-center space-x-2">
                <label for="rombel_id" class="font-semibold text-gray-700 text-sm">Pilih Kelas:</label>
                <select name="rombel_id" id="rombel_id" class="border-gray-300 rounded-md shadow-sm focus:border-red-500 focus:ring-red-500 text-sm" onchange="this.form.submit()">
                    <option value="">-- Pilih Kelas --</option>
                    @foreach($rombels as $rombel)
                        <option value="{{ $rombel->id }}" {{ request('rombel_id') == $rombel->id ? 'selected' : '' }}>{{ $rombel->nama_rombel }}</option>
                    @endforeach
                </select>
            </form>
        </div>

        @if($selectedRombelId)
        <div class="bg-white rounded-md shadow-md overflow-hidden">
            <div class="bg-[#8B1515] text-white px-6 py-4 font-bold tracking-wider uppercase text-sm border-b border-red-800 flex justify-between items-center">
                <span>Daftar Siswa</span>
                <span class="bg-white text-red-800 text-xs px-2 py-1 rounded-full font-bold">{{ count($siswas) }} Siswa</span>
            </div>
            
            <div class="p-0 overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-700">
                    <thead class="text-xs text-gray-700 bg-gray-100 uppercase border-b">
                        <tr>
                            <th class="px-4 py-3 w-16 text-center">No</th>
                            <th class="px-4 py-3">NISN / NIS</th>
                            <th class="px-4 py-3">Nama Siswa</th>
                            <th class="px-4 py-3 text-center">L/P</th>
                            <th class="px-4 py-3 text-center">Ketuntasan Rapor</th>
                            <th class="px-4 py-3 text-center">Deskripsi Rapor</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($siswas as $index => $siswa)
                            <tr class="border-b hover:bg-gray-50">
                                <td class="px-4 py-3 text-center font-bold">{{ $index + 1 }}</td>
                                <td class="px-4 py-3 font-mono text-xs">{{ $siswa->nisn }}<br><span class="text-gray-500">{{ $siswa->nis }}</span></td>
                                <td class="px-4 py-3 font-semibold">{{ $siswa->nama_lengkap }}</td>
                                <td class="px-4 py-3 text-center">{{ $siswa->jenis_kelamin }}</td>
                                <td class="px-4 py-3 text-center">
                                    <a href="{{ route('admin.perkembangan_nilai.capaian', $siswa->id) }}" class="inline-flex items-center px-3 py-1.5 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition shadow-sm">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                                        Cek Capaian Nilai
                                    </a>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <a href="{{ route('admin.perkembangan_nilai.deskripsi', $siswa->id) }}" class="inline-flex items-center px-3 py-1.5 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition shadow-sm">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"></path></svg>
                                        Cek Deskripsi Nilai
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-3 text-center text-gray-500">Belum ada siswa di kelas ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @else
        <div class="bg-white p-8 rounded-md shadow text-center text-gray-500 border border-gray-200">
            <svg class="mx-auto h-12 w-12 text-gray-400 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
            </svg>
            <p class="text-lg font-medium text-gray-900">Pilih Kelas</p>
            <p class="mt-1 text-sm text-gray-500">Silakan pilih kelas terlebih dahulu untuk melihat daftar siswa.</p>
        </div>
        @endif

    </div>
</x-app-layout>
