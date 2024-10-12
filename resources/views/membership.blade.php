<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-base md:text-xl text-gray-800 leading-tight">
            {{ __('Jenis Keanggotaan') }}
        </h2>
    </x-slot>

    <div class="py-2">
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
                <div class="p-8">
                    <div class="membership-section p-8">
                        <h2 class="text-lg md:text-2xl font-bold text-gray-800 dark:text-white mb-4 text-center md:text-left">
                            <i class="fas fa-id-card"></i> Keanggotaan
                        </h2>

                        @if($membership && $membership->status == 'active')
                        <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow-md text-left md:text-center mb-6">
                            @if($qrCodeImage && $membership->status == 'active')
                            <div class="flex justify-center mb-4">
                                <img src="{{ $qrCodeImage }}" alt="QR Code">
                            </div>
                            @endif
                            <p class="text-sm md:text-lg text-gray-600 dark:text-white">
                                <strong>Nama:</strong> {{ $user->name }}
                            </p>
                            <p class="text-sm md:text-lg text-gray-600 dark:text-white">
                                <strong>ID Membership:</strong> {{ $membership->id }}
                            </p>
                            <p class="text-sm md:text-lg text-gray-600 dark:text-white">
                                <strong>Tipe Keanggotaan:</strong> {{ $membership->membershipType->name }}
                            </p>
                            <p class="text-sm md:text-lg text-gray-600 dark:text-white">
                                <strong>Berakhir pada:</strong> {{ $membership->end_date->format('d M Y') }}
                            </p>
                            <p class="text-sm md:text-lg text-gray-600 dark:text-white">
                                <strong>Status:</strong> {{ ucfirst($membership->status) }}
                            </p>
                            <div class="mt-4">
                                <form action="{{ route('memberships.extend', $membership->id) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded-full shadow-lg mr-2">
                                        <i class="fas fa-sync-alt mr-2"></i> Perpanjang
                                    </button>
                                </form>
                                <form action="{{ route('memberships.cancel', $membership->id) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded-full shadow-lg" onclick="return confirm('Apakah Anda yakin ingin membatalkan keanggotaan?')">
                                        <i class="fas fa-times mr-2"></i> Batalkan
                                    </button>
                                </form>
                            </div>
                        </div>
                        @elseif($membership && $membership->status == 'cancelled')
                        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">
                            <p class="font-bold">Keanggotaan Dibatalkan</p>
                            <p>Keanggotaan Anda telah dibatalkan. Silakan pilih paket baru untuk mengaktifkan kembali keanggotaan Anda.</p>
                        </div>
                        @elseif($membership && $membership->status == 'pending')
                        <div class="bg-gray-300 border-l-4 border-yellow text-yellow-700 p-4 mb-4" role="alert">
                            <p class="font-bold">Menunggu Pembayaran</p>
                            <p>Silahkan selesaikan pembayaran Anda untuk paket {{ $membership->membershipType->name }}.</p>
                        </div>
                        @if($membership->latestPayment)
                        <a href="{{ route('payments.process', $membership->latestPayment->id) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-2 px-4 rounded-full shadow-lg transform transition duration-200 hover:scale-105 focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:ring-opacity-50">
                            <i class="fas fa-credit-card mr-2"></i> Proses Pembayaran
                        </a>
                        @endif
                        @endif

                        @if(!$membership || $membership->status == 'cancelled')
                        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach($membershipTypes as $membershipType)
                            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
                                <h2 class="text-xl font-semibold mb-2 text-gray-800 dark:text-white">{{ $membershipType->name }}</h2>
                                <p class="text-gray-600 dark:text-gray-300 mb-4">{{ $membershipType->description }}</p>
                                <p class="text-lg font-bold mb-2 text-gray-800 dark:text-white">Rp {{ number_format($membershipType->price, 0, ',', '.') }}</p>
                                <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">Durasi: {{ $membershipType->duration }} hari</p>
                                <form action="{{ route('memberships.purchase', $membershipType->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-2 px-4 rounded-full shadow-lg transform transition duration-200 hover:scale-105 focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:ring-opacity-50">
                                        <i class="fas fa-shopping-cart mr-2"></i> Pilih Keanggotaan
                                    </button>
                                </form>
                            </div>
                            @endforeach
                        </div>
                        @endif
                    </div>

                    <!-- Informasi Tambahan dan FAQ Section -->
                    <div class="mt-12">
                        <h2 class="text-2xl font-bold text-gray-800 dark:text-white mb-6">Informasi Tambahan dan FAQ</h2>
                        <div class="grid md:grid-cols-2 gap-8">
                            <!-- Informasi Tambahan -->
                            <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md">
                                <h3 class="text-xl font-semibold text-gray-800 dark:text-white mb-4">Keuntungan Menjadi Anggota:</h3>
                                <ul class="list-disc list-inside text-gray-600 dark:text-gray-300">
                                    <li>Akses penuh ke semua fasilitas gym</li>
                                    <li>Konsultasi gratis dengan pelatih profesional</li>
                                    <li>Diskon untuk kelas-kelas khusus</li>
                                    <li>Prioritas booking untuk kelas populer</li>
                                </ul>
                            </div>

                            <!-- FAQ -->
                            <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md">
                                <h3 class="text-xl font-semibold text-gray-800 dark:text-white mb-4">Pertanyaan Umum</h3>
                                <div class="space-y-4">
                                    <div>
                                        <h4 class="text-lg font-semibold text-gray-800 dark:text-white mb-2">Bagaimana cara memperbarui keanggotaan?</h4>
                                        <p class="text-gray-600 dark:text-gray-300">Anda dapat memperbarui keanggotaan Anda kapan saja dengan memilih paket baru dari daftar di atas.</p>
                                    </div>
                                    <div>
                                        <h4 class="text-lg font-semibold text-gray-800 dark:text-white mb-2">Apakah saya bisa membatalkan keanggotaan?</h4>
                                        <p class="text-gray-600 dark:text-gray-300">Ya, Anda dapat membatalkan keanggotaan Anda kapan saja. Silakan hubungi tim layanan pelanggan kami untuk informasi lebih lanjut.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>