<?php

namespace App\Http\Controllers;

use App\Models\PelatihanModel;
use App\Models\SertifikasiModel;
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
                ->whereIn('status_sertifikasi', ['tolak', 'terima', 'menunggu'])
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
                    $btn = '<button onclick="modalAction(\'' . url('/penerimaanpermintaan/' . $row->id . '/show_sertifikasi') . '\')" class="btn btn-info btn-sm">Detail</button> ';
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
                    $btn = '<button onclick="modalAction(\'' . url('/penerimaanpermintaan/' . $row->id . '/show_pelatihan') . '\')" class="btn btn-info btn-sm">Detail</button> ';
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

    public function show(String $id)
    {
        $sertifikasi = SertifikasiModel::with('vendor_sertifikasi', 'jenis_sertifikasi', 'periode', 'bidang_minat_sertifikasi', 'mata_kuliah_sertifikasi')->find($id);
        $pelatihan = PelatihanModel::with('vendor_pelatihan', 'jenis_pelatihan', 'periode', 'bidang_minat_pelatihan', 'mata_kuliah_pelatihan')->find($id);
        if ($sertifikasi) {
            return view('penerimaanpermintaan.show_sertifikasi', ['sertifikasi' => $sertifikasi]);
        } else {
            return view('penerimaanpermintaan.show_pelatihan', ['pelatihan' => $pelatihan]);
        }
    }

    public function updateStatus($id, $status)
    {
        $sertifikasi = SertifikasiModel::find($id);
    
        if ($sertifikasi) {
            // Validasi status yang diterima
            if (in_array($status, ['terima', 'tolak'])) {
                $sertifikasi->status_sertifikasi = $status;
                $sertifikasi->save();
                return redirect()->back()->with('success', 'Status sertifikasi berhasil diperbarui.');
            } else {
                return redirect()->back()->with('error', 'Status tidak valid.');
            }
        } else {
            return redirect()->back()->with('error', 'Sertifikasi tidak ditemukan.');
        }
    }
}
