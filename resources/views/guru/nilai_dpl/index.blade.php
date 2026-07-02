<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Input Nilai DPL K2013') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 rounded shadow-sm">
                    <p class="font-bold">Berhasil</p>
                    <p>{{ session('success') }}</p>
                </div>
            @endif
            @if(session('error'))
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 rounded shadow-sm">
                    <p class="font-bold">Gagal</p>
                    <p>{{ session('error') }}</p>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 border-b border-gray-200">
                    <form method="GET" action="{{ route('guru.nilai_dpl.index') }}" class="flex flex-col md:flex-row gap-4 items-end">
                        <div class="flex-1 w-full">
                            <label for="rombel_id" class="block text-sm font-bold text-gray-700 mb-2">Pilih Kelas</label>
                            <select name="rombel_id" id="rombel_id" onchange="this.form.submit()" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm bg-gray-50">
                                <option value="">-- Pilih Kelas --</option>
                                @foreach($rombels as $r)
                                    <option value="{{ $r->id }}" {{ $rombel_id == $r->id ? 'selected' : '' }}>
                                        Kelas {{ $r->nama_rombel }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="flex-1 w-full">
                            <label for="dimensi_id" class="block text-sm font-bold text-gray-700 mb-2">Pilih Dimensi Profil Lulusan</label>
                            <select name="dimensi_id" id="dimensi_id" onchange="this.form.submit()" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm bg-gray-50" {{ !$rombel_id ? 'disabled' : '' }}>
                                <option value="">-- Pilih Dimensi --</option>
                                @foreach($dimensis as $d)
                                    <option value="{{ $d->id }}" {{ $dimensi_id == $d->id ? 'selected' : '' }}>
                                        {{ $d->nama_dimensi }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </form>
                </div>
            </div>

            @if($rombel && $dimensi)
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 border-b border-gray-200">
                    <div class="mb-6 flex justify-between items-center">
                        <div>
                            <h3 class="text-lg font-bold text-gray-800">Form Pengisian Nilai DPL</h3>
                            <p class="text-sm text-gray-500">Isi capaian 1 (Berkembang), 2 (Cakap), atau 3 (Mahir) pada subdimensi yang teramati.</p>
                        </div>
                        <a href="{{ route('guru.nilai_dpl.import_index', ['rombel_id' => $rombel->id, 'dimensi_id' => $dimensi->id]) }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded shadow text-sm transition-colors">Import Excel</a>
                    </div>

                    <form action="{{ route('guru.nilai_dpl.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="rombel_id" value="{{ $rombel->id }}">
                        <input type="hidden" name="dimensi_id" value="{{ $dimensi->id }}">

                        <div class="overflow-x-auto rounded-lg border border-gray-200 shadow-sm">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-100">
                                    <tr>
                                        <th scope="col" class="px-4 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider sticky left-0 bg-gray-100 z-10 border-r border-gray-200 w-12">No</th>
                                        <th scope="col" class="px-4 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider sticky left-12 bg-gray-100 z-10 border-r border-gray-200 w-64 min-w-[250px]">Nama Siswa</th>
                                        @foreach($subdimensis as $sub)
                                            <th scope="col" class="px-4 py-3 text-center text-xs font-bold text-gray-700 uppercase tracking-wider border-b border-r border-gray-200 min-w-[200px]" title="{{ $sub->nama_subdimensi }}">
                                                <div class="line-clamp-2">{{ $sub->nama_subdimensi }}</div>
                                            </th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($siswas as $index => $siswa)
                                    <tr class="hover:bg-gray-50 transition-colors {{ $index % 2 === 0 ? 'bg-white' : 'bg-gray-50/50' }}">
                                        <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500 sticky left-0 z-10 border-r border-gray-200 {{ $index % 2 === 0 ? 'bg-white' : 'bg-gray-50' }}">{{ $index + 1 }}</td>
                                        <td class="px-4 py-4 whitespace-nowrap sticky left-12 z-10 border-r border-gray-200 {{ $index % 2 === 0 ? 'bg-white' : 'bg-gray-50' }}">
                                            <div class="font-bold text-gray-800">{{ $siswa->nama_lengkap }}</div>
                                            <div class="text-xs text-gray-500">{{ $siswa->nisn }}</div>
                                        </td>
                                        
                                        @foreach($subdimensis as $sub)
                                            @php
                                                $currentVal = null;
                                                if(isset($nilai[$siswa->id])) {
                                                    $rec = $nilai[$siswa->id]->where('dpl_subdimensi_id', $sub->id)->first();
                                                    if($rec) $currentVal = $rec->nilai;
                                                }
                                            @endphp
                                            <td class="px-4 py-4 whitespace-nowrap text-center border-r border-gray-200">
                                                <div class="flex items-center justify-center space-x-3 text-sm">
                                                    <label class="flex items-center cursor-pointer group">
                                                        <input type="radio" name="nilai[{{ $siswa->id }}][{{ $sub->id }}]" value="1" {{ $currentVal == 1 ? 'checked' : '' }} class="text-red-600 focus:ring-red-500">
                                                        <span class="ml-1 text-gray-600 group-hover:text-red-600 transition-colors" title="Berkembang">1</span>
                                                    </label>
                                                    <label class="flex items-center cursor-pointer group">
                                                        <input type="radio" name="nilai[{{ $siswa->id }}][{{ $sub->id }}]" value="2" {{ $currentVal == 2 ? 'checked' : '' }} class="text-yellow-500 focus:ring-yellow-500">
                                                        <span class="ml-1 text-gray-600 group-hover:text-yellow-600 transition-colors" title="Cakap">2</span>
                                                    </label>
                                                    <label class="flex items-center cursor-pointer group">
                                                        <input type="radio" name="nilai[{{ $siswa->id }}][{{ $sub->id }}]" value="3" {{ $currentVal == 3 ? 'checked' : '' }} class="text-green-500 focus:ring-green-500">
                                                        <span class="ml-1 text-gray-600 group-hover:text-green-600 transition-colors" title="Mahir">3</span>
                                                    </label>
                                                    <label class="flex items-center cursor-pointer group ml-2">
                                                        <input type="radio" name="nilai[{{ $siswa->id }}][{{ $sub->id }}]" value="" {{ !$currentVal ? 'checked' : '' }} class="text-gray-400 focus:ring-gray-400">
                                                        <span class="ml-1 text-xs text-gray-400 group-hover:text-gray-600" title="Kosongkan (Tidak Teramati)">-</span>
                                                    </label>
                                                </div>
                                            </td>
                                        @endforeach
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-6 flex justify-end">
                            <button type="submit" class="bg-red-800 hover:bg-red-700 text-white font-bold py-2 px-6 rounded shadow transition-colors inline-flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                Simpan Data Nilai
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            @endif

        </div>
    </div>
</x-app-layout>
