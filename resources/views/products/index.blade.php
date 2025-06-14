{{-- File: resources/views/products/index.blade.php --}}
@extends('layouts.app')

@section('title', $pageTitle)

@section('content')

<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold text-gray-800">{{ $pageTitle }}</h1>
    <a href="{{ route('dashboard') }}" class="text-blue-600 hover:text-blue-400 duration-300 flex items-center">
        <i class="fas fa-arrow-left mr-2"></i>
        Kembali ke Dasbor
    </a>
</div>

<div class="bg-white rounded-lg shadow overflow-hidden">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">SKU</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Produk</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stok</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Harga</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @forelse ($products as $product)
            <tr>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $product->sku }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">{{ $product->name }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-red-600">{{ $product->stock }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">Rp {{ number_format($product->price, 0, ',', '.') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="4" class="text-center px-6 py-4 text-sm text-gray-500">Tidak ada produk yang stoknya sedikit saat ini.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    {{-- Hanya tampilkan link halaman jika datanya adalah objek Paginator --}}
    @if ($products instanceof \Illuminate\Pagination\LengthAwarePaginator)
    <div class="p-6">
        {{ $products->links() }}
    </div>
    @endif
</div>
@endsection