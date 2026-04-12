<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HikerDashboardController;

Route::get('/', function () {
    $sec = request()->query('next_section');
    if (is_string($sec) && preg_match('/^[a-z0-9-]{1,64}$/', $sec)) {
        session(['after_login_section' => $sec]);
    }

    return view('welcome');
})->name('home');

// Authenticated Hikers Dashboard
Route::middleware('auth')->group(function () {
    Route::get('/hikers', [HikerDashboardController::class, 'index'])->name('hikers.dashboard');
    Route::post('/hikers/bookings', [HikerDashboardController::class, 'storeBooking'])->name('hikers.bookings.store');
    Route::post('/hikers/bookings/{booking}/cancel', [HikerDashboardController::class, 'cancelBooking'])->name('hikers.bookings.cancel');
    Route::post('/hikers/reviews', [HikerDashboardController::class, 'storeReview'])->name('hikers.reviews.store');
    Route::post('/hikers/community-posts', [HikerDashboardController::class, 'storeCommunityPost'])->name('hikers.community.store');
    Route::post('/hikers/profile/picture', [HikerDashboardController::class, 'updateProfilePicture'])->name('hikers.profile.picture');
    Route::post('/hikers/profile', [HikerDashboardController::class, 'updateProfile'])->name('hikers.profile.update');
    Route::post('/hikers/profile/password/send-code', [HikerDashboardController::class, 'sendPasswordChangeCode'])->name('hikers.profile.password.send-code');
    Route::post('/hikers/profile/password', [HikerDashboardController::class, 'updatePasswordWithCode'])->name('hikers.profile.password.update');
    Route::post('/hikers/achievements/{achievement}/claim', [HikerDashboardController::class, 'claimAchievement'])->name('hikers.achievements.claim');
});

// For unauthenticated users, keep the redirect fallback if they hit the URL directly
Route::middleware('guest')->group(function () {
    Route::get('/login', function () {
        return redirect('/?auth=login');
    })->name('login');

    Route::get('/register', function () {
        return redirect('/?auth=register');
    })->name('register');
});

// Authentication Routes
Route::post('/login', [AuthController::class, 'login'])->name('login.attempt');
Route::post('/register', [AuthController::class, 'register'])->name('register.attempt');
Route::post('/verify-email', [AuthController::class, 'verifyEmail'])->name('verify.attempt');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
