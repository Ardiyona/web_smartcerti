<?php

namespace App\Http\Controllers;

use App\Models\PelatihanModel;
use Illuminate\Http\Request;
use App\Models\SertifikasiModel;
use App\Models\UserModel;
use Illuminate\Support\Facades\Auth;
use App\Models\VendorSertifikasiModel;
use Yajra\DataTables\Facades\DataTables;

class SertifikasiUserController extends Controller
{
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Daftar Sertifikasi Dosen',
            'list'  => ['Home', 'Sertifikasi Dosen']
        ];
        $page = (object) [
            'title' => 'Daftar sertifikasi yang terdaftar dalam sistem'
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
        // Mengambil data user beserta level
        /** @var User */
        $user = Auth::user();
        // Mengambil data sertifikas yang hanya dimiliki oleh user yang sedang login
        $sertifikasis = $user->detail_peserta_sertifikasi()
                ->select(
                    'sertifikasi.id_sertifikasi',
                    'sertifikasi.id_vendor_sertifikasi',
                    'sertifikasi.id_jenis_sertifikasi',
                    'sertifikasi.id_periode',
                    'sertifikasi.nama_sertifikasi',
                    'sertifikasi.jenis',
                    'sertifikasi.tanggal',
                    'sertifikasi.masa_berlaku',
                    'sertifikasi.kuota_peserta',
                    'sertifikasi.biaya'
                )
                ->with([
                    'vendor_sertifikasi',
                    'jenis_sertifikasi',
                    'periode',
                    'bidang_minat_sertifikasi',
                    'mata_kuliah_sertifikasi',
                    'detail_peserta_sertifikasi' => function ($query) use ($user) {
                        $query->where('detail_peserta_sertifikasi.user_id', $user->user_id);
                    }
                ]);

                // Mengembalikan data dengan DataTables
        return DataTables::of($sertifikasis)
        ->addIndexColumn()
        ->addColumn('no_sertifikasi', function ($sertifikasi) use ($user) {
            // Jika user bukan admin atau pimpinan, hanya tampilkan nomor sertifikasi milik user tersebut
            if ($user->id_level != 1 && $user->id_level != 2) {
                return $sertifikasi->detail_peserta_sertifikasi
                    ->where('user_id', $user->user_id) // Filter nomor sertifikasi milik user
                    ->map(function ($peserta) {
                        return $peserta->pivot->no_sertifikasi; // Mengakses properti dari pivot
                    })->implode('- ');
            }
    
            // Jika admin atau pimpinan, tampilkan semua nomor sertifikasi
            return $sertifikasi->detail_peserta_sertifikasi->map(function ($peserta) {
                return $peserta->pivot->no_sertifikasi; // Mengakses properti dari pivot
            })->implode(', ');
        })
        ->addColumn('bidang_minat', function ($sertifikasi) {
            return $sertifikasi->bidang_minat_sertifikasi->pluck('nama_bidang_minat')->implode(', ');
        })
        ->addColumn('mata_kuliah', function ($sertifikasi) {
            return $sertifikasi->mata_kuliah_sertifikasi->pluck('nama_matakuliah')->implode(', ');
        })
        ->addColumn('peserta_sertifikasi', function ($sertifikasi) {
            return $sertifikasi->detail_peserta_sertifikasi->pluck('nama_lengkap')->implode(', ');
        })
        ->make(true);
        }
    
    }

    

