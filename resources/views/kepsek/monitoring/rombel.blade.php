<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Monitoring Data Rombel (Kelas)') }}
        </h2>
    </x-slot>

    <div class="py-6 space-y-6 max-w-7xl mx-auto sm:px-6 lg:px-8">
        
        <div class="bg-white rounded-md shadow-md overflow-hidden">
            <div class="bg-[#8B1515] text-white px-6 py-4 font-bold tracking-wider uppercase text-sm border-b border-red-800 flex justify-between items-center">
                <span>Daftar Rombel Aktif (Semester: {{ $semester_aktif ? $semester_aktif->tahun_ajaran . ' - ' . ($semester_aktif->semester == 1 ? 'Ganjil' : 'Genap') : 'Belum disetup' }})</span>
            </div>
            
            <div class="p-6 overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-700 border">
                    <thead class="text-xs text-white bg-gray-800 uppercase border-b">
                        <tr>
                            <th class="px-4 py-3 w-16">No</th>
                            <th class="px-4 py-3">Tingkat/Fase</th>
                            <th class="px-4 py-3">Nama Rombel</th>
                            <th class="px-4 py-3">Wali Kelas</th>
                            <th class="px-4 py-3 text-center">Jumlah Siswa</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($rombels as $index => $rombel)
                            <tr class="border-b hover:bg-gray-50">
                                <td class="px-4 py-3">{{ $index + 1 }}</td>
                                <td class="px-4 py-3">Tingkat {{ $rombel->tingkat }} (Fase {{ $rombel->fase }})</td>
                                <td class="px-4 py-3 font-semibold">{{ $rombel->nama_rombel }}</td>
                                <td class="px-4 py-3">
                                    @if($rombel->waliKelas)
                                        {{ $rombel->waliKelas->gelar_depan ? $rombel->waliKelas->gelar_depan . ' ' : '' }}{{ $rombel->waliKelas->nama_lengkap }}{{ $rombel->waliKelas->gelar_belakang ? ', ' . $rombel->waliKelas->gelar_belakang : '' }}
                                    @else
                                        <span class="text-red-500 italic">Belum di set</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-center font-bold">{{ $rombel->siswas->count() }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-3 text-center text-gray-500">Belum ada data rombel.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</x-app-layout>
