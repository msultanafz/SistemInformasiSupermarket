<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Transaction;

class DashboardController extends Controller
{
    public function index()
    {
        // --- Data untuk Kartu Atas ---
        $lowStockCount_card = Product::where('stock', '<=', 10)->count();
        $todayRevenue = Transaction::whereDate('created_at', today())->sum('total_amount');
        $todayTransactionCount = Transaction::whereDate('created_at', today())->count();
        $totalProductCount = Product::count();

        // --- Data untuk Tabel Transaksi Terakhir ---
        $recentTransactions = Transaction::with('user')->latest()->take(5)->get();

        // === LOGIKA UNTUK PANEL KANAN (STATUS STOK) ===
        $lowStockCount_panel = Product::where('stock', '>', 0)->where('stock', '<=', 10)->count();
        $outOfStockCount = Product::where('stock', '=', 0)->count();

        // Pengaman jika tidak ada produk sama sekali untuk menghindari error pembagian dengan nol
        if ($totalProductCount > 0) {
            $safeStockCount = $totalProductCount - $lowStockCount_panel - $outOfStockCount;

            $safeStockPercentage = ($safeStockCount / $totalProductCount) * 100;
            $lowStockPercentage = ($lowStockCount_panel / $totalProductCount) * 100;
            $outOfStockPercentage = ($outOfStockCount / $totalProductCount) * 100;
        } else {
            $safeStockPercentage = 0;
            $lowStockPercentage = 0;
            $outOfStockPercentage = 0;
        }

        // Kirim SEMUA data ke view
        return view('dashboard', [
            'stokHampirHabis' => $lowStockCount_card,
            'pendapatanHariIni' => $todayRevenue,
            'jumlahTransaksiHariIni' => $todayTransactionCount,
            'totalJenisProduk' => $totalProductCount,
            'transaksiTerakhir' => $recentTransactions,

            // Variabel baru untuk panel kanan
            'safeStockPercentage' => $safeStockPercentage,
            'lowStockPercentage' => $lowStockPercentage,
            'outOfStockPercentage' => $outOfStockPercentage,
        ]);
    }
}
