@props(['event','type'=>null])

@php
  $price = $type
      ? ($event->ticketTypes->where('name',$type)->min('price') ?? 0)
      : ($event->ticket_types_min_price ?? $event->ticketTypes->min('price') ?? 0);


  $showBadge = !empty($type);
  $badgeName = $showBadge ? ($event->ticketTypes->where('name',$type)->first()->name ?? null) : null;
@endphp

<a href="{{ route('events.show', $event->slug) }}">
<div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-lg transition
            js-reveal duration-700 will-change-transform">
  <div class="relative">
    <img src="{{ $event->banner_url }}" alt="{{ $event->title }}" class="h-48 w-full object-cover">

    @if($showBadge && $badgeName)
      <span data-badge class="absolute top-3 right-3 px-3 py-1 text-xs font-semibold rounded-full bg-indigo-600 text-white">
        {{ $badgeName }}
      </span>
    @endif
  </div>

  <div class="p-4">
    <h3 class="font-semibold text-lg truncate leading-snug" title="{{ $event->title }}">{{ $event->title }}</h3>
    <p class="text-sm text-gray-500 mt-1 truncate">{{ $event->venue->name ?? '-' }}</p>
    <p class="text-indigo-700 font-bold text-lg mt-2">Rp {{ number_format($price, 0, ',', '.') }}</p>
  </div>
</div>
</a>
