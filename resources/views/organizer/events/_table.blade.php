    @if($events->count() > 0)
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Event</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tiket</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pendapatan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($events as $event)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                @if($event->image)
                                    <img class="h-12 w-12 rounded-lg object-cover mr-4" 
                                         src="{{ asset('storage/' . $event->image) }}" alt="{{ $event->title }}">
                                @else
                                    <div class="h-12 w-12 bg-indigo-100 rounded-lg flex items-center justify-center mr-4">
                                        <i class="fas fa-music text-indigo-600"></i>
                                    </div>
                                @endif
                                <div>
                                    <a href="{{ route('events.show', $event->slug) }}" target="_blank" 
                                       class="font-semibold text-gray-900 hover:text-indigo-600">
                                        {{ $event->title }}
                                    </a>
                                    <div class="text-sm text-gray-500 mt-1">
                                        <i class="fas fa-map-marker-alt mr-1"></i> {{ $event->location }}
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $event->start_time->format('d M Y') }}</div>
                            <div class="text-sm text-gray-500">{{ $event->start_time->format('H:i') }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($event->status == 'published')
                                <span class="px-3 py-1 text-xs rounded-full bg-green-100 text-green-800">
                                    <i class="fas fa-check-circle mr-1"></i> Published
                                </span>
                            @elseif($event->status == 'draft')
                                <span class="px-3 py-1 text-xs rounded-full bg-yellow-100 text-yellow-800">
                                    <i class="fas fa-edit mr-1"></i> Draft
                                </span>
                            @else
                                <span class="px-3 py-1 text-xs rounded-full bg-red-100 text-red-800">
                                    <i class="fas fa-times-circle mr-1"></i> Cancelled
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm">
                                <div class="font-medium text-gray-900">
                                    {{ $event->ticketTypes->sum('sold') }} / {{ $event->ticketTypes->sum('quota') }}
                                </div>
                                @if($event->ticketTypes->sum('quota') > 0)
                                    <div class="w-full bg-gray-200 rounded-full h-2 mt-1">
                                        @php
                                            $percentage = min(100, ($event->ticketTypes->sum('sold') / $event->ticketTypes->sum('quota')) * 100);
                                        @endphp
                                        <div class="bg-indigo-600 h-2 rounded-full" style="width: {{ $percentage }}%"></div>
                                    </div>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-semibold text-gray-900">
                                Rp {{ number_format($event->ticketTypes->sum(function($tt) { return $tt->price * $tt->sold; }), 0, ',', '.') }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex space-x-2">
                                <a href="{{ route('organizer.events.show', $event) }}" 
                                   class="text-indigo-600 hover:text-indigo-900" title="Lihat Detail">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('organizer.events.edit', $event) }}" 
                                   class="text-yellow-600 hover:text-yellow-900" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button onclick="confirmDelete('{{ $event->id }}', '{{ $event->title }}')" 
                                        class="text-red-600 hover:text-red-900" title="Hapus">
                                    <i class="fas fa-trash"></i>
                                </button>
                                <form id="delete-form-{{ $event->id }}" 
                                      action="{{ route('organizer.events.destroy', $event) }}" 
                                      method="POST" class="hidden">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $events->links() }}
        </div>
    @else
        <div class="text-center py-12">
            <i class="fas fa-calendar-plus text-4xl text-gray-300 mb-4"></i>
            <p class="text-gray-500 mb-4">Belum ada event yang dibuat</p>
            <a href="{{ route('organizer.events.create') }}" 
               class="inline-flex items-center bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700">
                <i class="fas fa-plus mr-2"></i> Buat Event Pertama
            </a>
        </div>
    @endif
