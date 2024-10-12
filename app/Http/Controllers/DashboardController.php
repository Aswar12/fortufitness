<?php

namespace App\Http\Controllers;
// In your DashboardController.php file
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Illuminate\Support\Facades\Cache;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $membership = $user->membership;
        $membershipType = $membership ? $membership->membershipType : null;

        $qrCodeKey = "qr_code_" . ($membership ? $membership->id : 'no_membership');

        $qrCodeImage = Cache::remember($qrCodeKey, now()->addDays(1), function () use ($membership) {
            try {
                $qrCode = new QrCode($membership ? $membership->id : 'No Membership');
                $writer = new PngWriter();
                $result = $writer->write($qrCode);
                return $result->getDataUri();
            } catch (\Exception $e) {
                \Log::error("Failed to generate QR Code: " . $e->getMessage());
                return null;
            }
        });

        return view('dashboard', compact('user', 'membership', 'membershipType', 'qrCodeImage'));
    }
}
