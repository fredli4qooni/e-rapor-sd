<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Data Semester') }}
        </h2>
    </x-slot>

    <div class="py-6 space-y-6 max-w-7xl mx-auto sm:px-6 lg:px-8" x-data="semesterModal()">
        
        <!-- Flash Message -->
        @if (session('status'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative shadow-sm" role="alert">
                <span class="block sm:inline">{{ session('status') }}</span>
            </div>
        @endif
        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative shadow-sm" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif
        @if (session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative shadow-sm" role="alert">
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
        @endif

        <div class="bg-white rounded-md shadow-md overflow-hidden">
            <div class="bg-[#8B1515] text-white px-6 py-4 font-bold tracking-wider uppercase text-sm border-b border-red-800 flex justify-between items-center">
                <span>Daftar Semester</span>
                <button @click="openModal()" class="bg-red-600 hover:bg-red-500 text-white text-xs py-1.5 px-3 rounded shadow font-semibold">
                    + Tambah Semester
                </button>
            </div>
            
            <div class="p-6 overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-700 border">
                    <thead class="text-xs text-white bg-gray-800 uppercase border-b">
                        <tr>
                            <th class="px-4 py-3 w-16 text-center">No</th>
                            <th class="px-4 py-3">Tahun Ajaran</th>
                            <th class="px-4 py-3 text-center">Semester</th>
                            <th class="px-4 py-3">Kurikulum</th>
                            <th class="px-4 py-3 text-center">Status</th>
                            <th class="px-4 py-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($semesters as $index => $semester)
                            <tr class="border-b hover:bg-gray-50">
                                <td class="px-4 py-3 text-center">{{ $index + 1 }}</td>
                                <td class="px-4 py-3 font-semibold">{{ $semester->tahun_ajaran }}</td>
                                <td class="px-4 py-3 text-center">{{ $semester->semester == 1 ? 'Ganjil' : 'Genap' }}</td>
                                <td class="px-4 py-3">{{ $semester->kurikulum }}</td>
                                <td class="px-4 py-3 text-center">
                                    @if($semester->is_aktif)
                                        <span class="bg-green-100 text-green-800 text-xs font-semibold px-2.5 py-0.5 rounded border border-green-400">Aktif</span>
                                    @else
                                        <span class="bg-gray-100 text-gray-800 text-xs font-semibold px-2.5 py-0.5 rounded border border-gray-400">Nonaktif</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-center flex justify-center gap-2">
                                    @if(!$semester->is_aktif)
                                    <form action="{{ route('admin.semester.set_aktif', $semester->id) }}" method="POST" onsubmit="return confirm('Jadikan semester ini sebagai semester aktif untuk seluruh pengguna?');">
                                        @csrf
                                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white text-xs py-1 px-2 rounded font-medium shadow">Jadikan Aktif</button>
                                    </form>
                                    @endif
                                    
                                    <button @click="openModal({{ json_encode($semester) }})" class="bg-yellow-500 hover:bg-yellow-600 text-white text-xs py-1 px-2 rounded font-medium shadow">Edit</button>
                                    
                                    @if(!$semester->is_aktif)
                                    <form action="{{ route('admin.semester.destroy', $semester->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus semester ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="bg-red-600 hover:bg-red-700 text-white text-xs py-1 px-2 rounded font-medium shadow">Hapus</button>
                                    </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-3 text-center text-gray-500">Belum ada data semester.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Modal Formulir -->
        <div x-show="isOpen" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                
                <div x-show="isOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" @click="isOpen = false"></div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <div x-show="isOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    
                    <form :action="formAction" method="POST">
                        @csrf
                        <input type="hidden" name="_method" :value="isEdit ? 'PUT' : 'POST'">
                        
                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4" id="modal-title" x-text="isEdit ? 'Edit Semester' : 'Tambah Semester Baru'"></h3>
                            
                            <!-- Menampilkan pesan error validasi (jika ada) -->
                            @if ($errors->any())
                                <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative text-sm">
                                    <ul class="list-disc pl-5">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Tahun Ajaran</label>
                                    <input type="text" name="tahun_ajaran" x-model="formData.tahun_ajaran" required placeholder="Contoh: 2024/2025" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm">
                                    <p class="text-xs text-gray-500 mt-1">Gunakan format YYYY/YYYY.</p>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Semester</label>
                                    <select name="semester" x-model="formData.semester" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm">
                                        <option value="1">1 (Ganjil)</option>
                                        <option value="2">2 (Genap)</option>
                                    </select>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Kurikulum</label>
                                    <input type="text" name="kurikulum" x-model="formData.kurikulum" required placeholder="Contoh: Merdeka" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm">
                                </div>

                                <div x-show="!isEdit" class="flex items-center">
                                    <input id="is_aktif" name="is_aktif" type="checkbox" value="1" class="h-4 w-4 text-red-600 focus:ring-red-500 border-gray-300 rounded">
                                    <label for="is_aktif" class="ml-2 block text-sm text-gray-900">
                                        Langsung Jadikan Semester Aktif
                                    </label>
                                </div>
                            </div>
                        </div>
                        
                        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                            <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">
                                Simpan
                            </button>
                            <button type="button" @click="isOpen = false" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                                Batal
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>

    <script>
        function semesterModal() {
            return {
                isOpen: {{ $errors->any() ? 'true' : 'false' }},
                isEdit: false,
                formAction: '{{ route('admin.semester.store') }}',
                formData: {
                    tahun_ajaran: '{{ old('tahun_ajaran') ?? '' }}',
                    semester: '{{ old('semester') ?? '1' }}',
                    kurikulum: '{{ old('kurikulum') ?? 'Merdeka' }}'
                },
                openModal(semester = null) {
                    if (semester) {
                        this.isEdit = true;
                        this.formAction = `/admin/semester/${semester.id}`;
                        this.formData.tahun_ajaran = semester.tahun_ajaran;
                        this.formData.semester = semester.semester;
                        this.formData.kurikulum = semester.kurikulum;
                    } else {
                        this.isEdit = false;
                        this.formAction = '{{ route('admin.semester.store') }}';
                        this.formData.tahun_ajaran = '';
                        this.formData.semester = '1';
                        this.formData.kurikulum = 'Merdeka';
                    }
                    this.isOpen = true;
                }
            }
        }
    </script>
</x-app-layout>
