<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight uppercase">
            {{ __('Capaian Nilai Rapor') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Filter Section Card -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-t-4 border-red-800 mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-bold text-gray-800 uppercase mb-4 border-b pb-2">PERKEMBANGAN NILai RAPOR SISWA</h3>
                    <form method="GET" action="{{ route('guru.cek_penilaian.capaian') }}" class="space-y-4">
                        <div class="flex items-center gap-4">
                            <label class="w-1/4 text-sm font-bold text-gray-700 whitespace-nowrap">Pilih Kelas</label>
                            <select name="rombel_id" onchange="this.form.submit()" class="block w-3/4 rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm bg-gray-50">
                                <option value="">Pilih Data Kelas</option>
                                @foreach($rombels as $r)
                                    <option value="{{ $r->id }}" {{ $rombel_id == $r->id ? 'selected' : '' }}>
                                        {{ $r->nama_rombel }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="flex items-center gap-4">
                            <label class="w-1/4 text-sm font-bold text-gray-700 whitespace-nowrap">Pilih Mapel</label>
                            <select name="mapel_id" onchange="this.form.submit()" class="block w-3/4 rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm bg-gray-50" {{ !$rombel_id ? 'disabled' : '' }}>
                                <option value="">Pilih Data Mapel</option>
                                @foreach($mapels as $m)
                                    <option value="{{ $m->id }}" {{ $mapel_id == $m->id ? 'selected' : '' }}>
                                        {{ $m->nama_mapel }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="flex items-center gap-4">
                            <label class="w-1/4 text-sm font-bold text-gray-700 whitespace-nowrap">Jenis Data</label>
                            <select name="jenis_data" onchange="this.form.submit()" class="block w-3/4 rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm bg-gray-50" {{ !$mapel_id ? 'disabled' : '' }}>
                                <option value="nilai" {{ $jenis_data == 'nilai' ? 'selected' : '' }}>Perkembangan Nilai Rapor</option>
                                <option value="deskripsi" {{ $jenis_data == 'deskripsi' ? 'selected' : '' }}>Perkembangan Deskripsi Rapor</option>
                            </select>
                        </div>
                    </form>
                </div>
            </div>

            @if($rombel_id && $mapel_id)
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 border border-gray-300">
                            <thead class="bg-red-900 text-white">
                                <tr>
                                    <th rowspan="2" scope="col" class="px-3 py-3 text-center text-xs font-bold tracking-wider border border-red-800">No</th>
                                    <th rowspan="2" scope="col" class="px-4 py-3 text-left text-xs font-bold tracking-wider border border-red-800 whitespace-nowrap">Nama Siswa</th>
                                    <th rowspan="2" scope="col" class="px-3 py-3 text-center text-xs font-bold tracking-wider border border-red-800">NISN</th>
                                    <th rowspan="2" scope="col" class="px-3 py-3 text-center text-xs font-bold tracking-wider border border-red-800">NIS</th>
                                    @if($jenis_data == 'nilai')
                                    <th colspan="12" scope="col" class="px-3 py-2 text-center text-xs font-bold tracking-wider border border-red-800">Nilai Semester</th>
                                    <th rowspan="2" scope="col" class="px-3 py-3 text-center text-xs font-bold tracking-wider border border-red-800">Rata-Rata</th>
                                    @else
                                    <th colspan="12" scope="col" class="px-3 py-2 text-center text-xs font-bold tracking-wider border border-red-800">Deskripsi Ketercapaian Kompetensi</th>
                                    @endif
                                </tr>
                                <tr>
                                    @for($i = 1; $i <= 12; $i++)
                                        <th scope="col" class="px-2 py-2 text-center text-xs font-bold tracking-wider border border-red-800 min-w-[50px] {{ $jenis_data == 'deskripsi' ? 'min-w-[250px]' : '' }}">Smt {{ $i }}</th>
                                    @endfor
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($siswas as $index => $siswa)
                                    @php
                                        $capaian = $data_capaian[$siswa->id] ?? [];
                                    @endphp
                                    <tr class="hover:bg-red-50/50 transition-colors {{ $index % 2 == 0 ? 'bg-red-50/20' : 'bg-white' }}">
                                        <td class="px-3 py-2 text-sm text-center text-gray-700 border border-gray-300 align-top">{{ $index + 1 }}</td>
                                        <td class="px-4 py-2 text-sm font-medium text-red-900 border border-gray-300 align-top">{{ $siswa->nama_lengkap }}</td>
                                        <td class="px-3 py-2 text-sm text-center text-gray-700 border border-gray-300 align-top">{{ $siswa->nisn }}</td>
                                        <td class="px-3 py-2 text-sm text-center text-gray-700 border border-gray-300 align-top">{{ $siswa->nis ?? '-' }}</td>
                                        
                                        @for($i = 1; $i <= 12; $i++)
                                            <td class="px-2 py-2 text-sm text-center text-gray-700 border border-gray-300 align-top {{ $jenis_data == 'deskripsi' ? 'text-left text-xs leading-relaxed' : '' }}">
                                                {{ $capaian[$i] ?? '-' }}
                                            </td>
                                        @endfor
                                        
                                        @if($jenis_data == 'nilai')
                                        <td class="px-3 py-2 text-sm text-center font-bold text-gray-800 border border-gray-300 align-top">
                                            {{ $capaian['rata_rata'] ?? '-' }}
                                        </td>
                                        @endif
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="{{ $jenis_data == 'nilai' ? 17 : 16 }}" class="px-6 py-8 whitespace-nowrap text-sm text-gray-500 text-center">
                                            Belum ada data siswa pada kelas ini.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endif

        </div>
    </div>
</x-app-layout>
