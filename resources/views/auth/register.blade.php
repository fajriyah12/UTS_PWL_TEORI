<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Register - {{ config('app.name', 'Laravel') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="font-sans antialiased text-gray-900">
    <section class="bg-gray-100 min-h-screen flex box-border justify-center items-center py-10">
        <div class="bg-white rounded-2xl flex max-w-4xl w-full p-5 items-center shadow-lg mx-4">
            
            <!-- Form Side (Left) -->
            <div class="md:w-1/2 px-8">
                <h2 class="font-bold text-3xl text-indigo-900">Register</h2>
                <p class="text-sm mt-4 text-indigo-900">Buat akun baru untuk mulai menjelajah.</p>

                <form method="POST" action="{{ route('register') }}" class="flex flex-col gap-4 mt-8">
                    @csrf
                    
                    <!-- Name -->
                    <div>
                        <input class="p-2 rounded-xl border w-full {{ $errors->has('name') ? 'border-red-500' : '' }}" 
                               type="text" name="name" placeholder="Nama Lengkap" value="{{ old('name') }}" required autofocus>
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>

                    <!-- Email -->
                    <div>
                        <input class="p-2 rounded-xl border w-full {{ $errors->has('email') ? 'border-red-500' : '' }}" 
                               type="email" name="email" placeholder="Email" value="{{ old('email') }}" required>
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <!-- Password -->
                    <div class="relative">
                        <input class="p-2 rounded-xl border w-full {{ $errors->has('password') ? 'border-red-500' : '' }}" 
                               type="password" name="password" id="password" placeholder="Password" required autocomplete="new-password">
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <!-- Confirm Password -->
                    <div class="relative">
                        <input class="p-2 rounded-xl border w-full" 
                               type="password" name="password_confirmation" id="password_confirmation" placeholder="Konfirmasi Password" required autocomplete="new-password">
                        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                    </div>

                    <button class="bg-indigo-600 text-white py-2 rounded-xl hover:scale-105 duration-300 hover:bg-indigo-700 font-medium" type="submit">Register</button>
                </form>

                <div class="mt-6 grid grid-cols-3 items-center text-gray-400">
                    <hr class="border-gray-300">
                    <p class="text-center text-sm">OR</p>
                    <hr class="border-gray-300">
                </div>

                <a href="{{ route('auth.google') }}" class="bg-white border py-2 w-full rounded-xl mt-5 flex justify-center items-center text-sm hover:scale-105 duration-300 hover:bg-gray-50 font-medium text-indigo-900 border-indigo-100 shadow-sm">
                    <svg class="mr-3" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48" width="25px">
                        <path fill="#FFC107" d="M43.611,20.083H42V20H24v8h11.303c-1.649,4.657-6.08,8-11.303,8c-6.627,0-12-5.373-12-12c0-6.627,5.373-12,12-12c3.059,0,5.842,1.154,7.961,3.039l5.657-5.657C34.046,6.053,29.268,4,24,4C12.955,4,4,12.955,4,24c0,11.045,8.955,20,20,20c11.045,0,20-8.955,20-20C44,22.659,43.862,21.35,43.611,20.083z"></path>
                        <path fill="#FF3D00" d="M6.306,14.691l6.571,4.819C14.655,15.108,18.961,12,24,12c3.059,0,5.842,1.154,7.961,3.039l5.657-5.657C34.046,6.053,29.268,4,24,4C16.318,4,9.656,8.337,6.306,14.691z"></path>
                        <path fill="#4CAF50" d="M24,44c5.166,0,9.86-1.977,13.409-5.192l-6.19-5.238C29.211,35.091,26.715,36,24,36c-5.202,0-9.619-3.317-11.283-7.946l-6.522,5.025C9.505,39.556,16.227,44,24,44z"></path>
                        <path fill="#1976D2" d="M43.611,20.083H42V20H24v8h11.303c-0.792,2.237-2.231,4.166-4.087,5.571c0.001-0.001,0.002-0.001,0.003-0.002l6.19,5.238C36.971,39.205,44,34,44,24C44,22.659,43.862,21.35,43.611,20.083z"></path>
                    </svg>
                    Sign up with Google
                </a>

                <div class="mt-10 text-sm flex justify-between items-center text-indigo-900 border-t border-gray-200 pt-4">
                    <p>Sudah punya akun?</p>
                    <a href="{{ route('login') }}" class="py-2 px-5 bg-white border rounded-xl hover:scale-110 duration-300 border-gray-400 text-indigo-900 font-semibold">Login</a>
                </div>
            </div>

            <!-- Image Side (Right) -->
            <div class="md:block hidden w-1/2">
                <img class="rounded-2xl w-full object-cover h-[600px]" 
                     src="https://images.unsplash.com/photo-1720443671577-3227f117536a?q=80&w=687&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D" 
                     alt="Register Image">
            </div>
        </div>
    </section>
</body>
</html>
