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

        <div class="bg-white rounded-md shadow-md overflow-hidden mb-6">
            <div class="bg-gray-800 text-white px-6 py-4 font-bold tracking-wider uppercase text-sm border-b border-gray-900">
                Tambah Anggota Rombel
            </div>
            <div class="p-6">
                <form action="{{ route('admin.rombel.anggota.store', $rombel->id) }}" method="POST">
                    @csrf
                    <div x-data="{ 
                            search: '', 
                            jenisKelamin: '',
                            selectAll: false,
                            toggleAll() {
                                let checkboxes = document.querySelectorAll('.siswa-checkbox');
                                checkboxes.forEach(cb => {
                                    if(cb.closest('label').style.display !== 'none') {
                                        cb.checked = this.selectAll;
                                    }
                                });
                            }
                        }">
                        
                        <div class="mb-3 flex flex-col md:flex-row justify-between items-start md:items-center border-b border-gray-200 pb-3 gap-3">
                            <div>
                                <label class="block text-sm font-bold text-gray-700">Daftar Siswa yang Tersedia</label>
                                <span class="text-xs text-gray-500 font-medium bg-gray-100 px-2 py-1 rounded">Centang kotak untuk memilih</span>
                            </div>
                            <div class="flex flex-col sm:flex-row gap-2 w-full md:w-auto">
                                <select x-model="jenisKelamin" class="text-sm rounded-md shadow-sm border-gray-300 focus:border-red-300 focus:ring focus:ring-red-200 focus:ring-opacity-50">
                                    <option value="">Semua L/P</option>
                                    <option value="L">Laki-laki (L)</option>
                                    <option value="P">Perempuan (P)</option>
                                </select>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-4 w-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <input type="text" x-model="search" placeholder="Cari nama / NISN..." class="pl-9 text-sm rounded-md shadow-sm border-gray-300 focus:border-red-300 focus:ring focus:ring-red-200 focus:ring-opacity-50 w-full md:w-64">
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-2 px-1 flex justify-between items-center">
                            <label class="inline-flex items-center cursor-pointer hover:bg-gray-50 px-2 py-1 rounded transition-colors">
                                <input type="checkbox" x-model="selectAll" @change="toggleAll()" class="rounded border-gray-300 text-red-600 shadow-sm focus:ring-red-500 w-4 h-4">
                                <span class="ml-2 text-sm font-bold text-gray-700">Pilih Semua Tampil</span>
                            </label>
                            <span class="text-xs text-gray-500" x-text="(search || jenisKelamin) ? 'Hasil pencarian ditampilkan' : 'Semua siswa ditampilkan'"></span>
                        </div>
                        
                        <div class="border border-gray-200 rounded-md shadow-inner bg-gray-50 overflow-hidden mb-4">
                            <div class="max-h-72 overflow-y-auto p-2 space-y-1">
                                @forelse($availableSiswas as $siswa)
                                    <label class="flex items-center p-2 hover:bg-red-50 bg-white rounded border border-gray-100 cursor-pointer transition-colors"
                                           x-show="(search === '' || '{{ strtolower($siswa->nama_lengkap) }}'.includes(search.toLowerCase()) || '{{ $siswa->nisn }}'.includes(search)) && (jenisKelamin === '' || '{{ $siswa->jenis_kelamin }}' === jenisKelamin)">
                                        <input type="checkbox" name="siswa_ids[]" value="{{ $siswa->id }}" class="siswa-checkbox rounded border-gray-300 text-red-600 shadow-sm focus:ring-red-500 w-5 h-5 ml-1">
                                        <div class="ml-3 flex-1">
                                            <span class="block text-sm font-bold text-gray-800">{{ $siswa->nama_lengkap }}</span>
                                            <span class="block text-xs text-gray-500">NISN: {{ $siswa->nisn }} &nbsp;&bull;&nbsp; L/P: {{ $siswa->jenis_kelamin }}</span>
                                        </div>
                                    </label>
                                @empty
                                    <div class="p-6 text-center text-sm text-gray-500 italic bg-white rounded">
                                        Semua siswa sudah masuk ke kelas ini atau data siswa kosong.
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex justify-end">
                        <button type="submit" class="bg-red-800 hover:bg-red-700 text-white font-bold py-2 px-6 rounded shadow w-full md:w-auto flex items-center justify-center gap-2 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                            </svg>
                            Tambahkan Siswa Terpilih
                        </button>
                    </div>
                </form>
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
