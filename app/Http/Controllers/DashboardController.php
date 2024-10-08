<?php

namespace App\Http\Controllers;
// In your DashboardController.php file
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;

class DashboardController extends Controller
{
    public function index()
    {

        $user = Auth::user();
        $membership = $user->membership;
        $membershipType = $membership ? $membership->membershipType : null;

        $qrCode = new QrCode($membership ? $membership->id : 'No Membership');
        $writer = new PngWriter([
            'width' => 150,
            'height' => 150,
            'padding' => 10,
        ]);

        $result = $writer->write($qrCode);

        return view('dashboard', compact('user', 'membership', 'membershipType', 'result'));
    }
}
