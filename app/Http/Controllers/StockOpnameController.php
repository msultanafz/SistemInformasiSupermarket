<?php

namespace App\Http\Controllers;

use App\Models\Product; // Import model Product
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log; // Pastikan ini di-import jika dibutuhkan untuk logging

class StockOpnameController extends Controller
{
    /**
     * Menampilkan halaman Stok Opname dengan daftar produk.
     */
    public function index()
    {
        $pageTitle = 'Stok Opname';
        // Ambil semua produk, diurutkan berdasarkan nama atau SKU untuk konsistensi
        $products = Product::orderBy('name', 'asc')->paginate(20); // Paginate jika ada banyak produk

        return view('stock_opname.index', [
            'pageTitle' => $pageTitle,
            'products' => $products,
        ]);
    }

    /**
     * Menyimpan hasil penyesuaian stok dari Stok Opname.
     */
    public function store(Request $request)
    {
        $request->validate([
            'products' => 'required|array',
            'products.*.id' => 'required|integer|exists:products,id',
            'products.*.actual_stock' => 'required|integer|min:0',
        ]);

        DB::beginTransaction();
        try {
            // ... (logika update stok) ...
            DB::commit();
            return redirect()->route('stock-opname.index')->with('success', 'Stok berhasil diperbarui!'); // Ini harusnya
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Stock opname failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal memperbarui stok. Silakan coba lagi.')->withInput(); // Ini harusnya
        }
    }
}
