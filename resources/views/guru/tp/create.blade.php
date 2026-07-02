<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-white leading-tight">
                {{ __('Tambah Tujuan Pembelajaran (TP)') }}
            </h2>
            <a href="{{ route('guru.tujuan-pembelajaran.index') }}" class="bg-gray-800 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded shadow text-sm">
                &larr; Kembali
            </a>
        </div>
    </x-slot>

    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 border-b border-gray-200">
                    
                    <div class="mb-6 bg-blue-50 border-l-4 border-blue-500 p-4 rounded-r-lg">
                        <h4 class="font-bold text-blue-800 mb-2">Panduan Penulisan TP:</h4>
                        <ul class="list-disc list-inside text-sm text-blue-700 space-y-1">
                            <li>Memuat <strong>kompetensi</strong> dan <strong>konten/materi</strong>.</li>
                            <li>Diawali dengan <strong>huruf kecil</strong>.</li>
                            <li>Tidak perlu kata pembuka seperti <em>"Peserta didik dapat..."</em>.</li>
                            <li>Maksimal <strong>10 TP</strong> dalam sekali simpan.</li>
                            <li>Contoh: <em>"memahami makna kedaulatan rakyat dalam sistem pemerintahan Indonesia"</em></li>
                        </ul>
                    </div>

                    <form action="{{ route('guru.tujuan-pembelajaran.store') }}" method="POST" x-data="tpForm()">
                        @csrf
                        
                        <!-- Pilihan Mapel & Tingkat -->
                        <div class="mb-8">
                            <label for="mapel_tingkat" class="block text-sm font-bold text-gray-700 mb-2">Pilih Mata Pelajaran & Tingkat Kelas <span class="text-red-500">*</span></label>
                            <select name="mapel_tingkat" id="mapel_tingkat" required class="block w-full md:w-2/3 rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm bg-gray-50">
                                <option value="">-- Silakan Pilih --</option>
                                @foreach($mapelDiampu as $m)
                                    <option value="{{ $m['mata_pelajaran_id'] }}-{{ $m['tingkat'] }}">
                                        {{ $m['nama_mapel'] }} - Kelas {{ $m['tingkat'] }}
                                    </option>
                                @endforeach
                            </select>
                            @error('mapel_tingkat')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Dynamic Rows -->
                        <div class="mb-6">
                            <div class="flex justify-between items-end mb-2">
                                <label class="block text-sm font-bold text-gray-700">Deskripsi Tujuan Pembelajaran</label>
                                <span class="text-xs font-semibold px-2 py-1 bg-gray-100 text-gray-600 rounded-full" x-text="rows.length + '/10 Baris'"></span>
                            </div>

                            <div class="space-y-3">
                                <template x-for="(row, index) in rows" :key="row.id">
                                    <div class="flex gap-2 items-start group">
                                        <div class="pt-2 text-gray-400 font-bold w-6 text-center" x-text="(index + 1) + '.'"></div>
                                        <div class="flex-1">
                                            <textarea name="deskripsi[]" x-model="row.value" rows="2" required 
                                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm transition-colors" 
                                                placeholder="Contoh: menganalisis pelaksanaan sistem pemerintahan..."></textarea>
                                        </div>
                                        <button type="button" @click="removeRow(index)" x-show="rows.length > 1" class="pt-2 text-red-400 hover:text-red-600 opacity-50 group-hover:opacity-100 transition-opacity" title="Hapus Baris">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </div>
                                </template>
                            </div>
                            
                            @error('deskripsi')
                                <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                            @enderror
                            @error('deskripsi.*')
                                <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                            @enderror

                            <!-- Add Button -->
                            <button type="button" @click="addRow" x-show="rows.length < 10" class="mt-4 inline-flex items-center px-4 py-2 bg-gray-100 border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                                Tambah Baris TP
                            </button>
                        </div>

                        <!-- Submit -->
                        <div class="mt-8 pt-5 border-t border-gray-200 flex justify-end">
                            <button type="submit" class="bg-red-800 hover:bg-red-700 text-white font-bold py-2 px-6 rounded shadow transition-colors inline-flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
                                Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- AlpineJS Logic -->
    <script>
        function tpForm() {
            return {
                rows: [
                    { id: Date.now(), value: '' }
                ],
                addRow() {
                    if (this.rows.length < 10) {
                        this.rows.push({ id: Date.now(), value: '' });
                    } else {
                        alert('Maksimal 10 Tujuan Pembelajaran dalam satu kali simpan.');
                    }
                },
                removeRow(index) {
                    if (this.rows.length > 1) {
                        this.rows.splice(index, 1);
                    }
                }
            }
        }
    </script>
</x-app-layout>
