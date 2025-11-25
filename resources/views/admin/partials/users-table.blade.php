<table class="w-full text-left border-collapse">
  <thead>
    <tr class="bg-purple-50 text-gray-700">
      <th class="p-3">User ID</th>
      <th class="p-3">Name</th>
      <th class="p-3">Email</th>
      <th class="p-3">Phone</th>
      <th class="p-3">Role</th>
      <th class="p-3">Actions</th>
    </tr>
  </thead>
  <tbody class="text-gray-600">
    @forelse($users as $user)
    <tr class="border-b">
      <td class="p-3">{{ $user->id }}</td>
      <td>{{ $user->name }}</td>
      <td>{{ $user->email }}</td>
      <td>{{ $user->phone ?? '-' }}</td>
      <td>
        @if($user->role === 'admin')
          <span class="bg-red-100 text-red-600 px-2 py-1 rounded text-sm">Admin</span>
        @elseif($user->role === 'organizer')
          <span class="bg-green-100 text-green-600 px-2 py-1 rounded text-sm">Organizer</span>
        @else
          <span class="bg-blue-100 text-blue-600 px-2 py-1 rounded text-sm">User</span>
        @endif
      </td>
      <td>
        <i class="fas fa-edit text-blue-500 mr-2 cursor-pointer"></i>
        <i class="fas fa-trash text-red-500 cursor-pointer delete-user" data-user-id="{{ $user->id }}"></i>
      </td>
    </tr>
    @empty
    <tr>
      <td class="p-3 text-center text-gray-500" colspan="6">No users found</td>
    </tr>
    @endforelse
  </tbody>
</table>

<!-- Pagination -->
@if ($users->hasPages())
    @php
        $perPageRange = 5;
        $current = $users->currentPage();
        $start = floor(($current - 1) / $perPageRange) * $perPageRange + 1;
        $end = min($start + $perPageRange - 1, $users->lastPage());
    @endphp

    <div class="mt-4 flex justify-center gap-2 text-sm">

        {{-- Prev group --}}
        @if ($start > 1)
            <a href="{{ $users->url(max(1, $start - $perPageRange)) }}"
               class="px-3 py-1 border rounded hover:bg-gray-100">Prev</a>
        @else
            <span class="px-3 py-1 border rounded text-gray-300 cursor-not-allowed">Prev</span>
        @endif


        {{-- Page numbers --}}
        @for ($i = $start; $i <= $end; $i++)
            @if ($i == $users->currentPage())
                <span class="px-3 py-1 bg-purple-600 text-white rounded">{{ $i }}</span>
            @else
                <a href="{{ $users->url($i) }}"
                   class="px-3 py-1 border rounded hover:bg-gray-100">{{ $i }}</a>
            @endif
        @endfor


        {{-- Next group --}}
        @if ($end < $users->lastPage())
            <a href="{{ $users->url($end + 1) }}"
               class="px-3 py-1 border rounded hover:bg-gray-100">Next</a>
        @else
            <span class="px-3 py-1 border rounded text-gray-300 cursor-not-allowed">Next</span>
        @endif

    </div>
@endif

