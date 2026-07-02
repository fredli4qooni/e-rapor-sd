<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Daftar Kegiatan Kokurikuler') }}
        </h2>
    </x-slot>

    <div class="py-6 space-y-6 max-w-7xl mx-auto sm:px-6 lg:px-8">
        
        <!-- Flash Message -->
        @if (session('status'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative shadow-sm" role="alert">
                <span class="block sm:inline">{{ session('status') }}</span>
            </div>
        @endif

        <!-- Filter -->
        <div class="bg-white p-4 rounded-md shadow flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <form action="{{ route('admin.p5.proyek.index') }}" method="GET" class="flex flex-col md:flex-row items-start md:items-center gap-4 w-full md:w-auto">
                <div class="flex items-center space-x-2">
                    <label for="p5_tema_id" class="font-semibold text-gray-700 text-sm">Tema:</label>
                    <select name="p5_tema_id" id="p5_tema_id" class="border-gray-300 rounded-md shadow-sm focus:border-red-500 focus:ring-red-500 text-sm" onchange="this.form.submit()">
                        <option value="">-- Semua Tema --</option>
                        @foreach($temas as $tema)
                            <option value="{{ $tema->id }}" {{ request('p5_tema_id') == $tema->id ? 'selected' : '' }}>{{ $tema->nama_tema }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex items-center space-x-2">
                    <label for="fase" class="font-semibold text-gray-700 text-sm">Fase:</label>
                    <select name="fase" id="fase" class="border-gray-300 rounded-md shadow-sm focus:border-red-500 focus:ring-red-500 text-sm" onchange="this.form.submit()">
                        <option value="">-- Semua Fase --</option>
                        <option value="A" {{ request('fase') == 'A' ? 'selected' : '' }}>Fase A</option>
                        <option value="B" {{ request('fase') == 'B' ? 'selected' : '' }}>Fase B</option>
                        <option value="C" {{ request('fase') == 'C' ? 'selected' : '' }}>Fase C</option>
                    </select>
                </div>
            </form>
            <a href="{{ route('admin.p5.proyek.create', ['fase' => request('fase'), 'p5_tema_id' => request('p5_tema_id')]) }}" class="bg-red-600 hover:bg-red-500 text-white text-xs py-2 px-4 rounded shadow font-semibold shrink-0">
                + Tambah Kegiatan
            </a>
        </div>

        <div class="bg-white rounded-md shadow-md overflow-hidden">
            <div class="bg-[#8B1515] text-white px-6 py-4 font-bold tracking-wider uppercase text-sm border-b border-red-800">
                Daftar Kegiatan Kokurikuler
            </div>
            
            <div class="p-6 overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-700 border">
                    <thead class="text-xs text-white bg-gray-800 uppercase border-b">
                        <tr>
                            <th class="px-4 py-3 w-16 text-center">No Urut</th>
                            <th class="px-4 py-3">Nama Kegiatan</th>
                            <th class="px-4 py-3">Tema</th>
                            <th class="px-4 py-3 text-center">Fase</th>
                            <th class="px-4 py-3 text-center">Profil Lulusan</th>
                            <th class="px-4 py-3 text-center w-32">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($proyeks as $proyek)
                            <tr class="border-b hover:bg-gray-50">
                                <td class="px-4 py-3 text-center font-bold">{{ $proyek->no_urut }}</td>
                                <td class="px-4 py-3">
                                    <div class="font-semibold">{{ $proyek->nama_proyek }}</div>
                                    <div class="text-xs text-gray-500 mt-1">{{ $proyek->deskripsi }}</div>
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-600">{{ $proyek->tema->nama_tema }}</td>
                                <td class="px-4 py-3 text-center">
                                    <span class="bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded">Fase {{ $proyek->fase }}</span>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <span class="bg-gray-100 text-gray-800 text-xs px-2 py-1 rounded">{{ $proyek->targetSubElemens->count() }} Sub-Elemen</span>
                                </td>
                                <td class="px-4 py-3 text-center flex gap-2 justify-center">
                                    <a href="{{ route('admin.p5.proyek.edit', $proyek->id) }}" class="text-blue-600 hover:text-blue-800 font-medium">Edit</a>
                                    <form action="{{ route('admin.p5.proyek.destroy', $proyek->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus kegiatan ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800 font-medium">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-3 text-center text-gray-500">Belum ada data kegiatan kokurikuler.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</x-app-layout>
