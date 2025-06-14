<?php

// database/migrations/YYYY_MM_DD_HHMMSS_create_transaction_details_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('transaction_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transaction_id')->constrained()->onDelete('cascade'); // Jika transaksi terhapus, detail juga terhapus
            $table->foreignId('product_id')->constrained()->onDelete('cascade'); // <-- TAMBAHKAN on_Delete('cascade') DI SINI
            $table->integer('quantity');
            $table->decimal('price_at_transaction', 10, 2); // Harga saat transaksi terjadi
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
