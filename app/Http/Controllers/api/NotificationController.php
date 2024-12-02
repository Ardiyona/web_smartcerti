<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index_sertifikasi()
    {
        /** @var User */
        $user = Auth::guard('api')->user();
        
        $sertifikasi = $user->detail_peserta_sertifikasi()
            ->where('status_sertifikasi', 'terima')
            ->with('vendor_sertifikasi', 'jenis_sertifikasi', 'periode', 'bidang_minat_sertifikasi', 'mata_kuliah_sertifikasi')
            ->get();

        // Mengembalikan response dalam bentuk JSON
        return response()->json([
            'success' => true,
            'message' => 'Data sertifikasi retrieved successfully',
            'data' => $sertifikasi ?? 'tidak ada data',
        ], 200);
    }

    public function index_pelatihan()
    {
        /** @var User */
        $user = Auth::guard('api')->user();

        $pelatihan = $user->detail_peserta_pelatihan()
            ->where('status_pelatihan', 'terima')
            ->with('vendor_pelatihan', 'jenis_pelatihan', 'periode', 'bidang_minat_pelatihan', 'mata_kuliah_pelatihan')
            ->get();

        // Mengembalikan response dalam bentuk JSON
        return response()->json([
            'success' => true,
            'message' => 'Data sertifikasi retrieved successfully',
            'data' => $pelatihan ?? 'tidak ada data',
        ], 200);
    }
}
