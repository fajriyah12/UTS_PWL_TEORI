@props(['events'])

<div class="grid md:grid-cols-4 gap-6 mt-8">
    @forelse ($events as $event)
        <x-events.card :event="$event" />
    @empty
        <div class="col-span-4 bg-white border rounded-xl p-10 text-center text-gray-500">
            Belum ada event dipublikasikan.
        </div>
    @endforelse
</div>
