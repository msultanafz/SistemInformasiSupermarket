{{-- File: resources/views/layouts/partials/sidebar.blade.php --}}

<div class="hidden md:flex md:flex-shrink-0">
    <div class="flex flex-col w-64 bg-blue-800 text-white">
        <div class="flex items-center justify-center h-16 px-4 bg-blue-900">
            <a href="{{ route('dashboard') }}" class="text-white text-xl font-semibold flex items-center">
                <i class="fas fa-store mr-2"></i> Super Market
            </a>
        </div>
        <div class="flex flex-col flex-grow px-4 py-4 overflow-y-auto custom-scrollbar">
            <nav class="flex-1 space-y-2">
                {{-- Dashboard --}}
                <a href="{{ route('dashboard') }}" class="flex items-center px-4 py-2 text-sm font-medium rounded-md {{ request()->routeIs('dashboard') ? 'bg-blue-700' : '' }} text-white hover:bg-blue-700">
                    <i class="fas fa-home mr-3"></i> Dashboard
                </a>

                {{-- Penjualan (Sales) --}}
                <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-wider px-4 mt-4 mb-2">Penjualan</h3>
                <a href="{{ route('transactions.create') }}" class="flex items-center px-4 py-2 text-sm font-medium rounded-md {{ request()->routeIs('transactions.create') ? 'bg-blue-700' : '' }} text-white hover:bg-blue-700">
                    <i class="fas fa-cash-register mr-3"></i> Mulai Transaksi (POS)
                </a>
                <a href="{{ route('transactions.index') }}" class="flex items-center px-4 py-2 text-sm font-medium rounded-md {{ request()->routeIs('transactions.index') || request()->routeIs('transactions.show') || request()->routeIs('transactions.today') ? 'bg-blue-700' : '' }} text-white hover:bg-blue-700">
                    <i class="fas fa-receipt mr-3"></i> Daftar Transaksi
                </a>

                {{-- Inventaris (Inventory) --}}
                <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-wider px-4 mt-4 mb-2">Inventaris</h3>
                <a href="{{ route('products.index') }}" class="flex items-center px-4 py-2 text-sm font-medium rounded-md {{ request()->routeIs('products.*') ? 'bg-blue-700' : '' }} text-white hover:bg-blue-700">
                    <i class="fas fa-boxes mr-3"></i> Produk
                </a>
                <a href="{{ route('categories.index') }}" class="flex items-center px-4 py-2 text-sm font-medium rounded-md {{ request()->routeIs('categories.*') ? 'bg-blue-700' : '' }} text-white hover:bg-blue-700">
                    <i class="fas fa-tags mr-3"></i> Kategori
                </a>
                <a href="{{ route('suppliers.index') }}" class="flex items-center px-4 py-2 text-sm font-medium rounded-md {{ request()->routeIs('suppliers.*') ? 'bg-blue-700' : '' }} text-white hover:bg-blue-700">
                    <i class="fas fa-truck mr-3"></i> Supplier
                </a>

                {{-- Menu Lain --}}
                <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-wider px-4 mt-4 mb-2">Lainnya</h3>
                <a href="{{ route('reports.index') }}" class="flex items-center px-4 py-2 text-sm font-medium rounded-md {{ request()->routeIs('reports.*') ? 'bg-blue-700' : '' }} text-white hover:bg-blue-700">
                    <i class="fas fa-chart-bar mr-3"></i> Laporan
                </a>
                <a href="{{ route('settings.index') }}" class="flex items-center px-4 py-2 text-sm font-medium rounded-md {{ request()->routeIs('settings.*') ? 'bg-blue-700' : '' }} text-white hover:bg-blue-700">
                    <i class="fas fa-cog mr-3"></i> Pengaturan
                </a>

                {{-- Logout Button --}}
                <form method="POST" action="{{ route('logout') }}" class="w-full">
                    @csrf
                    <button type="submit" class="w-full cursor-pointer flex items-center px-4 py-2 text-sm font-medium rounded-md text-white hover:bg-blue-700 text-left">
                        <i class="fas fa-sign-out-alt mr-3"></i> Logout
                    </button>
                </form>
            </nav>
        </div>
        <div class="p-4 border-t border-blue-700">
            {{-- User Info --}}
            <div class="flex items-center ">
                <img class="w-10 h-10 rounded-full" src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=0D8ABC&color=fff" alt="User">
                <div class="ml-3">
                    <p class="text-sm font-medium">{{ auth()->user()->name ?? 'Pengguna' }}</p>
                    <p class="text-xs text-blue-200 capitalize">{{ auth()->user()->role ?? 'Role' }}</p>
                </div>
            </div>
        </div>
    </div>
</div>