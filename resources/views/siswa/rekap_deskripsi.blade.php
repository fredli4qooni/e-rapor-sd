<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Aplikasi e-Rapor | ' . ($semesterAktif ? $semesterAktif->tahun_ajaran . ' ' . $semesterAktif->semester : 'Semester Aktif')) }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-[95%] mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm rounded-lg border border-gray-200">
                <div class="p-6">
                    <h3 class="text-lg font-bold text-[#8b0000] mb-4 flex items-center border-b pb-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        DAFTAR DESKRIPSI KETERCAPAIAN NILAI RAPOR SISWA
                    </h3>
                    
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 border border-gray-300">
                            <thead class="bg-[#8b0000] text-white">
                                <tr>
                                    <th scope="col" class="px-4 py-3 text-left text-sm font-semibold border-r border-[#6b0000] w-12 align-top">No</th>
                                    <th scope="col" class="px-4 py-3 text-left text-sm font-semibold border-r border-[#6b0000] min-w-[150px] align-top">Nama Mapel</th>
                                    @for($i = 1; $i <= 12; $i++)
                                        <th scope="col" class="px-4 py-3 text-left text-sm font-semibold border-r border-[#6b0000] min-w-[200px] align-top">Smt {{ $i }}</th>
                                    @endfor
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($groupedMapels as $kelompok => $mapels)
                                    <tr class="bg-red-100">
                                        <td colspan="14" class="px-4 py-2 font-bold text-[#8b0000]">{{ $kelompok }}</td>
                                    </tr>
                                    @foreach($mapels as $index => $mapel)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-4 py-3 text-sm text-gray-900 border-r border-gray-200 align-top">{{ $index + 1 }}</td>
                                            <td class="px-4 py-3 text-sm text-gray-900 border-r border-gray-200 font-medium align-top">{{ $mapel->nama_mapel }}</td>
                                            @for($i = 1; $i <= 12; $i++)
                                                <td class="px-4 py-3 text-xs text-gray-700 border-r border-gray-200 align-top">
                                                    @if(isset($matrix[$mapel->id][$i]))
                                                        {{ $matrix[$mapel->id][$i] }}
                                                    @else
                                                        <span class="text-gray-400 italic">Mencapai Kompetensi</span>
                                                    @endif
                                                </td>
                                            @endfor
                                        </tr>
                                    @endforeach
                                @endforeach
                                @if($groupedMapels->isEmpty())
                                    <tr>
                                        <td colspan="14" class="px-4 py-8 text-center text-gray-500 italic">Belum ada riwayat deskripsi rapor yang tersedia.</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
