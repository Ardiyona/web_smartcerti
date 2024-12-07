<?php

namespace App\Http\Controllers;

use App\Models\pelatihanModel;
use App\Models\SertifikasiModel;
use App\Models\UserModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class JumlahBidangMinatController extends Controller
{
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Daftar Dosen',
            'list'  => ['Home', 'Daftar Dosen']
        ];

        $page = (object) [
            'title' => 'Daftar dosen yang terdaftar dalam sistem'
        ];

        $activeMenu = 'dashboardpimpinan';


        return view('jumlahbidangminat.index', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'activeMenu' => $activeMenu
        ]);
    }

    public function list()
    {
        $data = DB::table('bidang_minat')
            ->leftJoin('detail_bidang_minat_pelatihan', 'bidang_minat.id_bidang_minat', '=', 'detail_bidang_minat_pelatihan.id_bidang_minat')
            ->leftJoin('detail_bidang_minat_sertifikasi', 'bidang_minat.id_bidang_minat', '=', 'detail_bidang_minat_sertifikasi.id_bidang_minat')
            ->select(
                'bidang_minat.id_bidang_minat',
                'bidang_minat.nama_bidang_minat',
                DB::raw('COUNT(DISTINCT detail_bidang_minat_pelatihan.id_detail_bidang_minat_pelatihan) as jumlah_pelatihan'),
                DB::raw('COUNT(DISTINCT detail_bidang_minat_sertifikasi.id_detail_bidang_minat_sertifikasi) as jumlah_sertifikasi')
            )
            ->groupBy('bidang_minat.id_bidang_minat', 'bidang_minat.nama_bidang_minat');

        return DataTables::of($data)
            ->addIndexColumn() // Tambahkan nomor urut
            ->make(true);
    }
    

}