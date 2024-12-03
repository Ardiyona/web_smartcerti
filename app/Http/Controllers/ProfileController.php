<?php

namespace App\Http\Controllers;

use App\Models\UserModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function index()
    {
        // Ambil data user berdasarkan ID yang sedang login
        $user = UserModel::findOrFail(Auth::id());

        // Muat data relasi bidang minat dan mata kuliah
        $userData = $user->load([
            'detail_daftar_user_matakuliah', // Relasi mata kuliah
            'detail_daftar_user_bidang_minat', // Relasi bidang minat
        ]);

        // Breadcrumb dan active menu
        $breadcrumb = (object) [
            'title' => 'Profil',
            'list' => ['Home', 'Profil']
        ];
        $activeMenu = 'profile';

        // Return view
        return view('profile.index', [
            'user' => $user,
            'breadcrumb' => $breadcrumb,
            'activeMenu' => $activeMenu,
            'data' => $userData,
        ]);
    }

    // Fungsi untuk update informasi profil
    public function updateProfile(Request $request, $id)
    {
        // Validasi input
        // $this->validate($request, [
        //     'username' => 'required|string|min:3|unique:user,username,' . $id . ',user_id',
        //     'nama_lengkap' => 'required|string|max:255',
        //     'no_telp' => 'required|max:15',
        //     'email' => 'required|email|max:255',
        //     'id_bidang_minat' => 'required',
        //     'id_matakuliah' => 'required',
        // ]);
    
        // Ambil data user berdasarkan ID
        $user = UserModel::findOrFail($id);
    
        // Update data dengan metode update()
        $user->update([
            'username' => $request->username,
            'nama_lengkap' => $request->nama_lengkap,
            'no_telp' => $request->no_telp,
            'email' => $request->email,
        ]);
    
    
        // Kembali ke halaman profile dengan status sukses
        return redirect()->back()->with('status_profile', 'Profil berhasil diperbarui');
    }
    

    // Fungsi untuk update avatar (foto profil)
    public function updateAvatar(Request $request, $id)
    {
        // Validasi avatar
        $this->validate($request, [
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // Validasi untuk file gambar
        ]);

        // Ambil data user berdasarkan ID
        $user = UserModel::findOrFail($id);

        // Handle upload avatar jika ada file baru yang diupload
        if ($request->hasFile('avatar')) {
            // Hapus avatar lama jika ada
            if ($user->avatar && Storage::disk('public')->exists('photos/' . $user->avatar)) {
                Storage::disk('public')->delete('photos/' . $user->avatar);
            }

            // Simpan avatar baru
            $fileName = time() . '_' . $request->file('avatar')->getClientOriginalName(); // Unique filename
            $request->file('avatar')->storeAs('public/photos', $fileName);
            $user->avatar = $fileName;

            // Simpan perubahan data user
            $user->save();

            // Kembali ke halaman profile dengan status sukses
            return redirect()->back()->with('status_foto', 'Foto profil berhasil diperbarui');
        }

        return redirect()->back()->withErrors(['avatar' => 'Gagal memperbarui foto profil']);
    }

    // Fungsi untuk update password
    public function updatePassword(Request $request, $id)
    {
        // Validasi input password
        $this->validate($request, [
            'old_password' => 'required|string',
            'password' => 'required|min:5|confirmed', // Validasi password baru
        ]);

        // Ambil data user berdasarkan ID
        $user = UserModel::findOrFail($id);

        // Jika password lama diisi dan benar, ganti password
        if (Hash::check($request->old_password, $user->password)) {
            // Update password
            $user->password = Hash::make($request->password);
            $user->save();

            // Kembali ke halaman profile dengan status sukses
            return redirect()->back()->with('status_password', 'Password berhasil diperbarui');
            
        } else {
            // Jika password lama tidak sesuai
            return redirect()->back()
                ->withErrors(['old_password' => 'Password lama tidak sesuai'])
                ->withInput();
        }
        dd(session()->all());

    }
}