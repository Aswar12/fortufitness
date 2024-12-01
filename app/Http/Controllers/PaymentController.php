<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payment;
use App\Models\Membership;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use thiagoalessio\TesseractOCR\TesseractOCR;
use App\Models\BankAccount;
use DateTime;

class PaymentController extends Controller
{
    public function process($paymentId)
    {
        $bankAccounts = BankAccount::all();
        $payment = Payment::findOrFail($paymentId);
        $membership = Membership::findOrFail($payment->membership_id);

        return view('payments.process', compact('payment', 'membership', 'bankAccounts'));
    }

    public function uploadProof(Request $request, $paymentId)
    {
        try {
            Log::info('Upload proof started for payment ID: ' . $paymentId);

            $request->validate([
                'proof_of_payment' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            $payment = Payment::findOrFail($paymentId);
            Log::info('Payment found: ' . $payment->id);

            if ($payment->status !== 'pending') {
                Log::info('Payment status is not pending: ' . $payment->status);
                return redirect()->route('memberships.index')
                    ->with('error', 'Pembayaran ini sudah diproses sebelumnya.');
            }

            if ($request->hasFile('proof_of_payment')) {
                $image = $request->file('proof_of_payment');
                $imageName = time() . '_' . $payment->id . '.' . $image->getClientOriginalExtension();
                $imagePath = $image->storeAs('payment_proofs', $imageName, 'public');
                $fullImagePath = storage_path('app/public/' . $imagePath);

                Log::info('Image stored at: ' . $fullImagePath);

                // Perform OCR
                $ocrResult = $this->performOCR($fullImagePath);
                Log::info('OCR Result: ' . ($ocrResult ?? 'null'));

                // Verify the OCR result
                $verificationResult = $this->verifyPayment($ocrResult, $payment);
                Log::info('Verification result: ' . ($verificationResult ? 'true' : 'false'));
                $payment->proof_of_payment = $imagePath;

                if ($verificationResult) {
                    $payment->status = 'completed';
                    $membership = Membership::findOrFail($payment->membership_id);
                    $membership->status = 'active';
                    $membership->save();
                    $message = 'Bukti pembayaran berhasil diverifikasi dan membership telah diaktifkan.';
                } else {
                    $payment->status = 'pending';
                    $message = 'Bukti pembayaran diunggah dan sedang menunggu verifikasi manual oleh admin.';
                }

                $payment->save();
                Log::info('Payment updated: ' . $payment->id);

                return redirect()->route('memberships.index')->with('success', $message);
            }

            throw new \Exception('File bukti pembayaran tidak ditemukan.');
        } catch (\Exception $e) {
            Log::error('Error in uploadProof: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            return redirect()->route('memberships.index')
                ->with('error', 'Terjadi kesalahan saat mengunggah bukti pembayaran. Silakan coba lagi.');
        }
    }

    private function performOCR($imagePath)
    {
        try {
            Log::info('Performing OCR on: ' . $imagePath);
            $tesseract = new TesseractOCR($imagePath);
            $result = $tesseract->run();
            Log::info('OCR Result: ' . ($result ?? 'null'));
            return $result;
        } catch (\Exception $e) {
            Log::error('OCR Error: ' . $e->getMessage());
            Log::error('OCR Error Stack trace: ' . $e->getTraceAsString());
            return null;
        }
    }


    private function verifyPayment($ocrText, $payment)
    {
        if (!$ocrText) {
            Log::info('OCR text is empty');
            return false;
        }

        $expectedAmount = $payment->amount;
        $expectedDate = $payment->payment_date; // assume this is the expected payment date

        // Convert OCR text to lowercase for easier matching
        $lowerOcrText = strtolower($ocrText);

        // Get all active bank accounts
        $validBankAccounts = BankAccount::where('is_active', true)->get();

        // Check for matching bank account in OCR text
        $accountFound = false;
        foreach ($validBankAccounts as $account) {
            if (
                strpos($lowerOcrText, strtolower($account->account_number)) !== false &&
                strpos($lowerOcrText, strtolower($account->account_name)) !== false
            ) {
                $accountFound = true;
                Log::info('Valid account found: ' . $account->account_name . ' - ' . $account->account_number);
                break;
            }
        }

        // Check if the transfer amount is present in the OCR text
        $amountVariations = [
            'rp' . $expectedAmount,
            'rp ' . $expectedAmount,
            'rp' . number_format($expectedAmount, 0, ',', '.'),
            'rp ' . number_format($expectedAmount, 0, ',', '.'),
            str_replace(',', '', $expectedAmount),
            str_replace('.', '', $expectedAmount)
        ];

        $amountFound = false;
        foreach ($amountVariations as $amount) {
            if (strpos($lowerOcrText, strtolower($amount)) !== false) {
                $amountFound = true;
                break;
            }
        }
        Log::info('Amount found: ' . ($amountFound ? 'Yes' : 'No'));

        // Extract the date and time from the OCR text
        $dateRegex = '/\b(\d{1,2} [a-zA-Z]+ \d{4}, \d{2}:\d{2} wib)\b/'; // Regex to match the date format
        preg_match($dateRegex, $lowerOcrText, $dateMatches);
        $extractedDate = $dateMatches[1] ?? null;
        $extractedDate = str_replace(' wib', '', $extractedDate);
        $monthNames = array('januari', 'februari', 'maret', 'april', 'mei', 'juni', 'juli', 'agustus', 'september', 'oktober', 'november', 'desember');
        $monthValues = array('01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12');

        $extractedDate = strtolower($extractedDate); // convert to lowercase for case-insensitive replacement

        foreach ($monthNames as $key => $monthName) {
            $extractedDate = str_replace($monthName, $monthValues[$key], $extractedDate);
        }
        // Create a DateTime object from the created_at date
        try {
            $createdAtDateObject = new DateTime($payment->created_at);
        } catch (Exception $e) {
            Log::info('Error creating DateTime object: ' . $e->getMessage());
            return false; // Handle the error as needed
        }

        // Check if the extracted date is valid
        if ($extractedDate) {
            // Create a DateTime object from the extracted date
            $extractedDateObject = DateTime::createFromFormat('d m Y, H:i', $extractedDate);

            if (!$extractedDateObject) {
                Log::info('Invalid extracted date format: ' . $extractedDate);
                $dateFound = false;
            } else {
                // Format the extracted date to match the created_at format
                $formattedExtractedDate = $extractedDateObject->format('Y-m-d H:i:s');
                Log::info('Formatted extracted date: ' . $formattedExtractedDate);

                // Now you can compare this formatted date with the created_at date
                try {
                    $createdAtDateObject = new DateTime($payment->created_at);
                } catch (Exception $e) {
                    Log::info('Error creating DateTime object: ' . $e->getMessage());
                    return false; // Handle the error as needed
                }
                // Compare the two DateTime objects
                $dateFound = $createdAtDateObject->diff($extractedDateObject)->h <= 4; // Check if within 4 hours
                Log::info('Date found: ' . ($dateFound ? 'Yes' : 'No'));
            }
        } else {
            $dateFound = false;
            Log::info('No date found in OCR text.');
        }

        // Verification is successful if a valid bank account, transfer amount, and matching date are found
        $result = $accountFound && $amountFound && $dateFound;
        Log::info('Verification result: ' . ($result ? 'Success' : 'Failed'));

        return $result;
    }
}
