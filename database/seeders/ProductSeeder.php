<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product; // <-- IMPORT MODEL PRODUCT
use App\Models\Category; // <-- IMPORT MODEL CATEGORY JUGA

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Hapus data lama
        Product::truncate();

        // Ambil ID dari setiap kategori
        $cat_minuman = Category::where('name', 'Minuman')->first()->id;
        $cat_makanan = Category::where('name', 'Makanan Ringan')->first()->id;
        $cat_sembako = Category::where('name', 'Sembako')->first()->id;

        // Buat beberapa produk
        Product::create([
            'name' => 'Coca-Cola 1L',
            'sku' => 'CC-1000',
            'price' => 12000,
            'stock' => 50,
            'category_id' => $cat_minuman
        ]);

        Product::create([
            'name' => 'Indomie Goreng',
            'sku' => 'IDM-GRG',
            'price' => 3000,
            'stock' => 120, // Stok banyak
            'category_id' => $cat_sembako
        ]);

        Product::create([
            'name' => 'Qtela Keripik Singkong',
            'sku' => 'QTL-KSG',
            'price' => 8500,
            'stock' => 8, // Stok sedikit (untuk testing fitur "segera habis")
            'category_id' => $cat_makanan
        ]);
    }
}
