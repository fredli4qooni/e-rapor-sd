<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Anggota Kelas / Rombel') }}
        </h2>
    </x-slot>

    <div class="py-6 space-y-6 max-w-7xl mx-auto sm:px-6 lg:px-8">
        
        <!-- Flash Message -->
        @if (session('status'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative shadow-sm" role="alert">
                <span class="block sm:inline">{{ session('status') }}</span>
            </div>
        @endif

        <div class="bg-white rounded-md shadow-md overflow-hidden mb-6">
            <div class="bg-[#8B1515] text-white px-6 py-4 font-bold tracking-wider uppercase text-sm border-b border-red-800 flex justify-between items-center">
                <span>Informasi Rombel</span>
                <a href="{{ route('admin.rombel.index') }}" class="text-sm font-semibold text-red-100 hover:text-white">Kembali</a>
            </div>
            <div class="p-6 grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
                <div>
                    <span class="block text-gray-500 font-semibold">Nama Rombel</span>
                    <span class="block font-bold text-gray-800">{{ $rombel->nama_rombel }}</span>
                </div>
                <div>
                    <span class="block text-gray-500 font-semibold">Tingkat / Fase</span>
                    <span class="block font-bold text-gray-800">Tingkat {{ $rombel->tingkat }} / Fase {{ $rombel->fase ?? '-' }}</span>
                </div>
                <div>
                    <span class="block text-gray-500 font-semibold">Wali Kelas</span>
                    <span class="block font-bold text-gray-800">{{ $rombel->waliKelas->nama_lengkap ?? 'Belum Ditentukan' }}</span>
                </div>
                <div>
                    <span class="block text-gray-500 font-semibold">Total Anggota</span>
                    <span class="block font-bold text-gray-800">{{ $rombel->siswas->count() }} Siswa</span>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-md shadow-md overflow-hidden">
            <div class="bg-gray-800 text-white px-6 py-4 font-bold tracking-wider uppercase text-sm border-b border-gray-900">
                Daftar Anggota Rombel
            </div>
            
            <div class="p-0 overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-700 border">
                    <thead class="text-xs text-gray-600 bg-gray-100 uppercase border-b">
                        <tr>
                            <th class="px-4 py-3 w-16 text-center">No</th>
                            <th class="px-4 py-3">Nama Siswa</th>
                            <th class="px-4 py-3">NISN / NIS</th>
                            <th class="px-4 py-3 text-center">L/P</th>
                            <th class="px-4 py-3 text-center w-32">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($rombel->siswas as $index => $siswa)
                            <tr class="border-b hover:bg-gray-50">
                                <td class="px-4 py-3 text-center">{{ $index + 1 }}</td>
                                <td class="px-4 py-3 font-semibold">{{ $siswa->nama_lengkap }}</td>
                                <td class="px-4 py-3">
                                    {{ $siswa->nisn }}
                                    @if($siswa->nis)
                                        <span class="text-gray-500 text-xs block">NIS: {{ $siswa->nis }}</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-center">{{ $siswa->jenis_kelamin }}</td>
                                <td class="px-4 py-3 text-center">
                                    <form action="{{ route('admin.rombel.anggota.destroy', [$rombel->id, $siswa->id]) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus siswa ini dari rombel? (Data siswa tetap ada di database)');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800 font-medium text-sm">Hapus dari Rombel</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-4 text-center text-gray-500">Belum ada anggota di rombel ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</x-app-layout>
