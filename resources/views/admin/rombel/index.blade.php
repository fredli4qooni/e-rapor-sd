<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Data Kelas / Rombel') }}
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
                <span>Daftar Kelas / Rombongan Belajar</span>
                <a href="{{ route('admin.rombel.create') }}" class="bg-red-600 hover:bg-red-500 text-white text-xs py-1.5 px-3 rounded shadow font-semibold">
                    + Tambah Rombel
                </a>
            </div>
            
            <div class="p-6 overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-700 border">
                    <thead class="text-xs text-white bg-gray-800 uppercase border-b">
                        <tr>
                            <th class="px-4 py-3 w-16">No</th>
                            <th class="px-4 py-3">Nama Rombel</th>
                            <th class="px-4 py-3 text-center">Tingkat</th>
                            <th class="px-4 py-3 text-center">Fase</th>
                            <th class="px-4 py-3 text-center">Jenis</th>
                            <th class="px-4 py-3">Kurikulum</th>
                            <th class="px-4 py-3">Wali Kelas</th>
                            <th class="px-4 py-3 text-center w-32">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($rombels as $index => $rombel)
                            <tr class="border-b hover:bg-gray-50">
                                <td class="px-4 py-3">{{ $index + 1 }}</td>
                                <td class="px-4 py-3 font-semibold">{{ $rombel->nama_rombel }}</td>
                                <td class="px-4 py-3 text-center">{{ $rombel->tingkat }}</td>
                                <td class="px-4 py-3 text-center">{{ $rombel->fase ?? '-' }}</td>
                                <td class="px-4 py-3 text-center">{{ $rombel->jenis_rombel }}</td>
                                <td class="px-4 py-3">{{ $rombel->kurikulum }}</td>
                                <td class="px-4 py-3">{{ $rombel->waliKelas ? $rombel->waliKelas->nama_lengkap : 'Belum Ditentukan' }}</td>
                                <td class="px-4 py-3 text-center flex gap-2 justify-center flex-wrap">
                                    <a href="{{ route('admin.rombel.show', $rombel->id) }}" class="text-green-600 hover:text-green-800 font-medium">Anggota</a>
                                    <a href="{{ route('admin.rombel.edit', $rombel->id) }}" class="text-blue-600 hover:text-blue-800 font-medium">Edit</a>
                                    <form action="{{ route('admin.rombel.destroy', $rombel->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus rombel ini?');" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800 font-medium">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-4 py-3 text-center text-gray-500">Belum ada data rombel.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</x-app-layout>
