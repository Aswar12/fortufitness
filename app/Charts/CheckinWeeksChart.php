<?php

namespace App\Charts;

use ArielMejiaDev\LarapexCharts\LarapexChart;
use Carbon\Carbon;
use App\Models\CheckIn;

class CheckinWeeksChart
{
    protected $chart;

    public function __construct(LarapexChart $chart)
    {
        $this->chart = $chart;
    }

    public function build(): LarapexChart
    {
        $user = auth()->user();
        $chartLabels = ['Minggu 1', 'Minggu 2', 'Minggu 3', 'Minggu 4'];
        $chartData = [
            CheckIn::where('user_id', $user->id)
                ->whereBetween('check_in_time', [Carbon::now()->startOfMonth(), Carbon::now()->startOfMonth()->addWeek()])
                ->count(),
            CheckIn::where('user_id', $user->id)
                ->whereBetween('check_in_time', [Carbon::now()->startOfMonth()->addWeek(), Carbon::now()->startOfMonth()->addWeeks(2)])
                ->count(),
            CheckIn::where('user_id', $user->id)
                ->whereBetween('check_in_time', [Carbon::now()->startOfMonth()->addWeeks(2), Carbon::now()->startOfMonth()->addWeeks(3)])
                ->count(),
            CheckIn::where('user_id', $user->id)
                ->whereBetween('check_in_time', [Carbon::now()->startOfMonth()->addWeeks(3), Carbon::now()->endOfMonth()])
                ->count()
        ];

        return $this->chart->lineChart()
            ->setTitle('Kunjungan per Minggu')
            ->setSubtitle('Bulan ini')
            ->addData('Kunjungan', $chartData)
            ->setXAxis($chartLabels);
    }
}
