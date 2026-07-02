<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Status Penilaian') }}
        </h2>
    </x-slot>

    <div class="py-6 space-y-6 max-w-7xl mx-auto sm:px-6 lg:px-8">
        
        <!-- Filter -->
        <div class="bg-white p-4 rounded-md shadow flex justify-between items-center">
            <form action="{{ route('admin.status_penilaian.index') }}" method="GET" class="flex items-center space-x-2">
                <label for="rombel_id" class="font-semibold text-gray-700 text-sm">Pilih Kelas:</label>
                <select name="rombel_id" id="rombel_id" class="border-gray-300 rounded-md shadow-sm focus:border-red-500 focus:ring-red-500 text-sm" onchange="this.form.submit()">
                    <option value="">-- Pilih Kelas --</option>
                    @foreach($rombels as $rombel)
                        <option value="{{ $rombel->id }}" {{ request('rombel_id') == $rombel->id ? 'selected' : '' }}>{{ $rombel->nama_rombel }}</option>
                    @endforeach
                </select>
            </form>
        </div>

        @if($selectedRombelId)
        <div class="bg-white rounded-md shadow-md overflow-hidden">
            <div class="bg-[#8B1515] text-white px-6 py-4 font-bold tracking-wider uppercase text-sm border-b border-red-800">
                Status Penilaian Rapor
            </div>
            
            <div class="p-6 overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-700 border">
                    <thead class="text-xs text-white bg-gray-800 uppercase border-b">
                        <tr>
                            <th class="px-4 py-3 w-16 text-center">No</th>
                            <th class="px-4 py-3">Mata Pelajaran</th>
                            <th class="px-4 py-3">Guru Pengampu</th>
                            <th class="px-4 py-3 text-center">Siswa Ternilai</th>
                            <th class="px-4 py-3 text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($mapels as $index => $mapel)
                            <tr class="border-b hover:bg-gray-50">
                                <td class="px-4 py-3 text-center font-bold">{{ $index + 1 }}</td>
                                <td class="px-4 py-3 font-semibold">{{ $mapel->nama_mapel }}</td>
                                <td class="px-4 py-3 text-gray-600">{{ $mapel->guru }}</td>
                                <td class="px-4 py-3 text-center">
                                    {{ $mapel->siswa_dinilai }} / {{ $mapel->total_siswa }} Siswa
                                </td>
                                <td class="px-4 py-3 text-center">
                                    @if($mapel->status === 'Lengkap')
                                        <span class="bg-green-100 text-green-800 text-xs px-2 py-1 rounded font-bold">{{ $mapel->status }}</span>
                                    @else
                                        <span class="bg-red-100 text-red-800 text-xs px-2 py-1 rounded font-bold">{{ $mapel->status }}</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-3 text-center text-gray-500">Belum ada data mata pelajaran untuk kelas ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @else
        <div class="bg-white p-6 rounded-md shadow text-center text-gray-500">
            Silakan pilih kelas terlebih dahulu untuk melihat status penilaian.
        </div>
        @endif

    </div>
</x-app-layout>
