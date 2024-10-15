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

class PaymentController extends Controller
{
    public function process($paymentId)
    {
        $payment = Payment::findOrFail($paymentId);
        $membership = Membership::findOrFail($payment->membership_id);

        return view('payments.process', compact('payment', 'membership'));
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

        // Ubah teks OCR menjadi huruf kecil untuk memudahkan pencocokan
        $lowerOcrText = strtolower($ocrText);

        // Dapatkan semua rekening bank yang aktif
        $validBankAccounts = BankAccount::where('is_active', true)->get();

        // Cek apakah ada rekening bank yang cocok dalam teks OCR
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

        // Cek apakah jumlah transfer ada dalam teks OCR
        // Ubah format jumlah untuk mencoba beberapa kemungkinan
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

        // Log the full OCR text for debugging
        Log::info('Full OCR Text: ' . $lowerOcrText);

        // Verifikasi berhasil jika rekening bank valid dan jumlah transfer ditemukan
        $result = $accountFound && $amountFound;
        Log::info('Verification result: ' . ($result ? 'Success' : 'Failed'));

        return $result;
    }
}
