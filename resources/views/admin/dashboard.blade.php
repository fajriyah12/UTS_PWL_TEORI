<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard ORR'EA</title>
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
        <div class="text-indigo-600 text-2xl font-bold p-6 border-b border-gray-200">ORR'EA
            <p class="text-sm font-normal">Admin Portal</p>
        </div>
        <nav class="mt-4">
          <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2 px-6 py-3 bg-purple-100 text-purple-600 font-medium">
            <i class="fas fa-home"></i> Dashboard
          </a>
          <a href="{{ route('admin.datamaster') }}" class="flex items-center gap-2 px-6 py-3 text-gray-700 hover:bg-gray-100">
            <i class="fas fa-database"></i> Data Master
          </a>
          <a href="{{ route('admin.laporan') }}" class="flex items-center gap-2 px-6 py-3 text-gray-700 hover:bg-gray-100">
            <i class="fas fa-chart-line"></i> Laporan
          </a>
          <a href="{{ route('admin.settingAdmin') }}" class="flex items-center gap-2 px-6 py-3 text-gray-700 hover:bg-gray-100">
            <i class="fas fa-cog"></i> Pengaturan
          </a>
        </nav>
      </div>
      <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="flex items-center gap-2 px-6 py-3 text-red-500 font-semibold hover:bg-red-50 border-t border-gray-200">
        <i class="fas fa-sign-out-alt"></i> Keluar
      </a>
      <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
      </form>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 p-6 overflow-y-auto">
      <!-- Header -->
      <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold">Dashboard</h1>
        <div class="flex items-center gap-4">
          <i class="fas fa-cog text-gray-500 text-xl"></i>
          <div class="flex items-center gap-2">
            <div class="w-8 h-8 rounded-full bg-gray-300"></div>
            <span class="font-semibold">{{ auth()->user()->name }}</span>
          </div>
        </div>
      </div>

      <!-- Stats cards with real database data -->
      <div class="grid grid-cols-4 gap-6 mb-6">
        <div class="bg-white p-4 rounded-lg shadow-sm border-t-4 border-purple-400">
          <p class="text-gray-600">Acara Aktif</p>
          <h2 class="text-2xl font-bold mt-1">{{ $totalEvents }}</h2>
        </div>
        <div class="bg-white p-4 rounded-lg shadow-sm border-t-4 border-green-400">
          <p class="text-gray-600">Tiket Terjual</p>
          <h2 class="text-2xl font-bold mt-1">{{ number_format($totalTicketsSold) }}</h2>
        </div>
        <div class="bg-white p-4 rounded-lg shadow-sm border-t-4 border-blue-400">
          <p class="text-gray-600">Total Pendapatan</p>
          <h2 class="text-2xl font-bold mt-1">Rp {{ number_format($totalRevenue) }}</h2>
        </div>
        <div class="bg-white p-4 rounded-lg shadow-sm border-t-4 border-orange-400">
          <p class="text-gray-600">Penyelenggara</p>
          <h2 class="text-2xl font-bold mt-1">{{ $totalOrganizers }}</h2>
        </div>
      </div>

      <!-- Chart & Table -->
      <div class="grid grid-cols-2 gap-6">
        <!-- Chart -->
        <div class="bg-white p-6 rounded-lg shadow-sm">
          <h2 class="font-semibold mb-4">Tren Pembelian Tiket</h2>
          <canvas id="ticketChart" height="150"></canvas>
        </div>

        <!-- Table -->
        <div class="bg-white p-6 rounded-lg shadow-sm">
          <h2 class="font-semibold mb-4">Transaksi Terbaru</h2>
          <table class="w-full text-left">
            <thead>
              <tr class="border-b">
                <th class="py-2">ID</th>
                <th class="py-2">Event</th>
                <th class="py-2">Nama</th>
                <th class="py-2">Status</th>
              </tr>
            </thead>
            <tbody>
              <!-- Display recent transactions from database -->
              @forelse($recentTransactions as $transaction)
              <tr class="border-b">
                <td class="py-2">{{ $transaction->id }}</td>
                <td>
                  @if($transaction->orderItems->first())
                    {{ $transaction->orderItems->first()->ticketType->event->title }}
                  @else
                    -
                  @endif
                </td>
                <td>{{ $transaction->buyer_name }}</td>
                <td><span class="bg-green-100 text-green-600 px-2 py-1 rounded text-sm">{{ ucfirst($transaction->status) }}</span></td>
              </tr>
              @empty
              <tr>
                <td class="py-2 text-center text-gray-500" colspan="4">Tidak ada transaksi</td>
              </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </main>
  </div>

  <!-- Font Awesome & Chart.js -->
  <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script>
    const ctx = document.getElementById('ticketChart').getContext('2d');
    new Chart(ctx, {
      type: 'line',
      data: {
        labels: {!! json_encode($chartLabels) !!},
        datasets: [{
          label: 'Ticket Sales',
          data: {!! json_encode($chartData) !!},
          borderColor: '#A855F7',
          backgroundColor: 'rgba(168,85,247,0.2)',
          tension: 0.4,
          fill: true,
          pointRadius: 5,
          pointBackgroundColor: '#A855F7'
        }]
      },
      options: {
        scales: {
          y: { beginAtZero: true, grid: { color: '#f3f4f6' } },
          x: { grid: { display: false } }
        },
        plugins: {
          legend: { display: false }
        }
      }
    });
  </script>
</body>
</html>
