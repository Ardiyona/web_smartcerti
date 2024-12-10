<?php

namespace App\Http\Controllers;

use App\Models\BidangMinatModel;
use App\Models\MataKuliahModel;
use App\Models\UserModel;
use Illuminate\Http\Request;

class LandingPageController extends Controller
{
    public function index(Request $request)
    {
        // Ambil data bidang minat dan mata kuliah (seluruhnya)
        $bidangMinat = BidangMinatModel::select('id_bidang_minat', 'nama_bidang_minat')->get();
        $mataKuliah = MataKuliahModel::select('id_matakuliah', 'nama_matakuliah')->get();

        // Mulai query untuk mendapatkan data user berdasarkan level_id 2 dan 3
        $query = UserModel::with(['detail_daftar_user_bidang_minat', 'detail_daftar_user_matakuliah'])
            ->whereIn('id_level', [2, 3]); // Filter untuk level_id 2 dan 3

        // Filter berdasarkan bidang minat
        if ($request->has('bidang_minat')) {
            $query->whereHas('detail_daftar_user_bidang_minat', function($q) use ($request) {
                $q->whereIn('bidang_minat_id', $request->bidang_minat);
            });
        }

        // Filter berdasarkan mata kuliah
        if ($request->has('mata_kuliah')) {
            $query->whereHas('detail_daftar_user_matakuliah', function($q) use ($request) {
                $q->whereIn('mata_kuliah_id', $request->mata_kuliah);
            });
        }

        // Ambil data pengguna yang sudah difilter
        $user = $query->get();

        // Kirim data ke view
        return view('landingpage', [
            'user' => $user,
            'mataKuliah' => $mataKuliah,
            'bidangMinat' => $bidangMinat
        ]);
    }
}
