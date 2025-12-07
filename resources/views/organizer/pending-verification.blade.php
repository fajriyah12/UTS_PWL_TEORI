@extends('layouts.app')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <div class="text-center">
            <i class="fas fa-clock text-4xl text-yellow-500 mb-4"></i>
            <h2 class="text-3xl font-bold text-gray-900">Menunggu Verifikasi</h2>
            <p class="mt-2 text-gray-600">
                Akun organizer Anda sedang dalam proses verifikasi oleh tim admin.
                Anda akan mendapatkan notifikasi via email setelah akun diverifikasi.
            </p>
        </div>
        
        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-info-circle text-yellow-400"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-yellow-700">
                        Proses verifikasi biasanya memakan waktu 1-3 hari kerja.
                        Anda dapat <a href="mailto:support@orrea.com" class="underline">menghubungi support</a> 
                        jika membutuhkan bantuan.
                    </p>
                </div>
            </div>
        </div>
        
        <div class="text-center">
            <a href="{{ route('home') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-base font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                <i class="fas fa-home mr-2"></i> Kembali ke Beranda
            </a>
        </div>
    </div>
</div>
@endsection