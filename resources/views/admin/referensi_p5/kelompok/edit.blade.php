<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Edit Kelompok Projek') }}
        </h2>
    </x-slot>

    <div class="py-6 space-y-6 max-w-7xl mx-auto sm:px-6 lg:px-8">
        
        <!-- Validation Errors -->
        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative shadow-sm" role="alert">
                <strong class="font-bold">Terjadi Kesalahan!</strong>
                <ul class="mt-1 list-disc list-inside text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-white rounded-md shadow-md overflow-hidden max-w-2xl mx-auto">
            <div class="bg-[#8B1515] text-white px-6 py-4 font-bold tracking-wider uppercase text-sm border-b border-red-800 flex justify-between items-center">
                <span>Form Edit Kelompok Projek</span>
                <a href="{{ route('admin.referensi_p5.kelompok.index') }}" class="text-sm font-semibold text-red-100 hover:text-white">Kembali</a>
            </div>
            
            <form action="{{ route('admin.referensi_p5.kelompok.update', $kelompok->id) }}" method="POST" class="p-6">
                @csrf
                @method('PUT')

                <div class="space-y-4">
                    <div>
                        <x-input-label for="nama_kelompok" :value="__('Nama Kelompok')" class="text-gray-700 font-semibold" />
                        <x-text-input id="nama_kelompok" name="nama_kelompok" type="text" class="mt-1 block w-full" :value="old('nama_kelompok', $kelompok->nama_kelompok)" required autofocus />
                        <x-input-error class="mt-2" :messages="$errors->get('nama_kelompok')" />
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <x-input-label for="tingkat_pendidikan" :value="__('Tingkat Pendidikan')" class="text-gray-700 font-semibold" />
                            <select id="tingkat_pendidikan" name="tingkat_pendidikan" class="mt-1 block w-full border-gray-300 focus:border-red-500 focus:ring-red-500 rounded-md shadow-sm">
                                <option value="">-- Pilih Tingkat --</option>
                                <option value="1" {{ old('tingkat_pendidikan', $kelompok->tingkat_pendidikan) == '1' ? 'selected' : '' }}>Tingkat 1</option>
                                <option value="2" {{ old('tingkat_pendidikan', $kelompok->tingkat_pendidikan) == '2' ? 'selected' : '' }}>Tingkat 2</option>
                                <option value="3" {{ old('tingkat_pendidikan', $kelompok->tingkat_pendidikan) == '3' ? 'selected' : '' }}>Tingkat 3</option>
                                <option value="4" {{ old('tingkat_pendidikan', $kelompok->tingkat_pendidikan) == '4' ? 'selected' : '' }}>Tingkat 4</option>
                                <option value="5" {{ old('tingkat_pendidikan', $kelompok->tingkat_pendidikan) == '5' ? 'selected' : '' }}>Tingkat 5</option>
                                <option value="6" {{ old('tingkat_pendidikan', $kelompok->tingkat_pendidikan) == '6' ? 'selected' : '' }}>Tingkat 6</option>
                            </select>
                            <x-input-error class="mt-2" :messages="$errors->get('tingkat_pendidikan')" />
                        </div>
                        <div>
                            <x-input-label for="fase" :value="__('Fase')" class="text-gray-700 font-semibold" />
                            <select id="fase" name="fase" class="mt-1 block w-full border-gray-300 focus:border-red-500 focus:ring-red-500 rounded-md shadow-sm" required>
                                <option value="">-- Pilih Fase --</option>
                                <option value="A" {{ old('fase', $kelompok->fase) == 'A' ? 'selected' : '' }}>Fase A</option>
                                <option value="B" {{ old('fase', $kelompok->fase) == 'B' ? 'selected' : '' }}>Fase B</option>
                                <option value="C" {{ old('fase', $kelompok->fase) == 'C' ? 'selected' : '' }}>Fase C</option>
                            </select>
                            <x-input-error class="mt-2" :messages="$errors->get('fase')" />
                        </div>
                    </div>

                    <div>
                        <x-input-label for="guru_id" :value="__('Guru Koordinator')" class="text-gray-700 font-semibold" />
                        <select id="guru_id" name="guru_id" class="mt-1 block w-full border-gray-300 focus:border-red-500 focus:ring-red-500 rounded-md shadow-sm" required>
                            <option value="">-- Pilih Koordinator --</option>
                            @foreach($gurus as $guru)
                                <option value="{{ $guru->id }}" {{ old('guru_id', $kelompok->guru_id) == $guru->id ? 'selected' : '' }}>
                                    {{ $guru->name }}
                                </option>
                            @endforeach
                        </select>
                        <x-input-error class="mt-2" :messages="$errors->get('guru_id')" />
                    </div>
                </div>

                <div class="mt-6 flex justify-end">
                    <x-primary-button class="bg-red-600 hover:bg-red-700">
                        {{ __('Simpan Perubahan') }}
                    </x-primary-button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
