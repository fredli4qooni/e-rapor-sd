<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-white leading-tight">
                {{ __('Lihat & Edit Deskripsi Capaian') }}
            </h2>
            <a href="{{ route('guru.nilai.index', ['rombel_id' => $rombel->id, 'mata_pelajaran_id' => $mapel->id]) }}" class="bg-gray-800 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded shadow text-sm">
                &larr; Kembali ke Input Nilai
            </a>
        </div>
    </x-slot>

    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            @if(session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded shadow-sm" role="alert">
                <p class="font-bold">Berhasil</p>
                <p>{{ session('success') }}</p>
            </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 border-b border-gray-200">
                    <div class="mb-6">
                        <h3 class="text-lg font-bold text-gray-800">Kelas {{ $rombel->nama_rombel }} - {{ $mapel->nama_mapel }}</h3>
                        <p class="text-sm text-gray-500">Teks deskripsi di bawah ini di-generate otomatis berdasarkan TP yang dicentang sebelumnya. Anda bebas mengubahnya jika dirasa kurang sesuai dengan kondisi rill siswa.</p>
                    </div>

                    <form action="{{ route('guru.nilai.update_deskripsi') }}" method="POST">
                        @csrf
                        
                        <div class="space-y-8">
                            @foreach($siswas as $idx => $siswa)
                                @php
                                    $nilaiSiswa = $nilai->get($siswa->id);
                                    if(!$nilaiSiswa) continue; // Jangan tampilkan kalau nilai belum diisi
                                @endphp
                                <div class="bg-gray-50 p-4 rounded-lg border border-gray-200 shadow-sm relative">
                                    <div class="absolute top-0 right-0 bg-blue-600 text-white text-xs font-bold px-3 py-1 rounded-bl-lg rounded-tr-lg">
                                        Nilai Akhir: {{ $nilaiSiswa->nilai_akhir }}
                                    </div>
                                    <h4 class="font-bold text-gray-800 mb-4">{{ $idx + 1 }}. {{ $siswa->nama_lengkap }}</h4>
                                    
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-sm font-bold text-green-700 mb-1">Capaian Tertinggi (Tercapai Optimal)</label>
                                            <textarea name="deskripsi[{{ $nilaiSiswa->id }}][tertinggi]" rows="3" class="w-full rounded border-gray-300 focus:border-green-500 focus:ring-green-500 text-sm bg-white">{{ $nilaiSiswa->deskripsi->deskripsi_tertinggi ?? '' }}</textarea>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-bold text-yellow-700 mb-1">Capaian Terendah (Perlu Pendampingan)</label>
                                            <textarea name="deskripsi[{{ $nilaiSiswa->id }}][terendah]" rows="3" class="w-full rounded border-gray-300 focus:border-yellow-500 focus:ring-yellow-500 text-sm bg-white">{{ $nilaiSiswa->deskripsi->deskripsi_terendah ?? '' }}</textarea>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="mt-8 pt-5 border-t border-gray-200 flex justify-end">
                            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-8 rounded shadow-lg transition-colors inline-flex items-center text-lg">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
                                Simpan Perubahan Deskripsi
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
