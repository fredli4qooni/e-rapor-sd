<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight uppercase">
            {{ __('Input Nilai Projek') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            @if(session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded shadow-sm" role="alert">
                <p class="font-bold">Berhasil</p>
                <p>{{ session('success') }}</p>
            </div>
            @endif

            <!-- Filter Section Card -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6 border-t-4 border-red-800">
                <div class="p-6">
                    <form method="GET" action="{{ route('guru.nilai_p5.input_capaian') }}" class="space-y-4">
                        <div class="flex items-center gap-4">
                            <label class="w-1/4 text-sm font-bold text-gray-700 whitespace-nowrap">Pilih Kelompok</label>
                            <select name="kelompok_id" onchange="this.form.submit()" class="block w-3/4 rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm bg-gray-50">
                                <option value="">Pilih Kelompok</option>
                                @foreach($kelompoks as $k)
                                    <option value="{{ $k->id }}" {{ $kelompok_id == $k->id ? 'selected' : '' }}>
                                        {{ $k->nama_kelompok }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="flex items-center gap-4">
                            <label class="w-1/4 text-sm font-bold text-gray-700 whitespace-nowrap">Pilih Projek</label>
                            <select name="proyek_id" onchange="this.form.submit()" class="block w-3/4 rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm bg-gray-50" {{ !$kelompok_id ? 'disabled' : '' }}>
                                <option value="">Pilih Data Projek</option>
                                @foreach($proyeks as $p)
                                    <option value="{{ $p->id }}" {{ $proyek_id == $p->id ? 'selected' : '' }}>
                                        {{ $p->nama_proyek }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="flex items-center gap-4">
                            <label class="w-1/4 text-sm font-bold text-gray-700 whitespace-nowrap">Pilih Dimensi Profil</label>
                            <select name="dimensi_id" onchange="this.form.submit()" class="block w-3/4 rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm bg-gray-50" {{ !$proyek_id ? 'disabled' : '' }}>
                                <option value="">Pilih Dimensi Profil</option>
                                @foreach($dimensis as $d)
                                    <option value="{{ $d->id }}" {{ $dimensi_id == $d->id ? 'selected' : '' }}>
                                        {{ $d->nama_dimensi }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="flex items-center gap-4">
                            <label class="w-1/4 text-sm font-bold text-gray-700 whitespace-nowrap">Pilih Elemen Profil</label>
                            <select name="elemen_id" onchange="this.form.submit()" class="block w-3/4 rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm bg-gray-50" {{ !$dimensi_id ? 'disabled' : '' }}>
                                <option value="">Pilih Elemen Profil</option>
                                @foreach($elemens as $e)
                                    <option value="{{ $e->id }}" {{ $elemen_id == $e->id ? 'selected' : '' }}>
                                        {{ $e->nama_elemen }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="flex items-center gap-4">
                            <label class="w-1/4 text-sm font-bold text-gray-700 whitespace-nowrap">Pilih Sub Elemen Profil</label>
                            <select name="sub_elemen_id" onchange="this.form.submit()" class="block w-3/4 rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm bg-gray-50" {{ !$elemen_id ? 'disabled' : '' }}>
                                <option value="">Pilih Sub Elemen Profil</option>
                                @foreach($sub_elemens as $se)
                                    <option value="{{ $se->id }}" {{ $sub_elemen_id == $se->id ? 'selected' : '' }}>
                                        {{ $se->nama_sub_elemen }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </form>
                </div>
            </div>

            @if($sub_elemen_id)
            @php 
                $selected_sub_elemen = $sub_elemens->firstWhere('id', $sub_elemen_id); 
                $kelompok_selected = $kelompoks->firstWhere('id', $kelompok_id);
                $fase = $kelompok_selected ? $kelompok_selected->fase : 'B';
                $capaian_fase = '';
                if ($fase == 'A') $capaian_fase = $selected_sub_elemen->capaian_fase_a;
                elseif ($fase == 'B') $capaian_fase = $selected_sub_elemen->capaian_fase_b;
                elseif ($fase == 'C') $capaian_fase = $selected_sub_elemen->capaian_fase_c;
                else $capaian_fase = $selected_sub_elemen->capaian_fase_b; // fallback
            @endphp
            
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-bold text-gray-800 mb-2">Capaian Akhir Fase {{ $fase }}:</h3>
                    <p class="text-sm text-gray-600 mb-6">{{ $capaian_fase }}</p>

                    <form action="{{ route('guru.nilai_p5.store_capaian') }}" method="POST">
                        @csrf
                        <input type="hidden" name="proyek_id" value="{{ $proyek_id }}">
                        <input type="hidden" name="sub_elemen_id" value="{{ $sub_elemen_id }}">

                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 border-t border-gray-200">
                                <thead class="bg-red-800 text-white">
                                    <tr>
                                        <th scope="col" class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wider w-12 border-r border-red-900">No</th>
                                        <th scope="col" class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wider w-64 border-r border-red-900">Nama Siswa</th>
                                        <th scope="col" class="px-4 py-3 text-center text-xs font-bold uppercase tracking-wider w-24 border-r border-red-900">NISN</th>
                                        <th scope="col" class="px-4 py-3 text-center text-xs font-bold uppercase tracking-wider w-24 border-r border-red-900">NIS</th>
                                        <th scope="col" class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wider">Predikat</th>
                                    </tr>
                                    <tr class="bg-red-900 text-white">
                                        <th class="px-4 py-2 border-r border-red-800">#</th>
                                        <th colspan="3" class="px-4 py-2 text-left text-xs font-bold border-r border-red-800">Terapkan nilai ke semua siswa :</th>
                                        <th class="px-4 py-2 text-left">
                                            <select id="bulk_predikat" onchange="applyBulkPredikat(this.value)" class="block w-full rounded border-white text-gray-800 shadow-sm focus:border-red-500 focus:ring-red-500 text-xs py-1.5 px-2 bg-white font-semibold">
                                                <option value="">-- Pilih --</option>
                                                <option value="SAB">Sangat Berkembang</option>
                                                <option value="BSH">Berkembang Sesuai Harapan</option>
                                                <option value="SB">Sedang Berkembang</option>
                                                <option value="MB">Mulai Berkembang</option>
                                            </select>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @forelse($siswas as $index => $siswa)
                                        @php
                                            $nilai_capaian = isset($nilais[$siswa->id]) ? $nilais[$siswa->id]->capaian : '';
                                        @endphp
                                        <tr class="hover:bg-red-50/50 transition-colors">
                                            <td class="px-4 py-3 text-sm text-gray-500 font-medium border-r border-gray-200">{{ $index + 1 }}</td>
                                            <td class="px-4 py-3 text-sm font-bold text-gray-700 border-r border-gray-200 uppercase">{{ $siswa->nama_lengkap }}</td>
                                            <td class="px-4 py-3 text-center text-sm text-gray-700 border-r border-gray-200">{{ $siswa->nisn }}</td>
                                            <td class="px-4 py-3 text-center text-sm text-gray-700 border-r border-gray-200">{{ $siswa->nis }}</td>
                                            <td class="px-4 py-3 border-r border-gray-200">
                                                <select name="nilai[{{ $siswa->id }}]" class="capaian-select block w-full rounded border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 text-sm py-1.5 px-3 bg-gray-50">
                                                    <option value="">Pilih</option>
                                                    <option value="SAB" {{ $nilai_capaian == 'SAB' ? 'selected' : '' }}>Sangat Berkembang</option>
                                                    <option value="BSH" {{ $nilai_capaian == 'BSH' ? 'selected' : '' }}>Berkembang Sesuai Harapan</option>
                                                    <option value="SB" {{ $nilai_capaian == 'SB' ? 'selected' : '' }}>Sedang Berkembang</option>
                                                    <option value="MB" {{ $nilai_capaian == 'MB' ? 'selected' : '' }}>Mulai Berkembang</option>
                                                </select>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="px-6 py-8 whitespace-nowrap text-sm text-gray-500 text-center">
                                                Tidak ada data siswa pada kelompok ini.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        
                        @if($siswas->count() > 0)
                        <div class="mt-4 flex justify-end">
                            <button type="submit" class="bg-gray-800 hover:bg-gray-700 text-white font-bold py-2 px-6 rounded shadow transition-colors inline-flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
                                Simpan Data
                            </button>
                        </div>
                        @endif
                    </form>
                </div>
            </div>
            @endif

        </div>
    </div>

    <script>
        function applyBulkPredikat(val) {
            if(!val) return;
            const selects = document.querySelectorAll('.capaian-select');
            selects.forEach(select => {
                select.value = val;
            });
        }
    </script>
</x-app-layout>
