<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight uppercase">
            {{ __('Import Nilai Ekstrakurikuler') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-t-4 border-red-800">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-6 border-b pb-2">
                        <h3 class="text-lg font-bold text-gray-800 uppercase">IMPORT EXCEL KELAS {{ strtoupper($rombel->nama_rombel ?? '-') }}</h3>
                        <a href="{{ route('walikelas.ekskul.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded shadow transition-colors text-sm">
                            Kembali
                        </a>
                    </div>
                    
                    @if (session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif
                    @if (session('error'))
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <span class="block sm:inline">{{ session('error') }}</span>
                        </div>
                    @endif

                    <div class="mb-8 p-4 bg-blue-50 border-l-4 border-blue-500 rounded text-blue-800">
                        <h4 class="font-bold mb-2">Langkah-langkah Import:</h4>
                        <ol class="list-decimal pl-5 space-y-1 text-sm">
                            <li>Download format excel dengan mengklik tombol <strong>Download Format</strong> di bawah ini.</li>
                            <li>Buka file excel yang telah didownload.</li>
                            <li>Isi kolom predikat dan keterangan sesuai dengan nilai ekstrakurikuler siswa. Jangan mengubah ID Siswa maupun format kolom lainnya!</li>
                            <li>Simpan file excel.</li>
                            <li>Upload file excel yang telah diisi pada form di bawah ini.</li>
                            <li>Klik tombol <strong>Upload & Import Data</strong>.</li>
                        </ol>
                        <div class="mt-4">
                            <a href="{{ route('walikelas.ekskul.download_format') }}" class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded shadow transition-colors text-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                </svg>
                                Download Format Excel
                            </a>
                        </div>
                    </div>

                    <form action="{{ route('walikelas.ekskul.import_store') }}" method="POST" enctype="multipart/form-data" class="bg-gray-50 p-6 rounded border border-gray-200">
                        @csrf
                        <input type="hidden" name="rombel_id" value="{{ $rombel->id }}">
                        
                        <div class="mb-4">
                            <label class="block text-sm font-bold text-gray-700 mb-2">Pilih File Excel (.xls, .xlsx)</label>
                            <input type="file" name="file_nilai" accept=".xls,.xlsx" required class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-sm file:font-semibold file:bg-red-50 file:text-red-700 hover:file:bg-red-100 border border-gray-300 rounded focus:outline-none focus:border-red-500 focus:ring-red-500 bg-white">
                            @error('file_nilai')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mt-6 flex justify-end">
                            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-6 rounded shadow-lg transition-colors flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM6.293 6.707a1 1 0 010-1.414l3-3a1 1 0 011.414 0l3 3a1 1 0 01-1.414 1.414L11 5.414V13a1 1 0 11-2 0V5.414L7.707 6.707a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                </svg>
                                Upload & Import Data
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
