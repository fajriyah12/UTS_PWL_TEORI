@extends('layouts.app')

@section('content')
<div class="max-w-lg mx-auto text-center py-10">
    <h2 class="text-2xl font-semibold mb-4">Pembayaran Tiket</h2>
    <p class="text-gray-600 mb-6">Nomor Order: <strong>{{ $orderId }}</strong></p>
    <p class="text-xl font-bold text-indigo-700 mb-8">
        Total: Rp {{ number_format($total, 0, ',', '.') }}
    </p>

    <button id="pay-button"
        class="px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg font-semibold">
        Bayar Sekarang
    </button>

    <form id="finish-form" action="/" method="GET"></form>
</div>

<script src="https://app.sandbox.midtrans.com/snap/snap.js"
        data-client-key="{{ config('midtrans.client_key') }}"></script>
<script>
    document.getElementById('pay-button').addEventListener('click', function () {
        window.snap.pay('{{ $snapToken }}', {
            onSuccess: function(result){
                alert("Pembayaran Berhasil!");
                console.log(result);
                document.getElementById('finish-form').submit();
            },
            onPending: function(result){
                alert("Menunggu pembayaran...");
                console.log(result);
            },
            onError: function(result){
                alert("Terjadi kesalahan pembayaran");
                console.log(result);
            },
            onClose: function(){
                alert('Anda menutup popup tanpa menyelesaikan pembayaran.');
            }
        });
    });
</script>
@endsection
