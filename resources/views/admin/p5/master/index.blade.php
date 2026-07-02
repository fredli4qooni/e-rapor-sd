<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Master Data P5') }}
        </h2>
    </x-slot>

    <div class="py-6 space-y-6 max-w-7xl mx-auto sm:px-6 lg:px-8">

        <!-- Flash Message -->
        @if (session('status'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative shadow-sm" role="alert">
                <span class="block sm:inline">{{ session('status') }}</span>
            </div>
        @endif
        
        <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded text-sm text-blue-800 shadow-sm mb-6">
            <p><strong>Informasi:</strong> Data Tema, Dimensi, Elemen, dan Sub-Elemen P5 bersifat statis (Baku dari Kemendikbudristek) dan hanya ditampilkan untuk keperluan referensi saat merancang Proyek P5.</p>
        </div>

        <!-- Bagian Tema -->
        <div class="bg-white rounded-md shadow-md overflow-hidden">
            <div class="bg-[#8B1515] text-white px-6 py-4 font-bold tracking-wider uppercase text-sm border-b border-red-800">
                Daftar Tema P5
            </div>
            <div class="p-6 overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-700 border">
                    <thead class="text-xs text-white bg-gray-800 uppercase border-b">
                        <tr>
                            <th class="px-4 py-3 w-16 text-center">No</th>
                            <th class="px-4 py-3">Nama Tema</th>
                            <th class="px-4 py-3">Deskripsi Singkat</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($temas as $index => $tema)
                            <tr class="border-b hover:bg-gray-50">
                                <td class="px-4 py-3 text-center font-medium">{{ $index + 1 }}</td>
                                <td class="px-4 py-3 font-bold text-gray-800">{{ $tema->nama_tema }}</td>
                                <td class="px-4 py-3">{{ $tema->deskripsi }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Bagian Dimensi, Elemen, Sub Elemen -->
        <div class="bg-white rounded-md shadow-md overflow-hidden">
            <div class="bg-[#8B1515] text-white px-6 py-4 font-bold tracking-wider uppercase text-sm border-b border-red-800">
                Daftar Dimensi, Elemen, dan Sub-Elemen P5
            </div>
            
            <div class="p-6">
                <div x-data="{ openDimensi: null }" class="space-y-4">
                    @foreach($dimensis as $dimensi)
                        <div class="border rounded-md shadow-sm">
                            <button @click="openDimensi === {{ $dimensi->id }} ? openDimensi = null : openDimensi = {{ $dimensi->id }}" class="w-full flex justify-between items-center bg-gray-50 px-4 py-3 font-bold text-gray-800 hover:bg-gray-100 transition focus:outline-none">
                                <span class="flex items-center">
                                    <span class="bg-red-700 text-white text-xs px-2 py-1 rounded mr-3">Dimensi</span>
                                    {{ $dimensi->nama_dimensi }}
                                </span>
                                <svg :class="{'rotate-180': openDimensi === {{ $dimensi->id }} }" class="w-5 h-5 text-gray-500 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </button>
                            
                            <div x-show="openDimensi === {{ $dimensi->id }}" x-cloak class="p-4 border-t" style="display: none;">
                                <p class="text-sm text-gray-600 italic mb-4">"{{ $dimensi->deskripsi }}"</p>
                                
                                @if($dimensi->elemens->count() > 0)
                                    <div class="space-y-4">
                                        @foreach($dimensi->elemens as $elemen)
                                            <div class="bg-gray-50 border p-4 rounded">
                                                <h4 class="font-bold text-red-800 mb-2 border-b pb-1">Elemen: {{ $elemen->nama_elemen }}</h4>
                                                
                                                @if($elemen->subElemens->count() > 0)
                                                    <div class="overflow-x-auto mt-3">
                                                        <table class="w-full text-xs text-left text-gray-700">
                                                            <thead class="bg-gray-200">
                                                                <tr>
                                                                    <th class="px-3 py-2 border w-1/4">Sub Elemen</th>
                                                                    <th class="px-3 py-2 border w-1/4">Capaian Fase A</th>
                                                                    <th class="px-3 py-2 border w-1/4">Capaian Fase B</th>
                                                                    <th class="px-3 py-2 border w-1/4">Capaian Fase C</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @foreach($elemen->subElemens as $sub)
                                                                    <tr>
                                                                        <td class="px-3 py-2 border font-semibold">{{ $sub->nama_sub_elemen }}</td>
                                                                        <td class="px-3 py-2 border">{{ $sub->capaian_fase_a ?? '-' }}</td>
                                                                        <td class="px-3 py-2 border">{{ $sub->capaian_fase_b ?? '-' }}</td>
                                                                        <td class="px-3 py-2 border">{{ $sub->capaian_fase_c ?? '-' }}</td>
                                                                    </tr>
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                @else
                                                    <p class="text-xs text-gray-500 italic">Belum ada data sub elemen.</p>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <p class="text-sm text-gray-500 italic">Belum ada elemen ditambahkan untuk dimensi ini.</p>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

    </div>
</x-app-layout>
