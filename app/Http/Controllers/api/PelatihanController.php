<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BidangMinatModel;
use App\Models\JenisPelatihanModel;
use App\Models\MataKuliahModel;
use App\Models\PelatihanModel;
use App\Models\PeriodeModel;
use App\Models\PesertaPelatihanModel;
use App\Models\UserModel;
use App\Models\VendorPelatihanModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class PelatihanController extends Controller
{
    // public function index()
    // {
    //     // Mendapatkan data pelatihan beserta relasi yang diperlukan
    //     $pelatihan = PelatihanModel::select(
    //         'id_pelatihan',
    //         'id_vendor_pelatihan',
    //         'id_jenis_pelatihan',
    //         'id_periode',
    //         'nama_pelatihan',
    //         'lokasi',
    //         'level_pelatihan',
    //         'tanggal',
    //         'bukti_pelatihan',
    //         'kuota_peserta',
    //         'biaya'
    //     )
    //         ->with([
    //             'vendor_pelatihan',
    //             'jenis_pelatihan',
    //             'periode',
    //             'bidang_minat_pelatihan',
    //             'mata_kuliah_pelatihan',
    //             'detail_peserta_pelatihan'
    //         ])
    //         ->get();

    //     // Mengembalikan response dalam bentuk JSON
    //     return response()->json([
    //         'success' => true,
    //         'message' => 'Data pelatihan retrieved successfully',
    //         'data' => $pelatihan
    //     ], 200);
    // }



    public function index()
    {
        /** @var User */
        $user = Auth::guard('api')->user();
        // Mengambil pelatihan yang hanya dimiliki oleh user yang sedang login
        $pelatihan = $user->detail_peserta_pelatihan()
            ->with('vendor_pelatihan', 'jenis_pelatihan', 'periode', 'bidang_minat_pelatihan', 'mata_kuliah_pelatihan')
            ->get();

        // Mengembalikan response dalam bentuk JSON
        return response()->json([
            'success' => true,
            'message' => 'Data pelatihan retrieved successfully',
            'data' => $pelatihan
        ], 200);
    }

    public function store(Request $request)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'nama_pelatihan' => 'required|string|max:255',
            'jenis' => 'required|string',
            'tanggal' => 'required|date',
            'bukti_pelatihan' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'masa_berlaku' => 'required|date',
            'kuota_peserta' => 'required|integer',
            'biaya' => 'required|numeric',
            'id_vendor_pelatihan' => 'required|integer|exists:vendor_pelatihan,id_vendor_pelatihan',
            'id_jenis_pelatihan' => 'required|integer|exists:jenis_pelatihan,id_jenis_pelatihan',
            'id_periode' => 'required|integer|exists:periode,id_periode',
            'id_bidang_minat' => 'required|array',
            'id_matakuliah' => 'required|array',
            'user_id' => 'required|array',
        ]);

        // Return error jika validasi gagal
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        // Inisialisasi variabel untuk menyimpan path file
        $bukti_pelatihan = null;

        // Cek apakah file bukti pelatihan diunggah
        if ($request->hasFile('bukti_pelatihan')) {
            $filename = time() . '_' . $request->file('bukti_pelatihan')->getClientOriginalName();
            $bukti_pelatihan = $request->file('bukti_pelatihan')->storeAs('public/bukti_pelatihan/', $filename);
        }

        $pelatihan = PelatihanModel::create([
            'nama_pelatihan' => $request->nama_pelatihan,
            'no_pelatihan' => $request->no_pelatihan,
            'jenis' => $request->jenis,
            'tanggal' => $request->tanggal,
            'bukti_pelatihan' => $filename,
            'masa_berlaku' => $request->masa_berlaku,
            'kuota_peserta' => $request->kuota_peserta,
            'biaya' => $request->biaya,
            'id_vendor_pelatihan' => $request->id_vendor_pelatihan,
            'id_jenis_pelatihan' => $request->id_jenis_pelatihan,
            'id_periode' => $request->id_periode
        ]);
        $pelatihan->bidang_minat_pelatihan()->sync($request->id_bidang_minat);
        $pelatihan->mata_kuliah_pelatihan()->sync($request->id_matakuliah);

        $userId = Auth::id();

        $pelatihan->detail_peserta_pelatihan()->attach($userId, [
            'bukti_pelatihan' => $filename
        ]);

        return response()->json([
            'success' => true,
            'message' => 'pelatihan berhasil dibuat terima kasih',
            'data' => $pelatihan
        ], 201);
    }

    public function show(PelatihanModel $pelatihan)
    {
        // Memuat relasi yang diperlukan menggunakan `load()`
        $pelatihan->load([
            'vendor_pelatihan',
            'jenis_pelatihan',
            'periode',
            'bidang_minat_pelatihan',
            'mata_kuliah_pelatihan',
            'detail_peserta_pelatihan'
        ]);

        // Mengembalikan response dalam format JSON
        return response()->json([
            'success' => true,
            'message' => 'Data pelatihan retrieved successfully',
            'data' => $pelatihan
        ], 200);
    }

    public function update(Request $request, PelatihanModel $pelatihan)
    {
        $validator = Validator::make($request->all(), [
            'nama_pelatihan' => 'nullable|string|max:255',
            'lokasi' => 'nullable|string|max:255',
            'level_pelatihan' => 'nullable|string|max:50',
            'tanggal' => 'nullable|date',
            'bukti_pelatihan' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'kuota_peserta' => 'nullable|integer',
            'biaya' => 'nullable|numeric',
            'id_vendor_pelatihan' => 'nullable|integer|exists:vendor_pelatihan,id_vendor_pelatihan',
            'id_jenis_pelatihan' => 'nullable|integer|exists:jenis_pelatihan,id_jenis_pelatihan',
            'id_periode' => 'nullable|integer|exists:periode,id_periode',
            'id_bidang_minat' => 'nullable|',
            'id_matakuliah' => 'nullable|',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        // Mengisi field yang ada di request tanpa menghapus data lainnya
        $pelatihan->fill($request->only([
            'nama_pelatihan',
            'lokasi',
            'level_pelatihan',
            'tanggal',
            'kuota_peserta',
            'biaya',
            'id_vendor_pelatihan',
            'id_jenis_pelatihan',
            'id_periode'
        ]));

        $bukti_pelatihan = $pelatihan->bukti_pelatihan;

        if ($request->hasFile('bukti_pelatihan')) {
            if ($bukti_pelatihan) {
                Storage::delete($bukti_pelatihan);
            }
            $filename = time() . '_' . $request->file('bukti_pelatihan')->getClientOriginalName();

            $bukti_pelatihan = $request->file('bukti_pelatihan')->store('public/bukti_pelatihan');

            $pelatihan->detail_peserta_pelatihan()->updateExistingPivot(
                Auth::id(),
                [
                    'bukti_pelatihan' => $filename
                ]
            );
        }
        $pelatihan->save();
        $pelatihan->bukti_pelatihan = $bukti_pelatihan;


        $pelatihan->bidang_minat_pelatihan()->sync($request->id_bidang_minat);
        $pelatihan->mata_kuliah_pelatihan()->sync($request->id_matakuliah);

        return response()->json([
            'success' => true,
            'message' => 'Pelatihan berhasil diperbarui',
            'data' => $pelatihan->load([
                'vendor_pelatihan',
                'jenis_pelatihan',
                'periode',
                'bidang_minat_pelatihan',
                'mata_kuliah_pelatihan',
                'detail_peserta_pelatihan'
            ])
        ], 200);
    }

    public function destroy(PelatihanModel $pelatihan)
    {
        if ($pelatihan) {
            $pelatihan->bidang_minat_pelatihan()->detach();
            $pelatihan->mata_kuliah_pelatihan()->detach();
            $pelatihan->detail_peserta_pelatihan()->detach();

            $pelatihan->delete();

            return response()->json([
                'success' => true,
                'message' => 'Data pelatihan berhasil dihapus'
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Data pelatihan tidak ditemukan'
            ]);
        }
    }

    //     public function getPelatihanByUser($id)
    // {
    //     $pelatihans = PelatihanModel::where('user_id', $id)->get();
    //     if ($pelatihans->isEmpty()) {
    //         return response()->json([
    //             'message' => 'Tidak ada pelatihan ditemukan untuk user ini.',
    //         ], 404);
    //     }
    //     return response()->json($pelatihans);

    // }


    public function getPelatihanByUser($id)
    {
        // Ambil data peserta pelatihan berdasarkan user_id
        $pesertaPelatihan = PesertaPelatihanModel::with('pelatihan')
            ->where('user_id', $id)
            ->get();

        // Periksa apakah data kosong
        if ($pesertaPelatihan->isEmpty()) {
            return response()->json([
                'message' => 'Tidak ada pelatihan ditemukan untuk user ini.',
            ], 404);
        }

        // Jika data ditemukan, kembalikan dalam bentuk JSON
        return response()->json($pesertaPelatihan);
    }


    // public function create()
    // {
    //     // Mengambil data dari berbagai tabel terkait pelatihan
    //     $vendorpelatihan = VendorPelatihanModel::select('id_vendor_pelatihan', 'nama')->get();
    //     $jenispelatihan = JenisPelatihanModel::select('id_jenis_pelatihan', 'nama_jenis_pelatihan')->get();
    //     $periode = PeriodeModel::select('id_periode', 'tahun_periode')->get();
    //     $bidangMinat = BidangMinatModel::select('id_bidang_minat', 'nama_bidang_minat')->get();
    //     $mataKuliah = MataKuliahModel::select('id_matakuliah', 'nama_matakuliah')->get();
    //     $user = UserModel::select('user_id', 'nama_lengkap')->get();

    //     // Mendapatkan user yang sedang login
    //     $userid = Auth::user();

    //     // Menentukan data tambahan berdasarkan level user
    //     if ($userid->id_level == 1) {
    //         $response = [
    //             'success' => true,
    //             'message' => 'Data pelatihan untuk admin',
    //             'data' => [
    //                 'vendorpelatihan' => $vendorpelatihan,
    //                 'jenispelatihan' => $jenispelatihan,
    //                 'periode' => $periode,
    //                 'bidangMinat' => $bidangMinat,
    //                 'mataKuliah' => $mataKuliah,
    //                 'user' => $user,
    //             ],
    //         ];
    //     } else {
    //         $response = [
    //             'success' => true,
    //             'message' => 'Data pelatihan untuk user',
    //             'data' => [
    //                 'vendorpelatihan' => $vendorpelatihan,
    //                 'jenispelatihan' => $jenispelatihan,
    //                 'periode' => $periode,
    //                 'bidangMinat' => $bidangMinat,
    //                 'mataKuliah' => $mataKuliah,
    //                 'user' => $user,
    //             ],
    //         ];
    //     }

    //     // Mengembalikan data dalam bentuk JSON
    //     return response()->json($response, 200);
    // }


    public function create()
    {
        // Ambil data yang diperlukan
        $vendorpelatihan = VendorPelatihanModel::select('id_vendor_pelatihan', 'nama')->get();
        $jenispelatihan = JenisPelatihanModel::select('id_jenis_pelatihan', 'nama_jenis_pelatihan')->get();
        $periode = PeriodeModel::select('id_periode', 'tahun_periode')->get();
        $bidangMinat = BidangMinatModel::select('id_bidang_minat', 'nama_bidang_minat')->get();
        $mataKuliah = MataKuliahModel::select('id_matakuliah', 'nama_matakuliah')->get();
        $user = UserModel::select('user_id', 'nama_lengkap')->get();

        // Periksa apakah user terautentikasi
        $userid = Auth::user();
        if (!$userid) {
            return response()->json([
                'success' => false,
                'message' => 'User tidak terautentikasi',
            ], 401);
        }

        // Struktur data yang akan dikembalikan
        $data = [
            'vendorpelatihan' => $vendorpelatihan,
            'jenispelatihan' => $jenispelatihan,
            'periode' => $periode,
            'bidangMinat' => $bidangMinat,
            'mataKuliah' => $mataKuliah,
            'user' => $user,
        ];

        // Tentukan pesan berdasarkan level user
        $message = $userid->id_level == 1
            ? 'Data pelatihan untuk admin'
            : 'Data pelatihan untuk user';

        // Kembalikan data dalam format JSON
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data,
        ], 200);
    }
}
