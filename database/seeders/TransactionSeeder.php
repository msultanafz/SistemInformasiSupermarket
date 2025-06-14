<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB; // <-- Import DB Facade
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
        // Kita akan membuat 1 contoh transaksi
        DB::transaction(function () {
            // 1. Ambil data yang kita perlukan (kasir dan beberapa produk)
            $kasir = User::where('email', 'admin@toko.com')->first();
            $produk1 = Product::where('sku', 'CC-1000')->first(); // Coca-Cola
            $produk2 = Product::where('sku', 'IDM-GRG')->first(); // Indomie

            if (!$kasir || !$produk1 || !$produk2) {
                // Jika user atau produk tidak ditemukan, hentikan seeder
                $this->command->error('User kasir atau produk tidak ditemukan, seeder transaksi dibatalkan.');
                return;
            }

            // 2. Buat satu transaksi utama (struknya)
            $transaksi = Transaction::create([
                'transaction_code' => 'TRX-' . now()->format('Ymd-His'),
                'user_id' => $kasir->id,
                'total_amount' => 0, // Kita isi 0 dulu, nanti di-update
            ]);

            // 3. Buat detail transaksinya (daftar belanjanya)
            //   - Beli 2 Coca-Cola
            TransactionDetail::create([
                'transaction_id' => $transaksi->id,
                'product_id' => $produk1->id,
                'quantity' => 2,
                'price_at_transaction' => $produk1->price,
            ]);

            //   - Beli 5 Indomie Goreng
            TransactionDetail::create([
                'transaction_id' => $transaksi->id,
                'product_id' => $produk2->id,
                'quantity' => 5,
                'price_at_transaction' => $produk2->price,
            ]);

            // 4. Hitung total belanja sebenarnya
            $totalBelanja = ($produk1->price * 2) + ($produk2->price * 5);

            // 5. Update transaksi utama dengan total belanja yang benar
            $transaksi->update(['total_amount' => $totalBelanja]);
        });
    }
}
