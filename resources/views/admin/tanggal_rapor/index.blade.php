<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Tanggal Rapor') }}
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
                Pengaturan Tanggal Rapor Per Semester
            </div>
            
            <form action="{{ route('admin.tanggal_rapor.update') }}" method="POST" class="p-6">
                @csrf
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-700 border">
                        <thead class="text-xs text-white bg-gray-800 uppercase border-b">
                            <tr>
                                <th class="px-4 py-3 w-16 text-center">No</th>
                                <th class="px-4 py-3 w-1/4">Tahun Ajaran / Semester</th>
                                <th class="px-4 py-3">Tempat Terbit</th>
                                <th class="px-4 py-3">Tanggal Rapor</th>
                                <th class="px-4 py-3 w-32 text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($semesters as $index => $semester)
                                <tr class="border-b hover:bg-gray-50 {{ $semester->is_aktif ? 'bg-red-50' : '' }}">
                                    <td class="px-4 py-3 text-center">{{ $index + 1 }}</td>
                                    <td class="px-4 py-3 font-semibold">
                                        {{ $semester->tahun_ajaran }} - 
                                        Semester {{ $semester->semester == 1 ? '1 (Ganjil)' : '2 (Genap)' }}
                                        @if($semester->is_aktif)
                                            <span class="ml-2 text-xs bg-red-100 text-red-800 px-2 py-0.5 rounded font-bold">Aktif</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3">
                                        <input type="text" name="semester[{{ $semester->id }}][tempat_terbit]" value="{{ old('semester.'.$semester->id.'.tempat_terbit', $semester->tempat_terbit) }}" class="block w-full border-gray-300 focus:border-red-500 focus:ring-red-500 rounded-md shadow-sm text-sm" placeholder="Contoh: Jakarta">
                                    </td>
                                    <td class="px-4 py-3">
                                        <input type="date" name="semester[{{ $semester->id }}][tanggal_rapor]" value="{{ old('semester.'.$semester->id.'.tanggal_rapor', $semester->tanggal_rapor) }}" class="block w-full border-gray-300 focus:border-red-500 focus:ring-red-500 rounded-md shadow-sm text-sm">
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        @if($semester->is_aktif)
                                            <span class="text-green-600 font-semibold text-xs">Aktif</span>
                                        @else
                                            <span class="text-gray-500 font-medium text-xs">Tidak Aktif</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-4 py-4 text-center text-gray-500">Belum ada data semester.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="flex items-center justify-end mt-6">
                    <x-primary-button class="bg-[#8B1515] hover:bg-red-700">
                        {{ __('Simpan Tanggal Rapor') }}
                    </x-primary-button>
                </div>
            </form>
        </div>

    </div>
</x-app-layout>
