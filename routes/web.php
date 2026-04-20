<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HikerDashboardController;
use App\Http\Controllers\TourGuideDashboardController;

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
    Route::post('/hikers/guide-reviews', [HikerDashboardController::class, 'storeGuideReview'])->name('hikers.guide-reviews.store');
    Route::post('/hikers/community-posts', [HikerDashboardController::class, 'storeCommunityPost'])->name('hikers.community.store');
    Route::post('/hikers/profile/picture', [HikerDashboardController::class, 'updateProfilePicture'])->name('hikers.profile.picture');
    Route::post('/hikers/profile', [HikerDashboardController::class, 'updateProfile'])->name('hikers.profile.update');
    Route::post('/hikers/profile/password/send-code', [HikerDashboardController::class, 'sendPasswordChangeCode'])->name('hikers.profile.password.send-code');
    Route::post('/hikers/profile/password', [HikerDashboardController::class, 'updatePasswordWithCode'])->name('hikers.profile.password.update');
    Route::post('/hikers/achievements/{achievement}/claim', [HikerDashboardController::class, 'claimAchievement'])->name('hikers.achievements.claim');
    Route::post('/hikers/location', [HikerDashboardController::class, 'recordLocation'])->name('hikers.location.record');
});

// Authenticated Tour Guide Dashboard
Route::middleware(['auth', 'tour_guide'])->group(function () {
    Route::get('/tour-guide', [TourGuideDashboardController::class, 'index'])->name('tour-guide.dashboard');
    Route::post('/tour-guide/bookings/{booking}/approve', [TourGuideDashboardController::class, 'approveBooking'])->name('tour-guide.bookings.approve');
    Route::post('/tour-guide/bookings/{booking}/reject', [TourGuideDashboardController::class, 'rejectBooking'])->name('tour-guide.bookings.reject');
    Route::post('/tour-guide/bookings/{booking}/complete', [TourGuideDashboardController::class, 'completeBooking'])->name('tour-guide.bookings.complete');
    Route::post('/tour-guide/availability', [TourGuideDashboardController::class, 'updateAvailability'])->name('tour-guide.availability');
    Route::post('/tour-guide/profile', [TourGuideDashboardController::class, 'updateProfile'])->name('tour-guide.profile.update');
    Route::post('/tour-guide/profile/picture', [TourGuideDashboardController::class, 'updateProfilePicture'])->name('tour-guide.profile.picture');
});

// Authenticated Admin Dashboard
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('dashboard');

    Route::post('/tour-guides', [AdminController::class, 'storeTourGuide'])->name('tour-guides.store');
    Route::put('/tour-guides/{tourGuide}', [AdminController::class, 'updateTourGuide'])->name('tour-guides.update');
    Route::delete('/tour-guides/{tourGuide}', [AdminController::class, 'destroyTourGuide'])->name('tour-guides.destroy');

    Route::post('/admins', [AdminController::class, 'storeAdmin'])->name('admins.store');
    Route::delete('/admins/{admin}', [AdminController::class, 'destroyAdmin'])->name('admins.destroy');

    Route::get('/hikers/{hiker}', [AdminController::class, 'showHiker'])->name('hikers.show');
    Route::delete('/hikers/{hiker}', [AdminController::class, 'suspendHiker'])->name('hikers.destroy');

    Route::get('/live-locations', [AdminController::class, 'liveLocations'])->name('live-locations');
    Route::get('/audit-logs', [AdminController::class, 'auditLogs'])->name('audit-logs');
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
