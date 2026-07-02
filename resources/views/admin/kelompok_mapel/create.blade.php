<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Tambah Data Kelompok Mapel') }}
        </h2>
    </x-slot>

    <div class="py-6 space-y-6 max-w-7xl mx-auto sm:px-6 lg:px-8">
        
        <div class="bg-white rounded-md shadow-md overflow-hidden">
            <div class="bg-[#8B1515] text-white px-6 py-4 font-bold tracking-wider uppercase text-sm border-b border-red-800 flex justify-between items-center">
                <span>Form Tambah Kelompok Mapel</span>
                <a href="{{ route('admin.kelompok_mapel.index') }}" class="text-sm font-semibold text-red-100 hover:text-white">Kembali</a>
            </div>
            
            <form action="{{ route('admin.kelompok_mapel.store') }}" method="POST" class="p-6">
                @csrf

                <div class="space-y-4 max-w-2xl">
                    <div>
                        <x-input-label for="nama_kelompok" :value="__('Nama Kelompok')" class="text-gray-700 font-semibold" />
                        <x-text-input id="nama_kelompok" name="nama_kelompok" type="text" class="mt-1 block w-full" :value="old('nama_kelompok')" placeholder="Cth: Kelompok A (Wajib)" required autofocus />
                        <x-input-error class="mt-2" :messages="$errors->get('nama_kelompok')" />
                    </div>

                    <div>
                        <x-input-label for="jenis_kelompok" :value="__('Jenis Kelompok (Opsional)')" class="text-gray-700 font-semibold" />
                        <x-text-input id="jenis_kelompok" name="jenis_kelompok" type="text" class="mt-1 block w-full" :value="old('jenis_kelompok')" placeholder="Cth: Umum, Peminatan, Muatan Lokal" />
                        <x-input-error class="mt-2" :messages="$errors->get('jenis_kelompok')" />
                    </div>
                </div>

                <div class="flex items-center justify-start mt-8 border-t border-gray-200 pt-6">
                    <x-primary-button class="bg-[#8B1515] hover:bg-red-700">
                        {{ __('Simpan Kelompok') }}
                    </x-primary-button>
                </div>
            </form>
        </div>

    </div>
</x-app-layout>
