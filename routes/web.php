<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/dashboard', function () {
    $user = request()->user();
    if ($user->role === 'admin') return redirect()->route('admin.dashboard');
    if ($user->role === 'guru') return redirect()->route('guru.dashboard');
    if ($user->role === 'siswa') return redirect()->route('siswa.dashboard');
    if ($user->role === 'kepsek') return redirect()->route('kepsek.dashboard');
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Admin Routes
Route::middleware(['auth', 'can:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Admin\AdminController::class, 'dashboard'])->name('dashboard');
    Route::post('/toggle-input-nilai', [\App\Http\Controllers\Admin\AdminController::class, 'toggleInputNilai'])->name('toggle_input_nilai');
    Route::post('/sync', [\App\Http\Controllers\Admin\AdminController::class, 'sync'])->name('sync.all');
    Route::get('/sekolah', [\App\Http\Controllers\Admin\SekolahController::class, 'index'])->name('sekolah.index');
    Route::put('/sekolah', [\App\Http\Controllers\Admin\SekolahController::class, 'update'])->name('sekolah.update');
    Route::put('/sekolah/kepsek', [\App\Http\Controllers\Admin\SekolahController::class, 'updateKepsek'])->name('sekolah.updateKepsek');
    Route::post('/guru/generate-user', [\App\Http\Controllers\Admin\GuruController::class, 'generateUser'])->name('guru.generate-user');
    Route::resource('guru', \App\Http\Controllers\Admin\GuruController::class);
    Route::post('/siswa/generate-user', [\App\Http\Controllers\Admin\SiswaController::class, 'generateUser'])->name('siswa.generate-user');
    Route::resource('siswa', \App\Http\Controllers\Admin\SiswaController::class);
    Route::post('/rombel/{rombel}/anggota', [\App\Http\Controllers\Admin\RombelController::class, 'tambahAnggota'])->name('rombel.anggota.store');
    Route::delete('/rombel/{rombel}/anggota/{siswa}', [\App\Http\Controllers\Admin\RombelController::class, 'hapusAnggota'])->name('rombel.anggota.destroy');
    Route::resource('rombel', \App\Http\Controllers\Admin\RombelController::class);
    
    Route::post('/ekskul/{ekskul}/anggota', [\App\Http\Controllers\Admin\EkstrakurikulerController::class, 'tambahAnggota'])->name('ekskul.anggota.store');
    Route::delete('/ekskul/{ekskul}/anggota/{nilai}', [\App\Http\Controllers\Admin\EkstrakurikulerController::class, 'hapusAnggota'])->name('ekskul.anggota.destroy');
    Route::resource('ekskul', \App\Http\Controllers\Admin\EkstrakurikulerController::class);
    
    Route::resource('kelompok_mapel', \App\Http\Controllers\Admin\KelompokMapelController::class);
    
    Route::get('/mapping_rapor', [\App\Http\Controllers\Admin\MappingRaporController::class, 'index'])->name('mapping_rapor.index');
    Route::post('/mapping_rapor', [\App\Http\Controllers\Admin\MappingRaporController::class, 'update'])->name('mapping_rapor.update');

    Route::get('/tanggal_rapor', [\App\Http\Controllers\Admin\TanggalRaporController::class, 'index'])->name('tanggal_rapor.index');
    Route::post('/tanggal_rapor', [\App\Http\Controllers\Admin\TanggalRaporController::class, 'update'])->name('tanggal_rapor.update');

    // Custom User Routes
    Route::post('users/generate-all', [\App\Http\Controllers\Admin\UserController::class, 'generateAll'])->name('users.generate_all');
    Route::post('users/{user}/reset-password', [\App\Http\Controllers\Admin\UserController::class, 'resetPassword'])->name('users.reset_password');
    Route::post('users/{user}/toggle-active', [\App\Http\Controllers\Admin\UserController::class, 'toggleActive'])->name('users.toggle_active');
    Route::post('users/{user}/reset-login', [\App\Http\Controllers\Admin\UserController::class, 'resetLogin'])->name('users.reset_login');
    
    Route::resource('users', \App\Http\Controllers\Admin\UserController::class);
    Route::resource('mapel', \App\Http\Controllers\Admin\MataPelajaranController::class);
    Route::resource('pembelajaran', \App\Http\Controllers\Admin\PembelajaranController::class);
    Route::resource('p5/tema', \App\Http\Controllers\Admin\P5TemaController::class)->names('p5.tema');
    Route::resource('p5/proyek', \App\Http\Controllers\Admin\P5ProyekController::class)->names('p5.proyek');
    
    // Kelompok Kokurikuler
    Route::post('p5/kelompok/{kelompok}/anggota', [\App\Http\Controllers\Admin\P5KelompokController::class, 'tambahAnggota'])->name('p5.kelompok.anggota.store');
    Route::delete('p5/kelompok/{kelompok}/anggota/{siswa}', [\App\Http\Controllers\Admin\P5KelompokController::class, 'hapusAnggota'])->name('p5.kelompok.anggota.destroy');
    Route::post('p5/kelompok/{kelompok}/kegiatan', [\App\Http\Controllers\Admin\P5KelompokController::class, 'tambahKegiatan'])->name('p5.kelompok.kegiatan.store');
    Route::delete('p5/kelompok/{kelompok}/kegiatan/{kegiatan}', [\App\Http\Controllers\Admin\P5KelompokController::class, 'hapusKegiatan'])->name('p5.kelompok.kegiatan.destroy');
    Route::resource('p5/kelompok', \App\Http\Controllers\Admin\P5KelompokController::class)->names('p5.kelompok');

    // Referensi P5
    Route::prefix('p5/referensi')->name('p5.referensi.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\P5ReferensiController::class, 'index'])->name('index');
        
        Route::post('dimensi', [\App\Http\Controllers\Admin\P5ReferensiController::class, 'storeDimensi'])->name('dimensi.store');
        Route::put('dimensi/{dimensi}', [\App\Http\Controllers\Admin\P5ReferensiController::class, 'updateDimensi'])->name('dimensi.update');
        Route::delete('dimensi/{dimensi}', [\App\Http\Controllers\Admin\P5ReferensiController::class, 'destroyDimensi'])->name('dimensi.destroy');
        
        Route::post('elemen', [\App\Http\Controllers\Admin\P5ReferensiController::class, 'storeElemen'])->name('elemen.store');
        Route::put('elemen/{elemen}', [\App\Http\Controllers\Admin\P5ReferensiController::class, 'updateElemen'])->name('elemen.update');
        Route::delete('elemen/{elemen}', [\App\Http\Controllers\Admin\P5ReferensiController::class, 'destroyElemen'])->name('elemen.destroy');
        
        Route::post('sub-elemen', [\App\Http\Controllers\Admin\P5ReferensiController::class, 'storeSubElemen'])->name('sub_elemen.store');
        Route::put('sub-elemen/{sub_elemen}', [\App\Http\Controllers\Admin\P5ReferensiController::class, 'updateSubElemen'])->name('sub_elemen.update');
        Route::delete('sub-elemen/{sub_elemen}', [\App\Http\Controllers\Admin\P5ReferensiController::class, 'destroySubElemen'])->name('sub_elemen.destroy');
    });

    // Referensi P5 (Kurikulum Lama)
    Route::prefix('referensi-p5')->name('referensi_p5.')->group(function () {
        // Data Projek
        Route::post('proyek/{proyek}/sub-elemen', [\App\Http\Controllers\Admin\ReferensiP5ProyekController::class, 'subElemen'])->name('proyek.sub_elemen');
        Route::resource('proyek', \App\Http\Controllers\Admin\ReferensiP5ProyekController::class)->names('proyek');

        // Kelompok Projek
        Route::post('kelompok/{kelompok}/anggota', [\App\Http\Controllers\Admin\ReferensiP5KelompokController::class, 'tambahAnggota'])->name('kelompok.anggota.store');
        Route::delete('kelompok/{kelompok}/anggota/{siswa}', [\App\Http\Controllers\Admin\ReferensiP5KelompokController::class, 'hapusAnggota'])->name('kelompok.anggota.destroy');
        Route::post('kelompok/{kelompok}/kegiatan', [\App\Http\Controllers\Admin\ReferensiP5KelompokController::class, 'tambahKegiatan'])->name('kelompok.kegiatan.store');
        Route::delete('kelompok/{kelompok}/kegiatan/{kegiatan}', [\App\Http\Controllers\Admin\ReferensiP5KelompokController::class, 'hapusKegiatan'])->name('kelompok.kegiatan.destroy');
        Route::resource('kelompok', \App\Http\Controllers\Admin\ReferensiP5KelompokController::class)->names('kelompok');
    });

    // Status Penilaian
    Route::prefix('status-penilaian')->name('status_penilaian.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\StatusPenilaianController::class, 'index'])->name('index');
        Route::get('/statistik-rapor', [\App\Http\Controllers\Admin\StatusPenilaianController::class, 'statistikRapor'])->name('statistik_rapor');
        Route::get('/statistik-p3', [\App\Http\Controllers\Admin\StatusPenilaianController::class, 'statistikP3'])->name('statistik_p3');
    });

    // Perkembangan Nilai
    Route::prefix('perkembangan-nilai')->name('perkembangan_nilai.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\PerkembanganNilaiController::class, 'index'])->name('index');
        Route::get('/capaian/{siswa_id}', [\App\Http\Controllers\Admin\PerkembanganNilaiController::class, 'capaian'])->name('capaian');
        Route::get('/deskripsi/{siswa_id}', [\App\Http\Controllers\Admin\PerkembanganNilaiController::class, 'deskripsi'])->name('deskripsi');
        Route::get('/grafik', [\App\Http\Controllers\Admin\PerkembanganNilaiController::class, 'grafik'])->name('grafik');
    });

    // Transkrip Ijazah
    Route::prefix('transkrip-ijazah')->name('transkrip_ijazah.')->group(function () {
        // 1. Update Nomor Ijazah Siswa
        Route::get('/import-nomor', [\App\Http\Controllers\Admin\TranskripIjazahController::class, 'importNomorIndex'])->name('import_nomor.index');
        Route::post('/import-nomor', [\App\Http\Controllers\Admin\TranskripIjazahController::class, 'importNomorStore'])->name('import_nomor.store');
        Route::get('/import-nomor/download-format', [\App\Http\Controllers\Admin\TranskripIjazahController::class, 'downloadFormatNomor'])->name('import_nomor.download_format');

        // 2. Setting Tampilan Transkrip
        Route::get('/setting', [\App\Http\Controllers\Admin\TranskripIjazahController::class, 'settingIndex'])->name('setting.index');
        Route::post('/setting', [\App\Http\Controllers\Admin\TranskripIjazahController::class, 'settingStore'])->name('setting.store');

        // 3. Mapping Mata Pelajaran
        Route::get('/mapping-mapel', [\App\Http\Controllers\Admin\TranskripIjazahController::class, 'mappingIndex'])->name('mapping_mapel.index');
        Route::post('/mapping-mapel', [\App\Http\Controllers\Admin\TranskripIjazahController::class, 'mappingStore'])->name('mapping_mapel.store');
        Route::delete('/mapping-mapel/{id}', [\App\Http\Controllers\Admin\TranskripIjazahController::class, 'mappingDestroy'])->name('mapping_mapel.destroy');

        // 4. Input / Import Nilai
        Route::get('/input-nilai', [\App\Http\Controllers\Admin\TranskripIjazahController::class, 'inputNilaiIndex'])->name('input_nilai.index');
        Route::post('/input-nilai', [\App\Http\Controllers\Admin\TranskripIjazahController::class, 'inputNilaiStore'])->name('input_nilai.store');
        
        Route::get('/import-nilai', [\App\Http\Controllers\Admin\TranskripIjazahController::class, 'importNilaiIndex'])->name('import_nilai.index');
        Route::post('/import-nilai', [\App\Http\Controllers\Admin\TranskripIjazahController::class, 'importNilaiStore'])->name('import_nilai.store');
        Route::get('/import-nilai/download-format', [\App\Http\Controllers\Admin\TranskripIjazahController::class, 'downloadFormatNilai'])->name('import_nilai.download_format');

        // 5. Cetak Transkrip
        Route::get('/cetak', [\App\Http\Controllers\Admin\TranskripIjazahController::class, 'cetakIndex'])->name('cetak.index');
        Route::get('/cetak/generate-kelas/{rombel_id}', [\App\Http\Controllers\Admin\TranskripIjazahController::class, 'generateKelas'])->name('cetak.generate_kelas');
        Route::get('/cetak/generate-siswa/{siswa_id}', [\App\Http\Controllers\Admin\TranskripIjazahController::class, 'generateSiswa'])->name('cetak.generate_siswa');
    });

    // Cetak Nilai
    Route::prefix('cetak-nilai')->name('cetak_nilai.')->group(function () {
        // 1. Leger Rapor
        Route::get('/leger-rapor', [\App\Http\Controllers\Admin\CetakNilaiController::class, 'legerIndex'])->name('leger_rapor.index');
        Route::get('/leger-rapor/download/{rombel_id}', [\App\Http\Controllers\Admin\CetakNilaiController::class, 'legerDownload'])->name('leger_rapor.download');
        Route::get('/leger-rapor/download-semua/{rombel_id}', [\App\Http\Controllers\Admin\CetakNilaiController::class, 'legerDownloadSemua'])->name('leger_rapor.download_semua');

        // 2. Pelengkap Rapor
        Route::get('/pelengkap-rapor', [\App\Http\Controllers\Admin\CetakNilaiController::class, 'pelengkapIndex'])->name('pelengkap_rapor.index');
        Route::post('/pelengkap-rapor/setting', [\App\Http\Controllers\Admin\CetakNilaiController::class, 'pelengkapStoreSetting'])->name('pelengkap_rapor.store_setting');
        Route::get('/pelengkap-rapor/generate-kelas/{rombel_id}', [\App\Http\Controllers\Admin\CetakNilaiController::class, 'pelengkapGenerateKelas'])->name('pelengkap_rapor.generate_kelas');
        Route::get('/pelengkap-rapor/generate-siswa/{siswa_id}', [\App\Http\Controllers\Admin\CetakNilaiController::class, 'pelengkapGenerateSiswa'])->name('pelengkap_rapor.generate_siswa');

        // 3. Nilai Rapor
        Route::get('/nilai-rapor', [\App\Http\Controllers\Admin\CetakNilaiController::class, 'nilaiRaporIndex'])->name('nilai_rapor.index');
        Route::get('/nilai-rapor/generate-kelas/{rombel_id}', [\App\Http\Controllers\Admin\CetakNilaiController::class, 'nilaiRaporGenerateKelas'])->name('nilai_rapor.generate_kelas');
        Route::get('/nilai-rapor/generate-siswa/{siswa_id}', [\App\Http\Controllers\Admin\CetakNilaiController::class, 'nilaiRaporGenerateSiswa'])->name('nilai_rapor.generate_siswa');

        // 4. Rapor P5
        Route::get('/rapor-p5', [\App\Http\Controllers\Admin\CetakNilaiController::class, 'raporP5Index'])->name('rapor_p5.index');
        Route::get('/rapor-p5/generate-kelas/{rombel_id}', [\App\Http\Controllers\Admin\CetakNilaiController::class, 'raporP5GenerateKelas'])->name('rapor_p5.generate_kelas');
        Route::get('/rapor-p5/generate-siswa/{siswa_id}', [\App\Http\Controllers\Admin\CetakNilaiController::class, 'raporP5GenerateSiswa'])->name('rapor_p5.generate_siswa');
    });

    // Backup & Restore
    Route::prefix('backup-restore')->name('backup_restore.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\BackupRestoreController::class, 'index'])->name('index');
        Route::post('/backup', [\App\Http\Controllers\Admin\BackupRestoreController::class, 'backup'])->name('backup');
        Route::get('/download/{filename}', [\App\Http\Controllers\Admin\BackupRestoreController::class, 'download'])->name('download');
        Route::post('/restore', [\App\Http\Controllers\Admin\BackupRestoreController::class, 'restore'])->name('restore');
        Route::post('/restore-sp', [\App\Http\Controllers\Admin\BackupRestoreController::class, 'restoreSp'])->name('restore_sp');
    });
});

