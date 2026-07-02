<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('DATA TUJUAN PEMBELAJARAN') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            @if(session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded shadow-sm" role="alert">
                <p class="font-bold">Berhasil</p>
                <p>{{ session('success') }}</p>
            </div>
            @endif

            @if(session('error'))
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded shadow-sm" role="alert">
                <p class="font-bold">Gagal</p>
                <p>{{ session('error') }}</p>
            </div>
            @endif

            <!-- Pilih Mata Pelajaran Card -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6 border-t-4 border-red-800">
                <div class="p-6">
                    <form method="GET" action="{{ route('guru.tujuan-pembelajaran.index') }}" class="flex items-center gap-4">
                        <label for="mata_pelajaran_id" class="text-sm font-bold text-gray-700 whitespace-nowrap">Pilih Mata Pelajaran</label>
                        <select name="mata_pelajaran_id" id="mata_pelajaran_id" onchange="this.form.submit()" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm bg-gray-50">
                            <option value="">-- Pilih Mata Pelajaran --</option>
                            @foreach($mapelDiampu as $m)
                                <option value="{{ $m['id'] }}" {{ request('mata_pelajaran_id') == $m['id'] ? 'selected' : '' }}>
                                    {{ $m['nama'] }}
                                </option>
                            @endforeach
                        </select>
                    </form>
                </div>
            </div>

            @if($mata_pelajaran_id)
            <!-- TP Table Card -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-4 border-b border-gray-200 flex justify-end gap-2 bg-gray-50">
                    <a href="{{ route('guru.tujuan-pembelajaran.import_index') }}" class="bg-red-800 hover:bg-red-900 text-white font-bold py-2 px-4 rounded text-sm shadow transition-colors inline-flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                        Import TP
                    </a>
                    @if(!$isEdit)
                    <a href="{{ route('guru.tujuan-pembelajaran.index', ['mata_pelajaran_id' => $mata_pelajaran_id, 'edit' => 1]) }}" class="bg-red-800 hover:bg-red-900 text-white font-bold py-2 px-4 rounded text-sm shadow transition-colors inline-flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                        Tambah/Edit TP
                    </a>
                    @else
                    <a href="{{ route('guru.tujuan-pembelajaran.index', ['mata_pelajaran_id' => $mata_pelajaran_id]) }}" class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded text-sm shadow transition-colors inline-flex items-center">
                        Batal Edit
                    </a>
                    @endif
                </div>

                <div class="overflow-x-auto" x-data="tpForm()">
                    @if($isEdit)
                    <form action="{{ route('guru.tujuan-pembelajaran.bulk') }}" method="POST">
                        @csrf
                        <input type="hidden" name="mata_pelajaran_id" value="{{ $mata_pelajaran_id }}">
                    @endif

                    <table class="min-w-full divide-y divide-gray-200 border-t border-gray-200">
                        <thead class="bg-red-800 text-white">
                            <tr>
                                <th scope="col" class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wider w-12 border-r border-red-900">No</th>
                                <th scope="col" class="px-4 py-3 text-center text-xs font-bold uppercase tracking-wider w-24 border-r border-red-900">Tingkat</th>
                                <th scope="col" class="px-4 py-3 text-center text-xs font-bold uppercase tracking-wider w-24 border-r border-red-900">Fase</th>
                                <th scope="col" class="px-4 py-3 text-center text-xs font-bold uppercase tracking-wider w-24 border-r border-red-900">Semester</th>
                                <th scope="col" class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wider border-r border-red-900">Tujuan Pembelajaran</th>
                                <th scope="col" class="px-4 py-3 text-center text-xs font-bold uppercase tracking-wider w-24 border-r border-red-900">Status</th>
                                <th scope="col" class="px-4 py-3 text-center text-xs font-bold uppercase tracking-wider w-16">Hapus</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($tps as $index => $tp)
                                @php
                                    $fase = '-';
                                    if(in_array($tp->tingkat, [1, 2])) $fase = 'A';
                                    if(in_array($tp->tingkat, [3, 4])) $fase = 'B';
                                    if(in_array($tp->tingkat, [5, 6])) $fase = 'C';
                                @endphp
                                <tr class="{{ $index % 2 == 0 ? 'bg-red-50/30' : 'bg-white' }}">
                                    <td class="px-4 py-3 text-sm text-gray-500 font-medium border-r border-gray-200">{{ $index + 1 }}</td>
                                    <td class="px-4 py-3 text-center text-sm font-bold text-gray-700 border-r border-gray-200">{{ $tp->tingkat }}</td>
                                    <td class="px-4 py-3 text-center text-sm font-bold text-gray-700 border-r border-gray-200">{{ $fase }}</td>
                                    <td class="px-4 py-3 text-center text-sm text-gray-700 border-r border-gray-200">{{ $tp->semester->semester }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-700 border-r border-gray-200">
                                        @if($isEdit)
                                            <input type="hidden" name="tp[{{ $index }}][id]" value="{{ $tp->id }}">
                                            <textarea name="tp[{{ $index }}][deskripsi]" rows="2" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm text-gray-700">{{ $tp->deskripsi }}</textarea>
                                        @else
                                            {{ $tp->deskripsi }}
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-center text-sm font-medium border-r border-gray-200">
                                        @if($isEdit)
                                            <input type="hidden" name="tp[{{ $index }}][is_aktif]" value="0">
                                            <label class="inline-flex items-center cursor-pointer">
                                                <input type="checkbox" name="tp[{{ $index }}][is_aktif]" value="1" {{ $tp->is_aktif ? 'checked' : '' }} class="rounded border-gray-300 text-red-600 shadow-sm focus:border-red-300 focus:ring focus:ring-red-200 focus:ring-opacity-50">
                                                <span class="ml-2 text-gray-600 text-xs">Aktif</span>
                                            </label>
                                        @else
                                            <span class="{{ $tp->is_aktif ? 'text-green-600' : 'text-red-600' }} font-bold">
                                                {{ $tp->is_aktif ? 'Aktif' : 'Tidak Aktif' }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-center text-sm">
                                        @if($isEdit)
                                            <span class="text-gray-400">-</span>
                                        @else
                                            <form action="{{ route('guru.tujuan-pembelajaran.destroy', $tp->id) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus TP ini? Jika sudah digunakan di Rapor, menghapus TP dapat memutus relasi data!');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white p-2 rounded shadow transition-colors" title="Hapus">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                </button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                @if(!$isEdit)
                                <tr>
                                    <td colspan="7" class="px-6 py-8 whitespace-nowrap text-sm text-gray-500 text-center">
                                        Belum ada data Tujuan Pembelajaran. Klik "Tambah/Edit TP" untuk menambahkan.
                                    </td>
                                </tr>
                                @endif
                            @endforelse

                            @if($isEdit)
                                <!-- 5 Empty Rows for New TP -->
                                @php $startIndex = count($tps); @endphp
                                @for($i = 0; $i < 5; $i++)
                                    @php $idx = $startIndex + $i; @endphp
                                    <tr class="{{ $idx % 2 == 0 ? 'bg-red-50/30' : 'bg-white' }}" x-data="{ tingkat: '' }">
                                        <td class="px-4 py-3 text-sm text-gray-500 font-medium border-r border-gray-200">{{ $idx + 1 }}</td>
                                        <td class="px-4 py-3 text-center border-r border-gray-200">
                                            <select name="tp[{{ $idx }}][tingkat]" x-model="tingkat" class="block w-full rounded border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 text-xs py-1 px-2">
                                                <option value="">Pilih 1</option>
                                                @foreach($tingkatDiampu as $t)
                                                    <option value="{{ $t }}">{{ $t }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td class="px-4 py-3 text-center border-r border-gray-200">
                                            <!-- Auto calculated Fase based on Tingkat -->
                                            <input type="text" readonly :value="getFase(tingkat)" class="block w-full rounded border-gray-300 bg-gray-100 shadow-sm text-xs py-1 px-2 text-center" placeholder="Pilih F">
                                        </td>
                                        <td class="px-4 py-3 text-center text-sm text-gray-700 border-r border-gray-200">
                                            {{ $semesterAktif->semester }}
                                        </td>
                                        <td class="px-4 py-3 border-r border-gray-200">
                                            <textarea name="tp[{{ $idx }}][deskripsi]" rows="2" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm text-gray-700" placeholder="Tujuan Pembelajaran..."></textarea>
                                        </td>
                                        <td class="px-4 py-3 text-center text-sm font-medium border-r border-gray-200">
                                            <span class="text-gray-400">Aktif</span>
                                        </td>
                                        <td class="px-4 py-3 text-center text-sm">
                                            <span class="text-gray-400">-</span>
                                        </td>
                                    </tr>
                                @endfor
                            @endif
                        </tbody>
                    </table>

                    @if($isEdit)
                        <div class="p-4 bg-gray-50 border-t border-gray-200 flex justify-end">
                            <button type="submit" class="bg-gray-800 hover:bg-gray-700 text-white font-bold py-2 px-6 rounded shadow transition-colors inline-flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
                                Simpan Data
                            </button>
                        </div>
                    </form>
                    @endif
                </div>
            </div>
            @endif

        </div>
    </div>
    
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('tpForm', () => ({
                getFase(tingkat) {
                    if (!tingkat) return 'Pilih F';
                    const t = parseInt(tingkat);
                    if (t === 1 || t === 2) return 'A';
                    if (t === 3 || t === 4) return 'B';
                    if (t === 5 || t === 6) return 'C';
                    return '-';
                }
            }))
        })
    </script>
</x-app-layout>
