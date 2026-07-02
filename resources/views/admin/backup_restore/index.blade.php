<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Backup dan Restore Data') }}
        </h2>
    </x-slot>

    <div class="py-6 space-y-6 max-w-7xl mx-auto sm:px-6 lg:px-8">
        
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
        @endif

        @php
            $activeTab = session('tab', 'backup');
        @endphp

        <div class="bg-white rounded-lg shadow-md" x-data="{ activeTab: '{{ $activeTab }}' }">
            <div class="border-b border-gray-200">
                <nav class="-mb-px flex" aria-label="Tabs">
                    <button @click="activeTab = 'backup'" 
                            :class="{ 'border-red-500 text-red-600': activeTab === 'backup', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'backup' }" 
                            class="w-1/3 py-4 px-1 text-center border-b-2 font-medium text-sm">
                        Backup Data
                    </button>
                    <button @click="activeTab = 'restore'" 
                            :class="{ 'border-red-500 text-red-600': activeTab === 'restore', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'restore' }" 
                            class="w-1/3 py-4 px-1 text-center border-b-2 font-medium text-sm">
                        Restore Data
                    </button>
                    <button @click="activeTab = 'restore_sp'" 
                            :class="{ 'border-red-500 text-red-600': activeTab === 'restore_sp', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'restore_sp' }" 
                            class="w-1/3 py-4 px-1 text-center border-b-2 font-medium text-sm">
                        Restore Data dari Rapor SP 2025
                    </button>
                </nav>
            </div>

            <div class="p-6">
                <!-- Tab Backup -->
                <div x-show="activeTab === 'backup'" style="display: none;">
                    <div class="bg-blue-50 border-l-4 border-blue-400 p-4 mb-6">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-blue-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-blue-700">
                                    <strong>Catatan:</strong><br>
                                    Proses Backup dan Restore Data e-Rapor disarankan langsung dari server e-Rapor bukan melalui komputer client.<br>
                                    Untuk keamanan, silahkan lakukan proses backup data secara rutin dan simpan hasil backup Anda pada tempat yang aman.
                                </p>
                            </div>
                        </div>
                    </div>

                    <form action="{{ route('admin.backup_restore.backup') }}" method="POST">
                        @csrf
                        <button type="submit" class="bg-red-800 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="return confirm('Mulai proses backup database? Ini mungkin memakan waktu beberapa saat.')">
                            <svg class="w-5 h-5 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4"></path></svg>
                            Backup Data
                        </button>
                    </form>

                    @if(session('success_backup'))
                        <div class="mt-6 border-t pt-4">
                            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                                Backup berhasil dibuat!
                            </div>
                            <a href="{{ route('admin.backup_restore.download', session('success_backup')) }}" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded inline-block">
                                <svg class="w-5 h-5 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                Download Hasil Backup
                            </a>
                        </div>
                    @endif
                </div>

                <!-- Tab Restore -->
                <div x-show="activeTab === 'restore'" style="display: none;">
                    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-6">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-yellow-700">
                                    <strong>Peringatan!</strong><br>
                                    Proses restore akan mengembalikan data dari file backup (.sql) dan menimpa database yang ada saat ini secara keseluruhan. Pastikan file backup Anda benar.
                                </p>
                            </div>
                        </div>
                    </div>

                    <form action="{{ route('admin.backup_restore.restore') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-4">
                            <label for="file_sql" class="block text-sm font-medium text-gray-700 mb-2">Pilih File Backup e-Rapor (.sql)</label>
                            <input type="file" id="file_sql" name="file_sql" accept=".sql" required class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-sm file:font-semibold file:bg-red-50 file:text-red-700 hover:file:bg-red-100 border border-gray-300 rounded p-1">
                        </div>
                        <button type="submit" class="bg-red-800 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="return confirm('Apakah Anda yakin ingin melakukan restore? Data e-Rapor yang ada saat ini akan tergantikan.')">
                            <svg class="w-5 h-5 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                            Upload dan Restore Data eRapor
                        </button>
                    </form>
                </div>

                <!-- Tab Restore SP -->
                <div x-show="activeTab === 'restore_sp'" style="display: none;">
                    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-6">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-yellow-700">
                                    <strong>Peringatan!</strong><br>
                                    Restore Data dari Rapor SP 2025 diperuntukkan bagi pengguna Rapor SP 2025 yang ingin memigrasikan data hasil backup Rapor SP 2025 ke e-Rapor SD 2025.<br>
                                    Pastikan file yang diupload adalah format file .sql dari Rapor SP 2025.
                                </p>
                            </div>
                        </div>
                    </div>

                    <form action="{{ route('admin.backup_restore.restore_sp') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-4">
                            <label for="file_sql_sp" class="block text-sm font-medium text-gray-700 mb-2">Pilih File Backup Rapor SP 2025 (.sql)</label>
                            <input type="file" id="file_sql_sp" name="file_sql_sp" accept=".sql" required class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-sm file:font-semibold file:bg-red-50 file:text-red-700 hover:file:bg-red-100 border border-gray-300 rounded p-1">
                        </div>
                        <button type="submit" class="bg-red-800 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="return confirm('Apakah Anda yakin ingin melakukan migrasi dari Rapor SP 2025? Data e-Rapor yang ada saat ini akan tergantikan.')">
                            <svg class="w-5 h-5 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                            Upload dan Restore Data Rapor SP
                        </button>
                    </form>
                </div>
            </div>
        </div>

    </div>
</x-app-layout>
