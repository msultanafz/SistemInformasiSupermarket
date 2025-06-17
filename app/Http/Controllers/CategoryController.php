<?php

namespace App\Http\Controllers;

use App\Models\Category; // Import model Category
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log; // Untuk logging error

class CategoryController extends Controller
{
    /**
     * Menampilkan daftar semua kategori dengan pagination.
     */
    public function index()
    {
        $pageTitle = 'Daftar Kategori';
        // Ambil semua kategori, diurutkan berdasarkan nama dan paginate
        $categories = Category::orderBy('name', 'asc')->paginate(10);

        return view('categories.index', [
            'pageTitle' => $pageTitle,
            'categories' => $categories,
        ]);
    }

    /**
     * Menampilkan form untuk membuat kategori baru.
     */
    public function create()
    {
        $pageTitle = 'Tambah Kategori Baru';
        return view('categories.create', compact('pageTitle'));
    }

    /**
     * Menyimpan kategori baru ke database.
     */
    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
        ]);

        try {
            Category::create([
                'name' => $request->name,
            ]);
            return redirect()->route('categories.index')->with('success', 'Kategori berhasil ditambahkan!');
        } catch (\Exception $e) {
            Log::error('Gagal menambahkan kategori: ' . $e->getMessage());
            return redirect()->back()->withInput()->withErrors(['error' => 'Gagal menambahkan kategori. Silakan coba lagi.']);
        }
    }

    /**
     * Menampilkan form untuk mengedit kategori.
     * Route Model Binding akan otomatis menemukan kategori berdasarkan ID.
     */
    public function edit(Category $category)
    {
        $pageTitle = 'Edit Kategori: ' . $category->name;
        return view('categories.edit', compact('pageTitle', 'category'));
    }

    /**
     * Memperbarui kategori di database.
     */
    public function update(Request $request, Category $category)
    {
        // Validasi input
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id, // Abaikan nama kategori saat ini
        ]);

        try {
            $category->update([
                'name' => $request->name,
            ]);
            return redirect()->route('categories.index')->with('success', 'Kategori berhasil diperbarui!');
        } catch (\Exception | \Illuminate\Database\QueryException $e) {
            Log::error('Gagal memperbarui kategori: ' . $e->getMessage());
            return redirect()->back()->withInput()->withErrors(['error' => 'Gagal memperbarui kategori. Silakan coba lagi.']);
        }
    }

    /**
     * Menghapus kategori dari database.
     */
    public function destroy(Category $category)
    {
        try {
            // Periksa apakah ada produk yang terkait dengan kategori ini
            if ($category->products()->count() > 0) {
                return redirect()->back()->withErrors(['error' => 'Kategori tidak bisa dihapus karena masih ada produk yang terkait.']);
            }
            $category->delete();
            return redirect()->route('categories.index')->with('success', 'Kategori berhasil dihapus!');
        } catch (\Exception | \Illuminate\Database\QueryException $e) {
            Log::error('Gagal menghapus kategori: ' . $e->getMessage());
            return redirect()->back()->withErrors(['error' => 'Gagal menghapus kategori. Silakan coba lagi.']);
        }
    }
}
