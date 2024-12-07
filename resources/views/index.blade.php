<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet" />
    <style>
        body {
            font-family: 'Roboto', sans-serif;
        }
    </style>
</head>

<body class="bg-black text-white">
    <header class="flex justify-between items-center p-4 md:p-6">
        <div class="text-2xl md:text-3xl font-bold text-yellow-500">
            Fortu Fitness
        </div>
        @if (Route::has('login'))
        <nav class="relative">
            <button id="mobile-menu-button" class="md:hidden text-white focus:outline-none">
                <i class="fas fa-bars"></i>
            </button>
            <div id="mobile-menu" class="hidden absolute right-0 mt-2 w-48 bg-gray-800 rounded-md shadow-lg z-10">
                <div class="flex flex-col">
                    @auth
                    <a href="{{ url('/dashboard') }}" class="block rounded-md px-4 py-2 text-white bg-yellow-500 hover:bg-yellow-600 transition">
                        Dashboard
                    </a>
                    @else
                    <a href="{{ route('login') }}" class="flex items-center block rounded-md px-4 py-2 text-white hover:bg-yellow-600 transition">
                        <i class="fas fa-sign-in-alt mr-2"></i> Log in
                    </a>
                    @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="flex items-center block rounded-md px-4 py-2 text-white hover:bg-yellow-600 transition">
                        <i class="fas fa-user-plus mr-2"></i> Register
                    </a>
                    @endif
                    @endauth
                </div>
            </div>
            <div class="hidden md:flex md:items-center md:space-x-4">
                @auth
                <a href="{{ url('/dashboard') }}" class="block mt-4 md:mt-0 rounded-md px-4 py-2 text-white bg-yellow-500 hover:bg-yellow-600 transition">
                    Dashboard
                </a>
                @else
                <a href="{{ route('login') }}" class="flex items-center block mt-4 md:mt-0 rounded-md px-4 py-2 text-white bg-yellow-500 hover:bg-yellow-600 transition">
                    <i class="fas fa-sign-in-alt mr-2"></i> Log in
                </a>
                @if (Route::has('register'))
                <a href="{{ route('register') }}" class="flex items-center block mt-4 md:mt-0 rounded-md px-4 py-2 text-white bg-yellow-500 hover:bg-yellow-600 transition">
                    <i class="fas fa-user-plus mr-2"></i> Register
                </a>
                @endif
                @endauth
            </div>
        </nav>
        @endif
    </header>

    <script>
        // JavaScript untuk menangani tombol hamburger
        document.getElementById('mobile-menu-button').addEventListener('click', function() {
            const menu = document.getElementById('mobile-menu');
            menu.classList.toggle('hidden');
        });
    </script>

    <main class="text-center">
        <div class="relative">
            <img alt="Suasana gym Fortu Fitness Barru" class="w-full h-auto" src="{{ asset('assets/landing.webp') }}" />
            <div class="absolute inset-0 flex flex-col justify-center items-center bg-gradient-to-b from-black/70 to-black/30 p-4">
                <h1 class="text-3xl md:text-6xl font-bold mb-4 md:mb-6">
                    Gym Terbaik di Kabupaten Barru
                </h1>
                <p class="text-lg md:text-xl mb-4 md:mb-6">
                    Tingkatkan kebugaran Anda bersama kami di pusat kota Barru
                </p>
                <button class="px-6 py-2 md:px-8 md:py-3 bg-yellow-500 text-black text-lg md:text-xl font-bold rounded-full hover:bg-yellow-600 transition">
                    Mulai Sesi Gratis
                </button>
            </div>
        </div>
    </main>

    <!-- ... (other sections remain largely the same, just adjust padding and font sizes for mobile) ... -->

    <section class="py-12 md:py-20 bg-gray-900">
        <h2 class="text-3xl md:text-4xl font-bold text-center mb-8 md:mb-10">
            Mengapa Bergabung dengan Fortu Fitness Barru?
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 md:gap-10 px-4 md:px-10">
            <div class="flex items-center space-x-4 bg-gray-800 p-4 md:p-6 rounded-lg hover:bg-gray-700 transition">
                <i class="fas fa-map-marker-alt text-yellow-500 text-3xl md:text-4xl"></i>
                <div>
                    <h3 class="text-xl md:text-2xl font-bold">Lokasi Strategis</h3>
                    <p class="text-sm md:text-base">Terletak di pusat Kota Barru, mudah diakses dari berbagai area.</p>
                </div>
            </div>
            <div class="flex items-center space-x-4 bg-gray-800 p-4 md:p-6 rounded-lg hover:bg-gray-700 transition">
                <i class="fas fa-dumbbell text-yellow-500 text-3xl md:text-4xl"></i>
                <div>
                    <h3 class="text-xl md:text-2xl font-bold">Peralatan Modern</h3>
                    <p class="text-sm md:text-base">Dilengkapi dengan peralatan fitness terbaru dan terlengkap di Barru.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="py-12 md:py-20 bg-black">
        <h2 class="text-3xl md:text-4xl font-bold text-center mb-8 md:mb-10">Anggota Gym Kami</h2>
        <div class="flex flex-wrap justify-center gap-6">
            @foreach($gymMembers as $member)
            <div class="bg-gray-800 p-4 md:p-6 rounded-lg text-center hover:bg-gray-700 transition w-full sm:w-1/2 md:w-1/3 lg:w-1/4">
                <img alt="{{ $member->name }}" class="mx-auto mb-4 rounded-full w-32 h-32 md:w-40 md:h-40 object-cover" src="{{ $member->profile_photo_url }}" />
                <h3 class="text-lg md:text-xl font-bold mb-2">{{ $member->name }}</h3>
                @if($member->memberships && $member->memberships->first() && $member->memberships->first()->membershipType)
                <p class="text-sm md:text-base text-gray-400">{{ $member->memberships->first()->membershipType->name }}</p>
                @else
                <p class="text-sm md:text-base text-gray-400">Tidak ada membership aktif</p>
                @endif
            </div>
            @endforeach
        </div>
    </section>

    <section class="py-12 md:py-20 bg-gray-900">
        <h2 class="text-3xl md:text-4xl font-bold text-center mb-8 md:mb-10">Jenis Keanggotaan</h2>
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 md:gap-8">
                @foreach($membershipTypes as $type)
                <div class="bg-gray-800 rounded-lg p-6 text-center hover:bg-gray-700 transition">
                    <h3 class="text-xl md:text-2xl font-bold mb-4">{{ $type->name }}</h3>
                    <p class="text-2xl md:text-3xl font-bold text-yellow-500 mb-4">Rp {{ number_format($type->price, 0, ',', '.') }}</p>
                    <p class="mb-4">Durasi: {{ $type->duration }} hari</p>
                    <p class="mb-6 text-sm md:text-base">{{ $type->description }}</p>
                    <button class="bg-yellow-500 text-black font-bold py-2 px-4 rounded hover:bg-yellow-600 transition text-sm md:text-base">
                        Pilih Paket
                    </button>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <section class="py-12 md:py-20 bg-gray-900">
        <h2 class="text-3xl md:text-4xl font-bold text-center mb-8 md:mb-10">Tentang Fortu Fitness Barru</h2>
        <div class="flex flex-col md:flex-row items-center justify-center px-4 md:px-10 space-y-6 md:space-y-0 md:space-x-10">
            <div class="w-full md:w-1/2">
                <img src="https://images.unsplash.com/photo-1534438327276-14e5300c3a48?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1170&q=80" alt="Interior Fortu Fitness Barru" class="rounded-lg shadow-lg w-full h-auto">
            </div>
            <div class="w-full md:w-1/2 text-center md:text-left">
                <p class="mb-4 md:mb-6 text-sm md:text-base">
                    Fortu Fitness Barru adalah pusat kebugaran terkemuka di Kabupaten Barru, Sulawesi Selatan. Kami berkomitmen untuk menyediakan fasilitas fitness modern dan berkualitas tinggi bagi masyarakat Barru dan sekitarnya.
                </p>
                <p class="mb-4 md:mb-6 text-sm md:text-base">
                    Dengan lokasi strategis di Jl. Ali Hanafi No.2350, Sumpang Binangae, kami menawarkan akses mudah bagi semua anggota. Tim pelatih profesional kami siap membimbing Anda dalam perjalanan menuju gaya hidup yang lebih sehat dan bugar.
                </p>
            </div>
        </div>
    </section>

    <section class="py-12 md:py-20 bg-black">
        <h2 class="text-3xl md:text-5xl font-bold text-center mb-8 md:mb-12 text-white">Lokasi Kami</h2>
        <div class="flex justify-center mb-8 px-4 md:px-0">
            <div class="rounded-xl shadow-2xl overflow-hidden w-full max-w-[1200px]">
                <div class="aspect-w-16 aspect-h-9">
                    <iframe
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3978.021951887791!2d119.60023817585004!3d-4.40699234700241!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2dbe28075ad4016b%3A0xff5fbcad9bc62445!2sFORTU%20FITNESS%20BARRU!5e0!3m2!1sid!2sid!4v1729163057484!5m2!1sid!2sid"
                        style="border:0;"
                        allowfullscreen=""
                        loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade"
                        class="w-full h-full object-cover"></iframe>
                </div>
            </div>
        </div>
    </section>

    <section class="py-12 md:py-20 bg-gray-900">
        <h2 class="text-3xl md:text-4xl font-bold text-center mb-8 md:mb-10">Hubungi Kami</h2>
        <div class="flex flex-col items-center space-y-4 px-4 md:px-0">
            <p class="flex items-center space-x-2 text-sm md:text-base">
                <i class="fas fa-phone-alt text-yellow-500"></i>
                <a href="tel:+6287777279900" class="text-yellow-500 hover:underline">087777279900</a>
            </p>
            <p class="flex items-center space-x-2 text-sm md:text-base">
                <i class="fab fa-whatsapp text-yellow-500"></i>
                <a href="https://wa.me/087777279900" target="_blank" class="text-yellow-500 hover:underline">WhatsApp Kami</a>
            </p>
            <p class="flex items-center space-x-2 text-sm md:text-base">
                <i class="fas fa-envelope text-yellow-500"></i>
                <a href="mailto:fortufitness@gmail.com" class="text-yellow-500 hover:underline">fortufitness@gmail.com</a>
            </p>
            <p class="flex items-center space-x-2 text-sm md:text-base">
                <i class="fab fa-instagram text-yellow-500"></i>
                <a href="https://www.instagram.com/fortufitness" target="_blank" class="text-yellow-500 hover:underline">@fortufitness</a>
            </p>
            <p class="flex items-center space-x-2 text-sm md:text-base text-center md:text-left">
                <i class="fas fa-map-marker-alt text-yellow-500"></i>
                <span>Jl. Ali Hanafi No.2350, Sumpang Binangae, Kec. Barru, Kabupaten Barru, Sulawesi Selatan 90712</span>
            </p>
        </div>
    </section>

    <footer class="bg-gray-900 text-white py-8 md:py-10">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <div class="mb-6 md:mb-0">
                    <h3 class="text-xl md:text-2xl font-bold mb-4">FortuFitness Barru</h3>
                    <p class="text-sm md:text-base">Wujudkan impian tubuh ideal dan hidup sehat bersama kami.</p>
                </div>
                <div class="mb-6 md:mb-0">
                    <h3 class="text-lg font-bold mb-4">Layanan Kami</h3>
                    <ul class="space-y-2">
                        <li><a href="#" class="text-sm md:text-base hover:text-yellow-500 transition">Gym</a></li>
                        <li><a href="#" class="text-sm md:text-base hover:text-yellow-500 transition">Personal Training</a></li>
                        <li><a href="#" class="text-sm md:text-base hover:text-yellow-500 transition">Kelas Fitness</a></li>
                        <li><a href="#" class="text-sm md:text-base hover:text-yellow-500 transition">Nutrisi Konsultasi</a></li>
                    </ul>
                </div>
                <div class="mb-6 md:mb-0">
                    <h3 class="text-lg font-bold mb-4">Jam Operasional</h3>
                    <ul class="space-y-2">
                        <li class="text-sm md:text-base">Senin - Jumat: 06:00 - 22:00</li>
                        <li class="text-sm md:text-base">Sabtu: 07:00 - 20:00</li>
                        <li class="text-sm md:text-base">Minggu: 08:00 - 18:00</li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-lg font-bold mb-4">Ikuti Kami</h3>
                    <div class="flex space-x-4">
                        <a href="#" class="text-2xl hover:text-yellow-500 transition"><i class="fab fa-facebook"></i></a>
                        <a href="#" class="text-2xl hover:text-yellow-500 transition"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="text-2xl hover:text-yellow-500 transition"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="text-2xl hover:text-yellow-500 transition"><i class="fab fa-youtube"></i></a>
                    </div>
                </div>
            </div>
            <hr class="my-8 border-gray-700">
            <p class="text-center text-sm md:text-base">&copy; 2023 Fortu Fitness Barru. All rights reserved.</p>
        </div>
    </footer>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js"></script>
    <script>
        // JavaScript untuk menangani scrolling halus
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();

                document.querySelector(this.getAttribute('href')).scrollIntoView({
                    behavior: 'smooth'
                });
            });
        });
    </script>
</body>

</html>