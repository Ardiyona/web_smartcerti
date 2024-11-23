<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BidangMinatController;
use App\Http\Controllers\DashboardPimpinanController;
use App\Http\Controllers\DosenSertifikasiController;
use App\Http\Controllers\JenisSertifikasiController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LevelController;
use App\Http\Controllers\MataKuliahController;
use App\Http\Controllers\VendorPelatihanController;
use App\Http\Controllers\VendorSertifikasiController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\JenisPelatihanController;
use App\Http\Controllers\PelatihanController;
use App\Http\Controllers\PenerimaanPermintaanController;
use App\Http\Controllers\PeriodeController;
use App\Http\Controllers\PimpinanpelatihanDosenController;
use App\Http\Controllers\PimpinanSertifikasiDosenController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SertifikasiController;

Route::pattern('id', '[0-9]+'); // artinya ketika ada parameter {id}, maka harus berupa angka

Route::get('login', [AuthController::class, 'login'])->name('login');
Route::post('login', [AuthController::class, 'postlogin']);
Route::get('logout', [AuthController::class, 'logout'])->middleware('auth');

Route::middleware(['auth'])->group(function(){

Route::get('/', [DashboardPimpinanController::class, 'index']);

//Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
//    Route::patch('/{id}', [ProfileController::class, 'update'])->name('profile.update');
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
    Route::get('/{id}/admin_show_edit', [SertifikasiController::class, 'admin_show_edit']);
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
    Route::get('/{id}/show', [PelatihanController::class, 'show']);
    Route::get('/{id}/edit', [PelatihanController::class, 'edit']);
    Route::put('/{id}/update', [PelatihanController::class, 'update']);
    Route::get('/{id}/confirm', [PelatihanController::class, 'confirm']);
    Route::delete('/{id}/delete', [PelatihanController::class, 'delete']); 
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
    Route::post('/{id}/delete', [BidangMinatController::class, 'delete']);
    Route::get('/export_pdf', [BidangMinatController::class, 'export_pdf']);
    Route::post('/import_ajax', [BidangMinatController::class, 'import_ajax']);
    Route::get('/{id}/confirm', [BidangMinatController::class, 'confirm']);
});



Route::prefix('periode')->group(function () {
    Route::get('/', [PeriodeController::class, 'index']);
    Route::post('/list', [PeriodeController::class, 'list']);
    Route::get('/create', [PeriodeController::class, 'create']);
    Route::post('/store', [PeriodeController::class, 'store']);
    Route::get('/{id}/show', [PeriodeController::class, 'show']);
    Route::get('/{id}/edit', [PeriodeController::class, 'edit']);
    Route::put('/{id}/update', [PeriodeController::class, 'update']);
    Route::post('/{id}/delete', [PeriodeController::class, 'delete']);
    Route::get('/export_pdf', [PeriodeController::class, 'export_pdf']);
    Route::post('/import_ajax', [PeriodeController::class, 'import_ajax']);
    Route::get('/{id}/confirm', [PeriodeController::class, 'confirm']);
});


Route::get('/pimpinansertifikasidosen', [PimpinanSertifikasiDosenController::class, 'index']);
Route::post('/pimpinansertifikasidosen/list', [PimpinanSertifikasiDosenController::class, 'list']);

Route::get('/pimpinanpelatihandosen', [PimpinanpelatihanDosenController::class, 'index']);
Route::post('/pimpinanpelatihandosen/list', [PimpinanpelatihanDosenController::class, 'list']);

Route::get('/penerimaanpermintaan', [PenerimaanPermintaanController::class, 'index']);
Route::post('/penerimaanpermintaan/list', [PenerimaanPermintaanController::class, 'list']);


});

//tes clone

