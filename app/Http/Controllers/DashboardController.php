<?php
// DashboardController.php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Illuminate\Support\Facades\Cache;
use App\Models\MembershipType;

class DashboardController extends Controller
{
    const ACTIVE = 'active';
    const PENDING = 'pending';
    const COMPLETED = 'completed';
    const CANCELLED = 'cancelled';

    public function index()
    {
        $currentUser = Auth::user();
        $availableMembershipTypes = MembershipType::all();
        $currentMembership = $this->getCurrentMembership($currentUser);

        $qrCodeImage = $this->getQrCodeImage($currentMembership);

        return view('dashboard', compact('currentUser', 'currentMembership', 'availableMembershipTypes', 'qrCodeImage'));
    }

    private function getCurrentMembership($user)
    {
        return $user->memberships()
            ->where(function ($query) {
                $query->where('status', self::ACTIVE)
                    ->orWhere(function ($q) {
                        $q->where('status', self::PENDING)
                            ->whereHas('payments', function ($p) {
                                $p->where('status', self::PENDING);
                            });
                    });
            })
            ->with('membershipType')
            ->latest()
            ->first();
    }

    private function getQrCodeImage($membership)
    {
        if (!$membership || $membership->status != self::ACTIVE) {
            return null;
        }

        $qrCodeKey = "qr_code_" . $membership->id;

        return Cache::remember($qrCodeKey, now()->addDays(1), function () use ($membership) {
            try {
                $qrCode = new QrCode($membership->id);
                $writer = new PngWriter();
                $result = $writer->write($qrCode);
                return $result->getDataUri();
            } catch (\Exception $e) {
                return null;
            }
        });
    }
}
