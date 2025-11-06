@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-6 py-8">

    {{-- Hero Slider (pakai Swiper/Tailwind simple) --}}
    <div class="relative w-full overflow-hidden rounded-2xl shadow-md">
        <img src="https://images.unsplash.com/photo-1507874457470-272b3c8d8ee2"
             class="w-full h-[420px] object-cover" alt="Concert Banner">
        <button class="absolute top-1/2 left-4 -translate-y-1/2 bg-white/70 p-2 rounded-full hover:bg-white">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2"
                 viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" /></svg>
        </button>
        <button class="absolute top-1/2 right-4 -translate-y-1/2 bg-white/70 p-2 rounded-full hover:bg-white">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2"
                 viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" /></svg>
        </button>
    </div>

    {{-- Filter Buttons --}}
    <div class="flex space-x-2 mt-8">
        <button class="px-4 py-1.5 rounded-full bg-indigo-100 text-indigo-700 text-sm font-semibold">All</button>
        <button class="px-4 py-1.5 rounded-full bg-white border text-gray-700 text-sm hover:bg-gray-50">VIP</button>
        <button class="px-4 py-1.5 rounded-full bg-white border text-gray-700 text-sm hover:bg-gray-50">Regular</button>
        <button class="px-4 py-1.5 rounded-full bg-white border text-gray-700 text-sm hover:bg-gray-50">Festival</button>
    </div>

    {{-- Event Cards Grid --}}
    <div class="grid md:grid-cols-3 gap-6 mt-8">
        @php
            $events = [
                ['title' => 'Konser The Weeknd', 'artist'=>'The Weeknd', 'date'=>'15 Maret 2025', 'venue'=>'Jakarta International Expo', 'price'=>'Rp 850.000', 'type'=>'VIP', 'image'=>'https://images.unsplash.com/photo-1507874457470-272b3c8d8ee2'],
                ['title' => 'Festival Musik Indie Indonesia', 'artist'=>'Multi-Artis', 'date'=>'22 Maret 2025', 'venue'=>'Lapangan Bung Karno', 'price'=>'Rp 250.000', 'type'=>'Festival', 'image'=>'https://images.unsplash.com/photo-1504805572947-34fad45aed93'],
                ['title' => 'Concert Coldplay', 'artist'=>'Coldplay', 'date'=>'5 April 2025', 'venue'=>'Gelora Bung Karno Stadium', 'price'=>'Rp 1.200.000', 'type'=>'VIP', 'image'=>'https://images.unsplash.com/photo-1508214751196-bcfd4ca60f91'],
                ['title' => 'Jazz Night Jakarta', 'artist'=>'Multi-Jazz Artists', 'date'=>'10 April 2025', 'venue'=>'Istora Senayan', 'price'=>'Rp 450.000', 'type'=>'Regular', 'image'=>'https://images.unsplash.com/photo-1506157786151-b8491531f063'],
                ['title' => 'EDM Festival 2025', 'artist'=>'Various DJs', 'date'=>'20 April 2025', 'venue'=>'Pantai Marina Ancol', 'price'=>'Rp 350.000', 'type'=>'Festival', 'image'=>'https://images.unsplash.com/photo-1518972559570-7cc1309f3229'],
                ['title' => 'Konser Payung Teduh', 'artist'=>'Payung Teduh', 'date'=>'25 April 2025', 'venue'=>'Gedung Sari Pan Pacific', 'price'=>'Rp 300.000', 'type'=>'Regular', 'image'=>'https://images.unsplash.com/photo-1504805572947-34fad45aed93'],
            ];
        @endphp

        @foreach ($events as $event)
        <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-lg transition">
            <div class="relative">
                <img src="{{ $event['image'] }}" alt="{{ $event['title'] }}" class="h-48 w-full object-cover">
                <span class="absolute top-3 right-3 px-3 py-1 text-xs font-semibold rounded-full bg-indigo-600 text-white">{{ $event['type'] }}</span>
            </div>
            <div class="p-4">
                <p class="text-sm text-gray-500">{{ $event['artist'] }}</p>
                <h3 class="font-semibold text-lg">{{ $event['title'] }}</h3>
                <div class="flex items-center text-sm text-gray-500 mt-2 space-x-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2"
                         viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round"
                         d="M8 7V3m8 4V3m-9 4h10a2 2 0 012 2v10a2 2 0 01-2 2H7a2 2 0 01-2-2V9a2 2 0 012-2z" /></svg>
                    <span>{{ $event['date'] }}</span>
                </div>
                <div class="flex items-center text-sm text-gray-500 mt-1 space-x-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2"
                         viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round"
                         d="M17.657 16.657L13.414 12m0 0l4.243-4.243M13.414 12H3" /></svg>
                    <span>{{ $event['venue'] }}</span>
                </div>
                <p class="mt-3 text-gray-700 text-sm">Mulai dari</p>
                <p class="text-indigo-700 font-bold text-lg">{{ $event['price'] }}</p>
                <button class="mt-4 w-full bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold py-2 rounded-lg transition">Beli Tiket</button>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection
