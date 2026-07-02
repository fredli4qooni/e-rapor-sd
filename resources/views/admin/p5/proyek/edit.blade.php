<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Edit Kegiatan Kokurikuler') }}
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
                <span>Form Edit Kegiatan Kokurikuler</span>
                <a href="{{ route('admin.p5.proyek.index', ['fase' => $proyek->fase, 'p5_tema_id' => $proyek->p5_tema_id]) }}" class="text-sm font-semibold text-red-100 hover:text-white">Kembali</a>
            </div>
            
            <form action="{{ route('admin.p5.proyek.update', $proyek->id) }}" method="POST" class="p-6">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <!-- Kolom Kiri: Informasi Kegiatan -->
                    <div class="space-y-4">
                        <h3 class="text-lg font-bold border-b pb-2 text-gray-800">1. Informasi Kegiatan</h3>
                        
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <x-input-label for="no_urut" :value="__('Nomor Urut')" class="text-gray-700 font-semibold" />
                                <x-text-input id="no_urut" name="no_urut" type="number" class="mt-1 block w-full" :value="old('no_urut', $proyek->no_urut)" required />
                                <x-input-error class="mt-2" :messages="$errors->get('no_urut')" />
                            </div>
                            <div>
                                <x-input-label for="fase" :value="__('Fase')" class="text-gray-700 font-semibold" />
                                <select id="fase" name="fase" class="mt-1 block w-full border-gray-300 focus:border-red-500 focus:ring-red-500 rounded-md shadow-sm" required autofocus>
                                    <option value="">-- Pilih Fase --</option>
                                    <option value="A" {{ old('fase', $proyek->fase) == 'A' ? 'selected' : '' }}>Fase A (Kelas 1-2)</option>
                                    <option value="B" {{ old('fase', $proyek->fase) == 'B' ? 'selected' : '' }}>Fase B (Kelas 3-4)</option>
                                    <option value="C" {{ old('fase', $proyek->fase) == 'C' ? 'selected' : '' }}>Fase C (Kelas 5-6)</option>
                                </select>
                                <x-input-error class="mt-2" :messages="$errors->get('fase')" />
                            </div>
                        </div>

                        <div>
                            <x-input-label for="p5_tema_id" :value="__('Tema P5')" class="text-gray-700 font-semibold" />
                            <select id="p5_tema_id" name="p5_tema_id" class="mt-1 block w-full border-gray-300 focus:border-red-500 focus:ring-red-500 rounded-md shadow-sm" required>
                                <option value="">-- Pilih Tema --</option>
                                @foreach($temas as $tema)
                                    <option value="{{ $tema->id }}" {{ old('p5_tema_id', $proyek->p5_tema_id) == $tema->id ? 'selected' : '' }}>
                                        {{ $tema->nama_tema }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error class="mt-2" :messages="$errors->get('p5_tema_id')" />
                        </div>

                        <div>
                            <x-input-label for="nama_proyek" :value="__('Nama Kegiatan Kokurikuler')" class="text-gray-700 font-semibold" />
                            <x-text-input id="nama_proyek" name="nama_proyek" type="text" class="mt-1 block w-full" :value="old('nama_proyek', $proyek->nama_proyek)" required />
                            <x-input-error class="mt-2" :messages="$errors->get('nama_proyek')" />
                        </div>

                        <div>
                            <x-input-label for="deskripsi" :value="__('Tujuan Akhir Kegiatan')" class="text-gray-700 font-semibold" />
                            <textarea id="deskripsi" name="deskripsi" rows="3" class="mt-1 block w-full border-gray-300 focus:border-red-500 focus:ring-red-500 rounded-md shadow-sm" placeholder="Contoh: Peserta didik mampu mengidentifikasi...">{{ old('deskripsi', $proyek->deskripsi) }}</textarea>
                            <x-input-error class="mt-2" :messages="$errors->get('deskripsi')" />
                        </div>
                    </div>

                    <!-- Kolom Kanan: Target Capaian -->
                    <div class="space-y-4">
                        <h3 class="text-lg font-bold border-b pb-2 text-gray-800">2. Target Capaian (Sub-Elemen)</h3>
                        <p class="text-xs text-gray-500">Pilih Sub-Elemen yang akan menjadi target penilaian untuk proyek ini. Dianjurkan memilih 2-4 sub-elemen saja agar penilaian fokus.</p>
                        
                        <div class="max-h-[400px] overflow-y-auto pr-2 space-y-3" x-data="{ openDimensi: null }">
                            @foreach($dimensis as $dimensi)
                                <div class="border rounded shadow-sm">
                                    <button type="button" @click="openDimensi === {{ $dimensi->id }} ? openDimensi = null : openDimensi = {{ $dimensi->id }}" class="w-full flex justify-between items-center bg-gray-50 px-3 py-2 text-sm font-bold text-gray-800 hover:bg-gray-100 transition focus:outline-none">
                                        <span class="text-left">{{ $dimensi->nama_dimensi }}</span>
                                        <svg :class="{'rotate-180': openDimensi === {{ $dimensi->id }} }" class="w-4 h-4 text-gray-500 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                    </button>
                                    
                                    <div x-show="openDimensi === {{ $dimensi->id }}" x-cloak class="p-3 border-t bg-white" style="display: none;">
                                        @if($dimensi->elemens->count() > 0)
                                            <div class="space-y-3">
                                                @foreach($dimensi->elemens as $elemen)
                                                    @if($elemen->subElemens->count() > 0)
                                                        <div class="pl-2 border-l-2 border-red-200">
                                                            <h4 class="text-xs font-bold text-red-800 mb-2">{{ $elemen->nama_elemen }}</h4>
                                                            <div class="space-y-2">
                                                                @foreach($elemen->subElemens as $sub)
                                                                    <label class="flex items-start space-x-2 cursor-pointer hover:bg-gray-50 p-1 rounded">
                                                                        <input type="checkbox" name="sub_elemens[]" value="{{ $sub->id }}" class="mt-0.5 border-gray-300 rounded text-red-600 focus:ring-red-500" {{ in_array($sub->id, old('sub_elemens', $selectedSubElemens)) ? 'checked' : '' }}>
                                                                        <span class="text-xs text-gray-700">{{ $sub->nama_sub_elemen }}</span>
                                                                    </label>
                                                                @endforeach
                                                            </div>
                                                        </div>
                                                    @endif
                                                @endforeach
                                            </div>
                                        @else
                                            <p class="text-xs text-gray-500 italic">Belum ada sub elemen.</p>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <x-input-error class="mt-2" :messages="$errors->get('sub_elemens')" />
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
