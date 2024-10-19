<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CheckIn;
use Illuminate\Support\Carbon;
use App\Charts\CheckinWeeksChart;
use Illuminate\Support\Facades\Cache;

class MemberController extends Controller
{
    public function index(CheckinWeeksChart $chart)
    {
        try {
            $user = auth()->user();
            $visits = CheckIn::where('user_id', $user->id)
                ->orderBy('check_in_time', 'desc')
                ->paginate(10);

            $cacheKey = "user_{$user->id}_stats";
            $stats = Cache::remember($cacheKey, now()->addHours(1), function () use ($user, $visits) {
                return [
                    'totalVisits' => $visits->total(),
                    'averageDuration' => $this->calculateAverageDuration($visits),
                    'visitsThisMonth' => $this->getVisitsThisMonth($user->id),
                ];
            });

            $checkinChart = $chart->build();

            return view('visit-history', array_merge(compact('visits', 'checkinChart'), $stats));
        } catch (\Exception $e) {
            \Log::error('Error in MemberController@index: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat memuat data.');
        }
    }

    private function calculateAverageDuration($visits)
    {
        $totalDuration = 0;
        $count = 0;

        foreach ($visits as $visit) {
            if ($visit->check_out_time) {
                $checkInTime = Carbon::parse($visit->check_in_time);
                $checkOutTime = Carbon::parse($visit->check_out_time);

                if ($checkOutTime->gt($checkInTime)) {
                    $duration = $checkOutTime->diffInMinutes($checkInTime);
                    $totalDuration += $duration;
                    $count++;
                }
            }
        }

        return $count > 0 ? round($totalDuration / $count) : 0;
    }

    private function getVisitsThisMonth($userId)
    {
        return CheckIn::where('user_id', $userId)
            ->whereMonth('check_in_time', Carbon::now()->month)
            ->count();
    }
}
