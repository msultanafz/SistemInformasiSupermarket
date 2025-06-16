<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL; // <-- PASTIKAN BARIS INI ADA!

class TransactionController extends Controller
{
    /**
     * Menampilkan daftar SEMUA transaksi dengan pagination.
     */
    public function index()
    {
        $transactions = Transaction::with('user')
            ->latest()
            ->paginate(10);

        $pageTitle = 'Daftar Semua Transaksi';

        return view('transactions.index', [
            'transactions' => $transactions,
            'pageTitle' => $pageTitle
        ]);
    }

    /**
     * Menampilkan daftar transaksi HARI INI dengan pagination.
     */
    public function todayTransactions()
    {
        $transactions = Transaction::with('user')
            ->whereDate('created_at', today())
            ->latest()
            ->paginate(10);

        $pageTitle = 'Transaksi Hari Ini';

        return view('transactions.index', [
            'transactions' => $transactions,
            'pageTitle' => $pageTitle
        ]);
    }

    /**
     * Menampilkan halaman untuk membuat transaksi baru (Point of Sale/POS).
     */
    public function create()
    {
        $pageTitle = 'Mulai Transaksi (POS)';
        $products = Product::with('category')->orderBy('name', 'asc')->get();

        return view('transactions.create', [
            'pageTitle' => $pageTitle,
            'products' => $products
        ]);
    }

    /**
     * Menyimpan transaksi baru dari halaman POS.
     */
    public function store(Request $request)
    {
        // 1. Validasi data yang diterima dari keranjang
        $request->validate([
            'items' => 'required|array|min:1',
            'items.*.id' => 'required|integer|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        DB::beginTransaction();

        try {
            $totalAmount = 0;
            $transactionItems = [];

            foreach ($request->items as $item) {
                $product = Product::find($item['id']);

                if (!$product || $product->stock < $item['quantity']) {
                    DB::rollBack();
                    return response()->json([
                        'success' => false,
                        'message' => 'Stok tidak cukup atau produk tidak ditemukan untuk: ' . ($product ? $product->name : 'ID ' . $item['id'])
                    ], 400);
                }

                $subtotal = $item['quantity'] * $product->price;
                $totalAmount += $subtotal;

                $transactionItems[] = [
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'price_at_transaction' => $product->price,
                ];

                $product->decrement('stock', $item['quantity']);
            }

            $transaction = Transaction::create([
                'transaction_code' => 'TRX-' . now()->format('YmdHis') . '-' . Auth::id() . '-' . uniqid(),
                'user_id' => Auth::id(),
                'total_amount' => $totalAmount,
            ]);

            foreach ($transactionItems as $detail) {
                $transaction->details()->create($detail);
            }

            DB::commit();

            return response()->json(['success' => true, 'message' => 'Transaksi berhasil!', 'transaction_id' => $transaction->id]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Transaction failed: ' . $e->getMessage() . ' on line ' . $e->getLine() . ' in ' . $e->getFile());
            return response()->json(['success' => false, 'message' => 'Gagal menyelesaikan transaksi. Silakan coba lagi.'], 500);
        }
    }

    /**
     * Menampilkan detail transaksi spesifik.
     */
    public function show(Transaction $transaction)
    {
        $transaction->load(['user', 'details.product']);
        $pageTitle = 'Detail Transaksi';

        // PASTIKAN BARIS INI ADA PERSIS SEPERTI INI
        $previousUrl = URL::previous();

        return view('transactions.show', [
            'transaction' => $transaction,
            'pageTitle' => $pageTitle,
            'previousUrl' => $previousUrl // DAN PASTIKAN INI DIKIRIM KE VIEW
        ]);
    }
}
