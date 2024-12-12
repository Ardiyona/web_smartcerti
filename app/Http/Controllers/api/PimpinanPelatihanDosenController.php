<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\PelatihanModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class PimpinanPelatihanDosenController extends Controller
{
    public function index()
    {
        // Mendapatkan data pelatihan beserta relasi yang diperlukan
        $pelatihan = PelatihanModel::select(
            'id_pelatihan',
            'id_vendor_pelatihan',
            'id_jenis_pelatihan',
            'id_periode',
            'nama_pelatihan',
            'lokasi',
            'level_pelatihan',
            'tanggal',
            'kuota_peserta',
            'biaya',

        )
            ->where('status_pelatihan', 'terima')
            ->with([
                'vendor_pelatihan',
                'jenis_pelatihan',
                'periode',
                'bidang_minat_pelatihan',
                'mata_kuliah_pelatihan',
                'detail_peserta_pelatihan'
            ])
            ->get();

        // Mengembalikan response dalam bentuk JSON
        return response()->json([
            'success' => true,
            'message' => 'Data pelatihan retrieved successfully',
            'data' => $pelatihan
        ], 200);
    }
}
