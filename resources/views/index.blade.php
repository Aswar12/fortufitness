<html>

<head>
    <script src="https://cdn.tailwindcss.com">
    </script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&amp;display=swap" rel="stylesheet" />
    <style>
        body {
            font-family: 'Roboto', sans-serif;
        }
    </style>
</head>

<body class="bg-black text-white">
    <header class="flex justify-between items-center p-4">
        <div class="text-2xl font-bold text-yellow-500">
            Fortu Fitness
        </div>
        @if (Route::has('login'))
        <nav class="-mx-3 flex flex-1 justify-end">
            @auth
            <a
                href="{{ url('/dashboard') }}"
                class="rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white">
                Dashboard
            </a>
            @else
            <a
                href="{{ route('login') }}"
                class="rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white">
                Log in
            </a>

            @if (Route::has('register'))
            <a
                href="{{ route('register') }}"
                class="rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white">
                Register
            </a>
            @endif
            @endauth
        </nav>
        @endif
    </header>
    <main class="text-center py-20">
        <div class="relative">
            <img alt="A muscular man in a gym" class="mx-auto opacity-50" height="600" src="{{ asset('assets/landing.webp') }}" width="800" />
            <div class="absolute inset-0 flex flex-col justify-center items-center bg-black bg-opacity-70">
                <h1 class="text-5xl font-bold">
                    Free trial session with a trainer
                </h1>
                <button class="mt-4 px-6 py-2 bg-yellow-500 text-black font-bold rounded">
                    Details
                </button>
            </div>
        </div>
    </main>
    <section class="py-20">
        <h2 class="text-4xl font-bold text-center mb-10">
            Reasons to join
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-10 px-10">
            <div class="flex items-center space-x-4">
                <i class="fas fa-dumbbell text-yellow-500 text-3xl">
                </i>
                <div>
                    <h3 class="text-2xl font-bold">
                        1000 sq.ft.
                    </h3>
                    <p>
                        Spacious gym for a well-distanced workout experience.
                    </p>
                </div>
            </div>
            <div class="flex items-center space-x-4">
                <i class="fas fa-tools text-yellow-500 text-3xl">
                </i>
                <div>
                    <h3 class="text-2xl font-bold">
                        More than 500 equipment
                    </h3>
                    <p>
                        Modern and latest equipment for all your workout needs.
                    </p>
                </div>
            </div>
            <div class="flex items-center space-x-4">
                <i class="fas fa-clock text-yellow-500 text-3xl">
                </i>
                <div>
                    <h3 class="text-2xl font-bold">
                        Round-the-clock operation
                    </h3>
                    <p>
                        Open 24/7 for your convenience.
                    </p>
                </div>
            </div>
            <div class="flex items-center space-x-4">
                <i class="fas fa-users text-yellow-500 text-3xl">
                </i>
                <div>
                    <h3 class="text-2xl font-bold">
                        4 fitness zones
                    </h3>
                    <p>
                        Dedicated zones for cardio, strength, functional training, and relaxation.
                    </p>
                </div>
            </div>
        </div>
    </section>
    <section class="py-20 bg-gray-900">
        <h2 class="text-4xl font-bold text-center mb-10">
            Gym members
        </h2>
        <div class="flex justify-center space-x-4">
            <div class="bg-gray-800 p-6 rounded-lg text-center">
                <img alt="Gym member" class="mx-auto mb-4 rounded-full" height="200" src="https://storage.googleapis.com/a1aa/image/ZDkKX7QRkhZnChMLu3DrJfd26M4MoQjd2uFasEeGWFUwE9iTA.jpg" width="200" />
                <h3 class="text-xl font-bold">
                    Pass Trial
                </h3>
            </div>
            <div class="bg-gray-800 p-6 rounded-lg text-center">
                <img alt="Gym member" class="mx-auto mb-4 rounded-full" height="200" src="https://storage.googleapis.com/a1aa/image/ZDkKX7QRkhZnChMLu3DrJfd26M4MoQjd2uFasEeGWFUwE9iTA.jpg" width="200" />
                <h3 class="text-xl font-bold">
                    Pass Easy Start
                </h3>
            </div>
            <div class="bg-gray-800 p-6 rounded-lg text-center">
                <img alt="Gym member" class="mx-auto mb-4 rounded-full" height="200" src="https://storage.googleapis.com/a1aa/image/ZDkKX7QRkhZnChMLu3DrJfd26M4MoQjd2uFasEeGWFUwE9iTA.jpg" width="200" />
                <h3 class="text-xl font-bold">
                    Pass Free Time
                </h3>
            </div>
        </div>
    </section>
    <section class="py-20 bg-black">
        <h2 class="text-4xl font-bold text-center mb-10">
            Tentang Fortu Fitness
        </h2>
        <div class="text-center px-10">
            <p class="mb-6">
                Fortu Fitness adalah gym modern yang menawarkan berbagai peralatan dan fasilitas kebugaran untuk membantu Anda mencapai tujuan kebugaran Anda. Gym kami dirancang untuk memberikan lingkungan yang nyaman dan memotivasi untuk semua tingkat kebugaran.
            </p>
            <p class="mb-6">
                Kami berlokasi di tempat yang strategis, mudah diakses oleh semua orang. Tim pelatih profesional kami siap membimbing dan mendukung Anda dalam perjalanan kebugaran Anda.
            </p>
            <div class="flex justify-center mb-6">
                <iframe allowfullscreen="" class="rounded-lg w-full h-96" loading="lazy" referrerpolicy="no-referrer-when-downgrade" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3978.021923268468!2d119.60023817497752!3d-4.406997695567164!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2dbe28075ad4016b%3A0xff5fbcad9bc62445!2sFortu%20Fitness!5e0!3m2!1sid!2sid!4v1727930732205!5m2!1sid!2sid" style="border:0">
                </iframe>
            </div>
            <div class="text-center">
                <h3 class="text-2xl font-bold mb-4">
                    Hubungi Kami
                </h3>
                <p class="mb-2">
                    <a class="text-yellow-500" href="https://wa.me/621234567890" target="_blank">
                        <i class="fas fa-phone-alt text-yellow-500">
                        </i>
                        +62 123 456 7890
                    </a>
                </p>
                <p class="mb-2">
                    <i class="fas fa-envelope text-yellow-500">
                    </i>
                    info@fortufitness.com
                </p>
                <p>
                    <i class="fas fa-map-marker-alt text-yellow-500">
                    </i>
                    Jl. Ali Hanafi No.2350, Sumpang Binangae, Kec. Barru, Kabupaten Barru, Sulawesi Selatan 90712
                </p>
            </div>
        </div>
    </section>
</body>

</html>