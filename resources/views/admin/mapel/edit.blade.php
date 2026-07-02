<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Edit Mata Pelajaran') }}
        </h2>
    </x-slot>

    <div class="py-6 space-y-6 max-w-7xl mx-auto sm:px-6 lg:px-8">
        
        <div class="bg-white rounded-md shadow-md overflow-hidden">
            <div class="bg-[#8B1515] text-white px-6 py-4 font-bold tracking-wider uppercase text-sm border-b border-red-800 flex justify-between items-center">
                <span>Form Edit Mata Pelajaran</span>
                <a href="{{ route('admin.mapel.index') }}" class="text-sm font-semibold text-red-100 hover:text-white">Kembali</a>
            </div>
            
            <form action="{{ route('admin.mapel.update', $mapel->id) }}" method="POST" class="p-6">
                @csrf
                @method('PUT')

                <div class="space-y-4 max-w-2xl">
                    <div>
                        <x-input-label for="nama_mapel" :value="__('Nama Mata Pelajaran')" class="text-gray-700 font-semibold" />
                        <x-text-input id="nama_mapel" name="nama_mapel" type="text" class="mt-1 block w-full" :value="old('nama_mapel', $mapel->nama_mapel)" required autofocus />
                        <x-input-error class="mt-2" :messages="$errors->get('nama_mapel')" />
                    </div>

                    <div>
                        <x-input-label for="nama_singkat" :value="__('Singkatan Mapel (Opsional)')" class="text-gray-700 font-semibold" />
                        <x-text-input id="nama_singkat" name="nama_singkat" type="text" class="mt-1 block w-full" :value="old('nama_singkat', $mapel->nama_singkat)" placeholder="Cth: PAI, PJOK" />
                        <x-input-error class="mt-2" :messages="$errors->get('nama_singkat')" />
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <x-input-label for="is_transkrip" :value="__('Tampil di Transkrip/Rapor?')" class="text-gray-700 font-semibold" />
                            <select id="is_transkrip" name="is_transkrip" class="mt-1 block w-full border-gray-300 focus:border-red-500 focus:ring-red-500 rounded-md shadow-sm" required>
                                <option value="1" {{ old('is_transkrip', $mapel->is_transkrip) == '1' ? 'selected' : '' }}>Ya</option>
                                <option value="0" {{ old('is_transkrip', $mapel->is_transkrip) == '0' ? 'selected' : '' }}>Tidak</option>
                            </select>
                            <x-input-error class="mt-2" :messages="$errors->get('is_transkrip')" />
                        </div>
                        <div>
                            <x-input-label for="is_lokal" :value="__('Muatan Lokal?')" class="text-gray-700 font-semibold" />
                            <select id="is_lokal" name="is_lokal" class="mt-1 block w-full border-gray-300 focus:border-red-500 focus:ring-red-500 rounded-md shadow-sm" required onchange="toggleMapelReferensi(this.value)">
                                <option value="0" {{ old('is_lokal', $mapel->is_lokal) == '0' ? 'selected' : '' }}>Bukan (Nasional)</option>
                                <option value="1" {{ old('is_lokal', $mapel->is_lokal) == '1' ? 'selected' : '' }}>Ya (Muatan Lokal)</option>
                            </select>
                            <x-input-error class="mt-2" :messages="$errors->get('is_lokal')" />
                        </div>
                    </div>

                    <div id="div_mapel_referensi" class="{{ old('is_lokal', $mapel->is_lokal) == '1' ? 'block' : 'hidden' }}">
                        <x-input-label for="mapel_referensi_id" :value="__('Mata Pelajaran Referensi (Untuk Mapel Lokal)')" class="text-gray-700 font-semibold" />
                        <select id="mapel_referensi_id" name="mapel_referensi_id" class="mt-1 block w-full border-gray-300 focus:border-red-500 focus:ring-red-500 rounded-md shadow-sm">
                            <option value="">-- Pilih Mapel Referensi --</option>
                            @foreach($mapels as $m)
                                <option value="{{ $m->id }}" {{ old('mapel_referensi_id', $mapel->mapel_referensi_id) == $m->id ? 'selected' : '' }}>
                                    {{ $m->nama_mapel }}
                                </option>
                            @endforeach
                        </select>
                        <x-input-error class="mt-2" :messages="$errors->get('mapel_referensi_id')" />
                        <p class="text-xs text-gray-500 mt-1">Pilih mata pelajaran induk jika ini adalah cabang muatan lokal (opsional).</p>
                    </div>
                </div>

                <script>
                    function toggleMapelReferensi(val) {
                        if (val == '1') {
                            document.getElementById('div_mapel_referensi').classList.remove('hidden');
                        } else {
                            document.getElementById('div_mapel_referensi').classList.add('hidden');
                            document.getElementById('mapel_referensi_id').value = '';
                        }
                    }
                </script>

                <div class="flex items-center justify-start mt-8 border-t border-gray-200 pt-6">
                    <x-primary-button class="bg-[#8B1515] hover:bg-red-700">
                        {{ __('Simpan Perubahan') }}
                    </x-primary-button>
                </div>
            </form>
        </div>

    </div>
</x-app-layout>
