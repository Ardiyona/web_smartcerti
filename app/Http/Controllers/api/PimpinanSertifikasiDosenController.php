<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\SertifikasiModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class PimpinanSertifikasiDosenController extends Controller
{
    public function index()
    {
        // Mendapatkan data sertifikasi beserta relasi yang diperlukan
        $sertifikasi = SertifikasiModel::select(
            'id_sertifikasi',
            'id_vendor_sertifikasi',
            'id_jenis_sertifikasi',
            'id_periode',
            'nama_sertifikasi',
            'jenis',
            'tanggal',
            'masa_berlaku',
            'kuota_peserta',
            'biaya',
            'status_sertifikasi'
        )
        ->where(function ($query) {
            $query->where('status_sertifikasi', 'terima')
                  ->orWhereNull('status_sertifikasi');
        })
            ->with([
                'vendor_sertifikasi',
                'jenis_sertifikasi',
                'periode',
                'bidang_minat_sertifikasi',
                'mata_kuliah_sertifikasi',
                'detail_peserta_sertifikasi'
            ])
            ->get();

        // Mengembalikan response dalam bentuk JSON
        return response()->json([
            'success' => true,
            'message' => 'Data sertifikasi retrieved successfully',
            'data' => $sertifikasi
        ], 200);
    }
}
