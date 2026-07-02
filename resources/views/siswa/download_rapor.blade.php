<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Aplikasi e-Rapor | ' . ($activeSemester ? $activeSemester->tahun_ajaran . ' ' . $activeSemester->semester : 'Semester Aktif')) }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm rounded-lg border border-gray-200">
                <div class="p-6">
                    <h3 class="text-lg font-bold text-[#8b0000] mb-4 flex items-center border-b pb-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                        </svg>
                        DOWNLOAD DATA FILE RAPOR SISWA
                    </h3>
                    
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 border border-gray-300">
                            <thead class="bg-[#6b0000] text-white">
                                <tr>
                                    <th scope="col" class="px-4 py-3 text-left text-sm font-semibold border-r border-[#500000] w-12">No</th>
                                    <th scope="col" class="px-4 py-3 text-left text-sm font-semibold border-r border-[#500000]">Jenis File</th>
                                    <th scope="col" class="px-4 py-3 text-left text-sm font-semibold border-r border-[#500000] w-48">Semester/Tahun</th>
                                    <th scope="col" class="px-4 py-3 text-left text-sm font-semibold w-1/3">Link Download</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @php $no = 1; @endphp
                                @forelse($semesters as $smt)
                                    @php
                                        $smtName = $smt->tahun_ajaran . ' ' . $smt->semester;
                                        $p5Name = 'Thn. ' . $smt->tahun_ajaran;
                                        $isActive = $activeSemester && $smt->id == $activeSemester->id;
                                    @endphp

                                    <!-- File Pelengkap -->
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-4 py-3 text-sm text-gray-900 border-r border-gray-200">{{ $no++ }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-900 border-r border-gray-200">File Pelengkap {{ $smtName }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-900 border-r border-gray-200">{{ $smtName }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-900">
                                            @if($isActive && !$siswa->is_pelengkap_published)
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded text-xs font-medium bg-[#4a2311] text-white">
                                                    File Pelengkap {{ $smtName }} Disembunyikan
                                                </span>
                                            @else
                                                <a href="{{ route('siswa.cetak_pelengkap', ['semester_id' => $smt->id]) }}" target="_blank" class="text-blue-600 hover:text-blue-800 hover:underline flex items-center">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                                    </svg>
                                                    File Pelengkap {{ $smtName }}
                                                </a>
                                            @endif
                                        </td>
                                    </tr>

                                    <!-- File Nilai Rapor -->
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-4 py-3 text-sm text-gray-900 border-r border-gray-200">{{ $no++ }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-900 border-r border-gray-200">File Rapor {{ $smtName }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-900 border-r border-gray-200">{{ $smtName }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-900">
                                            @if($isActive && !$siswa->is_rapor_published)
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded text-xs font-medium bg-[#4a2311] text-white">
                                                    File File Rapor {{ $smtName }} Disembunyikan
                                                </span>
                                            @else
                                                <a href="{{ route('siswa.cetak', ['semester_id' => $smt->id]) }}" target="_blank" class="text-blue-600 hover:text-blue-800 hover:underline flex items-center">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                                    </svg>
                                                    File File Rapor {{ $smtName }}
                                                </a>
                                            @endif
                                        </td>
                                    </tr>

                                    <!-- File P5 -->
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-4 py-3 text-sm text-gray-900 border-r border-gray-200">{{ $no++ }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-900 border-r border-gray-200">File Projek P5 {{ $p5Name }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-900 border-r border-gray-200">{{ $smt->tahun_ajaran }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-900">
                                            @if($isActive && !$siswa->is_p5_published)
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded text-xs font-medium bg-[#4a2311] text-white">
                                                    File Projek P5 {{ $p5Name }} Disembunyikan
                                                </span>
                                            @else
                                                <a href="{{ route('siswa.cetak_p5', ['semester_id' => $smt->id]) }}" target="_blank" class="text-blue-600 hover:text-blue-800 hover:underline flex items-center">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                                    </svg>
                                                    File Projek P5 {{ $p5Name }}
                                                </a>
                                            @endif
                                        </td>
                                    </tr>

                                @empty
                                    <tr>
                                        <td colspan="4" class="px-4 py-8 text-center text-gray-500 italic">Belum ada dokumen rapor yang tersedia untuk diunduh.</td>
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
