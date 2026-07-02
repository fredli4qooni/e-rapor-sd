<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Cetak Transkrip Nilai Ijazah') }}
        </h2>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8">
        
        <!-- Setting Check -->
        @if(!$setting)
            <div class="mb-4 bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">Pengaturan Transkrip belum disetting. Silakan lengkapi pengaturan terlebih dahulu di menu <strong>Setting Transkrip</strong>.</span>
            </div>
        @endif

        <!-- Filter -->
        <div class="bg-white rounded-md shadow-md overflow-hidden mb-6">
            <div class="p-4 border-b">
                <form action="{{ route('admin.transkrip_ijazah.cetak.index') }}" method="GET" class="flex flex-col sm:flex-row gap-4 items-end">
                    <div class="w-full sm:w-1/3">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Pilih Kelas</label>
                        <select name="rombel_id" class="w-full border-gray-300 rounded-md shadow-sm focus:border-red-500 focus:ring-red-500 text-sm" required>
                            <option value="">Pilih Kelas...</option>
                            @foreach($rombels as $rombel)
                                <option value="{{ $rombel->id }}" {{ request('rombel_id') == $rombel->id ? 'selected' : '' }}>{{ $rombel->nama_rombel }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <button type="submit" class="bg-gray-800 text-white px-4 py-2 rounded-md hover:bg-gray-700 transition font-medium text-sm w-full sm:w-auto h-10">
                            Tampilkan Siswa
                        </button>
                    </div>
                </form>
            </div>
        </div>

        @if($rombel_id)
            <div class="bg-white rounded-md shadow-md overflow-hidden">
                <div class="bg-[#8B1515] text-white px-6 py-4 font-bold tracking-wider uppercase text-sm border-b border-red-800 flex justify-between items-center">
                    <span>Daftar Siswa untuk Dicetak</span>
                    <a href="{{ route('admin.transkrip_ijazah.cetak.generate_kelas', $rombel_id) }}" target="_blank" class="bg-white text-red-800 text-xs px-3 py-1.5 rounded shadow-sm hover:bg-gray-100 transition inline-flex items-center font-bold">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                        Cetak Seluruh Siswa (1 File PDF)
                    </a>
                </div>
                
                <div class="overflow-x-auto">
                    @if(count($siswas) > 0)
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-16">No</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">NISN</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Siswa</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No. Ijazah / Transkrip</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi Cetak PDF</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($siswas as $index => $siswa)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                        {{ $index + 1 }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $siswa->nisn }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        {{ $siswa->nama_lengkap }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        @if($siswa->no_ijazah || $siswa->no_transkrip)
                                            <div>Ijz: <span class="text-gray-900 font-semibold">{{ $siswa->no_ijazah ?: '-' }}</span></div>
                                            <div>Trn: <span class="text-gray-900 font-semibold">{{ $siswa->no_transkrip ?: '-' }}</span></div>
                                        @else
                                            <span class="text-red-500 text-xs italic">Belum diinput</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-center">
                                        <a href="{{ route('admin.transkrip_ijazah.cetak.generate_siswa', $siswa->id) }}" target="_blank" class="inline-flex items-center px-3 py-1.5 bg-blue-100 text-blue-700 hover:bg-blue-200 rounded font-semibold text-xs transition">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                            Generate PDF
                                        </a>
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
            </div>
        @endif

    </div>
</x-app-layout>
