<?php

namespace App\Helpers;

use App\Models\CheckIn;
use App\Models\Membership; // Assuming you might need this for membership checks
use Illuminate\Support\Facades\Auth;

class CheckInOutService
{
    public function checkIn($userId)
    {
        // Check if the user has an active membership
        $activeMembership = Membership::where('user_id', $userId)
            ->where('status', 'active')
            ->where('end_date', '>=', now())
            ->first();

        if (!$activeMembership) {
            return false; // User does not have an active membership
        }

        // Check if the user has already checked in
        $activeCheckIn = CheckIn::where('user_id', $userId)
            ->whereNull('check_out_time') // Check if check_out_time is null
            ->first();

        if ($activeCheckIn) {
            return false; // User has already checked in
        }

        // Create a new check-in record
        CheckIn::create([
            'user_id' => $userId,
            'check_in_time' => now(),
            'check_out_time' => null, // Set to null initially
            'check_in_method' => 'manual', // You can change this based on your logic
        ]);

        return true; // Check-in successful
    }

    public function checkOut($userId)
    {
        // Find the active check-in record
        $checkIn = CheckIn::where('user_id', $userId)
            ->whereNull('check_out_time') // Check if check_out_time is null
            ->first();

        if (!$checkIn) {
            return false; // No active check-in found
        }

        // Update the check-in record to mark it as checked out
        $checkIn->update([
            'check_out_time' => now(), // Set the current time as check_out_time
        ]);

        return true; // Check-out successful
    }

    public function getUserHistory($userId, $page = 1, $perPage = 10)
    {
        // Retrieve the user's check-in history with pagination
        return CheckIn::where('user_id', $userId)
            ->orderBy('check_in_time', 'desc')
            ->paginate($perPage, ['*'], 'page', $page);
    }

    public function getUserStats($userId)
    {
        // Retrieve statistics for the user
        return [
            'total_check_ins' => CheckIn::where('user_id', $userId)->count(),
            'total_check_outs' => CheckIn::where('user_id', $userId)->whereNotNull('check_out_time')->count(),
            'active_check_in' => CheckIn::where('user_id', $userId)->whereNull('check_out_time')->exists(),
        ];
    }
}