<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migrasi.
     */
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Tambahkan kolom supplier_id setelah category_id
            $table->foreignId('supplier_id')->nullable()->after('category_id')->constrained();
            // constraint() akan membuat foreign key ke tabel 'suppliers' dan kolom 'id'
            // nullable() karena mungkin ada produk lama yang tidak punya supplier atau supplier_id akan diisi belakangan
        });
    }

    /**
     * Batalkan migrasi.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Hapus foreign key terlebih dahulu sebelum menghapus kolom
            $table->dropConstrainedForeignId('supplier_id');
            $table->dropColumn('supplier_id');
        });
    }
};