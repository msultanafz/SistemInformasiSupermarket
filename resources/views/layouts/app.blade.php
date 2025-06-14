{{-- File: resources/views/layouts/app.blade.php --}}
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite('resources/css/app.css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />
    <title>@yield('title', 'Supermarket Management')</title> {{-- Judul halaman dinamis --}}
</head>

<body class="bg-gray-100 font-sans">
    <div class="flex h-screen overflow-hidden">
        @include('layouts.partials.sidebar') {{-- Kita pisahkan sidebar agar rapi --}}

        <div class="flex flex-col flex-1 overflow-hidden">
            @include('layouts.partials.header') {{-- Kita pisahkan header agar rapi --}}

            <main class="flex-1 overflow-y-auto p-6 bg-gray-100">
                @yield('content') {{-- Di sinilah "kamar" atau konten halaman akan dimasukkan --}}
            </main>
        </div>
    </div>
</body>

</html>