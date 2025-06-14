{{-- File: resources/views/layouts/partials/sidebar.blade.php --}}

<div class="hidden md:flex md:flex-shrink-0">
    <div class="flex flex-col w-64 bg-blue-800 text-white">
        <div class="flex items-center justify-center h-16 px-4 bg-blue-900">
            <span class="text-xl font-semibold">Super Market</span>
        </div>
        <div class="flex flex-col flex-grow px-4 py-4 overflow-y-auto">
            <nav class="flex-1 space-y-2">
                <a href="{{ route('dashboard') }}" class="flex items-center px-4 py-2 text-sm font-medium rounded-md {{ request()->routeIs('dashboard') ? 'bg-blue-700' : '' }} text-white hover:bg-blue-700">
                    <i class="fas fa-tachometer-alt mr-3"></i>
                    Dashboard
                </a>
                <a href="#" class="flex items-center px-4 py-2 text-sm font-medium rounded-md text-white hover:bg-blue-700">
                    <i class="fas fa-cash-register mr-3"></i>
                    Penjualan (POS)
                </a>
                <a href="#" class="flex items-center px-4 py-2 text-sm font-medium rounded-md text-white hover:bg-blue-700">
                    <i class="fas fa-boxes mr-3"></i>
                    Inventaris
                </a>
                <a href="#" class="flex items-center px-4 py-2 text-sm font-medium rounded-md text-white hover:bg-blue-700">
                    <i class="fas fa-truck-loading mr-3"></i>
                    Supplier
                </a>
                <a href="#" class="flex items-center px-4 py-2 text-sm font-medium rounded-md text-white hover:bg-blue-700">
                    <i class="fas fa-receipt mr-3"></i>
                    Pembelian
                </a>
                <a href="#" class="flex items-center px-4 py-2 text-sm font-medium rounded-md text-white hover:bg-blue-700">
                    <i class="fas fa-chart-bar mr-3"></i>
                    Laporan
                </a>
                <a href="#" class="flex items-center px-4 py-2 text-sm font-medium rounded-md text-white hover:bg-blue-700">
                    <i class="fas fa-cog mr-3"></i>
                    Pengaturan
                </a>

                <form method="POST" action="{{ route('logout') }}" class="w-full pt-2">
                    @csrf
                    <button type="submit" class="w-full flex items-center px-4 py-2 text-sm font-medium rounded-md text-white hover:bg-blue-700">
                        <i class="fas fa-sign-out-alt mr-3"></i>
                        Logout
                    </button>
                </form>
            </nav>
        </div>
        <div class="p-4 border-t border-blue-700">
            <div class="flex items-center">
                {{-- Tampilkan gambar user yang login jika ada, jika tidak, tampilkan gambar default --}}
                <img class="w-10 h-10 rounded-full" src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=0D8ABC&color=fff" alt="User">
                <div class="ml-3">
                    {{-- Tampilkan nama user yang sedang login --}}
                    <p class="text-sm font-medium">{{ auth()->user()->name }}</p>
                    <p class="text-xs text-blue-200 capitalize">{{ auth()->user()->role ?? 'User' }}</p>
                </div>
            </div>
        </div>
    </div>
</div>