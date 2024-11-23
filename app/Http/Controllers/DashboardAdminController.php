<?php

namespace App\Http\Controllers;

use App\Models\PelatihanModel;
use App\Models\SertifikasiModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardAdminController extends Controller
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

        // Kirim data ke view
        // return view('dashboardpimpinan', [
        //     'breadcrumb' => $breadcrumb,
        //     'activeMenu' => $activeMenu,
        //     'jumlahPelatihan' => $jumlahPelatihan,
        //     'jumlahSertifikasi' => $jumlahSertifikasi // Kirim jumlah sertifikasi ke view
        // ]);
        // }

        return view('dashboardpimpinan', [
            'breadcrumb' => $breadcrumb,
            'activeMenu' => $activeMenu,
            'jumlahPelatihan' => $jumlahPelatihan, // Total pelatihan
            'jumlahSertifikasi' => $jumlahSertifikasi, // Total sertifikasi
            'jumlahPelatihanUser' => $jumlahPelatihanUser, // Pelatihan user login
            'jumlahSertifikasiUser' => $jumlahSertifikasiUser // Sertifikasi user login
        ]);
        
}
}
