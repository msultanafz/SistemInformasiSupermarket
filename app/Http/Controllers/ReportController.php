<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\TransactionDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str; // 

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
    public function dailyReport(Request $request) // <-- Tambahkan Request sebagai parameter
    {
        $pageTitle = 'Laporan Harian';
        $today = now()->toDateString();

        $totalRevenueToday = Transaction::whereDate('created_at', $today)->sum('total_amount');
        $totalTransactionsToday = Transaction::whereDate('created_at', $today)->count();

        $topSellingProductsToday = TransactionDetail::selectRaw('products.name as product_name, products.sku as product_sku, suppliers.name as supplier_name, SUM(transaction_details.quantity) as total_quantity_sold')
            ->join('transactions', 'transaction_details.transaction_id', '=', 'transactions.id')
            ->join('products', 'transaction_details.product_id', '=', 'products.id')
            ->leftJoin('suppliers', 'products.supplier_id', '=', 'suppliers.id')
            ->whereDate('transactions.created_at', $today)
            ->groupBy('products.name', 'products.sku', 'suppliers.name')
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

        $suppliersInvolvedToday = DB::table('transaction_details')
            ->selectRaw('suppliers.name as supplier_name, COUNT(DISTINCT products.id) as total_products_sold, SUM(transaction_details.quantity) as total_units_sold')
            ->join('products', 'transaction_details.product_id', '=', 'products.id')
            ->join('suppliers', 'products.supplier_id', '=', 'suppliers.id')
            ->join('transactions', 'transaction_details.transaction_id', '=', 'transactions.id')
            ->whereDate('transactions.created_at', $today)
            ->groupBy('suppliers.name')
            ->orderByDesc('total_units_sold')
            ->get();
        
        // LOGIKA PERBAIKAN UNTUK TOMBOL KEMBALI
        $previousUrl = URL::previous();
        $backUrl = $previousUrl; // Default URL kembali adalah URL sebelumnya
        $backText = 'Kembali'; // Default teks

        // Normalisasi URL dengan menghapus port jika ada, untuk perbandingan yang lebih baik
        $normalizedPreviousUrl = preg_replace('/:\d{4}/', '', $previousUrl);
        $normalizedDashboardUrl = preg_replace('/:\d{4}/', '', route('dashboard'));
        $normalizedReportsIndexUrl = preg_replace('/:\d{4}/', '', route('reports.index'));

        // Cek apakah URL sebelumnya dimulai dengan URL Dashboard atau Daftar Laporan
        if (Str::startsWith($normalizedPreviousUrl, $normalizedDashboardUrl)) {
            $backText = 'Kembali ke Dashboard';
            $backUrl = route('dashboard'); // Pastikan URL kembali ke route dashboard yang benar
        } elseif (Str::startsWith($normalizedPreviousUrl, $normalizedReportsIndexUrl)) {
            $backText = 'Kembali ke Daftar Laporan';
            $backUrl = route('reports.index'); // Pastikan URL kembali ke route reports.index yang benar
        } else {
             // Fallback jika tidak cocok dengan dashboard atau daftar laporan (misal dari login atau direct access)
             // Defaultkan ke daftar laporan sebagai tujuan yang paling logis
             $backUrl = route('reports.index');
             $backText = 'Kembali ke Daftar Laporan';
        }


        return view('reports.daily', [
            'pageTitle' => $pageTitle,
            'totalRevenueToday' => $totalRevenueToday,
            'totalTransactionsToday' => $totalTransactionsToday,
            'topSellingProductsToday' => $topSellingProductsToday,
            'mostActiveCashiersToday' => $mostActiveCashiersToday,
            'suppliersInvolvedToday' => $suppliersInvolvedToday,
            'previousUrl' => $backUrl, // Kirimkan URL yang sudah ditentukan
            'previousText' => $backText, // Kirimkan teks yang sudah ditentukan
        ]);
    }

    /**
     * Menampilkan laporan ringkasan bulanan untuk bulan dan tahun tertentu.
     */
    public function monthlyReport(Request $request)
    {
        $year = $request->input('year', Carbon::now()->year);
        $month = $request->input('month', Carbon::now()->month);

        $startOfMonth = Carbon::create($year, $month, 1)->startOfDay();
        $endOfMonth = Carbon::create($year, $month, 1)->endOfMonth()->endOfDay();

        $pageTitle = 'Laporan Bulanan';

        $totalRevenueMonthly = Transaction::whereBetween('created_at', [$startOfMonth, $endOfMonth])->sum('total_amount');
        $totalTransactionsMonthly = Transaction::whereBetween('created_at', [$startOfMonth, $endOfMonth])->count();

        $topSellingProductsMonthly = TransactionDetail::selectRaw('products.name as product_name, products.sku as product_sku, suppliers.name as supplier_name, SUM(transaction_details.quantity) as total_quantity_sold')
            ->join('transactions', 'transaction_details.transaction_id', '=', 'transactions.id')
            ->join('products', 'transaction_details.product_id', '=', 'products.id')
            ->leftJoin('suppliers', 'products.supplier_id', '=', 'suppliers.id')
            ->whereBetween('transactions.created_at', [$startOfMonth, $endOfMonth])
            ->groupBy('products.name', 'products.sku', 'suppliers.name')
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
        
        $suppliersInvolvedMonthly = DB::table('transaction_details')
            ->selectRaw('suppliers.name as supplier_name, COUNT(DISTINCT products.id) as total_products_sold, SUM(transaction_details.quantity) as total_units_sold')
            ->join('products', 'transaction_details.product_id', '=', 'products.id')
            ->join('suppliers', 'products.supplier_id', '=', 'suppliers.id')
            ->join('transactions', 'transaction_details.transaction_id', '=', 'transactions.id')
            ->whereBetween('transactions.created_at', [$startOfMonth, $endOfMonth])
            ->groupBy('suppliers.name')
            ->orderByDesc('total_units_sold')
            ->get();

        // LOGIKA PERBAIKAN UNTUK TOMBOL KEMBALI
        $previousUrl = URL::previous();
        $backUrl = $previousUrl;
        $backText = 'Kembali';

        $normalizedPreviousUrl = preg_replace('/:\d{4}/', '', $previousUrl);
        $normalizedDashboardUrl = preg_replace('/:\d{4}/', '', route('dashboard'));
        $normalizedReportsIndexUrl = preg_replace('/:\d{4}/', '', route('reports.index'));

        if (Str::startsWith($normalizedPreviousUrl, $normalizedDashboardUrl)) {
            $backText = 'Kembali ke Dasbor';
            $backUrl = route('dashboard');
        } elseif (Str::startsWith($normalizedPreviousUrl, $normalizedReportsIndexUrl)) {
            $backText = 'Kembali ke Daftar Laporan';
            $backUrl = route('reports.index');
        } else {
             $backUrl = route('reports.index');
             $backText = 'Kembali ke Daftar Laporan';
        }

        return view('reports.monthly', [
            'pageTitle' => $pageTitle,
            'year' => $year,
            'month' => $month,
            'totalRevenueMonthly' => $totalRevenueMonthly,
            'totalTransactionsMonthly' => $totalTransactionsMonthly,
            'topSellingProductsMonthly' => $topSellingProductsMonthly,
            'mostActiveCashiersMonthly' => $mostActiveCashiersMonthly,
            'suppliersInvolvedMonthly' => $suppliersInvolvedMonthly,
            'previousUrl' => $backUrl,
            'previousText' => $backText,
        ]);
    }

    /**
     * Menampilkan laporan ringkasan tahunan untuk tahun tertentu.
     */
    public function yearlyReport(Request $request)
    {
        $year = $request->input('year', Carbon::now()->year);

        $startOfYear = Carbon::create($year, 1, 1)->startOfDay();
        $endOfYear = Carbon::create($year, 12, 31)->endOfDay();

        $pageTitle = 'Laporan Tahunan';

        $totalRevenueYearly = Transaction::whereBetween('created_at', [$startOfYear, $endOfYear])->sum('total_amount');
        $totalTransactionsYearly = Transaction::whereBetween('created_at', [$startOfYear, $endOfYear])->count();

        $topSellingProductsYearly = TransactionDetail::selectRaw('products.name as product_name, products.sku as product_sku, suppliers.name as supplier_name, SUM(transaction_details.quantity) as total_quantity_sold')
            ->join('transactions', 'transaction_details.transaction_id', '=', 'transactions.id')
            ->join('products', 'transaction_details.product_id', '=', 'products.id')
            ->leftJoin('suppliers', 'products.supplier_id', '=', 'suppliers.id')
            ->whereBetween('transactions.created_at', [$startOfYear, $endOfYear])
            ->groupBy('products.name', 'products.sku', 'suppliers.name')
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
        
        $suppliersInvolvedYearly = DB::table('transaction_details')
            ->selectRaw('suppliers.name as supplier_name, COUNT(DISTINCT products.id) as total_products_sold, SUM(transaction_details.quantity) as total_units_sold')
            ->join('products', 'transaction_details.product_id', '=', 'products.id')
            ->join('suppliers', 'products.supplier_id', '=', 'suppliers.id')
            ->join('transactions', 'transaction_details.transaction_id', '=', 'transactions.id')
            ->whereBetween('transactions.created_at', [$startOfYear, $endOfYear])
            ->groupBy('suppliers.name')
            ->orderByDesc('total_units_sold')
            ->get();

        // LOGIKA PERBAIKAN UNTUK TOMBOL KEMBALI
        $previousUrl = URL::previous();
        $backUrl = $previousUrl;
        $backText = 'Kembali';

        $normalizedPreviousUrl = preg_replace('/:\d{4}/', '', $previousUrl);
        $normalizedDashboardUrl = preg_replace('/:\d{4}/', '', route('dashboard'));
        $normalizedReportsIndexUrl = preg_replace('/:\d{4}/', '', route('reports.index'));

        if (Str::startsWith($normalizedPreviousUrl, $normalizedDashboardUrl)) {
            $backText = 'Kembali ke Dashboard';
            $backUrl = route('dashboard');
        } elseif (Str::startsWith($normalizedPreviousUrl, $normalizedReportsIndexUrl)) {
            $backText = 'Kembali ke Daftar Laporan';
            $backUrl = route('reports.index');
        } else {
             $backUrl = route('reports.index');
             $backText = 'Kembali ke Daftar Laporan';
        }

        return view('reports.yearly', [
            'pageTitle' => $pageTitle,
            'year' => $year,
            'totalRevenueYearly' => $totalRevenueYearly,
            'totalTransactionsYearly' => $totalTransactionsYearly,
            'topSellingProductsYearly' => $topSellingProductsYearly,
            'mostActiveCashiersYearly' => $mostActiveCashiersYearly,
            'suppliersInvolvedYearly' => $suppliersInvolvedYearly,
            'previousUrl' => $backUrl,
            'previousText' => $backText,
        ]);
    }
}