// Guru Routes
Route::middleware(['auth', 'can:guru'])->prefix('guru')->name('guru.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Guru\GuruController::class, 'dashboard'])->name('dashboard');
    
    // Tujuan Pembelajaran Routes
    Route::get('tujuan-pembelajaran/import', [\App\Http\Controllers\Guru\TujuanPembelajaranController::class, 'importIndex'])->name('tujuan-pembelajaran.import_index');
    Route::post('tujuan-pembelajaran/import', [\App\Http\Controllers\Guru\TujuanPembelajaranController::class, 'importStore'])->name('tujuan-pembelajaran.import_store');
    Route::get('tujuan-pembelajaran/download-format', [\App\Http\Controllers\Guru\TujuanPembelajaranController::class, 'downloadFormat'])->name('tujuan-pembelajaran.download_format');
    Route::post('tujuan-pembelajaran/bulk', [\App\Http\Controllers\Guru\TujuanPembelajaranController::class, 'bulkStore'])->name('tujuan-pembelajaran.bulk');
    Route::resource('tujuan-pembelajaran', \App\Http\Controllers\Guru\TujuanPembelajaranController::class);
    
    // Nilai Rapor Routes
    Route::get('nilai/import', [\App\Http\Controllers\Guru\InputNilaiController::class, 'importIndex'])->name('nilai.import_index');
    Route::post('nilai/import', [\App\Http\Controllers\Guru\InputNilaiController::class, 'importStore'])->name('nilai.import_store');
    Route::get('nilai/download-format', [\App\Http\Controllers\Guru\InputNilaiController::class, 'downloadFormat'])->name('nilai.download_format');
    
    Route::get('nilai/deskripsi', [\App\Http\Controllers\Guru\InputNilaiController::class, 'deskripsi'])->name('nilai.deskripsi');
    Route::post('nilai/deskripsi', [\App\Http\Controllers\Guru\InputNilaiController::class, 'updateDeskripsi'])->name('nilai.update_deskripsi');
    
    Route::get('nilai', [\App\Http\Controllers\Guru\InputNilaiController::class, 'index'])->name('nilai.index');
    Route::post('nilai', [\App\Http\Controllers\Guru\InputNilaiController::class, 'store'])->name('nilai.store');

    // Nilai Tersimpan
    Route::get('nilai-tersimpan/rapor', [\App\Http\Controllers\Guru\NilaiTersimpanController::class, 'indexNilaiRapor'])->name('nilai-tersimpan.rapor');
    Route::delete('nilai-tersimpan/rapor/{id}', [\App\Http\Controllers\Guru\NilaiTersimpanController::class, 'destroyNilaiRapor'])->name('nilai-tersimpan.rapor.destroy');
    Route::get('nilai-tersimpan/deskripsi', [\App\Http\Controllers\Guru\NilaiTersimpanController::class, 'indexDeskripsiRapor'])->name('nilai-tersimpan.deskripsi');
    Route::delete('nilai-tersimpan/deskripsi/{id}', [\App\Http\Controllers\Guru\NilaiTersimpanController::class, 'destroyDeskripsiRapor'])->name('nilai-tersimpan.deskripsi.destroy');

    // Nilai Ekskul (Khusus Pembina)
    Route::get('nilai-ekskul', [App\Http\Controllers\Guru\NilaiEkstrakurikulerController::class, 'index'])->name('nilai_ekskul.index');
    Route::post('nilai-ekskul', [App\Http\Controllers\Guru\NilaiEkstrakurikulerController::class, 'store'])->name('nilai_ekskul.store');
    Route::get('nilai-ekskul/download-format', [App\Http\Controllers\Guru\NilaiEkstrakurikulerController::class, 'downloadFormat'])->name('nilai_ekskul.download_format');
    Route::post('nilai-ekskul/import', [App\Http\Controllers\Guru\NilaiEkstrakurikulerController::class, 'importStore'])->name('nilai_ekskul.import_store');
    Route::delete('nilai-ekskul/{id}', [App\Http\Controllers\Guru\NilaiEkstrakurikulerController::class, 'destroy'])->name('nilai_ekskul.destroy');

    // Nilai P5 (Khusus Koordinator)
    Route::prefix('nilai-p5')->name('nilai_p5.')->group(function () {
        Route::get('input-capaian', [App\Http\Controllers\Guru\NilaiP5Controller::class, 'inputCapaian'])->name('input_capaian');
        Route::post('input-capaian', [App\Http\Controllers\Guru\NilaiP5Controller::class, 'storeCapaian'])->name('store_capaian');
        
        Route::get('import-capaian', [App\Http\Controllers\Guru\NilaiP5Controller::class, 'importCapaian'])->name('import_capaian');
        Route::post('import-capaian', [App\Http\Controllers\Guru\NilaiP5Controller::class, 'storeImportCapaian'])->name('store_import_capaian');
        Route::get('download-format', [App\Http\Controllers\Guru\NilaiP5Controller::class, 'downloadFormatImport'])->name('download_format');
        
        Route::get('input-catatan', [App\Http\Controllers\Guru\NilaiP5Controller::class, 'inputCatatan'])->name('input_catatan');
        Route::post('input-catatan', [App\Http\Controllers\Guru\NilaiP5Controller::class, 'storeCatatan'])->name('store_catatan');
        Route::post('reset-catatan', [App\Http\Controllers\Guru\NilaiP5Controller::class, 'resetCatatan'])->name('reset_catatan');
        
        Route::get('download-capaian', [App\Http\Controllers\Guru\NilaiP5Controller::class, 'downloadCapaian'])->name('download_capaian');
        Route::get('export-capaian', [App\Http\Controllers\Guru\NilaiP5Controller::class, 'exportCapaian'])->name('export_capaian');
    });

    // Nilai Kokurikuler (Khusus Koordinator >= 2025/2026)
    Route::prefix('nilai-kokurikuler')->name('nilai_kokurikuler.')->group(function () {
        Route::get('input-nilai', [App\Http\Controllers\Guru\NilaiKokurikulerController::class, 'index'])->name('index');
        Route::post('input-nilai', [App\Http\Controllers\Guru\NilaiKokurikulerController::class, 'store'])->name('store');
        
        Route::get('import', [App\Http\Controllers\Guru\NilaiKokurikulerController::class, 'importIndex'])->name('import_index');
        Route::post('import', [App\Http\Controllers\Guru\NilaiKokurikulerController::class, 'importStore'])->name('import_store');
        Route::get('download-format', [App\Http\Controllers\Guru\NilaiKokurikulerController::class, 'downloadFormat'])->name('download_format');
        
        Route::get('deskripsi', [App\Http\Controllers\Guru\NilaiKokurikulerController::class, 'deskripsiIndex'])->name('deskripsi_index');
        Route::post('deskripsi', [App\Http\Controllers\Guru\NilaiKokurikulerController::class, 'storeDeskripsi'])->name('store_deskripsi');
        Route::post('generate-deskripsi', [App\Http\Controllers\Guru\NilaiKokurikulerController::class, 'generateDeskripsi'])->name('generate_deskripsi');
    });

    // Cek Penilaian
    Route::prefix('cek-penilaian')->name('cek_penilaian.')->group(function () {
        Route::get('status', [App\Http\Controllers\Guru\CekPenilaianController::class, 'status'])->name('status');
        Route::get('capaian', [App\Http\Controllers\Guru\CekPenilaianController::class, 'capaian'])->name('capaian');
        Route::get('grafik', [App\Http\Controllers\Guru\CekPenilaianController::class, 'grafik'])->name('grafik');
    });

    // Nilai DPL K2013 (>= 2025/2026)
    Route::get('nilai-dpl', [\App\Http\Controllers\Guru\NilaiDplController::class, 'index'])->name('nilai_dpl.index');
    Route::post('nilai-dpl', [\App\Http\Controllers\Guru\NilaiDplController::class, 'store'])->name('nilai_dpl.store');
    Route::get('nilai-dpl/import', [\App\Http\Controllers\Guru\NilaiDplController::class, 'importIndex'])->name('nilai_dpl.import_index');
    Route::post('nilai-dpl/import', [\App\Http\Controllers\Guru\NilaiDplController::class, 'importStore'])->name('nilai_dpl.import_store');
    Route::get('nilai-dpl/download-format', [\App\Http\Controllers\Guru\NilaiDplController::class, 'downloadFormat'])->name('nilai_dpl.download_format');

    // Nilai P3 K13 (< 2025/2026)
    Route::get('nilai-p3', [\App\Http\Controllers\Guru\NilaiP3Controller::class, 'index'])->name('nilai_p3.index');
    Route::post('nilai-p3', [\App\Http\Controllers\Guru\NilaiP3Controller::class, 'store'])->name('nilai_p3.store');
    Route::get('nilai-p3/import', [\App\Http\Controllers\Guru\NilaiP3Controller::class, 'importIndex'])->name('nilai_p3.import_index');
    Route::post('nilai-p3/import', [\App\Http\Controllers\Guru\NilaiP3Controller::class, 'importStore'])->name('nilai_p3.import_store');
    Route::get('nilai-p3/download-format', [\App\Http\Controllers\Guru\NilaiP3Controller::class, 'downloadFormat'])->name('nilai_p3.download_format');
});

