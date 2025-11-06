{{-- resources/views/components/navbar.blade.php --}}
<nav class="bg-white shadow-sm sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-6 py-3 flex items-center justify-between">
        <div class="flex items-center space-x-10">
            <a href="/" class="font-bold text-xl text-indigo-600">ORR'EA</a>
            <ul class="hidden md:flex space-x-8 font-medium">
                <li><a href="/" class="hover:text-indigo-600">Home</a></li>

                @if (Route::has('events.index'))
                    <li><a href="{{ route('events.index') }}" class="hover:text-indigo-600">Event</a></li>
                @else
                    <li><span class="text-gray-400">Event</span></li>
                @endif

                <li><a href="#" class="hover:text-indigo-600">Shop</a></li>
                <li><a href="#" class="hover:text-indigo-600">About</a></li>
            </ul>
        </div>

        <div class="flex items-center space-x-3">
            <div class="relative">
                <input type="text" placeholder="Search"
                       class="border rounded-full px-4 py-1.5 pl-10 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                <svg class="w-4 h-4 absolute left-3 top-2.5 text-gray-400" fill="none" stroke="currentColor" stroke-width="2"
                     viewBox="0 0 24 24">
                     <path stroke-linecap="round" stroke-linejoin="round"
                           d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>

            @guest
                <a href="{{ route('login') }}" class="px-4 py-1.5 border rounded-lg text-sm font-semibold hover:bg-gray-100">Log in</a>
                <a href="{{ route('register') }}" class="px-4 py-1.5 bg-black text-white rounded-lg text-sm font-semibold hover:bg-gray-800">Sign up</a>
            @else
                <a href="{{ route('dashboard') }}" class="text-sm font-medium hover:text-indigo-600">{{ Auth::user()->name }}</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="text-sm text-gray-500 hover:text-red-500">Logout</button>
                </form>
            @endguest
        </div>
    </div>
</nav>
