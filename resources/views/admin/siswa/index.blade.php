<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Data Siswa') }}
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
                <span>Daftar Siswa</span>
                <div class="flex gap-2">
                    <form action="{{ route('admin.siswa.generate-user') }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin meng-generate akun User untuk Siswa yang belum memiliki akun?');">
                        @csrf
                        <button type="submit" class="bg-yellow-500 hover:bg-yellow-400 text-white text-xs py-1.5 px-3 rounded shadow font-semibold">
                            Generate User
                        </button>
                    </form>
                    <a href="{{ route('admin.siswa.create') }}" class="bg-red-600 hover:bg-red-500 text-white text-xs py-1.5 px-3 rounded shadow font-semibold">
                        + Tambah Siswa
                    </a>
                </div>
            </div>
            
            <div class="p-6 overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-700 border">
                    <thead class="text-xs text-white bg-gray-800 uppercase border-b">
                        <tr>
                            <th class="px-4 py-3 w-16">No</th>
                            <th class="px-4 py-3">NISN</th>
                            <th class="px-4 py-3">Nama Lengkap</th>
                            <th class="px-4 py-3 w-16 text-center">L/P</th>
                            <th class="px-4 py-3">Status Akun</th>
                            <th class="px-4 py-3 text-center w-32">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($siswas as $index => $siswa)
                            <tr class="border-b hover:bg-gray-50">
                                <td class="px-4 py-3">{{ $index + 1 }}</td>
                                <td class="px-4 py-3 font-medium">{{ $siswa->nisn }}</td>
                                <td class="px-4 py-3 font-semibold">{{ $siswa->nama_lengkap }}</td>
                                <td class="px-4 py-3 text-center">{{ $siswa->jenis_kelamin }}</td>
                                <td class="px-4 py-3">
                                    @if($siswa->user_id)
                                        <span class="bg-green-100 text-green-800 text-xs px-2 py-1 rounded">Terhubung ({{ $siswa->user->username }})</span>
                                    @else
                                        <span class="bg-red-100 text-red-800 text-xs px-2 py-1 rounded">Belum Terhubung</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-center flex gap-2 justify-center">
                                    <a href="{{ route('admin.siswa.edit', $siswa->id) }}" class="text-blue-600 hover:text-blue-800 font-medium">Edit</a>
                                    <form action="{{ route('admin.siswa.destroy', $siswa->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus siswa ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800 font-medium">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-3 text-center text-gray-500">Belum ada data siswa.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</x-app-layout>
