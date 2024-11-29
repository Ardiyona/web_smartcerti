<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\JenisSertifikasiModel;
use Illuminate\Http\Request;

class JenisSertifikasiController extends Controller
{
    public function index()
    {
        try {
            $jenisSertifikasi = JenisSertifikasiModel::all(); // Ambil semua data jenis sertifikasi
            return response()->json([
                'success' => true,
                'data' => $jenisSertifikasi,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching jenis sertifikasi: ' . $e->getMessage(),
            ], 500);
        }
    }
}
