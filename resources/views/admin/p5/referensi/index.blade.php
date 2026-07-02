<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Data Referensi Profil Lulusan (P5)') }}
        </h2>
    </x-slot>

    <div class="py-6 space-y-6 max-w-7xl mx-auto sm:px-6 lg:px-8" x-data="referensiData()">
        
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
            <p><strong>Informasi:</strong> Kelola data referensi Dimensi, Elemen, dan Sub Elemen Profil Pelajar Pancasila. Klik pada Dimensi untuk melihat Elemen, dan klik Elemen untuk melihat Sub Elemen.</p>
            <button @click="openModal('dimensi')" class="bg-red-600 hover:bg-red-500 text-white text-xs py-2 px-4 rounded shadow font-semibold shrink-0">
                + Tambah Dimensi
            </button>
        </div>

        <div class="bg-white rounded-md shadow-md overflow-hidden">
            <div class="bg-[#8B1515] text-white px-6 py-4 font-bold tracking-wider uppercase text-sm border-b border-red-800">
                Master Data Profil Lulusan
            </div>
            
            <div class="p-0">
                @forelse($dimensis as $dimensi)
                    <!-- Accordion Dimensi -->
                    <div class="border-b border-gray-200" x-data="{ expandedDimensi: false }">
                        <div class="flex items-center justify-between px-6 py-4 bg-gray-50 hover:bg-gray-100 cursor-pointer" @click="expandedDimensi = !expandedDimensi">
                            <div class="flex items-center gap-3">
                                <svg :class="{'rotate-90': expandedDimensi}" class="w-5 h-5 text-gray-500 transition-transform duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                </svg>
                                <div>
                                    <div class="font-bold text-gray-800 text-base">{{ $dimensi->nama_dimensi }}</div>
                                    <div class="text-xs text-gray-500 mt-1">{{ $dimensi->deskripsi }}</div>
                                </div>
                            </div>
                            <div class="flex items-center gap-2" @click.stop>
                                <button @click="openModal('dimensi', {{ json_encode($dimensi) }})" class="text-blue-600 hover:text-blue-800 text-sm font-semibold">Edit</button>
                                <form action="{{ route('admin.p5.referensi.dimensi.destroy', $dimensi->id) }}" method="POST" class="inline" onsubmit="return confirm('Hapus Dimensi ini beserta semua Elemen & Sub Elemen di dalamnya?');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800 text-sm font-semibold">Hapus</button>
                                </form>
                            </div>
                        </div>

                        <!-- Konten Dimensi (Elemen) -->
                        <div x-show="expandedDimensi" x-cloak class="bg-white border-t border-gray-200">
                            <div class="px-6 py-3 bg-gray-100 text-sm font-semibold text-gray-700 flex justify-between items-center">
                                <span>Daftar Elemen</span>
                                <button @click="openModal('elemen', null, {{ $dimensi->id }})" class="text-xs bg-gray-600 hover:bg-gray-500 text-white px-3 py-1 rounded">
                                    + Tambah Elemen
                                </button>
                            </div>
                            
                            @forelse($dimensi->elemens as $elemen)
                                <!-- Accordion Elemen -->
                                <div class="border-b border-gray-100 last:border-b-0" x-data="{ expandedElemen: false }">
                                    <div class="flex items-center justify-between px-10 py-3 hover:bg-gray-50 cursor-pointer" @click="expandedElemen = !expandedElemen">
                                        <div class="flex items-center gap-3">
                                            <svg :class="{'rotate-90': expandedElemen}" class="w-4 h-4 text-gray-400 transition-transform duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                            </svg>
                                            <div class="font-semibold text-gray-700 text-sm">{{ $elemen->nama_elemen }}</div>
                                        </div>
                                        <div class="flex items-center gap-2" @click.stop>
                                            <button @click="openModal('elemen', {{ json_encode($elemen) }}, {{ $dimensi->id }})" class="text-blue-600 hover:text-blue-800 text-xs font-semibold">Edit</button>
                                            <form action="{{ route('admin.p5.referensi.elemen.destroy', $elemen->id) }}" method="POST" class="inline" onsubmit="return confirm('Hapus Elemen ini beserta semua Sub Elemen di dalamnya?');">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-800 text-xs font-semibold">Hapus</button>
                                            </form>
                                        </div>
                                    </div>

                                    <!-- Konten Elemen (Sub Elemen) -->
                                    <div x-show="expandedElemen" x-cloak class="bg-gray-50 border-t border-gray-100">
                                        <div class="px-14 py-2 bg-gray-200 text-xs font-semibold text-gray-600 flex justify-between items-center">
                                            <span>Daftar Sub Elemen & Capaian Fase</span>
                                            <button @click="openModal('sub_elemen', null, null, {{ $elemen->id }})" class="text-xs bg-indigo-600 hover:bg-indigo-500 text-white px-2 py-1 rounded">
                                                + Tambah Sub Elemen
                                            </button>
                                        </div>
                                        
                                        <div class="px-14 py-3 divide-y divide-gray-200">
                                            @forelse($elemen->subElemens as $sub)
                                                <div class="py-3 group relative">
                                                    <div class="flex justify-between items-start">
                                                        <div class="flex-1">
                                                            <div class="font-bold text-sm text-gray-800 mb-2">{{ $sub->nama_sub_elemen }}</div>
                                                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-xs text-gray-600">
                                                                <div class="bg-white p-2 rounded border shadow-sm">
                                                                    <strong class="block text-indigo-700 mb-1">Capaian Fase A:</strong>
                                                                    {{ $sub->capaian_fase_a ?: '-' }}
                                                                </div>
                                                                <div class="bg-white p-2 rounded border shadow-sm">
                                                                    <strong class="block text-indigo-700 mb-1">Capaian Fase B:</strong>
                                                                    {{ $sub->capaian_fase_b ?: '-' }}
                                                                </div>
                                                                <div class="bg-white p-2 rounded border shadow-sm">
                                                                    <strong class="block text-indigo-700 mb-1">Capaian Fase C:</strong>
                                                                    {{ $sub->capaian_fase_c ?: '-' }}
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="ml-4 flex flex-col gap-2">
                                                            <button @click="openModal('sub_elemen', {{ json_encode($sub) }}, null, {{ $elemen->id }})" class="text-blue-600 hover:text-blue-800 text-xs font-semibold">Edit</button>
                                                            <form action="{{ route('admin.p5.referensi.sub_elemen.destroy', $sub->id) }}" method="POST" onsubmit="return confirm('Hapus Sub Elemen ini?');">
                                                                @csrf @method('DELETE')
                                                                <button type="submit" class="text-red-600 hover:text-red-800 text-xs font-semibold">Hapus</button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            @empty
                                                <div class="py-4 text-center text-sm text-gray-500">Belum ada Sub Elemen.</div>
                                            @endforelse
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="px-10 py-4 text-sm text-gray-500 italic border-b border-gray-100">Belum ada Elemen di dimensi ini.</div>
                            @endforelse
                        </div>
                    </div>
                @empty
                    <div class="p-6 text-center text-gray-500">Belum ada data Dimensi.</div>
                @endforelse
            </div>
        </div>

        <!-- MODALS -->
        <div x-show="modal.show" x-cloak class="fixed z-50 inset-0 overflow-y-auto" style="display: none;">
            <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 transition-opacity" aria-hidden="true" @click="closeModal()">
                    <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
                </div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
                    
                    <form :action="getFormAction()" method="POST">
                        @csrf
                        <template x-if="modal.isEdit">
                            <input type="hidden" name="_method" value="PUT">
                        </template>

                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 border-b pb-2 mb-4" x-text="getModalTitle()"></h3>
                            
                            <!-- Form Dimensi -->
                            <template x-if="modal.type === 'dimensi'">
                                <div class="space-y-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Nama Dimensi <span class="text-red-500">*</span></label>
                                        <input type="text" name="nama_dimensi" x-model="formData.nama_dimensi" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm" required>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Deskripsi</label>
                                        <textarea name="deskripsi" x-model="formData.deskripsi" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm"></textarea>
                                    </div>
                                </div>
                            </template>

                            <!-- Form Elemen -->
                            <template x-if="modal.type === 'elemen'">
                                <div class="space-y-4">
                                    <input type="hidden" name="p5_dimensi_id" x-model="formData.p5_dimensi_id">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Nama Elemen <span class="text-red-500">*</span></label>
                                        <input type="text" name="nama_elemen" x-model="formData.nama_elemen" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm" required>
                                    </div>
                                </div>
                            </template>

                            <!-- Form Sub Elemen -->
                            <template x-if="modal.type === 'sub_elemen'">
                                <div class="space-y-4">
                                    <input type="hidden" name="p5_elemen_id" x-model="formData.p5_elemen_id">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Nama Sub Elemen <span class="text-red-500">*</span></label>
                                        <input type="text" name="nama_sub_elemen" x-model="formData.nama_sub_elemen" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm" required>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Capaian Fase A (Kelas 1-2)</label>
                                        <textarea name="capaian_fase_a" x-model="formData.capaian_fase_a" rows="2" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm"></textarea>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Capaian Fase B (Kelas 3-4)</label>
                                        <textarea name="capaian_fase_b" x-model="formData.capaian_fase_b" rows="2" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm"></textarea>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Capaian Fase C (Kelas 5-6)</label>
                                        <textarea name="capaian_fase_c" x-model="formData.capaian_fase_c" rows="2" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm"></textarea>
                                    </div>
                                </div>
                            </template>

                        </div>
                        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                            <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                                Simpan Data
                            </button>
                            <button type="button" @click="closeModal()" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                                Batal
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>

    <script>
        function referensiData() {
            return {
                modal: {
                    show: false,
                    type: '', // 'dimensi', 'elemen', 'sub_elemen'
                    isEdit: false,
                    id: null
                },
                formData: {},
                
                openModal(type, data = null, parentDimensiId = null, parentElemenId = null) {
                    this.modal.type = type;
                    this.modal.isEdit = !!data;
                    this.modal.id = data ? data.id : null;
                    
                    if (data) {
                        this.formData = { ...data };
                    } else {
                        this.formData = {};
                        if (type === 'elemen') this.formData.p5_dimensi_id = parentDimensiId;
                        if (type === 'sub_elemen') this.formData.p5_elemen_id = parentElemenId;
                    }
                    
                    this.modal.show = true;
                },
                
                closeModal() {
                    this.modal.show = false;
                },
                
                getModalTitle() {
                    const prefix = this.modal.isEdit ? 'Edit ' : 'Tambah ';
                    if (this.modal.type === 'dimensi') return prefix + 'Dimensi';
                    if (this.modal.type === 'elemen') return prefix + 'Elemen';
                    if (this.modal.type === 'sub_elemen') return prefix + 'Sub Elemen';
                    return '';
                },
                
                getFormAction() {
                    let base = '{{ url('admin/p5/referensi') }}/';
                    let typePath = this.modal.type.replace('_', '-'); // sub_elemen -> sub-elemen
                    
                    if (this.modal.isEdit) {
                        return base + typePath + '/' + this.modal.id;
                    } else {
                        return base + typePath;
                    }
                }
            }
        }
    </script>
</x-app-layout>
