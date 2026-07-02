<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Mapping Pembelajaran') }}
        </h2>
    </x-slot>

    <div class="py-6 space-y-6 max-w-7xl mx-auto sm:px-6 lg:px-8">
        
        <!-- Flash Message -->
        @if (session('status'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative shadow-sm" role="alert">
                <span class="block sm:inline">{{ session('status') }}</span>
            </div>
        @endif

        <!-- Filter by Rombel -->
        <div class="bg-white p-4 rounded-md shadow flex justify-between items-center">
            <form action="{{ route('admin.pembelajaran.index') }}" method="GET" class="flex items-center space-x-4">
                <label for="rombel_id" class="font-semibold text-gray-700 text-sm">Pilih Kelas / Rombel:</label>
                <select name="rombel_id" id="rombel_id" class="border-gray-300 rounded-md shadow-sm focus:border-red-500 focus:ring-red-500 text-sm" onchange="this.form.submit()">
                    <option value="">-- Semua Rombel --</option>
                    @foreach($rombels as $rombel)
                        <option value="{{ $rombel->id }}" {{ request('rombel_id') == $rombel->id ? 'selected' : '' }}>
                            {{ $rombel->nama_rombel }} (Tingkat {{ $rombel->tingkat }})
                        </option>
                    @endforeach
                </select>
            </form>
            <a href="{{ route('admin.pembelajaran.create', ['rombel_id' => request('rombel_id')]) }}" class="bg-red-600 hover:bg-red-500 text-white text-xs py-2 px-4 rounded shadow font-semibold">
                + Tambah Mapping
            </a>
        </div>

        <div class="bg-white rounded-md shadow-md overflow-hidden">
            <div class="bg-[#8B1515] text-white px-6 py-4 font-bold tracking-wider uppercase text-sm border-b border-red-800">
                Daftar Mapping Pembelajaran
            </div>
            
            <div class="p-6 overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-700 border">
                    <thead class="text-xs text-white bg-gray-800 uppercase border-b">
                        <tr>
                            <th class="px-4 py-3 w-16 text-center">No</th>
                            <th class="px-4 py-3">Kelas / Rombel</th>
                            <th class="px-4 py-3">Mata Pelajaran</th>
                            <th class="px-4 py-3">Guru Pengajar</th>
                            <th class="px-4 py-3 text-center">Status</th>
                            <th class="px-4 py-3 text-center w-32">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pembelajarans as $index => $pemb)
                            <tr class="border-b hover:bg-gray-50">
                                <td class="px-4 py-3 text-center">{{ $index + 1 }}</td>
                                <td class="px-4 py-3 font-semibold">{{ $pemb->rombel->nama_rombel }}</td>
                                <td class="px-4 py-3">{{ $pemb->mapel->nama_mapel }}</td>
                                <td class="px-4 py-3">{{ $pemb->guru->nama_lengkap }}</td>
                                <td class="px-4 py-3 text-center">
                                    @if($pemb->is_aktif)
                                        <span class="bg-green-100 text-green-800 text-xs px-2 py-1 rounded">Aktif</span>
                                    @else
                                        <span class="bg-red-100 text-red-800 text-xs px-2 py-1 rounded">Tidak Aktif</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-center flex gap-2 justify-center flex-wrap">
                                    <a href="{{ route('admin.pembelajaran.create', ['rombel_id' => $pemb->rombel_id, 'parent_mapel_id' => $pemb->mata_pelajaran_id]) }}" class="text-green-600 hover:text-green-800 font-medium whitespace-nowrap">Tambah Sub Mapel</a>
                                    <a href="{{ route('admin.pembelajaran.edit', $pemb->id) }}" class="text-blue-600 hover:text-blue-800 font-medium">Edit</a>
                                    <form action="{{ route('admin.pembelajaran.destroy', $pemb->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus mapping ini?');" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800 font-medium">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-3 text-center text-gray-500">Belum ada data mapping pembelajaran. Pilih Rombel atau tambah baru.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</x-app-layout>
