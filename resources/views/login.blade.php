<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite('resources/css/app.css')
    <title>Document</title>

</head>

<body>
    <div class="flex justify-center items-center h-screen bg-gray-100">
        <div class="xl:w-[700px] px-10 h-[400px] bg-white rounded-3xl shadow-xl shadow-gray-400">
            <h1 class="text-center text-3xl font-bold mt-2 mb-2">Login</h1>
            <hr>
            <div class="flex justify-center mt-10">
                <form method="POST" action="{{ route('login.action') }}">
                    @csrf

                    <!-- Email -->
                    <input
                        type="email"
                        name="email"
                        id="email"
                        class="py-3 p-5 rounded-md bg-zinc-50 md:w-[500px] w-[300px] outline-indigo-400 shadow-gray-400 shadow-md"
                        placeholder="Enter your email"
                        required>
                    <br><br>

                    <!-- Password -->
                    <input
                        type="password"
                        name="password"
                        id="password"
                        class="py-3 p-5 rounded-md bg-zinc-50 md:w-[500px] w-[300px] outline-indigo-400 shadow-gray-400 shadow-md"
                        placeholder="Enter your password"
                        required>

                    <div class="flex justify-end mt-3 mb-4">
                        <a href="#" class="text-blue-700 hover:scale-105 duration-300">Forgot password</a>
                    </div>

                    <button type="submit" class="py-3 bg-indigo-400 text-white w-full rounded-md font-bold hover:bg-indigo-700 duration-500 shadow-gray-400 shadow-md">
                        Submit
                    </button>
                </form>
            </div>
        </div>
    </div>
</body>

</html>