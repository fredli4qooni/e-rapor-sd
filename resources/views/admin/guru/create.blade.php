<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Tambah Data Guru') }}
        </h2>
    </x-slot>

    <div class="py-6 space-y-6 max-w-7xl mx-auto sm:px-6 lg:px-8">
        
        <div class="bg-white rounded-md shadow-md overflow-hidden">
            <div class="bg-[#8B1515] text-white px-6 py-4 font-bold tracking-wider uppercase text-sm border-b border-red-800 flex justify-between items-center">
                <span>Form Tambah Guru</span>
                <a href="{{ route('admin.guru.index') }}" class="text-sm font-semibold text-red-100 hover:text-white">Kembali</a>
            </div>
            
            <form action="{{ route('admin.guru.store') }}" method="POST" class="p-6">
                @csrf

                <div class="space-y-4 max-w-2xl">
                    <div>
                        <x-input-label for="nama_lengkap" :value="__('Nama Lengkap (tanpa gelar)')" class="text-gray-700 font-semibold" />
                        <x-text-input id="nama_lengkap" name="nama_lengkap" type="text" class="mt-1 block w-full" :value="old('nama_lengkap')" required autofocus />
                        <x-input-error class="mt-2" :messages="$errors->get('nama_lengkap')" />
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <x-input-label for="gelar_depan" :value="__('Gelar Depan')" class="text-gray-700 font-semibold" />
                            <x-text-input id="gelar_depan" name="gelar_depan" type="text" class="mt-1 block w-full" :value="old('gelar_depan')" placeholder="Cth: Drs." />
                            <x-input-error class="mt-2" :messages="$errors->get('gelar_depan')" />
                        </div>
                        <div>
                            <x-input-label for="gelar_belakang" :value="__('Gelar Belakang')" class="text-gray-700 font-semibold" />
                            <x-text-input id="gelar_belakang" name="gelar_belakang" type="text" class="mt-1 block w-full" :value="old('gelar_belakang')" placeholder="Cth: S.Pd." />
                            <x-input-error class="mt-2" :messages="$errors->get('gelar_belakang')" />
                        </div>
                    </div>

                    <div>
                        <x-input-label for="nip" :value="__('NIP (Opsional)')" class="text-gray-700 font-semibold" />
                        <x-text-input id="nip" name="nip" type="text" class="mt-1 block w-full" :value="old('nip')" />
                        <x-input-error class="mt-2" :messages="$errors->get('nip')" />
                    </div>
                </div>

                <div class="flex items-center justify-start mt-8 border-t border-gray-200 pt-6">
                    <x-primary-button class="bg-[#8B1515] hover:bg-red-700">
                        {{ __('Simpan Guru') }}
                    </x-primary-button>
                </div>
            </form>
        </div>

    </div>
</x-app-layout>
