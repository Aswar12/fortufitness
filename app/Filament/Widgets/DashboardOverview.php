<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;
use App\Models\User;
use App\Models\Membership;
use App\Models\CheckIn;
use Carbon\Carbon;

class DashboardOverview extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getCards(): array
    {
        return [
            Card::make('Total Member', $this->getTotalMembers())
                ->description('Jumlah Member')
                ->descriptionIcon('heroicon-o-users')
                ->color('success'),
            Card::make('Keanggotaan Aktif', $this->getActiveMemberships())
                ->description('Keanggotaan aktif saat ini')
                ->descriptionIcon('heroicon-o-check-badge')
                ->color('primary'),
            Card::make('Check-in Hari Ini', $this->getTodayCheckIns())
                ->description('Member yang check-in hari ini')
                ->descriptionIcon('heroicon-o-arrow-right-on-rectangle')
                ->color('warning'),
            Card::make('Rata-rata Waktu Check-in', $this->getAverageCheckInTime())
                ->description('Jam Ramai')
                ->descriptionIcon('heroicon-o-clock')
                ->color('info'),
        ];
    }

    private function getTotalMembers(): int
    {
        return User::where('role', 'member')->count();
    }

    private function getActiveMemberships(): int
    {
        return Membership::where('status', 'active')->count();
    }

    private function getTodayCheckIns(): int
    {
        return CheckIn::whereDate('created_at', today())->count();
    }

    private function getAverageCheckInTime(): string
    {
        $averageTime = CheckIn::selectRaw('AVG(TIME_TO_SEC(TIME(created_at))) as avg_time')
            ->first()
            ->avg_time;

        if ($averageTime) {
            return Carbon::createFromTimestampUTC($averageTime)->format('H:i');
        }

        return 'Tidak Ada Data';
    }
}
