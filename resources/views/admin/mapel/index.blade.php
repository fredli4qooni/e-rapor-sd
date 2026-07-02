<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Data Mata Pelajaran') }}
        </h2>
    </x-slot>

    <div class="py-6 space-y-6 max-w-7xl mx-auto sm:px-6 lg:px-8">
        
        <!-- Flash Message -->
        @if (session('status'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative shadow-sm" role="alert">
                <span class="block sm:inline">{{ session('status') }}</span>
            </div>
        @endif

        <div class="bg-white rounded-md shadow-md overflow-hidden">
            <div class="bg-[#8B1515] text-white px-6 py-4 font-bold tracking-wider uppercase text-sm border-b border-red-800 flex justify-between items-center">
                <span>Daftar Mata Pelajaran</span>
                <a href="{{ route('admin.mapel.create') }}" class="bg-red-600 hover:bg-red-500 text-white text-xs py-1.5 px-3 rounded shadow font-semibold">
                    + Tambah Mapel
                </a>
            </div>
            
            <div class="p-6 overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-700 border">
                    <thead class="text-xs text-white bg-gray-800 uppercase border-b">
                        <tr>
                            <th class="px-4 py-3 w-16">No</th>
                            <th class="px-4 py-3">Nama Mapel</th>
                            <th class="px-4 py-3">Singkatan</th>
                            <th class="px-4 py-3 text-center">Tampil di Transkrip</th>
                            <th class="px-4 py-3 text-center">Muatan Lokal</th>
                            <th class="px-4 py-3 text-center w-32">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($mapels as $index => $mapel)
                            <tr class="border-b hover:bg-gray-50">
                                <td class="px-4 py-3">{{ $index + 1 }}</td>
                                <td class="px-4 py-3 font-semibold">{{ $mapel->nama_mapel }}</td>
                                <td class="px-4 py-3">{{ $mapel->nama_singkat ?? '-' }}</td>
                                <td class="px-4 py-3 text-center">
                                    @if($mapel->is_transkrip)
                                        <span class="text-green-600 font-bold">Ya</span>
                                    @else
                                        <span class="text-red-600 font-bold">Tidak</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-center">
                                    @if($mapel->is_lokal)
                                        <span class="text-blue-600 font-bold">Ya</span>
                                    @else
                                        <span class="text-gray-500">Tidak</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-center flex gap-2 justify-center">
                                    <a href="{{ route('admin.mapel.edit', $mapel->id) }}" class="text-blue-600 hover:text-blue-800 font-medium">Edit</a>
                                    <form action="{{ route('admin.mapel.destroy', $mapel->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus mapel ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800 font-medium">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-3 text-center text-gray-500">Belum ada data mata pelajaran.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</x-app-layout>
