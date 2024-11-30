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


    // Method untuk memperbarui profile
    public function update(Request $request, $id)
    {
        // Validasi input dari form
        $this->validate($request, [
            'username' => 'required|string|min:3|unique:user,username,' . $id . ',user_id',
            'nama_lengkap' => 'required|string|max:255',
            'no_telp' => 'required|max:15',
            'email' => 'required|max:255',
            'old_password' => 'nullable|string',
            'password' => 'nullable|min:5',
            'avatar'   => 'image|mimes:jpeg,png,jpg|max:2048',

            'id_bidang_minat' => 'required',
            'id_matakuliah' => 'required',
        ]);

        // Ambil data user berdasarkan ID
        $user = UserModel::findOrFail($id);
      

        // Jika password lama diisi dan benar, ganti password
        if ($request->filled('old_password') && Hash::check($request->old_password, $user->password)) {
            $user->password = Hash::make($request->password);
        } elseif ($request->filled('old_password')) {
            // Jika password lama salah, kembalikan error
            return redirect()->back()
                ->withErrors(['old_password' => 'Password lama tidak sesuai'])
                ->withInput();
        }

        // Handle upload avatar jika ada file baru yang diupload
        if ($request->hasFile('avatar')) {
            // Hapus avatar lama jika ada
            if ($user->avatar && Storage::disk('public')->exists('photos/' . $user->avatar)) {
                Storage::disk('public')->delete('photos/' . $user->avatar);
            }

            // Simpan avatar baru
            $fileName = $request->file('avatar')->hashName();
            $request->file('avatar')->storeAs('public/photos', $fileName);
            $user->avatar = $fileName;
        }

        $user->update($request->only('username', 'nama_lengkap', 'no_telp', 'email', 'avatar'));

        // Simpan perubahan data user
        $user->save();

        // Kembali ke halaman profile dengan status sukses
        return redirect()->back()->with('status', 'Profil berhasil diperbarui');
    }
}