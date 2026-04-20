<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\AuditLogger;
use App\Services\EmailService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /** @var list<string> */
    private const HIKER_HASH_SECTIONS = [
        'home', 'achievements', 'mountain-overview', 'mountain-detail', 'tour-guides',
        'book-hike', 'bookings', 'track-location', 'what-to-bring', 'hiking-history',
        'trail-plan', 'community-chat', 'settings', 'safety-alerts',
    ];

    /** @var list<string> */
    private const TOUR_GUIDE_HASH_SECTIONS = [
        'home', 'bookings', 'hikers', 'reviews', 'profile', 'settings',
    ];

    protected EmailService $emailService;

    public function __construct(EmailService $emailService)
    {
        $this->emailService = $emailService;
    }

    /**
     * Handle the registration request using AJAX.
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => ['required', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'phone' => ['required', 'string', 'regex:/^[0-9+\-\s]{7,22}$/'],
            'password' => ['required', 'string', 'min:8'],
            'confirm_details' => ['accepted'],
        ], [
            'phone.regex' => 'Enter a valid phone number.',
            'confirm_details.accepted' => 'Please confirm your details are correct.',
            'email.unique' => 'An account with this email already exists.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'role' => User::ROLE_HIKER,
            'verification_code' => Hash::make($code),
            'verification_code_expires_at' => now()->addMinutes(10),
        ]);

        AuditLogger::log('user.registered', "Registered new hiker account {$user->email}", $user, $user, [
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
        ], $user);

        $this->emailService->sendVerificationCode($user->email, $code, $user->first_name);

        return response()->json([
            'success' => true,
            'email' => $user->email,
            'message' => 'Verification code sent.'
        ]);
    }

    /**
     * Verify the email using the 6-digit code.
     */
    public function verifyEmail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'email'],
            'code' => ['required', 'string', 'size:6'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json(['success' => false, 'message' => 'User not found.'], 404);
        }

        if ($user->email_verified_at !== null) {
            return response()->json(['success' => false, 'message' => 'Email is already verified.']);
        }

        if (!$user->verification_code || $user->verification_code_expires_at < now()) {
            return response()->json(['success' => false, 'message' => 'Verification code expired or invalid.'], 422);
        }

        if (!Hash::check($request->code, $user->verification_code)) {
            return response()->json(['success' => false, 'message' => 'Invalid verification code.'], 422);
        }

        $user->email_verified_at = now();
        $user->verification_code = null;
        $user->verification_code_expires_at = null;
        $user->save();

        Auth::login($user, true);

        $request->session()->regenerate();

        AuditLogger::log('user.email_verified', "Verified email and signed in: {$user->email}", $user, $user, [], $user);

        return response()->json([
            'success' => true,
            'redirect' => $this->dashboardRedirectUrl($user),
        ]);
    }

    /**
     * Handle the login request using AJAX.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $user = User::where('email', $request->email)->first();

        if ($user && $user->email_verified_at === null) {
            return response()->json([
                'success' => false, 
                'needs_verification' => true,
                'message' => 'Please verify your email address to continue.'
            ], 403);
        }

        if (Auth::attempt($credentials, true)) {
            $request->session()->regenerate();
            $authed = Auth::user();
            AuditLogger::log('user.login', "Signed in: {$authed->email}", $authed, $authed, [
                'role' => $authed->role,
            ], $authed);

            return response()->json([
                'success' => true,
                'redirect' => $this->dashboardRedirectUrl($authed),
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'The provided credentials do not match our records.'
        ], 422);
    }

    /**
     * Pick the right post-login dashboard URL for the user's role.
     */
    private function dashboardRedirectUrl(User $user): string
    {
        if ($user->isAdmin()) {
            return route('admin.dashboard');
        }

        if ($user->isTourGuide()) {
            $url = route('tour-guide.dashboard');
            $sec = session()->pull('after_login_section');
            if (is_string($sec) && in_array($sec, self::TOUR_GUIDE_HASH_SECTIONS, true)) {
                return $url.'#'.$sec;
            }

            return $url;
        }

        $url = route('hikers.dashboard');
        $sec = session()->pull('after_login_section');

        if (is_string($sec) && in_array($sec, self::HIKER_HASH_SECTIONS, true)) {
            return $url.'#'.$sec;
        }

        return $url;
    }

    /**
     * Handle logout.
     */
    public function logout(Request $request)
    {
        $current = Auth::user();
        if ($current) {
            AuditLogger::log('user.logout', "Signed out: {$current->email}", $current, $current, [], $current);
        }

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
