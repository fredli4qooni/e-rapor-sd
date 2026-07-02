<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Tambah Pengguna') }}
        </h2>
    </x-slot>

    <div class="py-6 space-y-6 max-w-7xl mx-auto sm:px-6 lg:px-8">
        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>- {{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900" x-data="{ role: 'admin' }">
                <form action="{{ route('admin.users.store') }}" method="POST">
                    @csrf
                    
                    <div class="mb-4">
                        <label for="role" class="block text-sm font-medium text-gray-700 mb-2">Level Pengguna</label>
                        <select id="role" name="role" x-model="role" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500 sm:text-sm" required>
                            <option value="admin">Admin</option>
                            <option value="guru">Guru</option>
                            <option value="siswa">Siswa</option>
                        </select>
                    </div>

                    <!-- Dropdown Guru -->
                    <div class="mb-4" x-show="role === 'guru'" style="display: none;">
                        <label for="guru_id" class="block text-sm font-medium text-gray-700 mb-2">Pilih Guru</label>
                        <select id="guru_id" name="guru_id" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500 sm:text-sm">
                            <option value="">-- Pilih Guru --</option>
                            @foreach($gurus as $guru)
                                <option value="{{ $guru->id }}">{{ $guru->nama_lengkap }} (NIP: {{ $guru->nip ?? '-' }})</option>
                            @endforeach
                        </select>
                        <p class="text-xs text-gray-500 mt-1">Hanya guru yang belum memiliki akun yang ditampilkan.</p>
                    </div>

                    <!-- Dropdown Siswa -->
                    <div class="mb-4" x-show="role === 'siswa'" style="display: none;">
                        <label for="siswa_id" class="block text-sm font-medium text-gray-700 mb-2">Pilih Siswa</label>
                        <select id="siswa_id" name="siswa_id" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500 sm:text-sm">
                            <option value="">-- Pilih Siswa --</option>
                            @foreach($siswas as $siswa)
                                <option value="{{ $siswa->id }}">{{ $siswa->nama_lengkap }} (NISN: {{ $siswa->nisn ?? '-' }})</option>
                            @endforeach
                        </select>
                        <p class="text-xs text-gray-500 mt-1">Hanya siswa yang belum memiliki akun yang ditampilkan.</p>
                    </div>

                    <div class="mb-4">
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap</label>
                        <input type="text" id="name" name="name" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500 sm:text-sm" required>
                    </div>

                    <div class="mb-4">
                        <label for="username" class="block text-sm font-medium text-gray-700 mb-2">Username</label>
                        <input type="text" id="username" name="username" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500 sm:text-sm" required>
                    </div>

                    <div class="mb-6">
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                        <input type="password" id="password" name="password" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500 sm:text-sm" required minlength="6">
                    </div>

                    <div class="flex justify-end gap-3 border-t pt-4 border-gray-200">
                        <a href="{{ route('admin.users.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-2 px-4 rounded text-sm">
                            Batal
                        </a>
                        <button type="submit" class="bg-red-800 hover:bg-red-900 text-white font-bold py-2 px-4 rounded text-sm">
                            Simpan Pengguna
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
