<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\TransactionDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\URL; // <-- Import Facade URL

class ReportController extends Controller
{
    /**
     * Menampilkan halaman indeks untuk berbagai jenis laporan.
     */
    public function index()
    {
        $pageTitle = 'Daftar Laporan';
        return view('reports.index', compact('pageTitle'));
    }

    /**
     * Menampilkan laporan ringkasan harian.
     */
    public function dailyReport()
    {
        $pageTitle = 'Laporan Harian';

        $today = now()->toDateString();

        $totalRevenueToday = Transaction::whereDate('created_at', $today)->sum('total_amount');
        $totalTransactionsToday = Transaction::whereDate('created_at', $today)->count();

        $topSellingProductsToday = TransactionDetail::selectRaw('products.name as product_name, products.sku as product_sku, SUM(transaction_details.quantity) as total_quantity_sold')
            ->join('transactions', 'transaction_details.transaction_id', '=', 'transactions.id')
            ->join('products', 'transaction_details.product_id', '=', 'products.id')
            ->whereDate('transactions.created_at', $today)
            ->groupBy('products.name', 'products.sku')
            ->orderByDesc('total_quantity_sold')
            ->take(5)
            ->get();

        $mostActiveCashiersToday = Transaction::selectRaw('users.name as cashier_name, COUNT(transactions.id) as total_transactions')
            ->join('users', 'transactions.user_id', '=', 'users.id')
            ->whereDate('transactions.created_at', $today)
            ->groupBy('users.name')
            ->orderByDesc('total_transactions')
            ->take(3)
            ->get();

        // Perbaikan: Ambil URL halaman sebelumnya
        $previousUrl = URL::previous();

        return view('reports.daily', [
            'pageTitle' => $pageTitle,
            'totalRevenueToday' => $totalRevenueToday,
            'totalTransactionsToday' => $totalTransactionsToday,
            'topSellingProductsToday' => $topSellingProductsToday,
            'mostActiveCashiersToday' => $mostActiveCashiersToday,
            'previousUrl' => $previousUrl, // Kirimkan URL sebelumnya ke view
        ]);
    }

    /**
     * Menampilkan laporan ringkasan bulanan untuk bulan dan tahun tertentu.
     * Secara default, akan menampilkan laporan untuk bulan dan tahun saat ini.
     */
    public function monthlyReport(Request $request)
    {
        $year = $request->input('year', Carbon::now()->year);
        $month = $request->input('month', Carbon::now()->month);

        $startOfMonth = Carbon::create($year, $month, 1)->startOfDay();
        $endOfMonth = Carbon::create($year, $month, 1)->endOfMonth()->endOfDay();

        $pageTitle = 'Laporan Bulanan: ' . Carbon::create($year, $month, 1)->translatedFormat('F Y');

        $totalRevenueMonthly = Transaction::whereBetween('created_at', [$startOfMonth, $endOfMonth])->sum('total_amount');
        $totalTransactionsMonthly = Transaction::whereBetween('created_at', [$startOfMonth, $endOfMonth])->count();

        $topSellingProductsMonthly = TransactionDetail::selectRaw('products.name as product_name, products.sku as product_sku, SUM(transaction_details.quantity) as total_quantity_sold')
            ->join('transactions', 'transaction_details.transaction_id', '=', 'transactions.id')
            ->join('products', 'transaction_details.product_id', '=', 'products.id')
            ->whereBetween('transactions.created_at', [$startOfMonth, $endOfMonth])
            ->groupBy('products.name', 'products.sku')
            ->orderByDesc('total_quantity_sold')
            ->take(10)
            ->get();

        $mostActiveCashiersMonthly = Transaction::selectRaw('users.name as cashier_name, COUNT(transactions.id) as total_transactions')
            ->join('users', 'transactions.user_id', '=', 'users.id')
            ->whereBetween('transactions.created_at', [$startOfMonth, $endOfMonth])
            ->groupBy('users.name')
            ->orderByDesc('total_transactions')
            ->take(5)
            ->get();

        // Ambil URL halaman sebelumnya untuk tombol kembali
        $previousUrl = URL::previous();

        return view('reports.monthly', [
            'pageTitle' => $pageTitle,
            'year' => $year,
            'month' => $month,
            'totalRevenueMonthly' => $totalRevenueMonthly,
            'totalTransactionsMonthly' => $totalTransactionsMonthly,
            'topSellingProductsMonthly' => $topSellingProductsMonthly,
            'mostActiveCashiersMonthly' => $mostActiveCashiersMonthly,
            'previousUrl' => $previousUrl, // Kirimkan URL sebelumnya ke view
        ]);
    }

    /**
     * Menampilkan laporan ringkasan tahunan untuk tahun tertentu.
     * Secara default, akan menampilkan laporan untuk tahun saat ini.
     */
    public function yearlyReport(Request $request)
    {
        $year = $request->input('year', Carbon::now()->year);

        $startOfYear = Carbon::create($year, 1, 1)->startOfDay();
        $endOfYear = Carbon::create($year, 12, 31)->endOfDay();

        $pageTitle = 'Laporan Tahunan: ' . $year;

        $totalRevenueYearly = Transaction::whereBetween('created_at', [$startOfYear, $endOfYear])->sum('total_amount');
        $totalTransactionsYearly = Transaction::whereBetween('created_at', [$startOfYear, $endOfYear])->count();

        $topSellingProductsYearly = TransactionDetail::selectRaw('products.name as product_name, products.sku as product_sku, SUM(transaction_details.quantity) as total_quantity_sold')
            ->join('transactions', 'transaction_details.transaction_id', '=', 'transactions.id')
            ->join('products', 'transaction_details.product_id', '=', 'products.id')
            ->whereBetween('transactions.created_at', [$startOfYear, $endOfYear])
            ->groupBy('products.name', 'products.sku')
            ->orderByDesc('total_quantity_sold')
            ->take(10)
            ->get();

        $mostActiveCashiersYearly = Transaction::selectRaw('users.name as cashier_name, COUNT(transactions.id) as total_transactions')
            ->join('users', 'transactions.user_id', '=', 'users.id')
            ->whereBetween('transactions.created_at', [$startOfYear, $endOfYear])
            ->groupBy('users.name')
            ->orderByDesc('total_transactions')
            ->take(5)
            ->get();

        // Ambil URL halaman sebelumnya untuk tombol kembali
        $previousUrl = URL::previous();

        return view('reports.yearly', [
            'pageTitle' => $pageTitle,
            'year' => $year,
            'totalRevenueYearly' => $totalRevenueYearly,
            'totalTransactionsYearly' => $totalTransactionsYearly,
            'topSellingProductsYearly' => $topSellingProductsYearly,
            'mostActiveCashiersYearly' => $mostActiveCashiersYearly,
            'previousUrl' => $previousUrl, // Kirimkan URL sebelumnya ke view
        ]);
    }
}
