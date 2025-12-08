@extends('organizer.layout')

@section('title', 'Kelola Event')

@section('content')
<div class="mb-8">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Kelola Event</h1>
            <p class="text-gray-600">Buat, edit, dan kelola event konser Anda.</p>
        </div>
        <a href="{{ route('organizer.events.create') }}" 
           class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700">
            <i class="fas fa-plus mr-2"></i> Event Baru
        </a>
    </div>
</div>

<!-- Filter & Search -->
<div class="bg-white rounded-xl shadow p-6 mb-6">
    <form id="filterForm" method="GET" class="flex space-x-4" onsubmit="return false;">
        <div class="flex-1">
            <input type="text" id="searchInput" name="search" placeholder="Cari event..." 
                   value="{{ request('search') }}"
                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
        </div>
        <div>
            <select id="statusInput" name="status" class="px-4 py-2 border border-gray-300 rounded-lg">
                <option value="">Semua Status</option>
                <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>Published</option>
                <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
            </select>
        </div>
        <!-- Button is optional now with AJAX, but kept for non-JS fallback or manual trigger -->
        <button type="button" id="filterBtn" class="bg-gray-100 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-200">
            <i class="fas fa-search"></i> Filter
        </button>
    </form>
</div>

<!-- Events Table Container -->
<div id="eventsTableContainer" class="bg-white rounded-xl shadow overflow-hidden">
    @include('organizer.events._table')
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
                <i class="fas fa-exclamation-triangle text-red-600"></i>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mt-4">Hapus Event</h3>
            <div class="mt-2 px-7 py-3">
                <p class="text-sm text-gray-500">
                    Apakah Anda yakin ingin menghapus event "<span id="eventTitle"></span>"?
                    Tindakan ini tidak dapat dibatalkan.
                </p>
            </div>
            <div class="items-center px-4 py-3 mt-4">
                <button id="confirmDelete" class="px-4 py-2 bg-red-600 text-white text-base font-medium rounded-md w-full shadow-sm hover:bg-red-700">
                    Ya, Hapus
                </button>
                <button onclick="closeModal()" class="mt-2 px-4 py-2 bg-gray-100 text-gray-700 text-base font-medium rounded-md w-full shadow-sm hover:bg-gray-200">
                    Batal
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// AJAX Search Logic
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const statusInput = document.getElementById('statusInput');
    const filterBtn = document.getElementById('filterBtn');
    const tableContainer = document.getElementById('eventsTableContainer');

    function fetchEvents() {
        const search = searchInput.value;
        const status = statusInput.value;
        
        // Construct URL
        const url = new URL("{{ route('organizer.events.index') }}");
        if(search) url.searchParams.set('search', search);
        if(status) url.searchParams.set('status', status);

        // Fetch API
        fetch(url, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.text())
        .then(html => {
            tableContainer.innerHTML = html;
        })
        .catch(error => console.error('Error fetching events:', error));
    }

    // Debounce function for search
    let timeout = null;
    searchInput.addEventListener('keyup', function() {
        clearTimeout(timeout);
        timeout = setTimeout(fetchEvents, 500); // Wait 500ms after typing stops
    });

    statusInput.addEventListener('change', fetchEvents);
    filterBtn.addEventListener('click', fetchEvents);
});

// Delete Modal Logic
let eventIdToDelete = null;
let eventTitleToDelete = null;

function confirmDelete(id, title) {
    eventIdToDelete = id;
    eventTitleToDelete = title;
    document.getElementById('eventTitle').textContent = title;
    document.getElementById('deleteModal').classList.remove('hidden');
}

function closeModal() {
    document.getElementById('deleteModal').classList.add('hidden');
    eventIdToDelete = null;
    eventTitleToDelete = null;
}

document.getElementById('confirmDelete').addEventListener('click', function() {
    if (eventIdToDelete) {
        document.getElementById('delete-form-' + eventIdToDelete).submit();
    }
});

// Close modal when clicking outside
window.onclick = function(event) {
    const modal = document.getElementById('deleteModal');
    if (event.target == modal) {
        closeModal();
    }
}
</script>
@endpush
@endsection