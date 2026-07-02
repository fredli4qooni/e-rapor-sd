<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Tambah Mapping Pembelajaran') }}
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

        <div class="bg-white rounded-md shadow-md overflow-hidden">
            <div class="bg-[#8B1515] text-white px-6 py-4 font-bold tracking-wider uppercase text-sm border-b border-red-800 flex justify-between items-center">
                <span>{{ $parent_mapel_id ? 'Form Tambah Sub Pembelajaran' : 'Form Tambah Mapping Pembelajaran' }}</span>
                <a href="{{ route('admin.pembelajaran.index', ['rombel_id' => $selected_rombel]) }}" class="text-sm font-semibold text-red-100 hover:text-white">Kembali</a>
            </div>
            
            <form action="{{ route('admin.pembelajaran.store') }}" method="POST" class="p-6">
                @csrf

                <div class="space-y-4 max-w-2xl">
                    <div>
                        <x-input-label for="rombel_id" :value="__('Kelas / Rombel')" class="text-gray-700 font-semibold" />
                        <select id="rombel_id" name="rombel_id" class="mt-1 block w-full border-gray-300 focus:border-red-500 focus:ring-red-500 rounded-md shadow-sm" required autofocus>
                            <option value="">-- Pilih Rombel --</option>
                            @foreach($rombels as $rombel)
                                <option value="{{ $rombel->id }}" {{ (old('rombel_id') ?? $selected_rombel) == $rombel->id ? 'selected' : '' }}>
                                    {{ $rombel->nama_rombel }} (Tingkat {{ $rombel->tingkat }})
                                </option>
                            @endforeach
                        </select>
                        <x-input-error class="mt-2" :messages="$errors->get('rombel_id')" />
                    </div>

                    <div>
                        <x-input-label for="mata_pelajaran_id" :value="__('Mata Pelajaran')" class="text-gray-700 font-semibold" />
                        <select id="mata_pelajaran_id" name="mata_pelajaran_id" class="mt-1 block w-full border-gray-300 focus:border-red-500 focus:ring-red-500 rounded-md shadow-sm" required>
                            <option value="">-- Pilih Mata Pelajaran --</option>
                            @foreach($mapels as $mapel)
                                <option value="{{ $mapel->id }}" {{ old('mata_pelajaran_id') == $mapel->id ? 'selected' : '' }}>
                                    {{ $mapel->nama_mapel }}
                                </option>
                            @endforeach
                        </select>
                        <x-input-error class="mt-2" :messages="$errors->get('mata_pelajaran_id')" />
                    </div>

                    <div>
                        <x-input-label for="guru_id" :value="__('Guru Pengajar')" class="text-gray-700 font-semibold" />
                        <select id="guru_id" name="guru_id" class="mt-1 block w-full border-gray-300 focus:border-red-500 focus:ring-red-500 rounded-md shadow-sm" required>
                            <option value="">-- Pilih Guru --</option>
                            @foreach($gurus as $guru)
                                <option value="{{ $guru->id }}" {{ old('guru_id') == $guru->id ? 'selected' : '' }}>
                                    {{ $guru->nama_lengkap }}
                                </option>
                            @endforeach
                        </select>
                        <x-input-error class="mt-2" :messages="$errors->get('guru_id')" />
                    </div>

                    <div>
                        <x-input-label for="is_aktif" :value="__('Status Mapping')" class="text-gray-700 font-semibold" />
                        <select id="is_aktif" name="is_aktif" class="mt-1 block w-full border-gray-300 focus:border-red-500 focus:ring-red-500 rounded-md shadow-sm" required>
                            <option value="1" {{ old('is_aktif', '1') == '1' ? 'selected' : '' }}>Aktif</option>
                            <option value="0" {{ old('is_aktif') == '0' ? 'selected' : '' }}>Tidak Aktif</option>
                        </select>
                        <x-input-error class="mt-2" :messages="$errors->get('is_aktif')" />
                    </div>
                </div>

                <div class="flex items-center justify-start mt-8 border-t border-gray-200 pt-6">
                    <x-primary-button class="bg-[#8B1515] hover:bg-red-700">
                        {{ __('Simpan Mapping') }}
                    </x-primary-button>
                </div>
            </form>
        </div>

    </div>
</x-app-layout>
