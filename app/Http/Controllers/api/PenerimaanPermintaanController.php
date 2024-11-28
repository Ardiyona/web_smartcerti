<?php

namespace App\Http\Controllers\api;

use App\Models\PelatihanModel;
use App\Models\SertifikasiModel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class PenerimaanPermintaanController extends Controller
{
    public function listSertifikasi()
    {
        // Ambil data sertifikasi
        $sertifikasi = SertifikasiModel::select(
            'id_sertifikasi',
            'id_vendor_sertifikasi',
            'id_jenis_sertifikasi',
            'id_periode',
            'nama_sertifikasi',
            'jenis',
            'tanggal',
            'kuota_peserta',
            'status_sertifikasi',
            'biaya',
        )
            ->where('status_sertifikasi', 'menunggu')
            ->with([
                'vendor_sertifikasi',
                'jenis_sertifikasi',
                'periode',
                'bidang_minat_sertifikasi',
                'mata_kuliah_sertifikasi',
                'detail_peserta_sertifikasi'
            ])
        ->get();

        // Gabungkan data sertifikasi dan pelatihan
        // Mengembalikan response dalam bentuk JSON
        return response()->json([
            'success' => true,
            'message' => 'Data pelatihan retrieved successfully',
            'data' => $sertifikasi
        ], 200);
    }

    public function listPelatihan()
    {

        // Ambil data pelatihan
        $pelatihan = PelatihanModel::select(
            'id_pelatihan',
            'id_vendor_pelatihan',
            'id_jenis_pelatihan',
            'id_periode',
            'nama_pelatihan',
            'level_pelatihan',
            'tanggal',
            'kuota_peserta',
            'status_pelatihan',
            'biaya',
        )
        ->where('status_pelatihan', 'menunggu')
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
