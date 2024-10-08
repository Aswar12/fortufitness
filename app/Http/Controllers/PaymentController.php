<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Membership;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use thiagoalessio\TesseractOCR\TesseractOCR;


class PaymentController extends Controller
{
    public function confirm(Request $request)
    {
        $request->validate([
            'proof_of_payment' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $proofOfPayment = $request->file('proof_of_payment');
        $fileName = time() . '.' . $proofOfPayment->getClientOriginalExtension();
        $proofOfPayment->storeAs('public/proof_of_payment', $fileName);

        $text = $this->extractTextFromImage(storage_path('app/public/proof_of_payment/' . $fileName));

        // Extract important information from text, such as transaction number and amount
        $transactionNumber = '';
        $amount = '';
        foreach (explode("\n", $text) as $line) {
            if (strpos($line, 'Transaction Number') !== false) {
                $transactionNumber = trim(str_replace('Transaction Number:', '', $line));
            } elseif (strpos($line, 'Amount') !== false) {
                $amount = trim(str_replace('Amount:', '', $line));
            }
        }

        // Find the corresponding membership
        $membership = Membership::where('id', $transactionNumber)->first();

        if (!$membership) {
            return redirect()->back()->with('error', 'Membership not found');
        }

        // Create new payment
        $payment = new Payment();
        $payment->membership_id = $membership->id;
        $payment->amount = $amount;
        $payment->payment_date = now();
        $payment->payment_method = 'Automatic Confirmation';
        $payment->proof_of_payment = $fileName;
        $payment->save();

        return redirect()->back()->with('success', 'Payment confirmed successfully');
    }

    private function extractTextFromImage($imagePath)
    {
        // OCR implementation to extract text from image
        // Example using Tesseract OCR library
        $ocr = new TesseractOCR();
        $text = $ocr->image($imagePath)->run();
        return $text;
    }
}
