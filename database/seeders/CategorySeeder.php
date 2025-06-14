<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category; // <-- IMPORT MODEL CATEGORY

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Hapus data lama (opsional, tapi bagus untuk testing)
        Category::truncate();

        Category::create(['name' => 'Minuman']);
        Category::create(['name' => 'Makanan Ringan']);
        Category::create(['name' => 'Sembako']);
        Category::create(['name' => 'Produk Kebersihan']);
        Category::create(['name' => 'Obat-obatan']);
    }
}
