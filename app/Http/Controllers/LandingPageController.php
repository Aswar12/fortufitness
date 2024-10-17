<?php

namespace App\Http\Controllers;

use App\Models\MembershipType;
use Illuminate\Http\Request;
use App\Models\User;

class LandingPageController extends Controller
{
    public function index()
    {
        $membershipTypes = MembershipType::all();
        $gymMembers = User::where('role', 'member')->take(3)->get();
        return view('index', compact('gymMembers', 'membershipTypes'));
    }
}
