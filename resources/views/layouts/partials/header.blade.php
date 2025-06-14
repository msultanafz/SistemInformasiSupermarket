{{-- File: resources/views/layouts/partials/header.blade.php --}}

<header class="flex items-center justify-between px-6 py-4 bg-white border-b border-gray-200">
    <div class="flex items-center">
        <button class="md:hidden text-gray-500 focus:outline-none">
            <i class="fas fa-bars"></i>
        </button>
        <h1 class="text-xl font-semibold text-gray-800 ml-4">@yield('title', 'Dasbor')</h1>
    </div>
    <div class="flex items-center space-x-4">
        <button class="text-gray-500 focus:outline-none">
            <i class="fas fa-bell"></i>
        </button>
        <button class="text-gray-500 focus:outline-none">
            <i class="fas fa-envelope"></i>
        </button>
        <div class="relative">
            <button class="flex items-center focus:outline-none">
                {{-- Mengambil gambar dari UI Avatars sesuai nama user yg login --}}
                <img class="w-8 h-8 rounded-full" src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=0D8ABC&color=fff" alt="User">
            </button>
        </div>
    </div>
</header>