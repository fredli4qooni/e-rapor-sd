<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight uppercase">
            {{ __('Status Penilaian Oleh Guru Mapel') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-t-4 border-red-800">
                <div class="p-6 text-gray-900">
                    <div class="mb-6 p-4 border border-gray-200 rounded-md bg-white">
                        <div class="flex flex-col md:flex-row md:items-center gap-4">
                            <label class="w-full md:w-48 font-bold text-gray-700 text-sm">Pilih Kelas :</label>
                            <input type="text" value="{{ $rombel->tingkat . ' ' . strtoupper($rombel->nama_rombel) }}" class="flex-1 bg-gray-50 border border-blue-300 text-gray-900 text-sm rounded-md p-2 max-w-3xl" readonly>
                        </div>
                    </div>

                    <div class="overflow-x-auto mt-4">
                        <table class="min-w-full divide-y divide-gray-200 border border-gray-300">
                            <thead class="bg-red-900 text-white shadow">
                                <tr class="text-center text-xs uppercase">
                                    <th class="px-4 py-3 border border-red-800 w-12 align-middle" rowspan="2">No</th>
                                    <th class="px-4 py-3 border border-red-800 align-middle text-left" rowspan="2">Nama Mapel</th>
                                    <th class="px-4 py-3 border border-red-800 w-48 align-middle" rowspan="2">Rombel</th>
                                    <th class="px-4 py-2 border border-red-800 text-center" colspan="2">Nilai Rapor</th>
                                </tr>
                                <tr class="text-center bg-red-800 text-xs">
                                    <th class="px-4 py-2 border border-red-700 w-32">Nilai Rapor</th>
                                    <th class="px-4 py-2 border border-red-700 w-32">Deskripsi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($data_status as $index => $item)
                                    @php
                                        $mapel = $item['mapel'];
                                        $count_nilai = $item['count_nilai'];
                                        $count_deskripsi = $item['count_deskripsi'];
                                        $is_zero = ($count_nilai == 0 && $count_deskripsi == 0);
                                        $bg_class = $is_zero ? 'bg-gray-100 text-gray-500' : ($index % 2 == 0 ? 'bg-red-50/20' : 'bg-white');
                                        $cell_bg_nilai = $is_zero ? 'bg-gray-300' : 'bg-teal-600/20';
                                        $cell_bg_deskripsi = $is_zero ? 'bg-gray-300' : 'bg-teal-600/20';
                                    @endphp
                                <tr class="hover:bg-red-50/50 transition-colors {{ $bg_class }}">
                                    <td class="px-4 py-3 text-sm text-center border border-gray-300">{{ $loop->iteration }}</td>
                                    <td class="px-4 py-3 text-sm border border-gray-300 font-medium">{{ $mapel->nama_mapel }}</td>
                                    <td class="px-4 py-3 text-sm text-center border border-gray-300 uppercase">{{ $rombel->tingkat . ' ' . $rombel->nama_rombel }}</td>
                                    <td class="px-4 py-3 text-sm text-center border border-gray-300 {{ $cell_bg_nilai }} font-semibold text-gray-700">{{ $count_nilai }} Data</td>
                                    <td class="px-4 py-3 text-sm text-center border border-gray-300 {{ $cell_bg_deskripsi }} font-semibold text-gray-700">{{ $count_deskripsi }} Data</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-8 whitespace-nowrap text-sm text-gray-500 text-center">
                                        Belum ada mata pelajaran untuk kelas ini.
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
