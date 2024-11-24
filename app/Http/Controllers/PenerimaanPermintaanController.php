<?php

namespace App\Http\Controllers;

use App\Models\PelatihanModel;
use App\Models\SertifikasiModel;
use Illuminate\Http\Request;
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

    public function list()
    {
        // Ambil data dari tabel sertifikasi dengan status_sertifikasi = 'menunggu'
        $sertifikasi = SertifikasiModel::select(
            'id_sertifikasi as id',
            'id_vendor_sertifikasi',
            'id_jenis_sertifikasi',
            'id_periode',
            'nama_sertifikasi as nama_program',
            'jenis as jenis_level',
            'tanggal',
            'kuota_peserta',
            'biaya',
            DB::raw("'sertifikasi' as kategori")
        )
        ->where('status_sertifikasi', 'menunggu')
        ->with(['vendor_sertifikasi', 'jenis_sertifikasi', 'periode', 
            'detail_peserta_sertifikasi' => function($query) {
                $query->select('id_detail_peserta_sertifikasi', 'id_sertifikasi', 'nama_lengkap');
            }
        ]);

        // $sertifikasis = SertifikasiModel::with('detail_peserta_sertifikasi')->first();
        // dd($sertifikasis->detail_peserta_sertifikasi);

        // Ambil data dari tabel pelatihan
        $pelatihan = PelatihanModel::select(
            'id_pelatihan as id',
            'id_vendor_pelatihan as id_vendor_sertifikasi',
            'id_jenis_pelatihan as id_jenis_sertifikasi',
            'id_periode',
            'nama_pelatihan as nama_program',
            'level_pelatihan as jenis_level',
            'tanggal',
            'kuota_peserta',
            'biaya',
            DB::raw("'pelatihan' as kategori")
        )
        ->where('status_pelatihan', 'menunggu')
        ->with(['vendor_pelatihan', 'jenis_pelatihan', 'periode',
            'detail_peserta_pelatihan' => function($query) {
                $query->select('id_detail_peserta_pelatihan', 'id_pelatihan', 'nama_lengkap');
            }
        ]);

        // Gabungkan data sertifikasi dan pelatihan
        $data = $sertifikasi->union($pelatihan)->get();
        // Mengembalikan data dengan DataTables
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('peserta', function ($row) {
                
                if ($row->kategori === 'sertifikasi') {
                    $peserta = $row->detail_peserta_sertifikasi;
                    return $peserta ? $peserta->pluck('nama_lengkap')->implode(', ') : '-';
                } elseif ($row->kategori === 'pelatihan') {
                    $peserta = $row->detail_peserta_pelatihan;
                    return $peserta ? $peserta->pluck('nama_lengkap')->implode(', ') : '-';
                }
                return '-';
            })
            ->addColumn('aksi', function ($row) {
                $btn = '<button onclick="modalAction(\'' . url('/detail/' . $row->id . '/show') . '\')" class="btn btn-info btn-sm">Detail</button> ';
                return $btn;
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }
}
