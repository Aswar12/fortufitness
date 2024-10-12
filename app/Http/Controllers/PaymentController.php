<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payment;
use App\Models\Membership;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use thiagoalessio\TesseractOCR\TesseractOCR;

class PaymentController extends Controller
{
    public function uploadProof(Request $request, Payment $payment)
    {
        $this->authorize('update', $payment);

        $request->validate([
            'proof_of_payment' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($payment->status !== 'pending') {
            return redirect()->route('memberships.show', $payment->membership_id)
                ->with('error', 'Pembayaran ini sudah diproses sebelumnya.');
        }

        try {
            DB::beginTransaction();

            if ($request->hasFile('proof_of_payment')) {
                $image = $request->file('proof_of_payment');
                $imageName = time() . '_' . $payment->id . '.' . $image->getClientOriginalExtension();
                $imagePath = $image->storeAs('payment_proofs', $imageName, 'public');

                $fullImagePath = Storage::disk('public')->path($imagePath);
                $text = (new TesseractOCR($fullImagePath))->run();

                $amount = $this->extractAmount($text);
                $date = $this->extractDate($text);

                $payment->proof_of_payment = $imagePath;

                if ($amount && $date) {
                    $payment->status = 'review';
                    $message = 'Bukti pembayaran berhasil diunggah dan sedang menunggu verifikasi admin.';
                } else {
                    $payment->status = 'review';
                    $message = 'Bukti pembayaran diunggah, tetapi memerlukan verifikasi manual oleh admin.';
                }

                $payment->save();

                DB::commit();

                return redirect()->route('memberships.show', $payment->membership_id)
                    ->with('success', $message);
            }

            throw new \Exception('File bukti pembayaran tidak ditemukan.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error uploading proof of payment: ' . $e->getMessage());
            return redirect()->route('memberships.show', $payment->membership_id)
                ->with('error', 'Terjadi kesalahan saat mengunggah bukti pembayaran. Silakan coba lagi.');
        }
    }

    private function extractAmount($text)
    {
        preg_match('/Rp\.?\s?(\d{1,3}(?:[.,]\d{3})*(?:[.,]\d{2})?)/', $text, $matches);
        return isset($matches[1]) ? str_replace([',', '.'], '', $matches[1]) : null;
    }

    private function extractDate($text)
    {
        preg_match('/(\d{2}[-\/]\d{2}[-\/]\d{4})/', $text, $matches);
        return isset($matches[1]) ? $matches[1] : null;
    }
}
