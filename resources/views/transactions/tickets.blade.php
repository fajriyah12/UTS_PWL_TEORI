@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-slate-50 py-10">
    <div class="max-w-5xl mx-auto px-4">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-slate-900">Tiket Saya</h1>
            <p class="text-sm text-slate-500 mt-1">
                Kelola tiket event mendatang dan lihat riwayat event yang telah berlalu.
            </p>
        </div>

        <!-- Upcoming Events -->
        <div class="mb-10">
            <h2 class="text-xl font-semibold text-slate-800 mb-4 flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                Event Mendatang
            </h2>
            
            @if($upcomingTickets->isEmpty())
                <div class="bg-white rounded-xl border border-slate-200 p-8 text-center">
                    <div class="mx-auto w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
                        </svg>
                    </div>
                    <p class="text-slate-600 font-medium">Belum ada tiket aktif</p>
                    <p class="text-slate-500 text-sm mt-1">Anda belum memiliki tiket untuk event mendatang.</p>
                    <a href="{{ route('events.index') }}" class="mt-4 inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        Cari Event
                    </a>
                </div>
            @else
                <div class="grid md:grid-cols-2 gap-4">
                    @foreach($upcomingTickets as $ticket)
                        <div class="bg-white rounded-xl border border-slate-200 shadow-sm hover:shadow-md transition overflow-hidden flex flex-col">
                            <div class="p-5 flex-1">
                                <div class="flex justify-between items-start mb-2">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                                        {{ $ticket->ticketType->name }}
                                    </span>
                                    <span class="text-xs text-slate-500 font-mono">
                                        #{{ $ticket->serial }}
                                    </span>
                                </div>
                                <h3 class="text-lg font-bold text-slate-900 mb-1 line-clamp-2">
                                    {{ $ticket->ticketType->event->title }}
                                </h3>
                                <div class="space-y-1 mt-3">
                                    <p class="text-sm text-slate-600 flex items-center gap-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        {{ \Carbon\Carbon::parse($ticket->ticketType->event->start_time)->format('d M Y, H:i') }}
                                    </p>
                                    <p class="text-sm text-slate-600 flex items-center gap-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                        {{ $ticket->ticketType->event->location }}
                                    </p>
                                </div>
                            </div>
                            <div class="bg-slate-50 px-5 py-3 border-t border-slate-100 flex justify-between items-center">
                                <span class="text-xs text-slate-500">
                                    Dipesan {{ $ticket->created_at->diffForHumans() }}
                                </span>
                                <a href="{{ route('transactions.ticket.show', $ticket) }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-800 flex items-center gap-1">
                                    Lihat E-Ticket
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                    </svg>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Past Events -->
        <div>
            <h2 class="text-xl font-semibold text-slate-800 mb-4 flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Riwayat Event
            </h2>

            @if($pastTickets->isEmpty())
                <p class="text-slate-500 text-sm italic">Belum ada riwayat event yang diikuti.</p>
            @else
                <div class="bg-white rounded-xl border border-slate-200 divide-y divide-slate-100">
                    @foreach($pastTickets as $ticket)
                        <div class="p-4 flex flex-col sm:flex-row sm:items-center justify-between gap-4 hover:bg-slate-50 transition">
                            <div class="flex items-start gap-4">
                                <div class="w-12 h-12 bg-slate-100 rounded-lg flex items-center justify-center shrink-0 text-slate-400 font-bold text-xs uppercase">
                                    {{ \Carbon\Carbon::parse($ticket->ticketType->event->start_time)->format('M') }}
                                    <br>
                                    {{ \Carbon\Carbon::parse($ticket->ticketType->event->start_time)->format('d') }}
                                </div>
                                <div>
                                    <h4 class="font-semibold text-slate-900">{{ $ticket->ticketType->event->title }}</h4>
                                    <p class="text-sm text-slate-500">{{ $ticket->ticketType->name }} &middot; {{ $ticket->ticketType->event->location }}</p>
                                </div>
                            </div>
                            <div class="flex items-center justify-between sm:justify-end gap-4 w-full sm:w-auto">
                                <span class="px-2 py-1 bg-slate-100 text-slate-600 text-xs rounded">Selesai</span>
                                <a href="{{ route('transactions.ticket.show', $ticket) }}" class="text-sm text-slate-500 hover:text-indigo-600">
                                    Detail
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
