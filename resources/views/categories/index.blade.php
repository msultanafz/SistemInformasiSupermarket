{{-- File: resources/views/categories/index.blade.php --}}

@extends('layouts.app')

@section('title', $pageTitle ?? 'Daftar Kategori')

@section('content')
<div class="flex justify-between items-center mb-6">
    <a href="{{ route('categories.create') }}"
        class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg flex items-center">
        <i class="fas fa-plus mr-2"></i> Tambah Kategori Baru
    </a>
    <a href="{{ route('dashboard') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded-lg flex items-center">
        <i class="fas fa-arrow-left mr-2"></i> Kembali ke Dashboard
    </a>
</div>

<div class="bg-white rounded-lg px-6 shadow overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200">
        <h2 class="text-lg font-semibold text-gray-800">Semua Kategori</h2>
    </div>
    <div class="overflow-x-auto">
        <table class="min-w-full table-fixed divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap w-1/3 align-middle">
                        Nama Kategori
                    </th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap w-1/3 align-middle">
                        Jumlah Produk
                    </th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap w-1/3 align-middle">
                        Aksi
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse ($categories as $category)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 align-middle">
                        {{ $category->name }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center align-middle">
                        {{ $category->products->count() }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-right align-middle">
                        <div class="inline-flex items-center justify-center space-x-4">
                            <a href="{{ route('categories.edit', $category->id) }}"
                                class="text-indigo-600 hover:text-indigo-900" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form id="delete-form-{{ $category->id }}"
                                action="{{ route('categories.destroy', $category->id) }}"
                                method="POST" style="display: none;">
                                @csrf
                                @method('DELETE')
                            </form>
                            <button type="button"
                                onclick="confirmDeleteCategory({{ $category->id }})"
                                class="text-red-600 hover:text-red-900" title="Hapus">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" class="px-6 py-4 text-center text-sm text-gray-500">
                        Tidak ada kategori ditemukan.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="p-6">
        {{ $categories->links() }}
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Fungsi untuk konfirmasi hapus kategori (menggunakan SweetAlert2)
    window.confirmDeleteCategory = function(categoryId) {
        Swal.fire({
            title: 'Anda yakin?',
            text: "Kategori ini akan dihapus! Jika ada produk yang terkait, penghapusan akan gagal.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-form-' + categoryId).submit();
            }
        });
    };
</script>
@endpush