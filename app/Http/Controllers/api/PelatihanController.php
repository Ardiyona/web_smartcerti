<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PelatihanModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class PelatihanController extends Controller
{
    public function index()
    {
        // Mendapatkan data pelatihan beserta relasi yang diperlukan
        $pelatihan = PelatihanModel::select(
            'id_pelatihan',
            'id_vendor_pelatihan',
            'id_jenis_pelatihan',
            'id_periode',
            'nama_pelatihan',
            'lokasi',
            'level_pelatihan',
            'tanggal',
            'bukti_pelatihan',
            'kuota_peserta',
            'biaya'
        )
            ->with([
                'vendor_pelatihan',
                'jenis_pelatihan',
                'periode',
                'bidang_minat_pelatihan',
                'mata_kuliah_pelatihan',
                'detail_peserta_pelatihan'
            ])
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
            'lokasi' => 'required|string|max:255',
            'level_pelatihan' => 'required|string|max:50',
            'tanggal' => 'required|date',
            'bukti_pelatihan' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
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
            $bukti_pelatihan = time() . '_' . $request->file('bukti_pelatihan')->getClientOriginalName();
            $request->file('bukti_pelatihan')->storeAs('public/images/', $bukti_pelatihan);
        }

        // Membuat data pelatihan
        $pelatihan = PelatihanModel::create([
            'nama_pelatihan' => $request->nama_pelatihan,
            'lokasi' => $request->lokasi,
            'level_pelatihan' => $request->level_pelatihan,
            'tanggal' => $request->tanggal,
            'bukti_pelatihan' => $bukti_pelatihan,
            'kuota_peserta' => $request->kuota_peserta,
            'biaya' => $request->biaya,
            'id_vendor_pelatihan' => $request->id_vendor_pelatihan,
            'id_jenis_pelatihan' => $request->id_jenis_pelatihan,
            'id_periode' => $request->id_periode
        ]);

        // Sinkronisasi relasi many-to-many
        $pelatihan->bidang_minat_pelatihan()->sync($request->id_bidang_minat);
        $pelatihan->mata_kuliah_pelatihan()->sync($request->id_matakuliah);

        // Menambahkan peserta pelatihan
        $pelatihan->detail_peserta_pelatihan()->attach($request->user_id);

        return response()->json([
            'success' => true,
            'message' => 'Pelatihan berhasil dibuat',
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
            'id_bidang_minat' => 'nullable|array',
            'id_matakuliah' => 'nullable|array',
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
            $bukti_pelatihan = $request->file('bukti_pelatihan')->store('public/images');
        }

        $pelatihan->bukti_pelatihan = $bukti_pelatihan;
        $pelatihan->save();

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

    public function getPelatihanByUser($id)
{
    $pelatihans = PelatihanModel::where('user_id', $id)->get();
    if ($pelatihans->isEmpty()) {
        return response()->json([
            'message' => 'Tidak ada pelatihan ditemukan untuk user ini.',
        ], 404);
    }
    return response()->json($pelatihans);
}

}
