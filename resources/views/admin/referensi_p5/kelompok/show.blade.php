<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Detail Kelompok Projek') }}
        </h2>
    </x-slot>

    <div class="py-6 space-y-6 max-w-7xl mx-auto sm:px-6 lg:px-8">
        
        <!-- Flash Message -->
        @if (session('status'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative shadow-sm" role="alert">
                <span class="block sm:inline">{{ session('status') }}</span>
            </div>
        @endif
        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative shadow-sm" role="alert">
                <ul class="list-disc list-inside text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-white rounded-md shadow-md overflow-hidden mb-6">
            <div class="bg-[#8B1515] text-white px-6 py-4 font-bold tracking-wider uppercase text-sm border-b border-red-800 flex justify-between items-center">
                <span>Informasi Kelompok</span>
                <a href="{{ route('admin.referensi_p5.kelompok.index') }}" class="text-sm font-semibold text-red-100 hover:text-white">Kembali</a>
            </div>
            <div class="p-6 grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
                <div>
                    <span class="block text-gray-500 font-semibold">Nama Kelompok</span>
                    <span class="block font-bold text-gray-800">{{ $kelompok->nama_kelompok }}</span>
                </div>
                <div>
                    <span class="block text-gray-500 font-semibold">Tingkat / Fase</span>
                    <span class="block font-bold text-gray-800">Tingkat {{ $kelompok->tingkat_pendidikan ?? '-' }} / Fase {{ $kelompok->fase }}</span>
                </div>
                <div>
                    <span class="block text-gray-500 font-semibold">Koordinator</span>
                    <span class="block font-bold text-gray-800">{{ $kelompok->koordinator->name ?? '-' }}</span>
                </div>
                <div>
                    <span class="block text-gray-500 font-semibold">Total Anggota</span>
                    <span class="block font-bold text-gray-800">{{ $kelompok->siswas->count() }} Siswa</span>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Data Anggota -->
            <div class="bg-white rounded-md shadow-md overflow-hidden">
                <div class="bg-gray-800 text-white px-6 py-4 font-bold tracking-wider uppercase text-sm border-b border-gray-900">
                    Daftar Anggota Kelompok
                </div>
                <div class="p-4 bg-gray-50 border-b space-y-4">
                    <!-- Form Tambah Siswa Individual -->
                    <form action="{{ route('admin.referensi_p5.kelompok.anggota.store', $kelompok->id) }}" method="POST" class="flex gap-2">
                        @csrf
                        <select name="siswa_id" class="flex-1 border-gray-300 rounded-md shadow-sm focus:border-red-500 focus:ring-red-500 text-sm" required>
                            <option value="">-- Tambah Siswa Individual --</option>
                            @foreach($semuaSiswa as $siswa)
                                @if(!$kelompok->siswas->contains('id', $siswa->id))
                                    <option value="{{ $siswa->id }}">{{ $siswa->nama_lengkap }} (NISN: {{ $siswa->nisn }})</option>
                                @endif
                            @endforeach
                        </select>
                        <button type="submit" class="bg-blue-600 hover:bg-blue-500 text-white text-xs py-2 px-4 rounded shadow font-semibold shrink-0">
                            + Tambah
                        </button>
                    </form>

                    <!-- Form Tambah Semua Siswa dari Rombel -->
                    <form action="{{ route('admin.referensi_p5.kelompok.anggota.store', $kelompok->id) }}" method="POST" class="flex gap-2">
                        @csrf
                        <select name="rombel_id" class="flex-1 border-gray-300 rounded-md shadow-sm focus:border-red-500 focus:ring-red-500 text-sm" required>
                            <option value="">-- Tambah Semua Anggota (Dari Rombel) --</option>
                            @foreach($rombels as $rombel)
                                <option value="{{ $rombel->id }}">{{ $rombel->nama_rombel }} (Tingkat {{ $rombel->tingkat_pendidikan }})</option>
                            @endforeach
                        </select>
                        <button type="submit" class="bg-indigo-600 hover:bg-indigo-500 text-white text-xs py-2 px-4 rounded shadow font-semibold shrink-0" onclick="return confirm('Tambahkan seluruh siswa di rombel ini ke kelompok kokurikuler?');">
                            + Tambahkan Semua Anggota
                        </button>
                    </form>
                </div>
                <div class="p-0 overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-700">
                        <thead class="text-xs text-gray-600 bg-gray-100 uppercase border-b">
                            <tr>
                                <th class="px-4 py-2 w-12 text-center">No</th>
                                <th class="px-4 py-2">Nama Siswa</th>
                                <th class="px-4 py-2 text-center w-24">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($kelompok->siswas as $index => $siswa)
                                <tr class="border-b hover:bg-gray-50">
                                    <td class="px-4 py-2 text-center">{{ $index + 1 }}</td>
                                    <td class="px-4 py-2">
                                        <div class="font-semibold">{{ $siswa->nama_lengkap }}</div>
                                        <div class="text-xs text-gray-500">{{ $siswa->nisn }}</div>
                                    </td>
                                    <td class="px-4 py-2 text-center">
                                        <form action="{{ route('admin.referensi_p5.kelompok.anggota.destroy', [$kelompok->id, $siswa->id]) }}" method="POST" onsubmit="return confirm('Hapus siswa dari kelompok?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-800 font-medium text-xs">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-4 py-4 text-center text-gray-500">Belum ada anggota.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Data Data Projek -->
            <div class="bg-white rounded-md shadow-md overflow-hidden">
                <div class="bg-gray-800 text-white px-6 py-4 font-bold tracking-wider uppercase text-sm border-b border-gray-900">
                    Data Projek
                </div>
                <div class="p-4 bg-gray-50 border-b">
                    <form action="{{ route('admin.referensi_p5.kelompok.projek.store', $kelompok->id) }}" method="POST" class="flex gap-2">
                        @csrf
                        <select name="p5_proyek_id" class="flex-1 border-gray-300 rounded-md shadow-sm focus:border-red-500 focus:ring-red-500 text-sm" required>
                            <option value="">-- Pilih Projek (Fase {{ $kelompok->fase }}) --</option>
                            @foreach($semuaProjek as $projek)
                                @if(!$kelompok->proyeks->contains('id', $projek->id))
                                    <option value="{{ $projek->id }}">{{ $projek->nama_proyek }}</option>
                                @endif
                            @endforeach
                        </select>
                        <button type="submit" class="bg-green-600 hover:bg-green-500 text-white text-xs py-2 px-4 rounded shadow font-semibold shrink-0">
                            + Tambah
                        </button>
                    </form>
                </div>
                <div class="p-0 overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-700">
                        <thead class="text-xs text-gray-600 bg-gray-100 uppercase border-b">
                            <tr>
                                <th class="px-4 py-2 w-12 text-center">No</th>
                                <th class="px-4 py-2">Nama Projek</th>
                                <th class="px-4 py-2 text-center w-24">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($kelompok->proyeks as $index => $proyek)
                                <tr class="border-b hover:bg-gray-50">
                                    <td class="px-4 py-2 text-center font-bold">{{ $index + 1 }}</td>
                                    <td class="px-4 py-2 font-semibold text-gray-800">{{ $proyek->nama_proyek }}</td>
                                    <td class="px-4 py-2 text-center">
                                        <form action="{{ route('admin.referensi_p5.kelompok.projek.destroy', [$kelompok->id, $proyek->id]) }}" method="POST" onsubmit="return confirm('Hapus projek dari kelompok?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-800 font-medium text-xs">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-4 py-4 text-center text-gray-500">Belum ada projek yang dipilih.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</x-app-layout>
