@extends('organizer.layout')

@section('title', 'Buat Event Baru')

@push('styles')
<style>
    .ticket-type-item {
        border: 2px dashed #e5e7eb;
        transition: all 0.3s;
    }
    .ticket-type-item:hover {
        border-color: #6366f1;
    }
</style>
@endpush

@section('content')
<div class="mb-8">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Buat Event Baru</h1>
            <p class="text-gray-600">Isi informasi event konser Anda.</p>
        </div>
        <a href="{{ route('organizer.events.index') }}" 
           class="text-gray-600 hover:text-gray-900">
            <i class="fas fa-arrow-left mr-2"></i> Kembali
        </a>
    </div>
</div>

<form action="{{ route('organizer.events.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    
    <div class="bg-white rounded-xl shadow p-6 mb-6">
        <h2 class="text-xl font-bold mb-4 text-gray-800">Informasi Event</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Title -->
            <div class="col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Judul Event *
                </label>
                <input type="text" name="title" required
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                       placeholder="Contoh: Coldplay Music of the Spheres World Tour"
                       value="{{ old('title') }}">
                @error('title')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <!-- Venue -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Venue *
                </label>
                <select name="venue_id" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    <option value="">Pilih Venue</option>
                    @foreach($venues as $venue)
                        <option value="{{ $venue->id }}" {{ old('venue_id') == $venue->id ? 'selected' : '' }}>
                            {{ $venue->name }} - {{ $venue->address }}
                        </option>
                    @endforeach
                </select>
                @error('venue_id')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <!-- Location -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Lokasi *
                </label>
                <input type="text" name="location" required
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                       placeholder="Contoh: Jakarta, Indonesia"
                       value="{{ old('location') }}">
                @error('location')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <!-- Start Time -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Waktu Mulai *
                </label>
                <input type="datetime-local" name="start_time" required
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                       value="{{ old('start_time') }}">
                @error('start_time')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <!-- End Time -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Waktu Selesai *
                </label>
                <input type="datetime-local" name="end_time" required
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                       value="{{ old('end_time') }}">
                @error('end_time')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <!-- Status -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Status *
                </label>
                <select name="status" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                    <option value="published" {{ old('status') == 'published' ? 'selected' : '' }}>Published</option>
                </select>
                @error('status')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <!-- Image -->
            <div class="col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Banner Event
                </label>
                <input type="file" name="image" accept="image/*"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                       onchange="previewImage(event)">
                <p class="text-sm text-gray-500 mt-1">Ukuran rekomendasi: 1200x600px</p>
                @error('image')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
                
                <!-- Image Preview -->
                <div id="imagePreview" class="mt-4 hidden">
                    <img id="preview" class="max-w-full h-48 rounded-lg object-cover" src="" alt="Preview">
                </div>
            </div>
            
            <!-- Description -->
            <div class="col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Deskripsi Event *
                </label>
                <textarea name="description" rows="4" required
                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                          placeholder="Deskripsikan event konser Anda...">{{ old('description') }}</textarea>
                @error('description')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>
    </div>
    
    <!-- Ticket Types Section -->
    <div class="bg-white rounded-xl shadow p-6 mb-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-bold text-gray-800">Jenis Tiket</h2>
            <button type="button" onclick="addTicketType()" 
                    class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700">
                <i class="fas fa-plus mr-2"></i> Tambah Jenis Tiket
            </button>
        </div>
        
        <div id="ticketTypesContainer">
            <!-- Ticket types will be added here dynamically -->
        </div>
        
        <div id="noTicketMessage" class="text-center py-8 border-2 border-dashed border-gray-300 rounded-lg">
            <i class="fas fa-ticket-alt text-3xl text-gray-300 mb-4"></i>
            <p class="text-gray-500">Belum ada jenis tiket. Klik "Tambah Jenis Tiket" untuk menambahkan.</p>
        </div>
        
        @error('ticket_types')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
        @enderror
    </div>
    
    <!-- Submit Buttons -->
    <div class="flex justify-end space-x-4">
        <a href="{{ route('organizer.events.index') }}" 
           class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
            Batal
        </a>
        <button type="submit" 
                class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
            Simpan Event
        </button>
    </div>
</form>

