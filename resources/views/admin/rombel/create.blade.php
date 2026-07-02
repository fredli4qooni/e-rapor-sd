<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Tambah Data Kelas / Rombel') }}
        </h2>
    </x-slot>

    <div class="py-6 space-y-6 max-w-7xl mx-auto sm:px-6 lg:px-8">
        
        <div class="bg-white rounded-md shadow-md overflow-hidden">
            <div class="bg-[#8B1515] text-white px-6 py-4 font-bold tracking-wider uppercase text-sm border-b border-red-800 flex justify-between items-center">
                <span>Form Tambah Rombel</span>
                <a href="{{ route('admin.rombel.index') }}" class="text-sm font-semibold text-red-100 hover:text-white">Kembali</a>
            </div>
            
            <form action="{{ route('admin.rombel.store') }}" method="POST" class="p-6">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-4">
                        <div>
                            <x-input-label for="nama_rombel" :value="__('Nama Rombel')" class="text-gray-700 font-semibold" />
                            <x-text-input id="nama_rombel" name="nama_rombel" type="text" class="mt-1 block w-full" :value="old('nama_rombel')" placeholder="Cth: Kelas 1A" required autofocus />
                            <x-input-error class="mt-2" :messages="$errors->get('nama_rombel')" />
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <x-input-label for="tingkat" :value="__('Tingkat Kelas')" class="text-gray-700 font-semibold" />
                                <select id="tingkat" name="tingkat" class="mt-1 block w-full border-gray-300 focus:border-red-500 focus:ring-red-500 rounded-md shadow-sm" required>
                                    <option value="">-- Pilih --</option>
                                    @for($i=1; $i<=6; $i++)
                                        <option value="{{ $i }}" {{ old('tingkat') == $i ? 'selected' : '' }}>Kelas {{ $i }}</option>
                                    @endfor
                                </select>
                                <x-input-error class="mt-2" :messages="$errors->get('tingkat')" />
                            </div>
                            <div>
                                <x-input-label for="fase" :value="__('Fase (Kurikulum Merdeka)')" class="text-gray-700 font-semibold" />
                                <select id="fase" name="fase" class="mt-1 block w-full border-gray-300 focus:border-red-500 focus:ring-red-500 rounded-md shadow-sm">
                                    <option value="">-- Pilih --</option>
                                    <option value="A" {{ old('fase') == 'A' ? 'selected' : '' }}>Fase A (Kelas 1-2)</option>
                                    <option value="B" {{ old('fase') == 'B' ? 'selected' : '' }}>Fase B (Kelas 3-4)</option>
                                    <option value="C" {{ old('fase') == 'C' ? 'selected' : '' }}>Fase C (Kelas 5-6)</option>
                                </select>
                                <x-input-error class="mt-2" :messages="$errors->get('fase')" />
                            </div>
                        </div>

                        <div>
                            <x-input-label for="jenis_rombel" :value="__('Jenis Rombel')" class="text-gray-700 font-semibold" />
                            <select id="jenis_rombel" name="jenis_rombel" class="mt-1 block w-full border-gray-300 focus:border-red-500 focus:ring-red-500 rounded-md shadow-sm" required>
                                <option value="REGULER" {{ old('jenis_rombel') == 'REGULER' ? 'selected' : '' }}>REGULER</option>
                                <option value="PILIHAN" {{ old('jenis_rombel') == 'PILIHAN' ? 'selected' : '' }}>PILIHAN</option>
                                <option value="EKSKUL" {{ old('jenis_rombel') == 'EKSKUL' ? 'selected' : '' }}>EKSKUL</option>
                            </select>
                            <x-input-error class="mt-2" :messages="$errors->get('jenis_rombel')" />
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div>
                            <x-input-label for="kurikulum" :value="__('Kurikulum')" class="text-gray-700 font-semibold" />
                            <select id="kurikulum" name="kurikulum" class="mt-1 block w-full border-gray-300 focus:border-red-500 focus:ring-red-500 rounded-md shadow-sm" required>
                                <option value="Merdeka" {{ old('kurikulum') == 'Merdeka' ? 'selected' : '' }}>Kurikulum Merdeka</option>
                                <option value="K13" {{ old('kurikulum') == 'K13' ? 'selected' : '' }}>Kurikulum 2013</option>
                            </select>
                            <x-input-error class="mt-2" :messages="$errors->get('kurikulum')" />
                        </div>

                        <div>
                            <x-input-label for="wali_kelas_id" :value="__('Wali Kelas')" class="text-gray-700 font-semibold" />
                            <select id="wali_kelas_id" name="wali_kelas_id" class="mt-1 block w-full border-gray-300 focus:border-red-500 focus:ring-red-500 rounded-md shadow-sm">
                                <option value="">-- Tidak Ada / Belum Ditentukan --</option>
                                @foreach($gurus as $guru)
                                    <option value="{{ $guru->id }}" {{ old('wali_kelas_id') == $guru->id ? 'selected' : '' }}>
                                        {{ $guru->nama_lengkap }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error class="mt-2" :messages="$errors->get('wali_kelas_id')" />
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-start mt-8 border-t border-gray-200 pt-6">
                    <x-primary-button class="bg-[#8B1515] hover:bg-red-700">
                        {{ __('Simpan Rombel') }}
                    </x-primary-button>
                </div>
            </form>
        </div>

    </div>
</x-app-layout>
