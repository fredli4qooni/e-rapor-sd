<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Edit Data Ekstrakurikuler') }}
        </h2>
    </x-slot>

    <div class="py-6 space-y-6 max-w-7xl mx-auto sm:px-6 lg:px-8">
        
        <div class="bg-white rounded-md shadow-md overflow-hidden">
            <div class="bg-[#8B1515] text-white px-6 py-4 font-bold tracking-wider uppercase text-sm border-b border-red-800 flex justify-between items-center">
                <span>Form Edit Ekstrakurikuler</span>
                <a href="{{ route('admin.ekskul.index') }}" class="text-sm font-semibold text-red-100 hover:text-white">Kembali</a>
            </div>
            
            <form action="{{ route('admin.ekskul.update', $ekskul->id) }}" method="POST" class="p-6">
                @csrf
                @method('PUT')

                <div class="space-y-4 max-w-2xl">
                    <div>
                        <x-input-label for="nama_ekskul" :value="__('Nama Ekstrakurikuler')" class="text-gray-700 font-semibold" />
                        <x-text-input id="nama_ekskul" name="nama_ekskul" type="text" class="mt-1 block w-full" :value="old('nama_ekskul', $ekskul->nama_ekskul)" required autofocus />
                        <x-input-error class="mt-2" :messages="$errors->get('nama_ekskul')" />
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <x-input-label for="pembina_id" :value="__('Guru Pembina')" class="text-gray-700 font-semibold" />
                            <select id="pembina_id" name="pembina_id" class="mt-1 block w-full border-gray-300 focus:border-red-500 focus:ring-red-500 rounded-md shadow-sm">
                                <option value="">-- Pilih Pembina --</option>
                                @foreach($gurus as $guru)
                                    <option value="{{ $guru->id }}" {{ old('pembina_id', $ekskul->pembina_id) == $guru->id ? 'selected' : '' }}>
                                        {{ $guru->nama_lengkap }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error class="mt-2" :messages="$errors->get('pembina_id')" />
                        </div>
                        <div>
                            <x-input-label for="is_aktif" :value="__('Status')" class="text-gray-700 font-semibold" />
                            <select id="is_aktif" name="is_aktif" class="mt-1 block w-full border-gray-300 focus:border-red-500 focus:ring-red-500 rounded-md shadow-sm" required>
                                <option value="1" {{ old('is_aktif', $ekskul->is_aktif) == '1' ? 'selected' : '' }}>Aktif</option>
                                <option value="0" {{ old('is_aktif', $ekskul->is_aktif) == '0' ? 'selected' : '' }}>Tidak Aktif</option>
                            </select>
                            <x-input-error class="mt-2" :messages="$errors->get('is_aktif')" />
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-start mt-8 border-t border-gray-200 pt-6">
                    <x-primary-button class="bg-[#8B1515] hover:bg-red-700">
                        {{ __('Simpan Perubahan') }}
                    </x-primary-button>
                </div>
            </form>
        </div>

    </div>
</x-app-layout>
