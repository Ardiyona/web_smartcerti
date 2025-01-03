<?php

namespace App\Http\Controllers;

use App\Models\BidangMinatModel;
use App\Models\JenisPelatihanModel;
use App\Models\MataKuliahModel;
use App\Models\PeriodeModel;
use App\Models\PelatihanModel;
use App\Models\PesertaPelatihanModel;
use App\Models\UserModel;
use App\Models\VendorPelatihanModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\PhpWord;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Storage;

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
        $periode = PeriodeModel::orderBy('tahun_periode', 'asc')->get();

        return view('pelatihan.index', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'vendorpelatihan' => $vendorpelatihan,
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

        // $pelatihans = pelatihanModel::with('detail_peserta_pelatihan')->get();
        // return response()->json($pelatihans);

        // Jika user adalah admin (id_level = 1) atau pimpinan (id_level = 2), tampilkan semua pelatihan
        if ($user->id_level == 1) {
            // Mengambil semua pelatihan yang ada di database
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
                'biaya',
                'status_pelatihan',
            )
                ->with('vendor_pelatihan', 'jenis_pelatihan', 'periode', 'bidang_minat_pelatihan', 'mata_kuliah_pelatihan', 'detail_peserta_pelatihan');
            if ($request->id_periode) {
                $pelatihans->where('id_periode', $request->id_periode);
            }
        } else {
            // Jika user bukan admin atau pimpinan, hanya tampilkan pelatihan yang dimiliki oleh user tersebut
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
                ->where(function ($query) {
                    $query->where('status_pelatihan', 'terima')
                        ->orWhereNull('status_pelatihan');
                })
                ->with([
                    'vendor_pelatihan',
                    'jenis_pelatihan',
                    'periode',
                    'bidang_minat_pelatihan',
                    'mata_kuliah_pelatihan',
                    // Mengambil detail hanya untuk user login
                    'detail_peserta_pelatihan' => function ($query) use ($user) {
                        $query->where('detail_peserta_pelatihan.user_id', $user->user_id);
                    }
                ]);
            if ($request->id_periode) {
                $pelatihans->where('id_periode', $request->id_periode);
            }
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
                $levelId = Auth::user();
                if ($levelId->id_level == 1) {
                    $btn = '<button onclick="modalAction(\'' . url('/pelatihan/' . $pelatihan->id_pelatihan . '/admin_detail') . '\')" class="btn btn-success btn-sm">Detail</button> ';
                    $btn .= '<button onclick="modalAction(\'' . url('/pelatihan/' . $pelatihan->id_pelatihan . '/admin_show_edit') . '\')" class="btn btn-info btn-sm">Upload</button> ';
                    $btn .= '<button onclick="modalAction(\'' . url('/pelatihan/' . $pelatihan->id_pelatihan . '/edit') . '\')" class="btn btn-warning btn-sm">Edit</button> ';
                    $btn .= '<button onclick="modalAction(\'' . url('/pelatihan/' . $pelatihan->id_pelatihan . '/confirm') . '\')" class="btn btn-danger btn-sm">Hapus</button> ';
                    // if ($pelatihan->status_pelatihan == 'menunggu') {
                    //     $btn .= '<button onclick="modalAction(\'' . url('/pelatihan/' . $pelatihan->id_pelatihan . '/create_rekomendasi_peserta') . '\')" class="btn btn-info btn-sm">Peserta</button> ';
                    // }
                } else {
                    $btn = '<button onclick="modalAction(\'' . url('/pelatihan/' . $pelatihan->id_pelatihan . '/show') . '\')" class="btn btn-info btn-sm">Detail</button> ';
                    $btn .= '<button onclick="modalAction(\'' . url('/pelatihan/' . $pelatihan->id_pelatihan . '/edit') . '\')" class="btn btn-warning btn-sm">Edit</button> ';
                    $btn .= '<button onclick="modalAction(\'' . url('/pelatihan/' . $pelatihan->id_pelatihan . '/confirm') . '\')" class="btn btn-danger btn-sm">Hapus</button> ';
                }
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
        $periode = PeriodeModel::select('id_periode', 'tahun_periode')
            ->orderBy('tahun_periode', 'asc')
            ->get();

        $bidangMinat = BidangMinatModel::select('id_bidang_minat', 'nama_bidang_minat')->get();
        $mataKuliah = MataKuliahModel::select('id_matakuliah', 'nama_matakuliah')->get();
        $user = UserModel::select('user_id', 'nama_lengkap')
            ->where('id_level', '!=', 1)->get();
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
        $pelatihan = PelatihanModel::with('detail_peserta_pelatihan')->find($id);

        $vendorpelatihan = VendorPelatihanModel::select('id_vendor_pelatihan', 'nama')->get();
        $jenispelatihan = JenisPelatihanModel::select('id_jenis_pelatihan', 'nama_jenis_pelatihan')->get();
        $periode = PeriodeModel::select('id_periode', 'tahun_periode')
            ->orderBy('tahun_periode', 'asc')
            ->get();

        $bidangMinat = BidangMinatModel::select('id_bidang_minat', 'nama_bidang_minat')->get();
        $mataKuliah = MataKuliahModel::select('id_matakuliah', 'nama_matakuliah')->get();
        $user = UserModel::select('user_id', 'nama_lengkap')
            ->where('id_level', '!=', 1)->get();

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
                'kuota_peserta' => 'required|integer',
                'biaya' => 'required|string|max:255',

                'bukti_pelatihan' => 'nullable|mimes:pdf|max:5120',
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
            $surat_tugas = null;

            /** @var User */
            $user = Auth::user();

            $oldFile = DB::table('detail_peserta_pelatihan')
                ->where('user_id', $user->user_id)
                ->where('id_pelatihan', $id)
                ->value('bukti_pelatihan');

            // Cek apakah file bukti pelatihan diunggah
            if ($request->hasFile('bukti_pelatihan')) {
                // Hapus file bukti pelatihan lama jika ada
                if ($oldFile && Storage::exists('public/bukti_pelatihan/' . $oldFile)) {
                    Storage::delete('public/bukti_pelatihan/' . $oldFile);
                }
                $bukti_pelatihan = time() . '_' . $request->file('bukti_pelatihan')->getClientOriginalName();
                $request->file('bukti_pelatihan')->storeAs('public/bukti_pelatihan/' . $bukti_pelatihan);
            }

            if ($request->hasFile('surat_tugas')) {
                $surat_tugas = time() . '_' . $request->file('surat_tugas')->getClientOriginalName();
                $request->file('surat_tugas')->storeAs('public/surat_tugas/' . $surat_tugas);
            }

            if ($pelatihan) {
                $kuotaPeserta = $request->kuota_peserta;
                if ($user->id_level == 1) {
                    $kuotaPeserta = count($request->user_id);
                }
                if ($request->hasFile('bukti_pelatihan')) {
                    $pelatihan->update([
                        'nama_pelatihan'  => $request->nama_pelatihan,
                        'lokasi'      => $request->lokasi,
                        'level_pelatihan'      => $request->level_pelatihan,
                        'tanggal'      => $request->tanggal,
                        'bukti_pelatihan'      => $bukti_pelatihan,
                        'kuota_peserta'      => $kuotaPeserta,
                        'biaya'      => $request->biaya,
                        'id_vendor_pelatihan'  => $request->id_vendor_pelatihan,
                        'id_jenis_pelatihan'  => $request->id_jenis_pelatihan,
                        'id_periode'  => $request->id_periode,
                        'surat_tugas' => $surat_tugas,
                    ]);
                    $pelatihan->detail_peserta_pelatihan()->updateExistingPivot(
                        Auth::id(),
                        [
                            'bukti_pelatihan' => $bukti_pelatihan ?? $pelatihan->detail_peserta_pelatihan()->first()->pivot->bukti_pelatihan
                        ]
                    );
                } else {
                    $pelatihan->update([
                        'nama_pelatihan'  => $request->nama_pelatihan,
                        'lokasi'      => $request->lokasi,
                        'level_pelatihan'      => $request->level_pelatihan,
                        'tanggal'      => $request->tanggal,
                        'kuota_peserta'      => $kuotaPeserta,
                        'biaya'      => $request->biaya,
                        'id_vendor_pelatihan'  => $request->id_vendor_pelatihan,
                        'id_jenis_pelatihan'  => $request->id_jenis_pelatihan,
                        'id_periode'  => $request->id_periode,
                        'surat_tugas' => $surat_tugas,
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
            /** @var User $currentUser */
            $currentUser = Auth::user(); // Mendapatkan user login

            // Temukan data pelatihan berdasarkan ID
            $pelatihan = PelatihanModel::find($id);

            if ($pelatihan) {
                if ($currentUser->id_level == 1) {
                    // Hapus file bukti pelatihan dari setiap detail_peserta_pelatihan terkait
                    $detailPesertas = DB::table('detail_peserta_pelatihan')
                        ->where('id_pelatihan', $id)
                        ->get();

                    foreach ($detailPesertas as $detail) {
                        if ($detail->bukti_pelatihan && Storage::exists('public/bukti_pelatihan/' . $detail->bukti_pelatihan)) {
                            Storage::delete('public/bukti_pelatihan/' . $detail->bukti_pelatihan);
                        }
                    }
                    // Jika user adalah admin, hapus data pelatihan sepenuhnya
                    $pelatihan->mata_kuliah_pelatihan()->detach();
                    $pelatihan->bidang_minat_pelatihan()->detach();
                    PesertaPelatihanModel::where('id_pelatihan', $id)->delete();

                    // Hapus notifikasi terkait pelatihan
                    DB::table('notifications')
                        ->where('data', 'LIKE', '%"id_pelatihan":' . $id . '%')
                        ->delete();

                    // Hapus data pelatihan
                    $pelatihan->delete();

                    return response()->json([
                        'status' => true,
                        'message' => 'Data pelatihan berhasil dihapus.'
                    ]);
                } else {
                    // Jika user bukan admin, hapus hanya relasi pada tabel pivot berdasarkan user login
                    $detailPeserta = DB::table('detail_peserta_pelatihan')
                        ->where('id_pelatihan', $id)
                        ->where('user_id', $currentUser->user_id)
                        ->first(); // Ambil data pivot spesifik

                    if ($detailPeserta) {
                        // Hapus file bukti pelatihan jika ada
                        if ($detailPeserta->bukti_pelatihan && Storage::exists('public/bukti_pelatihan/' . $detailPeserta->bukti_pelatihan)) {
                            Storage::delete('public/bukti_pelatihan/' . $detailPeserta->bukti_pelatihan);
                        }
                        // Hapus data berdasarkan primary key tabel pivot
                        DB::table('detail_peserta_pelatihan')
                            ->where('id_detail_peserta_pelatihan', $detailPeserta->id_detail_peserta_pelatihan)
                            ->delete();

                        // Hapus notifikasi terkait untuk user ini
                        DB::table('notifications')
                            ->where('data', 'LIKE', '%"id_pelatihan":' . $id . '%')
                            ->where('notifiable_id', $currentUser->user_id)
                            ->delete();

                        return response()->json([
                            'status' => true,
                            'message' => 'Data relasi pada detail_peserta_pelatihan berhasil dihapus.'
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
        $vendorpelatihan = VendorPelatihanModel::select('id_vendor_pelatihan', 'nama')->get();
        $jenispelatihan = JenisPelatihanModel::select('id_jenis_pelatihan', 'nama_jenis_pelatihan')->get();
        $periode = PeriodeModel::select('id_periode', 'tahun_periode')
            ->orderBy('tahun_periode', 'asc')
            ->get();

        $bidangMinat = BidangMinatModel::select('id_bidang_minat', 'nama_bidang_minat')->get();
        $mataKuliah = MataKuliahModel::select('id_matakuliah', 'nama_matakuliah')->get();
        $user = UserModel::select('user_id', 'nama_lengkap')->get();

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

                'nama_pelatihan' => 'required|string|min:5',
                'lokasi' => 'required',
                'level_pelatihan' => 'required',
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

            // Simpan data user dengan hanya field yang diperlukan
            $pelatihan = pelatihanModel::create([
                'nama_pelatihan'  => $request->nama_pelatihan,
                'lokasi'      => $request->lokasi,
                'level_pelatihan'      => $request->level_pelatihan,
                'tanggal'      => $request->tanggal,
                'biaya'      => $request->biaya,
                'kuota_peserta'      => $kuotaPeserta,
                'id_vendor_pelatihan'  => $request->id_vendor_pelatihan,
                'id_jenis_pelatihan'  => $request->id_jenis_pelatihan,
                'id_periode'  => $request->id_periode,
                'status_pelatihan' => 'menunggu' // Pastikan status diatur di sini
            ]);

            $pelatihan->bidang_minat_pelatihan()->sync($request->id_bidang_minat);
            $pelatihan->mata_kuliah_pelatihan()->sync($request->id_matakuliah);
            $pelatihan->detail_peserta_pelatihan()->sync($request->user_id);

            return response()->json([
                'status' => true,
                'message' => 'Data user berhasil disimpan'
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
    //     $pelatihan = PelatihanModel::with('detail_peserta_pelatihan')->find($id);

    //     // Ambil ID bidang minat dan mata kuliah yang terkait dengan pelatihan
    //     $pelatihanBidangMinat = $pelatihan->bidang_minat_pelatihan->pluck('id_bidang_minat')->toArray();
    //     $pelatihanMataKuliah = $pelatihan->mata_kuliah_pelatihan->pluck('id_matakuliah')->toArray();

    //     $user = UserModel::with(['detail_daftar_user_matakuliah', 'detail_daftar_user_bidang_minat'])
    //     ->where('id_level', '!=', 1) // Tambahkan kondisi ini untuk mengecualikan admin
    //     ->withCount([
    //         'detail_daftar_user_matakuliah as mata_kuliah_count' => function ($query) use ($pelatihanMataKuliah) {
    //             $query->whereIn('detail_daftar_user_matakuliah.id_matakuliah', $pelatihanMataKuliah);
    //         },
    //         'detail_daftar_user_bidang_minat as bidang_minat_count' => function ($query) use ($pelatihanBidangMinat) {
    //             $query->whereIn('detail_daftar_user_bidang_minat.id_bidang_minat', $pelatihanBidangMinat);
    //         }
    //     ])
    //     ->orderByDesc('mata_kuliah_count')
    //     ->orderByDesc('bidang_minat_count')
    //     ->get();

    //     return view('pelatihan.create_rekomendasi_peserta')->with([
    //         'user' => $user,
    //         'pelatihan' => $pelatihan,
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
    //         $pelatihan = PelatihanModel::find($id);
    //         $pelatihan->update([
    //             'kuota_peserta'      => $kuotaPeserta,
    //         ]);

    //         if (!empty($request->user_id)) {
    //             $pelatihan->detail_peserta_pelatihan()->sync($request->user_id);
    //         }

    //         // Menyimpan user_id ke dalam pivot tabel dengan status 'menunggu'

    //         return response()->json([
    //             'status' => true,
    //             'message' => 'Data pelatihan berhasil disimpan'
    //         ]);
    //     }
    //     return redirect('/');
    // }
    public function admin_detail(String $id)
    {
        $pelatihan = PelatihanModel::with('vendor_pelatihan', 'jenis_pelatihan', 'periode', 'bidang_minat_pelatihan', 'mata_kuliah_pelatihan')->find($id);

        return view('pelatihan.admin_detail', ['pelatihan' => $pelatihan]);
    }

    public function admin_show_edit(string $id)
    {
        $pelatihan = PelatihanModel::with(['detail_peserta_pelatihan' => function ($query) {
            $query->select('user.user_id as user_id', 'user.nama_lengkap');
        }])->find($id);

        return view('pelatihan.admin_show', [
            'pelatihan' => $pelatihan,
        ]);
    }

    public function admin_show_update(Request $request, $pelatihanId)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'bukti_pelatihan.*' => 'nullable|mimes:pdf|max:5120',
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi gagal.',
                    'msgField' => $validator->errors()
                ]);
            }

            // Ambil daftar user_id yang terdaftar di pelatihan
            $pesertaIds = DB::table('detail_peserta_pelatihan')
                ->where('id_pelatihan', $pelatihanId)
                ->pluck('user_id')
                ->toArray();

            $userData = []; // Simpan data hasil upload

            if ($request->hasFile('bukti_pelatihan')) {
                foreach ($request->file('bukti_pelatihan', []) as $userId => $file) {
                    // Pastikan user_id termasuk dalam daftar peserta
                    if (!in_array($userId, $pesertaIds)) {
                        Log::warning("User ID: $userId tidak terdaftar pada pelatihan dengan ID: $pelatihanId");
                        continue;
                    }

                    if ($file->isValid()) {
                        // Cari file lama di database
                        $oldFile = DB::table('detail_peserta_pelatihan')
                            ->where('user_id', $userId)
                            ->where('id_pelatihan', $pelatihanId)
                            ->value('bukti_pelatihan');

                        // Hapus file lama jika ada
                        if ($oldFile && Storage::exists('public/bukti_pelatihan/' . $oldFile)) {
                            Storage::delete('public/bukti_pelatihan/' . $oldFile);
                        }

                        // Simpan file baru
                        $fileName = time() . '_' . $file->getClientOriginalName();
                        $file->storeAs('public/bukti_pelatihan', $fileName);

                        // Simpan ke database
                        $data = [
                            'bukti_pelatihan' => $fileName,
                            'updated_at' => now(),
                        ];

                        $result = DB::table('detail_peserta_pelatihan')
                            ->where('user_id', $userId)
                            ->where('id_pelatihan', $pelatihanId)
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


    public function generate($id)
    {
        $pelatihan = PelatihanModel::with('vendor_pelatihan', 'detail_peserta_pelatihan')->find($id);
        $user = UserModel::select('user_id', 'username', 'nama_lengkap', 'avatar', 'id_level')
            ->where('id_level', '2')
            ->with('level')
            ->first();

        // dd($user->nama_lengkap);

        if (!$pelatihan) {
            return redirect()->back()->with('error', 'Pelatihan tidak ditemukan.');
        }

        // Buat instance PhpWord
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();

        // Tambahkan tabel untuk header dengan logo
        $headerTable = $section->addTable();
        $headerTable->addRow();

        // Tambahkan logo
        $logoPath = public_path('storage/logo/Logo.png');

        if (file_exists($logoPath)) {
            $headerTable->addCell(2000)->addImage(
                $logoPath,
                [
                    'width' => 100,
                    'height' => 100,
                    'alignment' => 'left'
                ]
            );
        } else {
            return redirect()->back()->with('error', 'Logo tidak ditemukan di server.');
        }

        // Tambahkan teks header
        $cell = $headerTable->addCell(6000);
        $cell->addText(
            'KEMENTERIAN PENDIDIKAN, KEBUDAYAAN, RISET DAN TEKNOLOGI',
            ['bold' => true, 'size' => 14, 'alignment' => 'center']
        );
        $cell->addText(
            'POLITEKNIK NEGERI MALANG',
            ['bold' => true, 'size' => 14, 'alignment' => 'center']
        );
        $cell->addText('Jl. Soekarno Hatta No.9 Malang 65141', ['size' => 10]);
        $cell->addText('Telp (0341) 404424 – 404425 Fax (0341) 404420', ['size' => 10]);

        $section->addTextBreak(1);

        // Tambahkan informasi surat
        $section->addText("Nomor: -", ['size' => 12]);
        $section->addText("Lampiran: -", ['size' => 12]);
        $section->addText("Perihal: Surat Tugas", ['size' => 12]);
        $section->addTextBreak(1);

        // Tambahkan keterangan kegiatan
        $section->addText(
            "Sehubungan dengan Kegiatan Peningkatan Kompetensi Sumber Daya Manusia diselenggarakan pelatihan tentang "
                . $pelatihan->nama_pelatihan
                . " yang diselenggarakan oleh "
                . $pelatihan->vendor_pelatihan->nama
                . " pada tanggal "
                . $pelatihan->tanggal
                . ", maka kami mohon diterbitkan Surat Tugas kepada peserta berikut:",
            ['size' => 12]
        );
        $section->addTextBreak(1);

        // Tambahkan tabel peserta
        $table = $section->addTable(['borderSize' => 6, 'borderColor' => '999999', 'cellMargin' => 80]);
        $table->addRow();
        $table->addCell(1000)->addText("NO", ['bold' => true]);
        $table->addCell(4000)->addText("NIP", ['bold' => true]);
        $table->addCell(4000)->addText("NAMA LENGKAP", ['bold' => true]);
        $table->addCell(4000)->addText("JABATAN", ['bold' => true]);

        foreach ($pelatihan->detail_peserta_pelatihan as $index => $peserta) {
            $table->addRow();
            $table->addCell(1000)->addText($index + 1);
            $table->addCell(4000)->addText($peserta->nip);
            $table->addCell(4000)->addText($peserta->nama_lengkap);
            $table->addCell(4000)->addText($peserta->level->nama_level ?? 'Tidak Tersedia');
        }

        // Tambahkan penutup surat
        $section->addTextBreak(2);
        $section->addText("Demikian permohonan ini atas perhatiannya kami sampaikan terima kasih.", ['size' => 12]);
        $section->addTextBreak(2);
        $section->addText("Ketua Jurusan", ['size' => 12]);
        $section->addTextBreak(3);
        $section->addText($user->nama_lengkap, ['size' => 12, 'bold' => true]);

        // Simpan file
        $fileName = "Draft_Surat_Tugas_{$pelatihan->nama_pelatihan}.docx";
        $filePath = storage_path("app/public/{$fileName}");

        $phpWordWriter = IOFactory::createWriter($phpWord, 'Word2007');
        $phpWordWriter->save($filePath);

        return response()->download($filePath)->deleteFileAfterSend(true);
    }
}
