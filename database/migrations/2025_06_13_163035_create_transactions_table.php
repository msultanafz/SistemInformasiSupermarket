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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id(); // ID unik untuk setiap transaksi
            $table->string('transaction_code')->unique(); // Kode struk yang unik, misal: TRX-20250614-0001
            $table->unsignedBigInteger('total_amount'); // Total nilai belanja dalam transaksi ini

            // Kolom penghubung ke kasir yang melayani (dari tabel users)
            $table->foreignId('user_id')->constrained('users');

            $table->timestamps(); // Waktu transaksi akan tercatat di created_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
