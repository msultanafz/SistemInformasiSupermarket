<?php

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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('sku')->unique()->nullable(); // SKU, kode unik, boleh kosong
            $table->integer('price')->default(0); // Harga jual
            $table->integer('stock')->default(0); // Jumlah stok

            // Ini adalah kolom penghubung (Foreign Key) ke tabel 'categories'
            $table->foreignId('category_id')
                ->nullable()
                ->constrained('categories') // Merujuk ke kolom 'id' di tabel 'categories'
                ->onDelete('set null'); // Jika kategori dihapus, kolom ini jadi NULL

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
