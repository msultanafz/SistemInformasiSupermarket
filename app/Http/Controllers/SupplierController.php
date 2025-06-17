<?php

namespace App\Http\Controllers;

use App\Models\Supplier; // Impor model Supplier
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log; // Untuk logging error

class SupplierController extends Controller
{
    /**
     * Menampilkan daftar semua supplier dengan pagination.
     */
    public function index()
    {
        $pageTitle = 'Daftar Supplier';
        // Ambil semua supplier, diurutkan berdasarkan nama dan paginate
        $suppliers = Supplier::orderBy('name', 'asc')->paginate(10);

        return view('suppliers.index', [
            'pageTitle' => $pageTitle,
            'suppliers' => $suppliers,
        ]);
    }

    /**
     * Menampilkan form untuk membuat supplier baru.
     */
    public function create()
    {
        $pageTitle = 'Tambah Supplier Baru';
        return view('suppliers.create', compact('pageTitle'));
    }

    /**
     * Menyimpan supplier baru ke database.
     */
    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'name' => 'required|string|max:255|unique:suppliers,name',
            'contact_person' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255|unique:suppliers,email',
            'address' => 'nullable|string',
        ]);

        try {
            Supplier::create($request->all());
            return redirect()->route('suppliers.index')->with('success', 'Supplier berhasil ditambahkan!');
        } catch (\Exception $e) {
            Log::error('Gagal menambahkan supplier: ' . $e->getMessage());
            return redirect()->back()->withInput()->withErrors(['error' => 'Gagal menambahkan supplier. Silakan coba lagi.']);
        }
    }

    /**
     * Menampilkan form untuk mengedit supplier.
     * Route Model Binding akan otomatis menemukan supplier berdasarkan ID.
     */
    public function edit(Supplier $supplier)
    {
        $pageTitle = 'Edit Supplier';
        return view('suppliers.edit', compact('pageTitle', 'supplier'));
    }

    /**
     * Memperbarui supplier di database.
     */
    public function update(Request $request, Supplier $supplier)
    {
        // Validasi input
        $request->validate([
            'name' => 'required|string|max:255|unique:suppliers,name,' . $supplier->id, // Abaikan nama supplier saat ini
            'contact_person' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255|unique:suppliers,email,' . $supplier->id, // Abaikan email supplier saat ini
            'address' => 'nullable|string',
        ]);

        try {
            $supplier->update($request->all());
            return redirect()->route('suppliers.index')->with('success', 'Supplier berhasil diperbarui!');
        } catch (\Exception | \Illuminate\Database\QueryException $e) {
            Log::error('Gagal memperbarui supplier: ' . $e->getMessage());
            return redirect()->back()->withInput()->withErrors(['error' => 'Gagal memperbarui supplier. Silakan coba lagi.']);
        }
    }

    /**
     * Menghapus supplier dari database.
     */
    public function destroy(Supplier $supplier)
    {
        try {
            // TODO: Periksa apakah ada relasi lain (misal: pembelian) sebelum menghapus supplier
            // Untuk saat ini, kita langsung hapus. Jika ada foreign key, akan muncul error.
            $supplier->delete();
            return redirect()->route('suppliers.index')->with('success', 'Supplier berhasil dihapus!');
        } catch (\Exception | \Illuminate\Database\QueryException $e) {
            Log::error('Gagal menghapus supplier: ' . $e->getMessage());
            // Tangani error foreign key (misal jika ada pembelian terkait)
            if (str_contains($e->getMessage(), 'SQLSTATE[23503]')) { // Kode error foreign key violation
                 return redirect()->back()->withErrors(['error' => 'Supplier tidak bisa dihapus karena masih ada data terkait (misal: riwayat pembelian).']);
            }
            return redirect()->back()->withErrors(['error' => 'Gagal menghapus supplier. Silakan coba lagi.']);
        }
    }
}