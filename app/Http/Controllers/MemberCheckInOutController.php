<?php

namespace App\Http\Controllers;

use App\Models\CheckIn;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MemberCheckInOutController extends Controller
{
    public function checkin(Request $request)
    {
        $user_id = Auth::id();
        $activeCheckIn = CheckIn::where('user_id', $user_id)
            ->whereNull('check_out_time')
            ->first();

        if ($activeCheckIn) {
            return redirect()->route('dashboard')->with('error', 'Anda sudah melakukan check-in sebelumnya.');
        }

        CheckIn::create([
            'user_id' => $user_id,
            'check_in_time' => now(),
            'check_in_method' => 'manual',
        ]);

        return redirect()->route('dashboard')->with('success', 'Check-in berhasil.');
    }

    public function checkout(Request $request)
    {
        $user_id = Auth::id();
        $activeCheckIn = CheckIn::where('user_id', $user_id)
            ->whereNull('check_out_time')
            ->first();

        if (!$activeCheckIn) {
            return redirect()->route('dashboard')->with('error', 'Tidak ada check-in aktif.');
        }

        $activeCheckIn->update([
            'check_out_time' => now(),
        ]);

        return redirect()->route('dashboard')->with('success', 'Check-out berhasil.');
    }
}
