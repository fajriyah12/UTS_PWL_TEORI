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

        <!-- Tabs / Sections -->
        <div class="space-y-12">
            
            <!-- Pending / Failed Transactions -->
            @if($pendingOrders->isNotEmpty())
            <div>
                <h2 class="text-xl font-bold text-slate-800 mb-4 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-yellow-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Menunggu Pembayaran / Gagal
                </h2>
                <div class="grid md:grid-cols-2 gap-6">
                    @foreach($pendingOrders as $order)
                        @include('transactions.partials.order-card', ['order' => $order])
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Successful Transactions -->
            <div>
                <h2 class="text-xl font-bold text-slate-800 mb-4 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Transaksi Berhasil
                </h2>
                
                @if($successfulOrders->isEmpty())
                    <p class="text-slate-500 italic">Belum ada transaksi berhasil.</p>
                @else
                    <div class="grid md:grid-cols-2 gap-6">
                        @foreach($successfulOrders as $order)
                            @include('transactions.partials.order-card', ['order' => $order])
                        @endforeach
                    </div>
                @endif
            </div>

        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
<script>
    function pay(snapToken) {
        window.snap.pay(snapToken, {
            onSuccess: function(result){
                alert("Pembayaran Berhasil! Mengalihkan...");
                window.location.href = "{{ route('checkout.success') }}" + "?order_id=" + result.order_id;
            },
            onPending: function(result){
                alert("Menunggu pembayaran...");
                location.reload();
            },
            onError: function(result){
                alert("Terjadi kesalahan pembayaran");
                console.log(result);
            },
            onClose: function(){
            }
        });
    }
</script>
@endpush
