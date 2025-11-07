@props(['events','type'=>null])

<div class="grid md:grid-cols-4 gap-6 mt-8">
  @forelse($events as $event)
    <x-events.card :event="$event" :type="$type" />
  @empty
    <div class="col-span-4 bg-white border rounded-xl p-10 text-center text-gray-500">
      Belum ada event.
    </div>
  @endforelse
</div>

{{-- Pagination --}}
@if($events->hasPages())
  <div class="mt-8">
    {{ $events->onEachSide(1)->fragment('events')->links() }}
  </div>
@endif
