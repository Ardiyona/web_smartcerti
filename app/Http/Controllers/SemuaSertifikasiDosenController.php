<?php

namespace App\Http\Controllers;

use App\Models\PelatihanModel;
use Illuminate\Http\Request;
use App\Models\SertifikasiModel;
use App\Models\UserModel;
use App\Models\VendorPelatihanModel;
use App\Models\VendorSertifikasiModel;
use Yajra\DataTables\Facades\DataTables;

class SemuaSertifikasiDosenController extends Controller
{
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Daftar Sertifikasi Dosen',
            'list'  => ['Home', 'Sertifikasi Dosen']
        ];
        $page = (object) [
            'title' => 'Daftar sertifikasi dosen yang terdaftar dalam sistem'
        ];
        $activeMenu = 'dashboardpimpinan';
        $vendorSertifikasi = VendorSertifikasiModel::all();
        return view('semuasertifikasidosen.index', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'vendorSertifikasi' => $vendorSertifikasi,
            'activeMenu' => $activeMenu
        ]);
    }
    public function list()
    {
        // Mengambil semua data sertifikasi dengan relasi
        $sertifikasis = SertifikasiModel::select(
            'id_sertifikasi',
            'id_vendor_sertifikasi',
            'id_jenis_sertifikasi',
            'id_periode',
            'nama_sertifikasi',
            'no_sertifikasi',
            'jenis',
            'tanggal',
            'bukti_sertifikasi',
            'masa_berlaku',
            'kuota_peserta',
            'biaya'
        )
        ->with([
            'vendor_sertifikasi:id,nama',
            'jenis_sertifikasi:id,nama_jenis_sertifikasi',
            'periode:id,tahun_periode',
            'bidang_minat_sertifikasi:id,nama_bidang_minat',
            'mata_kuliah_sertifikasi:id,nama_matakuliah',
            'detail_peserta_sertifikasi:id,nama_lengkap'
        ]);
        
        return DataTables::of($sertifikasis)
            ->addIndexColumn()
            ->addColumn('bidang_minat', function ($sertifikasi) {
                return $sertifikasi->bidang_minat_sertifikasi
                    ? $sertifikasi->bidang_minat_sertifikasi->pluck('nama_bidang_minat')->implode(', ')
                    : '-';
            })
            ->addColumn('mata_kuliah', function ($sertifikasi) {
                return $sertifikasi->mata_kuliah_sertifikasi
                    ? $sertifikasi->mata_kuliah_sertifikasi->pluck('nama_matakuliah')->implode(', ')
                    : '-';
            })
            ->addColumn('peserta_sertifikasi', function ($sertifikasi) {
                return $sertifikasi->detail_peserta_sertifikasi
                    ? $sertifikasi->detail_peserta_sertifikasi->pluck('nama_lengkap')->implode(', ')
                    : '-';
            })
            ->make(true);
    }

    
}
