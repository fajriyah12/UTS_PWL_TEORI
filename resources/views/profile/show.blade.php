@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
    <div class="md:grid md:grid-cols-3 md:gap-6">
        
        <!-- Left Sidebar: Profile Photo & Basic Info -->
        <div class="md:col-span-1">
            <div class="px-4 sm:px-0">
                <h3 class="text-lg font-medium leading-6 text-gray-900">Profile Picture</h3>
                <p class="mt-1 text-sm text-gray-600">
                    Update your profile photo and personal details.
                </p>
            </div>

            <div class="mt-5 bg-white shadow sm:rounded-lg overflow-hidden">
                <div class="px-4 py-5 sm:p-6 text-center">
                    <div class="mb-4">
                        <img class="h-32 w-32 rounded-full object-cover mx-auto border-4 border-white shadow-lg" 
                             src="{{ $user->photo ? asset('storage/profile/' . $user->photo) : asset('images/default-avatar.png') }}" 
                             alt="{{ $user->name }}">
                    </div>
                    
                    <h3 class="text-lg font-medium text-gray-900">{{ $user->name }}</h3>
                    <p class="text-sm text-gray-500">{{ $user->email }}</p>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800 mt-2">
                        {{ ucfirst($user->role) }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Right Side: Edit Forms -->
        <div class="mt-5 md:mt-0 md:col-span-2 space-y-6">
            
            <!-- Personal Information Form -->
            <div class="bg-white shadow sm:rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg font-medium leading-6 text-gray-900 mb-4">Personal Information</h3>
                    
                    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PATCH')

                        <div class="grid grid-cols-6 gap-6">
                            
                            <!-- Photo Upload (Hidden but functional) -->
                            <div class="col-span-6 sm:col-span-6">
                                <label class="block text-sm font-medium text-gray-700">Change Photo</label>
                                <input type="file" name="photo" class="mt-1 block w-full text-sm text-gray-500
                                    file:mr-4 file:py-2 file:px-4
                                    file:rounded-full file:border-0
                                    file:text-sm file:font-semibold
                                    file:bg-indigo-50 file:text-indigo-700
                                    hover:file:bg-indigo-100">
                                @error('photo') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <!-- Name -->
                            <div class="col-span-6 sm:col-span-3">
                                <label for="name" class="block text-sm font-medium text-gray-700">Full Name</label>
                                <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>


                            <!-- Phone -->
                            <div class="col-span-6 sm:col-span-3">
                                <label for="phone" class="block text-sm font-medium text-gray-700">Phone Number</label>
                                <input type="text" name="phone" id="phone" value="{{ old('phone', $user->phone) }}" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                            </div>

                            <!-- Date of Birth -->
                            <div class="col-span-6 sm:col-span-3">
                                <label for="date_of_birth" class="block text-sm font-medium text-gray-700">Date of Birth</label>
                                <input type="date" name="date_of_birth" id="date_of_birth" value="{{ old('date_of_birth', $user->date_of_birth) }}" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                            </div>

                            <!-- Gender -->
                            <div class="col-span-6 sm:col-span-3">
                                <label class="block text-sm font-medium text-gray-700">Gender</label>
                                <div class="mt-2 flex space-x-4">
                                    <label class="inline-flex items-center">
                                        <input type="radio" name="gender" value="male" class="form-radio text-indigo-600" {{ old('gender', $user->gender) === 'male' ? 'checked' : '' }}>
                                        <span class="ml-2">Male</span>
                                    </label>
                                    <label class="inline-flex items-center">
                                        <input type="radio" name="gender" value="female" class="form-radio text-pink-600" {{ old('gender', $user->gender) === 'female' ? 'checked' : '' }}>
                                        <span class="ml-2">Female</span>
                                    </label>
                                </div>
                            </div>

                            <!-- Institution -->
                            <div class="col-span-6 sm:col-span-3">
                                <label for="institution" class="block text-sm font-medium text-gray-700">Institution / Company</label>
                                <input type="text" name="institution" id="institution" value="{{ old('institution', $user->institution) }}" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                            </div>

                            <!-- Occupation -->
                            <div class="col-span-6 sm:col-span-6">
                                <label for="occupation" class="block text-sm font-medium text-gray-700">Occupation</label>
                                <input type="text" name="occupation" id="occupation" value="{{ old('occupation', $user->occupation) }}" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                            </div>

                        </div>
                        
                        <div class="mt-6 text-right">
                            <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Save Information
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Password Update Section -->
            <div class="bg-white shadow sm:rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg font-medium leading-6 text-gray-900 mb-4">Update Password</h3>
                    <div class="max-w-xl text-sm text-gray-600 mb-4">
                        Ensure your account is using a long, random password to stay secure.
                    </div>

                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <!-- Delete Account Section -->
            <div class="bg-white shadow sm:rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg font-medium leading-6 text-red-600 mb-4">Danger Zone</h3>
                    <div class="max-w-xl text-sm text-gray-600 mb-4">
                        Once your account is deleted, all of its resources and data will be permanently deleted.
                    </div>
                    @include('profile.partials.delete-user-form')
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
