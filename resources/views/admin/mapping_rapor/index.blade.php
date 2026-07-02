<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Mapping Rapor') }}
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
            <div class="bg-[#8B1515] text-white px-6 py-4 font-bold tracking-wider uppercase text-sm border-b border-red-800">
                Mapping Mata Pelajaran ke Kelompok Mapel
            </div>
            
            <form action="{{ route('admin.mapping_rapor.update') }}" method="POST" class="p-6">
                @csrf
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-700 border">
                        <thead class="text-xs text-white bg-gray-800 uppercase border-b">
                            <tr>
                                <th class="px-4 py-3 w-16 text-center">No</th>
                                <th class="px-4 py-3 w-1/3">Mata Pelajaran</th>
                                <th class="px-4 py-3">Kelompok Mapel</th>
                                <th class="px-4 py-3 w-32 text-center">Nomor Urut</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($mapels as $index => $mapel)
                                <tr class="border-b hover:bg-gray-50">
                                    <td class="px-4 py-3 text-center">{{ $index + 1 }}</td>
                                    <td class="px-4 py-3 font-semibold">
                                        {{ $mapel->nama_mapel }}
                                        @if($mapel->is_lokal)
                                            <span class="ml-2 text-xs bg-yellow-100 text-yellow-800 px-2 py-0.5 rounded">Lokal</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3">
                                        <select name="mapel[{{ $mapel->id }}][kelompok_mapel_id]" class="block w-full border-gray-300 focus:border-red-500 focus:ring-red-500 rounded-md shadow-sm text-sm">
                                            <option value="">-- Pilih Kelompok --</option>
                                            @foreach($kelompoks as $kelompok)
                                                <option value="{{ $kelompok->id }}" {{ $mapel->kelompok_mapel_id == $kelompok->id ? 'selected' : '' }}>
                                                    {{ $kelompok->nama_kelompok }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td class="px-4 py-3">
                                        <input type="number" name="mapel[{{ $mapel->id }}][nomor_urut]" value="{{ $mapel->nomor_urut }}" class="block w-full text-center border-gray-300 focus:border-red-500 focus:ring-red-500 rounded-md shadow-sm text-sm" placeholder="1, 2, ...">
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-4 py-4 text-center text-gray-500">
                                        Belum ada mata pelajaran yang diset untuk tampil di rapor. Pastikan ada mapel dengan seting 'Tampil di Transkrip/Rapor' = Ya.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="flex items-center justify-end mt-6">
                    <x-primary-button class="bg-[#8B1515] hover:bg-red-700">
                        {{ __('Simpan Mapping') }}
                    </x-primary-button>
                </div>
            </form>
        </div>

    </div>
</x-app-layout>
