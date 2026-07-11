<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Cetak Leger Rapor') }}
        </h2>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8">
        
        <!-- Filter -->
        <div class="bg-white rounded-md shadow-md overflow-hidden mb-6">
            <div class="bg-indigo-800 text-white px-6 py-4 font-bold tracking-wider uppercase text-sm border-b border-indigo-900">
                Pilih Kelas untuk Cetak Leger
            </div>
            <div class="p-4 border-b">
                <form action="{{ route('kepsek.laporan.leger') }}" method="GET" class="flex flex-col sm:flex-row gap-4 items-end">
                    <div class="w-full sm:w-1/2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Rombongan Belajar (Kelas)</label>
                        <select name="rombel_id" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm" required>
                            <option value="">Pilih Kelas...</option>
                            @foreach($rombels as $rombel)
                                <option value="{{ $rombel->id }}" {{ request('rombel_id') == $rombel->id ? 'selected' : '' }}>{{ $rombel->nama_rombel }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <button type="submit" class="bg-gray-800 text-white px-6 py-2 rounded-md hover:bg-gray-700 transition font-medium text-sm w-full sm:w-auto h-10 shadow-sm">
                            Tampilkan Data Siswa
                        </button>
                    </div>
                </form>
            </div>
        </div>

        @if($rombel_id)
            <div class="bg-white rounded-md shadow-md overflow-hidden">
                <div class="p-6 bg-gray-50 border-b flex flex-col md:flex-row justify-between items-center gap-4">
                    <div class="text-sm text-gray-600">
                        <span class="font-bold text-gray-900">Total Siswa:</span> {{ count($siswas) }} Anak
                    </div>
                    <div class="flex gap-2">
                        <a href="{{ route('kepsek.laporan.leger_download', $rombel_id) }}" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 active:bg-green-800 focus:outline-none focus:border-green-900 focus:ring ring-green-300 disabled:opacity-25 transition ease-in-out duration-150 shadow-md">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            Download Leger (Semester Aktif)
                        </a>
                    </div>
                </div>
                
                <div class="overflow-x-auto">
                    @if(count($siswas) > 0)
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="px-6 py-3 text-center text-xs font-bold text-gray-700 uppercase tracking-wider w-16">No</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">NISN</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">NIS</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Nama Lengkap Siswa</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($siswas as $index => $siswa)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                        {{ $index + 1 }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-medium">
                                        {{ $siswa->nisn }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $siswa->nis }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900">
                                        {{ $siswa->nama_lengkap }}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <div class="p-8 text-center text-gray-500">
                            Tidak ada data siswa pada kelas ini.
                        </div>
                    @endif
                </div>
                <div class="p-4 bg-yellow-50 text-yellow-700 text-xs border-t border-yellow-200">
                    <span class="font-bold">Info Penting:</span> File Excel yang di-download berisikan daftar nilai akhir (Rapor) per Mata Pelajaran berdasarkan data yang sudah dientri oleh Guru Mata Pelajaran. Jika nilai masih kosong, mohon beritahu guru pengampu untuk menyelesaikan input nilai.
                </div>
            </div>
        @endif

    </div>
</x-app-layout>
