<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Setting Tampilan Transkrip Nilai Ijazah') }}
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

        <form action="{{ route('admin.transkrip_ijazah.setting.store') }}" method="POST">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Data Utama -->
                <div class="bg-white rounded-md shadow-md overflow-hidden">
                    <div class="bg-[#8B1515] text-white px-6 py-4 font-bold tracking-wider uppercase text-sm border-b border-red-800">
                        Pengaturan Data
                    </div>
                    <div class="p-6 space-y-4">
                        <div>
                            <label class="block font-semibold text-gray-700 text-sm mb-1">Tampilan Nama Siswa</label>
                            <select name="tampilan_nama_siswa" class="w-full border-gray-300 rounded-md shadow-sm focus:border-red-500 focus:ring-red-500 text-sm">
                                <option value="huruf_kapital" {{ $setting->tampilan_nama_siswa == 'huruf_kapital' ? 'selected' : '' }}>HURUF KAPITAL (Semua Besar)</option>
                                <option value="sesuai_data" {{ $setting->tampilan_nama_siswa == 'sesuai_data' ? 'selected' : '' }}>Sesuai Data Input</option>
                            </select>
                        </div>

                        <div>
                            <label class="block font-semibold text-gray-700 text-sm mb-1">Jumlah Angka Desimal Nilai</label>
                            <input type="number" name="jumlah_angka_desimal" value="{{ $setting->jumlah_angka_desimal }}" class="w-full border-gray-300 rounded-md shadow-sm focus:border-red-500 focus:ring-red-500 text-sm" min="0" max="2" required>
                        </div>

                        <div class="flex items-center space-x-2 mt-4">
                            <input type="checkbox" id="tampilkan_baris_rata_rata" name="tampilkan_baris_rata_rata" value="1" {{ $setting->tampilkan_baris_rata_rata ? 'checked' : '' }} class="rounded border-gray-300 text-red-600 shadow-sm focus:border-red-300 focus:ring focus:ring-red-200 focus:ring-opacity-50">
                            <label for="tampilkan_baris_rata_rata" class="font-semibold text-gray-700 text-sm">Tampilkan Baris Rata-Rata Nilai</label>
                        </div>

                        <div>
                            <label class="block font-semibold text-gray-700 text-sm mb-1">Jumlah Angka Desimal Rata-Rata</label>
                            <input type="number" name="angka_desimal_rata_rata" value="{{ $setting->angka_desimal_rata_rata }}" class="w-full border-gray-300 rounded-md shadow-sm focus:border-red-500 focus:ring-red-500 text-sm" min="0" max="2" required>
                        </div>

                        <div>
                            <label class="block font-semibold text-gray-700 text-sm mb-1">Tempat dan Tanggal Transkrip</label>
                            <input type="text" name="tempat_tanggal_transkrip" value="{{ $setting->tempat_tanggal_transkrip }}" class="w-full border-gray-300 rounded-md shadow-sm focus:border-red-500 focus:ring-red-500 text-sm" required>
                        </div>

                        <div>
                            <label class="block font-semibold text-gray-700 text-sm mb-1">Nama Kepala Sekolah</label>
                            <input type="text" name="nama_kepala_sekolah" value="{{ $setting->nama_kepala_sekolah }}" class="w-full border-gray-300 rounded-md shadow-sm focus:border-red-500 focus:ring-red-500 text-sm" required>
                        </div>

                        <div>
                            <label class="block font-semibold text-gray-700 text-sm mb-1">NIP Kepala Sekolah</label>
                            <input type="text" name="nip_kepala_sekolah" value="{{ $setting->nip_kepala_sekolah }}" class="w-full border-gray-300 rounded-md shadow-sm focus:border-red-500 focus:ring-red-500 text-sm">
                        </div>

                        <div class="flex items-center space-x-2 mt-4">
                            <input type="checkbox" id="tampilkan_ttd_kepala_sekolah" name="tampilkan_ttd_kepala_sekolah" value="1" {{ $setting->tampilkan_ttd_kepala_sekolah ? 'checked' : '' }} class="rounded border-gray-300 text-red-600 shadow-sm focus:border-red-300 focus:ring focus:ring-red-200 focus:ring-opacity-50">
                            <label for="tampilkan_ttd_kepala_sekolah" class="font-semibold text-gray-700 text-sm">Tampilkan Tanda Tangan Kepala Sekolah (Bila ada)</label>
                        </div>
                    </div>
                </div>

                <!-- Pengaturan Cetak (Margin & Layout) -->
                <div class="bg-white rounded-md shadow-md overflow-hidden flex flex-col justify-between">
                    <div>
                        <div class="bg-gray-800 text-white px-6 py-4 font-bold tracking-wider uppercase text-sm border-b border-gray-900">
                            Pengaturan Cetak & Layout
                        </div>
                        <div class="p-6 space-y-4">
                            <div>
                                <label class="block font-semibold text-gray-700 text-sm mb-1">Ukuran Kertas</label>
                                <select name="ukuran_kertas" class="w-full border-gray-300 rounded-md shadow-sm focus:border-red-500 focus:ring-red-500 text-sm">
                                    <option value="A4" {{ $setting->ukuran_kertas == 'A4' ? 'selected' : '' }}>A4 (210 x 297 mm)</option>
                                    <option value="F4" {{ $setting->ukuran_kertas == 'F4' ? 'selected' : '' }}>F4 / Folio (215 x 330 mm)</option>
                                </select>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block font-semibold text-gray-700 text-sm mb-1">Margin Kiri (mm)</label>
                                    <input type="number" name="margin_kiri" value="{{ $setting->margin_kiri }}" class="w-full border-gray-300 rounded-md shadow-sm focus:border-red-500 focus:ring-red-500 text-sm" required>
                                </div>
                                <div>
                                    <label class="block font-semibold text-gray-700 text-sm mb-1">Margin Kanan (mm)</label>
                                    <input type="number" name="margin_kanan" value="{{ $setting->margin_kanan }}" class="w-full border-gray-300 rounded-md shadow-sm focus:border-red-500 focus:ring-red-500 text-sm" required>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block font-semibold text-gray-700 text-sm mb-1">Margin Atas (mm)</label>
                                    <input type="number" name="margin_atas" value="{{ $setting->margin_atas }}" class="w-full border-gray-300 rounded-md shadow-sm focus:border-red-500 focus:ring-red-500 text-sm" required>
                                </div>
                                <div>
                                    <label class="block font-semibold text-gray-700 text-sm mb-1">Margin Bawah (mm)</label>
                                    <input type="number" name="margin_bawah" value="{{ $setting->margin_bawah }}" class="w-full border-gray-300 rounded-md shadow-sm focus:border-red-500 focus:ring-red-500 text-sm" required>
                                </div>
                            </div>

                            <div>
                                <label class="block font-semibold text-gray-700 text-sm mb-1">Jarak Antar Identitas (mm)</label>
                                <input type="number" name="jarak_antar_identitas" value="{{ $setting->jarak_antar_identitas }}" class="w-full border-gray-300 rounded-md shadow-sm focus:border-red-500 focus:ring-red-500 text-sm" required>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block font-semibold text-gray-700 text-sm mb-1">Tinggi Judul Tabel (mm)</label>
                                    <input type="number" name="tinggi_judul" value="{{ $setting->tinggi_judul }}" class="w-full border-gray-300 rounded-md shadow-sm focus:border-red-500 focus:ring-red-500 text-sm" required>
                                </div>
                                <div>
                                    <label class="block font-semibold text-gray-700 text-sm mb-1">Tinggi Isi Tabel (mm)</label>
                                    <input type="number" name="tinggi_isi_tabel" value="{{ $setting->tinggi_isi_tabel }}" class="w-full border-gray-300 rounded-md shadow-sm focus:border-red-500 focus:ring-red-500 text-sm" required>
                                </div>
                            </div>

                            <div>
                                <label class="block font-semibold text-gray-700 text-sm mb-1">Persentase Ukuran Kop Sekolah (%)</label>
                                <input type="number" name="persentase_kop" value="{{ $setting->persentase_kop }}" class="w-full border-gray-300 rounded-md shadow-sm focus:border-red-500 focus:ring-red-500 text-sm" required min="10" max="200">
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-gray-50 px-6 py-4 border-t text-right">
                        <button type="submit" class="inline-flex items-center px-6 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-sm text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-800 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150 shadow-md">
                            Simpan Pengaturan
                        </button>
                    </div>
                </div>

            </div>
        </form>

    </div>
</x-app-layout>
