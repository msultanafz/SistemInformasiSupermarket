{{-- resources/views/login.blade.php --}}

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login - Supermarket Management</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(to right, #4a90e2, #50b0ef); /* Gradient biru yang menarik */
        }
    </style>
</head>
<body class="flex items-center justify-center min-h-screen text-gray-800">

    <div class="w-full max-w-md bg-white rounded-xl shadow-2xl p-8 transform transition-all duration-300 ">
        <div class="text-center mb-8">
            <i class="fas fa-store text-blue-600 text-5xl mb-3"></i>
            <h1 class="text-3xl font-extrabold text-gray-900">Supermarket Management</h1>
            <p class="text-gray-500 mt-2">Masuk ke akun Anda</p>
        </div>

        <form action="{{ route('login.action') }}" method="POST">
            @csrf
            <div class="mb-5">
                <label for="email" class="block text-gray-700 text-sm font-semibold mb-2">Email:</label>
                <input type="email" name="email" id="email" 
                       class="shadow-sm appearance-none border border-gray-300 rounded-lg w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200 ease-in-out" 
                       value="{{ old('email') }}" required autofocus placeholder="Masukkan email Anda">
            </div>
            <div class="mb-6">
                <label for="password" class="block text-gray-700 text-sm font-semibold mb-2">Password:</label>
                <input type="password" name="password" id="password" 
                       class="shadow-sm appearance-none border border-gray-300 rounded-lg w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200 ease-in-out" 
                       required placeholder="Masukkan password Anda">
            </div>
            
            <button type="submit" 
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-lg focus:outline-none focus:shadow-outline transform transition-all duration-200 ease-in-out hover:scale-102 hover:shadow-lg text-lg">
                Login
            </button>
        </form>
    </div>

    {{-- SCRIPT KHUSUS UNTUK HALAMAN LOGIN: DEFINISI APP_FLASH_MESSAGES (INI SUDAH BENAR) --}}
    <script type="text/javascript">
        window.APP_FLASH_MESSAGES = {
            success: @json(session('success')),
            error: @json(session('error')),
            validationErrors: @json($errors->all())
        };
    </script>
</body>
</html>