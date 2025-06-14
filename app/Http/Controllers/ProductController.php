<?php

namespace App\Http\Controllers;

use App\Models\Category; // Tambahkan ini jika belum ada
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Menampilkan SEMUA produk dengan pagination (untuk route products.index).
     */
    public function index()
    {
        $allProducts = Product::with('category')->latest()->paginate(10); // Ambil juga data kategori
        $pageTitle = 'Daftar Semua Produk'; // Judul halaman

        return view('products.index', [
            'products' => $allProducts,
            'pageTitle' => $pageTitle
        ]);
    }

    /**
     * Menampilkan produk yang stoknya hampir habis (untuk route products.low-stock).
     */
    public function showLowStock()
    {
        $lowStockProducts = Product::with('category')
            ->where('stock', '>', 0)
            ->where('stock', '<=', 10)
            ->latest()
            ->paginate(10); // Gunakan paginate juga untuk konsistensi
        $pageTitle = 'Produk Segera Habis'; // Judul halaman

        return view('products.index', [
            'products' => $lowStockProducts,
            'pageTitle' => $pageTitle
        ]);
    }

    /**
     * Menampilkan form untuk membuat produk baru (products.create).
     */
    public function create()
    {
        $categories = Category::all(); // Ambil semua kategori untuk dropdown
        $pageTitle = 'Tambah Produk Baru';

        return view('products.create', [
            'categories' => $categories,
            'pageTitle' => $pageTitle
        ]);
    }

    /**
     * Menyimpan produk baru ke database (products.store).
     */
    public function store(Request $request)
    {
        // Logika validasi dan penyimpanan produk akan ditambahkan di sini
        $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'required|string|max:255|unique:products,sku',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id',
        ]);

        Product::create([
            'name' => $request->name,
            'sku' => $request->sku,
            'price' => $request->price,
            'stock' => $request->stock,
            'category_id' => $request->category_id,
        ]);

        return redirect()->route('products.index')->with('success', 'Produk berhasil ditambahkan!');
    }


    /**
     * Menampilkan detail produk (products.show) - Opsional, bisa diabaikan untuk CRUD dasar.
     */
    public function show(Product $product)
    {
        // return view('products.show', compact('product'));
        // Untuk saat ini, kita bisa redirect ke halaman edit saja jika perlu detail
        return redirect()->route('products.edit', $product->id);
    }

    /**
     * Menampilkan form untuk mengedit produk (products.edit).
     */
    public function edit(Product $product)
    {
        $categories = Category::all();
        $pageTitle = 'Edit Produk: ' . $product->name;
        return view('products.edit', [
            'product' => $product,
            'categories' => $categories,
            'pageTitle' => $pageTitle
        ]);
    }

    /**
     * Memperbarui produk di database (products.update).
     */
    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'required|string|max:255|unique:products,sku,' . $product->id, // Abaikan SKU ini untuk produk yang sedang diedit
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id',
        ]);

        $product->update([
            'name' => $request->name,
            'sku' => $request->sku,
            'price' => $request->price,
            'stock' => $request->stock,
            'category_id' => $request->category_id,
        ]);

        return redirect()->route('products.index')->with('success', 'Produk berhasil diperbarui!');
    }

    /**
     * Menghapus produk dari database (products.destroy).
     */
    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('products.index')->with('success', 'Produk berhasil dihapus!');
    }
}
