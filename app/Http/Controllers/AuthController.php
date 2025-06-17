<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log; // Pastikan ini di-import

class AuthController extends Controller
{
    /**
     * Menangani proses login.
     */
    public function loginAction(Request $request)
    {
        // Validasi input login
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Coba autentikasi pengguna
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate(); // Regenerasi session ID untuk keamanan
            Log::info('Pengguna berhasil login: ' . $request->email);
            return redirect()->intended('/dashboard')->with('success', 'Selamat datang kembali!');
        }

        // Jika autentikasi gagal
        Log::warning('Upaya login gagal untuk email: ' . $request->email);
        return back()->withErrors([
            'email' => 'Email atau password salah.', // Pesan error jika kredensial salah
        ])->onlyInput('email'); // Hanya mengisi kembali input email
    }

    /**
     * Menangani proses logout.
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        Log::info('Pengguna berhasil logout.');
        return redirect('/')->with('success', 'Anda telah berhasil logout.');
    }
}