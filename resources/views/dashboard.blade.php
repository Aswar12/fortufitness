<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-base md:text-xl text-gray-800  leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-2 ">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gray-700  overflow-hidden shadow-xl sm:rounded-lg">
                <!-- Hero Section -->
                <div class="hero-section ml-6 p-2 text-center md:text-left">
                    <h1 class="text-2xl md:text-3xl font-bold text-gray-800 dark:text-white"> Selamat Datang di FortuFitness</h1>
                    <p class="text-sm md:text-lg text-gray-600 dark:text-gray-100">Temukan Potensi Anda dalam Kebugaran</p>
                </div>
                <!-- User Info Section -->
                <div class="user-info-section p-8 ">
                    <h2 class="text-lg md:text-2xl font-bold text-gray-800 dark:text-white mb-4 text-center md:text-left">
                        <i class="fas fa-user"></i> Informasi Pengguna
                    </h2>
                    <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow-md text-left grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach([
                        ['icon' => 'fas fa-user-circle', 'label' => 'Nama Pengguna', 'value' => auth()->user()->name],
                        ['icon' => 'fas fa-check-circle', 'label' => 'Status Keanggotaan', 'value' => auth()->user()->membership->status],
                        ['icon' => 'fas fa-calendar-alt', 'label' => 'Tanggal Bergabung', 'value' => auth()->user()->created_at->format('d M Y')],
                        ['icon' => 'fas fa-calendar-check', 'label' => 'Jumlah Kehadiran', 'value' => auth()->user()->checkIns()->count()],
                        ['icon' => 'fas fa-crown', 'label' => 'Paket Keanggotaan', 'value' => auth()->user()->membership->membershipType->name],
                        ['icon' => 'fas fa-hourglass-half', 'label' => 'Sisa Hari Keanggotaan', 'value' => floor(\Carbon\Carbon::parse(auth()->user()->membership->end_date)->diffInDays(\Carbon\Carbon::now()))],
                        ] as $item)
                        <div class="flex justify-between">
                            <p class="text-sm md:text-lg text-gray-600 dark:text-white flex items-center w-1/2">
                                <i class="{{ $item['icon'] }} mr-2"></i>
                                <strong>{{ $item['label'] }}</strong>
                            </p>
                            <p class="text-sm md:text-lg truncate text-gray-600 dark:text-white w-1/2">: {{ $item['value'] }}</p>
                        </div>
                        @endforeach
                    </div>
                </div>
                <!-- Statistik Section -->
                <div class="stats-section p-8">
                    <div class="flex flex-wrap justify-start md:justify-center -mx-4">
                        <div class="w-full md:w-1/2 xl:w-1/3 p-4 order-1">
                            <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow-md text-left md:text-center">
                                <h2 class="text-lg md:text-2xl font-bold text-gray-800 dark:text-white"><i class="fas fa-users"></i> Anggota</h2>
                                <p class="text-sm md:text-lg text-gray-600 dark:text-gray-100">{{ App\Models\User::count() }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Daftar Member Check-In Section -->
                <div class="checkin-section p-8">
                    <h2 class="text-lg md:text-2xl font-bold text-gray-800 dark:text-white mb-4 text-center md:text-left"><i class="fas fa-user-check"></i> Daftar Member yang Sedang Check-In</h2>
                    <div class="flex flex-wrap justify-center -mx-4 overflow-y-auto h-64">
                        @foreach(App\Models\CheckIn::where('check_out_time', null)->get() as $checkIn)
                        <div class="w-full md:w-1/2 xl:w-1/3 p-4 md:p-6">
                            <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow-md flex flex-row items-center">
                                <div class="w-24 h-24 mr-4">
                                    @if($checkIn->user->profile_photo_url)
                                    <img src="{{  $checkIn->user->profile_photo_url }}" alt="Foto Profil" class="w-full h-full object-cover rounded-full">
                                    @else
                                    <img src="{{ asset('images/default-profile-picture.jpg') }}" alt="Foto Profil" class="w-full h-full object-cover rounded-full">
                                    @endif
                                </div>
                                <div class="flex-1 flex flex-col">
                                    <h3 class="text-sm md:text-lg font-bold text-gray-800 dark:text-white"><i class="fas fa-user"></i> {{ $checkIn->user->name }}</h3>
                                    <p class="text-xs md:text-sm text-gray-600 dark:text-gray-100 mt-1"><i class="fas fa-clock"></i> Check-In: {{ \Carbon\Carbon::parse($checkIn->check_in_time)->format('H:i') }} ({{ \Carbon\Carbon::parse($checkIn->check_in_time)->diffForHumans() }})</p>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                <!-- Fitur Utama Section -->
                <div class="features-section p-8">
                    <h2 class="text-lg md:text-2xl font-bold text-gray-800 dark:text-white mb-4 text-center md:text-left"><i class="fas fa-star"></i> Fitur Utama</h2>
                    <div class="flex flex-wrap justify-start md:justify-center -mx-4">
                        <div class="w-full md:w-1/2 xl:w-1/3 p-4">
                            <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow-md text-left md:text-center">
                                <h3 class="text-sm md:text-xl font-bold text-gray-800 dark:text-white"><i class="fas fa-users-cog"></i> Manajemen Anggota</h3>
                            </div>
                        </div>
                        <div class="w-full md:w-1/2 xl:w-1/3 p-4">
                            <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow-md text-left md:text-center">
                                <h3 class="text-sm md:text-xl font-bold text-gray-800 dark:text-white"><i class="fas fa-id-card"></i> Manajemen Keanggotaan</h3>
                            </div>
                        </div>
                        <div class="w-full md:w-1/2 xl:w-1/3 p-4">
                            <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow-md text-left md:text-center">
                                <h3 class="text-sm md:text-xl font-bold text-gray-800 dark:text-white"><i class="fas fa-running"></i> Manajemen Latihan</h3>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Informasi Terbaru Section -->
                <div class="latest-info-section p-8">
                    <h2 class="text-lg md:text-2xl font-bold text-gray-800 dark:text-white mb-4 text-center md:text-left"> Informasi Terbaru</h2>
                    <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow-md mb-4 text-left md:text-center">
                        <h3 class="text-sm md:text-xl font-bold text-gray-800 dark:text-white"><i class="fas fa-credit-card"></i> Update Fitur Baru: Manajemen Pembayaran</h3>
                    </div>
                    <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow-md text-left md:text-center">
                        <h3 class="text-sm md:text-xl font-bold text-gray-800 dark:text-white"><i class="fas fa-tags"></i> Promo Spesial: Diskon 10% untuk Anggota Baru</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>