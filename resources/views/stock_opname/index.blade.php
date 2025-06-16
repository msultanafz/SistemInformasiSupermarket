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
                            @error('products.' . $loop->index . '.actual_stock')
                            <span class="absolute right-0 top-0 mt-[-18px] text-red-500 text-xs italic bg-white px-1" title="{{ $message }}">!</span>
                            @enderror
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
@push('scripts')
<script>
    document.getElementById('stock-opname-form').addEventListener('submit', async function(event) {
        event.preventDefault();

        const form = event.target;
        const items = [];

        form.querySelectorAll('tbody tr').forEach(row => {
            const productIdInput = row.querySelector('input[name$="[id]"]');
            const actualStockInput = row.querySelector('input[name$="[actual_stock]"]');

            if (productIdInput && actualStockInput) {
                items.push({
                    id: parseInt(productIdInput.value),
                    actual_stock: parseInt(actualStockInput.value)
                });
            }
        });

        try {
            const response = await fetch(form.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    products: items
                })
            });

            const data = await response.json();

            if (response.ok) {
                await Swal.fire('Berhasil!', data.message, 'success');
                window.location.reload();
            } else {
                let errorMessage = data.message || 'Terjadi kesalahan saat menyimpan stok.';
                if (data.errors) {
                    errorMessage += '\n';
                    for (const key in data.errors) {
                        errorMessage += data.errors[key].join('\n') + '\n';
                    }
                }
                Swal.fire('Gagal!', errorMessage, 'error');
            }

        } catch (error) {
            console.error('Error:', error);
            Swal.fire('Error Jaringan!', 'Tidak dapat terhubung ke server.', 'error');
        }
    });
</script>
@endpush