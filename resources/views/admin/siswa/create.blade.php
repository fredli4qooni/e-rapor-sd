<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Tambah Data Siswa') }}
        </h2>
    </x-slot>

    <div class="py-6 space-y-6 max-w-7xl mx-auto sm:px-6 lg:px-8">
        
        <div class="bg-white rounded-md shadow-md overflow-hidden">
            <div class="bg-[#8B1515] text-white px-6 py-4 font-bold tracking-wider uppercase text-sm border-b border-red-800 flex justify-between items-center">
                <span>Form Tambah Siswa</span>
                <a href="{{ route('admin.siswa.index') }}" class="text-sm font-semibold text-red-100 hover:text-white">Kembali</a>
            </div>
            
            <form action="{{ route('admin.siswa.store') }}" method="POST" enctype="multipart/form-data" class="p-6">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-4">
                        <div>
                            <x-input-label for="nisn" :value="__('NISN (Unik)')" class="text-gray-700 font-semibold" />
                            <x-text-input id="nisn" name="nisn" type="text" class="mt-1 block w-full" :value="old('nisn')" required autofocus />
                            <x-input-error class="mt-2" :messages="$errors->get('nisn')" />
                        </div>

                        <div>
                            <x-input-label for="nis" :value="__('NIS Lokal (Opsional)')" class="text-gray-700 font-semibold" />
                            <x-text-input id="nis" name="nis" type="text" class="mt-1 block w-full" :value="old('nis')" />
                            <x-input-error class="mt-2" :messages="$errors->get('nis')" />
                        </div>

                        <div>
                            <x-input-label for="nama_lengkap" :value="__('Nama Lengkap Siswa')" class="text-gray-700 font-semibold" />
                            <x-text-input id="nama_lengkap" name="nama_lengkap" type="text" class="mt-1 block w-full" :value="old('nama_lengkap')" required />
                            <x-input-error class="mt-2" :messages="$errors->get('nama_lengkap')" />
                        </div>

                        <div>
                            <x-input-label for="jenis_kelamin" :value="__('Jenis Kelamin')" class="text-gray-700 font-semibold" />
                            <select id="jenis_kelamin" name="jenis_kelamin" class="mt-1 block w-full border-gray-300 focus:border-red-500 focus:ring-red-500 rounded-md shadow-sm" required>
                                <option value="">-- Pilih --</option>
                                <option value="L" {{ old('jenis_kelamin') == 'L' ? 'selected' : '' }}>Laki-laki (L)</option>
                                <option value="P" {{ old('jenis_kelamin') == 'P' ? 'selected' : '' }}>Perempuan (P)</option>
                            </select>
                            <x-input-error class="mt-2" :messages="$errors->get('jenis_kelamin')" />
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div>
                            <x-input-label for="tempat_lahir" :value="__('Tempat Lahir')" class="text-gray-700 font-semibold" />
                            <x-text-input id="tempat_lahir" name="tempat_lahir" type="text" class="mt-1 block w-full" :value="old('tempat_lahir')" />
                            <x-input-error class="mt-2" :messages="$errors->get('tempat_lahir')" />
                        </div>

                        <div>
                            <x-input-label for="tanggal_lahir" :value="__('Tanggal Lahir')" class="text-gray-700 font-semibold" />
                            <x-text-input id="tanggal_lahir" name="tanggal_lahir" type="date" class="mt-1 block w-full" :value="old('tanggal_lahir')" />
                            <x-input-error class="mt-2" :messages="$errors->get('tanggal_lahir')" />
                        </div>

                        <div class="bg-white p-4 border border-gray-200 rounded mt-4">
                            <h3 class="font-bold text-gray-800 border-b pb-2 mb-3">Foto Siswa</h3>
                            <div>
                                <input type="file" id="foto" name="foto" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100" />
                                <p class="text-xs text-gray-500 mt-1">Format: JPG, PNG. Maks: 2MB.</p>
                                <x-input-error class="mt-2" :messages="$errors->get('foto')" />
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-start mt-8 border-t border-gray-200 pt-6">
                    <x-primary-button class="bg-[#8B1515] hover:bg-red-700">
                        {{ __('Simpan Siswa') }}
                    </x-primary-button>
                </div>
            </form>
        </div>

    </div>
</x-app-layout>
