<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BidangMinatController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DatabaseController;
use App\Http\Controllers\DosenSertifikasiController;
use App\Http\Controllers\JenisSertifikasiController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LevelController;
use App\Http\Controllers\MataKuliahController;
use App\Http\Controllers\VendorPelatihanController;
use App\Http\Controllers\VendorSertifikasiController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\JenisPelatihanController;
use App\Http\Controllers\JumlahBidangMinatController;
use App\Http\Controllers\JumlahMatakuliahController;
use App\Http\Controllers\KompetensiProdiController;
use App\Http\Controllers\LandingPageController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\PelatihanController;
use App\Http\Controllers\PelatihanUserController;
use App\Http\Controllers\PenerimaanPermintaanController;
use App\Http\Controllers\PeriodeController;
use App\Http\Controllers\ProdiController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SemuaDosenController;
use App\Http\Controllers\SemuaPelatihanDosenController;
use App\Http\Controllers\SemuaSertifikasiController as ControllersSemuaSertifikasiController;
use App\Http\Controllers\SemuaSertifikasiDosenController;
use App\Http\Controllers\SertifikasiController;
use App\Http\Controllers\SertifikasiUserController;

Route::pattern('id', '[0-9]+'); // artinya ketika ada parameter {id}, maka harus berupa angka

Route::get('/', [LandingPageController::class, 'index'])->name('landingpage'); // Mengarahkan ke tampilan utama
// Route untuk halaman index database
Route::get('/database', [PageController::class, 'index'])->name('database.index');
// Route untuk halaman index Gamedev
Route::get('/gamedev', [PageController::class, 'gamedev'])->name('gamedev.index');
// Route untuk halaman index iot
Route::get('/iot', [PageController::class, 'iot'])->name('iot.index');
// Route untuk halaman index ar
Route::get('/ar', [PageController::class, 'ar'])->name('ar.index');
// Route untuk halaman index machine learning
Route::get('/machinelearning', [PageController::class, 'machinelearning'])->name('machinelearning.index');
// Route untuk halaman index bi
Route::get('/bi', [PageController::class, 'bi'])->name('bi.index');
// Route untuk halaman index computer network
Route::get('/network', [PageController::class, 'network'])->name('network.index');
// Route untuk halaman index big data
Route::get('/bigdata', [PageController::class, 'bigdata'])->name('bigdata.index');

Route::get('login', [AuthController::class, 'login'])->name('login');
Route::post('login', [AuthController::class, 'postlogin']);
Route::get('logout', [AuthController::class, 'logout'])->middleware('auth');

