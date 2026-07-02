<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Mapping Mata Pelajaran Transkrip Ijazah') }}
        </h2>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8">
        
        @if(session('success'))
            <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        @if($errors->any())
            <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>- {{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Filter -->
        <div class="bg-white rounded-md shadow-md overflow-hidden mb-6">
            <div class="p-4 border-b">
                <form action="{{ route('admin.transkrip_ijazah.mapping_mapel.index') }}" method="GET" class="flex flex-col sm:flex-row gap-4 items-end">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Kurikulum</label>
                        <select name="kurikulum" class="border-gray-300 rounded-md shadow-sm focus:border-red-500 focus:ring-red-500 text-sm">
                            <option value="Merdeka" {{ $kurikulum == 'Merdeka' ? 'selected' : '' }}>Kurikulum Merdeka</option>
                            <option value="2013" {{ $kurikulum == '2013' ? 'selected' : '' }}>Kurikulum 2013</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tingkat</label>
                        <select name="tingkat" class="border-gray-300 rounded-md shadow-sm focus:border-red-500 focus:ring-red-500 text-sm">
                            <option value="6" {{ $tingkat == 6 ? 'selected' : '' }}>Kelas 6</option>
                            <!-- Can add other grades if necessary later -->
                        </select>
                    </div>
                    <div>
                        <button type="submit" class="bg-gray-800 text-white px-4 py-2 rounded-md hover:bg-gray-700 transition font-medium text-sm">Filter Mapping</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Form Tambah Mapping -->
            <div class="md:col-span-1 bg-white rounded-md shadow-md overflow-hidden self-start">
                <div class="bg-[#8B1515] text-white px-6 py-4 font-bold tracking-wider uppercase text-sm border-b border-red-800">
                    Tambah / Edit Mapping
                </div>
                <div class="p-6">
                    <form action="{{ route('admin.transkrip_ijazah.mapping_mapel.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="kurikulum" value="{{ $kurikulum }}">
                        <input type="hidden" name="tingkat" value="{{ $tingkat }}">

                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Mata Pelajaran (Master)</label>
                                <select name="mata_pelajaran_id" class="w-full border-gray-300 rounded-md shadow-sm focus:border-red-500 focus:ring-red-500 text-sm" required>
                                    <option value="">Pilih Mata Pelajaran...</option>
                                    @foreach($mapels as $mapel)
                                        <option value="{{ $mapel->id }}">{{ $mapel->nama_mapel }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lokal di Transkrip (Opsional)</label>
                                <input type="text" name="nama_lokal" placeholder="Kosongkan jika sama dgn master" class="w-full border-gray-300 rounded-md shadow-sm focus:border-red-500 focus:ring-red-500 text-sm">
                                <span class="text-xs text-gray-500">Contoh: Pendidikan Agama dan Budi Pekerti</span>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Kelompok (A/B/C)</label>
                                <input type="text" name="kelompok" class="w-full border-gray-300 rounded-md shadow-sm focus:border-red-500 focus:ring-red-500 text-sm">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Nomor Urut</label>
                                <input type="number" name="no_urut" value="1" min="1" class="w-full border-gray-300 rounded-md shadow-sm focus:border-red-500 focus:ring-red-500 text-sm" required>
                            </div>

                            <button type="submit" class="w-full bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition font-medium text-sm mt-2 shadow-sm">
                                Simpan Mapping
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Tabel Daftar Mapping -->
            <div class="md:col-span-2 bg-white rounded-md shadow-md overflow-hidden">
                <div class="bg-gray-800 text-white px-6 py-4 font-bold tracking-wider uppercase text-sm border-b border-gray-900">
                    Daftar Mapping Transkrip - Kelas {{ $tingkat }} ({{ $kurikulum }})
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No Urut</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kelompok</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mata Pelajaran (Tampil)</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($mappings as $map)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-medium text-center">
                                    {{ $map->no_urut }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-center font-semibold">
                                    {{ $map->kelompok ?: '-' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $map->nama_lokal ?: $map->mapel->nama_mapel }}
                                    @if($map->nama_lokal)
                                        <div class="text-xs text-gray-500">Asli: {{ $map->mapel->nama_mapel }}</div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-center">
                                    <form action="{{ route('admin.transkrip_ijazah.mapping_mapel.destroy', $map->id) }}" method="POST" onsubmit="return confirm('Hapus mapping ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900 bg-red-100 hover:bg-red-200 px-2 py-1 rounded transition">
                                            Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-6 py-8 text-center text-gray-500 text-sm">
                                    Belum ada mapping mata pelajaran untuk Tingkat dan Kurikulum ini.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</x-app-layout>
