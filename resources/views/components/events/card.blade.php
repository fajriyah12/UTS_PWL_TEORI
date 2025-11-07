@props(['event'])

<a href="{{ route('events.show', $event->slug) }}">
<div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-lg transition">
    <div class="relative">
        <img src="{{ $event->banner_url }}" alt="{{ $event->title }}" class="h-48 w-full object-cover">
        <span class="absolute top-3 right-3 px-3 py-1 text-xs font-semibold rounded-full bg-indigo-600 text-white">
            {{ $event->ticketTypes->first()->name ?? 'Ticket' }}
        </span>
    </div>
    <div class="p-4">
        <h3 class="font-semibold text-lg">{{ $event->title }}</h3>
        <p class="text-sm text-gray-500 mt-1">{{ $event->venue->name ?? '-' }}</p>
        <p class="text-indigo-700 font-bold text-lg mt-2">
            Rp {{ number_format($event->ticketTypes->min('price') ?? 0, 0, ',', '.') }}
        </p>
    </div>
</div>
</a>
