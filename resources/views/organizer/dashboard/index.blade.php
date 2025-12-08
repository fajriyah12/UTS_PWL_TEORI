@extends('organizer.layout')

@section('title', 'Dashboard Organizer')

@section('content')
<div class="mb-8">
    <h1 class="text-3xl font-bold text-gray-800">Dashboard Organizer</h1>
    <p class="text-gray-600">Selamat datang, {{ auth()->user()->name }}! Kelola event dan pantau penjualan tiket Anda.</p>
</div>

<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="bg-white rounded-xl shadow p-6">
        <div class="flex items-center">
            <div class="p-3 bg-blue-100 rounded-lg">
                <i class="fas fa-calendar-alt text-blue-600 text-xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm text-gray-500">Total Events</p>
                <h3 class="text-2xl font-bold">{{ $totalEvents }}</h3>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-xl shadow p-6">
        <div class="flex items-center">
            <div class="p-3 bg-green-100 rounded-lg">
                <i class="fas fa-ticket-alt text-green-600 text-xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm text-gray-500">Tiket Terjual</p>
                <h3 class="text-2xl font-bold">{{ $totalTicketsSold }}</h3>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-xl shadow p-6">
        <div class="flex items-center">
            <div class="p-3 bg-purple-100 rounded-lg">
                <i class="fas fa-money-bill-wave text-purple-600 text-xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm text-gray-500">Total Pendapatan</p>
                <h3 class="text-2xl font-bold">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h3>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="bg-white rounded-xl shadow p-6 mb-8">
    <h2 class="text-xl font-bold mb-4">Quick Actions</h2>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <a href="{{ route('organizer.events.create') }}" 
           class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-indigo-500 hover:bg-indigo-50 transition">
            <i class="fas fa-plus text-3xl text-gray-400 mb-2"></i>
            <p class="font-semibold">Buat Event Baru</p>
            <p class="text-sm text-gray-500">Tambahkan event konser baru</p>
        </a>
        
        <a href="{{ route('organizer.events.index') }}" 
           class="border-2 border-gray-300 rounded-lg p-6 text-center hover:border-indigo-500 hover:bg-indigo-50 transition">
            <i class="fas fa-list text-3xl text-gray-400 mb-2"></i>
            <p class="font-semibold">Kelola Events</p>
            <p class="text-sm text-gray-500">Lihat dan edit semua event</p>
        </a>
        
        <a href="{{ route('organizer.statistics') }}" 
           class="border-2 border-gray-300 rounded-lg p-6 text-center hover:border-indigo-500 hover:bg-indigo-50 transition">
            <i class="fas fa-chart-bar text-3xl text-gray-400 mb-2"></i>
            <p class="font-semibold">Lihat Statistik</p>
            <p class="text-sm text-gray-500">Analisis penjualan dan performa</p>
        </a>
    </div>
</div>

<!-- Recent Events -->
<div class="bg-white rounded-xl shadow p-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl font-bold">Event Terbaru</h2>
        <a href="{{ route('organizer.events.index') }}" class="text-indigo-600 hover:text-indigo-800">
            Lihat Semua <i class="fas fa-arrow-right ml-1"></i>
        </a>
    </div>
    
    @if($recentEvents->count() > 0)
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Event</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tiket Terjual</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($recentEvents as $event)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                @if($event->image)
                                    <img class="h-10 w-10 rounded-lg object-cover mr-3" 
                                         src="{{ asset('storage/' . $event->image) }}" alt="{{ $event->title }}">
                                @else
                                    <div class="h-10 w-10 bg-indigo-100 rounded-lg flex items-center justify-center mr-3">
                                        <i class="fas fa-music text-indigo-600"></i>
                                    </div>
                                @endif
                                <div>
                                    <div class="font-medium text-gray-900">{{ $event->title }}</div>
                                    <div class="text-sm text-gray-500">{{ $event->location }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $event->start_time->format('d M Y') }}</div>
                            <div class="text-sm text-gray-500">{{ $event->start_time->format('H:i') }} - {{ $event->end_time->format('H:i') }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($event->status == 'published')
                                <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">Published</span>
                            @elseif($event->status == 'draft')
                                <span class="px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-800">Draft</span>
                            @else
                                <span class="px-2 py-1 text-xs rounded-full bg-gray-100 text-gray-800">{{ $event->status }}</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $event->ticketTypes->sum('sold') }} / {{ $event->ticketTypes->sum('quota') }}</div>
                            @if($event->ticketTypes->sum('quota') > 0)
                                <div class="w-full bg-gray-200 rounded-full h-2 mt-1">
                                    <div class="bg-indigo-600 h-2 rounded-full" 
                                         style="width: {{ min(100, ($event->ticketTypes->sum('sold') / $event->ticketTypes->sum('quota')) * 100) }}%"></div>
                                </div>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <a href="{{ route('organizer.events.show', $event) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('organizer.events.edit', $event) }}" class="text-yellow-600 hover:text-yellow-900 mr-3">
                                <i class="fas fa-edit"></i>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="text-center py-8">
            <i class="fas fa-calendar-plus text-4xl text-gray-300 mb-4"></i>
            <p class="text-gray-500 mb-4">Belum ada event yang dibuat</p>
            <a href="{{ route('organizer.events.create') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700">
                <i class="fas fa-plus mr-2"></i> Buat Event Pertama
            </a>
        </div>
    @endif
</div>
@endsection