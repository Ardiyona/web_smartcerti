<?php
// namespace App\Http\Controllers;

// use App\Models\PelatihanModel;
// use App\Models\SertifikasiModel;
// use App\Models\UserModel;
// use Illuminate\Support\Facades\Auth;

// class DashboardController extends Controller
// {
//     public function index()
// {
//     $breadcrumb = (object) [
//         'title' => 'Selamat Datang',
//         'list' => ['Home', 'Welcome']
//     ];

//     $activeMenu = 'dashboard';

//     // Hitung jumlah data sertifikasi dosen
//     $jumlahSertifikasi = SertifikasiModel::count();

//     // Hitung jumlah data pelatihan dosen
//     $jumlahPelatihan = PelatihanModel::count();

//     // Hitung jumlah data pengguna
//     $jumlahPengguna = UserModel::count();  // Menghitung jumlah pengguna yang terdaftar

//     // Ambil ID pengguna yang sedang login
//     $userId = Auth::id();

//     // Hitung jumlah sertifikasi untuk user yang sedang login dari detail_peserta_sertifikasi
//     $jumlahSertifikasiUser = SertifikasiModel::whereHas('detail_peserta_sertifikasi', function ($query) use ($userId) {
//         $query->where('detail_peserta_sertifikasi.user_id', $userId); // Hindari ambigu
//     })->count();

//     // Hitung jumlah pelatihan untuk user yang sedang login dari detail_peserta_pelatihan
//     $jumlahPelatihanUser = PelatihanModel::whereHas('detail_peserta_pelatihan', function ($query) use ($userId) {
//         $query->where('detail_peserta_pelatihan.user_id', $userId); // Hindari ambigu
//     })->count();

//     // Mengirim data ke view
//     return view('dashboard', compact(
//         'breadcrumb', 
//         'activeMenu', 
//         'jumlahSertifikasi', 
//         'jumlahPelatihan', 
//         'jumlahPengguna', 
//         'jumlahSertifikasiUser', 
//         'jumlahPelatihanUser'
//     ));
// }

// }

namespace App\Http\Controllers;

use App\Models\PelatihanModel;
use App\Models\SertifikasiModel;
use App\Models\UserModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;



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
        $jumlahPengguna = UserModel::count(); // Menghitung jumlah pengguna yang terdaftar

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

        // // Data Chart.js
        // $pelatihanData = PelatihanModel::with('periode')
        //     ->selectRaw('COUNT(*) as total, periode.tahun_periode as tahun')
        //     ->join('periode', 'pelatihan.id_periode', '=', 'periode.id_periode')
        //     ->groupBy('periode.tahun_periode')
        //     ->get();

        // $sertifikasiData = SertifikasiModel::with('periode')
        //     ->selectRaw('COUNT(*) as total, periode.tahun_periode as tahun')
        //     ->join('periode', 'sertifikasi.id_periode', '=', 'periode.id_periode')
        //     ->groupBy('periode.tahun_periode')
        //     ->get();

        // // Tahun yang unik dari kedua dataset
        // $years = $pelatihanData->pluck('tahun')->merge($sertifikasiData->pluck('tahun'))->unique()->sort()->values();

        // // Hitung jumlah berdasarkan tahun
        // $pelatihanCounts = $years->map(fn($year) => $pelatihanData->firstWhere('tahun', $year)->total ?? 0);
        // $sertifikasiCounts = $years->map(fn($year) => $sertifikasiData->firstWhere('tahun', $year)->total ?? 0);

        // // Data untuk Chart.js
        // $chartData = [
        //     'labels' => $years, // Tahun sebagai label
        //     'datasets' => [
        //         [
        //             'label' => 'Pelatihan',
        //             'data' => $pelatihanCounts,
        //             'backgroundColor' => 'rgba(54, 162, 235, 0.6)',
        //             'borderColor' => 'rgba(54, 162, 235, 1)',
        //             'borderWidth' => 1,
        //         ],
        //         [
        //             'label' => 'Sertifikasi',
        //             'data' => $sertifikasiCounts,
        //             'backgroundColor' => 'rgba(255, 99, 132, 0.6)',
        //             'borderColor' => 'rgba(255, 99, 132, 1)',
        //             'borderWidth' => 1,
        //         ],
        //     ],
        // ];


                // Data Chart.js
                $pelatihanData = PelatihanModel::with('periode')
                ->selectRaw('COUNT(*) as total, periode.tahun_periode as tahun')
                ->join('periode', 'pelatihan.id_periode', '=', 'periode.id_periode')
                ->groupBy('periode.tahun_periode')
                ->get();
    
            $sertifikasiData = SertifikasiModel::with('periode')
                ->selectRaw('COUNT(*) as total, periode.tahun_periode as tahun')
                ->join('periode', 'sertifikasi.id_periode', '=', 'periode.id_periode')
                ->groupBy('periode.tahun_periode')
                ->get();
    
            // Ambil semua tahun dari tabel periode (menghindari kehilangan tahun yang tidak ada di pelatihan atau sertifikasi)
            $allYears = DB::table('periode')->pluck('tahun_periode')->unique()->sort();
    
            // Hitung jumlah berdasarkan tahun, pastikan semua tahun dari $allYears digunakan
            $pelatihanCounts = $allYears->map(fn($year) => $pelatihanData->firstWhere('tahun', $year)->total ?? 0);
            $sertifikasiCounts = $allYears->map(fn($year) => $sertifikasiData->firstWhere('tahun', $year)->total ?? 0);
    
            // Data untuk Chart.js
            $chartData = [
                'labels' => $allYears, // Tahun sebagai label
                'datasets' => [
                    [
                        'label' => 'Pelatihan',
                        'data' => $pelatihanCounts,
                        'backgroundColor' => 'rgba(54, 162, 235, 0.6)',
                        'borderColor' => 'rgba(54, 162, 235, 1)',
                        'borderWidth' => 1,
                    ],
                    [
                        'label' => 'Sertifikasi',
                        'data' => $sertifikasiCounts,
                        'backgroundColor' => 'rgba(255, 99, 132, 0.6)',
                        'borderColor' => 'rgba(255, 99, 132, 1)',
                        'borderWidth' => 1,
                    ],
                ],
            ];
    

        // Mengirim data ke view
        return view('dashboard', compact(
            'breadcrumb',
            'activeMenu',
            'jumlahSertifikasi',
            'jumlahPelatihan',
            'jumlahPengguna',
            'jumlahSertifikasiUser',
            'jumlahPelatihanUser',
            'chartData'
        ));
    }
}