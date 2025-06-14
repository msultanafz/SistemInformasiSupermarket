<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionDetail;

class TransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Hapus data transaksi dan detail transaksi lama
        TransactionDetail::truncate();
        Transaction::truncate();

        // Ambil semua user yang bisa berperan sebagai kasir (misal: role 'admin' atau 'cashier')
        $cashiers = User::whereIn('role', ['admin', 'cashier'])->get();

        // Ambil semua produk yang tersedia
        $products = Product::all();

        // Pastikan ada kasir dan produk sebelum melanjutkan
        if ($cashiers->isEmpty() || $products->isEmpty()) {
            $this->command->error('Tidak ada kasir atau produk ditemukan untuk membuat transaksi, seeder dibatalkan.');
            return;
        }

        // Jumlah transaksi yang ingin kita buat
        $numberOfTransactions = 20; // Contoh: buat 20 transaksi

        for ($i = 0; $i < $numberOfTransactions; $i++) {
            DB::transaction(function () use ($cashiers, $products) {
                // Pilih kasir secara acak
                $kasir = $cashiers->random();

                // Buat transaksi utama (struknya)
                $transaction = Transaction::create([
                    'transaction_code' => 'TRX-' . now()->format('Ymd-His') . '-' . uniqid(), // Tambah uniqid agar lebih unik
                    'user_id' => $kasir->id,
                    'total_amount' => 0, // Akan di-update nanti
                ]);

                $totalAmountForThisTransaction = 0;
                $numberOfItems = rand(1, 5); // Setiap transaksi berisi 1-5 jenis produk

                // Buat detail transaksinya
                // Pastikan produk yang dipilih unik dalam satu transaksi
                $selectedProducts = $products->shuffle()->take($numberOfItems);

                foreach ($selectedProducts as $product) {
                    $quantity = rand(1, 10); // Kuantitas per produk antara 1-10
                    $subtotal = $quantity * $product->price;
                    $totalAmountForThisTransaction += $subtotal;

                    TransactionDetail::create([
                        'transaction_id' => $transaction->id,
                        'product_id' => $product->id,
                        'quantity' => $quantity,
                        'price_at_transaction' => $product->price,
                    ]);
                }

                // Update transaksi utama dengan total belanja yang benar
                $transaction->update(['total_amount' => $totalAmountForThisTransaction]);
            });
        }

        $this->command->info($numberOfTransactions . ' transaksi telah berhasil dibuat.');
    }
}
