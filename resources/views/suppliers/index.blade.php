{{-- File: resources/views/suppliers/index.blade.php --}}

@extends('layouts.app')

@section('title', $pageTitle ?? 'Daftar Supplier')

@section('content')
    <div class="flex justify-between items-center mb-6">
        <a href="{{ route('suppliers.create') }}"
           class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg flex items-center">
            <i class="fas fa-plus mr-2"></i> Tambah Supplier Baru
        </a>
        <a href="{{ route('dashboard') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded-lg flex items-center">
        <i class="fas fa-arrow-left mr-2"></i> Kembali ke Dashboard
    </a>
    </div>

    {{-- Notifikasi akan muncul via SweetAlert2 global --}}

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-800">Semua Supplier</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full table-fixed divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap w-1/5 align-middle">
                            Nama Supplier
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap w-1/5 align-middle">
                            Kontak Person
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap w-1/5 align-middle">
                            Telepon
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap w-1/5 align-middle">
                            Email
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap w-1/5 align-middle">
                            Alamat
                        </th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap w-1/5 align-middle">
                            Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($suppliers as $supplier)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 align-middle">
                                {{ $supplier->name }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 align-middle">
                                {{ $supplier->contact_person ?? '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 align-middle">
                                {{ $supplier->phone ?? '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 align-middle">
                                {{ $supplier->email ?? '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 align-middle">
                                {{ Str::limit($supplier->address, 50) ?: '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-center align-middle">
                                <div class="inline-flex items-center justify-center space-x-4">
                                    <a href="{{ route('suppliers.edit', $supplier->id) }}"
                                       class="text-indigo-600 hover:text-indigo-900" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>

                                    <form id="delete-form-{{ $supplier->id }}"
                                          action="{{ route('suppliers.destroy', $supplier->id) }}"
                                          method="POST" style="display: none;">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                    <button type="button"
                                            onclick="confirmDeleteSupplier({{ $supplier->id }})"
                                            class="text-red-600 hover:text-red-900" title="Hapus">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6"
                                class="px-6 py-4 text-center text-sm text-gray-500 whitespace-nowrap align-middle">
                                Tidak ada supplier ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-6">
            {{ $suppliers->links() }}
        </div>
    </div>
@endsection

@push('scripts')
<script>
    // Konfirmasi hapus supplier (SweetAlert2)
    function confirmDeleteSupplier(supplierId) {
        Swal.fire({
            title: 'Anda yakin?',
            text: "Supplier ini akan dihapus secara permanen!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-form-' + supplierId).submit();
            }
        });
    }
</script>
@endpush
