<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Verify Email - {{ config('app.name', 'Laravel') }}</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    
    <!-- Tailwind CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased text-gray-900 bg-gray-100 min-h-screen flex items-center justify-center p-4">

    <div class="bg-white rounded-2xl shadow-xl w-full max-w-md p-8 text-center">
        <!-- Icon -->
        <div class="mb-6 flex justify-center">
            <div class="bg-indigo-100 p-4 rounded-full">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                </svg>
            </div>
        </div>

        <h2 class="text-2xl font-bold text-gray-800 mb-2">Verifikasi Email Anda</h2>
        
        <div class="mb-6 text-gray-600 text-sm leading-relaxed">
            {{ __('Terima kasih telah mendaftar! Sebelum memulai, mohon verifikasi alamat email Anda dengan mengklik tautan yang baru saja kami kirimkan ke email Anda. Jika Anda tidak menerima email tersebut, kami dengan senang hati akan mengirimkanya lagi.') }}
        </div>

        @if (session('status') == 'verification-link-sent')
            <div class="mb-6 font-medium text-sm text-green-600 bg-green-50 p-3 rounded-lg border border-green-200">
                {{ __('Tautan verifikasi baru telah dikirim ke alamat email yang Anda berikan saat pendaftaran.') }}
            </div>
        @endif

        <div class="space-y-4">
            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-3 px-6 rounded-xl transition duration-300 shadow-md transform hover:scale-105">
                    {{ __('Kirim Ulang Email Verifikasi') }}
                </button>
            </form>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="text-indigo-600 hover:text-indigo-800 text-sm font-medium hover:underline transition duration-300">
                    {{ __('Log Out') }}
                </button>
            </form>
        </div>
    </div>

</body>
</html>
