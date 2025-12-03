@extends('layouts.app')

@section('content')
<div class="bg-slate-50 min-h-screen pb-20" x-data="{
    selectedTicketId: null,
    selectedTicketPrice: 0,
    selectedTicketName: '',
    quantity: 1,

    selectTicket(id, price, name) {
        console.log('Selected ticket:', id, price, name);
        this.selectedTicketId = id;
        this.selectedTicketPrice = price;
        this.selectedTicketName = name;
        this.quantity = 1;
    },

    increaseQty(limit) {
        if (this.quantity < limit) {
            this.quantity++;
        }
    },

    decreaseQty() {
        if (this.quantity > 1) {
            this.quantity--;
        } else {
            this.selectedTicketId = null;
            this.selectedTicketPrice = 0;
            this.selectedTicketName = '';
        }
    },

    checkout() {
        if (this.selectedTicketId) {
            window.location.href = `/checkout/${this.selectedTicketId}?quantity=${this.quantity}`;
        }
    }
}">
    
    <!-- Hero Section -->
    <div class="bg-white border-b border-slate-200">
        <div class="max-w-7xl mx-auto px-4 py-8 md:py-12">
            <div class="grid md:grid-cols-3 gap-8 items-start">
                <!-- Event Image -->
                <div class="md:col-span-2">
                    <div class="rounded-2xl overflow-hidden shadow-lg relative aspect-video group">
                        @if($event->image)
                            <img src="{{ asset('storage/' . $event->image) }}" alt="{{ $event->title }}" class="w-full h-full object-cover transition duration-700 group-hover:scale-105">
                        @else
                            <div class="w-full h-full bg-gradient-to-br from-indigo-600 to-purple-700 flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-20 w-20 text-white/30" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                        @endif
                        <div class="absolute top-4 right-4 bg-white/90 backdrop-blur px-3 py-1 rounded-full text-xs font-bold text-indigo-600 shadow-sm">
                            {{ $event->category ?? 'Event' }}
                        </div>
                    </div>
                </div>

                <!-- Event Info Sidebar (Desktop) -->
                <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm">
                    <h1 class="text-2xl font-bold text-slate-900 mb-4 leading-tight">{{ $event->title }}</h1>
                    
                    <div class="space-y-4">
                        <div class="flex items-start gap-3">
                            <div class="p-2 bg-indigo-50 rounded-lg text-indigo-600 shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm text-slate-500 font-medium">Tanggal</p>
                                <p class="text-slate-900 font-semibold">
                                    {{ $event->start_time->format('d M Y') }}
                                    @if($event->end_time)
                                        - {{ $event->end_time->format('d M Y') }}
                                    @endif
                                </p>
                            </div>
                        </div>

                        <div class="flex items-start gap-3">
                            <div class="p-2 bg-indigo-50 rounded-lg text-indigo-600 shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm text-slate-500 font-medium">Waktu</p>
                                <p class="text-slate-900 font-semibold">{{ $event->start_time->format('H:i') }} WIB</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-3">
                            <div class="p-2 bg-indigo-50 rounded-lg text-indigo-600 shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm text-slate-500 font-medium">Lokasi</p>
                                <p class="text-slate-900 font-semibold">{{ $event->location ?? 'Online' }}</p>
                                <a href="#" class="text-xs text-indigo-600 font-semibold hover:underline mt-1 inline-block">Petunjuk Arah</a>
                            </div>
                        </div>
                        
                        <div class="pt-6 border-t border-slate-100 mt-6">
                            <p class="text-sm text-slate-500 mb-1">Mulai Dari</p>
                            <p class="text-2xl font-bold text-indigo-600 mb-4">
                                Rp {{ number_format($event->ticketTypes->min('price'), 0, ',', '.') }}
                            </p>
                            <a href="#tickets" class="block w-full py-3 px-4 bg-indigo-600 hover:bg-indigo-700 text-white text-center font-bold rounded-xl shadow-lg shadow-indigo-200 transition transform active:scale-95">
                                Beli Sekarang
                            </a>
                        </div>

                        <div class="pt-4 border-t border-slate-100 mt-4">
                            <p class="text-sm text-slate-500 mb-1">Dibuat Oleh</p>
                            <div class="flex items-center gap-2">
                                <div class="w-8 h-8 bg-slate-200 rounded-full flex items-center justify-center text-xs font-bold text-slate-500">
                                    {{ substr($event->organizer->name ?? 'O', 0, 1) }}
                                </div>
                                <p class="text-slate-900 font-medium">{{ $event->organizer->name ?? 'Organizer' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 py-8 grid md:grid-cols-3 gap-8">
        
        <!-- Left Column: Description & Tickets -->
        <div class="md:col-span-2 space-y-8">
            
            <!-- Description -->
            <div class="bg-white rounded-2xl border border-slate-200 p-6 md:p-8 shadow-sm">
                <h2 class="text-xl font-bold text-slate-900 mb-4">Deskripsi</h2>
                <div class="prose prose-slate max-w-none text-slate-600">
                    {!! nl2br(e($event->description)) !!}
                </div>
            </div>

            <!-- Ticket Categories -->
            <div id="tickets" class="scroll-mt-24">
                <h2 class="text-xl font-bold text-slate-900 mb-4 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
                    </svg>
                    Kategori Tiket
                </h2>

                <div class="space-y-4">
                    @forelse($event->ticketTypes as $ticket)
                        <div class="bg-white rounded-xl border transition duration-200 overflow-hidden"
                             :class="selectedTicketId === '{{ $ticket->id }}' ? 'border-indigo-500 ring-1 ring-indigo-500 shadow-md' : 'border-slate-200 shadow-sm hover:border-indigo-300'">
                            
                            <div class="p-5 flex flex-col md:flex-row justify-between gap-4">
                                <div class="flex-1">
                                    <h3 class="font-bold text-lg text-slate-900">{{ $ticket->name }}</h3>
                                    <p class="text-sm text-slate-500 mt-1 line-clamp-2">{{ $ticket->description }}</p>
                                    
                                    <div class="flex items-center gap-4 mt-4">
                                        <div class="text-xs font-medium px-2.5 py-1 rounded bg-slate-100 text-slate-600 flex items-center gap-1">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            09:30 - 10:30 WIB
                                        </div>
                                        <div class="text-xs font-medium px-2.5 py-1 rounded bg-slate-100 text-slate-600 flex items-center gap-1">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                            </svg>
                                            {{ $event->location ?? 'Venue' }}
                                        </div>
                                    </div>
                                </div>

                                <div class="flex flex-col items-end justify-between min-w-[120px]">
                                    <p class="text-lg font-bold text-indigo-600">
                                        {{ $ticket->price == 0 ? 'Gratis' : 'Rp ' . number_format($ticket->price, 0, ',', '.') }}
                                    </p>

                                    <div class="mt-3">
                                        <div x-show="selectedTicketId !== '{{ $ticket->id }}'">
                                            @auth
                                                <button @click="selectTicket('{{ $ticket->id }}', {{ $ticket->price }}, '{{ $ticket->name }}')"
                                                        class="px-4 py-2 bg-indigo-50 text-indigo-600 text-sm font-bold rounded-lg hover:bg-indigo-100 transition">
                                                    Tambah
                                                </button>
                                            @else
                                                <a href="{{ route('login') }}" 
                                                   class="px-4 py-2 bg-indigo-50 text-indigo-600 text-sm font-bold rounded-lg hover:bg-indigo-100 transition inline-block">
                                                    Login untuk Membeli
                                                </a>
                                            @endauth
                                        </div>
                                        
                                        <div x-show="selectedTicketId === '{{ $ticket->id }}'" style="display: none;">
                                            <div class="flex items-center gap-3 bg-slate-50 rounded-lg p-1 border border-slate-200">
                                                <button @click="decreaseQty()" class="w-8 h-8 flex items-center justify-center bg-white rounded shadow-sm text-slate-600 hover:text-indigo-600 transition">
                                                    -
                                                </button>
                                                <span class="font-bold text-slate-900 w-4 text-center" x-text="quantity"></span>
                                                <button @click="increaseQty({{ $ticket->per_user_limit }})" class="w-8 h-8 flex items-center justify-center bg-white rounded shadow-sm text-slate-600 hover:text-indigo-600 transition">
                                                    +
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="p-8 text-center bg-slate-50 rounded-xl border border-dashed border-slate-300">
                            <p class="text-slate-500">Belum ada tiket tersedia untuk event ini.</p>
                        </div>
                    @endforelse
                </div>
            </div>

        </div>

        <!-- Right Column: Order Summary (Sticky) -->
        <div class="md:col-span-1">
            <div class="sticky top-24 space-y-6">
                
                <!-- Order Summary Card -->
                <div class="bg-white rounded-2xl border border-slate-200 shadow-lg overflow-hidden">
                    <div class="p-4 border-b border-slate-100 bg-slate-50">
                        <h3 class="font-bold text-slate-900">Detail Pesanan</h3>
                    </div>
                    
                    <div class="p-4">
                        <div class="flex gap-3 mb-4">
                            @if($event->image)
                                <img src="{{ asset('storage/' . $event->image) }}" class="w-16 h-16 rounded-lg object-cover bg-slate-100">
                            @else
                                <div class="w-16 h-16 rounded-lg bg-indigo-100 flex items-center justify-center text-indigo-500">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                            @endif
                            <div>
                                <h4 class="font-semibold text-sm text-slate-900 line-clamp-2">{{ $event->title }}</h4>
                                <p class="text-xs text-slate-500 mt-1">{{ $event->start_time->format('d M Y') }}</p>
                            </div>
                        </div>

                        <div class="border-t border-dashed border-slate-200 my-4"></div>

                        <div x-show="!selectedTicketId" class="text-center py-4 text-slate-500 text-sm italic">
                            Belum ada tiket dipilih
                        </div>

                        <div x-show="selectedTicketId" class="space-y-2">
                            <div class="flex justify-between text-sm">
                                <span class="text-slate-600" x-text="selectedTicketName"></span>
                                <span class="font-medium text-slate-900">x<span x-text="quantity"></span></span>
                            </div>
                            <div class="flex justify-between text-sm font-semibold text-slate-900 pt-2 border-t border-slate-100">
                                <span>Total</span>
                                <span>Rp <span x-text="(selectedTicketPrice * quantity).toLocaleString('id-ID')"></span></span>
                            </div>
                        </div>

                        <button @click="checkout()" 
                                :disabled="!selectedTicketId"
                                class="w-full mt-6 py-3 px-4 rounded-xl font-bold text-white transition transform active:scale-95 flex items-center justify-center gap-2"
                                :class="selectedTicketId ? 'bg-indigo-600 hover:bg-indigo-700 shadow-lg shadow-indigo-200' : 'bg-slate-300 cursor-not-allowed'">
                            Checkout
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Floating CTA (Mobile Only) -->
                <div class="md:hidden fixed bottom-0 left-0 right-0 bg-white border-t border-slate-200 p-4 shadow-[0_-4px_6px_-1px_rgba(0,0,0,0.1)] z-50">
                    <div class="flex justify-between items-center gap-4">
                        <div>
                            <p class="text-xs text-slate-500">Mulai Dari</p>
                            <p class="text-lg font-bold text-indigo-600">
                                Rp {{ number_format($event->ticketTypes->min('price'), 0, ',', '.') }}
                            </p>
                        </div>
                        <a href="#tickets" class="px-6 py-2.5 bg-indigo-600 text-white font-bold rounded-lg shadow-lg shadow-indigo-200">
                            Beli Sekarang
                        </a>
                    </div>
                </div>

            </div>
        </div>

    </div>
</div>

@endsection
