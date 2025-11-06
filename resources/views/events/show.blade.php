{{-- show --}}
<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl">{{ $event->title }}</h2></x-slot>
    <div class="py-6">
        <p>{{ $event->description }}</p>
        <p>Venue: {{ $event->venue->name ?? '-' }}</p>
        <p>Tiket:</p>
        <ul>
            @foreach ($event->ticketTypes as $ticket)
                <li>{{ $ticket->name }} - Rp{{ number_format($ticket->price, 0, ',', '.') }}</li>
            @endforeach
        </ul>
    </div>
</x-app-layout>
