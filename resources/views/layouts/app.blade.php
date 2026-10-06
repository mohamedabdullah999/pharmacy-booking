<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('نظام الحجوزات - كلية الصيدلة جامعة الزقازيق') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root { --color-zu-blue: #0b2242; --color-zu-maroon: #b91c28; --color-zu-beige: #f9f9f9; --color-zu-green: #10b981; }
        body { background-color: #f3f4f6; }
    </style>
</head>
<body class="text-gray-800 font-sans antialiased flex flex-col min-h-screen selection:bg-[var(--color-zu-maroon)] selection:text-white">

<header class="relative bg-center bg-cover bg-no-repeat text-white border-b-[6px] border-[var(--color-zu-maroon)]" 
    style="background-image: linear-gradient(rgba(11, 34, 66, 0.85), rgba(11, 34, 66, 0.95)), url('/images/pharmacy-header.png');">
    
    <div class="absolute top-4 {{ app()->getLocale() == 'ar' ? 'left-4' : 'right-4' }} flex items-center gap-4 z-20">
        
        @if(app()->getLocale() == 'ar')
            <a href="{{ route('lang.switch', 'en') }}" class="text-white hover:text-gray-300 font-bold flex items-center gap-1 text-sm border border-white/30 px-2 py-1 rounded">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"></path></svg>
                EN
            </a>
        @else
            <a href="{{ route('lang.switch', 'ar') }}" class="text-white hover:text-gray-300 font-bold flex items-center gap-1 text-sm border border-white/30 px-2 py-1 rounded">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"></path></svg>
                عربي
            </a>
        @endif
        
        <a href="{{ route('cart.index') ?? '#' }}" class="relative text-white hover:text-gray-200">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
            <span id="cart-count" class="absolute -top-1 -right-2 bg-red-600 text-white text-[10px] font-bold rounded-full h-4 w-4 flex items-center justify-center">0</span>
        </a>
    </div>

    <div class="relative z-10 max-w-7xl mx-auto px-4 py-8 sm:py-12 flex flex-col md:flex-row items-center justify-between gap-6">
        <div class="flex-shrink-0 flex flex-col items-center">
            <img src="{{ asset('images/zu-logo.png') }}" alt="جامعة الزقازيق" class="w-24 sm:w-28 md:w-32 object-contain drop-shadow-xl">
            <span class="mt-2 text-sm md:text-base font-bold tracking-wide">{{ __('جامعة الزقازيق') }}</span>
        </div>

        <div class="flex flex-col items-center text-center px-4">
            <h1 class="text-3xl sm:text-4xl md:text-5xl font-black tracking-tight mb-2 drop-shadow-md">{{ __('كلية الصيدلة') }}</h1>
            <div class="flex items-center gap-3 text-[var(--color-zu-maroon)] mb-1">
                <span class="w-2.5 h-2.5 rounded-full bg-current shadow-sm"></span>
                <span class="text-lg sm:text-xl font-bold text-white tracking-wide drop-shadow">{{ __('نظام الأجهزة') }}</span>
                <span class="w-2.5 h-2.5 rounded-full bg-current shadow-sm"></span>
            </div>
        </div>

        <div class="flex-shrink-0 flex flex-col items-center">
            <img src="{{ asset('images/pharmacy-logo.png') }}" alt="كلية الصيدلة" class="w-20 sm:w-24 md:w-28 object-contain drop-shadow-xl">
            <span class="mt-2 text-sm md:text-base font-bold tracking-wide">{{ __('كلية الصيدلة') }}</span>
        </div>
    </div>
</header>

<main class="flex-grow max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-8 md:py-10">
    @yield('content')
</main>

<footer class="bg-[var(--color-zu-blue)] text-gray-200 border-t-4 border-[var(--color-zu-maroon)] mt-auto shadow-inner">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 text-center text-xs sm:text-sm font-semibold">
        <p>{{ __('جميع الحقوق محفوظة') }} &copy; {{ date('Y') }} - {{ __('كلية الصيدلة، جامعة الزقازيق.') }}</p>
    </div>
</footer>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const cart = JSON.parse(localStorage.getItem('pharmacy_cart')) || [];
        document.getElementById('cart-count').textContent = cart.length;
    });
</script>
</body>
</html>