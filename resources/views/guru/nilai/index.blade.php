<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Input Nilai Rapor') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-full mx-auto sm:px-6 lg:px-8 space-y-6">
            
            @if(session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded shadow-sm" role="alert">
                <p class="font-bold">Berhasil</p>
                <p>{{ session('success') }}</p>
            </div>
            @endif

            <!-- Form Pilih Kelas & Mapel -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 border-b border-gray-200">
                    <form method="GET" action="{{ route('guru.nilai.index') }}" class="flex flex-col md:flex-row gap-4 items-end">
                        <div class="flex-1 w-full">
                            <label for="rombel_id" class="block text-sm font-bold text-gray-700 mb-2">Pilih Kelas</label>
                            <select name="rombel_id" id="rombel_id" onchange="this.form.submit()" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm bg-gray-50">
                                <option value="">-- Pilih Kelas --</option>
                                @foreach($guruRombels as $r)
                                    <option value="{{ $r->id }}" {{ $rombel_id == $r->id ? 'selected' : '' }}>
                                        Kelas {{ $r->nama_rombel }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="flex-1 w-full">
                            <label for="mata_pelajaran_id" class="block text-sm font-bold text-gray-700 mb-2">Pilih Mata Pelajaran</label>
                            <select name="mata_pelajaran_id" id="mata_pelajaran_id" onchange="this.form.submit()" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm bg-gray-50" {{ !$rombel_id ? 'disabled' : '' }}>
                                <option value="">-- Pilih Mata Pelajaran --</option>
                                @foreach($guruMapels as $m)
                                    <option value="{{ $m->id }}" {{ $mata_pelajaran_id == $m->id ? 'selected' : '' }}>
                                        {{ $m->nama_mapel }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Tabel Input Nilai -->
            @if($rombel && $mapel)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 border-b border-gray-200">
                        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-4 gap-4">
                            <div>
                                <h3 class="text-lg font-bold text-gray-800">Form Input Nilai: Kelas {{ $rombel->nama_rombel }} - {{ $mapel->nama_mapel }}</h3>
                                <p class="text-sm text-gray-500">Isi Nilai Akhir (0-100) dan centang TP yang relevan.</p>
                            </div>
                            <div class="flex gap-2">
                                <a href="{{ route('guru.nilai.import_index', ['rombel_id' => $rombel->id, 'mata_pelajaran_id' => $mapel->id]) }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded shadow text-sm transition-colors">Import Excel</a>
                                <a href="{{ route('guru.nilai.deskripsi', ['rombel_id' => $rombel->id, 'mata_pelajaran_id' => $mapel->id]) }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded shadow text-sm transition-colors">Lihat & Edit Deskripsi</a>
                            </div>
                        </div>

                        @if($tps->isEmpty())
                            <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mt-4">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm text-yellow-700">
                                            Anda belum menambahkan Tujuan Pembelajaran (TP) untuk mata pelajaran ini. Silakan <a href="{{ route('guru.tujuan-pembelajaran.index') }}" class="font-bold underline">kelola TP terlebih dahulu</a> agar deskripsi dapat di-generate otomatis.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @else
                            <form action="{{ route('guru.nilai.store') }}" method="POST">
                                @csrf
                                <input type="hidden" name="rombel_id" value="{{ $rombel->id }}">
                                <input type="hidden" name="mata_pelajaran_id" value="{{ $mapel->id }}">
                                @foreach($tps as $tp)
                                    <input type="hidden" name="all_tp_ids[]" value="{{ $tp->id }}">
                                @endforeach

                                <div class="overflow-x-auto border border-gray-200 rounded-lg shadow-sm">
                                    <table class="min-w-full divide-y divide-gray-200" style="table-layout: fixed;">
                                        <thead class="bg-gray-100">
                                            <tr>
                                                <th scope="col" rowspan="2" class="sticky left-0 bg-gray-100 z-10 px-4 py-3 text-center text-xs font-bold text-gray-700 uppercase tracking-wider w-12 border-r border-gray-200">No</th>
                                                <th scope="col" rowspan="2" class="sticky left-12 bg-gray-100 z-10 px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider w-48 border-r border-gray-200">Nama Siswa</th>
                                                <th scope="col" rowspan="2" class="px-4 py-3 text-center text-xs font-bold text-gray-700 uppercase tracking-wider w-24 border-r border-gray-200 bg-red-50 text-red-800">Nilai Akhir<br>(0-100)</th>
                                                <th scope="col" colspan="{{ $tps->count() }}" class="px-4 py-2 text-center text-xs font-bold text-gray-700 uppercase tracking-wider border-b border-gray-200 bg-green-50 text-green-800">Capaian Tertinggi (Optimal)</th>
                                                <th scope="col" colspan="{{ $tps->count() }}" class="px-4 py-2 text-center text-xs font-bold text-gray-700 uppercase tracking-wider border-b border-gray-200 bg-yellow-50 text-yellow-800">Capaian Terendah (Perlu Bimbingan)</th>
                                            </tr>
                                            <tr>
                                                @foreach($tps as $index => $tp)
                                                    <th scope="col" class="px-2 py-2 text-center text-xs text-gray-600 border-r border-gray-200 bg-green-50" title="{{ $tp->deskripsi }}">TP {{ $index + 1 }}</th>
                                                @endforeach
                                                @foreach($tps as $index => $tp)
                                                    <th scope="col" class="px-2 py-2 text-center text-xs text-gray-600 border-r border-gray-200 bg-yellow-50" title="{{ $tp->deskripsi }}">TP {{ $index + 1 }}</th>
                                                @endforeach
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200">
                                            @foreach($siswas as $idx => $siswa)
                                                @php
                                                    $nilaiSiswa = $nilai->get($siswa->id);
                                                    $tpTinggiRaw = $nilaiSiswa ? ($nilaiSiswa->tp_tertinggi ?? []) : [];
                                                    $tpTinggiArray = is_string($tpTinggiRaw) ? json_decode($tpTinggiRaw, true) : $tpTinggiRaw;
                                                    if (!is_array($tpTinggiArray)) $tpTinggiArray = [];
                                                    $tpTinggiArray = array_map(function($i) { return is_array($i) ? ($i['id'] ?? $i) : $i; }, $tpTinggiArray);

                                                    $tpRendahRaw = $nilaiSiswa ? ($nilaiSiswa->tp_terendah ?? []) : [];
                                                    $tpRendahArray = is_string($tpRendahRaw) ? json_decode($tpRendahRaw, true) : $tpRendahRaw;
                                                    if (!is_array($tpRendahArray)) $tpRendahArray = [];
                                                    $tpRendahArray = array_map(function($i) { return is_array($i) ? ($i['id'] ?? $i) : $i; }, $tpRendahArray);
                                                @endphp
                                                <tr class="hover:bg-gray-50 transition-colors">
                                                    <td class="sticky left-0 bg-white group-hover:bg-gray-50 z-10 px-4 py-3 text-sm text-gray-500 text-center border-r border-gray-200">{{ $idx + 1 }}</td>
                                                    <td class="sticky left-12 bg-white group-hover:bg-gray-50 z-10 px-6 py-3 text-sm font-bold text-gray-900 border-r border-gray-200 truncate" title="{{ $siswa->nama_lengkap }}">{{ $siswa->nama_lengkap }}</td>
                                                    <td class="px-4 py-3 text-center border-r border-gray-200">
                                                        <input type="number" name="nilai[{{ $siswa->id }}][nilai_akhir]" value="{{ $nilaiSiswa->nilai_akhir ?? '' }}" min="0" max="100" class="w-16 text-center rounded border-gray-300 focus:border-red-500 focus:ring-red-500 text-sm font-bold bg-red-50">
                                                    </td>
                                                    
                                                    <!-- Checkboxes Tertinggi -->
                                                    @foreach($tps as $tp)
                                                        <td class="px-2 py-3 text-center border-r border-gray-200">
                                                            <input type="checkbox" name="nilai[{{ $siswa->id }}][tp_tertinggi][]" value="{{ $tp->id }}" {{ in_array($tp->id, $tpTinggiArray) ? 'checked' : '' }} class="rounded text-green-600 focus:ring-green-500 w-4 h-4">
                                                        </td>
                                                    @endforeach
                                                    
                                                    <!-- Checkboxes Terendah -->
                                                    @foreach($tps as $tp)
                                                        <td class="px-2 py-3 text-center border-r border-gray-200">
                                                            <input type="checkbox" name="nilai[{{ $siswa->id }}][tp_terendah][]" value="{{ $tp->id }}" {{ in_array($tp->id, $tpRendahArray) ? 'checked' : '' }} class="rounded text-yellow-500 focus:ring-yellow-500 w-4 h-4">
                                                        </td>
                                                    @endforeach
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                                <div class="mt-6 flex justify-end">
                                    <button type="submit" class="bg-red-800 hover:bg-red-700 text-white font-bold py-3 px-8 rounded shadow-lg transition-colors inline-flex items-center text-lg">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
                                        Simpan Data Nilai
                                    </button>
                                </div>
                            </form>
                        @endif
                    </div>
                </div>
            @else
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-12 text-center text-gray-500">
                        <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9.5L18.5 8M6 12h12M6 16h12"></path></svg>
                        <p class="text-lg">Silakan pilih Kelas dan Mata Pelajaran terlebih dahulu di atas.</p>
                    </div>
                </div>
            @endif
        </div>
    </div>

</x-app-layout>
