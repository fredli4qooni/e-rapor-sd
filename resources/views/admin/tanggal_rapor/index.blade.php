<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Tanggal Periode Semester & Rapor') }}
        </h2>
    </x-slot>

    <div class="py-6 space-y-6 max-w-7xl mx-auto sm:px-6 lg:px-8">
        
        <!-- Flash Message -->
        @if (session('status'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative shadow-sm" role="alert">
                <span class="block sm:inline">{{ session('status') }}</span>
            </div>
        @endif

        <div class="bg-white rounded-md shadow-md overflow-hidden">
            <div class="bg-[#8B1515] text-white px-6 py-4 font-bold tracking-wider uppercase text-sm border-b border-red-800 flex justify-between items-center">
                <span>Pengaturan Tanggal Periode & Batas Input Nilai Per Semester</span>
            </div>
            
            <form action="{{ route('admin.tanggal_rapor.update') }}" method="POST" class="p-6">
                @csrf
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-700 border">
                        <thead class="text-xs text-white bg-gray-800 uppercase border-b">
                            <tr>
                                <th class="px-3 py-3 w-12 text-center">No</th>
                                <th class="px-4 py-3 w-48">Tahun Ajaran / Semester</th>
                                <th class="px-4 py-3">Periode Semester (Mulai s/d Selesai)</th>
                                <th class="px-4 py-3">Jadwal Input Nilai (Mulai s/d Batas Akhir)</th>
                                <th class="px-4 py-3">Tempat Terbit & Tanggal Rapor</th>
                                <th class="px-3 py-3 w-24 text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse($semesters as $index => $semester)
                                <tr class="hover:bg-gray-50 {{ $semester->is_aktif ? 'bg-red-50/40' : '' }}">
                                    <td class="px-3 py-3 text-center font-medium text-gray-500">{{ $index + 1 }}</td>
                                    <td class="px-4 py-3">
                                        <div class="font-bold text-gray-900">{{ $semester->tahun_ajaran }}</div>
                                        <div class="text-xs text-gray-600">Semester {{ $semester->semester == 1 ? '1 (Ganjil)' : '2 (Genap)' }}</div>
                                        @if($semester->is_aktif)
                                            <span class="inline-block mt-1 text-[10px] bg-red-100 text-red-800 px-2 py-0.5 rounded font-bold border border-red-200">Semester Aktif</span>
                                        @endif
                                    </td>
                                    
                                    <!-- Periode Semester -->
                                    <td class="px-4 py-3">
                                        <div class="grid grid-cols-2 gap-2">
                                            <div>
                                                <label class="block text-[11px] text-gray-500 mb-0.5">Tgl Mulai</label>
                                                <input type="date" name="semester[{{ $semester->id }}][tanggal_mulai]" value="{{ old('semester.'.$semester->id.'.tanggal_mulai', $semester->tanggal_mulai ? $semester->tanggal_mulai->format('Y-m-d') : '') }}" class="block w-full border-gray-300 focus:border-red-500 focus:ring-red-500 rounded-md shadow-sm text-xs">
                                            </div>
                                            <div>
                                                <label class="block text-[11px] text-gray-500 mb-0.5">Tgl Selesai</label>
                                                <input type="date" name="semester[{{ $semester->id }}][tanggal_selesai]" value="{{ old('semester.'.$semester->id.'.tanggal_selesai', $semester->tanggal_selesai ? $semester->tanggal_selesai->format('Y-m-d') : '') }}" class="block w-full border-gray-300 focus:border-red-500 focus:ring-red-500 rounded-md shadow-sm text-xs">
                                            </div>
                                        </div>
                                    </td>

                                    <!-- Jadwal Input Nilai Guru -->
                                    <td class="px-4 py-3">
                                        <div class="grid grid-cols-2 gap-2">
                                            <div>
                                                <label class="block text-[11px] text-gray-500 mb-0.5">Mulai Input</label>
                                                <input type="date" name="semester[{{ $semester->id }}][tanggal_mulai_input]" value="{{ old('semester.'.$semester->id.'.tanggal_mulai_input', $semester->tanggal_mulai_input ? $semester->tanggal_mulai_input->format('Y-m-d') : '') }}" class="block w-full border-gray-300 focus:border-red-500 focus:ring-red-500 rounded-md shadow-sm text-xs">
                                            </div>
                                            <div>
                                                <label class="block text-[11px] font-semibold text-red-700 mb-0.5">Batas Akhir (Deadline)</label>
                                                <input type="date" name="semester[{{ $semester->id }}][tanggal_akhir_input]" value="{{ old('semester.'.$semester->id.'.tanggal_akhir_input', $semester->tanggal_akhir_input ? $semester->tanggal_akhir_input->format('Y-m-d') : '') }}" class="block w-full border-red-300 focus:border-red-500 focus:ring-red-500 rounded-md shadow-sm text-xs font-semibold">
                                            </div>
                                        </div>
                                    </td>

                                    <!-- Tempat Terbit & Tanggal Rapor -->
                                    <td class="px-4 py-3">
                                        <div class="grid grid-cols-2 gap-2">
                                            <div>
                                                <label class="block text-[11px] text-gray-500 mb-0.5">Tempat Terbit</label>
                                                <input type="text" name="semester[{{ $semester->id }}][tempat_terbit]" value="{{ old('semester.'.$semester->id.'.tempat_terbit', $semester->tempat_terbit) }}" class="block w-full border-gray-300 focus:border-red-500 focus:ring-red-500 rounded-md shadow-sm text-xs" placeholder="Contoh: Jakarta">
                                            </div>
                                            <div>
                                                <label class="block text-[11px] text-gray-500 mb-0.5">Tanggal Rapor</label>
                                                <input type="date" name="semester[{{ $semester->id }}][tanggal_rapor]" value="{{ old('semester.'.$semester->id.'.tanggal_rapor', $semester->tanggal_rapor ? $semester->tanggal_rapor->format('Y-m-d') : '') }}" class="block w-full border-gray-300 focus:border-red-500 focus:ring-red-500 rounded-md shadow-sm text-xs">
                                            </div>
                                        </div>
                                    </td>

                                    <td class="px-3 py-3 text-center">
                                        @if($semester->is_aktif)
                                            <span class="text-green-600 font-bold text-xs bg-green-50 px-2 py-1 rounded border border-green-200">Aktif</span>
                                        @else
                                            <span class="text-gray-400 font-medium text-xs">Tidak Aktif</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-4 py-4 text-center text-gray-500">Belum ada data semester.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="flex items-center justify-between mt-6 bg-gray-50 p-4 rounded-md border">
                    <div class="text-xs text-gray-500">
                        <strong>Catatan:</strong> Batas akhir input nilai akan menjadi acuan countdown dan notifikasi peringatan di dashboard guru pengampu.
                    </div>
                    <x-primary-button class="bg-[#8B1515] hover:bg-red-700">
                        {{ __('Simpan Pengaturan Tanggal') }}
                    </x-primary-button>
                </div>
            </form>
        </div>

    </div>
</x-app-layout>
