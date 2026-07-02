<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Cetak Pelengkap Rapor') }}
        </h2>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8">
        
        @if(session('success'))
            <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        @if($errors->any())
            <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>- {{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            <!-- Panel Pengaturan Cetak -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-md shadow-md overflow-hidden">
                    <div class="bg-gray-800 text-white px-6 py-4 font-bold tracking-wider uppercase text-sm border-b border-gray-900">
                        Pengaturan Cetak
                    </div>
                    <form action="{{ route('admin.cetak_nilai.pelengkap_rapor.store_setting') }}" method="POST" class="p-6 space-y-4">
                        @csrf
                        <div>
                            <label class="block font-semibold text-gray-700 text-sm mb-1">Ukuran Kertas</label>
                            <select name="ukuran_kertas" class="w-full border-gray-300 rounded-md shadow-sm focus:border-red-500 focus:ring-red-500 text-sm">
                                <option value="A4" {{ ($setting->ukuran_kertas ?? '') == 'A4' ? 'selected' : '' }}>A4 (210 x 297 mm)</option>
                                <option value="F4" {{ ($setting->ukuran_kertas ?? '') == 'F4' ? 'selected' : '' }}>F4 / Folio (215 x 330 mm)</option>
                            </select>
                        </div>
                        
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block font-semibold text-gray-700 text-sm mb-1">Margin Atas (mm)</label>
                                <input type="number" name="margin_atas" value="{{ $setting->margin_atas ?? 15 }}" class="w-full border-gray-300 rounded-md shadow-sm text-sm" required>
                            </div>
                            <div>
                                <label class="block font-semibold text-gray-700 text-sm mb-1">Margin Bawah (mm)</label>
                                <input type="number" name="margin_bawah" value="{{ $setting->margin_bawah ?? 15 }}" class="w-full border-gray-300 rounded-md shadow-sm text-sm" required>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block font-semibold text-gray-700 text-sm mb-1">Margin Kiri (mm)</label>
                                <input type="number" name="margin_kiri" value="{{ $setting->margin_kiri ?? 15 }}" class="w-full border-gray-300 rounded-md shadow-sm text-sm" required>
                            </div>
                            <div>
                                <label class="block font-semibold text-gray-700 text-sm mb-1">Margin Kanan (mm)</label>
                                <input type="number" name="margin_kanan" value="{{ $setting->margin_kanan ?? 15 }}" class="w-full border-gray-300 rounded-md shadow-sm text-sm" required>
                            </div>
                        </div>

                        <div>
                            <label class="block font-semibold text-gray-700 text-sm mb-1">Halaman Awal Rapor</label>
                            <input type="number" name="hal_awal_rapor" value="{{ $setting->hal_awal_rapor ?? 1 }}" class="w-full border-gray-300 rounded-md shadow-sm text-sm" required min="1">
                        </div>

                        <hr class="border-gray-200">

                        <div class="flex items-center space-x-2">
                            <input type="checkbox" id="tampilkan_ttd_kepsek" name="tampilkan_ttd_kepsek" value="1" {{ ($setting->tampilkan_ttd_kepsek ?? true) ? 'checked' : '' }} class="rounded border-gray-300 text-red-600 shadow-sm focus:ring focus:ring-red-200">
                            <label for="tampilkan_ttd_kepsek" class="font-semibold text-gray-700 text-sm">Tampilkan TTD Kepala Sekolah</label>
                        </div>

                        <div>
                            <label class="block font-semibold text-gray-700 text-sm mb-1">Posisi TTD Kepala Sekolah</label>
                            <select name="posisi_ttd_kepsek" class="w-full border-gray-300 rounded-md shadow-sm text-sm">
                                <option value="kiri" {{ ($setting->posisi_ttd_kepsek ?? 'kanan') == 'kiri' ? 'selected' : '' }}>Kiri</option>
                                <option value="tengah" {{ ($setting->posisi_ttd_kepsek ?? 'kanan') == 'tengah' ? 'selected' : '' }}>Tengah</option>
                                <option value="kanan" {{ ($setting->posisi_ttd_kepsek ?? 'kanan') == 'kanan' ? 'selected' : '' }}>Kanan</option>
                            </select>
                        </div>

                        <div class="flex items-center space-x-2">
                            <input type="checkbox" id="tampilkan_ttd_wali" name="tampilkan_ttd_wali" value="1" {{ ($setting->tampilkan_ttd_wali ?? true) ? 'checked' : '' }} class="rounded border-gray-300 text-red-600 shadow-sm focus:ring focus:ring-red-200">
                            <label for="tampilkan_ttd_wali" class="font-semibold text-gray-700 text-sm">Tampilkan TTD Wali Kelas</label>
                        </div>

                        <div class="flex items-center space-x-2">
                            <input type="checkbox" id="tampilkan_nama_wali" name="tampilkan_nama_wali" value="1" {{ ($setting->tampilkan_nama_wali ?? true) ? 'checked' : '' }} class="rounded border-gray-300 text-red-600 shadow-sm focus:ring focus:ring-red-200">
                            <label for="tampilkan_nama_wali" class="font-semibold text-gray-700 text-sm">Tampilkan Nama Wali Kelas</label>
                        </div>

                        <button type="submit" class="w-full bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition font-semibold text-sm shadow-sm mt-4">
                            Simpan Pengaturan
                        </button>
                    </form>
                </div>
            </div>

            <!-- Panel Daftar Siswa -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Filter -->
                <div class="bg-white rounded-md shadow-md overflow-hidden">
                    <div class="bg-[#8B1515] text-white px-6 py-4 font-bold tracking-wider uppercase text-sm border-b border-red-800">
                        Cetak Pelengkap Rapor
                    </div>
                    <div class="p-4 border-b">
                        <form action="{{ route('admin.cetak_nilai.pelengkap_rapor.index') }}" method="GET" class="flex flex-col sm:flex-row gap-4 items-end">
                            <div class="w-full sm:w-1/2">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Rombongan Belajar (Kelas)</label>
                                <select name="rombel_id" class="w-full border-gray-300 rounded-md shadow-sm focus:border-red-500 focus:ring-red-500 text-sm" required>
                                    <option value="">Pilih Kelas...</option>
                                    @foreach($rombels as $rombel)
                                        <option value="{{ $rombel->id }}" {{ request('rombel_id') == $rombel->id ? 'selected' : '' }}>{{ $rombel->nama_rombel }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <button type="submit" class="bg-gray-800 text-white px-6 py-2 rounded-md hover:bg-gray-700 transition font-medium text-sm w-full sm:w-auto h-10 shadow-sm">
                                    Tampilkan Siswa
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                @if($rombel_id)
                    <div class="bg-white rounded-md shadow-md overflow-hidden">
                        <div class="p-4 bg-gray-50 border-b flex flex-col sm:flex-row justify-between items-center gap-4">
                            <div class="text-sm text-gray-600">
                                <span class="font-bold text-gray-900">Total Siswa:</span> {{ count($siswas) }}
                            </div>
                            <div>
                                <a href="{{ route('admin.cetak_nilai.pelengkap_rapor.generate_kelas', $rombel_id) }}" target="_blank" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-800 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150 shadow-md">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                                    Cetak Langsung 1 Kelas (PDF)
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
                                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Nama Lengkap Siswa</th>
                                            <th class="px-6 py-3 text-center text-xs font-bold text-gray-700 uppercase tracking-wider">Aksi</th>
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
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900">
                                                {{ $siswa->nama_lengkap }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-center">
                                                <a href="{{ route('admin.cetak_nilai.pelengkap_rapor.generate_siswa', $siswa->id) }}" target="_blank" class="inline-flex items-center px-3 py-1.5 bg-blue-100 text-blue-700 hover:bg-blue-200 rounded font-semibold text-xs transition">
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

        </div>

    </div>
</x-app-layout>
