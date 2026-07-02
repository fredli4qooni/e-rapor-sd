<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight uppercase">
            {{ __('Input Kenaikan Kelas') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if(!$isGenap)
                <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 mb-4" role="alert">
                    <p class="font-bold">Informasi</p>
                    <p>Input Kenaikan Kelas biasanya hanya dilakukan pada akhir <strong>Semester Genap</strong>. Saat ini bukan semester genap, namun Anda tetap dapat melihat form ini jika diperlukan.</p>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-t-4 border-red-800">
                <div class="p-6">
                    <div class="mb-6 p-4 border border-gray-200 rounded-md bg-white flex items-center">
                        <label class="w-48 font-bold text-gray-700 text-sm">Pilih Kelas</label>
                        <input type="text" value="{{ $rombel->tingkat . ' ' . strtoupper($rombel->nama_rombel) }}" class="flex-1 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-md block p-2" readonly>
                    </div>

                    <form method="POST" action="{{ route('walikelas.kenaikan.store') }}">
                        @csrf
                        <input type="hidden" name="rombel_id" value="{{ $rombel->id }}">

                        <div class="overflow-x-auto mt-4">
                            <table class="min-w-full divide-y divide-gray-200 border border-gray-300">
                                <thead class="bg-red-900 text-white">
                                    <tr>
                                        <th scope="col" class="px-4 py-3 text-center text-xs font-bold tracking-wider w-16 border border-red-800">No</th>
                                        <th scope="col" class="px-4 py-3 text-left text-xs font-bold tracking-wider border border-red-800">Nama Siswa</th>
                                        <th scope="col" class="px-4 py-3 text-center text-xs font-bold tracking-wider border border-red-800 w-24">NISN</th>
                                        <th scope="col" class="px-4 py-3 text-center text-xs font-bold tracking-wider border border-red-800 w-24">NIS</th>
                                        <th scope="col" class="px-4 py-3 text-center text-xs font-bold tracking-wider border border-red-800 w-64">STATUS KENAIKAN</th>
                                        <th scope="col" class="px-4 py-3 text-center text-xs font-bold tracking-wider border border-red-800 w-48">KELAS</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @forelse($siswas as $index => $siswa)
                                        @php
                                            $kenaikan = $kenaikans[$siswa->id] ?? null;
                                            $status_kenaikan = $kenaikan ? $kenaikan->status_kenaikan : '';
                                            $status_val = '';
                                            $kelas_val = '';
                                            if (str_starts_with($status_kenaikan, 'Naik ke Kelas ')) {
                                                $status_val = 'Naik ke Kelas';
                                                $kelas_val = str_replace('Naik ke Kelas ', '', $status_kenaikan);
                                            } elseif (str_starts_with($status_kenaikan, 'Tinggal di Kelas ')) {
                                                $status_val = 'Tinggal di Kelas';
                                                $kelas_val = str_replace('Tinggal di Kelas ', '', $status_kenaikan);
                                            } elseif ($status_kenaikan == 'Lulus' || $status_kenaikan == 'Tidak Lulus') {
                                                $status_val = $status_kenaikan;
                                            }
                                        @endphp
                                        <tr class="hover:bg-red-50/50 transition-colors {{ $index % 2 == 0 ? 'bg-red-50/20' : 'bg-white' }}">
                                            <td class="px-4 py-3 text-sm text-center text-gray-700 border border-gray-300 align-middle">{{ $index + 1 }}</td>
                                            <td class="px-4 py-3 text-sm font-medium text-red-900 border border-gray-300 align-middle uppercase">{{ $siswa->nama_lengkap }}</td>
                                            <td class="px-4 py-3 text-sm text-center text-gray-700 border border-gray-300 align-middle">{{ $siswa->nisn }}</td>
                                            <td class="px-4 py-3 text-sm text-center text-gray-700 border border-gray-300 align-middle">{{ $siswa->nis ?? '-' }}</td>
                                            <td class="px-2 py-2 border border-gray-300 align-middle">
                                                @php
                                                    $isLulus = ((int)$rombel->tingkat) == 6;
                                                @endphp
                                                <select name="data[{{ $siswa->id }}][status]" class="w-full text-sm rounded border-gray-300 focus:border-red-500 focus:ring-red-500">
                                                    <option value="">--</option>
                                                    @if($isLulus)
                                                        <option value="Lulus" {{ $status_val == 'Lulus' ? 'selected' : '' }}>Lulus</option>
                                                        <option value="Tidak Lulus" {{ $status_val == 'Tidak Lulus' ? 'selected' : '' }}>Tidak Lulus</option>
                                                    @else
                                                        <option value="Naik ke Kelas" {{ $status_val == "Naik ke Kelas" ? 'selected' : '' }}>Naik Ke Kelas</option>
                                                        <option value="Tinggal di Kelas" {{ $status_val == "Tinggal di Kelas" ? 'selected' : '' }}>Tinggal Di Kelas</option>
                                                    @endif
                                                </select>
                                            </td>
                                            <td class="px-2 py-2 border border-gray-300 align-middle">
                                                @php
                                                    $tingkatLanjut = ((int)$rombel->tingkat) + 1;
                                                    // Roman numerals matching logic
                                                    $romans = [1 => 'I', 2 => 'II', 3 => 'III', 4 => 'IV', 5 => 'V', 6 => 'VI'];
                                                    $tingkatRoman = $romans[$rombel->tingkat] ?? $rombel->tingkat;
                                                    $tingkatLanjutRoman = $romans[$tingkatLanjut] ?? $tingkatLanjut;
                                                @endphp
                                                @if(!$isLulus)
                                                <select name="data[{{ $siswa->id }}][kelas_tujuan]" class="w-full text-sm rounded border-gray-300 focus:border-red-500 focus:ring-red-500">
                                                    <option value="">--</option>
                                                    <option value="{{ $tingkatLanjut }}" {{ $kelas_val == (string)$tingkatLanjut ? 'selected' : '' }}>Kelas {{ $tingkatLanjutRoman }}</option>
                                                    <option value="{{ $rombel->tingkat }}" {{ $kelas_val == (string)$rombel->tingkat ? 'selected' : '' }}>Kelas {{ $tingkatRoman }}</option>
                                                </select>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="px-6 py-8 whitespace-nowrap text-sm text-gray-500 text-center">
                                                Belum ada data siswa.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        @if($siswas->count() > 0)
                        <div class="mt-6 flex justify-end">
                            <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-6 rounded shadow-lg transition-colors flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                </svg>
                                Simpan Kenaikan Kelas
                            </button>
                        </div>
                        @endif
                    </form>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
