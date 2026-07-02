<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Input Nilai P3 K13') }}
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
                    <form method="GET" action="{{ route('guru.nilai_p3.index') }}" class="flex flex-col md:flex-row gap-4 items-end">
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
                            <label for="dimensi_id" class="block text-sm font-bold text-gray-700 mb-2">Pilih Dimensi Profil Pelajar Pancasila</label>
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
                            <h3 class="text-lg font-bold text-gray-800">Form Pengisian Nilai P3</h3>
                            <p class="text-sm text-gray-500">Skala Penilaian: 1 (Mulai Berkembang), 2 (Sedang Berkembang), 3 (Berkembang Sesuai Harapan), 4 (Sangat Berkembang).</p>
                        </div>
                        <a href="{{ route('guru.nilai_p3.import_index', ['rombel_id' => $rombel->id, 'dimensi_id' => $dimensi->id]) }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded shadow text-sm transition-colors">Import Excel</a>
                    </div>

                    <form action="{{ route('guru.nilai_p3.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="rombel_id" value="{{ $rombel->id }}">
                        <input type="hidden" name="dimensi_id" value="{{ $dimensi->id }}">

                        <div class="overflow-x-auto rounded-lg border border-gray-200 shadow-sm pb-4">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-100">
                                    <tr>
                                        <th scope="col" rowspan="2" class="px-4 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider sticky left-0 bg-gray-100 z-10 border-r border-gray-200 w-12">No</th>
                                        <th scope="col" rowspan="2" class="px-4 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider sticky left-12 bg-gray-100 z-10 border-r border-gray-200 w-64 min-w-[250px]">Nama Siswa</th>
                                        @foreach($elemens as $elemen)
                                            <th scope="col" colspan="{{ $elemen->subElemens->count() }}" class="px-4 py-3 text-center text-xs font-bold text-gray-700 uppercase tracking-wider border-b border-r border-gray-200" title="{{ $elemen->nama_elemen }}">
                                                Elemen: <span class="line-clamp-1" title="{{ $elemen->nama_elemen }}">{{ current(explode(' ', $elemen->nama_elemen)) }}...</span>
                                            </th>
                                        @endforeach
                                    </tr>
                                    <tr>
                                        @foreach($elemens as $elemen)
                                            @foreach($elemen->subElemens as $sub)
                                            <th scope="col" class="px-2 py-2 text-center text-xs font-bold text-gray-600 uppercase tracking-wider border-b border-r border-gray-200 min-w-[200px]">
                                                <div class="line-clamp-2" title="{{ $sub->nama_sub_elemen }}">{{ current(explode(' ', $sub->nama_sub_elemen)) }}...</div>
                                            </th>
                                            @endforeach
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($siswas as $index => $siswa)
                                    <tr class="hover:bg-gray-50 transition-colors {{ $index % 2 === 0 ? 'bg-white' : 'bg-gray-50/50' }}">
                                        <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500 sticky left-0 z-10 border-r border-gray-200 {{ $index % 2 === 0 ? 'bg-white' : 'bg-gray-50' }}">{{ $index + 1 }}</td>
                                        <td class="px-4 py-4 whitespace-nowrap sticky left-12 z-10 border-r border-gray-200 {{ $index % 2 === 0 ? 'bg-white' : 'bg-gray-50' }}">
                                            <div class="font-bold text-gray-800">{{ $siswa->nama_lengkap }}</div>
                                        </td>
                                        
                                        @foreach($elemens as $elemen)
                                            @foreach($elemen->subElemens as $sub)
                                                @php
                                                    $currentVal = null;
                                                    if(isset($nilai[$siswa->id])) {
                                                        $rec = $nilai[$siswa->id]->where('p5_sub_elemen_id', $sub->id)->first();
                                                        if($rec) $currentVal = $rec->nilai;
                                                    }
                                                @endphp
                                                <td class="px-2 py-4 whitespace-nowrap text-center border-r border-gray-200">
                                                    <div class="flex items-center justify-center space-x-2 text-sm flex-wrap gap-y-2">
                                                        <label class="flex items-center cursor-pointer group">
                                                            <input type="radio" name="nilai[{{ $siswa->id }}][{{ $sub->id }}]" value="1" {{ $currentVal == 1 ? 'checked' : '' }} class="text-red-600 focus:ring-red-500">
                                                            <span class="ml-1 text-gray-600 group-hover:text-red-600" title="Mulai Berkembang">1</span>
                                                        </label>
                                                        <label class="flex items-center cursor-pointer group">
                                                            <input type="radio" name="nilai[{{ $siswa->id }}][{{ $sub->id }}]" value="2" {{ $currentVal == 2 ? 'checked' : '' }} class="text-yellow-500 focus:ring-yellow-500">
                                                            <span class="ml-1 text-gray-600 group-hover:text-yellow-600" title="Sedang Berkembang">2</span>
                                                        </label>
                                                        <label class="flex items-center cursor-pointer group">
                                                            <input type="radio" name="nilai[{{ $siswa->id }}][{{ $sub->id }}]" value="3" {{ $currentVal == 3 ? 'checked' : '' }} class="text-blue-500 focus:ring-blue-500">
                                                            <span class="ml-1 text-gray-600 group-hover:text-blue-600" title="Berkembang Sesuai Harapan">3</span>
                                                        </label>
                                                        <label class="flex items-center cursor-pointer group">
                                                            <input type="radio" name="nilai[{{ $siswa->id }}][{{ $sub->id }}]" value="4" {{ $currentVal == 4 ? 'checked' : '' }} class="text-green-500 focus:ring-green-500">
                                                            <span class="ml-1 text-gray-600 group-hover:text-green-600" title="Sangat Berkembang">4</span>
                                                        </label>
                                                        <label class="flex items-center cursor-pointer group">
                                                            <input type="radio" name="nilai[{{ $siswa->id }}][{{ $sub->id }}]" value="" {{ !$currentVal ? 'checked' : '' }} class="text-gray-300 focus:ring-gray-300">
                                                            <span class="ml-1 text-xs text-gray-400 group-hover:text-gray-500" title="Tidak Teramati">-</span>
                                                        </label>
                                                    </div>
                                                </td>
                                            @endforeach
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
