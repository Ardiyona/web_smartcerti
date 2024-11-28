<?php
namespace App\Http\Controllers;

use App\Models\PelatihanModel;
use App\Models\SertifikasiModel;
use App\Models\UserModel;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
{
    $breadcrumb = (object) [
        'title' => 'Selamat Datang',
        'list' => ['Home', 'Welcome']
    ];

    $activeMenu = 'dashboard';

    // Hitung jumlah data sertifikasi dosen
    $jumlahSertifikasi = SertifikasiModel::count();

    // Hitung jumlah data pelatihan dosen
    $jumlahPelatihan = PelatihanModel::count();

    // Hitung jumlah data pengguna
    $jumlahPengguna = UserModel::count();  // Menghitung jumlah pengguna yang terdaftar

    // Ambil ID pengguna yang sedang login
    $userId = Auth::id();

    // Hitung jumlah sertifikasi untuk user yang sedang login dari detail_peserta_sertifikasi
    $jumlahSertifikasiUser = SertifikasiModel::whereHas('detail_peserta_sertifikasi', function ($query) use ($userId) {
        $query->where('detail_peserta_sertifikasi.user_id', $userId); // Hindari ambigu
    })->count();

    // Hitung jumlah pelatihan untuk user yang sedang login dari detail_peserta_pelatihan
    $jumlahPelatihanUser = PelatihanModel::whereHas('detail_peserta_pelatihan', function ($query) use ($userId) {
        $query->where('detail_peserta_pelatihan.user_id', $userId); // Hindari ambigu
    })->count();

    // Mengirim data ke view
    return view('dashboard', compact(
        'breadcrumb', 
        'activeMenu', 
        'jumlahSertifikasi', 
        'jumlahPelatihan', 
        'jumlahPengguna', 
        'jumlahSertifikasiUser', 
        'jumlahPelatihanUser'
    ));
}

}