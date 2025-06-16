<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Transaction;
use App\Models\Category;
use App\Models\TransactionDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // --- Data untuk Kartu Atas ---
        $lowStockCount = Product::where('stock', '<=', 10)->count();
        $todayRevenue = Transaction::whereDate('created_at', today())->sum('total_amount');
        $todayTransactionCount = Transaction::whereDate('created_at', today())->count();
        $totalProductCount = Product::count();

        // --- Data untuk Tabel Transaksi Terakhir ---
        $recentTransactions = Transaction::with('user')
            ->latest()
            ->take(5)
            ->get();

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

        // === LOGIKA UNTUK KATEGORI TERLARIS ===
        $topSellingCategories = TransactionDetail::selectRaw('categories.name as category_name, SUM(transaction_details.quantity) as total_quantity_sold')
            ->join('products', 'transaction_details.product_id', '=', 'products.id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->groupBy('categories.name')
            ->orderByDesc('total_quantity_sold')
            ->take(5)
            ->get();

        $pageTitle = 'Dashboard'; // Judul halaman untuk Dasbor

        // Kirim SEMUA data ke view
        return view('dashboard', [
            'stokHampirHabis' => $lowStockCount,
            'pendapatanHariIni' => $todayRevenue,
            'jumlahTransaksiHariIni' => $todayTransactionCount,
            'totalJenisProduk' => $totalProductCount,
            'transaksiTerakhir' => $recentTransactions,
            'safeStockPercentage' => $safeStockPercentage, // Kembali kirim ini
            'lowStockPercentage' => $lowStockPercentage,   // Kembali kirim ini
            'outOfStockPercentage' => $outOfStockPercentage, // Kembali kirim ini
            'topSellingCategories' => $topSellingCategories,
            'pageTitle' => $pageTitle,
        ]);
    }
}
