@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-slate-50 py-10">
    <div class="max-w-7xl mx-auto px-4">
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-3xl font-bold text-slate-900">Riwayat Transaksi</h1>
                <p class="text-sm text-slate-500 mt-1">
                    Daftar pembelian tiket event Anda.
                </p>
            </div>
        </div>

        <div class="grid md:grid-cols-2 gap-6">
            @forelse($orders as $order)
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden flex flex-col">
                    <div class="px-6 py-4 bg-gradient-to-r from-indigo-500/5 via-indigo-500/10 to-indigo-500/5 flex justify-between items-start gap-4">
                        <div>
                            <p class="text-xs font-semibold tracking-wide text-indigo-600 uppercase">Order Code</p>
                            <p class="text-sm font-mono text-slate-900 font-bold">{{ $order->order_code }}</p>
                            <p class="mt-1 text-xs text-slate-500">
                                {{ $order->created_at->format('d M Y H:i') }}
                            </p>
                        </div>
                        <div class="text-right">
                            <p class="text-xs uppercase tracking-wide text-slate-500">Total</p>
                            <p class="text-lg font-semibold text-slate-900">
                                Rp {{ number_format($order->total_price, 0, ',', '.') }}
                            </p>
                            <span class="inline-flex items-center mt-1 px-2 py-0.5 rounded-full text-xs font-medium bg-emerald-50 text-emerald-700 border border-emerald-100">
                                Berhasil
                            </span>
                        </div>
                    </div>

                    <div class="px-6 py-5 bg-slate-50 flex-1">
                        @if($order->orderItems->isEmpty())
                            <p class="text-sm text-slate-500">
                                Tiket sedang diproses.
                            </p>
                        @else
                            <div class="space-y-4">
                                @foreach($order->orderItems as $item)
                                    @foreach($item->tickets as $ticket)
                                        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-3 flex items-center gap-3">
                                            <div class="shrink-0">
                                                {!! \SimpleSoftwareIO\QrCode\Facades\QrCode::size(50)->generate($ticket->qr_token) !!}
                                            </div>

                                            <div class="flex-1 min-w-0">
                                                <p class="text-sm font-semibold text-slate-900 truncate">
                                                    {{ $item->ticketType->event->title ?? '-' }}
                                                </p>
                                                <p class="text-xs text-slate-500">
                                                    {{ $item->ticketType->name ?? '-' }}
                                                </p>
                                                <p class="text-xs text-slate-400 font-mono truncate">
                                                    {{ $ticket->serial }}
                                                </p>
                                            </div>

                                            <div>
                                                <a href="{{ route('transactions.ticket.show', $ticket) }}"
                                                   class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-indigo-50 text-indigo-600 hover:bg-indigo-100 transition"
                                                   title="Lihat E-Ticket">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                    </svg>
                                                </a>
                                            </div>
                                        </div>
                                    @endforeach
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            @empty
                <div class="col-span-full mt-10 text-center text-slate-500">
                    <p class="text-sm">Belum ada transaksi tiket yang tercatat.</p>
                </div>
            @endforelse
        </div>

        <div class="mt-8">
            {{ $orders->links() }}
        </div>
    </div>
</div>
@endsection