// Siswa Routes
Route::middleware(['auth', 'can:siswa'])->prefix('siswa')->name('siswa.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Siswa\SiswaController::class, 'dashboard'])->name('dashboard');
    Route::get('cetak', [\App\Http\Controllers\Siswa\SiswaController::class, 'cetak'])->name('cetak');
    Route::get('cetak-pelengkap', [\App\Http\Controllers\Siswa\SiswaController::class, 'cetakPelengkap'])->name('cetak_pelengkap');
    Route::get('cetak-p5', [\App\Http\Controllers\Siswa\SiswaController::class, 'cetakP5'])->name('cetak_p5');
    
    Route::get('rekap-nilai', [\App\Http\Controllers\Siswa\SiswaController::class, 'rekapNilai'])->name('rekap_nilai');
    Route::get('rekap-deskripsi', [\App\Http\Controllers\Siswa\SiswaController::class, 'rekapDeskripsi'])->name('rekap_deskripsi');
    
    Route::get('download-rapor', [\App\Http\Controllers\Siswa\SiswaController::class, 'downloadRapor'])->name('download_rapor');
});

// Wali Kelas Routes
Route::middleware(['auth', 'can:guru'])->prefix('wali-kelas')->name('walikelas.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\WaliKelas\WaliKelasController::class, 'dashboard'])->name('dashboard');
    Route::prefix('cetak-nilai')->name('cetak_nilai.')->group(function () {
        // Leger
        Route::get('leger', [\App\Http\Controllers\WaliKelas\CetakRaporController::class, 'leger'])->name('leger');
        Route::get('leger/download', [\App\Http\Controllers\WaliKelas\CetakRaporController::class, 'downloadLeger'])->name('leger.download');
        
        // Pelengkap
        Route::get('pelengkap', [\App\Http\Controllers\WaliKelas\CetakRaporController::class, 'pelengkapIndex'])->name('pelengkap_index');
        Route::get('pelengkap/generate/{siswa_id?}', [\App\Http\Controllers\WaliKelas\CetakRaporController::class, 'generatePelengkap'])->name('pelengkap.generate');
        Route::post('pelengkap/toggle-publikasi', [\App\Http\Controllers\WaliKelas\CetakRaporController::class, 'togglePelengkap'])->name('pelengkap.toggle');

        // Nilai Rapor
        Route::get('rapor', [\App\Http\Controllers\WaliKelas\CetakRaporController::class, 'raporIndex'])->name('rapor_index');
        Route::get('rapor/generate/{siswa_id?}', [\App\Http\Controllers\WaliKelas\CetakRaporController::class, 'generateRapor'])->name('rapor.generate');
        Route::post('rapor/toggle-publikasi', [\App\Http\Controllers\WaliKelas\CetakRaporController::class, 'toggleRapor'])->name('rapor.toggle');

        // Rapor P5
        Route::get('rapor-p5', [\App\Http\Controllers\WaliKelas\CetakRaporController::class, 'raporP5Index'])->name('rapor_p5_index');
        Route::get('rapor-p5/generate/{siswa_id?}', [\App\Http\Controllers\WaliKelas\CetakRaporController::class, 'generateRaporP5'])->name('rapor_p5.generate');
        Route::post('rapor-p5/toggle-publikasi', [\App\Http\Controllers\WaliKelas\CetakRaporController::class, 'toggleRaporP5'])->name('rapor_p5.toggle');
    });

    Route::get('data-siswa', [\App\Http\Controllers\WaliKelas\DataSiswaController::class, 'index'])->name('data_siswa.index');
    Route::post('data-siswa/{id}', [\App\Http\Controllers\WaliKelas\DataSiswaController::class, 'update'])->name('data_siswa.update');

    Route::get('kehadiran', [\App\Http\Controllers\WaliKelas\KehadiranController::class, 'index'])->name('kehadiran.index');
    Route::post('kehadiran', [\App\Http\Controllers\WaliKelas\KehadiranController::class, 'store'])->name('kehadiran.store');

    Route::get('ekskul', [\App\Http\Controllers\WaliKelas\EkstrakurikulerController::class, 'index'])->name('ekskul.index');
    Route::post('ekskul', [\App\Http\Controllers\WaliKelas\EkstrakurikulerController::class, 'store'])->name('ekskul.store');
    Route::delete('ekskul/{id}', [\App\Http\Controllers\WaliKelas\EkstrakurikulerController::class, 'destroy'])->name('ekskul.destroy');
    Route::get('ekskul/import', [\App\Http\Controllers\WaliKelas\EkstrakurikulerController::class, 'importIndex'])->name('ekskul.import_index');
    Route::post('ekskul/import', [\App\Http\Controllers\WaliKelas\EkstrakurikulerController::class, 'importStore'])->name('ekskul.import_store');
    Route::get('ekskul/download-format', [\App\Http\Controllers\WaliKelas\EkstrakurikulerController::class, 'downloadFormat'])->name('ekskul.download_format');

    Route::get('catatan', [\App\Http\Controllers\WaliKelas\CatatanController::class, 'index'])->name('catatan.index');
    Route::post('catatan', [\App\Http\Controllers\WaliKelas\CatatanController::class, 'store'])->name('catatan.store');

    Route::get('kenaikan', [\App\Http\Controllers\WaliKelas\KenaikanKelasController::class, 'index'])->name('kenaikan.index');
    Route::post('kenaikan', [\App\Http\Controllers\WaliKelas\KenaikanKelasController::class, 'store'])->name('kenaikan.store');

    Route::get('deskripsi-p3', [\App\Http\Controllers\WaliKelas\DeskripsiP3Controller::class, 'index'])->name('deskripsi_p3.index');
    Route::post('deskripsi-p3', [\App\Http\Controllers\WaliKelas\DeskripsiP3Controller::class, 'store'])->name('deskripsi_p3.store');
    Route::post('deskripsi-p3/generate', [\App\Http\Controllers\WaliKelas\DeskripsiP3Controller::class, 'generate'])->name('deskripsi_p3.generate');

    Route::prefix('cek-penilaian-kelas')->name('cek_penilaian_kelas.')->group(function () {
        Route::get('status', [\App\Http\Controllers\WaliKelas\CekPenilaianKelasController::class, 'status'])->name('status');
        Route::get('statistik-rapor', [\App\Http\Controllers\WaliKelas\CekPenilaianKelasController::class, 'statistikRapor'])->name('statistik_rapor');
        Route::get('statistik-p3', [\App\Http\Controllers\WaliKelas\CekPenilaianKelasController::class, 'statistikP3'])->name('statistik_p3');
    });

    Route::get('deskripsi-dpl', [\App\Http\Controllers\WaliKelas\DeskripsiDplController::class, 'index'])->name('deskripsi_dpl.index');
    Route::post('deskripsi-dpl', [\App\Http\Controllers\WaliKelas\DeskripsiDplController::class, 'store'])->name('deskripsi_dpl.store');
    Route::post('deskripsi-dpl/generate', [\App\Http\Controllers\WaliKelas\DeskripsiDplController::class, 'generate'])->name('deskripsi_dpl.generate');

    Route::prefix('transkrip-ijazah')->name('transkrip_ijazah.')->group(function () {
        Route::get('input-nilai', [\App\Http\Controllers\WaliKelas\TranskripIjazahController::class, 'inputNilai'])->name('input_nilai');
        Route::post('input-nilai', [\App\Http\Controllers\WaliKelas\TranskripIjazahController::class, 'storeNilai'])->name('store_nilai');
        Route::get('import-nilai', [\App\Http\Controllers\WaliKelas\TranskripIjazahController::class, 'importNilai'])->name('import_nilai');
        Route::post('import-nilai', [\App\Http\Controllers\WaliKelas\TranskripIjazahController::class, 'processImport'])->name('process_import');
        Route::get('download-format', [\App\Http\Controllers\WaliKelas\TranskripIjazahController::class, 'downloadFormat'])->name('download_format');
        Route::get('cetak', [\App\Http\Controllers\WaliKelas\TranskripIjazahController::class, 'cetak'])->name('cetak');
        Route::get('cetak-pdf/{siswa_id?}', [\App\Http\Controllers\WaliKelas\TranskripIjazahController::class, 'generatePdf'])->name('generate_pdf');
        Route::post('toggle-publikasi', [\App\Http\Controllers\WaliKelas\TranskripIjazahController::class, 'togglePublikasi'])->name('toggle_publikasi');
    });
});

