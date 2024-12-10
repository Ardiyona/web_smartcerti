<?php

namespace App\Http\Controllers;

use App\Models\BidangMinatModel;
use App\Models\MataKuliahModel;
use App\Models\UserModel;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function index(Request $request)
    {
        // Ambil seluruh data bidang minat
        $bidangMinat = BidangMinatModel::select('id_bidang_minat', 'nama_bidang_minat')->get();

        // Ambil seluruh data mata kuliah
        $mataKuliah = MataKuliahModel::select('id_matakuliah', 'nama_matakuliah')->get();

        // Ambil data user dengan bidang minat "Database"
        $user = UserModel::with(['detail_daftar_user_bidang_minat', 'detail_daftar_user_matakuliah'])
            ->whereHas('detail_daftar_user_bidang_minat', function ($query) {
                $query->where('nama_bidang_minat', 'Database');
            })
            ->whereIn('id_level', [2, 3]) // Filter berdasarkan level ID
            ->get();

        // Kirim data ke view
        return view('page.database.index', [
            'user' => $user,
            'mataKuliah' => $mataKuliah,
            'bidangMinat' => $bidangMinat,
        ]);
    }

    public function gamedev(Request $request)
    {
        // Ambil seluruh data bidang minat
        $bidangMinat = BidangMinatModel::select('id_bidang_minat', 'nama_bidang_minat')->get();

        // Ambil seluruh data mata kuliah
        $mataKuliah = MataKuliahModel::select('id_matakuliah', 'nama_matakuliah')->get();

        // Ambil data user dengan bidang minat "Database"
        $user = UserModel::with(['detail_daftar_user_bidang_minat', 'detail_daftar_user_matakuliah'])
            ->whereHas('detail_daftar_user_bidang_minat', function ($query) {
                $query->where('nama_bidang_minat', 'Game Development');
            })
            ->whereIn('id_level', [2, 3]) // Filter berdasarkan level ID
            ->get();

        // Kirim data ke view
        return view('page.gamedev.index', [
            'user' => $user,
            'mataKuliah' => $mataKuliah,
            'bidangMinat' => $bidangMinat,
        ]);
    }

    public function iot(Request $request)
    {
        // Ambil seluruh data bidang minat
        $bidangMinat = BidangMinatModel::select('id_bidang_minat', 'nama_bidang_minat')->get();

        // Ambil seluruh data mata kuliah
        $mataKuliah = MataKuliahModel::select('id_matakuliah', 'nama_matakuliah')->get();

        // Ambil data user dengan bidang minat "Database"
        $user = UserModel::with(['detail_daftar_user_bidang_minat', 'detail_daftar_user_matakuliah'])
            ->whereHas('detail_daftar_user_bidang_minat', function ($query) {
                $query->where('nama_bidang_minat', 'Internet of Things');
            })
            ->whereIn('id_level', [2, 3]) // Filter berdasarkan level ID
            ->get();

        // Kirim data ke view
        return view('page.iot.index', [
            'user' => $user,
            'mataKuliah' => $mataKuliah,
            'bidangMinat' => $bidangMinat,
        ]);
    }

    public function ar(Request $request)
    {
        // Ambil seluruh data bidang minat
        $bidangMinat = BidangMinatModel::select('id_bidang_minat', 'nama_bidang_minat')->get();

        // Ambil seluruh data mata kuliah
        $mataKuliah = MataKuliahModel::select('id_matakuliah', 'nama_matakuliah')->get();

        // Ambil data user dengan bidang minat "Database"
        $user = UserModel::with(['detail_daftar_user_bidang_minat', 'detail_daftar_user_matakuliah'])
            ->whereHas('detail_daftar_user_bidang_minat', function ($query) {
                $query->where('nama_bidang_minat', 'Augmented Reality');
            })
            ->whereIn('id_level', [2, 3]) // Filter berdasarkan level ID
            ->get();

        // Kirim data ke view
        return view('page.ar.index', [
            'user' => $user,
            'mataKuliah' => $mataKuliah,
            'bidangMinat' => $bidangMinat,
        ]);
    }

    public function machinelearning(Request $request)
    {
        // Ambil seluruh data bidang minat
        $bidangMinat = BidangMinatModel::select('id_bidang_minat', 'nama_bidang_minat')->get();

        // Ambil seluruh data mata kuliah
        $mataKuliah = MataKuliahModel::select('id_matakuliah', 'nama_matakuliah')->get();

        // Ambil data user dengan bidang minat "Database"
        $user = UserModel::with(['detail_daftar_user_bidang_minat', 'detail_daftar_user_matakuliah'])
            ->whereHas('detail_daftar_user_bidang_minat', function ($query) {
                $query->where('nama_bidang_minat', 'Machine Learning');
            })
            ->whereIn('id_level', [2, 3]) // Filter berdasarkan level ID
            ->get();

        // Kirim data ke view
        return view('page.machinelearning.index', [
            'user' => $user,
            'mataKuliah' => $mataKuliah,
            'bidangMinat' => $bidangMinat,
        ]);
    }

    public function bi(Request $request)
    {
        // Ambil seluruh data bidang minat
        $bidangMinat = BidangMinatModel::select('id_bidang_minat', 'nama_bidang_minat')->get();

        // Ambil seluruh data mata kuliah
        $mataKuliah = MataKuliahModel::select('id_matakuliah', 'nama_matakuliah')->get();

        // Ambil data user dengan bidang minat "Database"
        $user = UserModel::with(['detail_daftar_user_bidang_minat', 'detail_daftar_user_matakuliah'])
            ->whereHas('detail_daftar_user_bidang_minat', function ($query) {
                $query->where('nama_bidang_minat', 'Business Intelligence');
            })
            ->whereIn('id_level', [2, 3]) // Filter berdasarkan level ID
            ->get();

        // Kirim data ke view
        return view('page.bi.index', [
            'user' => $user,
            'mataKuliah' => $mataKuliah,
            'bidangMinat' => $bidangMinat,
        ]);
    }

    public function network(Request $request)
    {
        // Ambil seluruh data bidang minat
        $bidangMinat = BidangMinatModel::select('id_bidang_minat', 'nama_bidang_minat')->get();

        // Ambil seluruh data mata kuliah
        $mataKuliah = MataKuliahModel::select('id_matakuliah', 'nama_matakuliah')->get();

        // Ambil data user dengan bidang minat "Database"
        $user = UserModel::with(['detail_daftar_user_bidang_minat', 'detail_daftar_user_matakuliah'])
            ->whereHas('detail_daftar_user_bidang_minat', function ($query) {
                $query->where('nama_bidang_minat', 'Computer Network');
            })
            ->whereIn('id_level', [2, 3]) // Filter berdasarkan level ID
            ->get();

        // Kirim data ke view
        return view('page.network.index', [
            'user' => $user,
            'mataKuliah' => $mataKuliah,
            'bidangMinat' => $bidangMinat,
        ]);
    }

    public function bigdata(Request $request)
    {
        // Ambil seluruh data bidang minat
        $bidangMinat = BidangMinatModel::select('id_bidang_minat', 'nama_bidang_minat')->get();

        // Ambil seluruh data mata kuliah
        $mataKuliah = MataKuliahModel::select('id_matakuliah', 'nama_matakuliah')->get();

        // Ambil data user dengan bidang minat "Database"
        $user = UserModel::with(['detail_daftar_user_bidang_minat', 'detail_daftar_user_matakuliah'])
            ->whereHas('detail_daftar_user_bidang_minat', function ($query) {
                $query->where('nama_bidang_minat', 'Big Data');
            })
            ->whereIn('id_level', [2, 3]) // Filter berdasarkan level ID
            ->get();

        // Kirim data ke view
        return view('page.bigdata.index', [
            'user' => $user,
            'mataKuliah' => $mataKuliah,
            'bidangMinat' => $bidangMinat,
        ]);
    }
}
