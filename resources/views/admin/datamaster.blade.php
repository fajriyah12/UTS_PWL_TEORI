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
          <a href="{{ route('admin.laporan') }}" class="flex items-center gap-2 px-6 py-3 text-gray-700 hover:bg-gray-100">
            <i class="fas fa-chart-line"></i> Laporan
          </a>
          <a href="{{ route('admin.settingAdmin') }}" class="flex items-center gap-2 px-6 py-3 text-gray-700 hover:bg-gray-100">
            <i class="fas fa-cog"></i> Pengaturan
          </a>
        </nav>
      </div>
      <a href="{{ route('staff.logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="flex items-center gap-2 px-6 py-3 text-red-500 font-semibold hover:bg-red-50 border-t border-gray-200">
        <i class="fas fa-sign-out-alt"></i> Keluar
      </a>
      <form id="logout-form" action="{{ route('staff.logout') }}" method="POST" style="display: none;">
        @csrf
      </form>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 p-6 overflow-y-auto">
      <!-- Header -->
      <div class="flex justify-between items-center mb-6">
        <div>
          <h1 class="text-2xl font-bold text-gray-800">Data Master Management</h1>
          <p class="text-gray-500 text-sm">Manage tickets, users, and organizers</p>
        </div>
        <div class="flex items-center gap-4">
          <!-- <i class="fas fa-bell text-gray-500 text-xl"></i> -->
          <i class="fas fa-cog text-gray-500 text-xl"></i>
          <div class="flex items-center gap-2">
            <div class="w-8 h-8 rounded-full bg-gray-300"></div>
            <div>
              <p class="font-semibold text-gray-800">{{ auth('staff')->user()->name }}</p>
              <p class="text-xs text-gray-500">Administrator</p>
            </div>
          </div>
        </div>
      </div>

      <!-- Tabs -->
      <div class="bg-white rounded-lg shadow-sm">
        <div class="flex border-b">
          <button id="tabTicket" class="px-6 py-3 tab-active">Ticket Management</button>
          <button id="tabUser" class="px-6 py-3 text-gray-600 hover:text-purple-600">User Management</button>
        </div>

        <!-- Ticket Management -->
        <div id="ticketSection" class="p-6">
          <!-- Search and filter form for tickets -->
          <div class="flex justify-between items-center mb-4">
            <input type="text" id="ticketSearch" placeholder="Search tickets..." class="border border-gray-300 rounded-lg px-4 py-2 w-1/3 focus:outline-none focus:ring-2 focus:ring-purple-500">
            <div class="flex gap-3">
              <select id="eventFilter" class="border border-gray-300 rounded-lg px-3 py-2">
                <option value="all">All Events</option>
                @foreach($ticketTypes->pluck('event')->unique('id') as $event)
                  <option value="{{ $event->id }}">{{ $event->title }}</option>
                @endforeach
              </select>
              <select id="statusFilter" class="border border-gray-300 rounded-lg px-3 py-2">
                <option value="all">All Categories</option>
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
              </select>
              <a href="{{ route('admin.create-ticket') }}" class="bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700">
    + Add Ticket