// Kepala Sekolah Routes
Route::middleware(['auth', 'can:kepsek'])->prefix('kepsek')->name('kepsek.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Kepsek\DashboardController::class, 'index'])->name('dashboard');
    
    // Monitoring
    Route::get('/monitoring/guru', [\App\Http\Controllers\Kepsek\MonitoringController::class, 'guru'])->name('monitoring.guru');
    Route::get('/monitoring/siswa', [\App\Http\Controllers\Kepsek\MonitoringController::class, 'siswa'])->name('monitoring.siswa');
    Route::get('/monitoring/rombel', [\App\Http\Controllers\Kepsek\MonitoringController::class, 'rombel'])->name('monitoring.rombel');

    // Laporan
    Route::prefix('laporan')->name('laporan.')->group(function () {
        Route::get('/rapor', [\App\Http\Controllers\Kepsek\LaporanController::class, 'rapor'])->name('rapor');
        Route::get('/rapor/kelas/{rombel_id}', [\App\Http\Controllers\Kepsek\LaporanController::class, 'raporKelas'])->name('rapor_kelas');
        Route::get('/rapor/siswa/{siswa_id}', [\App\Http\Controllers\Kepsek\LaporanController::class, 'raporSiswa'])->name('rapor_siswa');
        
        Route::get('/rapor-p5', [\App\Http\Controllers\Kepsek\LaporanController::class, 'raporP5'])->name('rapor_p5');
        Route::get('/rapor-p5/kelas/{rombel_id}', [\App\Http\Controllers\Kepsek\LaporanController::class, 'raporP5Kelas'])->name('rapor_p5_kelas');
        Route::get('/rapor-p5/siswa/{siswa_id}', [\App\Http\Controllers\Kepsek\LaporanController::class, 'raporP5Siswa'])->name('rapor_p5_siswa');
        
        Route::get('/leger', [\App\Http\Controllers\Kepsek\LaporanController::class, 'leger'])->name('leger');
        Route::get('/leger/download/{rombel_id}', [\App\Http\Controllers\Kepsek\LaporanController::class, 'legerDownload'])->name('leger_download');
    });
});

require __DIR__.'/auth.php';
