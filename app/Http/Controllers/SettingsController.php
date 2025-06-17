<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Untuk mendapatkan user yang sedang login
use Illuminate\Support\Facades\Hash; // Untuk hashing password
use Illuminate\Validation\Rule; // Untuk validasi unique email kecuali untuk user sendiri

class SettingsController extends Controller
{
    /**
     * Menampilkan halaman pengaturan utama.
     */
    public function index()
    {
        $pageTitle = 'Pengaturan Akun';
        $user = Auth::user(); // Dapatkan user yang sedang login
        return view('settings.index', compact('pageTitle', 'user'));
    }

    /**
     * Memperbarui informasi profil user yang sedang login.
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            // Email harus unik, tapi abaikan email user saat ini
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
        ]);

        try {
            $user->name = $request->name;
            $user->email = $request->email;
            $user->save();

            return redirect()->route('settings.index')->with('success', 'Profil berhasil diperbarui!');
        } catch (\Exception $e) {
            \Log::error('Gagal memperbarui profil: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal memperbarui profil. Silakan coba lagi.')->withInput();
        }
    }

    /**
     * Memperbarui password user yang sedang login.
     */
    public function updatePassword(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:8|confirmed', // 'confirmed' akan memvalidasi new_password_confirmation
        ]);

        // Cek apakah password lama sesuai
        if (!Hash::check($request->current_password, $user->password)) {
            return redirect()->back()->withErrors(['current_password' => 'Password lama tidak cocok.']);
        }

        try {
            $user->password = Hash::make($request->new_password);
            $user->save();

            return redirect()->route('settings.index')->with('success', 'Password berhasil diperbarui!');
        } catch (\Exception $e) {
            \Log::error('Gagal memperbarui password: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal memperbarui password. Silakan coba lagi.');
        }
    }
}