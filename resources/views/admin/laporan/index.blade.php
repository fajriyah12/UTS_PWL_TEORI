<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Laporan ORR'EA</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

  <style>
    body { font-family: 'Inter', sans-serif; }
  </style>
</head>

<body class="bg-gray-100">

<div class="flex h-screen">

    <!-- Sidebar -->
    <aside class="w-60 bg-white border-r border-gray-200 flex flex-col justify-between">
      <div>
        <div class="text-indigo-600 text-2xl font-bold p-6 border-b border-gray-200">
            ORR'EA
            <p class="text-sm font-normal">Laporan ORR'EA</p>
        </div>

        <nav class="mt-4">
          <a href="{{ route('admin.dashboard') }}" 
             class="flex items-center gap-2 px-6 py-3 hover:bg-gray-100 text-gray-700">
            <i class="fas fa-home"></i> Dashboard
          </a>

          <a href="{{ route('admin.datamaster') }}" 
             class="flex items-center gap-2 px-6 py-3 hover:bg-gray-100 text-gray-700">
            <i class="fas fa-database"></i> Data Master
          </a>

          <a href="{{ route('admin.laporan') }}" 
             class="flex items-center gap-2 px-6 py-3 bg-purple-100 text-purple-600 font-semibold">
            <i class="fas fa-chart-line"></i> Laporan
          </a>

          <a href="{{ route('admin.settingAdmin') }}" 
             class="flex items-center gap-2 px-6 py-3 hover:bg-gray-100 text-gray-700">
            <i class="fas fa-cog"></i> Pengaturan
          </a>
        </nav>
      </div>

      <a href="{{ route('logout') }}" 
         onclick="event.preventDefault(); document.getElementById('logout-form').submit();" 
         class="flex items-center gap-2 px-6 py-3 text-red-500 font-semibold hover:bg-red-50 border-t border-gray-200">
        <i class="fas fa-sign-out-alt"></i> Keluar
      </a>

      <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display:none;">
        @csrf
      </form>
    </aside>



    <!-- MAIN CONTENT -->
    <div class="p-6 w-full overflow-y-auto">

        <!-- TITLE + FILTER -->
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-2xl font-semibold">Laporan Penjualan Tiket</h2>
                <p class="text-gray-500 text-sm">Analitik lengkap penjualan tiket konser</p>
            </div>

            <form action="{{ route('admin.laporan') }}" method="GET" class="flex gap-3 items-center">

    <input type="date" name="start_date" value="{{ request('start_date') }}" 
           class="border rounded px-3 py-2">

    <input type="date" name="end_date" value="{{ request('end_date') }}" 
           class="border rounded px-3 py-2">

    <button class="px-4 py-2 bg-purple-600 text-white rounded">
        Filter
    </button>

</form>

        </div>

        <!-- STATISTIC CARDS -->
        <div class="grid grid-cols-4 gap-4 mb-6">

            <div class="p-4 rounded-lg border bg-white shadow-sm">
                <p class="text-gray-500 text-sm">Total Pendapatan</p>
                <h3 class="text-2xl font-bold">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h3>
            </div>

            <div class="p-4 rounded-lg border bg-white shadow-sm">
                <p class="text-gray-500 text-sm">Tiket Terjual</p>
                <h3 class="text-2xl font-bold">{{ $totalTickets }}</h3>
            </div>

            <div class="p-4 rounded-lg border bg-white shadow-sm">
                <p class="text-gray-500 text-sm">Total Event</p>
                <h3 class="text-2xl font-bold">{{ $totalEvents }}</h3>
            </div>

            <div class="p-4 rounded-lg border bg-white shadow-sm">
                <p class="text-gray-500 text-sm">Total Transaksi</p>
                <h3 class="text-2xl font-bold">{{ $totalOrders }}</h3>
            </div>

        </div>

        <!-- CHART -->
        <div class="bg-white border rounded-lg shadow-sm p-5 mb-6">
            <h3 class="font-semibold mb-3">Grafik Penjualan Tiket 2025</h3>
            <canvas id="ticketsChart" height="80"></canvas>
        </div>

        <!-- TABLE -->
        <div class="bg-white border rounded-lg shadow-sm p-5">

            <h3 class="font-semibold mb-4">Detail Transaksi Tiket</h3>

            <table class="min-w-full border-collapse">
                <thead>
                    <tr class="border-b bg-gray-50">
                        <th class="text-left py-3 px-2 text-sm font-medium">ID</th>
                        <th class="text-left py-3 px-2 text-sm font-medium">Event</th>
                        <th class="text-left py-3 px-2 text-sm font-medium">Name</th>
                        <th class="text-left py-3 px-2 text-sm font-medium">Email</th>
                        <th class="text-left py-3 px-2 text-sm font-medium">Jumlah Tiket</th>
                        <th class="text-left py-3 px-2 text-sm font-medium">Kode Tiket</th>
                        <th class="text-left py-3 px-2 text-sm font-medium">Total</th>
                        <th class="text-left py-3 px-2 text-sm font-medium">Status</th>
                        <th class="text-left py-3 px-2 text-sm font-medium">Tanggal</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($transactions as $tx)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="py-2 px-2 text-sm">{{ $tx->id }}</td>

                        <td class="py-2 px-2 text-sm">
                             {{ optional(optional(optional($tx->orderItems->first())->ticketType)->event)->title ?? '-' }}


                        </td>

                        <td class="py-2 px-2 text-sm">{{ $tx->buyer_name }}</td>
                        <td class="py-2 px-2 text-sm">{{ $tx->buyer_email }}</td>
                        <td class="py-2 px-2 text-sm">{{ $tx-> quantity}}</td>
                         <td class="py-2 px-2 text-sm">{{ $tx->order_code}}</td>
                        <td class="py-2 px-2 text-sm font-semibold">
                            Rp {{ number_format($tx->total_price, 0, ',', '.') }}
                        </td>
                        <td class="py-2 px-2 text-sm">
                    @if($tx->status == 'success')
                        <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs font-semibold">
                            Berhasil
                        </span>
                    @elseif($tx->status == 'pending')
                        <span class="px-3 py-1 bg-yellow-100 text-yellow-700 rounded-full text-xs font-semibold">
                            Pending
                        </span>
                    @else
                        <span class="px-3 py-1 bg-red-100 text-red-700 rounded-full text-xs font-semibold">
                            Gagal
                        </span>
                    @endif
                </td>

                        <td class="py-2 px-2 text-sm">
                            {{ $tx->created_at->format('d M Y') }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="mt-4">
                {{ $transactions->links() }}
            </div>

        </div>

    </div>

</div>


<!-- CHART SCRIPT -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const ctx = document.getElementById('ticketsChart').getContext('2d');

new Chart(ctx, {
    type: 'line',
    data: {
        labels: @json($chartLabels),
        datasets: [{
            label: 'Tiket Terjual',
            data: @json($chartData),
            borderWidth: 2,
            tension: 0.4,
            borderColor: '#7C3AED',
            backgroundColor: 'rgba(124, 58, 237, 0.15)'
        }]
    }
});
</script>

</body>
</html>
