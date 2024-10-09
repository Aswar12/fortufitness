<?php

namespace App\Http\Controllers;

use App\Models\Membership;
use App\Models\CheckIn;
use Illuminate\Http\Request;

class CheckInController extends Controller
{
    public function store(Request $request)
    {
        $membershipId = $request->input('membership_id');
        // Retrieve the membership and user information from the database
        $membership = Membership::find($membershipId);
        $user = $membership->user;
        // Create a new check-in record
        $checkIn = new CheckIn();
        $checkIn->user_id = $user->id;
        $checkIn->check_in_time = now();
        $checkIn->check_in_method = 'Scan QR Code';
        $checkIn->save();

        // Return a success response
        return response()->json(['message' => 'Check-in successful']);
    }
}
