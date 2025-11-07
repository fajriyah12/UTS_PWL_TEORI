@extends('layouts.app')

@section('content')
<div class="max-w-lg mx-auto px-6 py-8">
    <h2 class="text-2xl font-semibold text-gray-800 mb-6">Checkout Tiket</h2>

    <div class="bg-white p-6 rounded-xl shadow-md">
        <h3 class="font-bold text-lg">{{ $ticket->event->title }}</h3>
        <p class="text-sm text-gray-500 mb-4">{{ $ticket->name }} Ticket</p>
        <p class="text-indigo-700 font-semibold mb-6">
            Rp {{ number_format($ticket->price, 0, ',', '.') }} / tiket
        </p>

        <form method="POST" action="{{ route('checkout.store', $ticket->id) }}">
            @csrf
            <div class="mb-3">
                <label class="text-sm font-medium">Nama Lengkap</label>
                <input type="text" name="name" required class="w-full border rounded-lg px-3 py-2 mt-1">
            </div>

            <div class="mb-3">
                <label class="text-sm font-medium">Email</label>
                <input type="email" name="email" required class="w-full border rounded-lg px-3 py-2 mt-1">
            </div>

            <div class="mb-3">
                <label class="text-sm font-medium">Jumlah Tiket</label>
                <input type="number" name="quantity" min="1" max="{{ $ticket->per_user_limit }}" required
                       class="w-full border rounded-lg px-3 py-2 mt-1">
            </div>

            <button class="mt-4 w-full bg-indigo-600 hover:bg-indigo-700 text-white py-2 rounded-lg font-semibold">
                Konfirmasi & Bayar
            </button>
        </form>
    </div>
</div>
@endsection
