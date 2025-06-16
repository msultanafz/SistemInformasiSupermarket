{{-- File: resources/views/reports/yearly.blade.php --}}

@extends('layouts.app')

@section('title', $pageTitle ?? 'Laporan Tahunan')

@section('content')
<div class="flex justify-end mb-6">
    <a href="{{ $previousUrl }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded-lg flex items-center">
        <i class="fas fa-arrow-left mr-2"></i> Kembali ke Daftar Laporan
    </a>
</div>

<div class="bg-white rounded-lg shadow p-6 mb-6">
    <h2 class="text-xl font-bold text-gray-800 mb-4">Pilih Tahun</h2>
    <form action="{{ route('reports.yearly') }}" method="GET" class="flex flex-wrap items-center gap-4">
        <div>
            <label for="year" class="sr-only">Tahun:</label>
            <select name="year" id="year" class="border rounded-lg py-2 px-3 text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                @for($y = \Carbon\Carbon::now()->year; $y >= \Carbon\Carbon::now()->year - 5; $y--)
                <option value="{{ $y }}" {{ $y == $year ? 'selected' : '' }}>{{ $y }}</option>
                @endfor
            </select>
        </div>
        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg">
            Tampilkan Laporan
        </button>
    </form>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
    {{-- Total Pendapatan Tahun Ini --}}
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                <i class="fas fa-wallet text-xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-500">Pendapatan Tahun Ini</p>
                <p class="text-2xl font-semibold text-gray-800">Rp {{ number_format($totalRevenueYearly, 0, ',', '.') }}</p>
            </div>
        </div>
    </div>

    {{-- Jumlah Transaksi Tahun Ini --}}
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-green-100 text-green-600">
                <i class="fas fa-receipt text-xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-500">Jumlah Transaksi Tahun Ini</p>
                <p class="text-2xl font-semibold text-gray-800">{{ $totalTransactionsYearly }}</p>
            </div>
        </div>
    </div>

    {{-- Tahun Laporan --}}
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-gray-100 text-gray-600">
                <i class="fas fa-calendar-check text-xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-500">Laporan Untuk Tahun</p>
                <p class="text-2xl font-semibold text-gray-800">{{ $year }}</p>
            </div>
        </div>
    </div>
</div>

<div class="bg-white rounded-lg shadow overflow-hidden mb-6">
    <div class="px-6 py-4 border-b border-gray-200">
        <h2 class="text-lg font-semibold text-gray-800">Produk Terlaris Tahun Ini</h2>
    </div>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Produk</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">SKU</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Unit Terjual</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($topSellingProductsYearly as $product)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $product->product_name }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $product->product_sku }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $product->total_quantity_sold }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" class="text-center px-6 py-4 text-sm text-gray-500">Belum ada produk terjual tahun ini.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="bg-white rounded-lg shadow overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200">
        <h2 class="text-lg font-semibold text-gray-800">Kasir Paling Aktif Tahun Ini</h2>
    </div>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Kasir</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Transaksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($mostActiveCashiersYearly as $cashier)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $cashier->cashier_name }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $cashier->total_transactions }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="2" class="text-center px-6 py-4 text-sm text-gray-500">Belum ada aktivitas kasir tahun ini.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection