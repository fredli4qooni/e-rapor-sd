<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Input Nilai Transkrip Ijazah') }}
        </h2>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8">
        
        @if(session('success'))
            <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        @if($errors->any())
            <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>- {{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Filter -->
        <div class="bg-white rounded-md shadow-md overflow-hidden mb-6">
            <div class="p-4 border-b">
                <form action="{{ route('admin.transkrip_ijazah.input_nilai.index') }}" method="GET" class="flex flex-col md:flex-row gap-4 items-end">
                    <div class="w-full md:w-1/3">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Kelas Lulus (Kelas 6)</label>
                        <select name="rombel_id" class="w-full border-gray-300 rounded-md shadow-sm focus:border-red-500 focus:ring-red-500 text-sm" required>
                            <option value="">Pilih Kelas...</option>
                            @foreach($rombels as $rombel)
                                <option value="{{ $rombel->id }}" {{ request('rombel_id') == $rombel->id ? 'selected' : '' }}>{{ $rombel->nama_rombel }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="w-full md:w-1/3">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Mata Pelajaran (Transkrip)</label>
                        <select name="mata_pelajaran_id" class="w-full border-gray-300 rounded-md shadow-sm focus:border-red-500 focus:ring-red-500 text-sm" required>
                            <option value="">Pilih Mata Pelajaran...</option>
                            @foreach($mapels as $mapel)
                                <option value="{{ $mapel->id }}" {{ request('mata_pelajaran_id') == $mapel->id ? 'selected' : '' }}>{{ $mapel->nama_mapel }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <button type="submit" class="bg-gray-800 text-white px-4 py-2 rounded-md hover:bg-gray-700 transition font-medium text-sm w-full md:w-auto">
                            Tampilkan
                        </button>
                    </div>
                </form>
            </div>
        </div>

        @if($rombel_id && $mata_pelajaran_id)
            <div class="bg-white rounded-md shadow-md overflow-hidden">
                <div class="bg-blue-800 text-white px-6 py-4 font-bold tracking-wider uppercase text-sm border-b border-blue-900 flex justify-between items-center">
                    <span>Input Nilai Manual</span>
                    <a href="{{ route('admin.transkrip_ijazah.import_nilai.index') }}" class="text-xs bg-white text-blue-800 px-3 py-1 rounded hover:bg-gray-100 transition">Atau Import via Excel</a>
                </div>
                <div class="p-0">
                    @if(count($siswas) > 0)
                        <form action="{{ route('admin.transkrip_ijazah.input_nilai.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="mata_pelajaran_id" value="{{ $mata_pelajaran_id }}">
                            
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-16">No</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">NISN</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Siswa</th>
                                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-40">Nilai (0-100)</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach($siswas as $index => $siswa)
                                        <tr class="hover:bg-gray-50 transition">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                                {{ $index + 1 }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ $siswa->nisn }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                {{ $siswa->nama_lengkap }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-center">
                                                <input type="number" name="nilai[{{ $siswa->id }}]" value="{{ $nilaiTranskrip[$siswa->id] ?? '' }}" min="0" max="100" class="w-24 text-center border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="bg-gray-50 px-6 py-4 border-t flex justify-end">
                                <button type="submit" class="inline-flex items-center px-6 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-sm text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-800 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150 shadow-md">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
                                    Simpan Nilai Transkrip
                                </button>
                            </div>
                        </form>
                    @else
                        <div class="p-8 text-center text-gray-500">
                            Tidak ada data siswa yang ditemukan pada kelas ini.
                        </div>
                    @endif
                </div>
            </div>
        @else
            <div class="bg-gray-50 border border-gray-200 rounded-md p-8 text-center text-gray-500">
                Silakan pilih Kelas dan Mata Pelajaran untuk mulai menginput nilai.
            </div>
        @endif

    </div>
</x-app-layout>
