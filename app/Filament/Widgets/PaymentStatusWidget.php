<?php

namespace App\Filament\Widgets;

use App\Models\Payment;
use Carbon\Carbon;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;
use Illuminate\Support\Facades\DB;

class PaymentStatusWidget extends BaseWidget
{
    protected static ?int $sort = 4;

    protected function getCards(): array
    {
        $now = Carbon::now();
        $startOfMonth = $now->startOfMonth();
        $endOfMonth = $now->copy()->endOfMonth();

        $pendingPayments = Payment::where('status', 'pending');
        $pendingCount = $pendingPayments->count();
        $pendingTotal = $pendingPayments->sum('amount');

        $monthlyPayments = Payment::whereBetween('created_at', [$startOfMonth, $endOfMonth]);
        $successfulPayments = $monthlyPayments->where('status', 'completed')->count();
        $failedPayments = $monthlyPayments->where('status', 'failed')->count();
        $totalPayments = $successfulPayments + $failedPayments;
        $successRate = $totalPayments > 0 ? ($successfulPayments / $totalPayments) * 100 : 0;

        $popularPaymentMethod = Payment::select('payment_method', DB::raw('count(*) as total'))
            ->groupBy('payment_method')
            ->orderByDesc('total')
            ->first();

        return [
            Card::make('Pembayaran Tertunda', $pendingCount)
                ->description('Jumlah pembayaran tertunda')
                ->descriptionIcon('heroicon-s-clock')
                ->color('warning'),

            Card::make('Total Pembayaran Tertunda', 'Rp ' . number_format($pendingTotal, 0, ',', '.'))
                ->description('Nilai total pembayaran tertunda')
                ->descriptionIcon('heroicon-s-currency-dollar')
                ->color('warning'),

            Card::make('Tingkat Keberhasilan Bulan Ini', number_format($successRate, 1) . '%')
                ->description($successfulPayments . ' berhasil vs ' . $failedPayments . ' gagal')
                ->descriptionIcon('heroicon-s-chart-bar')
                ->color('success'),

            Card::make('Metode Pembayaran Terpopuler', $popularPaymentMethod ? $popularPaymentMethod->payment_method : 'Tidak Ada')
                ->description('Metode pembayaran yang paling sering digunakan')
                ->descriptionIcon('heroicon-s-credit-card')
                ->color('primary'),
        ];
    }
}
