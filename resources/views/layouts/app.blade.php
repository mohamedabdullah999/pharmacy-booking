<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>نظام الحجوزات - كلية الصيدلة جامعة الزقازيق</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .bg-zu-pattern {
            background-color: var(--color-zu-beige);
            background-image: url('/images/pattern-bg.png');
        }
    </style>
</head>
<body class="bg-gray-50 text-gray-800 font-sans antialiased flex flex-col min-h-screen selection:bg-[var(--color-zu-maroon)] selection:text-white">

<header
    class="relative overflow-hidden
           bg-gradient-to-l
           from-[var(--color-zu-blue)]
           via-[#00315f]
           to-[#001a35]
           text-white
           shadow-2xl
           border-b border-white/10"
    dir="rtl"
>
    <div class="absolute inset-0 pointer-events-none overflow-hidden">
        <div class="absolute -top-32 -left-32 w-[450px] h-[450px] rounded-full bg-white/5 blur-3xl"></div>
        <div class="absolute -bottom-40 right-1/4 w-[600px] h-[600px] rounded-full bg-[var(--color-zu-maroon)]/20 blur-3xl"></div>
        <div class="absolute inset-0 bg-[linear-gradient(120deg,transparent_0%,rgba(255,255,255,0.06)_50%,transparent_100%)]"></div>
    </div>

    <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div
            class="min-h-[160px]
                   sm:min-h-[200px]
                   md:min-h-[220px]
                   lg:min-h-[250px]
                   py-6
                   sm:py-8
                   lg:py-10
                   flex
                   flex-col
                   md:flex-row
                   items-center
                   justify-between
                   gap-6
                   md:gap-8"
        >

            <a href="/" class="shrink-0 group flex items-center justify-center">
                <img
                    src="{{ asset('images/zu-logo.webp') }}"
                    alt="جامعة الزقازيق"
                    class="
                        w-[240px]
                        xs:w-[280px]
                        sm:w-[380px]
                        md:w-[440px]
                        lg:w-[520px]
                        xl:w-[580px]

                        max-h-[100px]
                        sm:max-h-[125px]
                        md:max-h-[145px]
                        lg:max-h-[165px]
                        xl:max-h-[185px]

                        h-auto
                        object-contain

                        brightness-110
                        contrast-125
                        drop-shadow-[0_2px_8px_rgba(0,0,0,0.7)]
                        drop-shadow-[0_0_15px_rgba(255,255,255,0.95)]

                        transition-all
                        duration-300
                        group-hover:scale-[1.02]
                    "
                >
            </a>

            <div class="hidden md:flex flex-col items-center justify-center text-center shrink-0">
                <h1 class="text-2xl md:text-3xl lg:text-4xl font-black tracking-tight leading-none text-white drop-shadow-xl">
                    كلية الصيدلة
                </h1>
                <div class="flex items-center gap-3 mt-2.5">
                    <span class="w-3 h-3 rounded-full bg-[var(--color-zu-maroon)] shadow-lg"></span>
                    <span class="text-xs sm:text-sm lg:text-lg text-white/95 font-black tracking-wider">
                        نظام الحجوزات
                    </span>
                    <span class="w-3 h-3 rounded-full bg-[var(--color-zu-maroon)] shadow-lg"></span>
                </div>
            </div>

            <div class="flex flex-wrap items-center justify-center md:justify-end gap-4 sm:gap-6 lg:gap-8 w-full md:w-auto">

                <a href="/" class="shrink-0 group flex items-center justify-center">
                    <img
                        src="{{ asset('images/pharmacy-logo.png') }}"
                        alt="كلية الصيدلة - جامعة الزقازيق"
                        class="
                            w-[120px]
                            sm:w-[160px]
                            md:w-[190px]
                            lg:w-[230px]
                            xl:w-[260px]

                            max-h-[85px]
                            sm:max-h-[110px]
                            md:max-h-[130px]
                            lg:max-h-[150px]
                            xl:max-h-[170px]

                            h-auto
                            object-contain

                            brightness-105
                            drop-shadow-[0_0_8px_rgba(255,255,255,0.95)]

                            transition-all
                            duration-300
                            group-hover:scale-[1.03]
                        "
                    >
                </a>

                <!-- أزرار التنقل -->
                <nav class="flex items-center gap-2 sm:gap-4">
                    <!-- <a
                        href="/"
                        class="
                            hidden
                            lg:flex
                            items-center
                            gap-2.5
                            px-5
                            py-3.5
                            rounded-xl
                            text-base
                            font-bold
                            text-white/90
                            hover:text-white
                            hover:bg-white/10
                            transition-all
                            duration-300
                        "
                    > -->
                        <!-- <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l9-9 9 9M5 10v10h5v-6h4v6h5V10"/>
                        </svg>
                        الرئيسية
                    </a> -->

                    <!-- <a
                        href="/admin/login"
                        class="
                            group
                            flex
                            items-center
                            gap-2
                            bg-white
                            text-[var(--color-zu-blue)]
                            px-4
                            sm:px-6
                            lg:px-7
                            py-3
                            sm:py-3.5
                            lg:py-4
                            rounded-xl
                            font-black
                            text-xs
                            sm:text-sm
                            lg:text-base
                            shadow-2xl
                            shadow-black/30
                            hover:bg-[var(--color-zu-beige)]
                            hover:-translate-y-0.5
                            active:translate-y-0
                            transition-all
                            duration-300
                        "
                    >
                        <svg
                            class="w-4 h-4 sm:w-5 sm:h-5 transition-transform duration-300 group-hover:-translate-x-1"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                        </svg>
                        <span>دخول الإدارة</span>
                    </a> -->
                </nav>

            </div>

        </div>
    </div>

    <div class="relative z-10 h-[6px] bg-gradient-to-l from-[var(--color-zu-maroon)] via-[var(--color-zu-beige)] to-[var(--color-zu-maroon)]"></div>
</header>

<main class="flex-grow max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12">
    @yield('content')
</main>

<footer class="bg-[var(--color-zu-blue)] text-[var(--color-zu-beige)] border-t-4 border-[var(--color-zu-maroon)] mt-auto shadow-inner">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 text-center text-xs sm:text-sm font-semibold">
        <p>جميع الحقوق محفوظة &copy; {{ date('Y') }} - كلية الصيدلة، جامعة الزقازيق.</p>
    </div>
</footer>

</body>
</html>