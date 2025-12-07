@extends('organizer.layout')

@section('title', $event->title)

@section('content')
<div class="mb-8">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">{{ $event->title }}</h1>
            <p class="text-gray-600">Detail dan statistik event</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('organizer.events.edit', $event) }}" 
               class="bg-yellow-600 text-white px-4 py-2 rounded-lg hover:bg-yellow-700">
                <i class="fas fa-edit mr-2"></i> Edit
            </a>
            <a href="{{ route('organizer.events.index') }}" 
               class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700">
                <i class="fas fa-arrow-left mr-2"></i> Kembali
            </a>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Event Info -->
    <div class="lg:col-span-2">
        <div class="bg-white rounded-xl shadow p-6 mb-6">
            <!-- Banner Image -->
            @if($event->image)
            <div class="mb-6">
                <img src="{{ asset('storage/' . $event->image) }}" 
                     alt="{{ $event->title }}"
                     class="w-full h-64 object-cover rounded-lg">
            </div>
            @endif
            
            <!-- Event Details -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h3 class="text-lg font-semibold mb-4 text-gray-800">Informasi Event</h3>
                    <div class="space-y-3">
                        <div class="flex items-center">
                            <i class="fas fa-calendar-alt text-gray-400 mr-3"></i>
                            <div>
                                <p class="text-sm text-gray-500">Tanggal</p>
                                <p class="font-medium">{{ $event->start_time->format('d M Y') }}</p>
                            </div>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-clock text-gray-400 mr-3"></i>
                            <div>
                                <p class="text-sm text-gray-500">Waktu</p>
                                <p class="font-medium">
                                    {{ $event->start_time->format('H:i') }} - {{ $event->end_time->format('H:i') }}
                                </p>
                            </div>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-map-marker-alt text-gray-400 mr-3"></i>
                            <div>
                                <p class="text-sm text-gray-500">Lokasi</p>
                                <p class="font-medium">{{ $event->location }}</p>
                            </div>
                        </div>
                        @if($event->venue)
                        <div class="flex items-center">
                            <i class="fas fa-building text-gray-400 mr-3"></i>
                            <div>
                                <p class="text-sm text-gray-500">Venue</p>
                                <p class="font-medium">{{ $event->venue->name }}</p>
                            </div>
                        </div>
                        @endif
                        <div class="flex items-center">
                            <i class="fas fa-tag text-gray-400 mr-3"></i>
                            <div>
                                <p class="text-sm text-gray-500">Status</p>
                                <span class="px-3 py-1 text-xs rounded-full 
                                    {{ $event->status == 'published' ? 'bg-green-100 text-green-800' : 
                                       ($event->status == 'draft' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                    {{ ucfirst($event->status) }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div>
                    <h3 class="text-lg font-semibold mb-4 text-gray-800">Statistik Tiket</h3>
                    <div class="space-y-3">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-500">Total Kuota</p>
                                <p class="font-medium">{{ $event->ticketTypes->sum('quota') }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Terjual</p>
                                <p class="font-medium">{{ $event->ticketTypes->sum('sold') }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Tersedia</p>
                                <p class="font-medium">{{ $event->ticketTypes->sum('quota') - $event->ticketTypes->sum('sold') }}</p>
                            </div>
                        </div>
                        
                        <!-- Progress Bar -->
                        <div class="mt-4">
                            <div class="flex justify-between text-sm mb-1">
                                <span>Penjualan</span>
                                <span>
                                    @php
                                        $totalQuota = $event->ticketTypes->sum('quota');
                                        $totalSold = $event->ticketTypes->sum('sold');
                                        $percentage = $totalQuota > 0 ? ($totalSold / $totalQuota) * 100 : 0;
                                    @endphp
                                    {{ number_format($percentage, 1) }}%
                                </span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-indigo-600 h-2 rounded-full" 
                                     style="width: {{ min(100, $percentage) }}%"></div>
                            </div>
                        </div>
                        
                        <!-- Revenue -->
                        <div class="mt-6 p-4 bg-gray-50 rounded-lg">
                            <p class="text-sm text-gray-500">Total Pendapatan</p>
                            <p class="text-2xl font-bold text-gray-800">
                                Rp {{ number_format($event->ticketTypes->sum(function($tt) { 
                                    return $tt->price * $tt->sold; 
                                }), 0, ',', '.') }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Description -->
            <div class="mt-8 pt-6 border-t border-gray-200">
                <h3 class="text-lg font-semibold mb-4 text-gray-800">Deskripsi</h3>
                <div class="prose max-w-none">
                    {!! nl2br(e($event->description)) !!}
                </div>
            </div>
        </div>
    </div>
    
    <!-- Ticket Types & Quick Actions -->
    <div class="space-y-6">
        <!-- Ticket Types -->
        <div class="bg-white rounded-xl shadow p-6">
            <h3 class="text-lg font-semibold mb-4 text-gray-800">Jenis Tiket</h3>
            
            <div class="space-y-4">
                @foreach($event->ticketTypes as $ticketType)
                <div class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50">
                    <div class="flex justify-between items-start mb-2">
                        <div>
                            <h4 class="font-semibold">{{ $ticketType->name }}</h4>
                            <p class="text-sm text-gray-500">{{ $ticketType->description }}</p>
                        </div>
                        <span class="font-bold text-gray-800">
                            Rp {{ number_format($ticketType->price, 0, ',', '.') }}
                        </span>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-2 text-sm">
                        <div>
                            <p class="text-gray-500">Kuota</p>
                            <p>{{ $ticketType->quota }}</p>
                        </div>
                        <div>
                            <p class="text-gray-500">Terjual</p>
                            <p>{{ $ticketType->sold }}</p>
                        </div>
                        <div>
                            <p class="text-gray-500">Sisa</p>
                            <p>{{ $ticketType->remainingQuota }}</p>
                        </div>
                        <div>
                            <p class="text-gray-500">Maks/User</p>
                            <p>{{ $ticketType->per_user_limit }}</p>
                        </div>
                    </div>
                    
                    @if($ticketType->sales_start && $ticketType->sales_end)
                    <div class="mt-2 text-sm text-gray-500">
                        <i class="far fa-clock mr-1"></i>
                        Penjualan: {{ $ticketType->sales_start->format('d M') }} - {{ $ticketType->sales_end->format('d M') }}
                    </div>
                    @endif
                    
                    <!-- Progress -->
                    <div class="mt-3">
                        <div class="w-full bg-gray-200 rounded-full h-1.5">
                            @php
                                $ticketPercentage = $ticketType->quota > 0 ? 
                                    ($ticketType->sold / $ticketType->quota) * 100 : 0;
                            @endphp
                            <div class="bg-{{ $ticketPercentage > 80 ? 'red' : ($ticketPercentage > 50 ? 'yellow' : 'green') }}-500 h-1.5 rounded-full" 
                                 style="width: {{ min(100, $ticketPercentage) }}%"></div>
                        </div>
                    </div>
                </div>
                @endforeach
                
                @if($event->ticketTypes->isEmpty())
                <div class="text-center py-4 text-gray-500">
                    <i class="fas fa-ticket-alt text-2xl mb-2"></i>
                    <p>Belum ada jenis tiket</p>
                </div>
                @endif
            </div>
        </div>
        
        <!-- Quick Actions -->
        <div class="bg-white rounded-xl shadow p-6">
            <h3 class="text-lg font-semibold mb-4 text-gray-800">Quick Actions</h3>
            <div class="space-y-3">
                <a href="{{ route('organizer.events.edit', $event) }}" 
                   class="flex items-center p-3 border border-gray-200 rounded-lg hover:bg-gray-50">
                    <i class="fas fa-edit text-yellow-600 mr-3"></i>
                    <div>
                        <p class="font-medium">Edit Event</p>
                        <p class="text-sm text-gray-500">Ubah informasi event</p>
                    </div>
                </a>
                
                <a href="{{ route('events.show', $event->slug) }}" target="_blank"
                   class="flex items-center p-3 border border-gray-200 rounded-lg hover:bg-gray-50">
                    <i class="fas fa-external-link-alt text-indigo-600 mr-3"></i>
                    <div>
                        <p class="font-medium">Lihat di Publik</p>
                        <p class="text-sm text-gray-500">Lihat halaman event</p>
                    </div>
                </a>
                
                <button onclick="copyEventUrl()" 
                        class="w-full flex items-center p-3 border border-gray-200 rounded-lg hover:bg-gray-50">
                    <i class="fas fa-link text-green-600 mr-3"></i>
                    <div class="text-left">
                        <p class="font-medium">Salin Link Event</p>
                        <p class="text-sm text-gray-500">Bagikan ke media sosial</p>
                    </div>
                </button>
            </div>
        </div>
        
        <!-- Danger Zone -->
        <div class="bg-red-50 border border-red-200 rounded-xl shadow p-6">
            <h3 class="text-lg font-semibold mb-4 text-red-800">Zona Berbahaya</h3>
            
            @if($event->status == 'published')
            <form action="{{ route('organizer.events.update', $event) }}" method="POST" class="mb-4">
                @csrf
                @method('PUT')
                <input type="hidden" name="status" value="cancelled">
                <button type="submit" 
                        onclick="return confirm('Batalkan event ini? Tiket yang sudah terjual akan direfund.')"
                        class="w-full text-center bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700">
                    <i class="fas fa-times-circle mr-2"></i> Batalkan Event
                </button>
            </form>
            @endif
            
            <form action="{{ route('organizer.events.destroy', $event) }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" 
                        onclick="return confirm('Hapus permanen event ini? Tindakan ini tidak dapat dibatalkan.')"
                        class="w-full text-center bg-gray-800 text-white px-4 py-2 rounded-lg hover:bg-gray-900">
                    <i class="fas fa-trash mr-2"></i> Hapus Permanen
                </button>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
function copyEventUrl() {
    const url = "{{ route('events.show', $event->slug) }}";
    navigator.clipboard.writeText(url).then(() => {
        alert('Link event berhasil disalin!');
    });
}
</script>
@endpush
@endsection