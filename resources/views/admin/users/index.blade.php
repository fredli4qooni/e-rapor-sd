<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Data Pengguna') }}
        </h2>
    </x-slot>

    <div class="py-6 space-y-6 max-w-7xl mx-auto sm:px-6 lg:px-8">
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
                    <form action="{{ route('admin.users.index') }}" method="GET" class="flex w-full md:w-1/3">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama atau username..." class="form-input rounded-l-md border-gray-300 w-full focus:ring-red-500 focus:border-red-500">
                        <button type="submit" class="bg-gray-200 hover:bg-gray-300 px-4 rounded-r-md border border-l-0 border-gray-300">
                            Cari
                        </button>
                    </form>

                    <div class="flex gap-2 w-full md:w-auto">
                        <form action="{{ route('admin.users.generate_all') }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" onclick="return confirm('Apakah Anda yakin ingin membuat akun secara otomatis untuk semua Guru dan Siswa yang belum memiliki akun?')" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded text-sm w-full md:w-auto shadow-sm">
                                Generate Semua Pengguna
                            </button>
                        </form>
                        <a href="{{ route('admin.users.create') }}" class="bg-red-800 hover:bg-red-900 text-white font-bold py-2 px-4 rounded text-sm w-full md:w-auto shadow-sm text-center">
                            Tambah Pengguna
                        </a>
                    </div>
                </div>
                
                <div class="overflow-x-auto border border-gray-200 rounded-md">
                    <table class="min-w-full bg-white">
                        <thead>
                            <tr class="bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                <th class="py-3 px-4 border-b">No</th>
                                <th class="py-3 px-4 border-b">Nama / Username</th>
                                <th class="py-3 px-4 border-b">Level</th>
                                <th class="py-3 px-4 border-b">Status Akun</th>
                                <th class="py-3 px-4 border-b">Login</th>
                                <th class="py-3 px-4 border-b text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse($users as $index => $user)
                            <tr class="hover:bg-gray-50">
                                <td class="py-3 px-4">{{ $users->firstItem() + $index }}</td>
                                <td class="py-3 px-4">
                                    <div class="font-medium text-gray-900">{{ $user->name }}</div>
                                    <div class="text-xs text-gray-500">{{ $user->username }}</div>
                                </td>
                                <td class="py-3 px-4">
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-{{ $user->role === 'admin' ? 'red' : ($user->role === 'guru' ? 'blue' : 'green') }}-100 text-{{ $user->role === 'admin' ? 'red' : ($user->role === 'guru' ? 'blue' : 'green') }}-800">
                                        {{ ucfirst($user->role) }}
                                    </span>
                                </td>
                                <td class="py-3 px-4">
                                    @if($user->is_active)
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Aktif</span>
                                    @else
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">Nonaktif</span>
                                    @endif
                                </td>
                                <td class="py-3 px-4">
                                    @if(in_array($user->id, $onlineUserIds))
                                        <span class="flex items-center text-xs font-medium text-green-600">
                                            <span class="w-2 h-2 mr-1 bg-green-500 rounded-full animate-pulse"></span>
                                            Online
                                        </span>
                                    @else
                                        <span class="flex items-center text-xs font-medium text-gray-500">
                                            <span class="w-2 h-2 mr-1 bg-gray-400 rounded-full"></span>
                                            Offline
                                        </span>
                                    @endif
                                </td>
                                <td class="py-3 px-4 text-center">
                                    <div x-data="{ open: false }" class="relative inline-block text-left">
                                        <button @click="open = !open" @click.away="open = false" type="button" class="inline-flex justify-center w-full rounded-md border border-gray-300 shadow-sm px-3 py-1 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none">
                                            Aksi
                                            <svg class="-mr-1 ml-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                            </svg>
                                        </button>

                                        <div x-show="open" style="display: none;" class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-10">
                                            <div class="py-1" role="menu" aria-orientation="vertical">
                                                <!-- Reset Password -->
                                                <form action="{{ route('admin.users.reset_password', $user->id) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" onclick="return confirm('Reset password ke default (password123)?')" class="w-full text-left block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                                        Reset Password
                                                    </button>
                                                </form>

                                                <!-- Toggle Aktif -->
                                                <form action="{{ route('admin.users.toggle_active', $user->id) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="w-full text-left block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                                        {{ $user->is_active ? 'Nonaktifkan Pengguna' : 'Aktifkan Pengguna' }}
                                                    </button>
                                                </form>

                                                <!-- Reset Login -->
                                                @if(in_array($user->id, $onlineUserIds))
                                                <form action="{{ route('admin.users.reset_login', $user->id) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" onclick="return confirm('Keluarkan pengguna ini (Force Logout)?')" class="w-full text-left block px-4 py-2 text-sm text-yellow-600 hover:bg-gray-100">
                                                        Reset Login
                                                    </button>
                                                </form>
                                                @endif

                                                <!-- Hapus Pengguna -->
                                                @if($user->id !== auth()->id())
                                                <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" onclick="return confirm('Hapus pengguna secara permanen?')" class="w-full text-left block px-4 py-2 text-sm text-red-600 hover:bg-gray-100">
                                                        Hapus Pengguna
                                                    </button>
                                                </form>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="py-4 px-4 text-center text-gray-500">Tidak ada data pengguna yang ditemukan.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <div class="mt-4">
                    {{ $users->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
