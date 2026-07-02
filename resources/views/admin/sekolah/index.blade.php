<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Data Sekolah') }}
        </h2>
    </x-slot>

    <div class="py-6 space-y-6 max-w-7xl mx-auto sm:px-6 lg:px-8">
        
        <!-- Flash Message -->
        @if (session('status'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative shadow-sm" role="alert">
                <span class="block sm:inline">{{ session('status') }}</span>
            </div>
        @endif

        <div class="bg-white rounded-md shadow-md overflow-hidden">
            <div class="bg-[#8B1515] text-white px-6 py-4 font-bold tracking-wider uppercase text-sm border-b border-red-800">
                Ubah Profil Sekolah
            </div>
            
            <form action="{{ route('admin.sekolah.update') }}" method="POST" enctype="multipart/form-data" class="p-6">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Left Column -->
                    <div class="space-y-4">
                        <div>
                            <x-input-label for="npsn" :value="__('NPSN Sekolah')" class="text-gray-700 font-semibold" />
                            <x-text-input id="npsn" name="npsn" type="text" class="mt-1 block w-full bg-gray-50 border-gray-300 focus:border-red-500 focus:ring-red-500" :value="old('npsn', $sekolah->npsn)" required />
                            <x-input-error class="mt-2" :messages="$errors->get('npsn')" />
                        </div>

                        <div>
                            <x-input-label for="nama_sekolah" :value="__('Nama Sekolah')" class="text-gray-700 font-semibold" />
                            <x-text-input id="nama_sekolah" name="nama_sekolah" type="text" class="mt-1 block w-full bg-gray-50 border-gray-300 focus:border-red-500 focus:ring-red-500" :value="old('nama_sekolah', $sekolah->nama_sekolah)" required />
                            <x-input-error class="mt-2" :messages="$errors->get('nama_sekolah')" />
                        </div>

                        <div>
                            <x-input-label for="alamat" :value="__('Alamat Lengkap')" class="text-gray-700 font-semibold" />
                            <textarea id="alamat" name="alamat" rows="3" class="mt-1 block w-full bg-gray-50 border-gray-300 focus:border-red-500 focus:ring-red-500 rounded-md shadow-sm">{{ old('alamat', $sekolah->alamat) }}</textarea>
                            <x-input-error class="mt-2" :messages="$errors->get('alamat')" />
                        </div>
                    </div>

                    <!-- Right Column -->
                    <div class="space-y-4">
                        <div>
                            <x-input-label for="kecamatan" :value="__('Kecamatan')" class="text-gray-700 font-semibold" />
                            <x-text-input id="kecamatan" name="kecamatan" type="text" class="mt-1 block w-full bg-gray-50 border-gray-300 focus:border-red-500 focus:ring-red-500" :value="old('kecamatan', $sekolah->kecamatan)" />
                            <x-input-error class="mt-2" :messages="$errors->get('kecamatan')" />
                        </div>

                        <div>
                            <x-input-label for="kabupaten" :value="__('Kabupaten/Kota')" class="text-gray-700 font-semibold" />
                            <x-text-input id="kabupaten" name="kabupaten" type="text" class="mt-1 block w-full bg-gray-50 border-gray-300 focus:border-red-500 focus:ring-red-500" :value="old('kabupaten', $sekolah->kabupaten)" />
                            <x-input-error class="mt-2" :messages="$errors->get('kabupaten')" />
                        </div>

                        <div>
                            <x-input-label for="provinsi" :value="__('Provinsi')" class="text-gray-700 font-semibold" />
                            <x-text-input id="provinsi" name="provinsi" type="text" class="mt-1 block w-full bg-gray-50 border-gray-300 focus:border-red-500 focus:ring-red-500" :value="old('provinsi', $sekolah->provinsi)" />
                            <x-input-error class="mt-2" :messages="$errors->get('provinsi')" />
                        </div>
                    </div>
                </div>

                <!-- Logo Section -->
                <div class="mt-8 border-t border-gray-200 pt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Logo Sekolah -->
                    <div>
                        <x-input-label for="logo_sekolah" :value="__('Upload Logo Sekolah')" class="text-gray-700 font-semibold mb-2" />
                        <div class="flex items-center gap-4">
                            @if($sekolah->logo_sekolah)
                                <div class="w-16 h-16 rounded border bg-gray-100 flex items-center justify-center overflow-hidden shrink-0">
                                    <img src="{{ asset('storage/' . $sekolah->logo_sekolah) }}" alt="Logo" class="max-h-full max-w-full object-contain">
                                </div>
                            @endif
                            <input type="file" id="logo_sekolah" name="logo_sekolah" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-sm file:font-semibold file:bg-red-50 file:text-red-700 hover:file:bg-red-100" />
                        </div>
                        <p class="text-xs text-gray-500 mt-1">Format: JPG, PNG. Maksimal: 2MB.</p>
                        <x-input-error class="mt-2" :messages="$errors->get('logo_sekolah')" />
                    </div>

                    <!-- Logo Pemda -->
                    <div>
                        <x-input-label for="logo_pemda" :value="__('Upload Logo Pemda')" class="text-gray-700 font-semibold mb-2" />
                        <div class="flex items-center gap-4">
                            @if($sekolah->logo_pemda)
                                <div class="w-16 h-16 rounded border bg-gray-100 flex items-center justify-center overflow-hidden shrink-0">
                                    <img src="{{ asset('storage/' . $sekolah->logo_pemda) }}" alt="Logo Pemda" class="max-h-full max-w-full object-contain">
                                </div>
                            @endif
                            <input type="file" id="logo_pemda" name="logo_pemda" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-sm file:font-semibold file:bg-red-50 file:text-red-700 hover:file:bg-red-100" />
                        </div>
                        <p class="text-xs text-gray-500 mt-1">Format: JPG, PNG. Maksimal: 2MB.</p>
                        <x-input-error class="mt-2" :messages="$errors->get('logo_pemda')" />
                    </div>

                    <!-- Kop Sekolah -->
                    <div>
                        <x-input-label for="kop_sekolah" :value="__('Upload Kop Rapor (Header)')" class="text-gray-700 font-semibold mb-2" />
                        <div class="flex items-center gap-4">
                            @if($sekolah->kop_sekolah)
                                <div class="w-24 h-12 rounded border bg-gray-100 flex items-center justify-center overflow-hidden shrink-0 p-1">
                                    <img src="{{ asset('storage/' . $sekolah->kop_sekolah) }}" alt="Kop" class="max-h-full max-w-full object-contain">
                                </div>
                            @endif
                            <input type="file" id="kop_sekolah" name="kop_sekolah" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-sm file:font-semibold file:bg-red-50 file:text-red-700 hover:file:bg-red-100" />
                        </div>
                        <p class="text-xs text-gray-500 mt-1">Format: JPG, PNG. Rekomendasi ukuran memanjang. Maks: 2MB.</p>
                        <x-input-error class="mt-2" :messages="$errors->get('kop_sekolah')" />
                    </div>

                    <!-- TTD Kepsek -->
                    <div>
                        <x-input-label for="ttd_kepsek" :value="__('Upload TTD Kepala Sekolah (Stempel & TTD)')" class="text-gray-700 font-semibold mb-2" />
                        <div class="flex items-center gap-4">
                            @if($sekolah->ttd_kepsek)
                                <div class="w-24 h-12 rounded border bg-gray-100 flex items-center justify-center overflow-hidden shrink-0 p-1">
                                    <img src="{{ asset('storage/' . $sekolah->ttd_kepsek) }}" alt="TTD Kepsek" class="max-h-full max-w-full object-contain">
                                </div>
                            @endif
                            <input type="file" id="ttd_kepsek" name="ttd_kepsek" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-sm file:font-semibold file:bg-red-50 file:text-red-700 hover:file:bg-red-100" />
                        </div>
                        <p class="text-xs text-gray-500 mt-1">Format: JPG, PNG dengan background transparan. Maks: 2MB.</p>
                        <x-input-error class="mt-2" :messages="$errors->get('ttd_kepsek')" />
                    </div>
                </div>

                <div class="flex items-center justify-end mt-8 border-t border-gray-200 pt-6">
                    <x-primary-button class="bg-[#8B1515] hover:bg-red-700">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
                        {{ __('Simpan Perubahan') }}
                    </x-primary-button>
                </div>
            </form>
        </div>

        @if($kepsek)
        <div class="bg-white rounded-md shadow-md overflow-hidden mt-8">
            <div class="bg-gray-800 text-white px-6 py-4 font-bold tracking-wider uppercase text-sm border-b border-gray-900">
                Ubah Data Kepala Sekolah
            </div>
            
            <form action="{{ route('admin.sekolah.updateKepsek') }}" method="POST" class="p-6">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <x-input-label for="nama_lengkap" :value="__('Nama Lengkap (Tanpa Gelar)')" class="text-gray-700 font-semibold" />
                        <x-text-input id="nama_lengkap" name="nama_lengkap" type="text" class="mt-1 block w-full bg-gray-50 border-gray-300 focus:border-red-500 focus:ring-red-500" :value="old('nama_lengkap', $kepsek->nama_lengkap)" required />
                        <x-input-error class="mt-2" :messages="$errors->get('nama_lengkap')" />
                    </div>
                    <div>
                        <x-input-label for="nip_tampil" :value="__('NIP/NIY (Tampil di Rapor)')" class="text-gray-700 font-semibold" />
                        <x-text-input id="nip_tampil" name="nip_tampil" type="text" class="mt-1 block w-full bg-gray-50 border-gray-300 focus:border-red-500 focus:ring-red-500" :value="old('nip_tampil', $kepsek->nip_tampil)" />
                        <x-input-error class="mt-2" :messages="$errors->get('nip_tampil')" />
                    </div>
                    <div>
                        <x-input-label for="gelar_depan" :value="__('Gelar Depan')" class="text-gray-700 font-semibold" />
                        <x-text-input id="gelar_depan" name="gelar_depan" type="text" class="mt-1 block w-full bg-gray-50 border-gray-300 focus:border-red-500 focus:ring-red-500" :value="old('gelar_depan', $kepsek->gelar_depan)" />
                        <x-input-error class="mt-2" :messages="$errors->get('gelar_depan')" />
                    </div>
                    <div>
                        <x-input-label for="gelar_belakang" :value="__('Gelar Belakang')" class="text-gray-700 font-semibold" />
                        <x-text-input id="gelar_belakang" name="gelar_belakang" type="text" class="mt-1 block w-full bg-gray-50 border-gray-300 focus:border-red-500 focus:ring-red-500" :value="old('gelar_belakang', $kepsek->gelar_belakang)" />
                        <x-input-error class="mt-2" :messages="$errors->get('gelar_belakang')" />
                    </div>
                </div>

                <div class="flex items-center justify-end mt-6">
                    <x-primary-button class="bg-gray-800 hover:bg-gray-700">
                        {{ __('Simpan Kepala Sekolah') }}
                    </x-primary-button>
                </div>
            </form>
        </div>
        @endif

    </div>
</x-app-layout>
