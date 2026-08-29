<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <h2 class="font-semibold text-xl text-white leading-tight uppercase tracking-wider">
                {{ __('Presensi Harian Siswa') }}
            </h2>
            <div class="text-xs text-red-100 bg-red-950/40 px-3 py-1.5 rounded-md border border-red-800">
                Kelas: <span class="font-bold text-white">{{ $rombel->nama_rombel }}</span> | Semester: <span class="font-bold text-white">{{ $semesterAktif->nama_semester }}</span>
            </div>
        </div>
    </x-slot>

    <div class="py-8 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Flash Alert Messages -->
            @if(session('success'))
                <div class="bg-emerald-50 border-l-4 border-emerald-500 p-4 rounded-md shadow-sm">
                    <div class="flex items-center">
                        <svg class="h-5 w-5 text-emerald-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        <p class="text-sm font-medium text-emerald-800">{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            <!-- Filter Tanggal & Action Bar -->
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
                <form method="GET" action="{{ route('walikelas.presensi.index') }}" class="flex flex-col sm:flex-row items-start sm:items-end justify-between gap-4">
                    <div class="flex flex-col sm:flex-row items-start sm:items-center gap-3 w-full sm:w-auto">
                        <div>
                            <label for="tanggal" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">
                                Pilih Tanggal Presensi:
                            </label>
                            <input type="date" id="tanggal" name="tanggal" value="{{ $tanggal }}" max="{{ date('Y-m-d') }}"
                                   class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-red-500 focus:border-red-500 block px-3 py-2">
                        </div>
                        <button type="submit" class="mt-auto bg-red-800 hover:bg-red-700 text-white text-sm font-semibold px-4 py-2 rounded-lg transition-colors shadow-sm">
                            Tampilkan
                        </button>
                    </div>

                    <!-- Quick Set Button -->
                    <button type="button" onclick="setSemuaHadir()" class="bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold px-4 py-2 rounded-lg transition-colors shadow-sm flex items-center gap-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Set Semua Hadir (H)
                    </button>
                </form>
            </div>

            <!-- Ringkasan Statistik Hari Ini -->
            <div class="grid grid-cols-2 sm:grid-cols-5 gap-3">
                <div class="bg-white p-4 rounded-xl shadow-sm border border-emerald-100 border-l-4 border-l-emerald-500 text-center">
                    <span class="text-xs font-bold text-emerald-800 uppercase tracking-wider block">Hadir</span>
                    <span class="text-2xl font-black text-emerald-600">{{ $countHadir }}</span>
                </div>
                <div class="bg-white p-4 rounded-xl shadow-sm border border-blue-100 border-l-4 border-l-blue-500 text-center">
                    <span class="text-xs font-bold text-blue-800 uppercase tracking-wider block">Sakit</span>
                    <span class="text-2xl font-black text-blue-600">{{ $countSakit }}</span>
                </div>
                <div class="bg-white p-4 rounded-xl shadow-sm border border-amber-100 border-l-4 border-l-amber-500 text-center">
                    <span class="text-xs font-bold text-amber-800 uppercase tracking-wider block">Izin</span>
                    <span class="text-2xl font-black text-amber-600">{{ $countIzin }}</span>
                </div>
                <div class="bg-white p-4 rounded-xl shadow-sm border border-red-100 border-l-4 border-l-red-500 text-center">
                    <span class="text-xs font-bold text-red-800 uppercase tracking-wider block">Alpa</span>
                    <span class="text-2xl font-black text-red-600">{{ $countAlpa }}</span>
                </div>
                <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-200 border-l-4 border-l-gray-400 text-center col-span-2 sm:col-span-1">
                    <span class="text-xs font-bold text-gray-600 uppercase tracking-wider block">Belum Diabsen</span>
                    <span class="text-2xl font-black text-gray-500">{{ $countBelum }}</span>
                </div>
            </div>

            <!-- Form Presensi Siswa -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-200">
                <div class="bg-red-900 px-6 py-4 flex items-center justify-between">
                    <h3 class="text-base font-bold text-white tracking-wide">
                        Daftar Kehadiran Siswa Tanggal: <span class="text-yellow-300 font-extrabold">{{ \Carbon\Carbon::parse($tanggal)->translatedFormat('l, d F Y') }}</span>
                    </h3>
                    <span class="text-xs text-red-200">Total: {{ $siswas->count() }} Siswa</span>
                </div>

                <form method="POST" action="{{ route('walikelas.presensi.store') }}">
                    @csrf
                    <input type="hidden" name="rombel_id" value="{{ $rombel->id }}">
                    <input type="hidden" name="tanggal" value="{{ $tanggal }}">

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 text-sm">
                            <thead class="bg-gray-100 text-gray-700">
                                <tr>
                                    <th scope="col" class="px-4 py-3 text-center text-xs font-bold uppercase w-12 border-r border-gray-200">No</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-bold uppercase border-r border-gray-200">Nama Siswa</th>
                                    <th scope="col" class="px-4 py-3 text-center text-xs font-bold uppercase w-28 border-r border-gray-200">NISN</th>
                                    <th scope="col" class="px-4 py-3 text-center text-xs font-bold uppercase w-64 border-r border-gray-200">Status Kehadiran</th>
                                    <th scope="col" class="px-4 py-3 text-left text-xs font-bold uppercase w-64">Keterangan (Opsional)</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($siswas as $idx => $siswa)
                                    @php
                                        $presensi = $presensis[$siswa->id] ?? null;
                                        $currentStatus = $presensi ? $presensi->status : 'H';
                                    @endphp
                                    <tr class="hover:bg-red-50/30 transition-colors">
                                        <td class="px-4 py-3 text-center text-gray-500 font-medium border-r border-gray-200">{{ $idx + 1 }}</td>
                                        <td class="px-6 py-3 font-semibold text-gray-900 border-r border-gray-200">
                                            {{ $siswa->nama_lengkap }}
                                        </td>
                                        <td class="px-4 py-3 text-center text-gray-500 font-mono text-xs border-r border-gray-200">
                                            {{ $siswa->nisn ?? '-' }}
                                        </td>
                                        <td class="px-4 py-3 border-r border-gray-200">
                                            <div class="flex items-center justify-center gap-2 sm:gap-3">
                                                <!-- Radio Hadir -->
                                                <label class="flex items-center gap-1 cursor-pointer bg-emerald-50 hover:bg-emerald-100 px-2.5 py-1 rounded-md border border-emerald-200 transition-colors">
                                                    <input type="radio" name="presensi[{{ $siswa->id }}][status]" value="H" class="radio-status radio-h text-emerald-600 focus:ring-emerald-500 w-4 h-4" {{ $currentStatus == 'H' ? 'checked' : '' }}>
                                                    <span class="text-xs font-bold text-emerald-800">Hadir</span>
                                                </label>

                                                <!-- Radio Sakit -->
                                                <label class="flex items-center gap-1 cursor-pointer bg-blue-50 hover:bg-blue-100 px-2.5 py-1 rounded-md border border-blue-200 transition-colors">
                                                    <input type="radio" name="presensi[{{ $siswa->id }}][status]" value="S" class="radio-status radio-s text-blue-600 focus:ring-blue-500 w-4 h-4" {{ $currentStatus == 'S' ? 'checked' : '' }}>
                                                    <span class="text-xs font-bold text-blue-800">Sakit</span>
                                                </label>

                                                <!-- Radio Izin -->
                                                <label class="flex items-center gap-1 cursor-pointer bg-amber-50 hover:bg-amber-100 px-2.5 py-1 rounded-md border border-amber-200 transition-colors">
                                                    <input type="radio" name="presensi[{{ $siswa->id }}][status]" value="I" class="radio-status radio-i text-amber-600 focus:ring-amber-500 w-4 h-4" {{ $currentStatus == 'I' ? 'checked' : '' }}>
                                                    <span class="text-xs font-bold text-amber-800">Izin</span>
                                                </label>

                                                <!-- Radio Alpa -->
                                                <label class="flex items-center gap-1 cursor-pointer bg-red-50 hover:bg-red-100 px-2.5 py-1 rounded-md border border-red-200 transition-colors">
                                                    <input type="radio" name="presensi[{{ $siswa->id }}][status]" value="A" class="radio-status radio-a text-red-600 focus:ring-red-500 w-4 h-4" {{ $currentStatus == 'A' ? 'checked' : '' }}>
                                                    <span class="text-xs font-bold text-red-800">Alpa</span>
                                                </label>
                                            </div>
                                        </td>
                                        <td class="px-4 py-3">
                                            <input type="text" name="presensi[{{ $siswa->id }}][keterangan]" value="{{ $presensi->keterangan ?? '' }}"
                                                   placeholder="Catatan surat sakit / alasan izin..."
                                                   class="w-full text-xs rounded-lg border-gray-300 focus:ring-red-500 focus:border-red-500 py-1.5 px-3">
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-8 text-gray-500">
                                            Belum ada siswa yang terdaftar di kelas ini.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="p-6 bg-gray-50 border-t border-gray-200 flex justify-end">
                        <button type="submit" class="bg-red-800 hover:bg-red-700 text-white font-bold py-2.5 px-8 rounded-xl shadow-md transition-all flex items-center gap-2 text-sm">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Simpan Presensi Harian
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>

    <script>
        function setSemuaHadir() {
            document.querySelectorAll('.radio-h').forEach(function(radio) {
                radio.checked = true;
            });
        }
    </script>
</x-app-layout>
