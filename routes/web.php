<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\TransactionController;

/*
|--------------------------------------------------------------------------
| Halaman Autentikasi (Login)
|--------------------------------------------------------------------------
*/

// Middleware 'guest' memastikan user yang sudah login tidak bisa mengakses halaman ini lagi.
// Mereka akan otomatis diarahkan ke '/dashboard'.
Route::get('/', function () {
    return view('login');
})->middleware('guest')->name('login'); // Kita beri nama 'login' agar mudah dipanggil

// Route untuk memproses data dari form login
Route::post('/login', [AuthController::class, 'loginAction'])->name('login.action');

// Route untuk logout
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


/*
|--------------------------------------------------------------------------
| Halaman Setelah Login
|--------------------------------------------------------------------------
*/

// Middleware 'auth' memastikan hanya user yang sudah login yang bisa mengakses halaman ini.
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware('auth')
    ->name('dashboard');

// Anda bisa menambahkan route lain yang butuh login di sini
// Route::get('/products', [ProductController::class, 'index'])->middleware('auth')->name('products.index');

Route::get('/products/low-stock', [ProductController::class, 'showLowStock'])
    ->middleware('auth')
    ->name('products.low-stock');

// Route untuk menampilkan SEMUA produk (halaman utama inventaris)
Route::get('/products', [ProductController::class, 'index'])
    ->middleware('auth')
    ->name('products.index');

// Route untuk menampilkan transaksi HARI INI
Route::get('/transactions/today', [TransactionController::class, 'indexToday'])
    ->middleware('auth')
    ->name('transactions.today');
