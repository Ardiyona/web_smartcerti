<?php

namespace App\Http\Controllers;

use App\Models\pelatihanModel;
use App\Models\SertifikasiModel;
use App\Models\UserModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class JumlahMatakuliahController extends Controller
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


        return view('jumlahmatakuliah.index', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'activeMenu' => $activeMenu
        ]);
    }

    public function list()
    {
        $data = DB::table('mata_kuliah')
            ->leftJoin('detail_matakuliah_pelatihan', 'mata_kuliah.id_matakuliah', '=', 'detail_matakuliah_pelatihan.id_matakuliah')
            ->leftJoin('detail_matakuliah_sertifikasi', 'mata_kuliah.id_matakuliah', '=', 'detail_matakuliah_sertifikasi.id_matakuliah')
            ->select(
                'mata_kuliah.id_matakuliah',
                'mata_kuliah.nama_matakuliah',
                DB::raw('COUNT(DISTINCT detail_matakuliah_pelatihan.id_detail_matakuliah_pelatihan) as jumlah_pelatihan'),
                DB::raw('COUNT(DISTINCT detail_matakuliah_sertifikasi.id_detail_matakuliah_sertifikasi) as jumlah_sertifikasi')
            )
            ->groupBy('mata_kuliah.id_matakuliah', 'mata_kuliah.nama_matakuliah');
        
        return DataTables::of($data)
            ->addIndexColumn() // Tambahkan nomor urut
            ->make(true);
    }
    

}