<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Import Nomor Ijazah Siswa') }}
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
                Form Import Nomor Ijazah & Transkrip (Format CSV)
            </div>
            
            <div class="p-6 space-y-6">
                <!-- Info Section -->
                <div class="bg-blue-50 border-l-4 border-blue-500 p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-blue-700">
                                Transkrip Nilai Ijazah memuat data <strong>Nomor Ijazah Nasional</strong>, <strong>Nomor Transkrip Nilai Ijazah</strong>, dan <strong>Tanggal Kelulusan</strong>. Anda dapat mengunduh format CSV di bawah ini, mengisinya dengan aplikasi Spreadsheet (seperti Excel), lalu menyimpannya kembali dalam format <strong>CSV (Comma Delimited)</strong> untuk diimpor.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Download Action -->
                <div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">1. Unduh Format Import</h3>
                    <a href="{{ route('admin.transkrip_ijazah.import_nomor.download_format') }}" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 active:bg-green-800 focus:outline-none focus:border-green-900 focus:ring ring-green-300 disabled:opacity-25 transition ease-in-out duration-150">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                        Download Format Import (.csv)
                    </a>
                </div>

                <hr class="border-gray-200">

                <!-- Upload Action -->
                <div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">2. Upload File CSV</h3>
                    <form action="{{ route('admin.transkrip_ijazah.import_nomor.store') }}" method="POST" enctype="multipart/form-data" class="flex flex-col sm:flex-row gap-4 items-start sm:items-center">
                        @csrf
                        <div class="w-full sm:max-w-md">
                            <input type="file" name="file_import" id="file_import" accept=".csv" required class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-red-50 file:text-red-700 hover:file:bg-red-100 border border-gray-300 rounded-md">
                        </div>
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-900 active:bg-red-900 focus:outline-none focus:border-red-900 focus:ring ring-red-300 disabled:opacity-25 transition ease-in-out duration-150 shadow-sm">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
                            Import Nomor Ijazah
                        </button>
                    </form>
                </div>
            </div>
        </div>

    </div>
</x-app-layout>
