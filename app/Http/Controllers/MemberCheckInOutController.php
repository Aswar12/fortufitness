<?php

namespace App\Http\Controllers;

use App\Models\CheckIn;
use App\Helpers\CheckInOutService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class MemberCheckInOutController extends Controller
{
    protected $checkInOutService;

    public function __construct(CheckInOutService $checkInOutService)
    {
        $this->checkInOutService = $checkInOutService;
       
    }

    public function checkIn(Request $request)
    {
        try {
            $result = $this->checkInOutService->checkIn(Auth::id());
            if ($result) {
                Log::info('User  ' . Auth::id() . ' checked in successfully');
                return redirect()->route('dashboard')->with('success', 'Check-in berhasil.');
            }
            return redirect()->route('dashboard')->with('error', 'Anda sudah melakukan check-in sebelumnya atau tidak memiliki membership aktif.');
        } catch (\Exception $e) {
            Log::error('Check-in failed for user ' . Auth::id() . ': ' . $e->getMessage());
            return redirect()->route('dashboard')->with('error', 'Terjadi kesalahan saat melakukan check-in.');
        }
    }

    public function checkOut(Request $request)
    {
        try {
            $result = $this->checkInOutService->checkOut(Auth::id());
            if ($result) {
                Log::info('User  ' . Auth::id() . ' checked out successfully');
                return redirect()->route('dashboard')->with('success', 'Check-out berhasil.');
            }
            return redirect()->route('dashboard')->with('error', 'Tidak ada check-in aktif.');
        } catch (\Exception $e) {
            Log::error('Check-out failed for user ' . Auth::id() . ': ' . $e->getMessage());
            return redirect()->route('dashboard')->with('error', 'Terjadi kesalahan saat melakukan check-out.');
        }
    }

    public function history(Request $request)
    {
        try {
            $history = $this->checkInOutService->getUserHistory(Auth::id(), $request->get('page', 1));
            return view('member.check-in-history', compact('history'));
        } catch (\Exception $e) {
            Log::error('Failed to retrieve check-in history for user ' . Auth::id() . ': ' . $e->getMessage());
            return redirect()->route('dashboard')->with('error', 'Terjadi kesalahan saat mengambil riwayat check-in.');
        }
    }

    public function stats(Request $request)
    {
        try {
            $stats = $this->checkInOutService->getUserStats(Auth::id());
            return view('member.check-in-stats', compact('stats'));
        } catch (\Exception $e) {
            Log::error('Failed to retrieve check-in stats for user ' . Auth::id() . ': ' . $e->getMessage());
            return redirect()->route('dashboard')->with('error', 'Terjadi kesalahan saat mengambil statistik check-in.');
        }
    }
}