<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // Menampilkan halaman login
    public function login()
    {
        // Jika pengguna sudah login, arahkan ke halaman dashboard
        if (Auth::check()) {
            return redirect('/');
        }
        return view('auth.login'); // Menampilkan form login
    }

    // Proses login
    public function postlogin(Request $request)
    {
        // Memeriksa apakah permintaan adalah AJAX atau permintaan reguler
        if ($request->ajax() || $request->wantsJson()) {
            $credentials = $request->only('username', 'password');

            // Autentikasi pengguna
            if (Auth::attempt($credentials)) {
                session([
                    'profile_img_path' => Auth::user()->avatar,
                    'user_id' => Auth::user()->user_id
                ]);
                // Jika login berhasil, kirim respons JSON untuk permintaan AJAX
                return response()->json([
                    'status' => true,
                    'message' => 'Login Berhasil',
                    'redirect' => url('/') // Arahkan ke dashboard setelah login
                ]);
            }

            // Jika login gagal, kirim respons JSON dengan pesan gagal
            return response()->json([
                'status' => false,
                'message' => 'Login Gagal, periksa username atau password Anda.'
            ]);
        }

        // Logika untuk login reguler (non-AJAX)
        $credentials = $request->only('username', 'password');
        if (Auth::attempt($credentials)) {
            // Jika login berhasil, arahkan ke dashboard
            return redirect()->intended('/');
        }

        // Jika login gagal, kembalikan ke halaman login dengan pesan error
        return redirect()->back()->withErrors(['loginError' => 'Login gagal, periksa username dan password.']);
    }

    // Logout dan redirection
    public function logout(Request $request)
    {
        // Logout pengguna
        Auth::logout();

        // Menghapus session dan regenerasi token CSRF
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Arahkan kembali ke halaman login setelah logout
        return redirect('login');
    }
}
