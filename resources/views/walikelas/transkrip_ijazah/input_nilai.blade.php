<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight uppercase">
            {{ __('Input Nilai Ijazah') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-t-4 border-red-800">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-bold text-gray-800 mb-6 uppercase border-b pb-2">Input Nilai Ijazah</h3>

                    <form method="GET" action="{{ route('walikelas.transkrip_ijazah.input_nilai') }}" class="mb-8 p-4 border border-gray-200 rounded-md bg-white">
                        <div class="space-y-4">
                            <div class="flex flex-col md:flex-row md:items-center gap-4">
                                <label class="w-full md:w-48 font-bold text-gray-700 text-sm">Pilih Kelas</label>
                                <input type="text" value="{{ $rombel->tingkat . ' ' . strtoupper($rombel->nama_rombel) }}" class="flex-1 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-md p-2 max-w-3xl" readonly>
                            </div>
                            <div class="flex flex-col md:flex-row md:items-center gap-4">
                                <label class="w-full md:w-48 font-bold text-gray-700 text-sm">Pilih Mapel</label>
                                <select name="mata_pelajaran_id" class="flex-1 border border-gray-300 text-gray-900 text-sm rounded-md p-2 max-w-3xl focus:ring-red-500 focus:border-red-500" onchange="this.form.submit()">
                                    <option value="">Pilih Mata Pelajaran</option>
                                    @foreach($mapels as $mapel)
                                        <option value="{{ $mapel->id }}" {{ $mata_pelajaran_id == $mapel->id ? 'selected' : '' }}>
                                            {{ $mapel->nama_mapel }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </form>

                    @if($mata_pelajaran_id && count($siswas) > 0)
                    <form method="POST" action="{{ route('walikelas.transkrip_ijazah.store_nilai') }}">
                        @csrf
                        <input type="hidden" name="mata_pelajaran_id" value="{{ $mata_pelajaran_id }}">
                        
                        <div class="overflow-x-auto border border-gray-300 rounded-t-md">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-red-900 text-white shadow">
                                    <tr class="text-center text-xs uppercase">
                                        <th class="px-4 py-3 border border-red-800 w-12 align-middle">No</th>
                                        <th class="px-4 py-3 border border-red-800 align-middle text-left">Nama Siswa</th>
                                        <th class="px-4 py-3 border border-red-800 w-32 align-middle">NISN</th>
                                        <th class="px-4 py-3 border border-red-800 w-32 align-middle">NIS</th>
                                        <th class="px-4 py-3 border border-red-800 w-32 align-middle text-left">Nilai</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($siswas as $index => $siswa)
                                    <tr class="hover:bg-red-50 transition-colors {{ $index % 2 == 0 ? 'bg-white' : 'bg-gray-50' }}">
                                        <td class="px-4 py-3 text-sm text-center border border-gray-300">{{ $loop->iteration }}</td>
                                        <td class="px-4 py-3 text-sm border border-gray-300 font-medium uppercase">{{ $siswa->nama_lengkap }}</td>
                                        <td class="px-4 py-3 text-sm text-center border border-gray-300">{{ $siswa->nisn }}</td>
                                        <td class="px-4 py-3 text-sm text-center border border-gray-300">{{ $siswa->nis }}</td>
                                        <td class="px-4 py-2 border border-gray-300">
                                            <input type="number" min="0" max="100" name="nilai[{{ $siswa->id }}]" value="{{ $nilaiTranskrip[$siswa->id] ?? '' }}" class="w-full border-gray-300 rounded-md shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm px-2 py-1.5" required>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-4 flex justify-end">
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-900 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-800 focus:bg-red-800 active:bg-red-900 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
                                Simpan Data
                            </button>
                        </div>
                    </form>
                    @elseif($mata_pelajaran_id && count($siswas) == 0)
                        <div class="p-4 bg-yellow-50 border border-yellow-200 text-yellow-800 rounded">
                            Tidak ada data siswa di kelas ini.
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
