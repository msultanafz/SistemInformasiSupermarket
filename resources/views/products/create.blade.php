@extends('layouts.app')

@section('title', $pageTitle ?? 'Tambah Produk Baru')

@section('content')
<div class="flex justify-end mb-6 pr-3">
    <a href="{{ route('products.index') }}" class="text-blue-600 hover:text-blue-400 duration-300 flex items-center">
        <i class="fas fa-arrow-left mr-2"></i>
        Kembali ke Daftar Produk
    </a>
</div>

<div class="bg-white rounded-lg shadow p-6">
    {{-- Form akan mengirim data ke route products.store --}}
    <form action="{{ route('products.store') }}" method="POST">
        @csrf {{-- Token CSRF untuk keamanan --}}

        <div class="mb-4">
            <label for="name" class="block text-gray-700 text-sm font-bold mb-2">Nama Produk:</label>
            <input type="text" name="name" id="name" class="shadow appearance-none border rounded w-full py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('name') border-red-500 @enderror" value="{{ old('name') }}" required>
            @error('name')
            <p class="text-red-500 text-xs italic">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="sku" class="block text-gray-700 text-sm font-bold mb-2">SKU:</label>
            <input type="text" name="sku" id="sku" class="shadow appearance-none border rounded w-full py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('sku') border-red-500 @enderror" value="{{ old('sku') }}" required>
            @error('sku')
            <p class="text-red-500 text-xs italic">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="price" class="block text-gray-700 text-sm font-bold mb-2">Harga (Rp):</label>
            <input type="number" name="price" id="price" class="shadow appearance-none border rounded w-full py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('price') border-red-500 @enderror" value="{{ old('price') }}" required min="0">
            @error('price')
            <p class="text-red-500 text-xs italic">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="stock" class="block text-gray-700 text-sm font-bold mb-2">Stok:</label>
            <input type="number" name="stock" id="stock" class="shadow appearance-none border rounded w-full py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('stock') border-red-500 @enderror" value="{{ old('stock') }}" required min="0">
            @error('stock')
            <p class="text-red-500 text-xs italic">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-6">
            <label for="category_id" class="block text-gray-700 text-sm font-bold mb-2">Kategori:</label>
            <select name="category_id" id="category_id" class="shadow border rounded w-full py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('category_id') border-red-500 @enderror" required>
                <option value="">Pilih Kategori</option>
                @foreach($categories as $category)
                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                    {{ $category->name }}
                </option>
                @endforeach
            </select>
            @error('category_id')
            <p class="text-red-500 text-xs italic">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex items-center justify-between">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg focus:outline-none focus:shadow-outline">
                Simpan Produk
            </button>
        </div>
    </form>
</div>
@endsection