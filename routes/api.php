<?php

use App\Http\Controllers\api\BidangMinatController;
use App\Http\Controllers\api\JenisPelatihanController;
use App\Http\Controllers\api\JenisSertifikasiController;
use App\Http\Controllers\api\LevelController;
use App\Http\Controllers\api\MataKuliahController;
use App\Http\Controllers\api\MyAccountController;
use App\Http\Controllers\api\NotificationController;
use App\Http\Controllers\api\PelatihanController;
use App\Http\Controllers\api\PenerimaanPermintaanController;
use App\Http\Controllers\api\PeriodeController;
use App\Http\Controllers\api\PimpinanPelatihanDosenController;
use App\Http\Controllers\api\PimpinanSertifikasiDosenController;
use App\Http\Controllers\api\ProfileController;
use App\Http\Controllers\api\SertifikasiController;
use App\Http\Controllers\api\UserController;
use App\Http\Controllers\api\VendorPelatihanController;
use App\Http\Controllers\api\VendorSertifikasiController;
use App\Http\Controllers\PimpinanpelatihanDosenController as ControllersPimpinanpelatihanDosenController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/




Route::post('/login', App\Http\Controllers\api\LoginController::class)->name('login');
Route::post('/logout', App\Http\Controllers\api\LogoutController::class)->name('logout');
Route::middleware('auth:api')->get('/user', function(Request $request){
    return $request->user();
});

Route::middleware(['auth:api'])->group(function () {
Route::get('levels', [LevelController::class, 'index']);
Route::post('levels', [LevelController::class, 'store']);
Route::get('levels/{level}', [LevelController::class, 'show']);
Route::put('levels/{level}', [LevelController::class, 'update']);
Route::delete('levels/{level}', [LevelController::class, 'destroy']);

Route::get('users', [UserController::class, 'index']);
Route::post('users', [UserController::class, 'store']);
Route::get('users/{user}', [UserController::class, 'show']);
Route::put('users/{user}', [UserController::class, 'update']);
Route::delete('users/{user}', [UserController::class, 'destroy']);

Route::get('bidangMinats', [BidangMinatController::class, 'index']);
Route::post('bidangMinats', [BidangMinatController::class, 'store']);
Route::get('bidangMinats/{bidangMinat}', [BidangMinatController::class, 'show']);
Route::put('bidangMinats/{bidangMinat}', [BidangMinatController::class, 'update']);
Route::delete('bidangMinats/{bidangMinat}', [BidangMinatController::class, 'destroy']);

Route::get('mataKuliahs', [MataKuliahController::class, 'index']);
Route::post('mataKuliahs', [MataKuliahController::class, 'store']);
Route::get('mataKuliahs/{mataKuliah}', [MataKuliahController::class, 'show']);
Route::put('mataKuliahs/{mataKuliah}', [MataKuliahController::class, 'update']);
Route::delete('mataKuliahs/{mataKuliah}', [MataKuliahController::class, 'destroy']);

Route::get('pelatihans', [PelatihanController::class, 'index']);
Route::post('pelatihans', [PelatihanController::class, 'store']);
Route::get('pelatihans/{pelatihan}', [PelatihanController::class, 'show']);
Route::put('pelatihans/{pelatihan}', [PelatihanController::class, 'update']);
Route::delete('pelatihans/{pelatihan}', [PelatihanController::class, 'destroy']);
Route::get('pelatihans/{user_id}', [PelatihanController::class, 'getPelatihanByUser']);




Route::get('sertifikasis', [SertifikasiController::class, 'index']);
Route::post('sertifikasis', [SertifikasiController::class, 'store']);
Route::get('sertifikasis/{sertifikasi}', [SertifikasiController::class, 'show']);
Route::put('sertifikasis/{sertifikasi}', [SertifikasiController::class, 'update']);
Route::delete('sertifikasis/{sertifikasi}', [SertifikasiController::class, 'destroy']);

Route::get('pimpinanPelatihans', [PimpinanPelatihanDosenController::class, 'index']);
Route::get('pimpinanSertifikasis', [PimpinanSertifikasiDosenController::class, 'index']);

Route::get('/penerimaanPelatihans', [PenerimaanPermintaanController::class, 'listPelatihan']);
Route::put('/penerimaanPelatihans/updateStatusPelatihan/{id_pelatihan}', [PenerimaanPermintaanController::class, 'updateStatusPelatihan']);
Route::put('/penerimaanSertifikasis/updateStatusSertifikasi/{id_sertifikasi}', [PenerimaanPermintaanController::class, 'updateStatusSertifikasi']);
Route::get('/penerimaanSertifikasis', [PenerimaanPermintaanController::class, 'listSertifikasi']);

Route::get('profiles', [ProfileController::class, 'index']);

Route::get('my_accounts', [MyAccountController::class, 'index']);
Route::put('my_accounts/update', [MyAccountController::class, 'update']);
Route::put('my_accounts/update_password', [MyAccountController::class, 'updatePassword']);

Route::get('vendorSertifikasi', [VendorSertifikasiController::class, 'index']);
Route::get('jenisSertifikasi', [JenisSertifikasiController::class, 'index']);
Route::get('vendorPelatihan', [VendorPelatihanController::class, 'index']);
Route::get('jenisPelatihan', [JenisPelatihanController::class, 'index']);
Route::get('bidangMinat', [BidangMinatController::class, 'index']);
Route::get('periodes', [PeriodeController::class, 'index']);
Route::get('mataKuliahs', [MataKuliahController::class, 'index']);


Route::get('notifikasi_sertifikasis', [NotificationController::class, 'index_sertifikasi']);
Route::get('notifikasi_pelatihans', [NotificationController::class, 'index_pelatihan']);
});
