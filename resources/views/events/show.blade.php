@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto px-6 py-8">
    <div class="flex flex-col md:flex-row gap-8">
        {{-- Banner --}}
        <div class="md:w-2/3">
            <img src="{{ $event->banner_url ?? 'https://images.unsplash.com/photo-1507874457470-272b3c8d8ee2' }}"
                 class="rounded-2xl shadow-md w-full h-[400px] object-cover" alt="{{ $event->title }}">
        </div>

        {{-- Info Event --}}
        <div class="md:w-1/3">
            <h1 class="text-2xl font-bold text-gray-800">{{ $event->title }}</h1>
            <p class="text-gray-500 mt-1">{{ $event->venue->name ?? 'Lokasi tidak tersedia' }}</p>

            <p class="mt-4 text-sm text-gray-600">{{ $event->description }}</p>

            <div class="mt-6">
                <p class="text-sm text-gray-500">Tanggal</p>
                <p class="font-semibold">{{ $event->start_at->format('d M Y, H:i') }}</p>
            </div>
        </div>
    </div>

    {{-- Ticket Types --}}
    <div class="mt-10">
        <h2 class="text-xl font-semibold mb-4">Pilih Tiket</h2>
        <div class="grid md:grid-cols-3 gap-4">
            @foreach ($event->ticketTypes as $ticket)
                <div class="border rounded-lg p-4 shadow-sm hover:shadow-md transition">
                    <h3 class="font-semibold text-lg">{{ $ticket->name }}</h3>
                    <p class="text-gray-600 text-sm">Kapasitas: {{ $ticket->quota }} tiket</p>
                    <p class="text-indigo-600 font-bold text-xl mt-2">
                        Rp {{ number_format($ticket->price, 0, ',', '.') }}
                    </p>
                    @auth
    @if (in_array(Auth::user()->role, ['user', 'organizer']))
        <a href="{{ route('checkout.create', $ticket->id) }}"
           class="mt-4 block text-center bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 rounded-lg transition">
            Beli Sekarang
        </a>
    @else
        <button disabled
            class="mt-4 block w-full text-center bg-gray-300 text-gray-600 font-semibold py-2 rounded-lg cursor-not-allowed">
            Tidak dapat membeli tiket
        </button>
    @endif
@else
    <a href="{{ route('login') }}"
       class="mt-4 block text-center bg-gray-500 hover:bg-gray-600 text-white font-semibold py-2 rounded-lg transition">
        Login untuk Beli Tiket
    </a>
@endauth

                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
