<x-guest-layout>
    <div class="min-h-screen w-full flex flex-col bg-white">
        
        <!-- Border Khas Lampung di Bagian Atas -->
        <x-pixel-aztec-border :band-height="24" :top-only="true" class="w-full shadow-sm z-30" />

        <div class="flex-1 w-full flex flex-col lg:flex-row">
            
            <!-- Left Side: Visual Image & School Identity -->
            <div class="relative w-full lg:w-1/2 min-h-[320px] lg:min-h-[calc(100vh-24px)] bg-cover bg-center bg-no-repeat flex flex-col justify-between p-8 sm:p-12 lg:p-16"
                 style="background-image: url('{{ asset('images/16-9-bg.jpeg') }}');">
                
                <!-- Refined Gradient Overlay -->
                <div class="absolute inset-0 bg-gradient-to-t lg:bg-gradient-to-tr from-black/90 via-black/55 to-black/35"></div>

                <!-- Top: School Header Identity -->
                <div class="relative z-10 flex items-center gap-3.5">
                    @if(isset($sekolah_utama) && $sekolah_utama->logo_sekolah)
                        <div class="w-12 h-12 rounded-xl bg-white/95 p-1.5 shadow-md flex items-center justify-center shrink-0">
                            <img src="{{ asset('storage/' . $sekolah_utama->logo_sekolah) }}" alt="Logo Sekolah" class="w-full h-full object-contain">
                        </div>
                    @else
                        <div class="w-11 h-11 rounded-xl bg-white/20 backdrop-blur-sm border border-white/30 flex items-center justify-center text-white shrink-0">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                        </div>
                    @endif
                    <div>
                        <h2 class="text-white font-bold text-base sm:text-lg tracking-tight leading-tight drop-shadow-sm">
                            {{ $sekolah_utama->nama_sekolah ?? 'SD Negeri Percontohan' }}
                        </h2>
                        @if(isset($sekolah_utama->npsn))
                            <p class="text-xs text-gray-300 font-mono tracking-wide mt-0.5">NPSN: {{ $sekolah_utama->npsn }}</p>
                        @endif
                    </div>
                </div>

                <!-- Middle: Clean Editorial Headline & Description -->
                <div class="relative z-10 my-8 sm:my-auto max-w-lg">
                    <div class="w-10 h-1 bg-[#8B1515] rounded-full mb-4"></div>
                    <h1 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold tracking-tight text-white leading-tight drop-shadow-sm">
                        Aplikasi e-Rapor SD
                    </h1>
                    <p class="mt-3 text-sm sm:text-base text-gray-200 leading-relaxed font-normal drop-shadow-sm">
                        Sistem manajemen penilaian terintegrasi untuk pengelolaan capaian pembelajaran dan penerbitan laporan hasil belajar siswa secara transparan, akurat, dan akuntabel.
                    </p>

                    <!-- Simple Clean Specs -->
                    <div class="mt-6 flex items-center gap-4 text-xs text-gray-300 font-medium">
                        <span class="flex items-center gap-1.5">
                            <span class="w-1.5 h-1.5 rounded-full bg-red-400"></span>
                            Kurikulum Merdeka & 2013
                        </span>
                        <span class="text-gray-500">•</span>
                        <span class="flex items-center gap-1.5">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
                            Standar Kemendikbudristek
                        </span>
                    </div>
                </div>

                <!-- Bottom: Institutional Info & Copyright -->
                <div class="relative z-10 pt-4 border-t border-white/15 flex flex-col sm:flex-row sm:items-center justify-between gap-1.5 text-xs text-gray-300">
                    <span>
                        @if(isset($sekolah_utama->kecamatan) && isset($sekolah_utama->kabupaten))
                            {{ $sekolah_utama->kecamatan }}, {{ $sekolah_utama->kabupaten }}
                        @else
                            Lampung, Indonesia
                        @endif
                    </span>
                    <span>&copy; {{ date('Y') }} Hak Cipta Dilindungi</span>
                </div>
            </div>

            <!-- Right Side: Clean Minimalist Login Form -->
            <div class="w-full lg:w-1/2 min-h-[460px] lg:min-h-[calc(100vh-24px)] flex items-center justify-center p-6 sm:p-10 lg:p-12 bg-slate-50/50">
                <div class="w-full max-w-md bg-white border border-gray-200 rounded-2xl p-7 sm:p-9 shadow-sm">
                    
                    <div class="mb-7">
                        <h2 class="text-2xl font-bold text-gray-900 tracking-tight">
                            Masuk
                        </h2>
                        <p class="text-sm text-gray-500 mt-1">
                            Masukkan email dan kata sandi untuk mengakses akun Anda.
                        </p>
                    </div>

                    <x-auth-session-status class="mb-5" :status="session('status')" />

                    <form method="POST" action="{{ route('login') }}" class="space-y-4">
                        @csrf

                        <!-- Email Input -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-1.5">
                                Email
                            </label>
                            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                                   class="w-full px-3.5 py-2.5 bg-white border border-gray-300 rounded-lg text-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:border-[#8B1515] focus:ring-1 focus:ring-[#8B1515] transition-colors"
                                   placeholder="nama@email.com">
                            <x-input-error :messages="$errors->get('email')" class="mt-1.5 text-xs" />
                        </div>

                        <!-- Password Input -->
                        <div x-data="{ show: false }">
                            <div class="flex items-center justify-between mb-1.5">
                                <label for="password" class="block text-sm font-medium text-gray-700">
                                    Kata Sandi
                                </label>
                                @if (Route::has('password.request'))
                                    <a class="text-xs text-[#8B1515] hover:underline font-medium" href="{{ route('password.request') }}">
                                        Lupa kata sandi?
                                    </a>
                                @endif
                            </div>
                            <div class="relative">
                                <input id="password" x-bind:type="show ? 'text' : 'password'" name="password" required
                                       class="w-full pl-3.5 pr-11 py-2.5 bg-white border border-gray-300 rounded-lg text-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:border-[#8B1515] focus:ring-1 focus:ring-[#8B1515] transition-colors"
                                       placeholder="••••••••">
                                <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-gray-400 hover:text-[#8B1515] transition-colors focus:outline-none">
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
                            <button type="submit" class="w-full bg-[#8B1515] hover:bg-[#741010] text-white font-medium py-2.5 px-4 rounded-lg shadow-sm transition-colors text-sm flex justify-center items-center gap-2 focus:ring-2 focus:ring-offset-2 focus:ring-[#8B1515] outline-none">
                                Masuk
                            </button>
                        </div>
                    </form>

                </div>
            </div>

        </div>

    </div>
</x-guest-layout>