@push('scripts')
<script>
// Image Preview
function previewImage(event) {
    const preview = document.getElementById('preview');
    const imagePreview = document.getElementById('imagePreview');
    const file = event.target.files[0];
    
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
            imagePreview.classList.remove('hidden');
        }
        reader.readAsDataURL(file);
    } else {
        imagePreview.classList.add('hidden');
    }
}

// Ticket Type Management
let ticketTypeCount = 0;

function addTicketType() {
    ticketTypeCount++;
    
    // Hide no ticket message
    document.getElementById('noTicketMessage').classList.add('hidden');
    
    const container = document.getElementById('ticketTypesContainer');
    
    const ticketTypeHtml = `
        <div id="ticketType-${ticketTypeCount}" class="ticket-type-item mb-4 p-6 rounded-lg bg-gray-50">
            <div class="flex justify-between items-center mb-4">
                <h3 class="font-semibold text-gray-700">Jenis Tiket #${ticketTypeCount}</h3>
                <button type="button" onclick="removeTicketType(${ticketTypeCount})" 
                        class="text-red-600 hover:text-red-800">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <!-- Name -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Nama Tiket *
                    </label>
                    <input type="text" name="ticket_types[${ticketTypeCount}][name]" required
                           class="w-full px-3 py-2 border border-gray-300 rounded focus:ring-2 focus:ring-indigo-500"
                           placeholder="Contoh: Regular, VIP, Festival">
                </div>
                
                <!-- Price -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Harga (Rp) *
                    </label>
                    <input type="number" name="ticket_types[${ticketTypeCount}][price]" required min="1000"
                           class="w-full px-3 py-2 border border-gray-300 rounded focus:ring-2 focus:ring-indigo-500"
                           placeholder="100000">
                </div>
                
                <!-- Quota -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Kuota *
                    </label>
                    <input type="number" name="ticket_types[${ticketTypeCount}][quota]" required min="1"
                           class="w-full px-3 py-2 border border-gray-300 rounded focus:ring-2 focus:ring-indigo-500"
                           placeholder="100">
                </div>
                
                <!-- Per User Limit -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Maksimal per User *
                    </label>
                    <input type="number" name="ticket_types[${ticketTypeCount}][per_user_limit]" required min="1"
                           class="w-full px-3 py-2 border border-gray-300 rounded focus:ring-2 focus:ring-indigo-500"
                           value="4">
                </div>
                
                <!-- Sales Start -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Penjualan Mulai
                    </label>
                    <input type="datetime-local" name="ticket_types[${ticketTypeCount}][sales_start]"
                           class="w-full px-3 py-2 border border-gray-300 rounded focus:ring-2 focus:ring-indigo-500">
                </div>
                
                <!-- Sales End -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Penjualan Berakhir
                    </label>
                    <input type="datetime-local" name="ticket_types[${ticketTypeCount}][sales_end]"
                           class="w-full px-3 py-2 border border-gray-300 rounded focus:ring-2 focus:ring-indigo-500">
                </div>
                
                <!-- Description -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Deskripsi (Opsional)
                    </label>
                    <textarea name="ticket_types[${ticketTypeCount}][description]" rows="2"
                              class="w-full px-3 py-2 border border-gray-300 rounded focus:ring-2 focus:ring-indigo-500"
                              placeholder="Deskripsi manfaat tiket..."></textarea>
                </div>
            </div>
        </div>
    `;
    
    container.insertAdjacentHTML('beforeend', ticketTypeHtml);
}

function removeTicketType(id) {
    const element = document.getElementById(`ticketType-${id}`);
    if (element) {
        element.remove();
        
        // Show no ticket message if all removed
        const container = document.getElementById('ticketTypesContainer');
        if (container.children.length === 0) {
            document.getElementById('noTicketMessage').classList.remove('hidden');
        }
    }
}

// Add first ticket type on page load
document.addEventListener('DOMContentLoaded', function() {
    addTicketType();
});

// Form Validation
document.querySelector('form').addEventListener('submit', function(e) {
    const ticketTypes = document.querySelectorAll('[name^="ticket_types"]');
    if (ticketTypes.length === 0) {
        e.preventDefault();
        alert('Minimal harus ada satu jenis tiket.');
    }
});
</script>
@endpush
@endsection