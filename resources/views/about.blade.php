@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto px-6 py-10">

    {{-- Hero Section --}}
    <div class="flex flex-col md:flex-row items-center gap-10">
        
        <img src="https://images.unsplash.com/photo-1504384308090-c894fdcc538d"
             class="w-full md:w-1/2 rounded-2xl shadow-lg">

        <div class="md:w-1/2">
            <h1 class="text-4xl font-bold mb-4">ORR'EA</h1>
            <p class="text-gray-600 leading-relaxed">
                ORR'EA adalah platform pemesanan tiket event yang menyediakan informasi lengkap 
                mengenai konser, festival musik, dan berbagai acara menarik lainnya. 
                Kami hadir untuk memberikan pengalaman terbaik dalam menemukan event favorit 
                dan melakukan pembelian tiket dengan mudah, cepat, dan aman.
            </p>
        </div>
    </div>

    {{-- Visi & Misi --}}
    <div class="mt-16">
        <h2 class="text-3xl font-semibold mb-6">Visi & Misi</h2>

        <div class="grid md:grid-cols-2 gap-8">
            <div class="p-6 bg-white rounded-xl shadow js-reveal">
                <h3 class="text-xl font-bold mb-3">Visi</h3>
                <p class="text-gray-600">
                    Menjadi platform pemesanan tiket digital terbaik di Indonesia yang mudah digunakan 
                    dan dapat dipercaya oleh semua pengguna.
                </p>
            </div>

            <div class="p-6 bg-white rounded-xl shadow js-reveal">
                <h3 class="text-xl font-bold mb-3">Misi</h3>
                <ul class="text-gray-600 list-disc pl-5">
                    <li>Menyediakan informasi event terbaru dan terlengkap.</li>
                    <li>Memberikan kemudahan dalam proses pembelian tiket.</li>
                    <li>Mendukung penyelenggara event agar semakin berkembang.</li>
                    <li>Menyediakan layanan yang aman, cepat, dan responsif.</li>
                </ul>
            </div>
        </div>
    </div>

    {{-- Team Section --}}
    <div class="mt-16">
        <h2 class="text-3xl font-semibold mb-6">Our Team</h2>

        <div class="grid md:grid-cols-3 gap-8">

            <div class="bg-white p-6 rounded-xl shadow text-center js-reveal">
                <img src="{{ asset('images/RIRI12.jpg') }}" class="w-28 h-28 rounded-full mx-auto mb-3">
                <h3 class="text-lg font-bold">Fajriyah Indriani</h3>
                <p class="text-gray-500">UI/UX Designer</p>
            </div>

            <div class="bg-white p-6 rounded-xl shadow text-center js-reveal">
                <img src="{{ asset('images/fadil.jpg') }}" class="w-28 h-28 rounded-full mx-auto mb-3">
                <h3 class="text-lg font-bold">Muhammad Nur Faadil</h3>
                <p class="text-gray-500">Backend Developer</p>
            </div>

            <div class="bg-white p-6 rounded-xl shadow text-center js-reveal">
                <img src="{{ asset('images/agung.jpg') }}" class="w-28 h-28 rounded-full mx-auto mb-3">
                <h3 class="text-lg font-bold">S Agung Setiawan </h3>
                <p class="text-gray-500">Event Coordinator</p>
            </div>

            <div class="bg-white p-6 rounded-xl shadow text-center js-reveal">
                <img src="{{ asset('images/kemas.jpg') }}" class="w-28 h-28 rounded-full mx-auto mb-3">
                <h3 class="text-lg font-bold">Kemas Muhammad Alfath Iskandar </h3>
                <p class="text-gray-500">Marketing Specialist</p>
            </div>


        </div>
    </div>

</div>
@endsection
