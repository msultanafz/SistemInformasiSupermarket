<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
// use App\Http\Controllers\TransactionController; // Ini belum dibutuhkan jika menggunakan closure sementara
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Transaction; // Penting: Pastikan ini di-import

/*
|--------------------------------------------------------------------------
| Halaman Autentikasi (Login & Logout)
|--------------------------------------------------------------------------
*/

// Middleware 'guest' memastikan user yang sudah login tidak bisa mengakses halaman ini lagi.
// Mereka akan otomatis diarahkan ke '/dashboard'.
Route::get('/', function () {
    return view('login'); // Pastikan view 'login' ada
})->middleware('guest')->name('login');

// Route untuk memproses data dari form login
Route::post('/login', [AuthController::class, 'loginAction'])->name('login.action');

// Route untuk logout
// Pindahkan ke dalam grup 'auth' agar hanya user terautentikasi yang bisa logout
// Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


/*
|--------------------------------------------------------------------------
| Halaman Setelah Login (Membutuhkan Autentikasi)
|--------------------------------------------------------------------------
*/

// Grup route ini akan otomatis menerapkan middleware 'auth' ke semua route di dalamnya.
Route::middleware(['auth'])->group(function () {
    // Route Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Route untuk daftar produk stok rendah
    Route::get('/products/low-stock', [ProductController::class, 'showLowStock'])->name('products.low-stock');

    // Route Sumber Daya (Resource) untuk Produk
    // Ini akan membuat products.index, products.create, products.store, products.show, products.edit, products.update, products.destroy
    Route::resource('products', ProductController::class);

    // Route untuk daftar transaksi hari ini
    // Ini adalah definisi yang benar dan lengkap
    Route::get('/transactions/today', function () {
        $pageTitle = 'Transaksi Hari Ini'; // Judul halaman
        // Ambil transaksi hari ini dan paginasi
        $transactions = Transaction::with('user')
            ->whereDate('created_at', today())
            ->latest()
            ->paginate(10); // Menampilkan 10 transaksi per halaman

        return view('transactions.index', [
            'pageTitle' => $pageTitle,
            'transactions' => $transactions // Kirim data transaksi ke view
        ]);
    })->name('transactions.today');

    // Route Logout (Dipindahkan ke dalam grup 'auth')
    Route::post('/logout', function (Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login'); // Kembali ke halaman login setelah logout
    })->name('logout');
});
