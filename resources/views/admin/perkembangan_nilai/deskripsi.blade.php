<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Riwayat Deskripsi Kompetensi Siswa') }}
        </h2>
    </x-slot>

    <div class="py-6 space-y-6 max-w-7xl mx-auto sm:px-6 lg:px-8">
        
        <!-- Info Siswa -->
        <div class="bg-white p-6 rounded-md shadow flex flex-col sm:flex-row items-center sm:items-start space-y-4 sm:space-y-0 sm:space-x-6 border-l-4 border-green-700">
            <div class="flex-shrink-0">
                <div class="h-24 w-24 rounded-full bg-gray-200 flex items-center justify-center text-gray-500 text-3xl font-bold">
                    {{ substr($siswa->nama_lengkap, 0, 1) }}
                </div>
            </div>
            <div>
                <h3 class="text-xl font-bold text-gray-900">{{ $siswa->nama_lengkap }}</h3>
                <div class="mt-2 text-sm text-gray-600 grid grid-cols-1 sm:grid-cols-2 gap-2">
                    <p><strong>NISN:</strong> {{ $siswa->nisn }}</p>
                    <p><strong>NIS:</strong> {{ $siswa->nis ?? '-' }}</p>
                    <p><strong>Jenis Kelamin:</strong> {{ $siswa->jenis_kelamin === 'L' ? 'Laki-Laki' : 'Perempuan' }}</p>
                </div>
            </div>
        </div>

        @php
            $rombelId = $siswa->rombels->first()->id ?? '';
        @endphp
        <div>
            <a href="{{ route('admin.perkembangan_nilai.index', ['rombel_id' => $rombelId]) }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
                &larr; Kembali ke Daftar Siswa
            </a>
        </div>

        <div class="bg-white rounded-md shadow-md overflow-hidden">
            <div class="bg-gray-800 text-white px-6 py-4 font-bold tracking-wider uppercase text-sm border-b border-gray-900">
                Perkembangan Deskripsi Rapor
            </div>
            
            <div class="p-0 overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-700">
                    <thead class="text-xs text-gray-700 bg-gray-100 uppercase border-b">
                        <tr>
                            <th class="px-4 py-3 w-10 text-center border-r">No</th>
                            <th class="px-4 py-3 border-r min-w-[150px]">Mata Pelajaran</th>
                            @foreach($semesters as $semName)
                                <th class="px-4 py-3 border-r min-w-[300px]">{{ $semName }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @php $no = 1; @endphp
                        @forelse($deskripsiData as $mapel => $deskripsiPerSemester)
                            <tr class="border-b hover:bg-gray-50">
                                <td class="px-4 py-3 text-center font-bold border-r">{{ $no++ }}</td>
                                <td class="px-4 py-3 font-semibold border-r">{{ $mapel }}</td>
                                @foreach($semesters as $semId => $semName)
                                    <td class="px-4 py-3 border-r text-xs align-top">
                                        @if(isset($deskripsiPerSemester[$semId]))
                                            <div class="mb-2">
                                                <span class="inline-block px-2 py-1 bg-green-100 text-green-800 rounded font-semibold text-[10px] mb-1">TERTINGGI</span>
                                                <p class="text-gray-800">{{ $deskripsiPerSemester[$semId]['tertinggi'] ?: '-' }}</p>
                                            </div>
                                            <div>
                                                <span class="inline-block px-2 py-1 bg-red-100 text-red-800 rounded font-semibold text-[10px] mb-1">TERENDAH</span>
                                                <p class="text-gray-800">{{ $deskripsiPerSemester[$semId]['terendah'] ?: '-' }}</p>
                                            </div>
                                        @else
                                            <span class="text-gray-400 italic">Tidak ada data</span>
                                        @endif
                                    </td>
                                @endforeach
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ count($semesters) + 2 }}" class="px-4 py-3 text-center text-gray-500 py-8">
                                    Belum ada data deskripsi rapor untuk siswa ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</x-app-layout>
