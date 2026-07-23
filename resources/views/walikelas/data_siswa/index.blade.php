<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight uppercase">
            {{ __('Update Data Siswa') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-t-4 border-red-800">
                <div class="p-6">
                    <h3 class="text-lg font-bold text-gray-800 uppercase mb-4 border-b pb-2">DATA SISWA KELAS {{ strtoupper($rombel->nama_rombel) }}</h3>
                    
                    @if(session('success'))
                        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif

                    <div class="overflow-x-auto mt-4">
                        <table class="min-w-full divide-y divide-gray-200 border border-gray-300">
                            <thead class="bg-red-900 text-white">
                                <tr>
                                    <th scope="col" class="px-4 py-3 text-center text-xs font-bold tracking-wider w-12 border border-red-800">No</th>
                                    <th scope="col" class="px-4 py-3 text-left text-xs font-bold tracking-wider border border-red-800">Nama Siswa</th>
                                    <th scope="col" class="px-4 py-3 text-center text-xs font-bold tracking-wider border border-red-800">NIS</th>
                                    <th scope="col" class="px-4 py-3 text-center text-xs font-bold tracking-wider border border-red-800">NISN</th>
                                    <th scope="col" class="px-4 py-3 text-center text-xs font-bold tracking-wider border border-red-800 w-12">JK</th>
                                    <th scope="col" class="px-4 py-3 text-left text-xs font-bold tracking-wider border border-red-800">TTL</th>
                                    <th scope="col" class="px-4 py-3 text-center text-xs font-bold tracking-wider border border-red-800">Agama</th>
                                    <th scope="col" class="px-4 py-3 text-center text-xs font-bold tracking-wider border border-red-800">Tingkat</th>
                                    <th scope="col" class="px-4 py-3 text-center text-xs font-bold tracking-wider border border-red-800">Kelas</th>
                                    <th scope="col" class="px-4 py-3 text-center text-xs font-bold tracking-wider w-32 border border-red-800">Edit</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200" x-data="{ editing: null }">
                                @forelse($siswas as $index => $siswa)
                                    <!-- View Row -->
                                    <tr class="hover:bg-red-50/50 transition-colors {{ $index % 2 == 0 ? 'bg-red-50/20' : 'bg-white' }}" x-show="editing !== {{ $siswa->id }}">
                                        <td class="px-4 py-3 text-sm text-center text-gray-700 border border-gray-300 align-top">{{ $index + 1 }}</td>
                                        <td class="px-4 py-3 text-sm font-medium text-red-900 border border-gray-300 align-top uppercase">{{ $siswa->nama_lengkap }}</td>
                                        <td class="px-4 py-3 text-sm text-center text-gray-700 border border-gray-300 align-top">{{ $siswa->nis ?? '-' }}</td>
                                        <td class="px-4 py-3 text-sm text-center text-gray-700 border border-gray-300 align-top">{{ $siswa->nisn }}</td>
                                        <td class="px-4 py-3 text-sm text-center text-gray-700 border border-gray-300 align-top">{{ $siswa->jenis_kelamin }}</td>
                                        <td class="px-4 py-3 text-sm text-left text-gray-700 border border-gray-300 align-top uppercase">{{ $siswa->tempat_lahir ?? '-' }}, {{ $siswa->tanggal_lahir ? \Carbon\Carbon::parse($siswa->tanggal_lahir)->format('d-m-Y') : '-' }}</td>
                                        <td class="px-4 py-3 text-sm text-center text-gray-700 border border-gray-300 align-top capitalize">{{ $siswa->agama ?? 'Islam' }}</td>
                                        <td class="px-4 py-3 text-sm text-center text-gray-700 border border-gray-300 align-top">{{ $rombel->tingkat }}</td>
                                        <td class="px-4 py-3 text-sm text-center text-gray-700 border border-gray-300 align-top uppercase">{{ $rombel->tingkat . ' ' . $rombel->nama_rombel }}</td>
                                        <td class="px-4 py-3 text-sm text-center border border-gray-300 align-top">
                                            <button @click="editing = {{ $siswa->id }}" class="bg-red-900 hover:bg-red-800 text-white text-xs font-bold py-1 px-3 rounded shadow transition-colors flex items-center justify-center gap-1 w-full mx-auto max-w-[80px]">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                                Edit
                                            </button>
                                        </td>
                                    </tr>
                                    
                                    <!-- Edit Row -->
                                    <tr x-show="editing === {{ $siswa->id }}" style="display: none;" class="bg-yellow-50 border-2 border-yellow-400">
                                        <td colspan="10" class="p-4">
                                            <form action="{{ route('walikelas.data_siswa.update', $siswa->id) }}" method="POST" class="space-y-4">
                                                @csrf
                                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                                    <div>
                                                        <label class="block text-xs font-bold text-gray-700 mb-1">Nama Lengkap</label>
                                                        <input type="text" name="nama_lengkap" value="{{ $siswa->nama_lengkap }}" class="w-full rounded border-gray-300 text-sm focus:border-red-500 focus:ring-red-500" required>
                                                    </div>
                                                    <div>
                                                        <label class="block text-xs font-bold text-gray-700 mb-1">NISN</label>
                                                        <input type="text" name="nisn" value="{{ $siswa->nisn }}" class="w-full rounded border-gray-300 text-sm focus:border-red-500 focus:ring-red-500" required>
                                                    </div>
                                                    <div>
                                                        <label class="block text-xs font-bold text-gray-700 mb-1">NIS</label>
                                                        <input type="text" name="nis" value="{{ $siswa->nis }}" class="w-full rounded border-gray-300 text-sm focus:border-red-500 focus:ring-red-500">
                                                    </div>
                                                    <div>
                                                        <label class="block text-xs font-bold text-gray-700 mb-1">Jenis Kelamin</label>
                                                        <select name="jenis_kelamin" class="w-full rounded border-gray-300 text-sm focus:border-red-500 focus:ring-red-500">
                                                            <option value="L" {{ $siswa->jenis_kelamin == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                                            <option value="P" {{ $siswa->jenis_kelamin == 'P' ? 'selected' : '' }}>Perempuan</option>
                                                        </select>
                                                    </div>
                                                    <div>
                                                        <label class="block text-xs font-bold text-gray-700 mb-1">Tempat Lahir</label>
                                                        <input type="text" name="tempat_lahir" value="{{ $siswa->tempat_lahir }}" class="w-full rounded border-gray-300 text-sm focus:border-red-500 focus:ring-red-500">
                                                    </div>
                                                    <div>
                                                        <label class="block text-xs font-bold text-gray-700 mb-1">Tanggal Lahir</label>
                                                        <input type="date" name="tanggal_lahir" value="{{ $siswa->tanggal_lahir }}" class="w-full rounded border-gray-300 text-sm focus:border-red-500 focus:ring-red-500">
                                                    </div>
                                                    <div>
                                                        <label class="block text-xs font-bold text-gray-700 mb-1">Nama Ayah</label>
                                                        <input type="text" name="nama_ayah" value="{{ $siswa->nama_ayah }}" class="w-full rounded border-gray-300 text-sm focus:border-red-500 focus:ring-red-500">
                                                    </div>
                                                    <div>
                                                        <label class="block text-xs font-bold text-gray-700 mb-1">Pekerjaan Ayah</label>
                                                        <input type="text" name="pekerjaan_ayah" value="{{ $siswa->pekerjaan_ayah }}" class="w-full rounded border-gray-300 text-sm focus:border-red-500 focus:ring-red-500">
                                                    </div>
                                                    <div>
                                                        <label class="block text-xs font-bold text-gray-700 mb-1">Nama Ibu</label>
                                                        <input type="text" name="nama_ibu" value="{{ $siswa->nama_ibu }}" class="w-full rounded border-gray-300 text-sm focus:border-red-500 focus:ring-red-500">
                                                    </div>
                                                    <div>
                                                        <label class="block text-xs font-bold text-gray-700 mb-1">Pekerjaan Ibu</label>
                                                        <input type="text" name="pekerjaan_ibu" value="{{ $siswa->pekerjaan_ibu }}" class="w-full rounded border-gray-300 text-sm focus:border-red-500 focus:ring-red-500">
                                                    </div>
                                                    <div class="md:col-span-2">
                                                        <label class="block text-xs font-bold text-gray-700 mb-1">Alamat Siswa / Orang Tua</label>
                                                        <textarea name="alamat" rows="2" class="w-full rounded border-gray-300 text-sm focus:border-red-500 focus:ring-red-500">{{ $siswa->alamat }}</textarea>
                                                    </div>
                                                </div>
                                                <div class="flex justify-end gap-2 pt-2 border-t mt-4">
                                                    <button type="button" @click="editing = null" class="bg-gray-300 hover:bg-gray-400 text-gray-800 text-xs font-bold py-2 px-4 rounded shadow">Batal</button>
                                                    <button type="submit" class="bg-red-600 hover:bg-red-700 text-white text-xs font-bold py-2 px-4 rounded shadow">Simpan</button>
                                                </div>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="10" class="px-6 py-8 whitespace-nowrap text-sm text-gray-500 text-center">
                                            Belum ada data siswa.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
