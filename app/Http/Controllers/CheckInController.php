<?php

namespace App\Http\Controllers;

use App\Models\Membership;
use App\Models\CheckIn;
use Illuminate\Http\Request;

class CheckInController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'membership_id' => 'required|exists:memberships,id',
        ]);

        $membershipId = $request->input('membership_id');
        $membership = Membership::findOrFail($membershipId);
        $user = $membership->user;

        // Authorization check
        if (!$user->can('check-in')) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        try {
            $checkIn = CheckIn::create([
                'user_id' => $user->id,
                'check_in_time' => now(),
                'check_in_method' => 'Scan QR Code',
            ]);

            // Logging
            \Log::info("User {$user->id} checked in at " . $checkIn->check_in_time);

            return response()->json(['message' => 'Check-in successful']);
        } catch (\Exception $e) {
            \Log::error("Check-in failed for user {$user->id}: " . $e->getMessage());
            return response()->json(['message' => 'Check-in failed'], 500);
        }
    }
}
