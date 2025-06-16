<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\StockOpnameController; // <-- Pastikan ini di-import
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Transaction;


/*
|--------------------------------------------------------------------------
| Halaman Autentikasi (Login & Logout)
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('login');
})->middleware('guest')->name('login');

Route::post('/login', [AuthController::class, 'loginAction'])->name('login.action');


/*
|--------------------------------------------------------------------------
| Halaman Setelah Login (Membutuhkan Autentikasi)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {
    // Route Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Route untuk daftar produk stok rendah
    Route::get('/products/low-stock', [ProductController::class, 'showLowStock'])->name('products.low-stock');

    // Route Sumber Daya (Resource) untuk Produk
    Route::resource('products', ProductController::class);

    // ROUTE TRANSAKSI
    Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions.index');
    Route::get('/transactions/today', [TransactionController::class, 'todayTransactions'])->name('transactions.today');
    Route::get('/transactions/create', [TransactionController::class, 'create'])->name('transactions.create');
    Route::post('/transactions', [TransactionController::class, 'store'])->name('transactions.store');
    Route::get('/transactions/{transaction}', [TransactionController::class, 'show'])->name('transactions.show');

    // ROUTE LAPORAN
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/daily', [ReportController::class, 'dailyReport'])->name('reports.daily');
    Route::get('/reports/monthly/{year?}/{month?}', [ReportController::class, 'monthlyReport'])->name('reports.monthly');
    Route::get('/reports/yearly/{year?}', [ReportController::class, 'yearlyReport'])->name('reports.yearly');

    // ROUTE STOK OPNAME - TAMBAHKAN INI
    Route::get('/stock-opname', [StockOpnameController::class, 'index'])->name('stock-opname.index');
    Route::post('/stock-opname', [StockOpnameController::class, 'store'])->name('stock-opname.store');

    // Route Logout
    Route::post('/logout', function (Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    })->name('logout');
});
