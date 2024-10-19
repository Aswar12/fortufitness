<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-base md:text-xl text-gray-800 leading-tight">
            {{ __('Riwayat Kunjungan') }}
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
                <!-- Riwayat Kunjungan Section -->
                <div class="visit-history-section p-4 md:p-8">
                    <h2 class="text-lg md:text-2xl font-bold text-gray-800 dark:text-white mb-4 text-center md:text-left">
                        <i class="fas fa-history"></i> Riwayat Kunjungan
                    </h2>
                    <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow-md overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Tanggal</th>
                                    <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Check-In</th>
                                    <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Check-Out</th>
                                    <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Durasi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-700">
                                @foreach($visits as $visit)
                                <tr>
                                    <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                        {{ $visit->check_in_time->format('d-m-Y') }}
                                    </td>
                                    <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                        {{ $visit->check_in_time->format('H:i') }}
                                    </td>
                                    <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                        {{ $visit->check_out_time ? $visit->check_out_time->format('H:i') : 'Belum Check-Out' }}
                                    </td>
                                    <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                        @if($visit->check_out_time)
                                        {{ $visit->check_in_time->diff($visit->check_out_time)->format('%H:%I') }}
                                        @else
                                        Masih Berlangsung
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Pagination -->
                <div class="mt-4 p-4">
                    {{ $visits->links() }}
                </div>

                <!-- Statistik Kunjungan Section -->
                <div class="visit-stats-section p-8">
                    <h2 class="text-lg md:text-2xl font-bold text-gray-800 dark:text-white mb-4 text-center md:text-left">
                        <i class="fas fa-chart-bar"></i> Statistik Kunjungan
                    </h2>
                    <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow-md grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="text-center">
                            <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-300">Total Kunjungan</h3>
                            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $totalVisits }}</p>
                        </div>
                        <div class="text-center">
                            <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-300">Kunjungan Bulan Ini</h3>
                            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $visitsThisMonth }}</p>
                        </div>
                        <div class="text-center">
                            <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-300">Rata-rata Durasi</h3>
                            <p class="text-2xl font-bold text-gray-900 dark:text-white">@if ($averageDuration > 60)
                                {{ floor($averageDuration / 60) }} jam {{ $averageDuration % 60 }} menit
                                @else
                                {{ $averageDuration }} menit
                                @endif
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Grafik Kunjungan Section -->
                <div class="visit-chart-section p-8">
                    <h2 class="text-lg md:text-2xl font-bold text-gray-800 dark:text-white mb-4 text-center md:text-left">
                        <i class="fas fa-chart-line"></i> Grafik Kunjungan
                    </h2>
                    <div class="bg-gray-200  p-4 rounded-lg shadow-md">
                        <div class="card">
                            <div class="card-body">
                                {!! $checkinChart->container() !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="{{ $checkinChart->cdn() }}"></script>
    {{ $checkinChart->script() }}
    @endpush
</x-app-layout>