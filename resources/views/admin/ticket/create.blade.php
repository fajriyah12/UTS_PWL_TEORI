<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Data Master Management - ORR'EA</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
  <style>
    body { font-family: 'Inter', sans-serif; }
    .tab-active {
      color: #7C3AED;
      border-bottom: 2px solid #7C3AED;
      font-weight: 600;
    }
  </style>
</head>
<body class="bg-gray-100">

  <div class="flex h-screen">

<div class="flex min-h-screen bg-gray-50">
    <!-- Sidebar -->
    <aside class="w-60 bg-white border-r border-gray-200 flex flex-col justify-between">
      <div>
        <div class="text-indigo-600 text-2xl font-bold p-6 border-b border-gray-200">ORR'EA
            <p class="text-sm font-normal">Admin Portal</p>
        </div>
        <nav class="mt-4">
          <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2 px-6 py-3 text-gray-700 hover:bg-gray-100">
            <i class="fas fa-home"></i> Dashboard
          </a>
          <a href="{{ route('admin.datamaster') }}" class="flex items-center gap-2 px-6 py-3 bg-purple-100 text-purple-600 font-medium">
            <i class="fas fa-database"></i> Data Master
          </a>
          <a href="#" class="flex items-center gap-2 px-6 py-3 text-gray-700 hover:bg-gray-100">
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
    <div class="flex-1 overflow-auto">
        <div class="max-w-2xl mx-auto p-8">
            <!-- Header -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900">Tambah Tiket Baru</h1>
                <p class="text-gray-600 mt-2">Buat tiket untuk acara Anda</p>
            </div>

            <!-- Form Card -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-8">
                <form action="{{ route('admin.store-ticket') }}" method="POST" class="space-y-6">
                    @csrf

                    <!-- Event Selection -->
                    <div>
                        <label for="event_id" class="block text-sm font-semibold text-gray-900 mb-2">
                            Pilih Acara <span class="text-red-500">*</span>
                        </label>
                        <select id="event_id" name="event_id" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('event_id') border-red-500 @enderror">
                            <option value="">-- Pilih Acara --</option>
                            @foreach($events as $event)
                                <option value="{{ $event->id }}" {{ old('event_id') == $event->id ? 'selected' : '' }}>
                                    {{ $event->title }}
                                </option>
                            @endforeach
                        </select>
                        @error('event_id')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Ticket Type Name -->
                    <div>
                        <label for="name" class="block text-sm font-semibold text-gray-900 mb-2">
                            Nama Tiket <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="name" name="name" placeholder="Contoh: Regular, VIP, Festival" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('name') border-red-500 @enderror" value="{{ old('name') }}">
                        @error('name')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Price -->
                    <div>
                        <label for="price" class="block text-sm font-semibold text-gray-900 mb-2">
                            Harga (Rp) <span class="text-red-500">*</span>
                        </label>
                        <input type="number" id="price" name="price" placeholder="Contoh: 308416" min="0" step="1" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('price') border-red-500 @enderror" value="{{ old('price') }}">
                        @error('price')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Quota -->
                    <div>
                        <label for="quota" class="block text-sm font-semibold text-gray-900 mb-2">
                            Kapasitas Tiket <span class="text-red-500">*</span>
                        </label>
                        <input type="number" id="quota" name="quota" placeholder="Contoh: 202" min="1" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('quota') border-red-500 @enderror" value="{{ old('quota') }}">
                        @error('quota')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Per User Limit -->
                    <div>
                        <label for="per_user_limit" class="block text-sm font-semibold text-gray-900 mb-2">
                            Batas Tiket per Pengguna (Opsional)
                        </label>
                        <input type="number" id="per_user_limit" name="per_user_limit" placeholder="Contoh: 4" min="1" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('per_user_limit') border-red-500 @enderror" value="{{ old('per_user_limit', 4) }}">
                        <p class="text-gray-500 text-sm mt-1">Maksimal tiket yang bisa dibeli satu pengguna</p>
                        @error('per_user_limit')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Sales Period (Optional) -->
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="sales_start" class="block text-sm font-semibold text-gray-900 mb-2">
                                Penjualan Dimulai (Opsional)
                            </label>
                            <input type="datetime-local" id="sales_start" name="sales_start" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" value="{{ old('sales_start') }}">
                        </div>
                        <div>
                            <label for="sales_end" class="block text-sm font-semibold text-gray-900 mb-2">
                                Penjualan Berakhir (Opsional)
                            </label>
                            <input type="datetime-local" id="sales_end" name="sales_end" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" value="{{ old('sales_end') }}">
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex gap-3 pt-6 border-t border-gray-200">
                        <a href="{{ route('admin.datamaster') }}" class="flex-1 px-4 py-2.5 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition-colors text-center">
                            Batal
                        </a>
                        <button type="submit" class="flex-1 px-4 py-2.5 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors">
                            Buat Tiket
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

</body>
</html>
