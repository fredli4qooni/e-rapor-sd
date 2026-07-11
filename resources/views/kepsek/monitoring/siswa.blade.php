<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Monitoring Data Siswa') }}
        </h2>
    </x-slot>

    <div class="py-6 space-y-6 max-w-7xl mx-auto sm:px-6 lg:px-8">
        
        <div class="bg-white rounded-md shadow-md overflow-hidden">
            <div class="bg-[#8B1515] text-white px-6 py-4 font-bold tracking-wider uppercase text-sm border-b border-red-800 flex justify-between items-center">
                <span>Daftar Siswa</span>
                <form action="{{ route('kepsek.monitoring.siswa') }}" method="GET" class="flex">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari Nama / NISN..." class="text-sm text-gray-800 rounded-l border-none px-3 py-1.5 focus:ring-0">
                    <button type="submit" class="bg-gray-800 hover:bg-gray-700 px-3 py-1.5 rounded-r">Cari</button>
                </form>
            </div>
            
            <div class="p-6 overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-700 border">
                    <thead class="text-xs text-white bg-gray-800 uppercase border-b">
                        <tr>
                            <th class="px-4 py-3 w-16">No</th>
                            <th class="px-4 py-3">Nama Lengkap</th>
                            <th class="px-4 py-3">NISN</th>
                            <th class="px-4 py-3">L/P</th>
                            <th class="px-4 py-3">Kelas Saat Ini</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($siswas as $index => $siswa)
                            <tr class="border-b hover:bg-gray-50">
                                <td class="px-4 py-3">{{ $siswas->firstItem() + $index }}</td>
                                <td class="px-4 py-3 font-semibold">{{ $siswa->nama_lengkap }}</td>
                                <td class="px-4 py-3">{{ $siswa->nisn ?? '-' }}</td>
                                <td class="px-4 py-3">{{ $siswa->jenis_kelamin }}</td>
                                <td class="px-4 py-3">
                                    @php
                                        $rombel = $siswa->rombels->first();
                                    @endphp
                                    {{ $rombel ? $rombel->nama_rombel : 'Belum Masuk Rombel' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-3 text-center text-gray-500">Belum ada data siswa atau tidak ditemukan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="mt-4">
                    {{ $siswas->links() }}
                </div>
            </div>
        </div>

    </div>
</x-app-layout>
