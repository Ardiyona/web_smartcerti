<?php

namespace App\Http\Controllers;

use App\Models\BidangMinatModel;
use App\Models\JenispelatihanModel;
use App\Models\MataKuliahModel;
use App\Models\PeriodeModel;
use App\Models\PelatihanModel;
use App\Models\UserModel;
use App\Models\VendorPelatihanModel;
use App\Models\VendorSertifikasiModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class PelatihanController extends Controller
{
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Daftar Pelatihan',
            'list'  => ['Home', 'Pelatihan']
        ];

        $page = (object) [
            'title' => 'Daftar pelatihan yang terdaftar dalam sistem'
        ];

        $activeMenu = 'pelatihan';

        $vendorpelatihan = VendorPelatihanModel::all();

        return view('pelatihan.index', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'vendorpelatihan' => $vendorpelatihan,
            'activeMenu' => $activeMenu
        ]);
    }

    // Ambil data user dalam bentuk json untuk datatables
    public function list()
    {
        // Mengambil data user beserta level
        /** @var User */
        $user = Auth::user();

        // $pelatihans = pelatihanModel::with('detail_peserta_pelatihan')->get();
        // return response()->json($pelatihans);

        // Jika user bukan admin (id_level ≠ 1), tampilkan hanya pelatihan miliknya
        if ($user->id_level != 1) {
            // Mengambil pelatihan yang hanya dimiliki oleh user yang sedang login
            $pelatihans = $user->detail_peserta_pelatihan()
                ->select(
                    'pelatihan.id_pelatihan',
                    'pelatihan.id_vendor_pelatihan',
                    'pelatihan.id_jenis_pelatihan',
                    'pelatihan.id_periode',
                    'pelatihan.nama_pelatihan',
                    'pelatihan.lokasi',
                    'pelatihan.level_pelatihan',
                    'pelatihan.tanggal',
                    'pelatihan.kuota_peserta',
                    'pelatihan.biaya'
                )
                ->with('vendor_pelatihan', 'jenis_pelatihan', 'periode', 'bidang_minat_pelatihan', 'mata_kuliah_pelatihan', 'detail_peserta_pelatihan');
        } else {
            // Jika admin (id_level = 1), tampilkan semua pelatihan
            $pelatihans = PelatihanModel::select(
                'id_pelatihan',
                'id_vendor_pelatihan',
                'id_jenis_pelatihan',
                'id_periode',
                'nama_pelatihan',
                'lokasi',
                'level_pelatihan',
                'tanggal',
                'kuota_peserta',
                'biaya'
            )
                ->with('vendor_pelatihan', 'jenis_pelatihan', 'periode', 'bidang_minat_pelatihan', 'mata_kuliah_pelatihan', 'detail_peserta_pelatihan');
        }

        // Mengembalikan data dengan DataTables
        return DataTables::of($pelatihans)
            ->addIndexColumn()

            ->addColumn('bidang_minat', function ($pelatihan) {
                return $pelatihan->bidang_minat_pelatihan->pluck('nama_bidang_minat')->implode(', ');
            })
            ->addColumn('mata_kuliah', function ($pelatihan) {
                return $pelatihan->mata_kuliah_pelatihan->pluck('nama_matakuliah')->implode(', ');
            })
            ->addColumn('peserta_pelatihan', function ($pelatihan) {
                return $pelatihan->detail_peserta_pelatihan->pluck('nama_lengkap')->implode(', ');
            })
            ->addColumn('aksi', function ($pelatihan) {
                $btn = '<button onclick="modalAction(\'' . url('/pelatihan/' . $pelatihan->id_pelatihan . '/show') . '\')" class="btn btn-info btn-sm">Detail</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/pelatihan/' . $pelatihan->id_pelatihan . '/edit') . '\')" class="btn btn-warning btn-sm">Edit</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/pelatihan/' . $pelatihan->id_pelatihan . '/confirm') . '\')" class="btn btn-danger btn-sm">Hapus</button> ';
                return $btn;
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    public function create()
    {
        // Mengambil id_level dan nama_level dari tabel level
        $vendorpelatihan = VendorPelatihanModel::select('id_vendor_pelatihan', 'nama')->get();
        $jenispelatihan = JenisPelatihanModel::select('id_jenis_pelatihan', 'nama_jenis_pelatihan')->get();
        $periode = PeriodeModel::select('id_periode', 'tahun_periode')->get();

        $bidangMinat = BidangMinatModel::select('id_bidang_minat', 'nama_bidang_minat')->get();
        $mataKuliah = MataKuliahModel::select('id_matakuliah', 'nama_matakuliah')->get();
        $user = UserModel::select('user_id', 'nama_lengkap')->get();
        // dd($mataKuliah);

        $userid = Auth::user();

        if ($userid->id_level == 1) {
            return view('pelatihan.admin_create')->with([
                'vendorpelatihan' => $vendorpelatihan,
                'jenispelatihan' => $jenispelatihan,
                'periode' => $periode,
                'bidangMinat' => $bidangMinat,
                'mataKuliah' => $mataKuliah,
                'user' => $user,
            ]);
        } else {
            return view('pelatihan.create')->with([
                'vendorpelatihan' => $vendorpelatihan,
                'jenispelatihan' => $jenispelatihan,
                'periode' => $periode,
                'bidangMinat' => $bidangMinat,
                'mataKuliah' => $mataKuliah,
                'user' => $user,
            ]);
        }
    }

    public function store(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'id_vendor_pelatihan' => 'required|integer',
                'id_jenis_pelatihan' => 'required|integer',
                'id_periode' => 'required|integer',

                'id_bidang_minat' => 'required',
                'id_matakuliah' => 'required',
                'user_id' => 'nullable',

                'nama_pelatihan' => 'required|string|min:5',
                'lokasi' => 'required',
                'level_pelatihan' => 'required',
                'tanggal' => 'required|date',
                'kuota_peserta' => 'nullable|integer',
                'biaya' => 'required|string|max:255',

                'bukti_pelatihan' => 'nullable|mimes:pdf|max:5120'
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors()
                ]);
            }

            $bukti_pelatihan = null;

            /** @var User */
            $user = Auth::user();
            if ($user->id_level == 1) {
                // Ambil jumlah user yang dipilih sebagai kuota peserta
                $kuotaPeserta = count($request->user_id);
                // Simpan data pelatihan
                $pelatihan = PelatihanModel::create([
                    'nama_pelatihan'  => $request->nama_pelatihan,
                    'lokasi'      => $request->lokasi,
                    'level_pelatihan'      => $request->level_pelatihan,
                    'tanggal'      => $request->tanggal,
                    'kuota_peserta'      => $kuotaPeserta,
                    'biaya'      => $request->biaya,
                    'id_vendor_pelatihan'  => $request->id_vendor_pelatihan,
                    'id_jenis_pelatihan'  => $request->id_jenis_pelatihan,
                    'id_periode'  => $request->id_periode,
                    'status_pelatihan' => 'menunggu'
                ]);

                $pelatihan->bidang_minat_pelatihan()->sync($request->id_bidang_minat);
                $pelatihan->mata_kuliah_pelatihan()->sync($request->id_matakuliah);

                // Untuk admin, attach users with additional pivot data
                if (!empty($request->user_id)) {
                    $userData = [];
                    foreach ($request->user_id as $userId) {
                        $userData[$userId] = [
                            'bukti_pelatihan' => null
                        ];
                    }
                    $pelatihan->detail_peserta_pelatihan()->attach($userData);
                }

                return response()->json([
                    'status' => true,
                    'message' => 'Data berhasil disimpan'
                ]);
            } else {
                if ($request->hasFile('bukti_pelatihan')) {
                    $bukti_pelatihan = time() . '_' . $request->file('bukti_pelatihan')->getClientOriginalName();
                    $request->file('bukti_pelatihan')->storeAs('public/bukti_pelatihan/', $bukti_pelatihan);
                }
                $pelatihan = PelatihanModel::create([
                    'nama_pelatihan'  => $request->nama_pelatihan,
                    'lokasi'      => $request->lokasi,
                    'level_pelatihan'      => $request->level_pelatihan,
                    'tanggal'      => $request->tanggal,
                    'kuota_peserta'      => $request->kuota_peserta,
                    'biaya'      => $request->biaya,
                    'id_vendor_pelatihan'  => $request->id_vendor_pelatihan,
                    'id_jenis_pelatihan'  => $request->id_jenis_pelatihan,
                    'id_periode'  => $request->id_periode,
                    'status_pelatihan' => 'menunggu'
                ]);

                $pelatihan->bidang_minat_pelatihan()->sync($request->id_bidang_minat);
                $pelatihan->mata_kuliah_pelatihan()->sync($request->id_matakuliah);

                // Attach current user to pelatihan
                $pelatihan->detail_peserta_pelatihan()->attach(Auth::id(), [
                    'bukti_pelatihan' => $bukti_pelatihan
                ]);

                return response()->json([
                    'status' => true,
                    'message' => 'Data berhasil disimpan'
                ]);
            }
        }
        return redirect('/');
    }

    public function show(String $id)
    {
        $pelatihan = PelatihanModel::with('vendor_pelatihan', 'jenis_pelatihan', 'periode', 'bidang_minat_pelatihan', 'mata_kuliah_pelatihan')->find($id);

        return view('pelatihan.show', ['pelatihan' => $pelatihan]);
    }

    public function edit(string $id)
    {
        $pelatihan = PelatihanModel::find($id);

        $vendorpelatihan = VendorPelatihanModel::select('id_vendor_pelatihan', 'nama')->get();
        $jenispelatihan = JenisPelatihanModel::select('id_jenis_pelatihan', 'nama_jenis_pelatihan')->get();
        $periode = PeriodeModel::select('id_periode', 'tahun_periode')->get();

        $bidangMinat = BidangMinatModel::select('id_bidang_minat', 'nama_bidang_minat')->get();
        $mataKuliah = MataKuliahModel::select('id_matakuliah', 'nama_matakuliah')->get();
        $user = UserModel::select('user_id', 'nama_lengkap')->get();

        return view('pelatihan.edit', [
            'pelatihan' => $pelatihan,
            'vendorpelatihan' => $vendorpelatihan,
            'jenispelatihan' => $jenispelatihan,
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
                'id_vendor_pelatihan' => 'required|integer',
                'id_jenis_pelatihan' => 'required|integer',
                'id_periode' => 'required|integer',

                'id_bidang_minat' => 'required',
                'id_matakuliah' => 'required',
                'user_id' => 'nullable',

                'nama_pelatihan' => 'required|string|min:5',
                'lokasi' => 'required',
                'level_pelatihan' => 'required',
                'tanggal' => 'required|date',
                'bukti_pelatihan' => 'nullable|mimes:pdf|max:5120',
                'kuota_peserta' => 'required|integer',
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

            $pelatihan = PelatihanModel::find($id);

            // Inisialisasi variabel untuk menyimpan path file
            $bukti_pelatihan = null;

            /** @var User */
            $user = Auth::user();

            // Cek apakah file bukti pelatihan diunggah
            if ($request->hasFile('bukti_pelatihan')) {
                $bukti_pelatihan = time() . '_' . $request->file('bukti_pelatihan')->getClientOriginalName();
                $request->file('bukti_pelatihan')->storeAs('public/images/', $bukti_pelatihan);
            }

            if ($pelatihan) {
                if ($request->hasFile('bukti_pelatihan')) {
                    $pelatihan->update([
                        'nama_pelatihan'  => $request->nama_pelatihan,
                        'lokasi'      => $request->lokasi,
                        'level_pelatihan'      => $request->level_pelatihan,
                        'tanggal'      => $request->tanggal,
                        'bukti_pelatihan'      => $bukti_pelatihan,
                        'kuota_peserta'      => $request->kuota_peserta,
                        'biaya'      => $request->biaya,
                        'id_vendor_pelatihan'  => $request->id_vendor_pelatihan,
                        'id_jenis_pelatihan'  => $request->id_jenis_pelatihan,
                        'id_periode'  => $request->id_periode
                    ]);
                } else {
                    $pelatihan->update([
                        'nama_pelatihan'  => $request->nama_pelatihan,
                        'lokasi'      => $request->lokasi,
                        'level_pelatihan'      => $request->level_pelatihan,
                        'tanggal'      => $request->tanggal,
                        'kuota_peserta'      => $request->kuota_peserta,
                        'biaya'      => $request->biaya,
                        'id_vendor_pelatihan'  => $request->id_vendor_pelatihan,
                        'id_jenis_pelatihan'  => $request->id_jenis_pelatihan,
                        'id_periode'  => $request->id_periode
                    ]);
                }

                $pelatihan->bidang_minat_pelatihan()->sync($request->id_bidang_minat);
                $pelatihan->mata_kuliah_pelatihan()->sync($request->id_matakuliah);

                if ($user->id_level == 1) {
                    $pelatihan->detail_peserta_pelatihan()->sync($request->user_id);
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
        $pelatihan = PelatihanModel::find($id);
        return view('pelatihan.confirm', ['pelatihan' => $pelatihan]);
    }

    public function delete(Request $request, $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            // Temukan data pelatihan berdasarkan ID
            $pelatihan = PelatihanModel::find($id);

            if ($pelatihan) {
                // Hapus relasi many-to-many dengan MataKuliah dan BidangMinat
                $pelatihan->mata_kuliah_pelatihan()->detach();
                $pelatihan->bidang_minat_pelatihan()->detach();

                // Hapus data pelatihan
                $pelatihan->delete();

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

    public function create_rekomendasi()
    {
        // Mengambil id_level dan nama_level dari tabel level
        $vendorpelatihan = VendorPelatihanModel::select('id_vendor_pelatihan', 'nama')->get();
        $jenispelatihan = JenisPelatihanModel::select('id_jenis_pelatihan', 'nama_jenis_pelatihan')->get();
        $periode = PeriodeModel::select('id_periode', 'tahun_periode')->get();

        $bidangMinat = BidangMinatModel::select('id_bidang_minat', 'nama_bidang_minat')->get();
        $mataKuliah = MataKuliahModel::select('id_matakuliah', 'nama_matakuliah')->get();
        // $user = UserModel::select('user_id', 'nama_lengkap')->get();
        // Mengambil data user beserta status_pelatihan dari tabel pivot
        $user = UserModel::with(['detail_peserta_pelatihan' => function ($query) {
            $query->select(
                'detail_peserta_pelatihan.user_id',
                'detail_peserta_pelatihan.id_pelatihan',
                'detail_peserta_pelatihan.status_pelatihan'
            );
        }])->get();

        return view('pelatihan.create_rekomendasi')->with([
            'vendorpelatihan' => $vendorpelatihan,
            'jenispelatihan' => $jenispelatihan,
            'periode' => $periode,
            'bidangMinat' => $bidangMinat,
            'mataKuliah' => $mataKuliah,
            'user' => $user,
        ]);
    }

    public function store_rekomendasi(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'id_vendor_pelatihan' => 'required|integer',
                'id_jenis_pelatihan' => 'required|integer',
                'id_periode' => 'required|integer',

                'id_bidang_minat' => 'required',
                'id_matakuliah' => 'required',
                'user_id' => 'required',

                'nama_pelatihan' => 'required|string|min:5',
                'lokasi' => 'required',
                'level_pelatihan' => 'required',
                'tanggal' => 'required|date',
                'kuota_peserta' => 'required|integer',
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

            // Simpan data user dengan hanya field yang diperlukan
            $pelatihan = pelatihanModel::create([
                'nama_pelatihan'  => $request->nama_pelatihan,
                'lokasi'      => $request->lokasi,
                'level_pelatihan'      => $request->level_pelatihan,
                'tanggal'      => $request->tanggal,
                'kuota_peserta'      => $request->kuota_peserta,
                'biaya'      => $request->biaya,
                'id_vendor_pelatihan'  => $request->id_vendor_pelatihan,
                'id_jenis_pelatihan'  => $request->id_jenis_pelatihan,
                'id_periode'  => $request->id_periode
            ]);

            $pelatihan->bidang_minat_pelatihan()->sync($request->id_bidang_minat);
            $pelatihan->mata_kuliah_pelatihan()->sync($request->id_matakuliah);

            // Menyimpan user_id ke dalam pivot tabel dengan status 'menunggu'
            if (!empty($request->user_id)) {
                $userData = [];
                foreach ($request->user_id as $userId) {
                    $userData[$userId] = ['status_pelatihan' => 'menunggu'];
                }
                $pelatihan->detail_peserta_pelatihan()->attach($userData);
            }

            return response()->json([
                'status' => true,
                'message' => 'Data user berhasil disimpan'
            ]);
        }
        return redirect('/');
    }
}
