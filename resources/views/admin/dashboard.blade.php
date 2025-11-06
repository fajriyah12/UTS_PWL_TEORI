<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl">Admin Dashboard</h2></x-slot>
    <div class="py-6 space-y-3">
        <p>Total Event: {{ $totalEvent }}</p>
        <p>Total Order: {{ $totalOrder }}</p>
        <p>Total Pendapatan: Rp{{ number_format($income, 0, ',', '.') }}</p>
    </div>
</x-app-layout>
