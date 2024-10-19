<?php

use App\Filament\Resources\CheckInResource\Pages\ScanQrModal;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PaymentController;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\CheckRole;
use App\Http\Controllers\UserDashboardController;
use App\Http\Controllers\CheckInController;
use App\Http\Controllers\LandingPageController;
use App\Http\Controllers\MemberCheckInOutController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\MembershipController;

// Halaman utama
Route::get('/', [LandingPageController::class, 'index'])->name('landing');

// Rute untuk pendaftaran pengguna baru
Route::post('/register', [RegisterController::class, 'store'])->name('register');

// Grup rute yang memerlukan autentikasi dan verifikasi
Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified', CheckRole::class])->group(function () {

    // Dashboard pengguna
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Halaman keanggotaan
    Route::get('/keanggotaan', function () {
        return view('keanggotaan');
    });
    // Halaman check-in
    Route::get('/checkin', function () {
        return view('checkin');
    });

    // Check-in anggota
    Route::post('/member/checkin', [MemberCheckInOutController::class, 'checkin'])->name('member.checkin');

    // Check-out anggota
    Route::post('/member/checkout', [MemberCheckInOutController::class, 'checkout'])->name('member.checkout');

    // Riwayat kunjungan anggota
    Route::get('/member/visit-history', [MemberController::class, 'index'])->name('member.visit-history');

    // Daftar keanggotaan
    Route::get('/memberships', [MembershipController::class, 'index'])->name('memberships.index');

    // Membeli keanggotaan baru
    Route::post('/memberships/purchase/{membershipType}', [MembershipController::class, 'purchase'])->name('memberships.purchase');

    // Membatalkan keanggotaan
    Route::post('/memberships/{membership}/cancel', [MembershipController::class, 'cancel'])->name('memberships.cancel');

    // Memperpanjang keanggotaan
    Route::post('/memberships/extend/{membershipType}', [MembershipController::class, 'extend'])->name('memberships.extend');

    // Konfirmasi pembayaran keanggotaan
    Route::post('/memberships/confirm-payment/{payment}', [MembershipController::class, 'confirmPayment'])->name('memberships.confirmPayment');

    // Proses pembayaran
    Route::get('/payments/{payment}/process', [PaymentController::class, 'process'])->name('payments.process');

    // Upload bukti pembayaran
    Route::post('/payments/{payment}/upload-proof', [PaymentController::class, 'uploadProof'])->name('payments.uploadProof');

    // Menampilkan riwayat keanggotaan
    Route::get('/memberships/history', [MembershipController::class, 'history'])->name('memberships.history');

    // Callback untuk pembayaran berhasil
    Route::get('/payments/success', [PaymentController::class, 'success'])->name('payments.success');

    // Callback untuk pembayaran gagal
    Route::get('/payments/failed', [PaymentController::class, 'failed'])->name('payments.failed');

    // Konfirmasi pembayaran
    Route::post('/payments/confirm', [PaymentController::class, 'confirm'])->name('payments.confirm');
});
