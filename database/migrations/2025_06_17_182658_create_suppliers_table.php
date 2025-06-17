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
        Schema::create('suppliers', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // Nama supplier, harus unik
            $table->string('contact_person')->nullable(); // Nama kontak person supplier
            $table->string('phone')->nullable(); // Nomor telepon supplier
            $table->string('email')->nullable()->unique(); // Email supplier, harus unik
            $table->text('address')->nullable(); // Alamat lengkap supplier
            $table->timestamps(); // created_at dan updated_at
        });
    }

    /**
     * Batalkan migrasi.
     */
    public function down(): void
    {
        Schema::dropIfExists('suppliers');
    }
};