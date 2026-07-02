<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight uppercase">
            {{ __('Input Deskripsi DPL (Kurikulum 2013 Revisi 2025)') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-t-4 border-red-800">
                <div class="p-6">
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 border-b pb-4 gap-4">
                        <h3 class="text-lg font-bold text-gray-800 uppercase">DESKRIPSI DPL KELAS {{ strtoupper($rombel->nama_rombel ?? '-') }}</h3>
                        
                        <form action="{{ route('walikelas.deskripsi_dpl.generate') }}" method="POST" class="inline">
                            @csrf
                            <input type="hidden" name="rombel_id" value="{{ $rombel->id }}">
                            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded shadow transition-colors flex items-center gap-2 text-sm" onclick="return confirm('Generate otomatis akan menimpa deskripsi yang sudah ada. Lanjutkan?')">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                                </svg>
                                Generate dari Nilai Guru
                            </button>
                        </form>
                    </div>
                    
                    @if(session('success'))
                        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif
                    @if(session('error'))
                        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                            <span class="block sm:inline">{{ session('error') }}</span>
                        </div>
                    @endif

                    <div class="mb-6 p-4 border border-gray-200 rounded-md bg-white flex items-center">
                        <label class="w-48 font-bold text-gray-700 text-sm">Pilih Kelas</label>
                        <input type="text" value="{{ $rombel->tingkat . ' ' . strtoupper($rombel->nama_rombel) }}" class="flex-1 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-md block p-2" readonly>
                    </div>

                    <form method="POST" action="{{ route('walikelas.deskripsi_dpl.store') }}">
                        @csrf
                        <input type="hidden" name="rombel_id" value="{{ $rombel->id }}">

                        <div class="overflow-x-auto mt-4">
                            <table class="min-w-full divide-y divide-gray-200 border border-gray-300">
                                <thead class="bg-red-900 text-white">
                                    <tr>
                                        <th scope="col" class="px-4 py-3 text-left text-xs font-bold tracking-wider border border-red-800 w-64">Nama Siswa</th>
                                        <th scope="col" class="px-4 py-3 text-left text-xs font-bold tracking-wider border border-red-800">Deskripsi Ketercapaian Profil Lulusan</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @forelse($siswas as $index => $siswa)
                                        @php
                                            $sikap = $sikaps[$siswa->id] ?? null;
                                        @endphp
                                        <tr class="hover:bg-red-50/50 transition-colors {{ $index % 2 == 0 ? 'bg-red-50/20' : 'bg-white' }}">
                                            <td class="px-4 py-3 text-sm border border-gray-300 align-top">
                                                <div class="font-bold text-red-900 uppercase mb-1">{{ $index + 1 }} {{ $siswa->nama_lengkap }}</div>
                                                <div class="text-xs text-gray-500">NISN : {{ $siswa->nisn }}</div>
                                                <div class="text-xs text-gray-500">NIS : {{ $siswa->nis ?? '-' }}</div>
                                            </td>
                                            <td class="px-4 py-3 border border-gray-300 align-top">
                                                <textarea name="data[{{ $siswa->id }}][deskripsi_dpl]" rows="4" class="w-full text-sm rounded border-gray-300 focus:border-red-500 focus:ring-red-500" placeholder="Tulis deskripsi DPL di sini...">{{ $sikap ? $sikap->deskripsi_dpl : '' }}</textarea>
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
                                Simpan Deskripsi DPL
                            </button>
                        </div>
                        @endif
                    </form>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
