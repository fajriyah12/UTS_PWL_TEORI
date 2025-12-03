{{-- index --}}
<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl">Daftar Event</h2></x-slot>
    <div class="py-6">
        @foreach ($events as $event)
            <div>
                <a href="{{ route('events.show', $event->slug) }}">{{ $event->title }}</a> 
                ({{ $event->start_time->format('d M Y') }})
            </div>
        @endforeach
    </div>
</x-app-layout>
