<?php

namespace App\Http\Controllers;

use App\Models\pelatihanModel;
use App\Models\VendorPelatihanModel;
use App\Models\VendorSertifikasiModel;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class SemuaPelatihanDosenController extends Controller
{
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Daftar pelatihan Dosen',
            'list'  => ['Home', 'pelatihan Dosen']
        ];

        $page = (object) [
            'title' => 'Daftar pelatihan dosen yang terdaftar dalam sistem'
        ];

        $activeMenu = 'dashboardpimpinan';

        $vendorpelatihan = VendorpelatihanModel::all();
        $vendorPelatihan = VendorPelatihanModel::all();

        return view('semuapelatihandosen.index', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'vendorpelatihan' => $vendorpelatihan,
            'vendorPelatihan' => $vendorPelatihan,
            'activeMenu' => $activeMenu
        ]);
    }

    public function list()
    {
        // Mengambil semua data pelatihan dengan relasi
        $pelatihans = pelatihanModel::select(
            'id_pelatihan',
            'id_vendor_pelatihan',
            'id_jenis_pelatihan',
            'id_periode',
            'nama_pelatihan',
            'no_pelatihan',
            'lokasi',
            'level_pelatihan',
            'tanggal',
            'bukti_pelatihan',
            'masa_berlaku',
            'kuota_peserta',
            'biaya'
        )
            ->with('vendor_pelatihan', 'jenis_pelatihan', 'periode', 'bidang_minat_pelatihan', 'mata_kuliah_pelatihan', 'detail_peserta_pelatihan');
        

        return DataTables::of($pelatihans)
            ->addIndexColumn()
            ->addColumn('bidang_minat', function ($pelatihan) {
                return $pelatihan->bidang_minat_pelatihan
                    ? $pelatihan->bidang_minat_pelatihan->pluck('nama_bidang_minat')->implode(', ')
                    : '-';
            })
            ->addColumn('mata_kuliah', function ($pelatihan) {
                return $pelatihan->mata_kuliah_pelatihan
                    ? $pelatihan->mata_kuliah_pelatihan->pluck('nama_matakuliah')->implode(', ')
                    : '-';
            })
            ->addColumn('peserta_pelatihan', function ($pelatihan) {
                return $pelatihan->detail_peserta_pelatihan
                    ? $pelatihan->detail_peserta_pelatihan->pluck('nama_lengkap')->implode(', ')
                    : '-';
            })
            ->make(true);
    }
}
