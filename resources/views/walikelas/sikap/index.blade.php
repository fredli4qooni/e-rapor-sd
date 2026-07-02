<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Input Nilai Sikap (K13 / Merdeka)') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if (session('status'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <span class="block sm:inline">{{ session('status') }}</span>
                        </div>
                    @endif

                    <h3 class="text-lg font-bold mb-4">Kelas: {{ $rombel->nama_rombel ?? '-' }}</h3>
                    
                    <form action="{{ route('walikelas.sikap.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="rombel_id" value="{{ $rombel->id }}">
                        
                        <div class="overflow-x-auto">
                            <table class="min-w-full bg-white border border-gray-200 text-sm">
                                <thead class="bg-gray-100 sticky top-0 shadow">
                                    <tr class="text-left">
                                        <th class="py-3 px-2 border-b w-8 text-center" rowspan="2">No</th>
                                        <th class="py-3 px-4 border-b w-48" rowspan="2">Nama Siswa</th>
                                        <th class="py-3 px-2 border-b text-center" colspan="2">Sikap Spiritual</th>
                                        <th class="py-3 px-2 border-b text-center" colspan="2">Sikap Sosial</th>
                                    </tr>
                                    <tr class="text-left bg-gray-50">
                                        <th class="py-2 px-2 border-b w-32 text-center">Predikat</th>
                                        <th class="py-2 px-4 border-b">Deskripsi</th>
                                        <th class="py-2 px-2 border-b w-32 text-center">Predikat</th>
                                        <th class="py-2 px-4 border-b">Deskripsi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($siswas as $index => $siswa)
                                    <tr class="hover:bg-gray-50">
                                        <td class="py-2 px-2 border-b text-center">{{ $loop->iteration }}</td>
                                        <td class="py-2 px-4 border-b">{{ $siswa->nama_lengkap }}</td>
                                        
                                        <!-- Spiritual -->
                                        <td class="py-2 px-2 border-b">
                                            <select name="data[{{ $siswa->id }}][predikat_spiritual]" class="w-full border-gray-300 rounded-md shadow-sm">
                                                <option value="">--</option>
                                                @foreach(['Sangat Baik', 'Baik', 'Cukup', 'Kurang'] as $p)
                                                <option value="{{ $p }}" {{ (old('data.'.$siswa->id.'.predikat_spiritual', $sikaps[$siswa->id]->predikat_spiritual ?? '') == $p) ? 'selected' : '' }}>{{ $p }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td class="py-2 px-4 border-b">
                                            <input type="text" name="data[{{ $siswa->id }}][deskripsi_spiritual]" class="w-full border-gray-300 rounded-md shadow-sm" value="{{ old('data.'.$siswa->id.'.deskripsi_spiritual', $sikaps[$siswa->id]->deskripsi_spiritual ?? '') }}">
                                        </td>

                                        <!-- Sosial -->
                                        <td class="py-2 px-2 border-b">
                                            <select name="data[{{ $siswa->id }}][predikat_sosial]" class="w-full border-gray-300 rounded-md shadow-sm">
                                                <option value="">--</option>
                                                @foreach(['Sangat Baik', 'Baik', 'Cukup', 'Kurang'] as $p)
                                                <option value="{{ $p }}" {{ (old('data.'.$siswa->id.'.predikat_sosial', $sikaps[$siswa->id]->predikat_sosial ?? '') == $p) ? 'selected' : '' }}>{{ $p }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td class="py-2 px-4 border-b">
                                            <input type="text" name="data[{{ $siswa->id }}][deskripsi_sosial]" class="w-full border-gray-300 rounded-md shadow-sm" value="{{ old('data.'.$siswa->id.'.deskripsi_sosial', $sikaps[$siswa->id]->deskripsi_sosial ?? '') }}">
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="mt-6 flex justify-end">
                            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-6 rounded shadow">
                                Simpan Data Sikap
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
