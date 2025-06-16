{{-- File: resources/views/transactions/show.blade.php --}}

@extends('layouts.app')

@section('title', $pageTitle ?? 'Detail Transaksi')

@section('content')
<div class="flex justify-end mb-6">
    {{-- Perbaikan: Gunakan previousUrl untuk tombol kembali --}}
    <a href="{{ $previousUrl }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded-lg flex items-center">
        <i class="fas fa-arrow-left mr-2"></i> Kembali ke Daftar Transaksi
    </a>
</div>

<div class="bg-white rounded-lg shadow p-6 mb-6">
    <h2 class="text-xl font-bold text-gray-800 mb-4">Informasi Transaksi</h2>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <p class="text-sm font-medium text-gray-600">Kode Transaksi:</p>
            <p class="text-lg font-semibold text-gray-800">{{ $transaction->transaction_code }}</p>
        </div>
        <div>
            <p class="text-sm font-medium text-gray-600">Tanggal & Waktu:</p>
            <p class="text-lg font-semibold text-gray-800">{{ $transaction->created_at->format('d M Y, H:i:s') }}</p>
        </div>
        <div>
            <p class="text-sm font-medium text-gray-600">Kasir:</p>
            <p class="text-lg font-semibold text-gray-800">{{ $transaction->user->name ?? 'N/A' }}</p>
        </div>
        <div>
            <p class="text-sm font-medium text-gray-600">Total Belanja:</p>
            <p class="text-2xl font-bold text-blue-600">Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}</p>
        </div>
    </div>
</div>

<div class="bg-white rounded-lg shadow p-6">
    <h2 class="text-xl font-bold text-gray-800 mb-4">Detail Produk</h2>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Produk</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">SKU</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Harga Satuan</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kuantitas</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subtotal</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse ($transaction->details as $detail)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $detail->product->name ?? 'Produk Dihapus' }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $detail->product->sku ?? 'N/A' }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Rp {{ number_format($detail->price_at_transaction, 0, ',', '.') }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $detail->quantity }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">Rp {{ number_format($detail->price_at_transaction * $detail->quantity, 0, ',', '.') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">Tidak ada detail produk untuk transaksi ini.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection