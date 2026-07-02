<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight uppercase">
            {{ __('Cetak Pelengkap Nilai Rapor Siswa') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if (session('error'))
                <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif
            @if (session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-t-4 border-red-800">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-bold text-gray-800 mb-6 uppercase border-b pb-2">Cetak Pelengkap Nilai Rapor Siswa</h3>

                    <!-- Print Settings Form -->
                    <form method="GET" action="{{ route('walikelas.cetak_nilai.pelengkap.generate') }}" target="_blank" class="mb-8 p-4 border border-gray-200 rounded-md bg-white shadow-sm" id="print-settings-form">
                        <h4 class="font-bold text-md text-gray-800 mb-4">Pengaturan Hasil Cetak</h4>
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-4 mb-4">
                            <div>
                                <label class="block font-semibold text-sm text-gray-700 mb-1">Ukuran Kertas:</label>
                                <input type="text" name="ukuran_kertas" value="{{ $setting->ukuran_kertas ?? 'A4' }}" class="w-full border-gray-300 rounded-md text-center text-sm p-2 focus:ring-red-500 focus:border-red-500">
                            </div>
                            <div>
                                <label class="block font-semibold text-sm text-gray-700 mb-1">Margin Kiri (mm):</label>
                                <input type="number" name="margin_kiri" value="{{ $setting->margin_kiri ?? 20 }}" class="w-full border-gray-300 rounded-md text-center text-sm p-2 focus:ring-red-500 focus:border-red-500">
                            </div>
                            <div>
                                <label class="block font-semibold text-sm text-gray-700 mb-1">Margin Kanan (mm):</label>
                                <input type="number" name="margin_kanan" value="{{ $setting->margin_kanan ?? 20 }}" class="w-full border-gray-300 rounded-md text-center text-sm p-2 focus:ring-red-500 focus:border-red-500">
                            </div>
                            <div>
                                <label class="block font-semibold text-sm text-gray-700 mb-1">Margin Atas (mm):</label>
                                <input type="number" name="margin_atas" value="{{ $setting->margin_atas ?? 20 }}" class="w-full border-gray-300 rounded-md text-center text-sm p-2 focus:ring-red-500 focus:border-red-500">
                            </div>
                            <div>
                                <label class="block font-semibold text-sm text-gray-700 mb-1">Margin Bawah (mm):</label>
                                <input type="number" name="margin_bawah" value="{{ $setting->margin_bawah ?? 10 }}" class="w-full border-gray-300 rounded-md text-center text-sm p-2 focus:ring-red-500 focus:border-red-500">
                            </div>
                            <div>
                                <label class="block font-semibold text-sm text-gray-700 mb-1">Isi Tanda Tangan:</label>
                                <select name="isi_tanda_tangan" class="w-full border-gray-300 rounded-md text-center text-sm p-2 focus:ring-red-500 focus:border-red-500">
                                    <option value="Tanpa Tanda Tangan" {{ ($setting->isi_tanda_tangan ?? '') == 'Tanpa Tanda Tangan' ? 'selected' : '' }}>Tanpa Tanda Tangan</option>
                                    <option value="Dengan Tanda Tangan" {{ ($setting->isi_tanda_tangan ?? '') == 'Dengan Tanda Tangan' ? 'selected' : '' }}>Dengan Tanda Tangan</option>
                                </select>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-1 lg:grid-cols-1 gap-4">
                            <div>
                                <label class="block font-semibold text-sm text-gray-700 mb-1">Pilih Kelas :</label>
                                <input type="text" value="{{ $rombel->tingkat . ' ' . strtoupper($rombel->nama_rombel) }}" class="w-full bg-blue-50 border-blue-300 rounded-md text-gray-700 text-sm p-2 focus:ring-red-500 focus:border-red-500" readonly>
                            </div>
                        </div>
                    </form>

                    <!-- Table Area -->
                    <div class="border border-gray-300 rounded-md bg-white">
                        <div class="flex justify-end p-4 gap-2 bg-gray-50 border-b">
                            <!-- Submit massal via JS -->
                            <button onclick="document.getElementById('print-settings-form').submit();" class="inline-flex items-center px-4 py-2 bg-red-900 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-800 transition">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
                                Generate Pelengkap Rapor Kelas ini
                            </button>
                            <!-- Secondary button for direct print -->
                            <button onclick="document.getElementById('print-settings-form').submit();" class="inline-flex items-center px-4 py-2 bg-red-900 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-800 transition">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-3-3m0 0L8 8m4-4v12"></path></svg>
                                Cetak Langsung Pelengkap Rapor
                            </button>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-red-900 text-white shadow">
                                    <tr class="text-center text-xs uppercase">
                                        <th class="px-4 py-3 border border-red-800 w-12 align-middle">No</th>
                                        <th class="px-4 py-3 border border-red-800 align-middle text-left">Nama Siswa</th>
                                        <th class="px-4 py-3 border border-red-800 w-32 align-middle">NISN</th>
                                        <th class="px-4 py-3 border border-red-800 w-32 align-middle">NIS</th>
                                        <th class="px-4 py-3 border border-red-800 w-32 align-middle">Rombel</th>
                                        <th class="px-4 py-3 border border-red-800 w-48 align-middle">File Pelengkap Rapor</th>
                                        <th class="px-4 py-3 border border-red-800 w-32 align-middle">Cetak Ulang PDF</th>
                                        <th class="px-4 py-3 border border-red-800 w-48 align-middle">
                                            <div x-data="{ openAksi: false }" class="relative inline-block text-left">
                                                <button @click="openAksi = !openAksi" @click.away="openAksi = false" type="button" class="inline-flex justify-center items-center w-full rounded-md border border-yellow-600 shadow-sm px-4 py-2 bg-yellow-600 text-xs font-semibold text-white hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 transition">
                                                    Aksi <svg class="-mr-1 ml-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
                                                </button>
                                                <div x-show="openAksi" style="display: none;" class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 divide-y divide-gray-100 z-50">
                                                    <div class="py-1">
                                                        <form method="POST" action="{{ route('walikelas.cetak_nilai.pelengkap.toggle') }}">
                                                            @csrf
                                                            <input type="hidden" name="action" value="show_all">
                                                            <button type="submit" class="group flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900 w-full text-left font-normal">
                                                                <svg class="mr-3 h-4 w-4 text-gray-400 group-hover:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                                                Tampilkan Semua
                                                            </button>
                                                        </form>
                                                        <form method="POST" action="{{ route('walikelas.cetak_nilai.pelengkap.toggle') }}">
                                                            @csrf
                                                            <input type="hidden" name="action" value="hide_all">
                                                            <button type="submit" class="group flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900 w-full text-left font-normal">
                                                                <svg class="mr-3 h-4 w-4 text-gray-400 group-hover:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                                                Sembunyikan Semua
                                                            </button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($siswas as $index => $siswa)
                                    <tr class="hover:bg-red-50 transition-colors {{ $index % 2 == 0 ? 'bg-red-50/20' : 'bg-white' }}">
                                        <td class="px-4 py-3 text-sm text-center border border-gray-300">{{ $loop->iteration }}</td>
                                        <td class="px-4 py-3 text-sm border border-gray-300 font-medium uppercase">{{ $siswa->nama_lengkap }}</td>
                                        <td class="px-4 py-3 text-sm text-center border border-gray-300">{{ $siswa->nisn }}</td>
                                        <td class="px-4 py-3 text-sm text-center border border-gray-300">{{ $siswa->nis }}</td>
                                        <td class="px-4 py-3 text-sm text-center border border-gray-300 uppercase">{{ $rombel->tingkat . ' ' . $rombel->nama_rombel }}</td>
                                        <td class="px-4 py-3 text-sm text-center border border-gray-300">
                                            <a href="{{ route('walikelas.cetak_nilai.pelengkap.generate', $siswa->id) }}" target="_blank" class="text-blue-500 hover:text-blue-700 hover:underline uppercase font-semibold text-xs">Pelengkap Rapor {{ $siswa->nama_lengkap }}</a>
                                        </td>
                                        <td class="px-4 py-3 text-sm text-center border border-gray-300">
                                            <a href="{{ route('walikelas.cetak_nilai.pelengkap.generate', $siswa->id) }}" target="_blank" class="inline-flex items-center px-3 py-1.5 bg-red-900 border border-transparent rounded-md font-bold text-xs text-white uppercase tracking-widest hover:bg-red-800 shadow-sm transition">
                                                <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                                                Buat
                                            </a>
                                        </td>
                                        <td class="px-4 py-3 text-sm text-center border border-gray-300">
                                            <form method="POST" action="{{ route('walikelas.cetak_nilai.pelengkap.toggle') }}">
                                                @csrf
                                                <input type="hidden" name="action" value="toggle">
                                                <input type="hidden" name="siswa_id" value="{{ $siswa->id }}">
                                                
                                                @if($siswa->is_pelengkap_published)
                                                <button type="submit" class="inline-flex items-center px-3 py-1.5 bg-red-900 border border-transparent rounded-md font-bold text-xs text-white tracking-wide hover:bg-red-800 shadow-sm transition w-full justify-center">
                                                    <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
                                                    Sembunyikan dari Siswa
                                                </button>
                                                @else
                                                <button type="submit" class="inline-flex items-center px-3 py-1.5 bg-red-900 border border-transparent rounded-md font-bold text-xs text-white tracking-wide hover:bg-red-800 shadow-sm transition w-full justify-center">
                                                    <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                                                    Tampilkan pada Siswa
                                                </button>
                                                @endif
                                            </form>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
