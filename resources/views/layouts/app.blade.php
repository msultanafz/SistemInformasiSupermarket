<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Supermarket Management')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />
</head>

<body class="bg-gray-100 font-sans">
    <div class="flex h-screen overflow-hidden">
        @include('layouts.partials.sidebar')

        <div class="flex flex-col flex-1 overflow-hidden">
            @include('layouts.partials.header')

            <main class="flex-1 overflow-y-auto p-6 bg-gray-100">
                @yield('content')
            </main>
        </div>
    </div>

    <!-- Script Global Variables & Flash Messages - KEMBALIKAN KE SINI -->
    <script type="text/javascript">
        // Mendefinisikan URL route sebagai string JS
        window.APP_ROUTES = {
            transactions_store: "/transactions",
            transactions_index: "/transactions",
        };

        // Mengekspor flash messages dan errors ke JavaScript
        // Ini akan memicu notifikasi di SEMUA HALAMAN YANG MENG-EXTEND LAYOUT INI
        window.APP_FLASH_MESSAGES = {
            success: @json(session('success')),
            error: @json(session('error')),
            validationErrors: @json($errors->all())
        };
    </script>
    <!-- Akhir Script Global Variables -->

    @stack('scripts')

</body>

</html>