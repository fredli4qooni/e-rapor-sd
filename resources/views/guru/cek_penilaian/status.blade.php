<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight uppercase">
            {{ __('Cek Penilaian') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Filter Section Card -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-t-4 border-red-800">
                <div class="p-6">
                    <form method="GET" action="{{ route('guru.cek_penilaian.status') }}" class="space-y-4">
                        <div class="flex items-center gap-4">
                            <label class="w-1/4 text-sm font-bold text-gray-700 whitespace-nowrap">Pilih Kelas</label>
                            <select name="rombel_id" onchange="this.form.submit()" class="block w-3/4 rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm bg-gray-50">
                                <option value="">Pilih Data Kelas</option>
                                @foreach($rombels as $r)
                                    <option value="{{ $r->id }}" {{ $rombel_id == $r->id ? 'selected' : '' }}>
                                        {{ $r->nama_rombel }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </form>
                </div>
            </div>

            @if($rombel_id)
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mt-6">
                <div class="p-6">
                    <h3 class="text-lg font-bold text-gray-800 uppercase mb-4">DATA STATUS PENILAIAN GURU</h3>
                    
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 border border-gray-300">
                            <thead class="bg-red-900 text-white">
                                <tr>
                                    <th scope="col" class="px-4 py-3 text-center text-xs font-bold tracking-wider w-16 border border-red-800">No</th>
                                    <th scope="col" class="px-4 py-3 text-center text-xs font-bold tracking-wider border border-red-800">Nama Mapel</th>
                                    <th scope="col" class="px-4 py-3 text-center text-xs font-bold tracking-wider w-40 border border-red-800">Rombel</th>
                                    <th scope="col" class="px-4 py-3 text-center text-xs font-bold tracking-wider w-32 border border-red-800">Nilai Rapor</th>
                                    <th scope="col" class="px-4 py-3 text-center text-xs font-bold tracking-wider w-32 border border-red-800">Deskripsi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($data_status as $index => $item)
                                    <tr class="hover:bg-red-50/50 transition-colors {{ $index % 2 == 0 ? 'bg-red-50/20' : 'bg-white' }}">
                                        <td class="px-4 py-3 text-sm text-center text-gray-700 border border-gray-300">{{ $index + 1 }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-700 border border-gray-300">{{ $item['nama_mapel'] }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-700 border border-gray-300 text-center">{{ $item['rombel'] }}</td>
                                        <td class="px-4 py-3 text-sm border border-gray-300 text-center text-gray-500 bg-gray-200/50">
                                            {{ $item['nilai_rapor'] }} Data
                                        </td>
                                        <td class="px-4 py-3 text-sm border border-gray-300 text-center text-gray-500 bg-gray-200/50">
                                            {{ $item['deskripsi'] }} Data
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-8 whitespace-nowrap text-sm text-gray-500 text-center">
                                            Belum ada mata pelajaran pada rombongan belajar ini.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
            @endif

        </div>
    </div>
</x-app-layout>
