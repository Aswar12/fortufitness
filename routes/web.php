<?php

use App\Filament\Resources\CheckInResource\Pages\ScanQrModal;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PaymentController;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\CheckRole;
use App\Http\Controllers\UserDashboardController;
use App\Http\Controllers\CheckInController;
use App\Http\Controllers\MemberCheckInOutController;

Route::get('/', function () {
    return view('index');
});
Route::post('/register', [RegisterController::class, 'store'])->name('register');
Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified', CheckRole::class])->group(function () {

    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/keanggotaan', function () {
        return view('keanggotaan');
    });
    Route::post('/resources/check-ins', 'CheckInResource@store');
    Route::get('/transaksi/confirm', [PaymentController::class, 'confirm']);
    Route::get('/checkin', function () {
        return view('checkin');
    });

    Route::post('/member/checkin', [MemberCheckInOutController::class, 'checkin'])->name('member.checkin');
    Route::post('/member/checkout', [MemberCheckInOutController::class, 'checkout'])->name('member.checkout');
});
