<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>e-Rapor SD Modern</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,600,700|outfit:600,800&display=swap" rel="stylesheet" />
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Inter', sans-serif; }
        .font-outfit { font-family: 'Outfit', sans-serif; }
        .glass {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        .bg-mesh {
            background-color: #0f172a;
            background-image: 
                radial-gradient(at 0% 0%, hsla(253,16%,7%,1) 0, transparent 50%), 
                radial-gradient(at 50% 0%, hsla(225,39%,30%,1) 0, transparent 50%), 
                radial-gradient(at 100% 0%, hsla(339,49%,30%,1) 0, transparent 50%);
            background-attachment: fixed;
        }
    </style>
</head>
<body class="antialiased bg-mesh text-white min-h-screen flex flex-col selection:bg-pink-500 selection:text-white">

    <header class="w-full z-50 glass py-4 px-6 sm:px-10 flex justify-between items-center fixed top-0">
        <div class="flex items-center gap-2">
            <svg class="w-8 h-8 text-pink-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
            <span class="font-outfit text-2xl font-bold tracking-tight">e-Rapor<span class="text-pink-500">SD</span></span>
        </div>
        
        @if (Route::has('login'))
            <nav class="flex gap-4">
                @auth
                    <a href="{{ url('/dashboard') }}" class="font-semibold text-gray-300 hover:text-white transition focus:outline-none focus-visible:ring-2 focus-visible:ring-pink-500 rounded-md px-3 py-2">Dasbor</a>
                @else
                    <a href="{{ route('login') }}" class="glass hover:bg-white/20 transition text-white font-semibold py-2 px-6 rounded-full shadow-lg">Login Portal</a>
                @endauth
            </nav>
        @endif
    </header>

    <main class="flex-grow flex items-center justify-center pt-24 px-6 sm:px-10">
        <div class="max-w-4xl w-full flex flex-col items-center text-center gap-8 relative z-10">
            <div class="inline-block glass rounded-full px-4 py-1.5 text-sm font-semibold text-pink-300 border border-pink-500/30 mb-2 shadow-[0_0_15px_rgba(236,72,153,0.3)] animate-pulse">
                Sistem Penilaian Kurikulum Merdeka & K-2013
            </div>
            
            <h1 class="font-outfit text-5xl sm:text-7xl font-extrabold leading-tight tracking-tight">
                Revolusi Laporan <br/>
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-pink-500 via-purple-500 to-indigo-500">
                    Hasil Belajar
                </span>
            </h1>
            
            <p class="text-lg sm:text-xl text-gray-300 max-w-2xl leading-relaxed">
                Platform e-Rapor SD modern yang didesain khusus untuk memudahkan pengelolaan nilai, sinkronisasi Dapodik, dan pencetakan rapor dengan akurasi tinggi.
            </p>
            
            <div class="flex flex-col sm:flex-row gap-4 mt-4">
                <a href="{{ route('login') }}" class="bg-gradient-to-r from-pink-500 to-purple-600 hover:from-pink-600 hover:to-purple-700 text-white font-bold py-3 px-8 rounded-full shadow-[0_0_20px_rgba(236,72,153,0.4)] transition transform hover:-translate-y-1 hover:scale-105">
                    Mulai Sekarang
                </a>
                <a href="#fitur" class="glass hover:bg-white/10 text-white font-bold py-3 px-8 rounded-full transition transform hover:-translate-y-1">
                    Pelajari Fitur
                </a>
            </div>
        </div>
        
        <!-- Decorative background blurs -->
        <div class="absolute top-1/2 left-1/4 w-96 h-96 bg-pink-600/20 rounded-full mix-blend-screen filter blur-[100px] pointer-events-none"></div>
        <div class="absolute top-1/3 right-1/4 w-[500px] h-[500px] bg-indigo-600/20 rounded-full mix-blend-screen filter blur-[100px] pointer-events-none"></div>
    </main>

    <footer class="w-full glass py-6 text-center text-gray-400 text-sm z-10 mt-auto">
        &copy; {{ date('Y') }} e-Rapor SD. Terintegrasi dengan Dapodik Kemdikbudristek.
    </footer>
</body>
</html>
