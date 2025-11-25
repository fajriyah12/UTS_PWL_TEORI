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
                <h1 class="text-3xl font-bold text-gray-900">Edit Tiket</h1>
                <p class="text-gray-600 mt-2">Perbarui informasi tiket</p>
            </div>

            <!-- Form Card -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-8">
                <form action="{{ route('admin.update-ticket', $ticketType) }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <!-- Event Info (Read Only) -->
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <p class="text-sm text-gray-600">Acara</p>
                        <p class="text-lg font-semibold text-gray-900">{{ $ticketType->event->title }}</p>
                    </div>

                    <!-- Ticket Type Name -->
                    <div>
                        <label for="name" class="block text-sm font-semibold text-gray-900 mb-2">
                            Nama Tiket <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="name" name="name" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('name') border-red-500 @enderror" value="{{ old('name', $ticketType->name) }}">
                        @error('name')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Price -->
                    <div>
                        <label for="price" class="block text-sm font-semibold text-gray-900 mb-2">
                            Harga (Rp) <span class="text-red-500">*</span>
                        </label>
                        <input type="number" id="price" name="price" min="0" step="1" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('price') border-red-500 @enderror" value="{{ old('price', $ticketType->price) }}">
                        @error('price')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Quota -->
                    <div>
                        <label for="quota" class="block text-sm font-semibold text-gray-900 mb-2">
                            Kapasitas Tiket <span class="text-red-500">*</span>
                        </label>
                        <input type="number" id="quota" name="quota" min="1" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('quota') border-red-500 @enderror" value="{{ old('quota', $ticketType->quota) }}">
                        <p class="text-gray-500 text-sm mt-1">Terjual: <span class="font-semibold">{{ $ticketType->sold }}/{{ $ticketType->quota }}</span></p>
                        @error('quota')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Per User Limit -->
                    <div>
                        <label for="per_user_limit" class="block text-sm font-semibold text-gray-900 mb-2">
                            Batas Tiket per Pengguna (Opsional)
                        </label>
                        <input type="number" id="per_user_limit" name="per_user_limit" min="1" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('per_user_limit') border-red-500 @enderror" value="{{ old('per_user_limit', $ticketType->per_user_limit) }}">
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
                            <input type="datetime-local" id="sales_start" name="sales_start" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" value="{{ old('sales_start', $ticketType->sales_start?->format('Y-m-d\TH:i')) }}">
                        </div>
                        <div>
                            <label for="sales_end" class="block text-sm font-semibold text-gray-900 mb-2">
                                Penjualan Berakhir (Opsional)
                            </label>
                            <input type="datetime-local" id="sales_end" name="sales_end" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" value="{{ old('sales_end', $ticketType->sales_end?->format('Y-m-d\TH:i')) }}">
                        </div>
                    </div>

                    <!-- Metadata -->
                    <div class="bg-gray-50 rounded-lg p-4 space-y-2">
                        <p class="text-sm text-gray-600">
                            <span class="font-medium">Dibuat:</span> {{ $ticketType->created_at->format('d M Y, H:i') }}
                        </p>
                        <p class="text-sm text-gray-600">
                            <span class="font-medium">Diperbarui:</span> {{ $ticketType->updated_at->format('d M Y, H:i') }}
                        </p>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex gap-3 pt-6 border-t border-gray-200">
                        <a href="{{ route('admin.datamaster') }}" class="flex-1 px-4 py-2.5 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition-colors text-center">
                            Batal
                        </a>
                        <button type="submit" class="flex-1 px-4 py-2.5 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

</body>
</html>