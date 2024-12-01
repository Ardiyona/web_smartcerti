<?php

namespace App\Http\Controllers;

use App\Models\PelatihanModel;
use App\Models\SertifikasiModel;
use App\Models\UserModel;
use App\Notifications\NotifikasiPesertaPelatihan;
use App\Notifications\NotifikasiPesertaSertifikasi;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification as FacadesNotification;
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
                    $btn = '<button onclick="modalAction(\'' . url('/penerimaanpermintaan/' . $row->id_sertifikasi . '/show_sertifikasi') . '\')" class="btn btn-info btn-sm">Detail</button> ';
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
                DB::raw("'pelatihan' as kategori")
            )
                ->whereIn('status_pelatihan', ['tolak', 'terima', 'menunggu'])
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
                    $btn = '<button onclick="modalAction(\'' . url('/penerimaanpermintaan/' . $row->id_pelatihan . '/show_pelatihan') . '\')" class="btn btn-info btn-sm">Detail</button> ';
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
        $pelatihan = PelatihanModel::find($id);

        if ($sertifikasi) {
            // Validasi status yang diterima
            if (in_array($status, ['terima', 'tolak'])) {
                // Ambil semua peserta terkait dari pivot
                $pesertaSertifikasi = $sertifikasi->detail_peserta_sertifikasi()->pluck('detail_peserta_sertifikasi.user_id');

                // dd($pesertaSertifikasi);
    
                // Kirim notifikasi ke setiap peserta
                foreach ($pesertaSertifikasi as $userId) {
                    $pesertaUser = UserModel::find($userId);
                    if ($pesertaUser) {
                        FacadesNotification::send($pesertaUser, new NotifikasiPesertaSertifikasi($sertifikasi));
                    }
                }
                $sertifikasi->status_sertifikasi = $status;
                $sertifikasi->save();
    
                return redirect()->back()->with('success', 'Status sertifikasi berhasil diperbarui dan notifikasi telah dikirim.');
            } else {
                return redirect()->back()->with('error', 'Status tidak valid.');
            }
        } elseif ($pelatihan) {
            // Validasi status yang diterima
            if (in_array($status, ['terima', 'tolak'])) {
                // Ambil semua peserta terkait dari pivot
                $pesertaPelatihan = $pelatihan->detail_peserta_pelatihan()->pluck('detail_peserta_pelatihan.user_id');

                // dd($pesertapelatihan);
    
                // Kirim notifikasi ke setiap peserta
                foreach ($pesertaPelatihan as $userId) {
                    $pesertaUser = UserModel::find($userId);
                    if ($pesertaUser) {
                        FacadesNotification::send($pesertaUser, new NotifikasiPesertaPelatihan($pelatihan));
                    }
                }
                $pelatihan->status_pelatihan = $status;
                $pelatihan->save();
    
                return redirect()->back()->with('success', 'Status pelatihan berhasil diperbarui dan notifikasi telah dikirim.');
            } else {
                return redirect()->back()->with('error', 'Status tidak valid.');
            }
        } else {
            return redirect()->back()->with('error', 'Data Sertifikasi atau Pelatihan tidak ditemukan.');
        }
    }
}
