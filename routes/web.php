<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HikerDashboardController;
use App\Http\Controllers\NewsletterController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\TourGuideDashboardController;
use App\Http\Controllers\TrailExploreController;
use App\Http\Controllers\UserAvatarController;

Route::get('/', function () {
    $sec = request()->query('next_section');
    if (is_string($sec) && preg_match('/^[a-z0-9-]{1,64}$/', $sec)) {
        session(['after_login_section' => $sec]);
    }

    return view('welcome');
})->name('home');

Route::get('/avatars/{user}', [UserAvatarController::class, 'show'])->name('users.avatar');

// Public trail exploration — no login required
Route::get('/trails/{slug}', [TrailExploreController::class, 'show'])->name('trails.explore');
Route::get('/trails/{slug}/preview', [TrailExploreController::class, 'preview'])->name('trails.preview');

// Notifications (shared by hikers, tour guides, admins). The bell partial
// and Notifications tab on every dashboard read from these endpoints.
Route::middleware('auth')->prefix('notifications')->name('notifications.')->group(function () {
    Route::get('/bell', [NotificationController::class, 'bell'])->name('bell');
    Route::get('/', [NotificationController::class, 'index'])->name('index');
    Route::post('/{notification}/read', [NotificationController::class, 'markRead'])->name('mark-read');
    Route::post('/read-all', [NotificationController::class, 'markAllRead'])->name('mark-all-read');
});

// Authenticated Hikers Dashboard
Route::middleware('auth')->group(function () {
    Route::get('/hikers', [HikerDashboardController::class, 'index'])->name('hikers.dashboard');
    Route::post('/hikers/bookings', [HikerDashboardController::class, 'storeBooking'])->name('hikers.bookings.store');
    Route::post('/hikers/bookings/{booking}/cancel', [HikerDashboardController::class, 'cancelBooking'])->name('hikers.bookings.cancel');
    Route::post('/hikers/bookings/{booking}/check-in-scan', [HikerDashboardController::class, 'checkInScan'])->name('hikers.bookings.checkin-scan');
    Route::post('/hikers/bookings/{booking}/check-out-scan', [HikerDashboardController::class, 'checkOutScan'])->name('hikers.bookings.checkout-scan');
    Route::post('/hikers/reviews', [HikerDashboardController::class, 'storeReview'])->name('hikers.reviews.store');
    Route::post('/hikers/guide-reviews', [HikerDashboardController::class, 'storeGuideReview'])->name('hikers.guide-reviews.store');
    Route::post('/hikers/community-posts', [HikerDashboardController::class, 'storeCommunityPost'])->name('hikers.community.store');
    Route::post('/hikers/community-posts/{post}/like', [HikerDashboardController::class, 'toggleCommunityPostLike'])->name('hikers.community.like');
    Route::get('/hikers/community-posts/{post}/comments', [HikerDashboardController::class, 'indexCommunityPostComments'])->name('hikers.community.comments.index');
    Route::post('/hikers/community-posts/{post}/comments', [HikerDashboardController::class, 'storeCommunityPostComment'])->name('hikers.community.comments.store');
    Route::post('/hikers/profile/picture', [HikerDashboardController::class, 'updateProfilePicture'])->name('hikers.profile.picture');
    Route::post('/hikers/profile', [HikerDashboardController::class, 'updateProfile'])->name('hikers.profile.update');
    Route::post('/hikers/profile/password/send-code', [HikerDashboardController::class, 'sendPasswordChangeCode'])->name('hikers.profile.password.send-code');
    Route::post('/hikers/profile/password', [HikerDashboardController::class, 'updatePasswordWithCode'])->name('hikers.profile.password.update');
    Route::post('/hikers/achievements/{achievement}/claim', [HikerDashboardController::class, 'claimAchievement'])->name('hikers.achievements.claim');
    Route::post('/hikers/location', [HikerDashboardController::class, 'recordLocation'])->name('hikers.location.record');
    Route::post('/hikers/sos', [HikerDashboardController::class, 'triggerSos'])->name('hikers.sos.trigger');
    Route::post('/hikers/experience-feedback', [HikerDashboardController::class, 'storeExperienceFeedback'])->name('hikers.experience-feedback.store');
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
    Route::get('/tour-guide/sos-alerts', [TourGuideDashboardController::class, 'sosAlerts'])->name('tour-guide.sos-alerts.index');
    Route::patch('/tour-guide/sos-alerts/{alert}/acknowledge', [TourGuideDashboardController::class, 'acknowledgeSosAlert'])->name('tour-guide.sos-alerts.acknowledge');
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
    Route::patch('/mountains/{mountain}/safety', [AdminController::class, 'updateMountainSafety'])->name('mountains.safety.update');
    Route::post('/mountains', [AdminController::class, 'storeMountain'])->name('mountains.store');
    Route::put('/mountains/{mountain}', [AdminController::class, 'updateMountain'])->name('mountains.update');
    Route::delete('/mountains/{mountain}', [AdminController::class, 'destroyMountain'])->name('mountains.destroy');

    Route::get('/live-locations', [AdminController::class, 'liveLocations'])->name('live-locations');
    Route::get('/sos-alerts', [AdminController::class, 'sosAlerts'])->name('sos-alerts.index');
    Route::patch('/sos-alerts/{alert}', [AdminController::class, 'updateSosAlert'])->name('sos-alerts.update');
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
Route::post('/verification/send-code', [AuthController::class, 'sendVerificationCode'])->name('verification.send-code');
Route::post('/verify-email', [AuthController::class, 'verifyEmail'])->name('verify.attempt');
Route::post('/forgot-password/send-code', [AuthController::class, 'sendForgotPasswordCode'])->name('forgot-password.send-code');
Route::post('/forgot-password/reset', [AuthController::class, 'resetForgotPassword'])->name('forgot-password.reset');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::post('/newsletter/subscribe', [NewsletterController::class, 'store'])->name('newsletter.subscribe');
