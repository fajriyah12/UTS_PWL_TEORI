@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-6 py-8">

    {{-- ✅ HERO SLIDER (3 GAMBAR) --}}
    <div class="relative w-full overflow-hidden rounded-2xl shadow-md">

        {{-- Gambar slider --}}
        <img id="sliderImage"
             src="{{ asset('images/konser1.jpg') }}"
             class="w-full h-[420px] object-cover transition-all duration-700"
             alt="Concert Banner">

        {{-- Tombol kiri --}}
        <button onclick="prevImage()"
                class="absolute top-1/2 left-4 -translate-y-1/2 bg-white/70 p-2 rounded-full hover:bg-white">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2"
                 viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" /></svg>
        </button>

        {{-- Tombol kanan --}}
        <button onclick="nextImage()"
                class="absolute top-1/2 right-4 -translate-y-1/2 bg-white/70 p-2 rounded-full hover:bg-white">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2"
                 viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" /></svg>
        </button>

    </div>

    {{-- ✅ SCRIPT SLIDER --}}
    <script>
        const images = [
            "{{ asset('images/konser1.jpg') }}",
            "{{ asset('images/konser2.jpg') }}",
            "{{ asset('images/konser3.jpg') }}"
        ];

        let index = 0;

        function showImage() {
            document.getElementById('sliderImage').src = images[index];
        }

        function nextImage() {
            index = (index + 1) % images.length;
            showImage();
        }

        function prevImage() {
            index = (index - 1 + images.length) % images.length;
            showImage();
        }
    </script>


   {{-- Filter Buttons --}}
    @php
        // $types dan $type dikirim dari controller
    @endphp

    <div id="events" class="flex flex-wrap gap-2 mt-8 scroll-mt-24">

        {{-- ALL --}}
        <a href="{{ route('home') }}#events"
           class="px-4 py-1.5 rounded-full text-sm font-semibold
                  {{ $type ? 'bg-white border text-gray-700 hover:bg-gray-50' : 'bg-indigo-100 text-indigo-700' }}">
            All
        </a>

        {{-- Types dari DB --}}
        @foreach($types as $t)
            <a href="{{ route('home', ['type' => $t]) }}#events"
               class="px-4 py-1.5 rounded-full text-sm font-semibold
                      {{ $type === $t ? 'bg-indigo-100 text-indigo-700' : 'bg-white border text-gray-700 hover:bg-gray-50' }}">
                {{ $t }}
            </a>
        @endforeach
    </div>


    {{-- Events Grid --}}
    {{-- Events Grid --}}
    <div id="events-grid-container">
        <x-events.grid :events="$events" :type="$type ?? null" />
    </div>

</div>
@endsection
