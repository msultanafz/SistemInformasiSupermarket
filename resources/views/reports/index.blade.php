{{-- File: resources/views/reports/index.blade.php --}}

@extends('layouts.app')

@section('title', $pageTitle ?? 'Daftar Laporan')

@section('content')
<div class="flex justify-end mb-6">
    <a href="{{ route('dashboard') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded-lg flex items-center">
        <i class="fas fa-arrow-left mr-2"></i> Kembali ke Dashboard
    </a>
</div>

<div class="bg-white rounded-lg shadow p-6">
    <h2 class="text-xl font-bold text-gray-800 mb-4">Pilih Jenis Laporan</h2>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        {{-- Kartu Laporan Harian --}}
        <a href="{{ route('reports.daily') }}" class="flex flex-col items-center justify-center p-6 border border-gray-200 rounded-lg hover:bg-blue-50 hover:border-blue-200 transition">
            <div class="p-3 rounded-full bg-blue-100 text-blue-600 mb-3">
                <i class="fas fa-calendar-day text-2xl"></i>
            </div>
            <span class="text-lg font-medium text-gray-700 text-center">Laporan Harian</span>
            <p class="text-sm text-gray-500 text-center">Ringkasan transaksi hari ini.</p>
        </a>

        {{-- Kartu Laporan Bulanan --}}
        <a href="{{ route('reports.monthly') }}" class="flex flex-col items-center justify-center p-6 border border-gray-200 rounded-lg hover:bg-blue-50 hover:border-blue-200 transition">
            <div class="p-3 rounded-full bg-blue-100 text-blue-600 mb-3">
                <i class="fas fa-calendar-alt text-2xl"></i>
            </div>
            <span class="text-lg font-medium text-gray-700 text-center">Laporan Bulanan</span>
            <p class="text-sm text-gray-500 text-center">Ringkasan transaksi per bulan.</p>
        </a>

        {{-- Kartu Laporan Tahunan (Baru) --}}
        <a href="{{ route('reports.yearly') }}" class="flex flex-col items-center justify-center p-6 border border-gray-200 rounded-lg hover:bg-blue-50 hover:border-blue-200 transition">
            <div class="p-3 rounded-full bg-blue-100 text-blue-600 mb-3">
                <i class="fas fa-calendar-check text-2xl"></i>
            </div>
            <span class="text-lg font-medium text-gray-700 text-center">Laporan Tahunan</span>
            <p class="text-sm text-gray-500 text-center">Ringkasan transaksi per tahun.</p>
        </a>
    </div>
</div>
@endsection