<?php

namespace App\Http\Controllers;

use App\Models\PelatihanModel;
use App\Models\SertifikasiModel;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class PenerimaanPermintaanController extends Controller
{
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Penerimaan Permintaan',
            'list'  => ['Home', 'Penerimaan Permintaan']
        ];

        $page = (object) [
            'title' => 'Daftar penerimaan permintaan yang terdaftar dalam sistem'
        ];

        $activeMenu = 'penerimaanpermintaan';

        return view('penerimaanpermintaan.index', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'activeMenu' => $activeMenu
        ]);
    }

    public function listSertifikasi()
    {
        try {
            // Ambil data sertifikasi
            $sertifikasi = SertifikasiModel::select(
                'id_sertifikasi as id',
                'id_vendor_sertifikasi',
                'id_jenis_sertifikasi',
                'id_periode',
                'nama_sertifikasi as nama_program',
                'jenis as jenis_level',
                'tanggal',
                'kuota_peserta',
                'status_sertifikasi as status',
                'biaya',
                DB::raw("'sertifikasi' as kategori")
            )
                ->where('status_sertifikasi', 'menunggu')
                ->with([
                    'vendor_sertifikasi',
                    'jenis_sertifikasi',
                    'periode',
                    'detail_peserta_sertifikasi' => function ($query) {
                        $query->select('id_detail_peserta_sertifikasi', 'id_sertifikasi', 'nama_lengkap');
                    }
                ]);


            // Gabungkan data sertifikasi dan pelatihan
            $sertifikasi->get();

            return DataTables::of($sertifikasi)
                ->addIndexColumn()
                ->addColumn('peserta', function ($row) {
                        $peserta = $row->detail_peserta_sertifikasi;
                        return $peserta ? $peserta->pluck('nama_lengkap')->implode(', ') : '-';
                })
                ->addColumn('aksi', function ($row) {
                    $btn = '<button onclick="modalAction(\'' . url('/detail/' . $row->id . '/show') . '\')" class="btn btn-info btn-sm">Detail</button> ';
                    return $btn;
                })
                ->rawColumns(['aksi'])
                ->make(true);
        } catch (\Exception $e) {
            Log::error('Error in list method: ' . $e->getMessage());
            return response()->json([
                'error' => 'Internal Server Error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function listPelatihan()
    {
        try {

            // Ambil data pelatihan
            $pelatihan = PelatihanModel::select(
                'id_pelatihan as id',
                'id_vendor_pelatihan',
                'id_jenis_pelatihan',
                'id_periode',
                'nama_pelatihan as nama_program',
                'level_pelatihan as jenis_level',
                'tanggal',
                'kuota_peserta',
                'status_pelatihan as status',
                'biaya',
                DB::raw("'pelatihan' as kategori")
            )
                ->where('status_pelatihan', 'menunggu')
                ->with([
                    'vendor_pelatihan',
                    'jenis_pelatihan',
                    'periode',
                    'detail_peserta_pelatihan'
                ]);

            $pelatihan->get();

            return DataTables::of($pelatihan)
                ->addIndexColumn()
                ->addColumn('peserta', function ($pelatihan) {
                    return $pelatihan->detail_peserta_pelatihan->pluck('nama_lengkap')->implode(', ');
                })
                ->addColumn('aksi', function ($row) {
                    $btn = '<button onclick="modalAction(\'' . url('/detail/' . $row->id . '/show') . '\')" class="btn btn-info btn-sm">Detail</button> ';
                    return $btn;
                })
                ->rawColumns(['aksi'])
                ->make(true);
        } catch (\Exception $e) {
            Log::error('Error in list method: ' . $e->getMessage());
            return response()->json([
                'error' => 'Internal Server Error',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
