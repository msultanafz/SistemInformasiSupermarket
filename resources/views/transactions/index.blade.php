{{-- File: resources/views/transactions/index.blade.php --}}

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
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID Transaksi</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kasir</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Belanja</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Waktu</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @forelse ($transactions as $transaction)
            <tr>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $transaction->transaction_code }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $transaction->user->name ?? 'N/A' }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $transaction->created_at->format('H:i:s') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="4" class="text-center px-6 py-4 text-sm text-gray-500">Tidak ada transaksi yang cocok.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    {{-- Link Pagination --}}
    @if ($transactions instanceof \Illuminate\Pagination\LengthAwarePaginator)
    <div class="p-6">
        {{ $transactions->links() }}
    </div>
    @endif
</div>
@endsection