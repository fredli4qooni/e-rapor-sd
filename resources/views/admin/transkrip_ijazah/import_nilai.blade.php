<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Import Nilai Transkrip Ijazah') }}
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

        <div class="bg-white rounded-md shadow-md overflow-hidden">
            <div class="bg-[#8B1515] text-white px-6 py-4 font-bold tracking-wider uppercase text-sm border-b border-red-800">
                Form Import Nilai Transkrip per Mapel
            </div>
            
            <div class="p-6 space-y-6">
                <!-- Download Action -->
                <div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">1. Unduh Format Import Nilai</h3>
                    <form action="{{ route('admin.transkrip_ijazah.import_nilai.download_format') }}" method="GET" class="flex flex-col sm:flex-row gap-4 items-end">
                        <div class="w-full sm:w-1/3">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Pilih Kelas</label>
                            <select name="rombel_id" class="w-full border-gray-300 rounded-md shadow-sm focus:border-red-500 focus:ring-red-500 text-sm" required>
                                <option value="">Pilih Kelas...</option>
                                @foreach($rombels as $rombel)
                                    <option value="{{ $rombel->id }}">{{ $rombel->nama_rombel }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="w-full sm:w-1/3">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Mata Pelajaran</label>
                            <select name="mata_pelajaran_id" class="w-full border-gray-300 rounded-md shadow-sm focus:border-red-500 focus:ring-red-500 text-sm" required>
                                <option value="">Pilih Mata Pelajaran...</option>
                                @foreach($mapels as $mapel)
                                    <option value="{{ $mapel->id }}">{{ $mapel->nama_mapel }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 active:bg-green-800 focus:outline-none focus:border-green-900 focus:ring ring-green-300 disabled:opacity-25 transition ease-in-out duration-150 h-10 w-full sm:w-auto justify-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                Download Format CSV
                            </button>
                        </div>
                    </form>
                    <div class="mt-2 text-sm text-gray-500">
                        * Pilih kelas dan mata pelajaran, lalu klik download untuk mendapatkan template CSV berisi nama siswa dan kolom nilai kosong.
                    </div>
                </div>

                <hr class="border-gray-200">

                <!-- Upload Action -->
                <div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">2. Upload File CSV</h3>
                    <form action="{{ route('admin.transkrip_ijazah.import_nilai.store') }}" method="POST" enctype="multipart/form-data" class="flex flex-col sm:flex-row gap-4 items-end">
                        @csrf
                        <div class="w-full sm:w-1/3">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Mata Pelajaran</label>
                            <select name="mata_pelajaran_id" class="w-full border-gray-300 rounded-md shadow-sm focus:border-red-500 focus:ring-red-500 text-sm" required>
                                <option value="">Pilih Mata Pelajaran...</option>
                                @foreach($mapels as $mapel)
                                    <option value="{{ $mapel->id }}">{{ $mapel->nama_mapel }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="w-full sm:w-1/3">
                            <label class="block text-sm font-medium text-gray-700 mb-1">File CSV</label>
                            <input type="file" name="file_import" id="file_import" accept=".csv" required class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-red-50 file:text-red-700 hover:file:bg-red-100 border border-gray-300 rounded-md h-10">
                        </div>
                        <div>
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-800 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150 h-10 w-full sm:w-auto justify-center shadow-sm">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
                                Import Nilai
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
</x-app-layout>
