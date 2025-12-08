@extends('organizer.layout')

@section('title', 'Statistik Penjualan')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold text-gray-800">Statistik Penjualan & Data Tiket</h1>
    </div>

    {{-- Charts Section --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        {{-- Weekly Sales --}}
        <div class="bg-white p-6 rounded-xl shadow-sm border">
            <h3 class="text-lg font-semibold mb-4 text-gray-700">Penjualan Mingguan (7 Hari Terakhir)</h3>
            <canvas id="weeklyChart"></canvas>
        </div>

        {{-- Monthly Sales --}}
        <div class="bg-white p-6 rounded-xl shadow-sm border">
            <h3 class="text-lg font-semibold mb-4 text-gray-700">Penjualan Bulanan (1 Tahun Terakhir)</h3>
            <canvas id="monthlyChart"></canvas>
        </div>
    </div>

    {{-- Events Data & Verification --}}
    <div class="bg-white rounded-xl shadow-sm border mt-8">
        <div class="p-6 border-b">
            <h3 class="text-lg font-semibold text-gray-800">Daftar Penjualan & Verifikasi Tiket per Event</h3>
            <p class="text-sm text-gray-500">Klik pada event untuk melihat detail pembeli dan data verifikasi.</p>
        </div>
        
        <div class="divide-y">
            @forelse($events as $event)
                <div x-data="{ open: false }" class="group">
                    {{-- Event Header --}}
                    <div class="p-4 hover:bg-gray-50 cursor-pointer flex justify-between items-center transition"
                         @click="open = !open">
                        <div class="flex items-center space-x-4">
                            <div class="flex-shrink-0 w-12 h-12 rounded bg-indigo-100 flex items-center justify-center text-indigo-600">
                                <i class="fas fa-calendar-alt"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-gray-800">{{ $event->title }}</h4>
                                <p class="text-sm text-gray-500">{{ $event->start_time->format('d M Y') }} • {{ $event->venue->name ?? 'Online' }}</p>
                            </div>
                        </div>
                        <div class="flex items-center space-x-6">
                            <div class="text-right">
                                <p class="text-xs text-gray-500">Tiket Terjual</p>
                                <p class="font-bold text-gray-800">
                                    {{ $event->ticketTypes->sum(function($tt){ return $tt->tickets->count(); }) }}
                                </p>
                            </div>
                            <i class="fas fa-chevron-down text-gray-400 transition-transform" :class="{'rotate-180': open}"></i>
                        </div>
                    </div>

                    {{-- Event Details (Collapsible) --}}
                    <div x-show="open" class="bg-gray-50 p-6 border-t" style="display: none;">
                        <div class="flex justify-between items-center mb-4">
                            <h5 class="font-semibold text-gray-700">Data Tiket & Verifikasi</h5>
                            <a href="{{ route('organizer.events.export-buyers', $event->id) }}" 
                               class="bg-green-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-green-700 flex items-center">
                                <i class="fas fa-file-excel mr-2"></i> Export Excel (CSV)
                            </a>
                        </div>
                        
                        <div class="overflow-x-auto bg-white rounded-lg border shadow-sm">
                            <table class="w-full text-sm text-left text-gray-500">
                                <thead class="text-xs text-gray-700 uppercase bg-gray-100">
                                    <tr>
                                        <th class="px-6 py-3">Serial & QR Token</th>
                                        <th class="px-6 py-3">Holder Name</th>
                                        <th class="px-6 py-3">Email</th>
                                        <th class="px-6 py-3">Tipe Tiket</th>
                                        <th class="px-6 py-3">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        // Collect all tickets for this event
                                        $allTickets = collect();
                                        foreach($event->ticketTypes as $tt) {
                                            foreach($tt->orderItems as $oi) {
                                                foreach($oi->tickets as $ticket) {
                                                    $allTickets->push($ticket);
                                                }
                                            }
                                        }
                                    @endphp

                                    @forelse($allTickets as $ticket)
                                        <tr class="border-b hover:bg-gray-50">
                                            <td class="px-6 py-4 font-mono font-medium text-indigo-600">
                                                <div>{{ $ticket->serial }}</div>
                                                <div class="text-xs text-gray-400 mt-1">{{ $ticket->qr_token }}</div>
                                            </td>
                                            <td class="px-6 py-4">{{ $ticket->holder_name }}</td>
                                            <td class="px-6 py-4">{{ $ticket->holder_email }}</td>
                                            <td class="px-6 py-4"><span class="bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded">{{ $ticket->ticketType->name }}</span></td>
                                            <td class="px-6 py-4">
                                                @if($ticket->status == 'valid')
                                                    <span class="text-green-600 font-bold">Valid</span>
                                                @elseif($ticket->status == 'checked_in')
                                                    <span class="text-gray-500">Checked In</span>
                                                @else
                                                    <span class="text-red-500">{{ ucfirst($ticket->status) }}</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="px-6 py-4 text-center">Belum ada tiket terjual.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @empty
                <div class="p-6 text-center text-gray-500">
                    Belum ada event.
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
{{-- Alpine.js for accordion --}}
<script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

<script>
    // Data from Controller
    const weekLabels = @json($weekDates);
    const weeklyData = @json(array_values($weeklySales));
    
    const monthLabels = @json($monthLabels);
    const monthlyData = @json(array_values($monthlySales));

    // Weekly Chart
    const ctxWeekly = document.getElementById('weeklyChart').getContext('2d');
    new Chart(ctxWeekly, {
        type: 'bar',
        data: {
            labels: weekLabels,
            datasets: [{
                label: 'Tiket Terjual',
                data: weeklyData,
                backgroundColor: 'rgba(79, 70, 229, 0.6)',
                borderColor: 'rgba(79, 70, 229, 1)',
                borderWidth: 1
            }]
        },
        options: { scales: { y: { beginAtZero: true } } }
    });

    // Monthly Chart
    const ctxMonthly = document.getElementById('monthlyChart').getContext('2d');
    new Chart(ctxMonthly, {
        type: 'line',
        data: {
            labels: monthLabels.reverse(), // Reverse to show order correctly if needed
            datasets: [{
                label: 'Tiket Terjual',
                data: monthlyData.reverse(),
                fill: true,
                backgroundColor: 'rgba(16, 185, 129, 0.2)',
                borderColor: 'rgba(16, 185, 129, 1)',
                tension: 0.3
            }]
        },
        options: { scales: { y: { beginAtZero: true } } }
    });
</script>
@endpush
