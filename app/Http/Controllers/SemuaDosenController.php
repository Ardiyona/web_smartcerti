<?php

namespace App\Http\Controllers;

use App\Models\pelatihanModel;
use App\Models\SertifikasiModel;
use App\Models\UserModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class SemuaDosenController extends Controller
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


        return view('semuadosen.index', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'activeMenu' => $activeMenu
        ]);
    }

    public function list()
    {
        $data = DB::table('user')
            ->leftJoin('detail_peserta_pelatihan', 'user.user_id', '=', 'detail_peserta_pelatihan.user_id')
            ->leftJoin('detail_peserta_sertifikasi', 'user.user_id', '=', 'detail_peserta_sertifikasi.user_id') // Add join for certifications
            ->select('users.user_id', 'user.nama_lengkap',
                DB::raw('COUNT(detail_peserta_pelatihan.id_detail_peserta_pelatihan) as jumlah_pelatihan'),
                DB::raw('COUNT(detail_peserta_sertifikasi.id_detail_peserta_sertifikasi) as jumlah_sertifikasi')
            )
            ->groupBy('user.user_id', 'user.nama_lengkap')
            ->get();

        return DataTables::of($data)
            ->addIndexColumn()
            ->make(true);
    }


}