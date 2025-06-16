@extends('layouts.app')

@section('title', $pageTitle ?? 'Dasbor')

@section('content')

{{-- Bagian Kartu Informasi Atas --}}
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
    {{-- Card Pendapatan Hari Ini --}}
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-blue-100 text-blue-600"><i class="fas fa-wallet text-xl"></i></div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-500">Pendapatan Hari Ini</p>
                <p class="text-2xl font-semibold text-gray-800">Rp {{ number_format($pendapatanHariIni, 0, ',', '.') }}</p>
            </div>
        </div>
    </div>
    {{-- Card Jumlah Transaksi Hari Ini --}}
    <a href="{{ route('transactions.today') }}" class="block hover:bg-gray-50 transition rounded-lg shadow">
        <div class="bg-white rounded-lg p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 text-green-600"><i class="fas fa-receipt text-xl"></i></div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Jumlah Transaksi Hari Ini</p>
                    <p class="text-2xl font-semibold text-gray-800">{{ $jumlahTransaksiHariIni }}</p>
                </div>
            </div>
        </div>
    </a>
    {{-- Card Total Jenis Produk --}}
    <a href="{{ route('products.index') }}" class="block hover:bg-gray-50 transition rounded-lg shadow">
        <div class="bg-white rounded-lg p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-yellow-100 text-yellow-600"><i class="fas fa-box-open text-xl"></i></div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Total Jenis Produk</p>
                    <p class="text-2xl font-semibold text-gray-800">{{ $totalJenisProduk }}</p>
                </div>
            </div>
        </div>
    </a>
    {{-- Card Produk Segera Habis --}}
    <a href="{{ route('products.low-stock') }}" class="block hover:bg-gray-50 transition rounded-lg shadow">
        <div class="bg-white rounded-lg p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-red-100 text-red-600"><i class="fas fa-exclamation-triangle text-xl"></i></div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Produk Habis / Segera Habis</p>
                    <p class="text-2xl font-semibold text-gray-800">{{ $stokHampirHabis }}</p>
                </div>
            </div>
        </div>
    </a>
</div>

{{-- Bagian Grid Utama --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">

    {{-- Kolom Kiri - Transaksi Terakhir --}}
    <div class="lg:col-span-2 bg-white rounded-lg shadow overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-800">Transaksi Terakhir</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID Transaksi</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kasir</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Belanja</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Waktu</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($transaksiTerakhir as $transaksi)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $transaksi->transaction_code }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $transaksi->user->name ?? 'N/A' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Rp {{ number_format($transaksi->total_amount, 0, ',', '.') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $transaksi->created_at->format('H:i') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center px-6 py-4 text-sm text-gray-500">Belum ada transaksi hari ini.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- KOLOM KANAN (1/3) - Panel Status (DENGAN TEKNIK CSS VARIABLES) -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-800">Status Stok Inventaris</h2>
        </div>
        <div class="p-6">
            <!-- Stok Aman -->
            <div class="mb-4" style="--width: {{ round($safeStockPercentage) }}%">
                <div class="flex justify-between mb-1">
                    <span class="text-sm font-medium text-gray-700">Stok Aman</span>
                    <span class="text-sm font-medium text-gray-700">{{ round($safeStockPercentage) }}%</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2.5">
                    <div class="bg-green-600 h-2.5 rounded-full w-[var(--width)]"></div>
                </div>
            </div>
            <!-- Segera Habis -->
            <div class="mb-4" style="--width: {{ round($lowStockPercentage) }}%">
                <div class="flex justify-between mb-1">
                    <span class="text-sm font-medium text-gray-700">Segera Habis</span>
                    <span class="text-sm font-medium text-gray-700">{{ round($lowStockPercentage) }}%</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2.5">
                    <div class="bg-yellow-500 h-2.5 rounded-full w-[var(--width)]"></div>
                </div>
            </div>
            <!-- Stok Habis -->
            <div class="mb-4" style="--width: {{ round($outOfStockPercentage) }}%">
                <div class="flex justify-between mb-1">
                    <span class="text-sm font-medium text-gray-700">Stok Habis</span>
                    <span class="text-sm font-medium text-gray-700">{{ round($outOfStockPercentage) }}%</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2.5">
                    <div class="bg-red-600 h-2.5 rounded-full w-[var(--width)]"></div>
                </div>
            </div>

            <!-- Bagian Kategori Terlaris (masih statis) -->
            <div class="mt-6">
                <h3 class="text-md font-medium text-gray-800 mb-3">Kategori Produk Terlaris</h3>
                <div class="space-y-3">
                    @forelse ($topSellingCategories as $category)
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">{{ $category->category_name }}</span>
                        <span class="text-sm font-medium">{{ $category->total_quantity_sold }} Unit</span>
                    </div>
                    @empty
                    <div class="text-sm text-gray-500 text-center">Belum ada data penjualan kategori yang tercatat.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

</div>

{{-- Bagian Akses Cepat --}}
<div class="bg-white rounded-lg shadow p-6">
    <h2 class="text-lg font-semibold text-gray-800 mb-4">Akses Cepat</h2>
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        {{-- Tombol Mulai Transaksi --}}
        <a href="#" class="flex flex-col items-center justify-center p-4 border border-gray-200 rounded-lg hover:bg-blue-50 hover:border-blue-200 transition">
            <div class="p-3 rounded-full bg-blue-100 text-blue-600 mb-2"><i class="fas fa-cash-register text-lg"></i></div>
            <span class="text-sm font-medium text-gray-700 text-center">Mulai Transaksi (POS)</span>
        </a>
        {{-- Tombol Tambah Produk --}}
        <a href="#" class="flex flex-col items-center justify-center p-4 border border-gray-200 rounded-lg hover:bg-green-50 hover:border-green-200 transition">
            <div class="p-3 rounded-full bg-green-100 text-green-600 mb-2"><i class="fas fa-plus-circle text-lg"></i></div>
            <span class="text-sm font-medium text-gray-700 text-center">Tambah Produk Baru</span>
        </a>
        {{-- Tombol Laporan Harian --}}
        <a href="#" class="flex flex-col items-center justify-center p-4 border border-gray-200 rounded-lg hover:bg-yellow-50 hover:border-yellow-200 transition">
            <div class="p-3 rounded-full bg-yellow-100 text-yellow-600 mb-2"><i class="fas fa-file-alt text-lg"></i></div>
            <span class="text-sm font-medium text-gray-700 text-center">Laporan Harian</span>
        </a>
        {{-- Tombol Stok Opname --}}
        <a href="#" class="flex flex-col items-center justify-center p-4 border border-gray-200 rounded-lg hover:bg-purple-50 hover:border-purple-200 transition">
            <div class="p-3 rounded-full bg-purple-100 text-purple-600 mb-2"><i class="fas fa-clipboard-check text-lg"></i></div>
            <span class="text-sm font-medium text-gray-700 text-center">Stok Opname</span>
        </a>
    </div>
</div>

@endsection