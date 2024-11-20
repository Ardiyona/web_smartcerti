<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::guard('api')->user();


        $userData = [
            'nama_level' => $user->level->nama_level,
            'username' => $user->username,
            'nama_lengkap' => $user->nama_lengkap,
            'no_telp' => $user->no_telp,
            'email' => $user->email,
            'jenis_kelamin' => $user->jenis_kelamin,
            'avatar' => $user->avatar,
        ];

        return response()->json([
            'success' => true,
            'data' => $userData,
        ], 200);
    }
}