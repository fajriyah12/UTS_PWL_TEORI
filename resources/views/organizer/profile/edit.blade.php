@extends('organizer.layout')

@section('title', 'Edit Profil Organizer')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-800">Profil Organizer</h1>
        <p class="text-gray-600">Kelola informasi publik dan data organizer Anda.</p>
    </div>

    <form action="{{ route('organizer.profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')

        {{-- Logo Section --}}
        <div class="bg-white p-6 rounded-xl shadow-sm border">
            <h2 class="text-lg font-semibold mb-4 text-gray-800">Logo & Branding</h2>
            
            <div class="flex items-center space-x-6">
                <div class="flex-shrink-0">
                    @if($organizer->logo_path)
                        <img src="{{ asset('storage/' . $organizer->logo_path) }}" 
                             alt="Logo" class="w-24 h-24 rounded-full object-cover border-2 border-indigo-100">
                    @else
                        <div class="w-24 h-24 rounded-full bg-indigo-50 flex items-center justify-center text-indigo-300 border-2 border-indigo-100">
                            <i class="fas fa-image text-3xl"></i>
                        </div>
                    @endif
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Upload Logo Baru</label>
                    <input type="file" name="logo" accept="image/*"
                           class="block w-full text-sm text-gray-500
                                  file:mr-4 file:py-2 file:px-4
                                  file:rounded-full file:border-0
                                  file:text-sm file:font-semibold
                                  file:bg-indigo-50 file:text-indigo-700
                                  hover:file:bg-indigo-100">
                    <p class="mt-1 text-xs text-gray-500">JPG, PNG, max 2MB. Disarankan rasio 1:1.</p>
                    @error('logo') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="mt-6">
                <label for="name" class="block text-sm font-medium text-gray-700">Nama Organizer / Perusahaan</label>
                <input type="text" name="name" id="name" value="{{ old('name', $organizer->name) }}"
                       class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="mt-4">
                <label for="bio" class="block text-sm font-medium text-gray-700">Bio / Deskripsi Singkat</label>
                <textarea name="bio" id="bio" rows="3"
                          class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('bio', $organizer->bio) }}</textarea>
                @error('bio') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        {{-- Contact Info --}}
        <div class="bg-white p-6 rounded-xl shadow-sm border">
            <h2 class="text-lg font-semibold mb-4 text-gray-800">Informasi Kontak</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="contact_email" class="block text-sm font-medium text-gray-700">Email Kontak</label>
                    <input type="email" name="contact_email" id="contact_email" value="{{ old('contact_email', $organizer->contact_email) }}"
                           class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    @error('contact_email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="contact_phone" class="block text-sm font-medium text-gray-700">Nomor Telepon</label>
                    <input type="text" name="contact_phone" id="contact_phone" value="{{ old('contact_phone', $organizer->contact_phone) }}"
                           class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    @error('contact_phone') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="mt-4">
                <label for="address" class="block text-sm font-medium text-gray-700">Alamat Lengkap</label>
                <textarea name="address" id="address" rows="2"
                          class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('address', $organizer->address) }}</textarea>
                @error('address') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        {{-- Banking --}}
        <div class="bg-white p-6 rounded-xl shadow-sm border">
            <h2 class="text-lg font-semibold mb-4 text-gray-800">Informasi Bank (Untuk Pencairan Dana)</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="bank_name" class="block text-sm font-medium text-gray-700">Nama Bank</label>
                    <input type="text" name="bank_name" id="bank_name" value="{{ old('bank_name', $organizer->bank_name) }}" placeholder="Contoh: BCA, Mandiri"
                           class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    @error('bank_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="bank_account" class="block text-sm font-medium text-gray-700">Nomor Rekening</label>
                    <input type="text" name="bank_account" id="bank_account" value="{{ old('bank_account', $organizer->bank_account) }}"
                           class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    @error('bank_account') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        <div class="flex justify-end">
            <button type="submit" class="bg-indigo-600 text-white px-6 py-2.5 rounded-lg hover:bg-indigo-700 font-semibold shadow-md transition">
                Simpan Perubahan
            </button>
        </div>
    </form>
</div>
@endsection
