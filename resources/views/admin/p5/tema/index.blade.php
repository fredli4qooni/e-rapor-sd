<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Daftar Tema Kokurikuler') }}
        </h2>
    </x-slot>

    <div class="py-6 space-y-6 max-w-7xl mx-auto sm:px-6 lg:px-8" x-data="{ showModal: false, isEdit: false, modalData: { id: '', nama_tema: '', deskripsi: '', is_aktif: '1' } }">

        <!-- Flash Message -->
        @if (session('status'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative shadow-sm" role="alert">
                <span class="block sm:inline">{{ session('status') }}</span>
            </div>
        @endif

        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative shadow-sm" role="alert">
                <ul class="list-disc list-inside text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded text-sm text-blue-800 shadow-sm mb-6 flex justify-between items-center">
            <p><strong>Informasi:</strong> Tema Kokurikuler berfungsi mengaitkan kegiatan kokurikuler sesuai konteks. Daftar tema yang telah diinput tidak dapat dihapus, namun dapat dinonaktifkan.</p>
            <button @click="isEdit = false; modalData = { id: '', nama_tema: '', deskripsi: '', is_aktif: '1' }; showModal = true" class="bg-red-600 hover:bg-red-500 text-white text-xs py-2 px-4 rounded shadow font-semibold flex-shrink-0">
                + Tambah Tema
            </button>
        </div>

        <div class="bg-white rounded-md shadow-md overflow-hidden">
            <div class="bg-[#8B1515] text-white px-6 py-4 font-bold tracking-wider uppercase text-sm border-b border-red-800">
                Daftar Tema Kokurikuler
            </div>
            <div class="p-6 overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-700 border">
                    <thead class="text-xs text-white bg-gray-800 uppercase border-b">
                        <tr>
                            <th class="px-4 py-3 w-16 text-center">No</th>
                            <th class="px-4 py-3">Tema Kegiatan Kokurikuler</th>
                            <th class="px-4 py-3">Deskripsi Singkat</th>
                            <th class="px-4 py-3 text-center">Status</th>
                            <th class="px-4 py-3 text-center w-32">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($temas as $index => $tema)
                            <tr class="border-b hover:bg-gray-50">
                                <td class="px-4 py-3 text-center font-medium">{{ $index + 1 }}</td>
                                <td class="px-4 py-3 font-bold text-gray-800">{{ $tema->nama_tema }}</td>
                                <td class="px-4 py-3">{{ $tema->deskripsi }}</td>
                                <td class="px-4 py-3 text-center">
                                    @if($tema->is_aktif)
                                        <span class="bg-green-100 text-green-800 text-xs px-2 py-1 rounded">Aktif</span>
                                    @else
                                        <span class="bg-red-100 text-red-800 text-xs px-2 py-1 rounded">Tidak Aktif</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <button @click="isEdit = true; modalData = { id: '{{ $tema->id }}', nama_tema: '{{ addslashes($tema->nama_tema) }}', deskripsi: '{{ addslashes($tema->deskripsi) }}', is_aktif: '{{ $tema->is_aktif ? '1' : '0' }}' }; showModal = true" class="text-blue-600 hover:text-blue-800 font-medium">Edit</button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-3 text-center text-gray-500">Belum ada data Tema Kokurikuler.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Modal Tambah/Edit -->
        <div x-show="showModal" x-cloak class="fixed z-50 inset-0 overflow-y-auto" style="display: none;">
            <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div x-show="showModal" class="fixed inset-0 transition-opacity" aria-hidden="true" @click="showModal = false">
                    <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
                </div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div x-show="showModal" class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <form :action="isEdit ? '{{ url('admin/p5/tema') }}/' + modalData.id : '{{ route('admin.p5.tema.store') }}'" method="POST">
                        @csrf
                        <template x-if="isEdit">
                            <input type="hidden" name="_method" value="PUT">
                        </template>

                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <div class="sm:flex sm:items-start">
                                <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                    <h3 class="text-lg leading-6 font-medium text-gray-900 border-b pb-2" x-text="isEdit ? 'Edit Tema Kokurikuler' : 'Tambah Tema Kokurikuler'"></h3>
                                    <div class="mt-4 space-y-4">
                                        <div>
                                            <label for="nama_tema" class="block text-sm font-medium text-gray-700">Tema Kegiatan Kokurikuler <span class="text-red-500">*</span></label>
                                            <input type="text" name="nama_tema" id="nama_tema" x-model="modalData.nama_tema" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm" required>
                                        </div>
                                        <div>
                                            <label for="deskripsi" class="block text-sm font-medium text-gray-700">Deskripsi Singkat (Opsional)</label>
                                            <textarea name="deskripsi" id="deskripsi" x-model="modalData.deskripsi" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm"></textarea>
                                        </div>
                                        <div x-show="isEdit">
                                            <label for="is_aktif" class="block text-sm font-medium text-gray-700">Status <span class="text-red-500">*</span></label>
                                            <select name="is_aktif" id="is_aktif" x-model="modalData.is_aktif" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm">
                                                <option value="1">Aktif</option>
                                                <option value="0">Tidak Aktif</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                            <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                                Simpan Data
                            </button>
                            <button type="button" @click="showModal = false" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                                Batal
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
</x-app-layout>
