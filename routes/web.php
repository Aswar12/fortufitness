<?php

use App\Http\Controllers\Auth\RegisterController;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\CheckRole;
use App\Http\Controllers\UserDashboardController;

Route::get('/', function () {
    return view('index');
});
Route::post('/register', [RegisterController::class, 'store'])->name('register');
Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified', CheckRole::class])->group(function () {

    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/keanggotaan', function () {
        return view('keanggotaan');
    });

    Route::get('/transaksi', function () {
        return view('transaksi');
    });

    Route::get('/checkin', function () {
        return view('checkin');
    });
});
