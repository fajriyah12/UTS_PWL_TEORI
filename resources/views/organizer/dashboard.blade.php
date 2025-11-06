<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl">Organizer Dashboard</h2></x-slot>
    <div class="py-6">
        <p>Organizer: {{ $organizer->name ?? 'Belum terdaftar' }}</p>
        <h3 class="font-semibold mt-4">Daftar Event:</h3>
        <ul>
            @forelse ($events as $event)
                <li>{{ $event->title }} - Status: {{ $event->status }}</li>
            @empty
                <li>Belum ada event.</li>
            @endforelse
        </ul>
    </div>
</x-app-layout>
