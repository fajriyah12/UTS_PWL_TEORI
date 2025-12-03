@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-slate-100 py-12 flex items-center justify-center px-4">
    <div class="max-w-md w-full bg-white rounded-3xl shadow-2xl overflow-hidden relative">
        <!-- Decorative Circle -->
        <div class="absolute -top-20 -left-20 w-64 h-64 bg-indigo-500 rounded-full mix-blend-multiply filter blur-xl opacity-20 animate-blob"></div>
        <div class="absolute -bottom-20 -right-20 w-64 h-64 bg-purple-500 rounded-full mix-blend-multiply filter blur-xl opacity-20 animate-blob animation-delay-2000"></div>

        <!-- Header Image -->
        <div class="h-48 bg-indigo-600 relative">
            @if($ticket->ticketType->event->image)
                <img src="{{ asset('storage/' . $ticket->ticketType->event->image) }}" alt="Event Image" class="w-full h-full object-cover opacity-50">
            @else
                <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-indigo-600 to-purple-700">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-20 w-20 text-white/30" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
                    </svg>
                </div>
            @endif
            <div class="absolute bottom-0 left-0 right-0 p-6 bg-gradient-to-t from-black/80 to-transparent">
                <h1 class="text-2xl font-bold text-white leading-tight">
                    {{ $ticket->ticketType->event->title ?? 'Nama Event' }}
                </h1>
                <p class="text-indigo-200 text-sm mt-1 flex items-center gap-1">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    {{ $ticket->ticketType->event->location ?? 'Lokasi Event' }}
                </p>
            </div>
        </div>

        <!-- Ticket Body -->
        <div class="p-6 relative">
            <!-- Cutout Circles -->
            <div class="absolute top-0 left-0 -ml-4 -mt-3 w-8 h-8 bg-slate-100 rounded-full"></div>
            <div class="absolute top-0 right-0 -mr-4 -mt-3 w-8 h-8 bg-slate-100 rounded-full"></div>
            
            <!-- Dashed Line -->
            <div class="border-t-2 border-dashed border-slate-200 mb-6"></div>

            <div class="text-center mb-8">
                <p class="text-sm text-slate-500 uppercase tracking-wider mb-1">Tipe Tiket</p>
                <p class="text-2xl font-bold text-indigo-600">{{ $ticket->ticketType->name ?? 'Regular' }}</p>
            </div>

            <!-- Visitor Data -->
            <div class="mb-8 bg-slate-50 rounded-xl p-4 border border-slate-100">
                <p class="text-xs text-slate-400 uppercase text-center mb-3 tracking-wider">Data Pengunjung</p>
                <div class="space-y-2">
                    <div class="flex justify-between text-sm">
                        <span class="text-slate-500">Nama</span>
                        <span class="font-semibold text-slate-800">{{ $ticket->holder_name ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-slate-500">Email</span>
                        <span class="font-semibold text-slate-800">{{ $ticket->holder_email ?? '-' }}</span>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4 mb-8">
                <div class="text-center">
                    <p class="text-xs text-slate-400 uppercase">Tanggal</p>
                    <p class="font-semibold text-slate-800">
                        {{ \Carbon\Carbon::parse($ticket->ticketType->event->start_time)->format('d M Y') }}
                    </p>
                </div>
                <div class="text-center">
                    <p class="text-xs text-slate-400 uppercase">Waktu</p>
                    <p class="font-semibold text-slate-800">
                        {{ \Carbon\Carbon::parse($ticket->ticketType->event->start_time)->format('H:i') }} WIB
                    </p>
                </div>
            </div>

            <!-- QR Code Area -->
            <div class="flex flex-col items-center justify-center bg-white p-4 rounded-xl border-2 border-slate-100 mb-6">
                {!! QrCode::size(200)->color(79, 70, 229)->generate($ticket->qr_token) !!}
                <p class="mt-3 text-xs font-mono text-slate-400 tracking-widest">{{ $ticket->qr_token }}</p>
            </div>

            <p class="text-center text-xs text-slate-400 mb-6">
                Tunjukkan QR Code ini kepada petugas saat memasuki venue. <br>
                Pastikan kecerahan layar HP Anda maksimal.
            </p>

            <div class="flex justify-center">
                <a href="{{ route('transactions.tickets') }}" class="inline-flex items-center gap-2 text-indigo-600 hover:text-indigo-800 font-medium text-sm transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Kembali ke Tiket Saya
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
