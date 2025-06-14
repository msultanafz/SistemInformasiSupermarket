<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('transaction_details', function (Blueprint $table) {
            $table->id();

            // Kolom penghubung ke tabel transactions
            $table->foreignId('transaction_id')
                ->constrained('transactions')
                ->onDelete('cascade'); // Jika transaksi dihapus, detailnya ikut terhapus

            // Kolom penghubung ke tabel products
            $table->foreignId('product_id')->constrained('products');

            $table->integer('quantity'); // Jumlah produk yang dibeli
            $table->unsignedBigInteger('price_at_transaction'); // Harga produk saat transaksi terjadi

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaction_details');
    }
};
