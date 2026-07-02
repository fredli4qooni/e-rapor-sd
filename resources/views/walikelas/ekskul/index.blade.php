<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight uppercase">
            {{ __('Input Nilai Ekstra Kurikuler Siswa') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-t-4 border-red-800">
                <div class="p-6 text-gray-900">
                    <div class="mb-6 p-4 border border-gray-200 rounded-md bg-white">
                        <div class="flex flex-col md:flex-row md:items-center gap-4">
                            <label class="w-full md:w-48 font-bold text-gray-700 text-sm">Pilih Kelas</label>
                            <input type="text" value="{{ $rombel->tingkat . ' ' . strtoupper($rombel->nama_rombel) }}" class="flex-1 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-md p-2 max-w-3xl" readonly>
                        </div>
                    </div>

                    <div class="mb-6 p-4 border border-gray-200 rounded-md bg-white">
                        <div class="flex flex-col lg:flex-row items-center justify-between gap-4">
                            <form action="{{ route('walikelas.ekskul.import_store') }}" method="POST" enctype="multipart/form-data" class="flex-1 flex flex-col sm:flex-row items-center gap-4 w-full">
                                @csrf
                                <input type="hidden" name="rombel_id" value="{{ $rombel->id }}">
                                
                                <label class="w-full sm:w-48 font-bold text-gray-700 text-sm">Pilih File Nilai</label>
                                <div class="flex flex-1 items-center gap-4">
                                    <input type="file" name="file_nilai" accept=".xls,.xlsx" required class="text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-gray-200 file:text-gray-700 hover:file:bg-gray-300">
                                    <button type="submit" class="bg-red-800 hover:bg-red-900 text-white font-bold py-2 px-4 rounded shadow transition-colors flex items-center gap-2 text-sm whitespace-nowrap">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                                        </svg>
                                        Import Nilai
                                    </button>
                                </div>
                            </form>

                            <div class="flex items-center gap-2 lg:ml-auto">
                                <a href="{{ route('walikelas.ekskul.download_format') }}" class="bg-red-800 hover:bg-red-900 text-white font-bold py-2 px-4 rounded shadow transition-colors flex items-center gap-2 text-sm whitespace-nowrap">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                    </svg>
                                    Format Nilai
                                </a>
                                <button type="button" onclick="toggleView()" class="bg-teal-700 hover:bg-teal-800 text-white font-bold py-2 px-4 rounded shadow transition-colors flex items-center gap-2 text-sm whitespace-nowrap">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                    </svg>
                                    <span id="btn-toggle-text">Input Nilai</span>
                                </button>
                            </div>
                        </div>
                    </div>

                    @if(session('success'))
                        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif
                    @if(session('error'))
                        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                            <span class="block sm:inline">{{ session('error') }}</span>
                        </div>
                    @endif

                    <!-- LIST VIEW -->
                    <div id="list-view" class="overflow-x-auto mt-4">
                        <table class="min-w-full divide-y divide-gray-200 border border-gray-300">
                            <thead class="bg-red-900 text-white shadow">
                                <tr class="text-left text-xs uppercase">
                                    <th class="px-4 py-3 border border-red-800 w-12 text-center font-bold">No</th>
                                    <th class="px-4 py-3 border border-red-800 w-48 font-bold">Nama Siswa</th>
                                    <th class="px-4 py-3 border border-red-800 w-24 text-center font-bold">NISN</th>
                                    <th class="px-4 py-3 border border-red-800 w-24 text-center font-bold">NIS</th>
                                    <th class="px-4 py-3 border border-red-800 text-center font-bold">Ekskul</th>
                                    <th class="px-4 py-3 border border-red-800 text-center font-bold">Nilai</th>
                                    <th class="px-4 py-3 border border-red-800 font-bold">Deskripsi</th>
                                    <th class="px-4 py-3 border border-red-800 w-24 text-center font-bold">Hapus</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @php
                                    $all_nilai = [];
                                    foreach($nilai_ekskuls as $siswa_id => $nilais) {
                                        foreach($nilais as $nilai) {
                                            $all_nilai[] = $nilai;
                                        }
                                    }
                                @endphp
                                @forelse($all_nilai as $index => $nilai)
                                <tr class="hover:bg-red-50/50 transition-colors {{ $index % 2 == 0 ? 'bg-red-50/20' : 'bg-white' }}">
                                    <td class="px-4 py-3 text-sm text-center text-gray-700 border border-gray-300 align-top">{{ $loop->iteration }}</td>
                                    <td class="px-4 py-3 text-sm font-bold text-red-900 border border-gray-300 align-top uppercase">{{ $nilai->siswa->nama_lengkap ?? '-' }}</td>
                                    <td class="px-4 py-3 text-sm text-center text-gray-700 border border-gray-300 align-top">{{ $nilai->siswa->nisn ?? '-' }}</td>
                                    <td class="px-4 py-3 text-sm text-center text-gray-700 border border-gray-300 align-top">{{ $nilai->siswa->nis ?? '-' }}</td>
                                    <td class="px-4 py-3 text-sm text-center text-gray-700 border border-gray-300 align-top">{{ $nilai->ekstrakurikuler->nama_ekskul ?? '-' }}</td>
                                    <td class="px-4 py-3 text-sm text-center text-gray-700 border border-gray-300 align-top">{{ $nilai->predikat }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-700 border border-gray-300 align-top">{{ $nilai->keterangan }}</td>
                                    <td class="px-4 py-3 text-sm text-center text-gray-700 border border-gray-300 align-top">
                                        <form action="{{ route('walikelas.ekskul.destroy', $nilai->id) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900 border border-red-200 hover:bg-red-50 px-2 py-1 rounded text-xs flex items-center justify-center gap-1 mx-auto transition-colors">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                                Hapus
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="px-6 py-8 whitespace-nowrap text-sm text-gray-500 text-center">
                                        Data Kosong.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- FORM VIEW -->
                    <div id="form-view" class="hidden mt-4">
                        <form id="form-ekskul" action="{{ route('walikelas.ekskul.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="rombel_id" value="{{ $rombel->id }}">
                            
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200 border border-gray-300">
                                    <thead class="bg-red-900 text-white shadow">
                                        <tr class="text-left text-xs uppercase">
                                            <th class="px-4 py-3 border border-red-800 w-12 text-center font-bold">No</th>
                                            <th class="px-4 py-3 border border-red-800 w-48 font-bold">Nama Siswa</th>
                                            <th class="px-4 py-3 border border-red-800 w-24 text-center font-bold">NISN</th>
                                            <th class="px-4 py-3 border border-red-800 w-24 text-center font-bold">NIS</th>
                                            <th class="px-4 py-3 border border-red-800 text-center font-bold w-48">Ekskul</th>
                                            <th class="px-4 py-3 border border-red-800 text-center font-bold w-32">Nilai</th>
                                            <th class="px-4 py-3 border border-red-800 font-bold min-w-[200px]">Deskripsi</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-amber-50 divide-y divide-gray-200">
                                        @forelse($siswas as $index => $siswa)
                                        <tr class="hover:bg-amber-100 transition-colors {{ $index % 2 == 0 ? 'bg-amber-50/50' : 'bg-amber-50' }}">
                                            <td class="px-4 py-3 text-sm text-center text-gray-700 border border-gray-300 align-top">{{ $loop->iteration }}</td>
                                            <td class="px-4 py-3 text-sm font-medium text-amber-900 border border-gray-300 align-top uppercase">{{ $siswa->nama_lengkap }}</td>
                                            <td class="px-4 py-3 text-sm text-center text-gray-700 border border-gray-300 align-top">{{ $siswa->nisn }}</td>
                                            <td class="px-4 py-3 text-sm text-center text-gray-700 border border-gray-300 align-top">{{ $siswa->nis ?? '-' }}</td>
                                            
                                            <td class="px-2 py-2 border border-gray-300 align-top">
                                                <select name="data[{{ $siswa->id }}][ekskul_id]" class="w-full text-sm border-gray-300 rounded focus:border-red-500 focus:ring-red-500">
                                                    <option value="">-- Pilih Ekskul --</option>
                                                    @foreach($ekskuls as $ekskul)
                                                    <option value="{{ $ekskul->id }}">{{ $ekskul->nama_ekskul }}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td class="px-2 py-2 border border-gray-300 align-top">
                                                <select name="data[{{ $siswa->id }}][predikat]" class="w-full text-sm border-gray-300 rounded focus:border-red-500 focus:ring-red-500">
                                                    <option value="">Pilih Nilai</option>
                                                    @foreach(['Sangat Baik', 'Baik', 'Cukup', 'Kurang'] as $p)
                                                    <option value="{{ $p }}">{{ $p }}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td class="px-2 py-2 border border-gray-300 align-top">
                                                <textarea name="data[{{ $siswa->id }}][keterangan]" rows="2" class="w-full text-sm border-gray-300 rounded focus:border-red-500 focus:ring-red-500"></textarea>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="7" class="px-6 py-8 whitespace-nowrap text-sm text-gray-500 text-center">
                                                Belum ada data siswa.
                                            </td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            
                            <div class="mt-6 flex justify-end">
                                <button type="submit" class="bg-teal-700 hover:bg-teal-800 text-white font-bold py-2 px-6 rounded shadow transition-colors flex items-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                    </svg>
                                    Simpan Nilai
                                </button>
                            </div>
                        </form>
                    </div>

                </div>
            </div>

        </div>
    </div>
    
    <script>
        function toggleView() {
            const listView = document.getElementById('list-view');
            const formView = document.getElementById('form-view');
            const btnText = document.getElementById('btn-toggle-text');
            
            if (listView.classList.contains('hidden')) {
                listView.classList.remove('hidden');
                formView.classList.add('hidden');
                btnText.textContent = 'Kembali ke List';
            } else {
                listView.classList.add('hidden');
                formView.classList.remove('hidden');
                btnText.textContent = 'Input Nilai';
            }
        }
    </script>
</x-app-layout>