Route::middleware(['auth'])->group(function(){

Route::get('/dashboard', [DashboardController::class, 'index']);
Route::get('/semuasertifikasidosen', [SemuaSertifikasiDosenController::class, 'index']);
Route::post('/semuasertifikasidosen/list', [SemuaSertifikasiDosenController::class, 'list']);

Route::get('/semuadosen', [SemuaDosenController::class, 'index']);
Route::post('/semuadosen/list', [SemuaDosenController::class, 'list']);
Route::get('/semuadosen/list', [SemuaDosenController::class, 'list'])->name('semuadosen.list');

Route::get('/semuapelatihandosen', [SemuaPelatihanDosenController::class, 'index']);
Route::post('/semuapelatihandosen/list', [SemuaPelatihanDosenController::class, 'list']);

Route::get('/pelatihanuser', [PelatihanUserController::class, 'index']);
Route::post('/pelatihanuser/list', [PelatihanUserController::class, 'list']);

Route::get('/sertifikasiuser', [SertifikasiUserController::class, 'index']);
Route::post('/sertifikasiuser/list', [SertifikasiUserController::class, 'list']);

//dashboard matakuliah
Route::get('/jumlahmatakuliah', [JumlahMatakuliahController::class, 'index']);
Route::get('/jumlahmatakuliah/list', [JumlahMatakuliahController::class, 'list'])->name('jumlahmatakuliah.list');


//dashboard bidangminat
Route::get('/jumlahbidangminat', [JumlahBidangMinatController::class, 'index']);
Route::get('/jumlahbidangminat/list', [JumlahBidangMinatController::class, 'list'])->name('jumlahbidangminat.list');

// Menampilkan halaman profil
Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
// Update data profil (username, email, dll)
Route::put('/profile/{id}', [ProfileController::class, 'updateProfile'])->name('profile.updateProfile');
// Update foto profil
Route::put('/profile/{id}/avatar', [ProfileController::class, 'updateAvatar'])->name('profile.updateAvatar');
// Update password
Route::put('/profile/{id}/password', [ProfileController::class, 'updatePassword'])->name('profile.updatePassword');


// user
Route::group(['prefix' => 'user', 'middleware' => 'authorize:ADM'], function() {
    Route::get('/', [UserController::class, 'index']);        
    Route::post('/list', [UserController::class, 'list']);  
    Route::get('/create', [UserController::class, 'create']);   
    Route::post('/store', [UserController::class, 'store']); 
    Route::get('/{id}/show', [UserController::class, 'show']);  // Perbaikan URL
    Route::get('/{id}/edit', [UserController::class, 'edit']); 
    Route::put('/{id}/update', [UserController::class, 'update']); // Perbaikan URL
    Route::get('/{id}/confirm', [UserController::class, 'confirm']); 
    Route::delete('/{id}/delete', [UserController::class, 'delete']); 
    Route::get('/import', [UserController::class, 'import']);
    Route::post('/import_ajax', [UserController::class, 'import_ajax']);
});


//level
Route::group(['prefix' => 'level', 'middleware' => 'authorize:ADM'], function () {
    Route::get('/', [LevelController::class, 'index']);
    Route::post('/list', [LevelController::class, 'list']);
    Route::get('/create', [LevelController::class, 'create']);
    Route::post('/store', [LevelController::class, 'store']);
    Route::get('/{id}/show', [LevelController::class, 'show']);
    Route::get('/{id}/edit', [LevelController::class, 'edit']);
    Route::put('/{id}/update', [LevelController::class, 'update']);
    Route::get('/{id}/confirm', [LevelController::class, 'confirm']);
    Route::delete('/{id}/delete', [LevelController::class, 'delete']); 
});


Route::group(['prefix' => 'sertifikasi'], function () {
    Route::get('/', [SertifikasiController::class, 'index']);
    Route::post('/list', [SertifikasiController::class, 'list']);
    Route::get('/create', [SertifikasiController::class, 'create']);
    Route::post('/store', [SertifikasiController::class, 'store']);
    Route::get('/create_rekomendasi', [SertifikasiController::class, 'create_rekomendasi']);
    Route::post('/store_rekomendasi', [SertifikasiController::class, 'store_rekomendasi']);
    Route::post('/filter_peserta', [SertifikasiController::class, 'filterPeserta']);
    // Route::get('/{id}/create_rekomendasi_peserta', [SertifikasiController::class, 'create_rekomendasi_peserta']);
    // Route::put('/{id}/store_rekomendasi_peserta', [SertifikasiController::class, 'store_rekomendasi_peserta']);
    Route::get('/{id}/admin_show_edit', [SertifikasiController::class, 'admin_show_edit']);
    Route::get('/{id}/admin_detail', [SertifikasiController::class, 'admin_detail']);
    Route::put('/{id}/admin_show_update', [SertifikasiController::class, 'admin_show_update']);
    Route::get('/{id}/show', [SertifikasiController::class, 'show']);
    Route::get('/{id}/edit', [SertifikasiController::class, 'edit']);
    Route::put('/{id}/update', [SertifikasiController::class, 'update']);
    Route::get('/{id}/confirm', [SertifikasiController::class, 'confirm']);
    Route::delete('/{id}/delete', [SertifikasiController::class, 'delete']); 
});
Route::group(['prefix' => 'pelatihan'], function () {
    Route::get('/', [PelatihanController::class, 'index']);
    Route::post('/list', [PelatihanController::class, 'list']);
    Route::get('/create', [PelatihanController::class, 'create']);
    Route::post('/store', [PelatihanController::class, 'store']);
    Route::get('/create_rekomendasi', [PelatihanController::class, 'create_rekomendasi']);
    Route::post('/store_rekomendasi', [PelatihanController::class, 'store_rekomendasi']);
    Route::post('/filter_peserta', [PelatihanController::class, 'filterPeserta']);
    // Route::get('/{id}/create_rekomendasi_peserta', [PelatihanController::class, 'create_rekomendasi_peserta']);
    // Route::put('/{id}/store_rekomendasi_peserta', [PelatihanController::class, 'store_rekomendasi_peserta']);
    Route::get('/{id}/admin_detail', [PelatihanController::class, 'admin_detail']);
    Route::get('/{id}/admin_show_edit', [PelatihanController::class, 'admin_show_edit']);
    Route::put('/{id}/admin_show_update', [PelatihanController::class, 'admin_show_update']);
    Route::get('/{id}/show', [PelatihanController::class, 'show']);
    Route::get('/{id}/edit', [PelatihanController::class, 'edit']);
    Route::put('/{id}/update', [PelatihanController::class, 'update']);
    Route::get('/{id}/confirm', [PelatihanController::class, 'confirm']);
    Route::delete('/{id}/delete', [PelatihanController::class, 'delete']); 
    Route::get('/{id}/generate', [PelatihanController::class, 'generate']); 
});

//Route Mata Kuliah
Route::group(['prefix' => 'matakuliah'], function () {
    Route::get('/', [MataKuliahController::class, 'index']);
    Route::post('/list', [MataKuliahController::class, 'list']);
    Route::get('/create', [MataKuliahController::class, 'create']);
    Route::post('/store', [MataKuliahController::class, 'store']);
    Route::get('/{id}/show', [MataKuliahController::class, 'show']);
    Route::get('/{id}/edit', [MataKuliahController::class, 'edit']);
    Route::put('/{id}/update', [MataKuliahController::class, 'update']);
    Route::get('/{id}/confirm', [MataKuliahController::class, 'confirm']);
    Route::delete('/{id}/delete', [MataKuliahController::class, 'delete']);
    Route::get('/import', [MataKuliahController::class, 'import']);
    Route::post('/import_ajax', [MataKuliahController::class, 'import_ajax']);
});

//Route Vendor Pelatihan
Route::group(['prefix' => 'vendorpelatihan'], function () {
    Route::get('/', [VendorPelatihanController::class, 'index']);
    Route::post('/list', [VendorPelatihanController::class, 'list']);
    Route::get('/create', [VendorPelatihanController::class, 'create']);
    Route::post('/store', [VendorPelatihanController::class, 'store']);
    Route::get('/{id}/show', [VendorPelatihanController::class, 'show']);
    Route::get('/{id}/edit', [VendorPelatihanController::class, 'edit']);
    Route::put('/{id}/update', [VendorPelatihanController::class, 'update']);
    Route::get('/{id}/confirm', [VendorPelatihanController::class, 'confirm']);
    Route::delete('/{id}/delete', [VendorPelatihanController::class, 'delete']);
    Route::get('/import', [VendorPelatihanController::class, 'import']);
    Route::post('/import_ajax', [VendorPelatihanController::class, 'import_ajax']);
});
//Route Vendor Sertifikasi
Route::group(['prefix' => 'vendorsertifikasi'], function () {
    Route::get('/', [VendorSertifikasiController::class, 'index']);
    Route::post('/list', [VendorSertifikasiController::class, 'list']);
    Route::get('/create', [VendorSertifikasiController::class, 'create']);
    Route::post('/store', [VendorSertifikasiController::class, 'store']);
    Route::get('/{id}/show', [VendorSertifikasiController::class, 'show']);
    Route::get('/{id}/edit', [VendorSertifikasiController::class, 'edit']);
    Route::put('/{id}/update', [VendorSertifikasiController::class, 'update']);
    Route::get('/{id}/confirm', [VendorSertifikasiController::class, 'confirm']);
    Route::delete('/{id}/delete', [VendorSertifikasiController::class, 'delete']);
    Route::get('/import', [VendorSertifikasiController::class, 'import']);
    Route::post('/import_ajax', [VendorSertifikasiController::class, 'import_ajax']);
});

//Route Jenis Sertifikasi
Route::group(['prefix' => 'jenissertifikasi'], function () {
    Route::get('/', [JenisSertifikasiController::class, 'index']);
    Route::post('/list', [JenisSertifikasiController::class, 'list']);
    Route::get('/create', [JenisSertifikasiController::class, 'create']);
    Route::post('/store', [JenisSertifikasiController::class, 'store']);
    Route::get('/{id}/show', [JenisSertifikasiController::class, 'show']);
    Route::get('/{id}/edit', [JenisSertifikasiController::class, 'edit']);
    Route::put('/{id}/update', [JenisSertifikasiController::class, 'update']);
    Route::get('/{id}/confirm', [JenisSertifikasiController::class, 'confirm']);
    Route::delete('/{id}/delete', [JenisSertifikasiController::class, 'delete']);
    Route::get('/import', [JenisSertifikasiController::class, 'import']);
    Route::post('/import_ajax', [JenisSertifikasiController::class, 'import_ajax']);
});



//Route Jenis Pelatihan
Route::group(['prefix' => 'jenispelatihan'], function () {
    Route::get('/', [JenisPelatihanController::class, 'index']);
    Route::post('/list', [JenisPelatihanController::class, 'list']);
    Route::get('/create', [JenisPelatihanController::class, 'create']);
    Route::post('/store', [JenisPelatihanController::class, 'store']);
    Route::get('/{id}/show', [JenisPelatihanController::class, 'show']);
    Route::get('/{id}/edit', [JenisPelatihanController::class, 'edit']);
    Route::put('/{id}/update', [JenisPelatihanController::class, 'update']);
    Route::get('/{id}/confirm', [JenisPelatihanController::class, 'confirm']);
    Route::delete('/{id}/delete', [JenisPelatihanController::class, 'delete']);
    Route::get('/export_pdf', [JenisPelatihanController::class, 'export_pdf']); 
    Route::get('/import', [JenisPelatihanController::class, 'import']);
    Route::post('/import_ajax', [JenisPelatihanController::class, 'import_ajax']);
});

Route::prefix('bidangminat')->group(function () {
    Route::get('/', [BidangMinatController::class, 'index']);
    Route::post('/list', [BidangMinatController::class, 'list']);
    Route::get('/create', [BidangMinatController::class, 'create']);
    Route::post('/store', [BidangMinatController::class, 'store']);
    Route::get('/{id}/show', [BidangMinatController::class, 'show']);
    Route::get('/{id}/edit', [BidangMinatController::class, 'edit']);
    Route::put('/{id}/update', [BidangMinatController::class, 'update']);
    Route::delete('/{id}/delete', [BidangMinatController::class, 'delete']);
    Route::get('/export_pdf', [BidangMinatController::class, 'export_pdf']);
    Route::post('/import_ajax', [BidangMinatController::class, 'import_ajax']);
    Route::get('/{id}/confirm', [BidangMinatController::class, 'confirm']);
    Route::get('/import', [BidangMinatController::class, 'import']);
    Route::post('/import_ajax', [BidangMinatController::class, 'import_ajax']);
});



Route::prefix('periode')->group(function () {
    Route::get('/', [PeriodeController::class, 'index']);
    Route::post('/list', [PeriodeController::class, 'list']);
    Route::get('/create', [PeriodeController::class, 'create']);
    Route::post('/store', [PeriodeController::class, 'store']);
    Route::get('/{id}/show', [PeriodeController::class, 'show']);
    Route::get('/{id}/edit', [PeriodeController::class, 'edit']);
    Route::put('/{id}/update', [PeriodeController::class, 'update']);
    Route::delete('/{id}/delete', [PeriodeController::class, 'delete']);
    Route::get('/export_pdf', [PeriodeController::class, 'export_pdf']);
    Route::post('/import_ajax', [PeriodeController::class, 'import_ajax']);
    Route::get('/{id}/confirm', [PeriodeController::class, 'confirm']);
});

Route::prefix('kompetensiprodi')->group(function () {
    Route::get('/', [KompetensiProdiController::class, 'index']);
    Route::post('/list', [KompetensiProdiController::class, 'list']);
    Route::get('/create', [KompetensiProdiController::class, 'create']);
    Route::post('/store', [KompetensiProdiController::class, 'store']);
    Route::get('/{id}/show', [KompetensiProdiController::class, 'show']);
    Route::get('/{id}/edit', [KompetensiProdiController::class, 'edit']);
    Route::put('/{id}/update', [KompetensiProdiController::class, 'update']);
    Route::delete('/{id}/delete', [KompetensiProdiController::class, 'delete']);
    Route::get('/export_pdf', [KompetensiProdiController::class, 'export_pdf']); // Jika ada fitur export
    Route::post('/import_ajax', [KompetensiProdiController::class, 'import_ajax']); // Jika ada fitur import
    Route::get('/{id}/confirm', [KompetensiProdiController::class, 'confirm']);
});

Route::prefix('prodi')->group(function () {
    Route::get('/', [ProdiController::class, 'index']); // Halaman utama
    Route::post('/list', [ProdiController::class, 'list']); // DataTables
    Route::get('/create', [ProdiController::class, 'create']); // Form create
    Route::post('/store', [ProdiController::class, 'store']); // Simpan data baru
    Route::get('/{id}/show', [ProdiController::class, 'show']); // Detail data
    Route::get('/{id}/edit', [ProdiController::class, 'edit']); // Form edit data
    Route::put('/{id}/update', [ProdiController::class, 'update']); // Update data
    Route::delete('/{id}/delete', [ProdiController::class, 'delete']); // Hapus data
    Route::get('/export_pdf', [ProdiController::class, 'export_pdf']); // Export ke PDF
    Route::post('/import_ajax', [ProdiController::class, 'import_ajax']); // Import data
    Route::get('/{id}/confirm', [ProdiController::class, 'confirm']); // Konfirmasi hapus
});


Route::get('/penerimaanpermintaan', [PenerimaanPermintaanController::class, 'index']);
Route::post('/penerimaanpermintaan/listSertifikasi', [PenerimaanPermintaanController::class, 'listSertifikasi']);
Route::post('/penerimaanpermintaan/listPelatihan', [PenerimaanPermintaanController::class, 'listPelatihan']);
Route::get('/penerimaanpermintaan/{id}/show_sertifikasi', [PenerimaanPermintaanController::class, 'show']);
Route::get('/penerimaanpermintaan/{id}/show_pelatihan', [PenerimaanPermintaanController::class, 'show']);
Route::put('/penerimaanpermintaan/{id}/status/{status}', [PenerimaanPermintaanController::class, 'updateStatus'])->name('penerimaanpermintaan.updateStatus');


Route::get('notifikasi_sertifikasi/{id}', [NotificationController::class, 'indexSertifikasi'])->name('notifikasi_sertifikasi.show');
Route::get('notifikasi_pelatihan/{id}', [NotificationController::class, 'indexPelatihan'])->name('notifikasi_pelatihan.show');

});

