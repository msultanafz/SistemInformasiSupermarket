<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product; // <-- Import Model Product

class ProductController extends Controller
{
    /**
     * Menampilkan produk yang stoknya sedikit.
     */
    public function showLowStock()
    {
        // Ambil daftar LENGKAP produk yang stoknya 10 atau kurang
        $lowStockProducts = Product::where('stock', '<=', 10)->get();

        // Kirim data tersebut ke view 'products.index'
        return view('products.index', [
            'products' => $lowStockProducts,
            'pageTitle' => 'Produk Segera Habis' // Ini untuk judul halaman
        ]);
    }

    public function index()
    {
        // Ambil SEMUA produk, diurutkan dari yang terbaru, dengan pagination
        $allProducts = Product::latest()->paginate(10); // Menampilkan 10 produk per halaman

        // Kita bisa gunakan view yang sama! Cukup kirim data yang berbeda.
        return view('products.index', [
            'products' => $allProducts,
            'pageTitle' => 'Daftar Semua Produk'
        ]);
    }
}
