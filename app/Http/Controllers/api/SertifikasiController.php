<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BidangMinatSertifikasiModel;
use App\Models\MataKuliahSertifikasiModel;
use App\Models\SertifikasiModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class SertifikasiController extends Controller
{
    public function index()
    {
        /** @var User */
        $user = Auth::guard('api')->user();
        // Mengambil sertifikasi yang hanya dimiliki oleh user yang sedang login
        $sertifikasi = $user->detail_peserta_sertifikasi()
        ->with('vendor_sertifikasi', 'jenis_sertifikasi', 'periode', 'bidang_minat_sertifikasi', 'mata_kuliah_sertifikasi')
        ->get();

        // Mengembalikan response dalam bentuk JSON
        return response()->json([
            'success' => true,
            'message' => 'Data sertifikasi retrieved successfully',
            'data' => $sertifikasi
        ], 200);
    }
    public function store(Request $request)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'nama_sertifikasi' => 'required|string|max:255',
            'no_sertifikasi' => 'required|string|max:100|unique:sertifikasi,no_sertifikasi',
            'jenis' => 'required|string',
            'tanggal' => 'required|date',
            'bukti_sertifikasi' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'masa_berlaku' => 'required|date',
            'kuota_peserta' => 'required|integer',
            'biaya' => 'required|numeric',
            'id_vendor_sertifikasi' => 'required|integer|exists:vendor_sertifikasi,id_vendor_sertifikasi',
            'id_jenis_sertifikasi' => 'required|integer|exists:jenis_sertifikasi,id_jenis_sertifikasi',
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
        $bukti_sertifikasi = null;

        // Cek apakah file bukti sertifikasi diunggah
        if ($request->hasFile('bukti_sertifikasi')) {
            $bukti_sertifikasi = time() . '_' . $request->file('bukti_sertifikasi')->getClientOriginalName();
            $request->file('bukti_sertifikasi')->storeAs('public/images/', $bukti_sertifikasi);
        }

        $sertifikasi = SertifikasiModel::create([
            'nama_sertifikasi'  => $request->nama_sertifikasi,
            'no_sertifikasi'      => $request->no_sertifikasi,
            'jenis'      => $request->jenis,
            'tanggal'      => $request->tanggal,
            'bukti_sertifikasi'      => $bukti_sertifikasi,
            'masa_berlaku'      => $request->masa_berlaku,
            'kuota_peserta'      => $request->kuota_peserta,
            'biaya'      => $request->biaya,
            'id_vendor_sertifikasi'  => $request->id_vendor_sertifikasi,
            'id_jenis_sertifikasi'  => $request->id_jenis_sertifikasi,
            'id_periode'  => $request->id_periode
        ]);
        $sertifikasi->bidang_minat_sertifikasi()->sync($request->id_bidang_minat);
        $sertifikasi->mata_kuliah_sertifikasi()->sync($request->id_matakuliah);

        $userId = Auth::id();

        $sertifikasi->detail_peserta_sertifikasi()->attach($userId);

        return response()->json([
            'success' => true,
            'message' => 'Sertifikasi berhasil dibuat',
            'data' => $sertifikasi
        ], 201);
    }

    public function show(SertifikasiModel $sertifikasi)
    {
        // Memuat relasi yang diperlukan menggunakan `load()`
        $sertifikasi->load([
            'vendor_sertifikasi',
            'jenis_sertifikasi',
            'periode',
            'bidang_minat_sertifikasi',
            'mata_kuliah_sertifikasi',
            'detail_peserta_sertifikasi'
        ]);

        // Mengembalikan response dalam format JSON
        return response()->json([
            'success' => true,
            'message' => 'Data sertifikasi retrieved successfully',
            'data' => $sertifikasi
        ], 200);
    }

    public function update(Request $request, SertifikasiModel $sertifikasi)
    {
        $validator = Validator::make($request->all(), [
            'nama_sertifikasi' => 'nullable|string|max:255',
            'no_sertifikasi' => 'nullable|string|max:100|unique:sertifikasi,no_sertifikasi,' . $sertifikasi->id_sertifikasi . ',id_sertifikasi',
            'jenis' => 'nullable|string',
            'tanggal' => 'nullable|date',
            'bukti_sertifikasi' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'masa_berlaku' => 'nullable|date',
            'kuota_peserta' => 'nullable|integer',
            'biaya' => 'nullable|numeric',
            'id_vendor_sertifikasi' => 'nullable|integer|exists:vendor_sertifikasis,id_vendor_sertifikasi',
            'id_jenis_sertifikasi' => 'nullable|integer|exists:jenis_sertifikasis,id_jenis_sertifikasi',
            'id_periode' => 'nullable|integer|exists:periodes,id_periode',
            'id_bidang_minat' => 'nullable|array',
            'id_matakuliah' => 'nullable|array',
        ]);

        // Return error jika validasi gagal
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        // Mengisi field yang ada di request tanpa menghapus data lainnya
        $sertifikasi->fill($request->only([
            'nama_sertifikasi',
            'no_sertifikasi',
            'jenis',
            'tanggal',
            'masa_berlaku',
            'kuota_peserta',
            'biaya',
            'id_vendor_sertifikasi',
            'id_jenis_sertifikasi',
            'id_periode'
        ]));

        // Mengunggah file jika ada
        $bukti_sertifikasi = $sertifikasi->bukti_sertifikasi; // Default ke file lama
        if ($request->hasFile('bukti_sertifikasi')) {
            // Hapus file lama jika ada
            if ($bukti_sertifikasi) {
                Storage::delete($bukti_sertifikasi);
            }
            // Simpan file baru
            $bukti_sertifikasi = $request->file('bukti_sertifikasi')->store('public/images');
        }

        $sertifikasi->save();

        $sertifikasi->bidang_minat_sertifikasi()->sync($request->id_bidang_minat);
        $sertifikasi->mata_kuliah_sertifikasi()->sync($request->id_matakuliah);


        // Return response JSON dengan data yang telah diperbarui
        return response()->json([
            'success' => true,
            'message' => 'Sertifikasi berhasil diperbarui',
            'data' => $sertifikasi->load(['vendor_sertifikasi', 'jenis_sertifikasi', 'periode', 'bidang_minat_sertifikasi', 'mata_kuliah_sertifikasi', 'detail_peserta_sertifikasi'])
        ], 200);
    }

    public function destroy(SertifikasiModel $sertifikasi)
    {
        if ($sertifikasi) {
            // Hapus relasi many-to-many dengan MataKuliah dan BidangMinat
            $sertifikasi->mata_kuliah_sertifikasi()->detach();
            $sertifikasi->bidang_minat_sertifikasi()->detach();
            $sertifikasi->detail_peserta_sertifikasi()->detach();

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
}
