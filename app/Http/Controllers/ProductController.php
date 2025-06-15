<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // Pastikan ini di-import untuk DB::raw()

class ProductController extends Controller
{
    /**
     * Menampilkan SEMUA produk dengan pagination dan pencarian yang diperbarui.
     */
    public function index(Request $request)
    {
        $query = Product::with('category')->latest(); // Mulai query

        // Logika Pencarian yang Diperbarui
        if ($request->has('search') && $request->search != '') {
            $search = strtolower($request->search); // Ubah input pencarian menjadi huruf kecil

            $query->where(function ($q) use ($search) {
                // Hanya mencari berdasarkan NAMA PRODUK
                // Menggunakan lower() di database agar tidak peka huruf besar/kecil
                $q->where(DB::raw('lower(name)'), 'like', '%' . $search . '%');
            });
        }

        $allProducts = $query->paginate(10);
        $pageTitle = 'Daftar Semua Produk';

        return view('products.index', [
            'products' => $allProducts,
            'pageTitle' => $pageTitle
        ]);
    }

    /**
     * Menampilkan produk yang stoknya hampir habis (untuk route products.low-stock).
     * Metode ini juga perlu disesuaikan untuk menerima Request jika ingin pencarian
     * di halaman ini juga berfungsi. Namun, untuk menjaga fokus, kita biarkan dulu
     * tanpa pencarian di sini, hanya tampilkan produk stok rendah.
     */
    public function showLowStock(Request $request)
    {
        $lowStockProducts = Product::with('category')
            ->where('stock', '<=', 10)
            ->latest()
            ->paginate(10);
        $pageTitle = 'Produk Segera Habis';

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
        $categories = Category::all();
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
            'sku' => 'required|string|max:255|unique:products,sku,' . $product->id,
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
