<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;

class AuthController extends Controller
{
    /**
     * Menangani permintaan autentikasi (login).
     */
    public function loginAction(Request $request): RedirectResponse
    {
        // 1. Validasi input dari form
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // 2. Coba untuk mengautentikasi pengguna
        //    Auth::attempt() akan otomatis membandingkan password yang di-hash
        if (Auth::attempt($credentials)) {
            // 3. Jika berhasil, buat session baru
            $request->session()->regenerate();

            // 4. Arahkan ke halaman dashboard
            return redirect()->intended('dashboard');
        }

        // 5. Jika gagal, kembali ke halaman login dengan pesan error
        return back()->withErrors([
            'email' => 'Email atau Password yang Anda masukkan salah.',
        ])->onlyInput('email');
    }

    /**
     * Menangani permintaan logout.
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
