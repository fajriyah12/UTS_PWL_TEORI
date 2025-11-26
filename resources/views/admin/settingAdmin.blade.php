<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Settings - ORR'EA Admin</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

  <style>
    body { font-family: 'Inter', sans-serif; }
  </style>
</head>
<body class="bg-gray-100">

  <div class="flex h-screen">
    <!-- Sidebar -->
    <aside class="w-60 bg-white border-r border-gray-200 flex flex-col justify-between overflow-y-auto">
      <div>
        <div class="text-indigo-600 text-2xl font-bold p-6 border-b border-gray-200">ORR'EA
            <p class="text-sm font-normal">Admin Portal</p>
        </div>
        <nav class="mt-4">
          <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2 px-6 py-3 text-gray-700 hover:bg-gray-100">
            <i class="fas fa-home"></i> Dashboard
          </a>
          <a href="{{ route('admin.datamaster') }}" class="flex items-center gap-2 px-6 py-3 text-gray-700 hover:bg-gray-100">
            <i class="fas fa-database"></i> Data Master
          </a>
          <a href="#" class="flex items-center gap-2 px-6 py-3 text-gray-700 hover:bg-gray-100">
            <i class="fas fa-chart-line"></i> Laporan
          </a>
          <a href="{{ route('admin.settingAdmin') }}" class="flex items-center gap-2 px-6 py-3 bg-purple-100 text-purple-600 font-medium">
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
    <main class="flex-1 p-8 overflow-y-auto">

      @if(session('status'))
      <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg flex items-center gap-2 shadow-sm">
        <i class="fas fa-check-circle"></i>
        {{ session('status') }}
      </div>
      @endif

      <h1 class="text-4xl font-bold text-gray-900 mb-2">My Profile</h1>
      <p class="text-gray-600 mb-8">Manage your account settings and personal information</p>

      <!-- Profile Card with Photo and Icons -->
      <div class="bg-white rounded-2xl p-8 mb-8 shadow-sm border border-gray-100">
        <div class="flex items-start justify-between">
          <div class="flex items-center gap-8">
            <!-- Profile Photo with Edit/Delete Icons -->
            <div class="profile-avatar relative">
              <div class="relative inline-block">
                <img src="{{ $user->photo ? asset('storage/'.$user->photo) : 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&size=120&background=random' }}" 
                     alt="{{ $user->name }}"
                     class="w-32 h-32 rounded-full object-cover border-4 border-purple-100 shadow-lg">
                
                <!-- Added overlay with edit and delete icons -->
                <div class="avatar-overlay absolute inset-0 rounded-full bg-black bg-opacity-40 flex items-center justify-center gap-3">
                  <button onclick="document.getElementById('photo-edit-modal').showModal()" class="bg-white text-purple-600 p-2 rounded-full hover:bg-gray-100 transition shadow-lg">
                    <i class="fas fa-pencil-alt"></i>
                  </button>
                  <button onclick="document.getElementById('photo-delete-modal').showModal()" class="bg-white text-red-600 p-2 rounded-full hover:bg-gray-100 transition shadow-lg">
                    <i class="fas fa-trash-alt"></i>
                  </button>
                </div>
              </div>
            </div>

            <!-- Profile Info -->
            <div>
              <h2 class="text-3xl font-bold text-gray-900">{{ $user->name }}</h2>
              <p class="text-purple-600 font-semibold capitalize text-lg mt-1">{{ $user->role }}</p>
              <p class="text-gray-500 text-sm mt-3">{{ $user->email }}</p>
            </div>
          </div>
        </div>
      </div>

      <!-- Personal Information Section -->
      <div class="bg-white rounded-2xl p-8 mb-8 shadow-sm border border-gray-100">
        <div class="flex justify-between items-center mb-8 border-b border-gray-100 pb-6">
          <div>
            <h3 class="text-2xl font-bold text-gray-900">Personal Information</h3>
            <p class="text-gray-500 text-sm mt-1">Your personal details and contact information</p>
          </div>
          <button onclick="document.getElementById('personal-info-modal').showModal()" class="bg-orange-500 hover:bg-orange-600 text-white px-5 py-2 rounded-lg flex items-center gap-2 transition shadow-sm">
            <i class="fas fa-pencil-alt"></i> Edit
          </button>
        </div>

        <div class="grid grid-cols-2 gap-8">
          <div>
            <p class="text-gray-600 text-sm font-semibold mb-2">First Name</p>
            <p class="text-gray-900 text-lg font-medium">{{ explode(' ', $user->name)[0] }}</p>
          </div>
          <div>
            <p class="text-gray-600 text-sm font-semibold mb-2">Last Name</p>
            <p class="text-gray-900 text-lg font-medium">{{ implode(' ', array_slice(explode(' ', $user->name), 1)) ?: '-' }}</p>
          </div>
          <div>
            <p class="text-gray-600 text-sm font-semibold mb-2">Email Address</p>
            <p class="text-gray-900 text-lg font-medium">{{ $user->email }}</p>
          </div>
          <div>
            <p class="text-gray-600 text-sm font-semibold mb-2">Phone Number</p>
            <p class="text-gray-900 text-lg font-medium">{{ $user->phone ?? '-' }}</p>
          </div>
          <div>
            <p class="text-gray-600 text-sm font-semibold mb-2">Role</p>
            <p class="text-gray-900 text-lg font-medium capitalize">{{ $user->role }}</p>
          </div>
        </div>
      </div>

      <!-- Change Password Section -->
      <div class="bg-white rounded-2xl p-8 shadow-sm border border-gray-100">
        <div class="flex justify-between items-center mb-8 border-b border-gray-100 pb-6">
          <div>
            <h3 class="text-2xl font-bold text-gray-900">Ubah Password</h3>
            <p class="text-gray-500 text-sm mt-1">Update your password to keep your account secure</p>
          </div>
          <button onclick="document.getElementById('password-modal').showModal()" class="bg-orange-500 hover:bg-orange-600 text-white px-5 py-2 rounded-lg flex items-center gap-2 transition shadow-sm">
            <i class="fas fa-pencil-alt"></i> Edit
          </button>
        </div>
        <div class="flex items-center gap-4">
          <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
            <i class="fas fa-lock text-blue-600"></i>
          </div>
          <div>
            <p class="text-gray-700 font-medium">Password</p>
            <p class="text-gray-500 text-sm">Last changed {{ $user->updated_at->format('d M Y') }}</p>
          </div>
        </div>
      </div>

    </main>
  </div>

  <!-- Modal: Edit Photo -->
  <dialog id="photo-edit-modal" class="modal">
    <div class="modal-box w-full max-w-sm rounded-2xl">
      <h3 class="font-bold text-lg mb-4 text-gray-900">Change Profile Photo</h3>
      <form method="POST" action="{{ route('admin.update-photo') }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
        <div class="mb-6">
          <label class="block text-gray-700 font-semibold mb-3">Select New Photo</label>
          <input type="file" name="photo" accept="image/*" class="file-input file-input-bordered w-full" required>
          @error('photo') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <div class="flex gap-3">
          <button type="button" onclick="photo_edit_modal.close()" class="btn btn-ghost flex-1 rounded-lg">Cancel</button>
          <button type="submit" class="btn flex-1 rounded-lg bg-orange-500 hover:bg-orange-600 text-white border-none">Upload</button>
        </div>
      </form>
    </div>
    <form method="dialog" class="modal-backdrop">
      <button>close</button>
    </form>
  </dialog>

  <!-- Modal: Delete Photo -->
  <dialog id="photo-delete-modal" class="modal">
    <div class="modal-box w-full max-w-sm rounded-2xl">
      <h3 class="font-bold text-lg mb-4 text-red-600">Delete Profile Photo</h3>
      <p class="text-gray-700 mb-6">Are you sure you want to delete your profile photo?</p>
      <form method="POST" action="{{ route('admin.delete-photo') }}">
        @csrf
        @method('DELETE')
        
        <div class="flex gap-3">
          <button type="button" onclick="photo_delete_modal.close()" class="btn btn-ghost flex-1 rounded-lg">Cancel</button>
          <button type="submit" class="btn flex-1 rounded-lg bg-red-500 hover:bg-red-600 text-white border-none">Delete</button>
        </div>
      </form>
    </div>
    <form method="dialog" class="modal-backdrop">
      <button>close</button>
    </form>
  </dialog>

  
  <!-- DaisyUI & Tailwind -->
  <script src="https://cdn.jsdelivr.net/npm/daisyui@5"></script>
</body>
</html>