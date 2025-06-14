<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction; // <-- Pastikan ini ada

class TransactionController extends Controller
{
    /**
     * Menampilkan transaksi yang terjadi hari ini.
     */
    public function indexToday()
    {
        $todayTransactions = Transaction::with('user')
            ->whereDate('created_at', today())
            ->latest()
            ->paginate(15);

        return view('transactions.index', [
            'transactions' => $todayTransactions,
            'pageTitle' => 'Daftar Transaksi Hari Ini'
        ]);
    }
}
