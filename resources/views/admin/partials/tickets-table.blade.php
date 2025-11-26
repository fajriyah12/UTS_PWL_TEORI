<table class="w-full text-left border-collapse">
  <thead>
    <tr class="bg-purple-50 text-gray-700">
      <th class="p-3">Ticket ID</th>
      <th class="p-3">Event Name</th>
      <th class="p-3">Category</th>
      <th class="p-3">Price</th>
      <th class="p-3">Quantity</th>
      <th class="p-3">Sold</th>
      <th class="p-3">Status</th>
      <th class="p-3">Actions</th>
    </tr>
  </thead>
  <tbody class="text-gray-600">
    @forelse($tickets as $ticket)
    <tr class="border-b">
      <td class="p-3">{{ $ticket->id }}</td>
      <td>{{ $ticket->event->title }}</td>
      <td><span class="bg-purple-100 text-purple-600 px-2 py-1 rounded text-sm">{{ $ticket->name }}</span></td>
      <td>Rp {{ number_format($ticket->price) }}</td>
      <td>{{ $ticket->quota }}</td>
      <td>{{ $ticket->sold }}</td>
      <td>
        @if($ticket->quota > $ticket->sold)
          <span class="bg-green-100 text-green-600 px-2 py-1 rounded text-sm">Active</span>
        @else
          <span class="bg-gray-100 text-gray-600 px-2 py-1 rounded text-sm">Inactive</span>
        @endif
      </td>
      <td>
        <a href="{{ route('admin.edit-ticket', $ticket->id) }}" class="text-blue-600 hover:text-blue-800">
    <i class="fas fa-edit"></i>
</a>

        <i class="fas fa-trash text-red-500 cursor-pointer delete-ticket" data-ticket-id="{{ $ticket->id }}"></i>
      </td>
    </tr>
    @empty
    <tr>
      <td class="p-3 text-center text-gray-500" colspan="8">No tickets found</td>
    </tr>
    @endforelse
  </tbody>
</table>

<!-- Pagination -->
@if ($tickets->hasPages())
    @php
        $perPageRange = 5;
        $current = $tickets->currentPage();
        $start = floor(($current - 1) / $perPageRange) * $perPageRange + 1;
        $end = min($start + $perPageRange - 1, $tickets->lastPage());
    @endphp

    <div class="mt-4 flex justify-center gap-2 text-sm">

        {{-- Prev group --}}
        @if ($start > 1)
            <a href="{{ $tickets->url(max(1, $start - $perPageRange)) }}"
               class="px-3 py-1 border rounded hover:bg-gray-100">Prev</a>
        @else
            <span class="px-3 py-1 border rounded text-gray-300 cursor-not-allowed">Prev</span>
        @endif


        {{-- Page numbers --}}
        @for ($i = $start; $i <= $end; $i++)
            @if ($i == $tickets->currentPage())
                <span class="px-3 py-1 bg-purple-600 text-white rounded">{{ $i }}</span>
            @else
                <a href="{{ $tickets->url($i) }}"
                   class="px-3 py-1 border rounded hover:bg-gray-100">{{ $i }}</a>
            @endif
        @endfor


        {{-- Next group --}}
        @if ($end < $tickets->lastPage())
            <a href="{{ $tickets->url($end + 1) }}"
               class="px-3 py-1 border rounded hover:bg-gray-100">Next</a>
        @else
            <span class="px-3 py-1 border rounded text-gray-300 cursor-not-allowed">Next</span>
        @endif

    </div>
@endif
