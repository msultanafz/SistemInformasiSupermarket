<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Buat user utama untuk login dan sebagai kasir
        // Ini akan selalu dijalankan setiap kali 'db:seed' dipanggil
        \App\Models\User::factory()->create([
            'name' => 'Admin Toko',
            'email' => 'admin@toko.com',
            // Secara default, passwordnya adalah 'password'
        ]);

        // 2. Panggil seeder lain setelah user dibuat
        $this->call([
            CategorySeeder::class,
            ProductSeeder::class,
            TransactionSeeder::class,
        ]);
    }
}
