<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Monitoring Kinerja & Progres Penilaian Guru') }} | {{ $semester_teks ?? '2025/2026 Ganjil' }}
        </h2>
    </x-slot>

    <div class="py-6 space-y-6 max-w-7xl mx-auto sm:px-6 lg:px-8">
        
        <!-- Header Banner / Summary Info -->
        <div class="bg-gradient-to-r from-[#8B1515] to-[#a31c1c] rounded-md text-white p-5 shadow-md flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h3 class="font-bold text-lg mb-1 flex items-center gap-2">
                    <svg class="w-5 h-5 text-yellow-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Pemantauan Kelengkapan Penilaian Rapor Guru
                </h3>
                <p class="text-sm text-red-100">
                    Memantau progres pengisian Nilai Rapor dan Deskripsi Capaian Kompetensi oleh seluruh Guru Mata Pelajaran & Wali Kelas.
                </p>
            </div>
            <div class="bg-black/25 backdrop-blur px-4 py-2 rounded-lg border border-white/20 text-center flex-shrink-0">
                <span class="text-xs uppercase tracking-wider block text-red-200">Kesiapan Rapor Sekolah</span>
                <span class="text-2xl font-black text-yellow-300">{{ $rekapKinerja['persen_global'] ?? 0 }}%</span>
            </div>
        </div>

        <!-- Metric Stat Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Total Pembelajaran -->
            <div class="bg-white p-4 rounded-md shadow-md border-l-4 border-blue-600 flex justify-between items-center">
                <div>
                    <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Total Pembelajaran</span>
                    <div class="text-2xl font-bold text-gray-800 mt-1">{{ $rekapKinerja['total_pembelajaran'] ?? 0 }}</div>
                    <span class="text-xs text-gray-400">Mapel di seluruh rombel</span>
                </div>
                <div class="p-3 bg-blue-50 rounded-full text-blue-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                </div>
            </div>

            <!-- Tuntas -->
            <a href="{{ route('kepsek.monitoring.kinerja_guru', array_merge(request()->query(), ['status' => 'tuntas'])) }}" class="bg-white p-4 rounded-md shadow-md border-l-4 border-green-500 hover:shadow-lg transition-all block {{ request('status') === 'tuntas' ? 'ring-2 ring-green-500' : '' }}">
                <div class="flex justify-between items-center">
                    <div>
                        <span class="text-xs font-semibold text-green-700 uppercase tracking-wider">Penilaian Tuntas</span>
                        <div class="text-2xl font-bold text-green-600 mt-1">{{ $rekapKinerja['tuntas'] ?? 0 }}</div>
                        <span class="text-xs text-green-500">Nilai & Deskripsi 100%</span>
                    </div>
                    <div class="p-3 bg-green-50 rounded-full text-green-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                </div>
            </a>

            <!-- Sebagian -->
            <a href="{{ route('kepsek.monitoring.kinerja_guru', array_merge(request()->query(), ['status' => 'sebagian'])) }}" class="bg-white p-4 rounded-md shadow-md border-l-4 border-yellow-500 hover:shadow-lg transition-all block {{ request('status') === 'sebagian' ? 'ring-2 ring-yellow-500' : '' }}">
                <div class="flex justify-between items-center">
                    <div>
                        <span class="text-xs font-semibold text-yellow-700 uppercase tracking-wider">Sedang Mengisi</span>
                        <div class="text-2xl font-bold text-yellow-600 mt-1">{{ $rekapKinerja['sebagian'] ?? 0 }}</div>
                        <span class="text-xs text-yellow-500">Belum lengkap semua siswa</span>
                    </div>
                    <div class="p-3 bg-yellow-50 rounded-full text-yellow-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                </div>
            </a>

            <!-- Belum Mengisi -->
            <a href="{{ route('kepsek.monitoring.kinerja_guru', array_merge(request()->query(), ['status' => 'belum'])) }}" class="bg-white p-4 rounded-md shadow-md border-l-4 border-red-500 hover:shadow-lg transition-all block {{ request('status') === 'belum' ? 'ring-2 ring-red-500' : '' }}">
                <div class="flex justify-between items-center">
                    <div>
                        <span class="text-xs font-semibold text-red-700 uppercase tracking-wider">Belum Mengisi</span>
                        <div class="text-2xl font-bold text-red-600 mt-1">{{ $rekapKinerja['belum'] ?? 0 }}</div>
                        <span class="text-xs text-red-500">0% pengisian nilai</span>
                    </div>
                    <div class="p-3 bg-red-50 rounded-full text-red-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    </div>
                </div>
            </a>
        </div>

        <!-- Filter & Search Section -->
        <div class="bg-white rounded-md shadow-md p-4">
            <form method="GET" action="{{ route('kepsek.monitoring.kinerja_guru') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <!-- Status Tabs / Dropdown -->
                <div>
                    <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">Status Progres</label>
                    <select name="status" class="w-full text-sm border-gray-300 rounded-md shadow-sm focus:border-red-500 focus:ring-red-500">
                        <option value="">-- Semua Status --</option>
                        <option value="tuntas" {{ request('status') == 'tuntas' ? 'selected' : '' }}>Tuntas (100%)</option>
                        <option value="sebagian" {{ request('status') == 'sebagian' ? 'selected' : '' }}>Sebagian (Sedang Mengisi)</option>
                        <option value="belum" {{ request('status') == 'belum' ? 'selected' : '' }}>Belum Mengisi (0%)</option>
                    </select>
                </div>

                <!-- Rombel Filter -->
                <div>
                    <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">Kelas / Rombel</label>
                    <select name="rombel_id" class="w-full text-sm border-gray-300 rounded-md shadow-sm focus:border-red-500 focus:ring-red-500">
                        <option value="">-- Semua Rombel --</option>
                        @foreach($filterRombels as $r)
                            <option value="{{ $r->id }}" {{ $selected_rombel_id == $r->id ? 'selected' : '' }}>{{ $r->nama_rombel }} (Tingkat {{ $r->tingkat }})</option>
                        @endforeach
                    </select>
                </div>

                <!-- Guru Filter -->
                <div>
                    <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">Guru Pengampu</label>
                    <select name="guru_id" class="w-full text-sm border-gray-300 rounded-md shadow-sm focus:border-red-500 focus:ring-red-500">
                        <option value="">-- Semua Guru --</option>
                        @foreach($filterGurus as $g)
                            <option value="{{ $g->id }}" {{ $selected_guru_id == $g->id ? 'selected' : '' }}>{{ $g->nama_lengkap }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Action Buttons & Search -->
                <div class="flex items-end gap-2">
                    <button type="submit" class="flex-1 bg-[#8B1515] hover:bg-red-800 text-white font-semibold py-2 px-4 rounded-md text-sm transition-colors shadow-sm flex items-center justify-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                        Terapkan Filter
                    </button>
                    @if(request()->hasAny(['status', 'rombel_id', 'guru_id', 'search']))
                        <a href="{{ route('kepsek.monitoring.kinerja_guru') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 py-2 px-3 rounded-md text-sm transition-colors" title="Reset Filter">
                            Reset
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- Tabel Detail Progres Kinerja Guru -->
        <div class="bg-white rounded-md shadow-md overflow-hidden">
            <div class="bg-[#8B1515] text-white px-6 py-4 font-bold tracking-wider uppercase text-sm border-b border-red-800 flex justify-between items-center">
                <span>Daftar Progres Penilaian per Mata Pelajaran & Kelas</span>
                <span class="text-xs bg-red-900/80 px-3 py-1 rounded-full text-red-100 font-normal">Total: {{ $progresKinerja->count() }} Data</span>
            </div>
            
            <div class="p-6 overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-700 border">
                    <thead class="text-xs text-white bg-gray-800 uppercase border-b">
                        <tr>
                            <th class="px-3 py-3 w-12 text-center">No</th>
                            <th class="px-4 py-3">Guru Pengampu</th>
                            <th class="px-4 py-3">Mata Pelajaran</th>
                            <th class="px-4 py-3 text-center">Kelas</th>
                            <th class="px-4 py-3 text-center">Jml Siswa</th>
                            <th class="px-4 py-3 w-48">Progres Nilai Rapor</th>
                            <th class="px-4 py-3 w-48">Progres Deskripsi</th>
                            <th class="px-4 py-3 text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($progresKinerja as $index => $item)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-3 py-3 text-center text-gray-500 font-medium">{{ $loop->iteration }}</td>
                                <td class="px-4 py-3">
                                    <div class="font-semibold text-gray-900">
                                        {{ $item['guru']->nama_lengkap ?? '-' }}
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        NIP: {{ $item['guru']->nip ?? '-' }}
                                    </div>
                                </td>
                                <td class="px-4 py-3 font-medium text-gray-800">
                                    {{ $item['mapel']->nama_mapel ?? '-' }}
                                </td>
                                <td class="px-4 py-3 text-center font-bold text-gray-700">
                                    <span class="inline-block bg-gray-100 px-2.5 py-1 rounded border border-gray-200">
                                        {{ $item['rombel']->nama_rombel ?? '-' }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-center font-semibold text-gray-800">
                                    {{ $item['total_siswa'] }}
                                </td>
                                
                                <!-- Progres Nilai Rapor -->
                                <td class="px-4 py-3">
                                    <div class="flex justify-between items-center text-xs mb-1">
                                        <span class="font-semibold {{ $item['persen_nilai'] == 100 ? 'text-green-600' : ($item['persen_nilai'] > 0 ? 'text-yellow-600' : 'text-gray-400') }}">
                                            {{ $item['terisi_nilai'] }}/{{ $item['total_siswa'] }} Siswa
                                        </span>
                                        <span class="font-bold {{ $item['persen_nilai'] == 100 ? 'text-green-700' : ($item['persen_nilai'] > 0 ? 'text-yellow-700' : 'text-gray-500') }}">
                                            {{ $item['persen_nilai'] }}%
                                        </span>
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-2.5 overflow-hidden">
                                        <div class="h-2.5 rounded-full transition-all duration-300 {{ $item['persen_nilai'] == 100 ? 'bg-green-500' : ($item['persen_nilai'] > 0 ? 'bg-yellow-500' : 'bg-transparent') }}" 
                                             style="width: {{ $item['persen_nilai'] }}%"></div>
                                    </div>
                                </td>

                                <!-- Progres Deskripsi Rapor -->
                                <td class="px-4 py-3">
                                    <div class="flex justify-between items-center text-xs mb-1">
                                        <span class="font-semibold {{ $item['persen_deskripsi'] == 100 ? 'text-green-600' : ($item['persen_deskripsi'] > 0 ? 'text-yellow-600' : 'text-gray-400') }}">
                                            {{ $item['terisi_deskripsi'] }}/{{ $item['total_siswa'] }} Siswa
                                        </span>
                                        <span class="font-bold {{ $item['persen_deskripsi'] == 100 ? 'text-green-700' : ($item['persen_deskripsi'] > 0 ? 'text-yellow-700' : 'text-gray-500') }}">
                                            {{ $item['persen_deskripsi'] }}%
                                        </span>
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-2.5 overflow-hidden">
                                        <div class="h-2.5 rounded-full transition-all duration-300 {{ $item['persen_deskripsi'] == 100 ? 'bg-green-500' : ($item['persen_deskripsi'] > 0 ? 'bg-yellow-500' : 'bg-transparent') }}" 
                                             style="width: {{ $item['persen_deskripsi'] }}%"></div>
                                    </div>
                                </td>

                                <!-- Status Badge -->
                                <td class="px-4 py-3 text-center">
                                    @if($item['status'] === 'Tuntas')
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-green-100 text-green-800 border border-green-200">
                                            <svg class="w-3 h-3 mr-1 text-green-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                                            Tuntas
                                        </span>
                                    @elseif($item['status'] === 'Sebagian')
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-yellow-100 text-yellow-800 border border-yellow-200">
                                            <svg class="w-3 h-3 mr-1 text-yellow-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path></svg>
                                            Sebagian
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-red-100 text-red-800 border border-red-200">
                                            <svg class="w-3 h-3 mr-1 text-red-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path></svg>
                                            Belum Mengisi
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-4 py-8 text-center text-gray-500">
                                    <svg class="w-12 h-12 mx-auto text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    Tidak ada data pembelajaran atau guru yang sesuai dengan filter yang dipilih.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</x-app-layout>
