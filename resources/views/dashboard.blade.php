<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-base md:text-xl text-gray-800  leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-2 ">
        @if (session('success'))
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mt-4">
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        </div>
        @endif

        @if (session('error'))
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mt-4">
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
        </div>
        @endif
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gray-900 bg-opacity-10 overflow-hidden shadow-xl sm:rounded-lg">
                <!-- Hero Section -->
                <div class="hero-section ml-6 p-2 text-center md:text-left">
                    <h1 class="text-2xl md:text-3xl font-bold text-gray-800 dark:text-white"> Selamat Datang di FortuFitness</h1>
                    <p class="text-sm md:text-lg text-gray-600 dark:text-gray-100">Temukan Potensi Anda dalam Kebugaran</p>
                </div>
                <!-- Kartu Keanggotaan Digital Section -->
                <!-- Kartu Keanggotaan Digital Section -->
                <div class="membership-card-section p-8">
                    <h2 class="text-lg md:text-2xl font-bold text-gray-800 dark:text-white mb-4 text-center md:text-left">
                        <i class="fas fa-id-card"></i> Kartu Keanggotaan Digital
                    </h2>
                    <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow-md text-left md:text-center">
                        @if($membership && $qrCodeImage)
                        <div class="flex justify-center mb-4">
                            <img src="{{ $qrCodeImage }}" alt="QR Code">
                        </div>
                        <p class="text-sm md:text-lg text-gray-600 dark:text-white">
                            <strong>Nama:</strong> {{ $user->name }}
                        </p>
                        <p class="text-sm md:text-lg text-gray-600 dark:text-white">
                            <strong>ID Membership:</strong> {{ $membership->id }}
                        </p>
                        <p class="text-sm md:text-lg text-gray-600 dark:text-white">
                            <strong>Tipe Keanggotaan:</strong> {{ $membershipType ? $membershipType->name : 'N/A' }}
                        </p>
                        @else
                        <p class="text-sm md:text-lg text-gray-600 dark:text-white">
                            Anda belum memiliki keanggotaan aktif. Silakan pilih paket keanggotaan untuk mengaktifkan kartu keanggotaan digital Anda.
                        </p>
                        @endif
                    </div>
                </div>
                <!-- Check-In/Check-Out Section -->
                <div class="checkin-checkout-section p-8">
                    <h2 class="text-lg md:text-2xl font-bold text-gray-800 dark:text-white mb-4 text-center md:text-left">
                        <i class="fas fa-sign-in-alt"></i> Check-In / Check-Out
                    </h2>
                    <div class="p-4 rounded-lg shadow-md text-left md:text-center">
                        @php
                        $activeCheckIn = \App\Models\CheckIn::where('user_id', Auth::id())
                        ->whereNull('check_out_time')
                        ->first();
                        @endphp

                        @if ($activeCheckIn)
                        <div class="bg-white dark:bg-transparent rounded-lg shadow-md p-6 mb-6">
                            <p class="text-lg md:text-xl text-gray-800 dark:text-white font-semibold mb-2">Status Check-in</p>
                            <p class="text-sm md:text-base text-gray-600 dark:text-gray-300 mb-4">
                                Anda sedang check-in sejak:
                                @php
                                $checkInTime = $activeCheckIn->check_in_time;
                                if (is_string($checkInTime)) {
                                $checkInTime = \Carbon\Carbon::parse($checkInTime);
                                }
                                @endphp
                                <span class="font-medium">{{ $checkInTime->format('H:i') }}</span>
                                <span class="text-gray-500 dark:text-gray-400">({{ $checkInTime->diffForHumans() }})</span>
                            </p>
                            <form action="{{ route('member.checkout') }}" method="POST">
                                @csrf
                                <button type="submit" class="bg-yellow hover:bg-gray-300 text-white font-bold py-2 px-4 rounded-full shadow-lg transform transition duration-200 hover:scale-105 focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:ring-opacity-50">
                                    <i class="fas fa-sign-out-alt mr-2"></i> Check Out
                                </button>
                            </form>
                        </div>

                        @endif
                    </div>
                </div>

                <!-- User Info Section -->
                <div class="user-info-section p-8 ">
                    <h2 class="text-lg md:text-2xl font-bold text-gray-800 dark:text-white mb-4 text-center md:text-left">
                        <i class="fas fa-user"></i> Informasi Pengguna
                    </h2>
                    <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow-md text-left grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach([
                        ['icon' => 'fas fa-user-circle', 'label' => 'Nama Pengguna', 'value' => auth()->user()->name],
                        ['icon' => 'fas fa-check-circle', 'label' => 'Status Keanggotaan', 'value' => optional(auth()->user()->membership)->status],
                        ['icon' => 'fas fa-calendar-alt', 'label' => 'Tanggal Bergabung', 'value' => auth()->user()->created_at->format('d M Y')],
                        ['icon' => 'fas fa-calendar-check', 'label' => 'Jumlah Kehadiran', 'value' => auth()->user()->checkIns()->count()],
                        ['icon' => 'fas fa-crown', 'label' => 'Paket Keanggotaan', 'value' => optional(optional(auth()->user()->membership)->membershipType)->name],
                        ['icon' => 'fas fa-hourglass-half', 'label' => 'Sisa Hari Keanggotaan', 'value' => floor(\Carbon\Carbon::parse(optional(auth()->user()->membership)->end_date)->diffInDays(\Carbon\Carbon::now()))],
                        ] as $item)
                        <div class="flex justify-between">
                            <p class="text-sm md:text-lg text-gray-600 dark:text-white flex items-center w-1/2">
                                <i class="{{ $item['icon'] }} mr-2"></i>
                                <strong>{{ $item['label'] }}</strong>
                            </p>
                            <p class="text-sm md:text-lg truncate text-gray-600 dark:text-white w-1/2">:
                                @if($item['value'])
                                {{ $item['value'] }}
                                @else
                                @switch($item['label'])
                                @case('Nama Pengguna')
                                Belum diisi
                                @break
                                @case('Status Keanggotaan')
                                Belum aktif
                                @break
                                @case('Tanggal Bergabung')
                                Belum bergabung
                                @break
                                @case('Jumlah Kehadiran')
                                Belum hadir
                                @break
                                @case('Paket Keanggotaan')
                                Belum memilih paket membership
                                @break
                                @case('Sisa Hari Keanggotaan')
                                Belum ada sisa hari
                                @break
                                @endswitch
                                @endif
                            </p>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Statistik Section -->
                <!-- <div class="stats-section p-8">
                    <div class="flex flex-wrap justify-start md:justify-center -mx-4">
                        <div class="w-full md:w-1/2 xl:w-1/3 p-4 order-1">
                            <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow-md text-left md:text-center">
                                <h2 class="text-lg md:text-2xl font-bold text-gray-800 dark:text-white"><i class="fas fa-users"></i> Anggota</h2>
                                <p class="text-sm md:text-lg text-gray-600 dark:text-gray-100">{{ App\Models\User::count() }}</p>
                            </div>
                        </div>
                    </div>
                </div> -->
                <!-- Kalender Keanggotaan Section -->
                <div class="membership-calendar-section p-8">
                    <h2 class="text-lg md:text-2xl font-bold text-gray-800 dark:text-white mb-4 text-center md:text-left">
                        <i class="fas fa-calendar-alt"></i> Kalender Keanggotaan
                    </h2>
                    <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow-md">
                        @php
                        $startDate = optional(auth()->user()->membership)->start_date ? \Carbon\Carbon::parse(auth()->user()->membership->start_date) : null;
                        $endDate = optional(auth()->user()->membership)->end_date ? \Carbon\Carbon::parse(auth()->user()->membership->end_date) : null;
                        $today = \Carbon\Carbon::now();
                        $totalDays = $startDate && $endDate ? $startDate->diffInDays($endDate) : 0;
                        $remainingDays = $endDate ? ceil($today->diffInDays($endDate, false)) : 0;

                        // Ambil data check-in member
                        $checkIns = auth()->user()->checkIns()
                        ->whereBetween('check_in_time', [$startDate, $endDate])
                        ->pluck('check_in_time')
                        ->map(function($date) {
                        return $date->format('Y-m-d');
                        })
                        ->toArray();
                        @endphp

                        @if($startDate && $endDate)
                        <div class="flex flex-wrap justify-center">
                            @for($i = 0; $i < $totalDays; $i++)
                                @php
                                $currentDate=$startDate->copy()->addDays($i);
                                $isPast = $currentDate->isPast();
                                $isToday = $currentDate->isToday();
                                $hasCheckedIn = in_array($currentDate->format('Y-m-d'), $checkIns);
                                @endphp
                                <div class="w-8 h-8 m-1 rounded-full flex items-center justify-center text-xs
                        @if($hasCheckedIn)
                            bg-yellow dark:bg-yellow
                        @elseif($isPast)
                            bg-gray-200 dark:bg-gray-600
                        @else
                            bg-gray-800 dark:bg-gray-400
                        @endif
                        ">
                                    {{ $currentDate->format('d') }}
                                </div>
                                @endfor
                        </div>
                        <div class="mt-4 text-center">
                            <p class="text-sm md:text-base text-gray-600 dark:text-white">
                                Sisa masa keanggotaan: <span class="font-bold">{{ $remainingDays }} hari</span>
                            </p>
                            <p class="text-xs md:text-sm text-gray-500 dark:text-gray-300 mt-2">
                                Mulai: {{ $startDate->format('d M Y') }} | Berakhir: {{ $endDate->format('d M Y') }}
                            </p>
                        </div>
                        @else
                        <p class="text-sm md:text-base text-gray-600 dark:text-white text-center">
                            Anda belum memiliki keanggotaan aktif.
                        </p>
                        @endif
                    </div>
                </div>
                <!-- Daftar Member Check-In Section -->
                <!-- Daftar Member Check-In Section -->
                <div class="checkin-section p-8">
                    <h2 class="text-lg md:text-2xl font-bold text-gray-800 dark:text-white mb-4 text-center md:text-left"><i class="fas fa-user-check"></i> Daftar Member yang Sedang Check-In</h2>
                    <div class="flex flex-wrap justify-center -mx-4 overflow-y-auto h-64">
                        @foreach(App\Models\CheckIn::whereNull('check_out_time')->get() as $checkIn)
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
                                    <p class="text-xs md:text-sm text-gray-600 dark:text-gray-100 mt-1">
                                        <i class="fas fa-clock"></i> Check-In:
                                        @php
                                        $checkInTime = $checkIn->check_in_time;
                                        if (is_string($checkInTime)) {
                                        $checkInTime = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $checkInTime);
                                        }
                                        @endphp
                                        {{ $checkInTime->format('H:i') }}
                                        ({{ $checkInTime->diffForHumans() }})
                                    </p>
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
    </div>
</x-app-layout>