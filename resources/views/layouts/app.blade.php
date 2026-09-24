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
<body class="bg-gray-50 text-gray-800 font-sans antialiased flex flex-col min-h-screen">

<header
    class="relative overflow-hidden
           bg-gradient-to-l
           from-[var(--color-zu-blue)]
           via-[#00315f]
           to-[#001a35]
           text-white
           shadow-xl
           border-b border-white/10"
    dir="rtl"
>


    <div class="absolute inset-0 pointer-events-none">

        <div
            class="absolute -top-24 -left-24
                   w-72 h-72
                   rounded-full
                   bg-white/5
                   blur-3xl">
        </div>

        <div
            class="absolute -bottom-32 right-1/4
                   w-80 h-80
                   rounded-full
                   bg-[var(--color-zu-maroon)]/10
                   blur-3xl">
        </div>

        <div
            class="absolute inset-0
                   bg-[linear-gradient(120deg,transparent_0%,rgba(255,255,255,0.03)_50%,transparent_100%)]">
        </div>

    </div>


    <div
        class="relative z-10
               max-w-7xl
               mx-auto
               px-4
               sm:px-6
               lg:px-8"
    >

        <div
            class="min-h-[76px]
                   sm:min-h-[82px]
                   lg:min-h-[88px]
                   flex
                   items-center
                   justify-between
                   gap-3
                   sm:gap-5
                   lg:gap-8"
        >



            <a
                href="/"
                class="shrink-0 group
                       flex items-center"
            >

                <img
                    src="{{ asset('images/zu-logo.png') }}"
                    alt="جامعة الزقازيق"
                    class="
                        w-[155px]
                        xs:w-[175px]
                        sm:w-[220px]
                        md:w-[260px]
                        lg:w-[300px]

                        max-h-[58px]
                        sm:max-h-[66px]
                        lg:max-h-[72px]

                        h-auto
                        object-contain

                        drop-shadow-[0_0_2px_rgba(255,255,255,0.95)]
                        drop-shadow-[0_0_5px_rgba(255,255,255,0.35)]

                        transition-transform
                        duration-300

                        group-hover:scale-[1.02]
                    "
                >

            </a>


            <div
                class="
                    hidden
                    md:flex
                    flex-col
                    items-center
                    justify-center
                    text-center
                    shrink-0
                "
            >

                <h1
                    class="
                        text-lg
                        lg:text-xl
                        font-black
                        tracking-tight
                        leading-none
                    "
                >
                    كلية الصيدلة
                </h1>

                <div
                    class="
                        flex
                        items-center
                        gap-2
                        mt-1
                    "
                >

                    <span
                        class="
                            w-1.5
                            h-1.5
                            rounded-full
                            bg-[var(--color-zu-maroon)]
                        "
                    ></span>

                    <span
                        class="
                            text-xs
                            lg:text-sm
                            text-white/65
                            font-medium
                        "
                    >
                        نظام الحجوزات
                    </span>

                    <span
                        class="
                            w-1.5
                            h-1.5
                            rounded-full
                            bg-[var(--color-zu-maroon)]
                        "
                    ></span>

                </div>

            </div>


            <div
                class="
                    flex
                    items-center
                    gap-2
                    sm:gap-4
                    lg:gap-6
                    min-w-0
                "
            >


                <a
                    href="/"
                    class="
                        shrink-0
                        group
                        flex
                        items-center
                    "
                >

                    <img
                        src="{{ asset('images/pharmacy-logo.png') }}"
                        alt="كلية الصيدلة - جامعة الزقازيق"
                        class="
                            w-[75px]
                            sm:w-[105px]
                            md:w-[125px]
                            lg:w-[150px]

                            max-h-[48px]
                            sm:max-h-[55px]
                            lg:max-h-[64px]

                            h-auto
                            object-contain

                            drop-shadow-[0_0_2px_rgba(255,255,255,0.95)]
                            drop-shadow-[0_0_5px_rgba(255,255,255,0.30)]

                            transition-transform
                            duration-300

                            group-hover:scale-[1.03]
                        "
                    >

                </a>



                <nav
                    class="
                        flex
                        items-center
                        gap-1
                        sm:gap-2
                        lg:gap-3
                    "
                >


                    <a
                        href="/"
                        class="
                            hidden
                            lg:flex
                            items-center
                            gap-2

                            px-3
                            py-2

                            rounded-lg

                            text-sm
                            font-bold
                            text-white/75

                            hover:text-white
                            hover:bg-white/10

                            transition-all
                            duration-300
                        "
                    >

                        <svg
                            class="w-4 h-4"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M3 12l9-9 9 9M5 10v10h5v-6h4v6h5V10"
                            />
                        </svg>

                        الرئيسية

                    </a>



                    <a
                        href="/admin/login"
                        class="
                            group

                            flex
                            items-center
                            gap-1.5
                            sm:gap-2

                            bg-white
                            text-[var(--color-zu-blue)]

                            px-2.5
                            sm:px-3
                            lg:px-4

                            py-2
                            sm:py-2.5

                            rounded-lg
                            lg:rounded-xl

                            font-bold

                            text-[11px]
                            sm:text-xs
                            lg:text-sm

                            shadow-lg
                            shadow-black/10

                            ring-1
                            ring-white/20

                            hover:bg-[var(--color-zu-beige)]
                            hover:-translate-y-0.5
                            hover:shadow-xl

                            transition-all
                            duration-300
                        "
                    >

                        <svg
                            class="
                                w-3.5
                                h-3.5
                                sm:w-4
                                sm:h-4

                                transition-transform
                                duration-300

                                group-hover:-translate-x-1
                            "
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"
                            />
                        </svg>

                        <span>
                            دخول الإدارة
                        </span>

                    </a>

                </nav>

            </div>

        </div>

    </div>



    <div
        class="
            relative
            z-10
            h-[3px]
            bg-gradient-to-l
            from-[var(--color-zu-maroon)]
            via-[var(--color-zu-beige)]
            to-[var(--color-zu-maroon)]
        "
    ></div>

</header>


    <main class="flex-grow max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-8">
        @yield('content')
    </main>

    <footer class="bg-[var(--color-zu-blue)] text-[var(--color-zu-beige)] border-t-4 border-[var(--color-zu-maroon)] mt-auto">
        <div class="max-w-7xl mx-auto px-4 py-6 text-center text-sm">
            <p>جميع الحقوق محفوظة &copy; {{ date('Y') }} - كلية الصيدلة، جامعة الزقازيق.</p>
        </div>
    </footer>

</body>
</html>