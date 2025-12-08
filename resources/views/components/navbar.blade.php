{{-- resources/views/components/navbar.blade.php --}}
<nav class="bg-white shadow-sm sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-6 py-3 flex items-center justify-between">
        <div class="flex items-center space-x-10">
            <a href="/" class="font-bold text-xl text-indigo-600">ORR'EA</a>
            <ul class="hidden md:flex space-x-8 font-medium">
                <li><a href="/" class="hover:text-indigo-600">Home</a></li>
                <li>
                    <a href="{{ url('/') }}#events" class="hover:text-indigo-600 scroll-smooth">
                        Event
                    </a>
                </li>
               <li><a href="{{ route('about') }}" class="hover:text-indigo-600">About</a></li>
            </ul>
        </div>

        <div class="flex items-center space-x-3">
            <div class="relative">
                <form id="searchForm" action="{{ route('home') }}" method="GET">
                    <input type="text" id="searchInput" name="search" value="{{ request('search') }}" placeholder="Search"
                           class="border rounded-full px-4 py-1.5 pl-10 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <button type="submit" class="absolute left-3 top-2.5 text-gray-400 hover:text-indigo-600">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2"
                             viewBox="0 0 24 24">
                             <path stroke-linecap="round" stroke-linejoin="round"
                                   d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </button>
                </form>
            </div>

            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const searchInput = document.getElementById('searchInput');
                    const searchForm = document.getElementById('searchForm');
                    const resultsContainer = document.getElementById('events-grid-container');
                    let timeout = null;

                    if (searchInput) {
                        searchInput.addEventListener('input', function() {
                            // Only do AJAX if we are on the page with the results container
                            if (resultsContainer) {
                                clearTimeout(timeout);
                                const query = this.value;

                                timeout = setTimeout(() => {
                                    // Get current URL parameters to preserve filters like 'type'
                                    const params = new URLSearchParams(window.location.search);
                                    
                                    // Update search param
                                    if (query) {
                                        params.set('search', query);
                                    } else {
                                        params.delete('search');
                                    }
                                    
                                    // CRITICAL: Always reset to page 1 when searching
                                    params.delete('page');

                                    fetch(`{{ route('home') }}?${params.toString()}`, {
                                        headers: {
                                            'X-Requested-With': 'XMLHttpRequest'
                                        }
                                    })
                                    .then(response => response.text())
                                    .then(html => {
                                        resultsContainer.innerHTML = html;
                                        // Optional: Update URL in browser without reload
                                        // const newUrl = `${window.location.pathname}?${params.toString()}`;
                                        // history.replaceState(null, '', newUrl);
                                    })
                                    .catch(error => console.error('Error:', error));
                                }, 300); // Debounce 300ms
                            }
                        });

                        searchForm.addEventListener('submit', function(e) {
                             if (resultsContainer) {
                                e.preventDefault(); // Prevent reload if we can update in-place
                             }
                             // Otherwise allow default submission (redirect to home with search param)
                        });
                    }
                });
            </script>

            @guest
                <a href="{{ route('login') }}" class="px-4 py-1.5 border rounded-lg text-sm font-semibold hover:bg-gray-100">Log in</a>
                <a href="{{ route('register') }}" class="px-4 py-1.5 bg-black text-white rounded-lg text-sm font-semibold hover:bg-gray-800">Sign up</a>
            @else
                <div class="dropdown dropdown-end">
                    <div class="flex items-center space-x-3">

                        @php
                            $nameParts = preg_split('/\s+/', trim(Auth::user()->name));
                            $initials = '';
                            foreach ($nameParts as $part) {
                                $initials .= mb_strtoupper(mb_substr($part, 0, 1));
                            }
                            $initials = mb_substr($initials, 0, 2);
                        @endphp

                        <div tabindex="0" role="button" class="btn btn-ghost btn-circle avatar">
                            <div class="w-10 rounded-full flex items-center justify-center bg-indigo-600 text-white text-sm font-semibold overflow-hidden">
                                @if (Auth::user()->photo)
                                    <img
                                        alt="Profile"
                                        src="{{ asset('storage/profile/' . Auth::user()->photo) }}" />
                                @else
                                    {{ $initials }}
                                @endif
                            </div>
                        </div>
                    </div>

                    <ul tabindex="-1" class="menu menu-sm dropdown-content bg-base-100 rounded-box z-10 mt-3 w-52 p-2 shadow">
                        <li>
                            <a href="{{ url('/') }}" class=" hover:text-indigo-600">{{ Auth::user()->name }}</a>
                        </li>
                        <li>
                            <a href="{{ route('profile.show') }}" class="justify-between">
                              Profile
                            <span class="badge">New</span>
                             </a>

                        </li>
                        <li><a href="{{ route('transactions.tickets') }}">My Tickets</a></li>
                        <li><a href="{{ route('transactions.index') }}">Riwayat Transaksi</a></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}" class="w-full">
                                @csrf
                                <button type="submit" class="w-full text-left">Logout</button>
                            </form>
                        </li>
                    </ul>
                </div>
            @endguest
        </div>
    </div>
</nav>
