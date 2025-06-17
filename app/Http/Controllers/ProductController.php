<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Supplier; // Pastikan ini di-import
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log; // Pastikan ini di-import

class ProductController extends Controller
{
    /**
     * Menampilkan SEMUA produk dengan pagination, pencarian, dan eager loading supplier.
     */
    public function index(Request $request)
    {
        $query = Product::with(['category', 'supplier'])->latest(); 

        if ($request->has('search') && $request->search != '') {
            $search = strtolower($request->search);
            $query->where(function($q) use ($search) {
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
     * Menampilkan produk yang stoknya hampir habis.
     */
    public function showLowStock(Request $request)
    {
        $lowStockProducts = Product::with(['category', 'supplier'])
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
     * PASTIKAN METODE INI ADA DAN LENGKAP
     */
    public function create()
    {
        $categories = Category::all();
        $suppliers = Supplier::all(); // Ambil semua supplier
        $pageTitle = 'Tambah Produk Baru';

        return view('products.create', [
            'categories' => $categories,
            'suppliers' => $suppliers, // Kirim daftar supplier ke view
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
            'supplier_id' => 'nullable|exists:suppliers,id', // Validasi supplier_id
        ]);

        Product::create([
            'name' => $request->name,
            'sku' => $request->sku,
            'price' => $request->price,
            'stock' => $request->stock,
            'category_id' => $request->category_id,
            'supplier_id' => $request->supplier_id, // Simpan supplier_id
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
        $suppliers = Supplier::all(); // Ambil semua supplier
        $pageTitle = 'Edit Produk: ' . $product->name;
        return view('products.edit', [
            'product' => $product,
            'categories' => $categories,
            'suppliers' => $suppliers, // Kirim daftar supplier ke view
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
            'supplier_id' => 'nullable|exists:suppliers,id',
        ]);

        $product->update([
            'name' => $request->name,
            'sku' => $request->sku,
            'price' => $request->price,
            'stock' => $request->stock,
            'category_id' => $request->category_id,
            'supplier_id' => $request->supplier_id,
        ]);

        return redirect()->route('products.index')->with('success', 'Produk berhasil diperbarui!');
    }

    /**
     * Menghapus produk dari database (products.destroy).
     */
    public function destroy(Product $product)
    {
        try {
            if ($product->transactionDetails()->count() > 0) {
                return redirect()->back()->withErrors(['error' => 'Produk tidak bisa dihapus karena sudah ada di riwayat transaksi.']);
            }
            $product->delete();
            return redirect()->route('products.index')->with('success', 'Produk berhasil dihapus!');
        } catch (\Exception | \Illuminate\Database\QueryException $e) {
            Log::error('Gagal menghapus produk: ' . $e->getMessage());
            if (str_contains($e->getMessage(), 'SQLSTATE[23503]')) {
                 return redirect()->back()->withErrors(['error' => 'Produk tidak bisa dihapus karena masih ada data terkait (misal: di detail transaksi).']);
            }
            return redirect()->back()->withErrors(['error' => 'Gagal menghapus produk. Silakan coba lagi.']);
        }
    }
}