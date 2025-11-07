<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ORR'EA</title>
    <link href="https://cdn.jsdelivr.net/npm/daisyui@5" rel="stylesheet" type="text/css" />
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body class="bg-gray-50 text-gray-900">
    <style>
        html {
  scroll-behavior: smooth;
}
    </style>

    @if(session('success'))
    <div class="fixed top-4 right-4 bg-green-500 text-white px-4 py-2 rounded shadow-lg z-50">
        {{ session('success') }}
    </div>
@endif


    {{-- Navbar --}}
    <x-navbar />

    {{-- Content --}}
    <main class="min-h-screen">
        @yield('content')
    </main>

    {{-- Footer --}}
    <footer class="bg-gray-100 text-center py-6 text-sm text-gray-500 mt-10">
        © {{ date('Y') }} ORR'EA. All rights reserved.
    </footer>

    <script>
document.addEventListener('DOMContentLoaded', function () {
  const reduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  const cards = Array.from(document.querySelectorAll('.js-reveal'));

  if (reduced || cards.length === 0) return;

  cards.forEach((el, idx) => {
    el.style.transitionDuration = '700ms';
    el.classList.add('opacity-0', 'translate-y-6');
    el.style.transitionDelay = (idx % 8) * 60 + 'ms'; 
  });

  const io = new IntersectionObserver((entries) => {
    entries.forEach(e => {
      if (!e.isIntersecting) return;
      const el = e.target;

      el.classList.remove('opacity-0','translate-y-6');
      el.classList.add('opacity-100','translate-y-0');

      setTimeout(() => { el.style.transitionDelay = '0ms'; }, 800);
      io.unobserve(el);
    });
  }, { root: null, rootMargin: '0px 0px -10% 0px', threshold: 0.12 });

  cards.forEach(el => io.observe(el));
});
</script>

</body>
</html>
