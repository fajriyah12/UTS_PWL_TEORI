@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto mt-10 mb-20">

    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">My Profile</h1>

        <a href="{{ route('profile.edit') }}"
           class="px-4 py-2 bg-gray-800 text-white rounded-lg shadow hover:bg-gray-900">
            ✎ Edit
        </a>
    </div>

    <!-- Profile Card -->
    <div class="bg-white shadow rounded-xl p-6 mb-8 flex items-center gap-5 border">
        <img 
            src="{{ $user->photo 
                ? asset('storage/profile/' . $user->photo) 
                : asset('storage/profile/default.jpg') }}"
            class="w-16 h-16 rounded-full object-cover border"
            alt="Profile Photo">

        <div>
            <h2 class="text-xl font-semibold text-gray-800">{{ $user->name }}</h2>
            <p class="text-gray-500">{{ $user->email }}</p>
        </div>
    </div>

    <!-- Main Form Card -->
    <div class="bg-white shadow rounded-xl p-8 border">

        <h2 class="text-xl font-semibold mb-6">Personal Information</h2>

        <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PATCH')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <!-- LEFT SIDE -->
                <div class="space-y-4">

                    {{-- Name --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Name:</label>
                        <input type="text" name="first_name" value="{{ $user->name }}"
                               class="w-full border rounded-lg p-3 bg-gray-50">
                    </div>

                    {{-- Email --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Email Address:</label>
                        <input type="email" name="email" value="{{ $user->email }}"
                               class="w-full border rounded-lg p-3 bg-gray-50">
                    </div>

                    {{-- Password --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Password:</label>
                        <input type="password" name="password"
                               class="w-full border rounded-lg p-3 bg-gray-50">
                    </div>

                    {{-- Confirm Password --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Confirm Password:</label>
                    <input type="password" name="password_confirmation"
                           class="w-full border rounded-lg p-3 bg-gray-50">
                </div>

                </div>

                <!-- RIGHT SIDE (ADDRESS SECTION) -->
                <div class="space-y-4">

                    {{-- Phone --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Phone Number:</label>
                        <input type="text" name="phone" value="{{ $user->phone ?? '-' }}"
                               class="w-full border rounded-lg p-3 bg-gray-50">
                    </div>

                    {{-- Country --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Country:</label>
                        <input type="text" name="country" value="{{ $user->country }}"
                               class="w-full border rounded-lg p-3 bg-gray-50">
                    </div>

                    {{-- State --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">State:</label>
                        <input type="text" name="state" value="{{ $user->state }}"
                               class="w-full border rounded-lg p-3 bg-gray-50">
                    </div>

                    {{-- Role --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Role:</label>
                        <input type="text" name="role" value="{{ $user->role }}"
                               class="w-full border rounded-lg p-3 bg-gray-50">
                    </div>

                </div>
            </div>
        </form>

    </div>

</div>
@endsection
