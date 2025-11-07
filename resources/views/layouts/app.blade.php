<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ORR'EA</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/daisyui@5.4.5/dist/full.css" rel="stylesheet" type="text/css" />
</head>
<body class="bg-gray-50 text-gray-900">
    @if(session('success'))
    <div class="fixed top-4 right-4 bg-green-500 text-white px-4 py-2 rounded shadow-lg z-50">
        {{ session('success') }}
    </div>
@endif

    {{-- Navbar --}}
    <x-navbar />

    {{-- Content --}}
    <main class="min-h-screen">
        @yield('content')
    </main>

    {{-- Footer --}}
    <footer class="bg-gray-100 text-center py-6 text-sm text-gray-500 mt-10">
        © {{ date('Y') }} ORR'EA. All rights reserved.
    </footer>
</body>
</html>
