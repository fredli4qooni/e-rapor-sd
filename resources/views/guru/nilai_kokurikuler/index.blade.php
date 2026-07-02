<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight uppercase">
            {{ __('Input Nilai Kokurikuler') }}
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

            <!-- Filter Section Card -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6 border-t-4 border-red-800">
                <div class="p-6">
                    <form method="GET" action="{{ route('guru.nilai_kokurikuler.index') }}" class="space-y-4">
                        <div class="flex items-center gap-4">
                            <label class="w-1/4 text-sm font-bold text-gray-700 whitespace-nowrap">Pilih Kelompok</label>
                            <select name="kelompok_id" onchange="this.form.submit()" class="block w-3/4 rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm bg-gray-50">
                                <option value="">Pilih Kelompok</option>
                                @foreach($kelompoks as $k)
                                    <option value="{{ $k->id }}" {{ $kelompok_id == $k->id ? 'selected' : '' }}>
                                        {{ $k->nama_kelompok }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="flex items-center gap-4">
                            <label class="w-1/4 text-sm font-bold text-gray-700 whitespace-nowrap">Pilih Kegiatan Kokurikuler</label>
                            <select name="proyek_id" onchange="this.form.submit()" class="block w-3/4 rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm bg-gray-50" {{ !$kelompok_id ? 'disabled' : '' }}>
                                <option value="">Pilih Kegiatan Kokurikuler</option>
                                @foreach($proyeks as $p)
                                    <option value="{{ $p->id }}" {{ $proyek_id == $p->id ? 'selected' : '' }}>
                                        {{ $p->nama_proyek }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="flex items-center gap-4">
                            <label class="w-1/4 text-sm font-bold text-gray-700 whitespace-nowrap">Pilih Dimensi Profil Lulusan</label>
                            <select name="dimensi_id" onchange="this.form.submit()" class="block w-3/4 rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm bg-gray-50" {{ !$proyek_id ? 'disabled' : '' }}>
                                <option value="">Pilih Dimensi Profil Lulusan</option>
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

            @if($dimensi_id && count($sub_elemens) > 0)
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 border-b border-gray-200">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-bold text-gray-800">Capaian Subdimensi Profil Lulusan</h3>
                        <button type="button" class="bg-teal-700 hover:bg-teal-800 text-white font-bold py-2 px-4 rounded text-sm shadow transition-colors inline-flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            Alur Perkembangan
                        </button>
                    </div>

                    <form action="{{ route('guru.nilai_kokurikuler.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="proyek_id" value="{{ $proyek_id }}">

                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 border border-gray-300">
                                <thead class="bg-red-900 text-white">
                                    <tr>
                                        <th rowspan="2" scope="col" class="px-4 py-3 text-center text-xs font-bold tracking-wider w-12 border border-red-800">No</th>
                                        <th rowspan="2" scope="col" class="px-4 py-3 text-center text-xs font-bold tracking-wider w-64 border border-red-800">Nama Siswa</th>
                                        <th colspan="{{ count($sub_elemens) }}" scope="col" class="px-4 py-3 text-center text-xs font-bold tracking-wider border border-red-800">Capaian Subdimensi Profil Lulusan</th>
                                    </tr>
                                    <tr>
                                        @foreach($sub_elemens as $se)
                                            <th scope="col" class="px-4 py-2 text-center text-xs font-semibold border border-red-800" title="{{ $se->nama_sub_elemen }}">
                                                {{ Str::limit($se->nama_sub_elemen, 25) }}
                                            </th>
                                        @endforeach
                                    </tr>
                                    <tr class="bg-red-800 text-white">
                                        <th class="px-4 py-2 border border-red-700 text-center">#</th>
                                        <th class="px-4 py-2 text-right text-xs font-bold border border-red-700">Terapkan nilai ke semua siswa >></th>
                                        @foreach($sub_elemens as $se)
                                            <th class="px-2 py-2 border border-red-700 text-center">
                                                <select onchange="applyBulkPredikat(this.value, '{{ $se->id }}')" class="block w-full rounded border-white text-gray-800 shadow-sm focus:border-red-500 focus:ring-red-500 text-xs py-1 px-1 bg-white font-semibold">
                                                    <option value="">Pilih Nilai Untuk Semua</option>
                                                    <option value="1">Berkembang</option>
                                                    <option value="2">Cakap</option>
                                                    <option value="3">Mahir</option>
                                                </select>
                                            </th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @forelse($siswas as $index => $siswa)
                                        <tr class="hover:bg-red-50/50 transition-colors">
                                            <td class="px-4 py-3 text-sm text-gray-500 font-medium border border-gray-300 text-center">{{ $index + 1 }}</td>
                                            <td class="px-4 py-3 text-sm text-gray-700 border border-gray-300">
                                                <div class="font-bold uppercase">{{ $siswa->nama_lengkap }}</div>
                                                <div class="text-xs text-gray-500">NISN : {{ $siswa->nisn }}</div>
                                                <div class="text-xs text-gray-500">NIS : {{ $siswa->nis ?? '-' }}</div>
                                            </td>
                                            @foreach($sub_elemens as $se)
                                                @php
                                                    $nilai_capaian = '';
                                                    if (isset($nilais[$siswa->id])) {
                                                        $n = $nilais[$siswa->id]->firstWhere('p5_sub_elemen_id', $se->id);
                                                        if ($n) $nilai_capaian = $n->capaian;
                                                    }
                                                @endphp
                                                <td class="px-2 py-3 border border-gray-300">
                                                    <select name="nilai[{{ $siswa->id }}][{{ $se->id }}]" class="capaian-select-{{ $se->id }} block w-full rounded border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 text-sm py-1.5 px-2 bg-gray-50">
                                                        <option value=""></option>
                                                        <option value="1" {{ $nilai_capaian == '1' ? 'selected' : '' }}>Berkembang</option>
                                                        <option value="2" {{ $nilai_capaian == '2' ? 'selected' : '' }}>Cakap</option>
                                                        <option value="3" {{ $nilai_capaian == '3' ? 'selected' : '' }}>Mahir</option>
                                                    </select>
                                                </td>
                                            @endforeach
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="{{ 2 + count($sub_elemens) }}" class="px-6 py-8 whitespace-nowrap text-sm text-gray-500 text-center">
                                                Tidak ada data siswa pada kelompok ini.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        
                        @if($siswas->count() > 0)
                        <div class="mt-4 flex justify-end">
                            <button type="submit" class="bg-gray-800 hover:bg-gray-700 text-white font-bold py-2 px-6 rounded shadow transition-colors inline-flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
                                Simpan Data
                            </button>
                        </div>
                        @endif
                    </form>
                </div>
            </div>
            @elseif($dimensi_id)
            <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mt-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-yellow-700">
                            Tidak ada target subdimensi yang dipilih untuk dimensi ini pada kegiatan kokurikuler tersebut. Silakan atur target di menu admin terlebih dahulu.
                        </p>
                    </div>
                </div>
            </div>
            @endif

        </div>
    </div>

    <script>
        function applyBulkPredikat(val, subElemenId) {
            if(!val) return;
            const selects = document.querySelectorAll('.capaian-select-' + subElemenId);
            selects.forEach(select => {
                select.value = val;
            });
        }
    </script>
</x-app-layout>
