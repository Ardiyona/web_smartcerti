<?php

namespace App\Http\Controllers;

use App\Models\pelatihanModel;
use App\Models\VendorPelatihanModel;
use App\Models\VendorSertifikasiModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class PelatihanUserController extends Controller{
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Daftar pelatihan Dosen',
            'list'  => ['Home', 'pelatihan Dosen']
        ];

        $page = (object) [
            'title' => 'Daftar pelatihan yang terdaftar dalam sistem'
        ];

        $activeMenu = 'dashboardpimpinan';

        $vendorpelatihan = VendorpelatihanModel::all();
        $vendorPelatihan = VendorPelatihanModel::all();

        return view('pelatihanuser.index', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'vendorpelatihan' => $vendorpelatihan,
            'vendorPelatihan' => $vendorPelatihan,
            'activeMenu' => $activeMenu
        ]);
    }

    public function list()
    {
        // Mengambil data user beserta level
        /** @var User */
        $user = Auth::user();
        // Mengambil data pelatihan yang hanya dimiliki oleh user yang sedang login
        $pelatihans = $user->detail_peserta_pelatihan()
        ->select(
            'pelatihan.id_pelatihan',
            'pelatihan.id_vendor_pelatihan',
            'pelatihan.id_jenis_pelatihan',
            'pelatihan.id_periode',
            'pelatihan.nama_pelatihan',
            'pelatihan.lokasi',
            'pelatihan.level_pelatihan',
            'pelatihan.tanggal',
            'pelatihan.kuota_peserta',
            'pelatihan.biaya'
        )
        ->with([
            'vendor_pelatihan',
            'jenis_pelatihan',
            'periode',
            'bidang_minat_pelatihan',
            'mata_kuliah_pelatihan',
            // Mengambil detail hanya untuk user login
            'detail_peserta_pelatihan' => function ($query) use ($user) {
                $query->where('detail_peserta_pelatihan.user_id', $user->user_id);
            }
        ]);
        // Mengembalikan data dengan DataTables
        return DataTables::of($pelatihans)
        ->addIndexColumn()

        ->addColumn('bidang_minat', function ($pelatihan) {
            return $pelatihan->bidang_minat_pelatihan->pluck('nama_bidang_minat')->implode(', ');
        })
        ->addColumn('mata_kuliah', function ($pelatihan) {
            return $pelatihan->mata_kuliah_pelatihan->pluck('nama_matakuliah')->implode(', ');
        })
        ->addColumn('peserta_pelatihan', function ($pelatihan) {
            return $pelatihan->detail_peserta_pelatihan->pluck('nama_lengkap')->implode(', ');
        })

                ->make(true);
        }


}
    
