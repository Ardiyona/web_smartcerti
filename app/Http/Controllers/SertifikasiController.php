<?php

namespace App\Http\Controllers;

use App\Models\BidangMinatModel;
use App\Models\JenisSertifikasiModel;
use App\Models\MataKuliahModel;
use App\Models\PeriodeModel;
use App\Models\PesertaSertifikasiModel;
use App\Models\SertifikasiModel;
use App\Models\UserModel;
use Illuminate\Support\Facades\Log;
use App\Models\VendorSertifikasiModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Storage;

class SertifikasiController extends Controller
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

        $activeMenu = 'sertifikasi';

        $vendorSertifikasi = VendorSertifikasiModel::all();
        $periode = PeriodeModel::orderBy('tahun_periode', 'asc')->get();

        return view('sertifikasi.index', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'vendorSertifikasi' => $vendorSertifikasi,
            'periode' => $periode,
            'activeMenu' => $activeMenu
        ]);
    }

    // Ambil data user dalam bentuk json untuk datatables
    public function list(Request $request)
    {

        // Mengambil data user beserta level
        /** @var User */
        $user = Auth::user();

        if ($user->id_level == 1) {
            // Jika user adalah admin (id_level = 1)
            $sertifikasis = SertifikasiModel::select(
                'id_sertifikasi',
                'id_vendor_sertifikasi',
                'id_jenis_sertifikasi',
                'id_periode',
                'nama_sertifikasi',
                'jenis',
                'tanggal',
                'masa_berlaku',
                'kuota_peserta',
                'biaya',
                'status_sertifikasi'
            )
                ->with('vendor_sertifikasi', 'jenis_sertifikasi', 'periode', 'bidang_minat_sertifikasi', 'mata_kuliah_sertifikasi', 'detail_peserta_sertifikasi');

            if ($request->id_periode) {
                $sertifikasis->where('id_periode', $request->id_periode);
            }
        } else {
            // Jika user bukan admin, hanya tampilkan sertifikasi yang dimiliki oleh user tersebut
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
                ->where(function ($query) {
                    $query->where('status_sertifikasi', 'terima')
                        ->orWhereNull('status_sertifikasi');
                })
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
            if ($request->id_periode) {
                $sertifikasis->where('id_periode', $request->id_periode);
            }
        }

        // Mengembalikan data dengan DataTables
        return DataTables::of($sertifikasis)
            ->addIndexColumn()
            ->addColumn('no_sertifikasi', function ($sertifikasi) use ($user) {
                $getNoSertifikasi = function ($peserta) {
                    return $peserta->pivot->no_sertifikasi ?: null; // Jika kosong, kembalikan null
                };

                // Jika user bukan admin atau pimpinan, hanya tampilkan nomor sertifikasi milik user tersebut
                if ($user->id_level != 1 && $user->id_level != 2) {
                    $noSertifikasi = $sertifikasi->detail_peserta_sertifikasi
                        ->where('user_id', $user->user_id) // Filter nomor sertifikasi milik user
                        ->map($getNoSertifikasi) // Menggunakan fungsi untuk memproses setiap peserta
                        ->filter() // Hanya menyimpan nilai yang tidak null
                        ->implode(', ');

                    return $noSertifikasi ?: '-'; // Jika tidak ada no_sertifikasi sama sekali, tampilkan "-"
                }

                // Jika admin atau pimpinan, tampilkan semua nomor sertifikasi
                $noSertifikasi = $sertifikasi->detail_peserta_sertifikasi
                    ->map($getNoSertifikasi) // Menggunakan fungsi untuk memproses setiap peserta
                    ->filter() // Hanya menyimpan nilai yang tidak null
                    ->implode(', ');

                return $noSertifikasi ?: '-';
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

            ->addColumn('aksi', function ($sertifikasi) {
                $levelId = Auth::user();
                if ($levelId->id_level == 1) {
                    $btn  = '<button onclick="modalAction(\'' . url('/sertifikasi/' . $sertifikasi->id_sertifikasi . '/admin_detail') . '\')" class="btn btn-success btn-sm">Detail</button> ';
                    $btn .= '<button onclick="modalAction(\'' . url('/sertifikasi/' . $sertifikasi->id_sertifikasi . '/admin_show_edit') . '\')" class="btn btn-info btn-sm">Upload</button> ';
                    $btn .= '<button onclick="modalAction(\'' . url('/sertifikasi/' . $sertifikasi->id_sertifikasi . '/edit') . '\')" class="btn btn-warning btn-sm">Edit</button> ';
                    $btn .= '<button onclick="modalAction(\'' . url('/sertifikasi/' . $sertifikasi->id_sertifikasi . '/confirm') . '\')" class="btn btn-danger btn-sm">Hapus</button> ';

                    // if ($sertifikasi->status_sertifikasi == 'menunggu') {
                    //     $btn .= '<button onclick="modalAction(\'' . url('/sertifikasi/' . $sertifikasi->id_sertifikasi . '/create_rekomendasi_peserta') . '\')" class="btn btn-info btn-sm">Peserta</button> ';
                    // }
                } else {
                    $btn = '<button onclick="modalAction(\'' . url('/sertifikasi/' . $sertifikasi->id_sertifikasi . '/show') . '\')" class="btn btn-info btn-sm">Detail</button> ';
                    $btn .= '<button onclick="modalAction(\'' . url('/sertifikasi/' . $sertifikasi->id_sertifikasi . '/edit') . '\')" class="btn btn-warning btn-sm">Edit</button> ';
                    $btn .= '<button onclick="modalAction(\'' . url('/sertifikasi/' . $sertifikasi->id_sertifikasi . '/confirm') . '\')" class="btn btn-danger btn-sm">Hapus</button> ';
                }

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
        $user = UserModel::select('user_id', 'nama_lengkap')
            ->where('id_level', '!=', 1)->get();
        // dd($mataKuliah);

        $userid = Auth::user();

        if ($userid->id_level == 1) {
            return view('sertifikasi.admin_create')->with([
                'vendorSertifikasi' => $vendorSertifikasi,
                'jenisSertifikasi' => $jenisSertifikasi,
                'periode' => $periode,
                'bidangMinat' => $bidangMinat,
                'mataKuliah' => $mataKuliah,
                'user' => $user,
            ]);
        } else {
            return view('sertifikasi.create')->with([
                'vendorSertifikasi' => $vendorSertifikasi,
                'jenisSertifikasi' => $jenisSertifikasi,
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
                'id_vendor_sertifikasi' => 'required|integer',
                'id_jenis_sertifikasi' => 'required|integer',
                'id_periode' => 'required|integer',

                'id_bidang_minat' => 'required',
                'id_matakuliah' => 'required',
                'user_id' => 'nullable',

                'nama_sertifikasi' => 'required|string|min:5',
                'jenis' => 'required',
                'tanggal' => 'required|date',
                'masa_berlaku' => 'required',
                'kuota_peserta' => 'nullable|integer',
                'biaya' => 'required|string|max:255',

                'no_sertifikasi' => 'nullable|string|unique:detail_peserta_sertifikasi,no_sertifikasi',
                'bukti_sertifikasi' => 'nullable|mimes:pdf|max:5120'
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors()
                ]);
            }

            $bukti_sertifikasi = null;

            /** @var User */
            $user = Auth::user();
            if ($user->id_level == 1) {
                // Ambil jumlah user yang dipilih sebagai kuota peserta
                $kuotaPeserta = count($request->user_id);
                // Simpan data sertifikasi
                $sertifikasi = SertifikasiModel::create([
                    'nama_sertifikasi'  => $request->nama_sertifikasi,
                    'jenis'      => $request->jenis,
                    'tanggal'      => $request->tanggal,
                    'masa_berlaku'      => $request->masa_berlaku,
                    'kuota_peserta'      => $kuotaPeserta,
                    'biaya'      => $request->biaya,
                    'id_vendor_sertifikasi'  => $request->id_vendor_sertifikasi,
                    'id_jenis_sertifikasi'  => $request->id_jenis_sertifikasi,
                    'id_periode'  => $request->id_periode,
                ]);

                $sertifikasi->bidang_minat_sertifikasi()->sync($request->id_bidang_minat);
                $sertifikasi->mata_kuliah_sertifikasi()->sync($request->id_matakuliah);

                // Untuk admin, attach users with additional pivot data
                if (!empty($request->user_id)) {
                    $userData = [];
                    foreach ($request->user_id as $userId) {
                        $userData[$userId] = [
                            'no_sertifikasi' => null,
                            'bukti_sertifikasi' => null
                        ];
                    }
                    $sertifikasi->detail_peserta_sertifikasi()->attach($userData);
                }

                return response()->json([
                    'status' => true,
                    'message' => 'Data berhasil disimpan'
                ]);
            } else {
                if ($request->hasFile('bukti_sertifikasi')) {
                    $bukti_sertifikasi = time() . '_' . $request->file('bukti_sertifikasi')->getClientOriginalName();
                    $request->file('bukti_sertifikasi')->storeAs('public/bukti_sertifikasi/', $bukti_sertifikasi);
                }
                $sertifikasi = SertifikasiModel::create([
                    'nama_sertifikasi'  => $request->nama_sertifikasi,
                    'jenis'      => $request->jenis,
                    'tanggal'      => $request->tanggal,
                    'masa_berlaku'      => $request->masa_berlaku,
                    'kuota_peserta'      => $request->kuota_peserta,
                    'biaya'      => $request->biaya,
                    'id_vendor_sertifikasi'  => $request->id_vendor_sertifikasi,
                    'id_jenis_sertifikasi'  => $request->id_jenis_sertifikasi,
                    'id_periode'  => $request->id_periode,
                ]);

                $sertifikasi->bidang_minat_sertifikasi()->sync($request->id_bidang_minat);
                $sertifikasi->mata_kuliah_sertifikasi()->sync($request->id_matakuliah);

                // Attach current user to sertifikasi
                $sertifikasi->detail_peserta_sertifikasi()->attach(Auth::id(), [
                    'no_sertifikasi' => $request->no_sertifikasi,
                    'bukti_sertifikasi' => $bukti_sertifikasi
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
        $sertifikasi = SertifikasiModel::with('vendor_sertifikasi', 'jenis_sertifikasi', 'periode', 'bidang_minat_sertifikasi', 'mata_kuliah_sertifikasi')->find($id);
        return view('sertifikasi.show', ['sertifikasi' => $sertifikasi]);
    }

    public function edit(string $id)
    {
        $sertifikasi = SertifikasiModel::with('detail_peserta_sertifikasi')->find($id);

        $vendorSertifikasi = VendorSertifikasiModel::select('id_vendor_sertifikasi', 'nama')->get();
        $jenisSertifikasi = JenisSertifikasiModel::select('id_jenis_sertifikasi', 'nama_jenis_sertifikasi')->get();
        $periode = PeriodeModel::select('id_periode', 'tahun_periode')->get();

        $bidangMinat = BidangMinatModel::select('id_bidang_minat', 'nama_bidang_minat')->get();
        $mataKuliah = MataKuliahModel::select('id_matakuliah', 'nama_matakuliah')->get();
        $user = UserModel::select('user_id', 'nama_lengkap')
            ->where('id_level', '!=', 1)->get();

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
                'jenis' => 'required',
                'tanggal' => 'required|date',
                'masa_berlaku' => 'required',
                'kuota_peserta' => 'required|integer',
                'biaya' => 'required|string|max:255',

                'no_sertifikasi' => 'nullable|string',
                'bukti_sertifikasi.*' => 'nullable|mimes:pdf|max:5120'
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

            $oldFile = DB::table('detail_peserta_sertifikasi')
                ->where('user_id', $user->user_id)
                ->where('id_sertifikasi', $id)
                ->value('bukti_sertifikasi');

            // Cek apakah file bukti sertifikasi diunggah
            if ($request->hasFile('bukti_sertifikasi')) {
                if ($oldFile && Storage::exists('public/bukti_sertifikasi/' . $oldFile)) {
                    Storage::delete('public/bukti_sertifikasi/' . $oldFile);
                }
                $bukti_sertifikasi = time() . '_' . $request->file('bukti_sertifikasi')->getClientOriginalName();
                $request->file('bukti_sertifikasi')->storeAs('public/bukti_sertifikasi/', $bukti_sertifikasi);
            }

            if ($sertifikasi) {
                $kuotaPeserta = $request->kuota_peserta;
                if ($user->id_level == 1) {
                    $kuotaPeserta = count($request->user_id);
                }
                if ($request->hasFile('bukti_sertifikasi')) {
                    $sertifikasi->update([
                        'nama_sertifikasi'  => $request->nama_sertifikasi,
                        'jenis'      => $request->jenis,
                        'tanggal'      => $request->tanggal,
                        'masa_berlaku'      => $request->masa_berlaku,
                        'kuota_peserta'      => $kuotaPeserta,
                        'biaya'      => $request->biaya,
                        'id_vendor_sertifikasi'  => $request->id_vendor_sertifikasi,
                        'id_jenis_sertifikasi'  => $request->id_jenis_sertifikasi,
                        'id_periode'  => $request->id_periode,
                    ]);
                    $sertifikasi->detail_peserta_sertifikasi()->updateExistingPivot(
                        Auth::id(),
                        [
                            'no_sertifikasi' => $request->no_sertifikasi,
                            'bukti_sertifikasi' => $bukti_sertifikasi ?? $sertifikasi->detail_peserta_sertifikasi()->first()->pivot->bukti_sertifikasi
                        ]
                    );
                } else {
                    $sertifikasi->update([
                        'nama_sertifikasi'  => $request->nama_sertifikasi,
                        'jenis'      => $request->jenis,
                        'tanggal'      => $request->tanggal,
                        'masa_berlaku'      => $request->masa_berlaku,
                        'kuota_peserta'      => $kuotaPeserta,
                        'biaya'      => $request->biaya,
                        'id_vendor_sertifikasi'  => $request->id_vendor_sertifikasi,
                        'id_jenis_sertifikasi'  => $request->id_jenis_sertifikasi,
                        'id_periode'  => $request->id_periode,
                    ]);
                    $sertifikasi->detail_peserta_sertifikasi()->updateExistingPivot(
                        Auth::id(),
                        [
                            'no_sertifikasi' => $request->no_sertifikasi,
                        ]
                    );
                }

                $sertifikasi->bidang_minat_sertifikasi()->sync($request->id_bidang_minat);
                $sertifikasi->mata_kuliah_sertifikasi()->sync($request->id_matakuliah);
                if ($user->id_level == 1 && !empty($request->user_id)) {
                    // Jika user_id adalah string, ubah menjadi array
                    $userIds = is_array($request->user_id) ? $request->user_id : [$request->user_id];

                    $userData = [];
                    foreach ($userIds as $userId) {
                        $userData[$userId] = [
                            'no_sertifikasi' => $request->no_sertifikasi,
                        ];
                    }
                    $sertifikasi->detail_peserta_sertifikasi()->sync($userData);
                }
                return response()->json([
                    'status' => true,
                    'message' => 'Data berhasil diupdate',
                    'response' => 'success'
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
            /** @var User $currentUser */
            $currentUser = Auth::user(); // Mendapatkan user login

            // Temukan data sertifikasi berdasarkan ID
            $sertifikasi = SertifikasiModel::find($id);

            if ($sertifikasi) {
                if ($currentUser->id_level == 1) {
                    // Hapus file bukti sertifikasi dari setiap detail_peserta_sertifikasi terkait
                    $detailPesertas = DB::table('detail_peserta_sertifikasi')
                        ->where('id_sertifikasi', $id)
                        ->get();

                    foreach ($detailPesertas as $detail) {
                        if ($detail->bukti_sertifikasi && Storage::exists('public/bukti_sertifikasi/' . $detail->bukti_sertifikasi)) {
                            Storage::delete('public/bukti_sertifikasi/' . $detail->bukti_sertifikasi);
                        }
                    }
                    // Jika user adalah admin, hapus data sertifikasi sepenuhnya
                    $sertifikasi->mata_kuliah_sertifikasi()->detach();
                    $sertifikasi->bidang_minat_sertifikasi()->detach();
                    PesertaSertifikasiModel::where('id_sertifikasi', $id)->delete();

                    // Hapus data sertifikasi
                    $sertifikasi->delete();

                    return response()->json([
                        'status' => true,
                        'message' => 'Data sertifikasi berhasil dihapus.'
                    ]);
                } else {
                    // Jika user bukan admin, hapus hanya relasi pada tabel pivot berdasarkan user login
                    $detailPeserta = DB::table('detail_peserta_sertifikasi')
                        ->where('id_sertifikasi', $id)
                        ->where('user_id', $currentUser->user_id)
                        ->first(); // Ambil data pivot spesifik

                    if ($detailPeserta) {
                        // Hapus file bukti sertifikasi jika ada
                        if ($detailPeserta->bukti_sertifikasi && Storage::exists('public/bukti_sertifikasi/' . $detailPeserta->bukti_sertifikasi)) {
                            Storage::delete('public/bukti_sertifikasi/' . $detailPeserta->bukti_sertifikasi);
                        }
                        // Hapus data berdasarkan primary key tabel pivot
                        DB::table('detail_peserta_sertifikasi')
                            ->where('id_detail_peserta_sertifikasi', $detailPeserta->id_detail_peserta_sertifikasi)
                            ->delete();

                        return response()->json([
                            'status' => true,
                            'message' => 'Data relasi pada detail_peserta_sertifikasi berhasil dihapus.'
                        ]);
                    } else {
                        return response()->json([
                            'status' => false,
                            'message' => 'Data relasi tidak ditemukan untuk user ini.'
                        ]);
                    }
                }
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Data tidak ditemukan.'
                ]);
            }
        }

        // Jika bukan request AJAX atau JSON, redirect ke halaman utama
        return redirect('/');
    }


    public function create_rekomendasi()
    {
        // Mengambil id_level dan nama_level dari tabel level
        $vendorSertifikasi = VendorSertifikasiModel::select('id_vendor_sertifikasi', 'nama')->get();
        $jenisSertifikasi = JenisSertifikasiModel::select('id_jenis_sertifikasi', 'nama_jenis_sertifikasi')->get();
        $periode = PeriodeModel::select('id_periode', 'tahun_periode')->get();

        $bidangMinat = BidangMinatModel::select('id_bidang_minat', 'nama_bidang_minat')->get();
        $mataKuliah = MataKuliahModel::select('id_matakuliah', 'nama_matakuliah')->get();
        $user = UserModel::select('user_id', 'nama_lengkap')->get();

        return view('sertifikasi.create_rekomendasi')->with([
            'vendorSertifikasi' => $vendorSertifikasi,
            'jenisSertifikasi' => $jenisSertifikasi,
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
                'id_vendor_sertifikasi' => 'required|integer',
                'id_jenis_sertifikasi' => 'required|integer',
                'id_periode' => 'required|integer',

                'id_bidang_minat' => 'required|array',
                'id_matakuliah' => 'required|array',
                'user_id' => 'nullable|array',

                'nama_sertifikasi' => 'required|string|min:5',
                'jenis' => 'required',
                'tanggal' => 'required|date',
                'biaya' => 'required|string|max:255',
                'kuota_peserta' => "nullable|integer"
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors()
                ]);
            }

            $kuotaPeserta = count($request->user_id);

            // Simpan data sertifikasi
            $sertifikasi = SertifikasiModel::create([
                'nama_sertifikasi'  => $request->nama_sertifikasi,
                'jenis'      => $request->jenis,
                'tanggal'      => $request->tanggal,
                'biaya'      => $request->biaya,
                'kuota_peserta'      => $kuotaPeserta,
                'id_vendor_sertifikasi'  => $request->id_vendor_sertifikasi,
                'id_jenis_sertifikasi'  => $request->id_jenis_sertifikasi,
                'id_periode'  => $request->id_periode,
                'status_sertifikasi' => 'menunggu'
            ]);

            // Sinkronkan bidang minat dan mata kuliah
            $sertifikasi->bidang_minat_sertifikasi()->sync($request->id_bidang_minat);
            $sertifikasi->mata_kuliah_sertifikasi()->sync($request->id_matakuliah);
            $sertifikasi->detail_peserta_sertifikasi()->sync($request->user_id);

            // Jika user_id tidak disediakan, lakukan seleksi otomatis
            if (empty($request->user_id)) {
                // Ambil ID bidang minat dan mata kuliah yang terkait dengan sertifikasi
                $sertifikasiBidangMinat = $sertifikasi->bidang_minat_sertifikasi->pluck('id_bidang_minat')->toArray();
                $sertifikasiMataKuliah = $sertifikasi->mata_kuliah_sertifikasi->pluck('id_matakuliah')->toArray();

                $user = UserModel::where('id_level', '!=', 1)
                    ->withCount([
                        'detail_daftar_user_matakuliah as mata_kuliah_count' => function ($query) use ($sertifikasiMataKuliah) {
                            $query->whereIn('detail_daftar_user_matakuliah.id_matakuliah', $sertifikasiMataKuliah);
                        },
                        'detail_daftar_user_bidang_minat as bidang_minat_count' => function ($query) use ($sertifikasiBidangMinat) {
                            $query->whereIn('detail_daftar_user_bidang_minat.id_bidang_minat', $sertifikasiBidangMinat);
                        }
                    ])
                    ->orderByDesc('mata_kuliah_count')
                    ->orderByDesc('bidang_minat_count')
                    ->take(10) // Misalnya, ambil 10 peserta teratas
                    ->pluck('user_id')
                    ->toArray();

                // $request->user_id = $user;
            }

            // Simpan peserta sertifikasi
            if (!empty($request->user_id)) {
                $sertifikasi->detail_peserta_sertifikasi()->sync($request->user_id);
                $sertifikasi->update([
                    'kuota_peserta' => count($request->user_id)
                ]);
            }

            return response()->json([
                'status' => true,
                'message' => 'Data sertifikasi berhasil disimpan'
            ]);
        }
        return redirect('/');
    }

    public function filterPeserta(Request $request)
    {
        // Ambil bidang minat dan mata kuliah yang dipilih
        $bidangMinatIds = $request->input('bidang_minat', []);
        $mataKuliahIds = $request->input('mata_kuliah', []);

        // Ambil peserta
        $user = UserModel::select('user_id', 'nama_lengkap') // Pastikan 'nama' termasuk dalam select
            ->withCount([
                'detail_daftar_user_matakuliah as mata_kuliah_count' => function ($query) use ($mataKuliahIds) {
                    $query->whereIn('detail_daftar_user_matakuliah.id_matakuliah', $mataKuliahIds);
                },
                'detail_daftar_user_bidang_minat as bidang_minat_count' => function ($query) use ($bidangMinatIds) {
                    $query->whereIn('detail_daftar_user_bidang_minat.id_bidang_minat', $bidangMinatIds);
                }
            ])
            ->where('id_level', '!=', 1)
            ->orderByDesc('mata_kuliah_count')
            ->orderByDesc('bidang_minat_count')
            ->get();

        // Pastikan ada fallback jika tidak ada peserta yang cocok
        if ($user->isEmpty()) {
            $user = UserModel::where('id_level', '!=', 1) // Eksklusi admin
                ->get(); // Ambil semua peserta tanpa urutan
        }

        return response()->json([
            'status' => true,
            'data' => $user
        ]);
    }

    // public function create_rekomendasi_peserta($id)
    // {
    //     $sertifikasi = SertifikasiModel::with('detail_peserta_sertifikasi')->find($id);

    //     // Ambil ID bidang minat dan mata kuliah yang terkait dengan sertifikasi
    //     $sertifikasiBidangMinat = $sertifikasi->bidang_minat_sertifikasi->pluck('id_bidang_minat')->toArray();
    //     $sertifikasiMataKuliah = $sertifikasi->mata_kuliah_sertifikasi->pluck('id_matakuliah')->toArray();

    //     $user = UserModel::with(['detail_daftar_user_matakuliah', 'detail_daftar_user_bidang_minat'])
    //         ->where('id_level', '!=', 1) // Tambahkan kondisi ini untuk mengecualikan admin
    //         ->withCount([
    //             'detail_daftar_user_matakuliah as mata_kuliah_count' => function ($query) use ($sertifikasiMataKuliah) {
    //                 $query->whereIn('detail_daftar_user_matakuliah.id_matakuliah', $sertifikasiMataKuliah);
    //             },
    //             'detail_daftar_user_bidang_minat as bidang_minat_count' => function ($query) use ($sertifikasiBidangMinat) {
    //                 $query->whereIn('detail_daftar_user_bidang_minat.id_bidang_minat', $sertifikasiBidangMinat);
    //             }
    //         ])
    //         ->orderByDesc('mata_kuliah_count')
    //         ->orderByDesc('bidang_minat_count')
    //         ->get();

    //     return view('sertifikasi.create_rekomendasi_peserta')->with([
    //         'user' => $user,
    //         'sertifikasi' => $sertifikasi,
    //     ]);
    // }

    // public function store_rekomendasi_peserta(Request $request, $id)
    // {
    //     if ($request->ajax() || $request->wantsJson()) {
    //         $rules = [
    //             'user_id' => 'required',
    //             'kuota_peserta' => 'nullable|integer',
    //         ];

    //         $validator = Validator::make($request->all(), $rules);

    //         if ($validator->fails()) {
    //             return response()->json([
    //                 'status' => false,
    //                 'message' => 'Validasi Gagal',
    //                 'msgField' => $validator->errors()
    //             ]);
    //         }

    //         $kuotaPeserta = count($request->user_id);
    //         $sertifikasi = SertifikasiModel::find($id);
    //         $sertifikasi->update([
    //             'kuota_peserta'      => $kuotaPeserta,
    //         ]);

    //         if (!empty($request->user_id)) {
    //             $sertifikasi->detail_peserta_sertifikasi()->sync($request->user_id);
    //         }

    //         // Menyimpan user_id ke dalam pivot tabel dengan status 'menunggu'

    //         return response()->json([
    //             'status' => true,
    //             'message' => 'Data sertifikasi berhasil disimpan'
    //         ]);
    //     }
    //     return redirect('/');
    // }

    public function admin_detail(String $id)
    {
        $sertifikasi = SertifikasiModel::with('vendor_sertifikasi', 'jenis_sertifikasi', 'periode', 'bidang_minat_sertifikasi', 'mata_kuliah_sertifikasi')->find($id);
        return view('sertifikasi.admin_detail', ['sertifikasi' => $sertifikasi]);
    }



    public function admin_show_edit(string $id)
    {
        $sertifikasi = SertifikasiModel::with(['detail_peserta_sertifikasi' => function ($query) {
            $query->select('user.user_id as user_id', 'user.nama_lengkap');
        }])->find($id);

        return view('sertifikasi.admin_show', [
            'sertifikasi' => $sertifikasi,
        ]);
    }

    public function admin_show_update(Request $request, $sertifikasiId)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'bukti_sertifikasi.*' => 'nullable|mimes:pdf|max:5120',
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi gagal.',
                    'msgField' => $validator->errors()
                ]);
            }

            // Ambil daftar user_id yang terdaftar di sertifikasi
            $pesertaIds = DB::table('detail_peserta_sertifikasi')
                ->where('id_sertifikasi', $sertifikasiId)
                ->pluck('user_id')
                ->toArray();

            $userData = []; // Simpan data hasil upload

            if ($request->hasFile('bukti_sertifikasi')) {
                foreach ($request->file('bukti_sertifikasi', []) as $userId => $file) {
                    // Pastikan user_id termasuk dalam daftar peserta
                    if (!in_array($userId, $pesertaIds)) {
                        Log::warning("User ID: $userId tidak terdaftar pada sertifikasi dengan ID: $sertifikasiId");
                        continue;
                    }

                    if ($file->isValid()) {
                        // Cari file lama di database
                        $oldFile = DB::table('detail_peserta_sertifikasi')
                            ->where('user_id', $userId)
                            ->where('id_sertifikasi', $sertifikasiId)
                            ->value('bukti_sertifikasi');

                        // Hapus file lama jika ada
                        if ($oldFile && Storage::exists('public/bukti_sertifikasi/' . $oldFile)) {
                            Storage::delete('public/bukti_sertifikasi/' . $oldFile);
                        }

                        // Simpan file baru
                        $fileName = time() . '_' . $file->getClientOriginalName();
                        $file->storeAs('public/bukti_sertifikasi', $fileName);

                        // Simpan ke database
                        $data = [
                            'bukti_sertifikasi' => $fileName,
                            'updated_at' => now(),
                        ];

                        $result = DB::table('detail_peserta_sertifikasi')
                            ->where('user_id', $userId)
                            ->where('id_sertifikasi', $sertifikasiId)
                            ->update($data);

                        if (!$result) {
                            return response()->json([
                                'status' => false,
                                'message' => "Gagal menyimpan data untuk User ID: $userId"
                            ]);
                        }

                        $userData[$userId] = $data;
                    } else {
                        return response()->json([
                            'status' => false,
                            'message' => "File untuk User ID: $userId tidak valid atau gagal diunggah.",
                        ]);
                    }
                }

                // Jika ada data yang berhasil diunggah, kembalikan respons berhasil
                if (!empty($userData)) {
                    return response()->json([
                        'status' => true,
                        'message' => 'File berhasil diunggah dan disimpan ke database.',
                        'data' => $userData
                    ]);
                } else {
                    return response()->json([
                        'status' => false,
                        'message' => 'Tidak ada file yang diunggah untuk peserta yang terdaftar.',
                    ]);
                }
            }

            return response()->json([
                'status' => false,
                'message' => 'Tidak ada file yang diunggah.',
            ]);
        }
    }
}
