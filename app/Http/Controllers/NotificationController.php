<?php

namespace App\Http\Controllers;

use App\Models\PelatihanModel;
use App\Models\SertifikasiModel;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function indexSertifikasi($id)
    {
        $breadcrumb = (object) [
            'title' => 'Detail Notifikasi Sertifikasi',
            'list'  => ['Home', 'Detail Notifikasi Sertifikasi']
        ];

        $page = (object) [
            'title' => 'Detail notifikasi sertifikasi'
        ];

        $activeMenu = 'none';

        $sertifikasi = SertifikasiModel::with('vendor_sertifikasi', 'jenis_sertifikasi', 'periode', 'bidang_minat_sertifikasi', 'mata_kuliah_sertifikasi')->find($id);
        return view('notifikasi.show_sertifikasi', ['sertifikasi' => $sertifikasi, 'breadcrumb' => $breadcrumb, 'page' => $page, 'activeMenu' => $activeMenu ]);
    }

    public function indexPelatihan($id)
    {
        $breadcrumb = (object) [
            'title' => 'Detail Notifikasi Pelatihan',
            'list'  => ['Home', 'Detail Notifikasi Pelatihan']
        ];

        $page = (object) [
            'title' => 'Detail notifikasi pelatihan'
        ];

        $activeMenu = 'none';

        $pelatihan = PelatihanModel::with('vendor_pelatihan', 'jenis_pelatihan', 'periode', 'bidang_minat_pelatihan', 'mata_kuliah_pelatihan')->find($id);
        return view('notifikasi.show_pelatihan', ['pelatihan' => $pelatihan, 'breadcrumb' => $breadcrumb, 'page' => $page, 'activeMenu' => $activeMenu ]);
    }
}
