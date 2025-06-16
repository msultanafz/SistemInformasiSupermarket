{{-- File: resources/views/reports/daily.blade.php --}}

@extends('layouts.app')

@php use Illuminate\Support\Str; @endphp

@section('title', $pageTitle ?? 'Laporan Harian')

@section('content')
<div class="flex justify-end mb-6">
    <a href="{{ $previousUrl }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded-lg flex items-center">
        <i class="fas fa-arrow-left mr-2"></i>
        @if (Str::contains($previousUrl, route('dashboard')))
        Kembali ke Dashboard
        @elseif (Str::contains($previousUrl, route('reports.index')))
        Kembali ke Daftar Laporan
        @else
        Kembali
        @endif
    </a>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
    {{-- Total Pendapatan Hari Ini --}}
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                <i class="fas fa-wallet text-xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-500">Pendapatan Hari Ini</p>
                <p class="text-2xl font-semibold text-gray-800">Rp {{ number_format($totalRevenueToday, 0, ',', '.') }}</p>
            </div>
        </div>
    </div>

    {{-- Jumlah Transaksi Hari Ini --}}
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-green-100 text-green-600">
                <i class="fas fa-receipt text-xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-500">Jumlah Transaksi Hari Ini</p>
                <p class="text-2xl font-semibold text-gray-800">{{ $totalTransactionsToday }}</p>
            </div>
        </div>
    </div>

    {{-- Tanggal Laporan --}}
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-gray-100 text-gray-600">
                <i class="fas fa-calendar-day text-xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-500">Laporan Untuk Tanggal</p>
                <p class="text-2xl font-semibold text-gray-800">{{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</p>
            </div>
        </div>
    </div>
</div>

<div class="bg-white rounded-lg shadow overflow-hidden mb-6">
    <div class="px-6 py-4 border-b border-gray-200">
        <h2 class="text-lg font-semibold text-gray-800">Produk Terlaris Hari Ini</h2>
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
                @forelse($topSellingProductsToday as $product)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $product->product_name }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $product->product_sku }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $product->total_quantity_sold }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" class="text-center px-6 py-4 text-sm text-gray-500">Belum ada produk terjual hari ini.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="bg-white rounded-lg shadow overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200">
        <h2 class="text-lg font-semibold text-gray-800">Kasir Paling Aktif Hari Ini</h2>
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
                @forelse($mostActiveCashiersToday as $cashier)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $cashier->cashier_name }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $cashier->total_transactions }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="2" class="text-center px-6 py-4 text-sm text-gray-500">Belum ada aktivitas kasir hari ini.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection