@extends('layouts.app')

@section('title', $pageTitle ?? 'Mulai Transaksi (POS)')

@section('content')
<div class="flex justify-end mb-6">
    <a href="{{ route('dashboard') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded-lg flex items-center">
        <i class="fas fa-arrow-left mr-2"></i> Kembali ke Dashboard
    </a>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    {{-- Kolom Kiri: Pencarian Produk & Daftar Produk --}}
    <div class="md:col-span-2 bg-white rounded-lg shadow p-6">
        <h2 class="text-xl font-bold text-gray-800 mb-4">Cari Produk</h2>
        <div class="relative mb-6">
            <input type="text" id="product-search" placeholder="Cari berdasarkan nama atau SKU..."
                class="pl-3 pr-10 py-2 border rounded-lg w-full focus:outline-none focus:ring-2 focus:ring-blue-500">
            <i class="fas fa-search absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
        </div>

        <h3 class="text-lg font-semibold text-gray-700 mb-3">Daftar Produk Tersedia</h3>
        <div class="overflow-y-auto max-h-96 border rounded-lg">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50 sticky top-0">
                    <tr>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Produk</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">SKU</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stok</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Harga</th>
                        <th class="px-4 py-2 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody id="product-list" class="bg-white divide-y divide-gray-200">
                    @forelse($products as $product)
                    <tr class="product-item"
                        data-id="{{ $product->id }}"
                        data-name="{{ $product->name }}"
                        data-sku="{{ $product->sku }}"
                        data-price="{{ $product->price }}"
                        data-stock="{{ $product->stock }}"
                        data-category="{{ $product->category->name ?? 'N/A' }}">
                        <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-900">{{ $product->name }}</td>
                        <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-500">{{ $product->sku }}</td>
                        <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-500">{{ $product->stock }}</td>
                        <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-700">Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                        <td class="px-4 py-2 whitespace-nowrap text-center text-sm font-medium">
                            <button type="button" onclick="addToCart(this)" class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded-md text-xs font-semibold">
                                Tambah
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-4 py-2 text-center text-sm text-gray-500">Tidak ada produk tersedia.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Kolom Kanan: Keranjang Belanja & Ringkasan Transaksi --}}
    <div class="md:col-span-1 bg-white rounded-lg shadow p-6">
        <h2 class="text-xl font-bold text-gray-800 mb-4">Keranjang Belanja</h2>
        <div id="cart-items" class="mb-4 overflow-y-auto max-h-60 border-b pb-4">
            {{-- empty-cart-message akan dikelola renderCart --}}
        </div>

        <div class="text-right text-gray-800 font-bold text-2xl mb-4">
            Total: <span id="cart-total">Rp 0</span>
        </div>

        <button id="complete-transaction-btn" class="bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-6 rounded-lg w-full text-lg">
            Selesaikan Transaksi
        </button>
    </div>
</div>

@push('scripts')
<script>
    let cart = {};
    let productSearchTimeout;

    function renderCart() {
        const cartItemsContainer = document.getElementById('cart-items');
        const cartTotalElement = document.getElementById('cart-total');
        let total = 0;
        let cartHtml = '';

        if (Object.keys(cart).length === 0) {
            // Jika keranjang kosong, tampilkan pesan
            cartHtml = '<div class="text-gray-500 text-sm text-center py-4" id="empty-cart-message">Keranjang kosong. Tambahkan produk!</div>';
        } else {
            for (const productId in cart) {
                const item = cart[productId];
                const subtotal = item.price * item.quantity;
                total += subtotal;

                cartHtml += `
                        <div class="flex items-center justify-between border-b py-2">
                            <div>
                                <p class="text-sm font-semibold">${item.name}</p>
                                <p class="text-xs text-gray-500">Rp ${item.price.toLocaleString('id-ID')}</p>
                            </div>
                            <div class="flex items-center space-x-2">
                                <button type="button" onclick="updateQuantity(${productId}, -1)" class="bg-gray-200 text-gray-700 rounded-md px-2 py-1 text-xs">-</button>
                                <span class="text-sm font-medium">${item.quantity}</span>
                                <button type="button" onclick="updateQuantity(${productId}, 1)" class="bg-gray-200 text-gray-700 rounded-md px-2 py-1 text-xs">+</button>
                                <button type="button" onclick="removeFromCart(${productId})" class="text-red-500 hover:text-red-700 ml-2 text-sm"><i class="fas fa-times"></i></button>
                            </div>
                        </div>
                    `;
            }
        }

        cartItemsContainer.innerHTML = cartHtml;
        cartTotalElement.textContent = `Rp ${total.toLocaleString('id-ID')}`;
        // Perbaikan: Hanya tampilkan pesan kosong jika cart benar-benar kosong
        const emptyCartMessageElement = document.getElementById('empty-cart-message');
        if (emptyCartMessageElement) { // Pastikan elemen ada
            emptyCartMessageElement.style.display = Object.keys(cart).length === 0 ? 'block' : 'none';
        }
    }

    function addToCart(button) {
        const productRow = button.closest('.product-item');
        const productId = parseInt(productRow.dataset.id);
        const productName = productRow.dataset.name;
        const productPrice = parseFloat(productRow.dataset.price);
        const productStock = parseInt(productRow.dataset.stock);

        if (cart[productId]) {
            if (cart[productId].quantity < productStock) {
                cart[productId].quantity++;
            } else {
                Swal.fire('Stok Habis!', 'Kuantitas tidak bisa melebihi stok tersedia.', 'warning');
            }
        } else {
            if (productStock > 0) {
                cart[productId] = {
                    id: productId,
                    name: productName,
                    price: productPrice,
                    stock: productStock,
                    quantity: 1
                };
            } else {
                Swal.fire('Stok Habis!', 'Produk ini tidak memiliki stok.', 'error');
            }
        }
        renderCart();
    }

    function updateQuantity(productId, change) {
        if (cart[productId]) {
            const newQuantity = cart[productId].quantity + change;
            if (newQuantity <= 0) {
                removeFromCart(productId);
            } else if (newQuantity <= cart[productId].stock) {
                cart[productId].quantity = newQuantity;
            } else {
                Swal.fire('Stok Habis!', 'Kuantitas tidak bisa melebihi stok tersedia.', 'warning');
            }
        }
        renderCart();
    }

    function removeFromCart(productId) {
        delete cart[productId];
        renderCart();
    }

    function filterProducts() {
        const searchTerm = document.getElementById('product-search').value.toLowerCase();
        const productItems = document.querySelectorAll('.product-item');

        productItems.forEach(item => {
            const name = item.dataset.name.toLowerCase();
            const sku = item.dataset.sku.toLowerCase();
            if (name.includes(searchTerm) || sku.includes(searchTerm)) {
                item.style.display = '';
            } else {
                item.style.display = 'none';
            }
        });
    }

    document.getElementById('product-search').addEventListener('keyup', () => {
        clearTimeout(productSearchTimeout);
        productSearchTimeout = setTimeout(filterProducts, 300);
    });

    document.addEventListener('DOMContentLoaded', renderCart);

    // Fungsi untuk menyelesaikan transaksi - BARIS INI DIUBAH UNTUK REFRESH HALAMAN
    document.getElementById('complete-transaction-btn').addEventListener('click', async () => {
        if (Object.keys(cart).length === 0) {
            Swal.fire('Keranjang Kosong!', 'Tambahkan produk ke keranjang sebelum menyelesaikan transaksi.', 'warning');
            return;
        }

        // Konfirmasi sebelum menyelesaikan transaksi
        const result = await Swal.fire({
            title: 'Konfirmasi Transaksi',
            text: "Apakah Anda yakin ingin menyelesaikan transaksi ini?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, Selesaikan!',
            cancelButtonText: 'Batal'
        });

        if (result.isConfirmed) {
            // Siapkan data keranjang untuk dikirim ke backend
            const itemsToSend = Object.values(cart).map(item => ({
                id: item.id,
                quantity: item.quantity,
            }));

            try {
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                // Menggunakan variabel global yang sudah didefinisikan di layouts/app.blade.php
                const storeRoute = window.APP_ROUTES.transactions_store;

                const response = await fetch(storeRoute, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({
                        items: itemsToSend
                    })
                });

                const data = await response.json();

                if (response.ok) { // Jika respons HTTP 200-299
                    await Swal.fire('Berhasil!', data.message, 'success'); // Gunakan await agar SweetAlert selesai sebelum reload
                    // Pilihan:
                    // 1. Refresh halaman saat ini untuk menampilkan stok terbaru
                    window.location.reload();
                    // 2. Redirect ke halaman daftar transaksi
                    // window.location.href = window.APP_ROUTES.transactions_index; 
                } else { // Jika respons HTTP error (4xx, 5xx)
                    let errorMessage = data.message || 'Terjadi kesalahan saat menyelesaikan transaksi.';
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
        }
    });
</script>
@endpush
@endsection