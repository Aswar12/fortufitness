<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MembershipType;
use App\Models\Membership;
use App\Models\Payment;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;

class MembershipController extends Controller
{
    const ACTIVE = 'active';
    const PENDING = 'pending';
    const COMPLETED = 'completed';
    const CANCELLED = 'cancelled';
    public function index()
    {
        $user = Auth::user();
        $membershipTypes = MembershipType::all();
        $membership = $user->memberships()
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

        $qrCodeImage = null;
        if ($membership && $membership->status == self::ACTIVE) {
            $qrCodeKey = "qr_code_" . $membership->id;
            $qrCodeImage = Cache::remember($qrCodeKey, now()->addDays(1), function () use ($membership) {
                try {
                    $qrCode = new QrCode($membership->id);
                    $writer = new PngWriter();
                    $result = $writer->write($qrCode);
                    return $result->getDataUri();
                } catch (\Exception $e) {
                    \Log::error("Failed to generate QR Code: " . $e->getMessage());
                    return null;
                }
            });
        }

        return view('membership', compact('user', 'membershipTypes', 'membership', 'qrCodeImage'));
    }

    public function purchase(Request $request, MembershipType $membershipType)
    {
        $user = Auth::user();

        // Cek apakah user sudah memiliki membership aktif
        if ($user->membership && $user->membership->isActive()) {
            return redirect()->route('memberships.index')->with('error', 'Anda sudah memiliki keanggotaan aktif.');
        }

        // Hitung tanggal mulai dan berakhir
        $startDate = now();
        $endDate = $startDate->copy()->addDays($membershipType->duration);

        // Buat membership baru
        $membership = new Membership([
            'user_id' => $user->id,
            'membership_type_id' => $membershipType->id,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'status' => self::PENDING, // Ubah status menjadi 'pending' sampai pembayaran selesai
        ]);

        $membership->save();

        // Buat entri pembayaran
        $payment = new Payment([
            'membership_id' => $membership->id,
            'amount' => $membershipType->price,
            'payment_date' => now(),
            'payment_method' => $request->payment_method ?? 'Cash',
            'status' => self::PENDING
        ]);

        $payment->save();

        // Di sini Anda bisa menambahkan logika untuk proses pembayaran
        // Misalnya, redirect ke halaman pembayaran atau integrasi dengan payment gateway

        return redirect()->route('payments.process', $payment->id)->with('success', 'Silakan selesaikan pembayaran Anda.');
    }

    public function cancel(Membership $membership)
    {
        $user = Auth::user();

        if (!$membership || $membership->user_id !== $user->id || $membership->status !== self::ACTIVE) {
            return redirect()->route('memberships.index')->with('error', 'Anda tidak memiliki keanggotaan aktif untuk dibatalkan.');
        }

        $membership->status = self::CANCELLED;
        $membership->cancelled_at = now();
        $membership->save();

        return redirect()->route('memberships.index')->with('success', 'Keanggotaan Anda telah dibatalkan.');
    }

    public function extend(Request $request, Membership $membership)
    {
        $user = Auth::user();

        if (!$membership || $membership->user_id !== $user->id || $membership->status !== self::ACTIVE) {
            return redirect()->route('memberships.index')->with('error', 'Keanggotaan tidak valid untuk diperpanjang.');
        }

        $membershipType = $membership->membershipType;

        // Buat entri pembayaran untuk perpanjangan
        $payment = new Payment([
            'membership_id' => $membership->id,
            'amount' => $membershipType->price,
            'payment_date' => now(),
            'payment_method' => $request->payment_method ?? 'pending',
            'status' => self::PENDING
        ]);

        $payment->save();

        // Perpanjang tanggal berakhir keanggotaan
        $membership->end_date = Carbon::parse($membership->end_date)->addDays($membershipType->duration);
        $membership->save();

        return redirect()->route('payments.process', $payment->id)->with('success', 'Silakan selesaikan pembayaran untuk perpanjangan keanggotaan Anda.');
    }

    public function confirmPayment(Payment $payment)
    {
        // Metode ini akan dipanggil setelah pembayaran berhasil diverifikasi
        // Ini bisa dilakukan melalui webhook dari payment gateway atau melalui konfirmasi manual

        $membership = $payment->membership;

        if ($payment->status !== self::PENDING) {
            return redirect()->route('memberships.index')->with('error', 'Pembayaran ini sudah diproses sebelumnya.');
        }

        $payment->status = self::COMPLETED;
        $payment->save();

        if ($membership->status === self::PENDING) {
            // Ini adalah pembelian keanggotaan baru
            $membership->status = self::ACTIVE;
            $membership->save();
            $message = 'Keanggotaan baru Anda telah aktif.';
        } else if ($membership->status === self::ACTIVE) {
            // Ini adalah perpanjangan keanggotaan
            $membership->end_date = Carbon::parse($membership->end_date)->addDays($membership->membershipType->duration);
            $membership->save();
            $message = 'Keanggotaan Anda telah berhasil diperpanjang.';
        }

        return redirect()->route('memberships.index')->with('success', $message);
    }

    public function history()
    {
        $user = Auth::user();
        $memberships = $user->memberships()->with('membershipType', 'payments')->get();

        return view('memberships.history', compact('memberships'));
    }
    public function qrCode(Membership $membership)
    {
        $qrCodeKey = "qr_code_" . $membership->id;
        $qrCodeImage = Cache::remember($qrCodeKey, now()->addDays(1), function () use ($membership) {
            try {
                $qrCode = new QrCode($membership->id);
                $writer = new PngWriter();
                $result = $writer->write($qrCode);
                return $result->getDataUri();
            } catch (\Exception $e) {
                \Log::error("Failed to generate QR Code: " . $e->getMessage());
                return null;
            }
        });

        return response()->json(['qrCodeImage' => $qrCodeImage]);
    }

    public function paymentProcess(Payment $payment)
    {
        $membership = $payment->membership;

        if ($payment->status !== self::PENDING) {
            return redirect()->route('memberships.index')->with('error', 'Pembayaran ini sudah diproses sebelumnya.');
        }

        // Di sini Anda bisa menambahkan logika untuk proses pembayaran
        // Misalnya, redirect ke halaman pembayaran atau integrasi dengan payment gateway

        return view('payments.process', compact('payment', 'membership'));
    }

    public function paymentConfirm(Request $request, Payment $payment)
    {
        $membership = $payment->membership;

        if ($payment->status !== self::PENDING) {
            return redirect()->route('memberships.index')->with('error', 'Pembayaran ini sudah diproses sebelumnya.');
        }

        $payment->status = self::COMPLETED;
        $payment->save();

        if ($membership->status === self::PENDING) {
            // Ini adalah pembelian keanggotaan baru
            $membership->status = self::ACTIVE;
            $membership->save();
            $message = 'Keanggotaan baru Anda telah aktif.';
        } else if ($membership->status === self::ACTIVE) {
            // Ini adalah perpanjangan keanggotaan
            $membership->end_date = Carbon::parse($membership->end_date)->addDays($membership->membershipType->duration);
            $membership->save();
            $message = 'Keanggotaan Anda telah berhasil diperpanjang.';
        }

        return redirect()->route('memberships.index')->with('success', $message);
    }
}
