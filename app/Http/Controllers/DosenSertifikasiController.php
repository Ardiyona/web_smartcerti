<?php

namespace App\Http\Controllers;

use App\Models\BidangMinatModel;
use App\Models\BidangMinatSertifikasiModel;
use App\Models\JenisSertifikasiModel;
use App\Models\MataKuliahModel;
use App\Models\MataKuliahSertifikasiModel;
use App\Models\PeriodeModel;
use App\Models\SertifikasiModel;
use App\Models\UserModel;
use App\Models\VendorPelatihanModel;
use App\Models\VendorSertifikasiModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class DosenSertifikasiController extends Controller
{
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Daftar Sertifikasi',
            'list'  => ['Home', 'Sertifikasi']
        ];

        $page = (object) [
            'title' => 'Daftar sertifikasi yang terdaftar dalam sistem'
        ];

        $activeMenu = 'dosensertifikasi';

        $vendorSertifikasi = VendorSertifikasiModel::all();
        $vendorPelatihan = VendorPelatihanModel::all();

        return view('sertifikasi.index', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'vendorSertifikasi' => $vendorSertifikasi,
            'vendorPelatihan' => $vendorPelatihan,
            'activeMenu' => $activeMenu
        ]);
    }

    // Ambil data user dalam bentuk json untuk datatables
    public function list()
    {
        // Mengambil data user beserta level
        /** @var User */
        $user = Auth::user();

        // Jika user bukan admin (id_level ≠ 1), tampilkan hanya sertifikasi miliknya
        if ($user->id_level != 2) {
            // Mengambil sertifikasi yang hanya dimiliki oleh user yang sedang login
            $sertifikasis = $user->detail_peserta_sertifikasi()
                ->select(
                    'sertifikasi.id_sertifikasi',
                    'sertifikasi.id_vendor_sertifikasi',
                    'sertifikasi.id_jenis_sertifikasi',
                    'sertifikasi.id_periode',
                    'sertifikasi.nama_sertifikasi',
                    'sertifikasi.no_sertifikasi',
                    'sertifikasi.jenis',
                    'sertifikasi.tanggal',
                    'sertifikasi.bukti_sertifikasi',
                    'sertifikasi.masa_berlaku',
                    'sertifikasi.biaya'
                )
                ->with('vendor_sertifikasi', 'jenis_sertifikasi', 'periode', 'bidang_minat_sertifikasi', 'mata_kuliah_sertifikasi', 'detail_peserta_sertifikasi');
        } else {
            // Jika admin (id_level = 1), tampilkan semua sertifikasi
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
                'biaya'
            )
                ->with('vendor_sertifikasi', 'jenis_sertifikasi', 'periode', 'bidang_minat_sertifikasi', 'mata_kuliah_sertifikasi', 'detail_peserta_sertifikasi');
        }

        // Mengembalikan data dengan DataTables
        return DataTables::of($sertifikasis)
            ->addIndexColumn()
            ->addColumn('bidang_minat', function ($sertifikasi) {
                return $sertifikasi->bidang_minat_sertifikasi->pluck('nama_bidang_minat')->implode(', ');
            })
            ->addColumn('mata_kuliah', function ($sertifikasi) {
                return $sertifikasi->mata_kuliah_sertifikasi->pluck('nama_matakuliah')->implode(', ');
            })
            ->addColumn('peserta_sertifikasi', function ($sertifikasi) {
                return $sertifikasi->detail_peserta_sertifikasi->pluck('nama_lengkap')->implode(', ');
            })
            ->addColumn('aksi', function ($sertifikasi) {
                $btn = '<button onclick="modalAction(\'' . url('/dosensertifikasi/' . $sertifikasi->id_sertifikasi . '/show') . '\')" class="btn btn-info btn-sm">Detail</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/dosensertifikasi/' . $sertifikasi->id_sertifikasi . '/edit') . '\')" class="btn btn-warning btn-sm">Edit</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/dosensertifikasi/' . $sertifikasi->id_sertifikasi . '/confirm') . '\')" class="btn btn-danger btn-sm">Hapus</button> ';
                return $btn;
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    public function create()
    {
        // Mengambil id_level dan nama_level dari tabel level
        $vendorSertifikasi = VendorSertifikasiModel::select('id_vendor_sertifikasi', 'nama')->get();
        $jenisSertifikasi = JenisSertifikasiModel::select('id_jenis_sertifikasi', 'nama_jenis_sertifikasi')->get();
        $periode = PeriodeModel::select('id_periode', 'tahun_periode')->get();

        $bidangMinat = BidangMinatModel::select('id_bidang_minat', 'nama_bidang_minat')->get();
        $mataKuliah = MataKuliahModel::select('id_matakuliah', 'nama_matakuliah')->get();
        $user = UserModel::select('user_id', 'nama_lengkap')->get();
        // dd($mataKuliah);

        return view('sertifikasi.create')->with([
            'vendorSertifikasi' => $vendorSertifikasi,
            'jenisSertifikasi' => $jenisSertifikasi,
            'periode' => $periode,
            'bidangMinat' => $bidangMinat,
            'mataKuliah' => $mataKuliah,
            'user' => $user,
        ]);
    }

    public function store(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'id_vendor_sertifikasi' => 'required|integer',
                'id_jenis_sertifikasi' => 'required|integer',
                'id_periode' => 'required|integer',

                'id_bidang_minat' => 'required',
                'id_matakuliah' => 'required',
                'user_id' => 'nullable',

                'nama_sertifikasi' => 'required|string|min:5',
                'no_sertifikasi' => 'required|string|max:255',
                'jenis' => 'required',
                'tanggal' => 'required|date',
                'bukti_sertifikasi' => 'nullable|mimes:pdf|max:5120',
                'masa_berlaku' => 'required',
                'biaya' => 'required|string|max:255',
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors()
                ]);
            }

            // Inisialisasi variabel untuk menyimpan path file
            $bukti_sertifikasi = null;


            /** @var User */
            $user = Auth::user();
            if ($user->id_level == 1) {
                // Cek apakah file bukti sertifikasi diunggah
                if ($request->hasFile('bukti_sertifikasi')) {
                    $bukti_sertifikasi = time() . '_' . $request->file('bukti_sertifikasi')->getClientOriginalName();
                    $request->file('bukti_sertifikasi')->storeAs('public/images/', $bukti_sertifikasi);
                }

                // Simpan data user dengan hanya field yang diperlukan
                $sertifikasi = SertifikasiModel::create([
                    'nama_sertifikasi'  => $request->nama_sertifikasi,
                    'no_sertifikasi'      => $request->no_sertifikasi,
                    'jenis'      => $request->jenis,
                    'tanggal'      => $request->tanggal,
                    'bukti_sertifikasi'      => $bukti_sertifikasi,
                    'masa_berlaku'      => $request->masa_berlaku,
                    'biaya'      => $request->biaya,
                    'id_vendor_sertifikasi'  => $request->id_vendor_sertifikasi,
                    'id_jenis_sertifikasi'  => $request->id_jenis_sertifikasi,
                    'id_periode'  => $request->id_periode
                ]);

                $sertifikasi->bidang_minat_sertifikasi()->sync($request->id_bidang_minat);
                $sertifikasi->mata_kuliah_sertifikasi()->sync($request->id_matakuliah);
                $sertifikasi->detail_peserta_sertifikasi()->sync($request->user_id);

                return response()->json([
                    'status' => true,
                    'message' => 'Data user berhasil disimpan'
                ]);
            } else {
                // Cek apakah file bukti sertifikasi diunggah
                if ($request->hasFile('bukti_sertifikasi')) {
                    $bukti_sertifikasi = time() . '_' . $request->file('bukti_sertifikasi')->getClientOriginalName();
                    $request->file('bukti_sertifikasi')->storeAs('public/buktiSertifikasi/', $bukti_sertifikasi);
                }

                // Simpan data user dengan hanya field yang diperlukan
                $sertifikasi = SertifikasiModel::create([
                    'nama_sertifikasi'  => $request->nama_sertifikasi,
                    'no_sertifikasi'      => $request->no_sertifikasi,
                    'jenis'      => $request->jenis,
                    'tanggal'      => $request->tanggal,
                    'bukti_sertifikasi'      => $bukti_sertifikasi,
                    'masa_berlaku'      => $request->masa_berlaku,
                    'biaya'      => $request->biaya,
                    'id_vendor_sertifikasi'  => $request->id_vendor_sertifikasi,
                    'id_jenis_sertifikasi'  => $request->id_jenis_sertifikasi,
                    'id_periode'  => $request->id_periode
                ]);

                $sertifikasi->bidang_minat_sertifikasi()->sync($request->id_bidang_minat);
                $sertifikasi->mata_kuliah_sertifikasi()->sync($request->id_matakuliah);

                // Mendapatkan user yang sedang login
                $userId = Auth::user();

                // Cek apakah user bukan admin (id_level != 1)
                if ($userId && $userId->id_level != 1) {
                    // Tambahkan ke tabel detail_peserta_sertifikasi
                    $sertifikasi->detail_peserta_sertifikasi()->attach($userId);
                }

                return response()->json([
                    'status' => true,
                    'message' => 'Data user berhasil disimpan'
                ]);
            }
        }
        return redirect('/');
    }

    public function show(String $id)
    {
        $sertifikasi = SertifikasiModel::with('vendor_sertifikasi', 'jenis_sertifikasi', 'periode', 'bidang_minat_sertifikasi', 'mata_kuliah_sertifikasi')->find($id);

        return view('sertifikasi.show', ['sertifikasi' => $sertifikasi]);
    }

    public function edit(string $id)
    {
        $sertifikasi = SertifikasiModel::find($id);

        $vendorSertifikasi = VendorSertifikasiModel::select('id_vendor_sertifikasi', 'nama')->get();
        $jenisSertifikasi = JenisSertifikasiModel::select('id_jenis_sertifikasi', 'nama_jenis_sertifikasi')->get();
        $periode = PeriodeModel::select('id_periode', 'tahun_periode')->get();

        $bidangMinat = BidangMinatModel::select('id_bidang_minat', 'nama_bidang_minat')->get();
        $mataKuliah = MataKuliahModel::select('id_matakuliah', 'nama_matakuliah')->get();
        $user = UserModel::select('user_id', 'nama_lengkap')->get();

        return view('sertifikasi.edit', [
            'sertifikasi' => $sertifikasi,
            'vendorSertifikasi' => $vendorSertifikasi,
            'jenisSertifikasi' => $jenisSertifikasi,
            'periode' => $periode,
            'bidangMinat' => $bidangMinat,
            'mataKuliah' => $mataKuliah,
            'user' => $user,
        ]);
    }

    public function update(Request $request, $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'id_vendor_sertifikasi' => 'required|integer',
                'id_jenis_sertifikasi' => 'required|integer',
                'id_periode' => 'required|integer',

                'id_bidang_minat' => 'required',
                'id_matakuliah' => 'required',
                'user_id' => 'nullable',

                'nama_sertifikasi' => 'required|string|min:5',
                'no_sertifikasi' => 'required|string|max:255',
                'jenis' => 'required',
                'tanggal' => 'required|date',
                'bukti_sertifikasi' => 'nullable|mimes:pdf|max:5120',
                'masa_berlaku' => 'required',
                'biaya' => 'required|string|max:255',
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi gagal.',
                    'msgField' => $validator->errors()
                ]);
            }

            $sertifikasi = SertifikasiModel::find($id);

            // Inisialisasi variabel untuk menyimpan path file
            $bukti_sertifikasi = null;

            /** @var User */
            $user = Auth::user();

            // Cek apakah file bukti sertifikasi diunggah
            if ($request->hasFile('bukti_sertifikasi')) {
                $bukti_sertifikasi = time() . '_' . $request->file('bukti_sertifikasi')->getClientOriginalName();
                $request->file('bukti_sertifikasi')->storeAs('public/images/', $bukti_sertifikasi);
            }

            if ($sertifikasi) {
                if ($request->hasFile('bukti_sertifikasi')) {
                    $sertifikasi->update([
                        'nama_sertifikasi'  => $request->nama_sertifikasi,
                        'no_sertifikasi'      => $request->no_sertifikasi,
                        'jenis'      => $request->jenis,
                        'tanggal'      => $request->tanggal,
                        'bukti_sertifikasi'      => $bukti_sertifikasi,
                        'masa_berlaku'      => $request->masa_berlaku,
                        'biaya'      => $request->biaya,
                        'id_vendor_sertifikasi'  => $request->id_vendor_sertifikasi,
                        'id_jenis_sertifikasi'  => $request->id_jenis_sertifikasi,
                        'id_periode'  => $request->id_periode
                    ]);
                } else {
                    $sertifikasi->update([
                        'nama_sertifikasi'  => $request->nama_sertifikasi,
                        'no_sertifikasi'      => $request->no_sertifikasi,
                        'jenis'      => $request->jenis,
                        'tanggal'      => $request->tanggal,
                        'masa_berlaku'      => $request->masa_berlaku,
                        'biaya'      => $request->biaya,
                        'id_vendor_sertifikasi'  => $request->id_vendor_sertifikasi,
                        'id_jenis_sertifikasi'  => $request->id_jenis_sertifikasi,
                        'id_periode'  => $request->id_periode
                    ]);
                }

                $sertifikasi->bidang_minat_sertifikasi()->sync($request->id_bidang_minat);
                $sertifikasi->mata_kuliah_sertifikasi()->sync($request->id_matakuliah);

                if ($user->id_level == 1) {
                    $sertifikasi->detail_peserta_sertifikasi()->sync($request->user_id);
                }
                return response()->json([
                    'status' => true,
                    'message' => 'Data berhasil diupdate'
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Data tidak ditemukan'
                ]);
            }

            return redirect('/');
        }
    }

    public function confirm(string $id)
    {
        $sertifikasi = SertifikasiModel::find($id);
        return view('sertifikasi.confirm', ['sertifikasi' => $sertifikasi]);
    }

    public function delete(Request $request, $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            // Temukan data sertifikasi berdasarkan ID
            $sertifikasi = SertifikasiModel::find($id);

            if ($sertifikasi) {
                // Hapus relasi many-to-many dengan MataKuliah dan BidangMinat
                $sertifikasi->mata_kuliah_sertifikasi()->detach();
                $sertifikasi->bidang_minat_sertifikasi()->detach();

                // Hapus data sertifikasi
                $sertifikasi->delete();

                return response()->json([
                    'status' => true,
                    'message' => 'Data berhasil dihapus'
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Data tidak ditemukan'
                ]);
            }
        }

        // Jika bukan request AJAX atau JSON, redirect ke halaman utama
        return redirect('/');
    }
}
