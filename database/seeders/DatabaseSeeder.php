<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Panggil seeder untuk User terlebih dahulu
        $this->call([
            UserSeeder::class, // Pastikan ini dipanggil PALING AWAL
            CategorySeeder::class,
            ProductSeeder::class,
            TransactionSeeder::class,
        ]);
    }
}
