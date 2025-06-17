@extends('layouts.app')

@section('title', $pageTitle ?? 'Stok Opname')

@section('content')
<div class="flex justify-end mb-6">
    <a href="{{ route('dashboard') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded-lg flex items-center">
        <i class="fas fa-arrow-left mr-2"></i> Kembali ke Dashboard
    </a>
</div>

<div class="bg-white rounded-lg shadow p-6">
    <form id="stock-opname-form" action="{{ route('stock-opname.store') }}" method="POST">
        @csrf
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-bold text-gray-800">Input Stok Aktual</h2>
            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-lg focus:outline-none focus:shadow-outline">
                Simpan Stok Opname
            </button>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap" style="width: 140px;">SKU</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap" style="width: 200px;">Nama Produk</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap" style="width: 140px;">Kategori</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap" style="width: 120px;">Stok Tercatat</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap" style="width: 120px;">Stok Aktual</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($products as $product)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900" style="width: 140px;">{{ $product->sku }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800" style="width: 200px;">{{ $product->name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500" style="width: 140px;">{{ $product->category->name ?? 'N/A' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm text-gray-500" style="width: 120px;">{{ $product->stock }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm" style="width: 120px; position: relative;">
                            <input type="hidden" name="products[{{ $loop->index }}][id]" value="{{ $product->id }}">
                            <input type="number"
                                name="products[{{ $loop->index }}][actual_stock]"
                                value="{{ old('products.' . $loop->index . '.actual_stock', $product->stock) }}"
                                class="w-full border rounded-md px-2 py-1 text-right focus:outline-none focus:ring-2 focus:ring-blue-500 @error('products.' . $loop->index . '.actual_stock') border-red-500 @enderror"
                                min="0" style="max-width: 100px;">
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">Tidak ada produk ditemukan.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-6">
            {{ $products->links() }}
        </div>
    </form>
</div>
@endsection
