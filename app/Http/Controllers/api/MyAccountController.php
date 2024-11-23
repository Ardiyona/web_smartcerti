<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MyAccountController extends Controller
{
    public function index()
    {
        /** @var User */
        $user = Auth::guard('api')->user();

        // Ambil data user beserta relasi mata_kuliah dan bidang_minat
        $userData = $user->load([
            'detail_daftar_user_matakuliah', // Relasi mata_kuliah
            'detail_daftar_user_bidang_minat', // Relasi bidang_minat
        ]);

        return response()->json([
            'success' => true,
            'data' => $userData,
        ], 200);
    }
}