</a>
            </div>
          </div>

          <!-- Table with real ticket data -->
          <div id="ticketTableContainer">
            @include('admin.partials.tickets-table', ['tickets' => $ticketTypes])
          </div>
        </div>

        <!-- User Management -->
        <div id="userSection" class="hidden p-6">
          <!-- Search and filter form for users -->
          <div class="flex justify-between items-center mb-4">
            <input type="text" id="userSearch" placeholder="Search users..." class="border border-gray-300 rounded-lg px-4 py-2 w-1/3 focus:outline-none focus:ring-2 focus:ring-purple-500">
            <div class="flex gap-3">
              <select id="roleFilter" class="border border-gray-300 rounded-lg px-3 py-2">
                <option value="all">All Roles</option>
                <option value="user">User</option>
                <option value="organizer">Organizer</option>
                <option value="admin">Admin</option>
              </select>
              <button class="bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700" onclick="openAddUserModal()">
                + Add User
              </button>
            </div>
          </div>

          <!-- Table with real user data -->
          <div id="userTableContainer">
            @include('admin.partials.users-table', ['users' => $users])
          </div>
        </div>
      </div>
    </main>
  </div>

  <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
  <script>
    const ticketBtn = document.getElementById('tabTicket');
    const userBtn = document.getElementById('tabUser');
    const ticketSec = document.getElementById('ticketSection');
    const userSec = document.getElementById('userSection');
    const ticketSearch = document.getElementById('ticketSearch');
    const eventFilter = document.getElementById('eventFilter');
    const statusFilter = document.getElementById('statusFilter');
    const userSearch = document.getElementById('userSearch');
    const roleFilter = document.getElementById('roleFilter');

    ticketBtn.addEventListener('click', () => {
      ticketSec.classList.remove('hidden');
      userSec.classList.add('hidden');
      ticketBtn.classList.add('tab-active');
      userBtn.classList.remove('tab-active');
    });

    userBtn.addEventListener('click', () => {
      userSec.classList.remove('hidden');
      ticketSec.classList.add('hidden');
      userBtn.classList.add('tab-active');
      ticketBtn.classList.remove('tab-active');
    });

    ticketSearch.addEventListener('keyup', searchTickets);
    eventFilter.addEventListener('change', searchTickets);
    statusFilter.addEventListener('change', searchTickets);
    userSearch.addEventListener('keyup', searchUsers);
    roleFilter.addEventListener('change', searchUsers);

    async function searchTickets() {
      const search = ticketSearch.value;
      const event = eventFilter.value;
      const status = statusFilter.value;

      const params = new URLSearchParams({
        search,
        event,
        status
      });

      try {
        const response = await fetch(`{{ route('admin.search-tickets') }}?${params}`);
        const html = await response.text();
        document.getElementById('ticketTableContainer').innerHTML = html;
        attachDeleteHandlers();
      } catch (error) {
        console.error('Error:', error);
      }
    }

    async function searchUsers() {
      const search = userSearch.value;
      const role = roleFilter.value;

      const params = new URLSearchParams({
        search,
        role
      });

      try {
        const response = await fetch(`{{ route('admin.search-users') }}?${params}`);
        const html = await response.text();
        document.getElementById('userTableContainer').innerHTML = html;
        attachDeleteHandlers();
      } catch (error) {
        console.error('Error:', error);
      }
    }

    function attachDeleteHandlers() {
      document.querySelectorAll('.delete-ticket').forEach(btn => {
        btn.addEventListener('click', deleteTicket);
      });
      document.querySelectorAll('.delete-user').forEach(btn => {
        btn.addEventListener('click', deleteUser);
      });
    }

    async function deleteTicket(e) {
      if (confirm('Are you sure you want to delete this ticket?')) {
        const ticketId = e.target.dataset.ticketId;
        try {
          const response = await fetch(`/admin/data-master/tickets/${ticketId}`, {
            method: 'DELETE',
            headers: {
              'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
          });
          if (response.ok) {
            searchTickets();
          }
        } catch (error) {
          console.error('Error:', error);
        }
      }
    }

    async function deleteUser(e) {
      if (confirm('Are you sure you want to delete this user?')) {
        const userId = e.target.dataset.userId;
        try {
          const response = await fetch(`/admin/data-master/users/${userId}`, {
            method: 'DELETE',
            headers: {
              'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
          });
          if (response.ok) {
            searchUsers();
          }
        } catch (error) {
          console.error('Error:', error);
        }
      }
    }

    function openAddUserModal() {
      document.getElementById('addUserModal').classList.remove('hidden');
    }

    function closeAddUserModal() {
      document.getElementById('addUserModal').classList.add('hidden');
    }

    // Wrap initialization in DOMContentLoaded
    document.addEventListener('DOMContentLoaded', function() {
        
        // Form Submit Handler
        const addUserForm = document.getElementById('addUserForm');
        if(addUserForm) {
            addUserForm.addEventListener('submit', async function(e) {
                e.preventDefault();
                
                const formData = new FormData(this);
                const data = Object.fromEntries(formData.entries());
                
                try {
                    const response = await fetch("{{ route('admin.store-user') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify(data)
                    });
                    
                    if (response.ok) {
                        closeAddUserModal();
                        this.reset();
                        searchUsers(); // Refresh table
                        alert('User created successfully!');
                    } else {
                        const errorData = await response.json();
                        alert('Error: ' + JSON.stringify(errorData.message || errorData));
                    }
                } catch (error) {
                    console.error('Error:', error);
                    alert('An error occurred');
                }
            });
        }

        // Attach delete handlers
        attachDeleteHandlers();
    });

  </script>

  <!-- Add User Modal -->
  <div id="addUserModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Add New User</h3>
            <form id="addUserForm">
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Name</label>
                    <input type="text" name="name" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Email</label>
                    <input type="email" name="email" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Role</label>
                    <select name="role" class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        <option value="user">User</option>
                        <option value="organizer">Organizer</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>
                <div class="mb-6">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Password</label>
                    <input type="password" name="password" required minlength="8" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                </div>
                <div class="flex justify-end gap-2">
                    <button type="button" onclick="closeAddUserModal()" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                        Cancel
                    </button>
                    <button type="submit" class="bg-purple-600 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                        Create User
                    </button>
                </div>
            </form>
        </div>
    </div>
  </div>
</body>
</html>
