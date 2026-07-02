<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Anggota Ekstrakurikuler') }}
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
                <span>Informasi Ekstrakurikuler</span>
                <a href="{{ route('admin.ekskul.index') }}" class="text-sm font-semibold text-red-100 hover:text-white">Kembali</a>
            </div>
            <div class="p-6 grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
                <div>
                    <span class="block text-gray-500 font-semibold">Nama Ekstrakurikuler</span>
                    <span class="block font-bold text-gray-800">{{ $ekskul->nama_ekskul }}</span>
                </div>
                <div>
                    <span class="block text-gray-500 font-semibold">Status</span>
                    <span class="block font-bold text-gray-800">{{ $ekskul->is_aktif ? 'Aktif' : 'Tidak Aktif' }}</span>
                </div>
                <div>
                    <span class="block text-gray-500 font-semibold">Guru Pembina</span>
                    <span class="block font-bold text-gray-800">{{ $ekskul->pembina->nama_lengkap ?? 'Belum Ditentukan' }}</span>
                </div>
                <div>
                    <span class="block text-gray-500 font-semibold">Total Anggota</span>
                    <span class="block font-bold text-gray-800">{{ $nilais->count() }} Siswa</span>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-md shadow-md overflow-hidden mb-6">
            <div class="bg-blue-800 text-white px-6 py-4 font-bold tracking-wider uppercase text-sm border-b border-blue-900">
                Tambah Anggota dari Rombel
            </div>
            <form action="{{ route('admin.ekskul.anggota.store', $ekskul->id) }}" method="POST" class="p-6 flex items-end gap-4">
                @csrf
                <div class="flex-1">
                    <x-input-label for="rombel_id" :value="__('Pilih Kelas / Rombel')" class="text-gray-700 font-semibold" />
                    <select id="rombel_id" name="rombel_id" class="mt-1 block w-full border-gray-300 focus:border-red-500 focus:ring-red-500 rounded-md shadow-sm" required>
                        <option value="">-- Pilih Rombel --</option>
                        @foreach($rombels as $rombel)
                            <option value="{{ $rombel->id }}">
                                {{ $rombel->nama_rombel }} (Tingkat {{ $rombel->tingkat }})
                            </option>
                        @endforeach
                    </select>
                    <x-input-error class="mt-2" :messages="$errors->get('rombel_id')" />
                </div>
                <div class="w-auto">
                    <x-primary-button class="bg-blue-600 hover:bg-blue-500 py-3">
                        {{ __('Tambahkan Seluruh Siswa Rombel Ini') }}
                    </x-primary-button>
                </div>
            </form>
        </div>

        <div class="bg-white rounded-md shadow-md overflow-hidden">
            <div class="bg-gray-800 text-white px-6 py-4 font-bold tracking-wider uppercase text-sm border-b border-gray-900">
                Daftar Anggota Ekstrakurikuler
            </div>
            
            <div class="p-0 overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-700 border">
                    <thead class="text-xs text-gray-600 bg-gray-100 uppercase border-b">
                        <tr>
                            <th class="px-4 py-3 w-16 text-center">No</th>
                            <th class="px-4 py-3">Nama Siswa</th>
                            <th class="px-4 py-3">NISN / NIS</th>
                            <th class="px-4 py-3">Asal Rombel</th>
                            <th class="px-4 py-3 text-center w-32">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($nilais as $index => $nilai)
                            <tr class="border-b hover:bg-gray-50">
                                <td class="px-4 py-3 text-center">{{ $index + 1 }}</td>
                                <td class="px-4 py-3 font-semibold">{{ $nilai->siswa->nama_lengkap }}</td>
                                <td class="px-4 py-3">
                                    {{ $nilai->siswa->nisn }}
                                    @if($nilai->siswa->nis)
                                        <span class="text-gray-500 text-xs block">NIS: {{ $nilai->siswa->nis }}</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3">{{ $nilai->rombel->nama_rombel }}</td>
                                <td class="px-4 py-3 text-center">
                                    <form action="{{ route('admin.ekskul.anggota.destroy', [$ekskul->id, $nilai->id]) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus siswa ini dari anggota ekstrakurikuler?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800 font-medium text-sm">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-4 text-center text-gray-500">Belum ada anggota di ekstrakurikuler ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</x-app-layout>
