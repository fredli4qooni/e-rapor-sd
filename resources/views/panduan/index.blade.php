<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <h2 class="font-semibold text-xl text-white leading-tight uppercase tracking-wider">
                {{ __('Panduan Penggunaan Aplikasi e-Rapor SD') }}
            </h2>
            <div class="text-xs text-red-100 bg-red-950/40 px-3 py-1.5 rounded-md border border-red-800">
                Standar Kurikulum Merdeka & Kurikulum 2013
            </div>
        </div>
    </x-slot>

    <div class="py-8 bg-gray-50 min-h-screen" x-data="{ tab: '{{ $role == 'admin' ? 'admin' : ($role == 'guru' ? 'guru' : 'siswa') }}' }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Hero Welcome Card -->
            <div class="bg-gradient-to-r from-[#8b0000] to-[#5c0000] text-white p-6 sm:p-8 rounded-2xl shadow-md flex flex-col md:flex-row items-center justify-between gap-6">
                <div class="max-w-2xl">
                    <span class="inline-block bg-white/20 text-white text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wider mb-2 backdrop-blur-sm">
                        Pusat Bantuan & Petunjuk Teknis
                    </span>
                    <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight">
                        Panduan Lengkap Penggunaan e-Rapor SD
                    </h1>
                    <p class="mt-2 text-sm sm:text-base text-red-100 leading-relaxed font-normal">
                        Pelajari alur kerja, pengisian tujuan pembelajaran, input nilai, presensi harian, hingga pencetakan buku laporan hasil belajar peserta didik.
                    </p>
                </div>
                <div class="w-16 h-16 sm:w-20 sm:h-20 bg-white/10 rounded-2xl border border-white/20 flex items-center justify-center text-white shrink-0 shadow-inner">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                </div>
            </div>

            <!-- Role Selector Tabs -->
            <div class="flex flex-wrap gap-2 border-b border-gray-200 pb-2">
                <button @click="tab = 'guru'" :class="tab === 'guru' ? 'bg-[#8b0000] text-white font-bold shadow-sm' : 'bg-white text-gray-700 hover:bg-gray-100 font-medium'" class="px-5 py-2.5 rounded-xl text-sm transition-all flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    Panduan Guru Mapel
                </button>
                <button @click="tab = 'walikelas'" :class="tab === 'walikelas' ? 'bg-[#8b0000] text-white font-bold shadow-sm' : 'bg-white text-gray-700 hover:bg-gray-100 font-medium'" class="px-5 py-2.5 rounded-xl text-sm transition-all flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    Panduan Wali Kelas
                </button>
                <button @click="tab = 'siswa'" :class="tab === 'siswa' ? 'bg-[#8b0000] text-white font-bold shadow-sm' : 'bg-white text-gray-700 hover:bg-gray-100 font-medium'" class="px-5 py-2.5 rounded-xl text-sm transition-all flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    Panduan Siswa & Orang Tua
                </button>
                <button @click="tab = 'admin'" :class="tab === 'admin' ? 'bg-[#8b0000] text-white font-bold shadow-sm' : 'bg-white text-gray-700 hover:bg-gray-100 font-medium'" class="px-5 py-2.5 rounded-xl text-sm transition-all flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    Panduan Administrator
                </button>
            </div>

            <!-- TAB 1: PANDUAN GURU MAPEL -->
            <div x-show="tab === 'guru'" x-cloak class="space-y-6">
                
                <div class="bg-white p-6 sm:p-8 rounded-2xl shadow-sm border border-gray-200 space-y-6">
                    <h3 class="text-xl font-bold text-gray-900 border-b pb-3 flex items-center gap-2">
                        <span class="w-8 h-8 rounded-lg bg-red-100 text-red-800 flex items-center justify-center text-sm font-black">1</span>
                        Alur Kerja Guru Mata Pelajaran
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="p-4 bg-red-50/50 rounded-xl border border-red-100">
                            <span class="text-xs font-bold text-red-700 uppercase tracking-wider">Langkah 1</span>
                            <h4 class="font-bold text-gray-900 text-base mt-1">1. Buat Tujuan Pembelajaran (TP)</h4>
                            <p class="text-xs text-gray-600 mt-1 leading-relaxed">
                                Masuk ke menu <strong>Tujuan Pembelajaran</strong>. Tambahkan TP per mata pelajaran dan tingkat kelas untuk semester aktif.
                            </p>
                        </div>
                        <div class="p-4 bg-red-50/50 rounded-xl border border-red-100">
                            <span class="text-xs font-bold text-red-700 uppercase tracking-wider">Langkah 2</span>
                            <h4 class="font-bold text-gray-900 text-base mt-1">2. Input Nilai Rapor & TP</h4>
                            <p class="text-xs text-gray-600 mt-1 leading-relaxed">
                                Masuk ke menu <strong>Input Nilai Rapor</strong>. Pilih kelas & mapel. Masukkan Nilai Akhir (0-100) dan centang capaian TP Tertinggi / Terendah.
                            </p>
                        </div>
                        <div class="p-4 bg-red-50/50 rounded-xl border border-red-100">
                            <span class="text-xs font-bold text-red-700 uppercase tracking-wider">Langkah 3</span>
                            <h4 class="font-bold text-gray-900 text-base mt-1">3. Cek Status Penilaian</h4>
                            <p class="text-xs text-gray-600 mt-1 leading-relaxed">
                                Periksa menu <strong>Cek Penilaian</strong> untuk memastikan seluruh peserta didik telah dinilai dan deskripsi rapor telah ter-generate otomatis.
                            </p>
                        </div>
                    </div>

                    <!-- Petunjuk Ekspor Impor Excel -->
                    <div class="bg-blue-50 p-5 rounded-xl border border-blue-200">
                        <h4 class="font-bold text-blue-900 text-sm flex items-center gap-2">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            Petunjuk Pengisian Format Excel Nilai Rapor:
                        </h4>
                        <ul class="mt-2.5 text-xs text-blue-800 space-y-1.5 list-disc list-inside">
                            <li>Unduh file template melalui menu <strong>Import Nilai -> Download Format Excel</strong>.</li>
                            <li><strong>JANGAN MENGUBAH</strong> kolom <code class="bg-blue-100 px-1 py-0.5 rounded font-mono">ID Siswa (JANGAN DIUBAH)</code> atau nama sheet.</li>
                            <li>Isi kolom <code class="bg-blue-100 px-1 py-0.5 rounded font-mono">Nilai Akhir</code> dengan angka bulat (0-100).</li>
                            <li>Pada kolom TP (<code class="bg-blue-100 px-1 py-0.5 rounded font-mono">TP 1</code>, <code class="bg-blue-100 px-1 py-0.5 rounded font-mono">TP 2</code>, dst.), cukup ketik huruf <strong>T</strong> untuk capaian tertinggi (optimal) atau <strong>R</strong> untuk capaian terendah (perlu bimbingan). Kosongkan jika bukan tertinggi maupun terendah.</li>
                            <li>Simpan file lalu unggah kembali pada form Import Nilai.</li>
                        </ul>
                    </div>
                </div>

            </div>

            <!-- TAB 2: PANDUAN WALI KELAS -->
            <div x-show="tab === 'walikelas'" x-cloak class="space-y-6">
                
                <div class="bg-white p-6 sm:p-8 rounded-2xl shadow-sm border border-gray-200 space-y-6">
                    <h3 class="text-xl font-bold text-gray-900 border-b pb-3 flex items-center gap-2">
                        <span class="w-8 h-8 rounded-lg bg-red-100 text-red-800 flex items-center justify-center text-sm font-black">2</span>
                        Panduan Tugas & Tanggung Jawab Wali Kelas
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="p-5 bg-gray-50 rounded-xl border border-gray-200">
                            <h4 class="font-bold text-gray-900 text-sm flex items-center gap-2">
                                <span class="w-6 h-6 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center text-xs font-bold">A</span>
                                Presensi Harian Siswa
                            </h4>
                            <p class="text-xs text-gray-600 mt-2 leading-relaxed">
                                Buka menu <strong>Presensi Harian</strong> setiap hari sekolah. Gunakan tombol cepat <em>"Set Semua Hadir"</em> atau ubah status ke <strong>Sakit (S)</strong>, <strong>Izin (I)</strong>, atau <strong>Alpa (A)</strong> bagi siswa yang tidak hadir. Data ini langsung dapat dilihat orang tua siswa secara realtime.
                            </p>
                        </div>

                        <div class="p-5 bg-gray-50 rounded-xl border border-gray-200">
                            <h4 class="font-bold text-gray-900 text-sm flex items-center gap-2">
                                <span class="w-6 h-6 rounded-full bg-blue-100 text-blue-700 flex items-center justify-center text-xs font-bold">B</span>
                                Rekap Kehadiran Semester
                            </h4>
                            <p class="text-xs text-gray-600 mt-2 leading-relaxed">
                                Masuk ke menu <strong>Input Kehadiran</strong> untuk mengisi total rekap ketidakhadiran (Sakit, Izin, Alpa) yang akan dicetak pada halaman rapor siswa.
                            </p>
                        </div>

                        <div class="p-5 bg-gray-50 rounded-xl border border-gray-200">
                            <h4 class="font-bold text-gray-900 text-sm flex items-center gap-2">
                                <span class="w-6 h-6 rounded-full bg-amber-100 text-amber-700 flex items-center justify-center text-xs font-bold">C</span>
                                Catatan Wali Kelas & Ekstrakurikuler
                            </h4>
                            <p class="text-xs text-gray-600 mt-2 leading-relaxed">
                                Isi catatan motivasi perkembangan siswa pada menu <strong>Catatan Wali Kelas</strong>, serta masukkan predikat kegiatan ekstrakurikuler siswa pada menu <strong>Ekstrakurikuler</strong>.
                            </p>
                        </div>

                        <div class="p-5 bg-gray-50 rounded-xl border border-gray-200">
                            <h4 class="font-bold text-gray-900 text-sm flex items-center gap-2">
                                <span class="w-6 h-6 rounded-full bg-red-100 text-red-700 flex items-center justify-center text-xs font-bold">D</span>
                                Publikasi & Cetak Rapor
                            </h4>
                            <p class="text-xs text-gray-600 mt-2 leading-relaxed">
                                Buka menu <strong>Cetak Rapor</strong> untuk mencetak buku rapor (Kurikulum Merdeka / 2013), Leger Nilai, Pelengkap Rapor, serta mengaktifkan tombol <em>"Publikasi Rapor"</em> agar siswa/orang tua dapat mengunduhnya.
                            </p>
                        </div>
                    </div>
                </div>

            </div>

            <!-- TAB 3: PANDUAN SISWA & ORANG TUA -->
            <div x-show="tab === 'siswa'" x-cloak class="space-y-6">
                
                <div class="bg-white p-6 sm:p-8 rounded-2xl shadow-sm border border-gray-200 space-y-6">
                    <h3 class="text-xl font-bold text-gray-900 border-b pb-3 flex items-center gap-2">
                        <span class="w-8 h-8 rounded-lg bg-red-100 text-red-800 flex items-center justify-center text-sm font-black">3</span>
                        Panduan Akses Siswa & Orang Tua
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="p-5 bg-emerald-50/60 rounded-xl border border-emerald-200">
                            <div class="w-10 h-10 rounded-xl bg-emerald-600 text-white flex items-center justify-center mb-3">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>
                            <h4 class="font-bold text-gray-900 text-base">Pantau Presensi Harian</h4>
                            <p class="text-xs text-gray-600 mt-1.5 leading-relaxed">
                                Lihat status kehadiran anak hari ini di kartu <strong>Status Kehadiran Hari Ini</strong> pada dashboard utama. Status akan langsung terupdate setelah wali kelas melakukan absensi.
                            </p>
                        </div>

                        <div class="p-5 bg-blue-50/60 rounded-xl border border-blue-200">
                            <div class="w-10 h-10 rounded-xl bg-blue-600 text-white flex items-center justify-center mb-3">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                            </div>
                            <h4 class="font-bold text-gray-900 text-base">Grafik Perkembangan Nilai</h4>
                            <p class="text-xs text-gray-600 mt-1.5 leading-relaxed">
                                Evaluasi capaian belajar melalui grafik interaktif yang menampilkan rata-rata nilai akhir untuk seluruh mata pelajaran di semester berjalan.
                            </p>
                        </div>

                        <div class="p-5 bg-red-50/60 rounded-xl border border-red-200">
                            <div class="w-10 h-10 rounded-xl bg-red-800 text-white flex items-center justify-center mb-3">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            </div>
                            <h4 class="font-bold text-gray-900 text-base">Download File Rapor</h4>
                            <p class="text-xs text-gray-600 mt-1.5 leading-relaxed">
                                Jika wali kelas telah mempublikasikan rapor, tombol unduh file <strong>Pelengkap Rapor</strong>, <strong>Nilai Rapor</strong>, dan <strong>Rapor P5</strong> akan aktif secara otomatis.
                            </p>
                        </div>
                    </div>
                </div>

            </div>

            <!-- TAB 4: PANDUAN ADMINISTRATOR -->
            <div x-show="tab === 'admin'" x-cloak class="space-y-6">
                
                <div class="bg-white p-6 sm:p-8 rounded-2xl shadow-sm border border-gray-200 space-y-6">
                    <h3 class="text-xl font-bold text-gray-900 border-b pb-3 flex items-center gap-2">
                        <span class="w-8 h-8 rounded-lg bg-red-100 text-red-800 flex items-center justify-center text-sm font-black">4</span>
                        Panduan Administrator Sekolah
                    </h3>

                    <div class="space-y-4">
                        <div class="p-4 bg-gray-50 rounded-xl border border-gray-200 flex items-start gap-4">
                            <div class="w-8 h-8 rounded-lg bg-red-800 text-white flex items-center justify-center font-bold text-sm shrink-0">1</div>
                            <div>
                                <h4 class="font-bold text-gray-900 text-sm">Pengaturan Profil Sekolah & Setting Rapor</h4>
                                <p class="text-xs text-gray-600 mt-0.5">Kelola identitas sekolah, logo, kepala sekolah, tanggal penerbitan rapor, serta ukuran kertas (A4 / F4) pada menu <strong>Pengaturan Rapor</strong>.</p>
                            </div>
                        </div>

                        <div class="p-4 bg-gray-50 rounded-xl border border-gray-200 flex items-start gap-4">
                            <div class="w-8 h-8 rounded-lg bg-red-800 text-white flex items-center justify-center font-bold text-sm shrink-0">2</div>
                            <div>
                                <h4 class="font-bold text-gray-900 text-sm">Manajemen Semester Aktif</h4>
                                <p class="text-xs text-gray-600 mt-0.5">Aktifkan satu semester yang sedang berjalan melalui menu <strong>Data Semester</strong>. Seluruh proses input nilai dan rombel akan otomatis menyesuaikan semester aktif.</p>
                            </div>
                        </div>

                        <div class="p-4 bg-gray-50 rounded-xl border border-gray-200 flex items-start gap-4">
                            <div class="w-8 h-8 rounded-lg bg-red-800 text-white flex items-center justify-center font-bold text-sm shrink-0">3</div>
                            <div>
                                <h4 class="font-bold text-gray-900 text-sm">Manajemen Rombel, Pembelajaran, dan Pengguna</h4>
                                <p class="text-xs text-gray-600 mt-0.5">Pastikan setiap rombel memiliki wali kelas, anggota siswa terdaftar, dan guru pengampu mapel telah diatur pada menu <strong>Pembelajaran</strong>.</p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>
</x-app-layout>
