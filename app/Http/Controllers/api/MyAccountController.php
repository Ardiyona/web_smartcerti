<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\UserModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class MyAccountController extends Controller
{
    public function index()
    {
        /** @var User */
        $user = Auth::guard('api')->user();

        // Ambil data user beserta relasi mata_kuliah dan bidang_minat
        $userData = $user->load([
            'detail_daftar_user_matakuliah', // Relasi mata_kuliah
            'detail_daftar_user_bidang_minat', // Relasi bidang_minat
        ]);

        return response()->json([
            'success' => true,
            'data' => $userData,
        ], 200);
    }

    public function update(Request $request)
    {
        /** @var User */
        $user = Auth::guard('api')->user();

        // Proses avatar jika ada file yang diunggah
        if ($request->hasFile('avatar')) {
            $fileName = time() . '.' . $request->file('avatar')->getClientOriginalExtension();
            $path = $request->file('avatar')->storeAs('images', $fileName);
            $request['avatar'] = '/storage/' . $path;
        } else {
            $request->request->remove('avatar');
        }

        // Perbarui data utama pengguna
        $user->update($request->only('username', 'nama_lengkap', 'no_telp', 'email', 'jenis_kelamin', 'avatar', 'id_level'));

        Log::info('Query setelah update:', $user->toArray());

        // Sinkronisasi relasi hanya jika data dikirim
        if ($request->has('id_bidang_minat')) {
            $user->detail_daftar_user_bidang_minat()->sync($request->id_bidang_minat);
        }

        if ($request->has('id_matakuliah')) {
            $user->detail_daftar_user_matakuliah()->sync($request->id_matakuliah);
        }

        return response()->json([
            'success' => true,
            'message' => 'Profil berhasil diperbarui',
            'data' => $user->refresh()->load(['detail_daftar_user_matakuliah', 'detail_daftar_user_bidang_minat']),
        ], 200);
    }

    public function updatePassword(Request $request)
    {
        /** @var User */
        $user = Auth::guard('api')->user();
        // Validasi input dari form
        $validator = Validator::make($request->all(), [
            'old_password' => 'required|string',
            'password' => 'required|string|min:5|confirmed', // Pastikan ada konfirmasi password
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors(),
            ], 422);
        }

        // Cek apakah password lama benar
        if (!Hash::check($request->old_password, $user->password)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Password lama tidak sesuai.',
            ], 403);
        }

        // Update password baru
        $user->password = Hash::make($request->password);
        $user->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Password berhasil diperbarui.',
            'data' => $user
        ]);
    }
}
