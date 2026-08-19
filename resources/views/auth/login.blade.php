<x-guest-layout>
    <div class="min-h-screen w-full flex flex-col lg:flex-row bg-[#f8fafc]">
        
        <!-- Left Side: Visual Showcase & Hero Banner -->
        <div class="relative w-full lg:w-7/12 min-h-[480px] lg:min-h-screen bg-cover bg-center bg-no-repeat flex flex-col justify-between p-6 sm:p-10 lg:p-12 text-white overflow-hidden"
             style="background-image: url('{{ asset('images/16-9-bg.jpeg') }}');">
            
            <!-- Atmospheric Dark Red & Sunset Gradient Overlay -->
            <div class="absolute inset-0 bg-gradient-to-t from-[#1a0505]/95 via-[#5c0d0d]/80 to-transparent"></div>
            <div class="absolute inset-0 bg-red-950/20 mix-blend-multiply"></div>

            <!-- Top Left: Logo -->
            <div class="relative z-10">
                @if(isset($sekolah_utama) && $sekolah_utama->logo_sekolah)
                    <img src="{{ asset('storage/' . $sekolah_utama->logo_sekolah) }}" alt="Logo Sekolah" class="w-14 h-14 sm:w-16 sm:h-16 object-contain drop-shadow-xl">
                @else
                    <div class="w-14 h-14 rounded-2xl bg-white/20 backdrop-blur-md border border-white/30 flex items-center justify-center text-white drop-shadow-lg">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                    </div>
                @endif
            </div>

            <!-- Middle / Title Section -->
            <div class="relative z-10 my-8 sm:my-auto max-w-xl">
                <span class="inline-block px-3.5 py-1 rounded-full bg-white text-[#C41E18] text-[11px] font-extrabold uppercase tracking-wider shadow-sm mb-3">
                    Aplikasi e-Rapor SD
                </span>
                
                <h1 class="text-3xl sm:text-4xl lg:text-5xl font-black uppercase tracking-tight leading-none drop-shadow-md">
                    @php
                        $namaSekolah = $sekolah_utama->nama_sekolah ?? 'SD NEGERI 1 PERCONTOHAN';
                        $words = explode(' ', $namaSekolah);
                        if (count($words) > 1) {
                            $lastWord = array_pop($words);
                            $firstPart = implode(' ', $words);
                        } else {
                            $firstPart = $namaSekolah;
                            $lastWord = '';
                        }
                    @endphp
                    <span>{{ $firstPart }}</span>
                    @if($lastWord)
                        <span class="block text-[#ff4d4d]">{{ $lastWord }}</span>
                    @endif
                </h1>
                
                <p class="mt-3.5 text-sm sm:text-base text-gray-200 leading-relaxed font-normal max-w-lg drop-shadow">
                    Sistem informasi e-rapor untuk memudahkan pengelolaan nilai dan laporan perkembangan peserta didik.
                </p>
            </div>

            <!-- Bottom 3 Feature Cards -->
            <div class="relative z-10 grid grid-cols-1 sm:grid-cols-3 gap-3.5 sm:gap-4 pt-4">
                <!-- Card 1 -->
                <div class="bg-black/30 backdrop-blur-md border border-white/15 rounded-2xl p-4 text-center flex flex-col items-center justify-center shadow-lg">
                    <div class="w-10 h-10 rounded-full bg-white flex items-center justify-center text-[#C41E18] mb-2.5 shadow-sm">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                    </div>
                    <h4 class="font-bold text-white text-xs sm:text-sm">Aman & Terpercaya</h4>
                    <p class="text-[10px] sm:text-[11px] text-gray-300 mt-1 leading-snug">Data tersimpan aman dengan sistem terproteksi</p>
                </div>

                <!-- Card 2 -->
                <div class="bg-black/30 backdrop-blur-md border border-white/15 rounded-2xl p-4 text-center flex flex-col items-center justify-center shadow-lg">
                    <div class="w-10 h-10 rounded-full bg-white flex items-center justify-center text-[#C41E18] mb-2.5 shadow-sm">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                    </div>
                    <h4 class="font-bold text-white text-xs sm:text-sm">Mudah Digunakan</h4>
                    <p class="text-[10px] sm:text-[11px] text-gray-300 mt-1 leading-snug">Antarmuka sederhana dan intuitif untuk semua pengguna</p>
                </div>

                <!-- Card 3 -->
                <div class="bg-black/30 backdrop-blur-md border border-white/15 rounded-2xl p-4 text-center flex flex-col items-center justify-center shadow-lg">
                    <div class="w-10 h-10 rounded-full bg-white flex items-center justify-center text-[#C41E18] mb-2.5 shadow-sm">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                    </div>
                    <h4 class="font-bold text-white text-xs sm:text-sm">Laporan Akurat</h4>
                    <p class="text-[10px] sm:text-[11px] text-gray-300 mt-1 leading-snug">Hasil laporan cepat, akurat, dan dapat dipertanggungjawabkan</p>
                </div>
            </div>

        </div>

        <!-- Right Side: Login Card & Notice -->
        <div class="w-full lg:w-5/12 min-h-screen flex flex-col justify-between items-center p-6 sm:p-10 lg:p-12 relative bg-[#f8fafc] overflow-hidden">
            
            <!-- Subtle Decorative Dots Top Right -->
            <div class="absolute -top-6 -right-6 w-32 h-32 opacity-20 pointer-events-none"
                 style="background-image: radial-gradient(#C41E18 2px, transparent 2px); background-size: 12px 12px;"></div>

            <div class="w-full max-w-md my-auto flex flex-col gap-4">
                
                <!-- Main Login Card -->
                <div class="w-full bg-white rounded-3xl p-8 sm:p-10 shadow-[0_10px_35px_rgba(0,0,0,0.05)] border border-gray-100/80">
                    
                    <!-- Card Top Header -->
                    <div class="flex flex-col items-center justify-center mb-8 text-center">
                        @if(isset($sekolah_utama) && $sekolah_utama->logo_sekolah)
                            <img src="{{ asset('storage/' . $sekolah_utama->logo_sekolah) }}" alt="Logo Sekolah" class="w-16 h-16 object-contain mb-3 drop-shadow">
                        @else
                            <div class="w-16 h-16 rounded-2xl bg-red-50 text-[#C41E18] flex items-center justify-center mb-3 shadow-inner">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                            </div>
                        @endif
                        <h2 class="text-xl sm:text-2xl font-extrabold text-gray-900 tracking-tight">
                            {{ $sekolah_utama->nama_sekolah ?? 'SD Negeri 1 Percontohan' }}
                        </h2>
                        <span class="text-[11px] font-extrabold text-[#C41E18] uppercase tracking-widest mt-1">
                            Aplikasi e-Rapor SD
                        </span>
                    </div>

                    <x-auth-session-status class="mb-5" :status="session('status')" />

                    <form method="POST" action="{{ route('login') }}" class="space-y-4">
                        @csrf

                        <!-- Email Input -->
                        <div>
                            <label for="email" class="block text-xs sm:text-sm font-semibold text-gray-700 mb-1.5">
                                Email
                            </label>
                            <div class="relative flex items-center">
                                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-[#C41E18]">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                                </div>
                                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                                       class="w-full pl-11 pr-4 py-3 bg-white border border-gray-300 rounded-xl text-sm text-gray-800 placeholder-gray-400 focus:outline-none focus:border-[#C41E18] focus:ring-2 focus:ring-[#C41E18]/10 transition-colors"
                                       placeholder="mahasiswatest@radenintan.ac.id">
                            </div>
                            <x-input-error :messages="$errors->get('email')" class="mt-1.5 text-xs" />
                        </div>

                        <!-- Password Input -->
                        <div x-data="{ show: false }">
                            <div class="flex items-center justify-between mb-1.5">
                                <label for="password" class="block text-xs sm:text-sm font-semibold text-gray-700">
                                    Kata Sandi
                                </label>
                                @if (Route::has('password.request'))
                                    <a class="text-xs font-semibold text-[#C41E18] hover:underline" href="{{ route('password.request') }}">
                                        Lupa kata sandi?
                                    </a>
                                @endif
                            </div>
                            <div class="relative flex items-center">
                                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                                </div>
                                <input id="password" x-bind:type="show ? 'text' : 'password'" name="password" required
                                       class="w-full pl-11 pr-11 py-3 bg-[#f8fafc] border border-gray-200 rounded-xl text-sm text-gray-800 placeholder-gray-400 focus:bg-white focus:outline-none focus:border-[#C41E18] focus:ring-2 focus:ring-[#C41E18]/10 transition-colors"
                                       placeholder="••••••••">
                                <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-gray-400 hover:text-[#C41E18] transition-colors focus:outline-none">
                                    <svg x-show="!show" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                    <svg x-show="show" x-cloak class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                    </svg>
                                </button>
                            </div>
                            <x-input-error :messages="$errors->get('password')" class="mt-1.5 text-xs" />
                        </div>

                        <!-- Submit Button -->
                        <div class="pt-2">
                            <button type="submit" class="w-full bg-[#C41E18] hover:bg-[#a81914] text-white font-bold py-3.5 px-4 rounded-xl shadow-md hover:shadow-lg transition-all duration-200 text-sm flex justify-center items-center gap-2 focus:ring-4 focus:ring-[#C41E18]/20 outline-none">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path></svg>
                                <span>Masuk</span>
                            </button>
                        </div>
                    </form>

                </div>

                <!-- Bottom Notice Banner -->
                <div class="w-full bg-[#fff5f5] border border-[#fed7d7] rounded-2xl p-4 flex items-center justify-between gap-3 shadow-sm relative overflow-hidden">
                    <div class="flex items-start gap-3 relative z-10">
                        <div class="text-[#C41E18] mt-0.5 shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                        </div>
                        <div>
                            <h5 class="text-xs font-bold text-[#C41E18]">Sistem ini hanya untuk pengguna terdaftar.</h5>
                            <p class="text-[11px] text-gray-600 mt-0.5">Pastikan email dan kata sandi Anda benar.</p>
                        </div>
                    </div>
                    
                    <!-- Decorative Dots Watermark -->
                    <div class="absolute -right-1 -bottom-1 w-20 h-20 opacity-20 pointer-events-none"
                         style="background-image: radial-gradient(#C41E18 2px, transparent 2px); background-size: 8px 8px;"></div>
                </div>

            </div>

            <!-- Bottom Copyright -->
            <div class="text-center text-xs text-gray-500 py-2">
                &copy; 2026 {{ $sekolah_utama->nama_sekolah ?? 'SD Negeri 1 Percontohan' }}. Hak Cipta Dilindungi.
            </div>

        </div>

    </div>
</x-guest-layout>