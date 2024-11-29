<?php

// namespace App\Http\Controllers;

// use Illuminate\Http\Request;
// use App\Models\PelatihanModel;
// use App\Models\SertifikasiModel;

// class ChartController extends Controller
// {
//     public function getChartData()
//     {
//         $pelatihanData = PelatihanModel::with('periode')
//             ->selectRaw('COUNT(*) as total, periode.tahun_periode as tahun')
//             ->join('periode', 'pelatihan.id_periode', '=', 'periode.id_periode')
//             ->groupBy('periode.tahun_periode')
//             ->get();

//         $sertifikasiData = SertifikasiModel::with('periode')
//             ->selectRaw('COUNT(*) as total, periode.tahun_periode as tahun')
//             ->join('periode', 'sertifikasi.id_periode', '=', 'periode.id_periode')
//             ->groupBy('periode.tahun_periode')
//             ->get();

//         // Format data untuk Chart.js
//         $years = $pelatihanData->pluck('tahun')->merge($sertifikasiData->pluck('tahun'))->unique()->sort()->values();
//         $pelatihanCounts = $years->map(fn($year) => $pelatihanData->firstWhere('tahun', $year)->total ?? 0);
//         $sertifikasiCounts = $years->map(fn($year) => $sertifikasiData->firstWhere('tahun', $year)->total ?? 0);

//         return response()->json([
//             'years' => $years,
//             'pelatihan' => $pelatihanCounts,
//             'sertifikasi' => $sertifikasiCounts,
//         ]);
//     }
// }


// namespace App\Http\Controllers;

// use Illuminate\Http\Request;
// use App\Models\PelatihanModel;
// use App\Models\SertifikasiModel;

// class ChartController extends Controller
// {
//     public function index()
//     {
//         $pelatihanData = PelatihanModel::with('periode')
//             ->selectRaw('COUNT(*) as total, periode.tahun_periode as tahun')
//             ->join('periode', 'pelatihan.id_periode', '=', 'periode.id_periode')
//             ->groupBy('periode.tahun_periode')
//             ->get();

//         $sertifikasiData = SertifikasiModel::with('periode')
//             ->selectRaw('COUNT(*) as total, periode.tahun_periode as tahun')
//             ->join('periode', 'sertifikasi.id_periode', '=', 'periode.id_periode')
//             ->groupBy('periode.tahun_periode')
//             ->get();

//         $years = $pelatihanData->pluck('tahun')->merge($sertifikasiData->pluck('tahun'))->unique()->sort()->values();
//         $pelatihanCounts = $years->map(fn($year) => $pelatihanData->firstWhere('tahun', $year)->total ?? 0);
//         $sertifikasiCounts = $years->map(fn($year) => $sertifikasiData->firstWhere('tahun', $year)->total ?? 0);

//         return view('dashboardpimpinan', [
//             'years' => $years,
//             'pelatihanCounts' => $pelatihanCounts,
//             'sertifikasiCounts' => $sertifikasiCounts,
//         ]);
//     }
// }



namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PelatihanModel;
use App\Models\SertifikasiModel;

class ChartController extends Controller
{
    public function index()
    {
        // Breadcrumb
        $breadcrumb = (object) [
            'title' => 'Dashboard Pimpinan',
            'list'  => ['Home', 'Dashboard Pimpinan']
        ];

        // Halaman Metadata
        $page = (object) [
            'title' => 'Dashboard Pimpinan - Statistik Pelatihan dan Sertifikasi'
        ];

        // Active Menu
        $activeMenu = 'dashboardpimpinan';

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

        $years = $pelatihanData->pluck('tahun')->merge($sertifikasiData->pluck('tahun'))->unique()->sort()->values();
        $pelatihanCounts = $years->map(fn($year) => $pelatihanData->firstWhere('tahun', $year)->total ?? 0);
        $sertifikasiCounts = $years->map(fn($year) => $sertifikasiData->firstWhere('tahun', $year)->total ?? 0);
        dd($years);
        // Kirim data ke view
        return view('dashboardpimpinan', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'activeMenu' => $activeMenu,
            'years' => $years,
            'pelatihanCounts' => $pelatihanCounts,
            'sertifikasiCounts' => $sertifikasiCounts,
        ]);
    }
}
