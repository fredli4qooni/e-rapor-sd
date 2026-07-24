<x-guest-layout>
    <style>
        @keyframes blob {
            0% { transform: translate(0px, 0px) scale(1); }
            33% { transform: translate(30px, -50px) scale(1.1); }
            66% { transform: translate(-20px, 20px) scale(0.9); }
            100% { transform: translate(0px, 0px) scale(1); }
        }
        .animate-blob {
            animation: blob 7s infinite;
        }
        .animation-delay-2000 {
            animation-delay: 2s;
        }
        .animation-delay-4000 {
            animation-delay: 4s;
        }
    </style>

    <div class="relative flex w-full h-full min-h-[calc(100vh-4rem)] items-center justify-center bg-slate-50 overflow-hidden">
        
        <div class="absolute top-0 -left-4 w-72 h-72 bg-red-200 rounded-full mix-blend-multiply filter blur-2xl opacity-70 animate-blob"></div>
        <div class="absolute top-0 -right-4 w-72 h-72 bg-rose-200 rounded-full mix-blend-multiply filter blur-2xl opacity-70 animate-blob animation-delay-2000"></div>
        <div class="absolute -bottom-8 left-20 w-96 h-96 bg-[#8B1515] rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob animation-delay-4000"></div>
        <div class="absolute bottom-10 right-20 w-72 h-72 bg-orange-100 rounded-full mix-blend-multiply filter blur-2xl opacity-60 animate-blob"></div>

        <div class="relative z-10 w-full max-w-md bg-white/80 backdrop-blur-xl rounded-3xl shadow-[0_8px_30px_rgb(0,0,0,0.08)] border border-white/50 overflow-hidden px-8 py-10">
            
            <div class="flex flex-col items-center justify-center mb-8 text-center">
                @if(isset($sekolah_utama) && $sekolah_utama->logo_sekolah)
                    <img src="{{ asset('storage/' . $sekolah_utama->logo_sekolah) }}" alt="Logo Sekolah" class="w-24 h-24 object-contain mb-4 drop-shadow-md">
                @else
                    <div class="w-24 h-24 bg-gradient-to-br from-gray-100 to-gray-200 rounded-2xl shadow-inner flex items-center justify-center mb-4 text-gray-400 rotate-3">
                        <svg class="w-10 h-10 -rotate-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                    </div>
                @endif
                
                <h2 class="text-2xl font-extrabold text-gray-800 tracking-tight">
                    {{ $sekolah_utama->nama_sekolah ?? 'e-Rapor SD' }}
                </h2>
                <div class="flex items-center justify-center mt-3 w-full opacity-60">
                    <hr class="w-full border-gray-300">
                    <span class="px-3 text-[10px] text-gray-500 font-bold tracking-widest uppercase whitespace-nowrap">Silakan Masuk</span>
                    <hr class="w-full border-gray-300">
                </div>
            </div>

            <x-auth-session-status class="mb-4" :status="session('status')" />

            <form method="POST" action="{{ route('login') }}" class="space-y-5">
                @csrf

                <div class="flex flex-col border border-gray-200 rounded-xl overflow-hidden bg-white/50 focus-within:bg-white focus-within:border-[#8B1515] focus-within:ring-4 focus-within:ring-[#8B1515]/10 shadow-sm transition-all duration-300">
                    <div class="px-4 pt-3 pb-1 text-[10px] font-bold text-gray-500 uppercase tracking-wider">
                        Email Pengguna
                    </div>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus class="px-4 pb-3 pt-1 border-none bg-transparent focus:ring-0 text-sm w-full text-gray-800 placeholder-gray-300" placeholder="Ketikkan email...">
                </div>
                <x-input-error :messages="$errors->get('email')" class="mt-1" />

                <div x-data="{ show: false }" class="flex flex-col border border-gray-200 rounded-xl overflow-hidden bg-white/50 focus-within:bg-white focus-within:border-[#8B1515] focus-within:ring-4 focus-within:ring-[#8B1515]/10 shadow-sm transition-all duration-300">
                    <div class="px-4 pt-3 pb-1 text-[10px] font-bold text-gray-500 uppercase tracking-wider">
                        Kata Sandi
                    </div>
                    <div class="flex">
                        <input id="password" x-bind:type="show ? 'text' : 'password'" name="password" required class="flex-1 px-4 pb-3 pt-1 border-none bg-transparent focus:ring-0 text-sm w-full text-gray-800 placeholder-gray-300" placeholder="Ketikkan sandi...">
                        <button type="button" @click="show = !show" class="px-4 pb-2 text-gray-400 hover:text-[#8B1515] transition-colors focus:outline-none">
                            <svg x-show="!show" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                            <svg x-show="show" x-cloak class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path></svg>
                        </button>
                    </div>
                </div>
                <x-input-error :messages="$errors->get('password')" class="mt-1" />



                <div class="flex justify-end pt-1">
                    @if (Route::has('password.request'))
                        <a class="text-xs text-gray-500 hover:text-[#8B1515] font-medium transition-colors" href="{{ route('password.request') }}">
                            Lupa Kata Sandi?
                        </a>
                    @endif
                </div>

                <button type="submit" class="w-full bg-[#8B1515] hover:bg-red-900 text-white font-bold py-3.5 px-4 rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 flex justify-center items-center gap-2 transition-all duration-200 focus:ring-4 focus:ring-red-900/30">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path></svg>
                    MASUK SEKARANG
                </button>
            </form>
        </div>
    </div>
</x-guest-layout>