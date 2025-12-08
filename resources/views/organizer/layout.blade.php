<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Organizer Dashboard') - ORR'EA</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    @stack('styles')
</head>
<body class="bg-gray-50">
    <!-- Navbar -->
    <nav class="bg-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <a href="{{ route('organizer.dashboard') }}" class="text-xl font-bold text-indigo-600">
                        <i class="fas fa-ticket-alt mr-2"></i>ORR'EA Organizer
                    </a>
                </div>
                
                <div class="flex items-center space-x-4">
                    <a href="{{ route('home') }}" class="text-gray-600 hover:text-gray-900">
                        <i class="fas fa-home"></i> Beranda
                    </a>
                    
                    <!-- User Dropdown -->
                    <div class="relative">
                        <button id="userDropdown" class="flex items-center space-x-2 text-gray-700 hover:text-gray-900">
                            <div class="w-8 h-8 bg-indigo-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-user text-indigo-600"></i>
                            </div>
                            <span>{{ auth('staff')->user()->name }}</span>
                            <i class="fas fa-chevron-down"></i>
                        </button>
                        
                        <div id="dropdownMenu" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1">
                            <a href="{{ route('organizer.profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                <i class="fas fa-user-cog mr-2"></i> Profile
                            </a>
                            <a href="{{ route('organizer.statistics') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                <i class="fas fa-chart-bar mr-2"></i> Statistik
                            </a>
                            <hr class="my-1">
                            <form method="POST" action="{{ route('staff.logout') }}">
                                @csrf
                                <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100">
                                    <i class="fas fa-sign-out-alt mr-2"></i> Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Sidebar & Main Content -->
    <div class="flex">
        <!-- Sidebar -->
        <div class="w-64 bg-white shadow-md min-h-screen">
            <div class="p-6">
                <!-- Organizer Info -->
                <div class="mb-8">
                    @if(auth('staff')->user()->organizer && auth('staff')->user()->organizer->logo_path)
                        <img src="{{ asset('storage/' . auth('staff')->user()->organizer->logo_path) }}" 
                             alt="Logo" class="w-16 h-16 rounded-full mx-auto mb-2">
                    @else
                        <div class="w-16 h-16 bg-indigo-100 rounded-lg flex items-center justify-center mx-auto mb-2">
                            <i class="fas fa-building text-indigo-600 text-2xl"></i>
                        </div>
                    @endif
                    <h3 class="text-center font-semibold">{{ auth('staff')->user()->organizer->name ?? 'Organizer' }}</h3>
                    <p class="text-center text-sm text-gray-500">
                        {{ auth('staff')->user()->organizer->is_verified ? '✅ Verified' : '⏳ Pending Verification' }}
                    </p>
                </div>

                <!-- Menu -->
                <nav class="space-y-2">
                    <a href="{{ route('organizer.dashboard') }}" 
                       class="flex items-center px-4 py-3 text-gray-700 rounded-lg hover:bg-indigo-50 {{ request()->routeIs('organizer.dashboard') ? 'bg-indigo-50 text-indigo-600' : '' }}">
                        <i class="fas fa-tachometer-alt mr-3"></i> Dashboard
                    </a>
                    
                    <a href="{{ route('organizer.events.index') }}" 
                       class="flex items-center px-4 py-3 text-gray-700 rounded-lg hover:bg-indigo-50 {{ request()->routeIs('organizer.events.*') ? 'bg-indigo-50 text-indigo-600' : '' }}">
                        <i class="fas fa-calendar-alt mr-3"></i> Events
                    </a>
                    
                    <a href="{{ route('organizer.statistics') }}" 
                       class="flex items-center px-4 py-3 text-gray-700 rounded-lg hover:bg-indigo-50 {{ request()->routeIs('organizer.statistics') ? 'bg-indigo-50 text-indigo-600' : '' }}">
                        <i class="fas fa-chart-line mr-3"></i> Statistik
                    </a>
                    
                    <a href="{{ route('organizer.profile.edit') }}" 
                       class="flex items-center px-4 py-3 text-gray-700 rounded-lg hover:bg-indigo-50 {{ request()->routeIs('organizer.profile.*') ? 'bg-indigo-50 text-indigo-600' : '' }}">
                        <i class="fas fa-cog mr-3"></i> Settings
                    </a>
                </nav>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex-1 p-8">
            @if(session('success'))
                <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif
            
            @if(session('error'))
                <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                    {{ session('error') }}
                </div>
            @endif
            
            @yield('content')
        </div>
    </div>

    <!-- JavaScript -->
    <script>
        // Toggle dropdown
        document.getElementById('userDropdown').addEventListener('click', function() {
            document.getElementById('dropdownMenu').classList.toggle('hidden');
        });

        // Close dropdown when clicking outside
        document.addEventListener('click', function(event) {
            const dropdown = document.getElementById('dropdownMenu');
            const button = document.getElementById('userDropdown');
            
            if (!button.contains(event.target) && !dropdown.contains(event.target)) {
                dropdown.classList.add('hidden');
            }
        });
    </script>
    
    @stack('scripts')
</body>
</html>