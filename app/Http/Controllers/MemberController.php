<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CheckIn;
use Illuminate\Support\Carbon;
use ArielMejiaDev\LarapexCharts\LarapexChart;
use App\Charts\CheckinWeeksChart;

class MemberController extends Controller
{
    public function index(CheckinWeeksChart $chart)
    {
        $user = auth()->user();
        $visits = CheckIn::where('user_id', $user->id)
            ->orderBy('check_in_time', 'desc')
            ->paginate(10);

        $totalVisits = $visits->total();
        $averageDuration = $this->calculateAverageDuration($visits);
        $frequencyPerWeek = $this->calculateFrequencyPerWeek($visits);
        $visitsThisMonth = CheckIn::where('user_id', $user->id)
            ->whereMonth('check_in_time', Carbon::now()->month)
            ->count();

        $checkinChart = $chart->build();

        return view('visit-history', compact('visits', 'totalVisits', 'averageDuration', 'frequencyPerWeek', 'visitsThisMonth', 'checkinChart'));
    }

    private function calculateAverageDuration($visits)
    {
        $totalDuration = 0;
        $count = 0;

        foreach ($visits as $visit) {
            if ($visit->check_out_time) {
                $checkInTime = Carbon::parse($visit->check_in_time);
                $checkOutTime = Carbon::parse($visit->check_out_time);

                // Pastikan check_out_time selalu lebih besar dari check_in_time
                if ($checkOutTime->gt($checkInTime)) {
                    $duration =  abs($checkOutTime->diffInMinutes($checkInTime));
                    $totalDuration += $duration;
                    $count++;
                }
            }
        }

        // Mengembalikan rata-rata durasi dalam menit, dibulatkan ke bilangan bulat terdekat
        return $count > 0 ? round($totalDuration / $count) : 0;
    }

    private function calculateFrequencyPerWeek($visits)
    {
        if ($visits->isEmpty()) {
            return 0;
        }
        $firstVisit = $visits->sortBy('check_in_time')->first();
        $weekCount = Carbon::parse($firstVisit->check_in_time)->diffInWeeks(Carbon::now()) + 1;
        return $weekCount > 0 ? round($visits->total() / $weekCount, 2) : 0;
    }
}
