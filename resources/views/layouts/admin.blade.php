<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('لوحة الإدارة') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 text-gray-800 font-sans antialiased flex h-screen overflow-hidden">

    <div id="sidebar-overlay" class="fixed inset-0 bg-black/50 z-40 hidden lg:hidden transition-opacity"></div>

    <aside id="sidebar" class="w-64 bg-[#002244] text-white flex flex-col shadow-2xl z-50 fixed inset-y-0 {{ app()->getLocale() == 'ar' ? 'right-0 translate-x-full' : 'left-0 -translate-x-full' }} lg:translate-x-0 lg:relative transition-transform duration-300 ease-in-out">
        
        <button id="close-sidebar-btn" class="lg:hidden absolute top-4 {{ app()->getLocale() == 'ar' ? 'left-4' : 'right-4' }} text-gray-300 hover:text-white">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
        </button>

        <div class="p-6 border-b border-white/10 text-center mt-4 lg:mt-0">
            <img src="{{ asset('images/zu-logo.jpeg') }}" alt="Logo" class="w-16 h-16 mx-auto rounded-xl object-contain bg-white p-1 mb-3 mix-blend-screen">
            <h2 class="text-xl font-black tracking-tight">{{ __('نظام الحجوزات') }}</h2>
            <p class="text-xs text-[var(--color-zu-beige)] mt-1">{{ __('لوحة تحكم الإدارة') }}</p>
        </div>
        
        <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition {{ request()->routeIs('admin.dashboard') ? 'bg-white/10 text-white font-bold' : 'text-gray-300 hover:bg-white/5 hover:text-white font-semibold' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l9-9 9 9M5 10v10h5v-6h4v6h5V10"></path></svg>
                {{ __('الرئيسية') }}
            </a>
            
           <a href="{{ route('admin.items.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition {{ request()->routeIs('admin.items.*') ? 'bg-white/10 text-white font-bold' : 'text-gray-300 hover:bg-white/5 hover:text-white font-semibold' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                {{ __('المنتجات والأجهزة') }}
            </a>

            <a href="{{ route('admin.bookings.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition {{ request()->routeIs('admin.bookings.*') ? 'bg-white/10 text-white font-bold' : 'text-gray-300 hover:bg-white/5 hover:text-white font-semibold' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                {{ __('إدارة الحجوزات') }}
            </a>
        </nav>
        
        <div class="p-4 border-t border-white/10">
            <form method="POST" action="{{ route('admin.logout') }}">
                @csrf
                <button type="submit" class="flex items-center justify-center w-full bg-[var(--color-zu-maroon)] hover:bg-red-800 text-white px-4 py-2 rounded-lg font-bold transition">
                    {{ __('خروج للموقع') }}
                </button>
            </form>
        </div>
    </aside>

    <main class="flex-1 flex flex-col overflow-hidden bg-gray-50 text-gray-800 relative w-full">
        <header class="bg-white shadow-sm border-b border-gray-200 h-16 flex items-center justify-between px-4 sm:px-6 z-10">
            <div class="flex items-center gap-4">
                <button id="mobile-menu-btn" class="lg:hidden text-gray-600 hover:text-[var(--color-zu-blue)] focus:outline-none">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                </button>
                <h1 class="text-lg sm:text-xl font-bold text-[var(--color-zu-blue)]">@yield('title', __('لوحة التحكم'))</h1>
            </div>
            
            <div class="flex items-center gap-3">
                @if(app()->getLocale() == 'ar')
                    <a href="{{ route('lang.switch', 'en') }}" class="text-xs font-bold bg-gray-100 px-3 py-1 rounded border">EN</a>
                @else
                    <a href="{{ route('lang.switch', 'ar') }}" class="text-xs font-bold bg-gray-100 px-3 py-1 rounded border">عربي</a>
                @endif
                <span class="text-xs sm:text-sm font-semibold bg-blue-50 text-blue-800 px-3 py-1 rounded-full border border-blue-200">{{ __('المدير العام') }}</span>
            </div>
        </header>

        <div class="flex-1 overflow-y-auto p-4 sm:p-6">
            @yield('content')
        </div>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebar');
            const mobileBtn = document.getElementById('mobile-menu-btn');
            const closeBtn = document.getElementById('close-sidebar-btn');
            const overlay = document.getElementById('sidebar-overlay');
            const isRTL = "{{ app()->getLocale() }}" === "ar";
            const transformClass = isRTL ? 'translate-x-full' : '-translate-x-full';

            function openSidebar() {
                sidebar.classList.remove(transformClass);
                overlay.classList.remove('hidden');
            }
            function closeSidebar() {
                sidebar.classList.add(transformClass);
                overlay.classList.add('hidden');
            }
            mobileBtn.addEventListener('click', openSidebar);
            closeBtn.addEventListener('click', closeSidebar);
            overlay.addEventListener('click', closeSidebar);
        });
    </script>
</body>
</html>