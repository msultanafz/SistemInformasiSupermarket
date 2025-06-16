@extends('layouts.app')

@section('title', $pageTitle ?? 'Daftar Produk')

@section('content')
<div class="flex justify-between items-center mb-6">
    <a href="{{ route('products.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg flex items-center">
        <i class="fas fa-plus mr-2"></i> Tambah Produk Baru
    </a>
    <a href="{{ route('dashboard') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded-lg flex items-center">
        <i class="fas fa-arrow-left mr-2"></i> Kembali ke Dashboard
    </a>
</div>

<div class="bg-white rounded-lg shadow overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
        <h2 class="text-lg font-semibold text-gray-800">Semua Produk</h2>
        <form id="search-form" action="{{ route('products.index') }}" method="GET" class="relative flex items-center">
            <input type="text" name="search" id="search-input"
                placeholder="Cari produk..."
                class="pl-3 pr-10 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                value="{{ request('search') }}"
                onkeyup="liveSearch()">
            <button type="submit" class="absolute right-0 top-0 h-full px-3 text-gray-700 hover:bg-gray-100 rounded-r-lg">
                <i class="fas fa-search"></i>
            </button>
        </form>
    </div>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">SKU</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Produk</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kategori</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Harga</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stok</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse ($products as $product)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $product->sku }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800">{{ $product->name }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $product->category->name ?? 'N/A' }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $product->stock }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium flex justify-center space-x-2">
                        <a href="{{ route('products.edit', $product->id) }}" class="text-indigo-600 hover:text-indigo-900" title="Edit"><i class="fas fa-edit"></i></a>
                        <form id="delete-form-{{ $product->id }}" action="{{ route('products.destroy', $product->id) }}" method="POST" style="display: none;">
                            @csrf
                            @method('DELETE')
                        </form>
                        <button type="button"
                            data-id="{{ $product->id }}"
                            onclick="confirmDelete(this)"
                            class="text-red-600 hover:text-red-900" title="Hapus">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">Tidak ada produk ditemukan.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="p-6">
        {{ $products->appends(request()->query())->links() }}
    </div>
</div>

@push('scripts')
<script>
    let searchTimeout;

    function liveSearch() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            document.getElementById('search-form').submit();
        }, 300);
    }

    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('search-input');
        const urlParams = new URLSearchParams(window.location.search); // Menggunakan URLSearchParams
        const searchTermFromUrl = urlParams.get('search'); // Mendapatkan nilai parameter 'search'

        // Fokuskan input jika parameter 'search' ada di URL (bahkan jika kosong)
        if (searchTermFromUrl !== null) {
            searchInput.focus();
            // Memposisikan kursor di akhir teks yang ada
            searchInput.setSelectionRange(searchInput.value.length, searchInput.value.length);
        }
    });
</script>
@endpush
@endsection