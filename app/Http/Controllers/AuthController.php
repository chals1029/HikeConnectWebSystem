<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\EmailService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    /** @var list<string> */
    private const HIKER_HASH_SECTIONS = [
        'home', 'achievements', 'mountain-overview', 'mountain-detail', 'tour-guides',
        'book-hike', 'bookings', 'track-location', 'what-to-bring', 'hiking-history',
        'trail-plan', 'community-chat', 'settings', 'safety-alerts',
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

        // Generate 6-digit code
        $code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password), // User model casts this as hashed, but Hash::make is safe
            'verification_code' => Hash::make($code),
            'verification_code_expires_at' => now()->addMinutes(10),
        ]);

        // Send email
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

        // Mark as verified
        $user->email_verified_at = now();
        $user->verification_code = null;
        $user->verification_code_expires_at = null;
        $user->save();

        // Log the user in immediately
        Auth::login($user, true); // true = remember

        $request->session()->regenerate();

        return response()->json([
            'success' => true,
            'redirect' => $this->hikerDashboardRedirectUrl(),
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

        // Check if user exists but hasn't verified email
        if ($user && $user->email_verified_at === null) {
            // Need to verify email first
            return response()->json([
                'success' => false, 
                'needs_verification' => true,
                'message' => 'Please verify your email address to continue.'
            ], 403);
        }

        if (Auth::attempt($credentials, true)) { // true = remember
            $request->session()->regenerate();

            return response()->json([
                'success' => true,
                'redirect' => $this->hikerDashboardRedirectUrl(),
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'The provided credentials do not match our records.'
        ], 422);
    }

    /**
     * URL for the hikers SPA, including an optional hash from the welcome page (e.g. Explore Trail).
     */
    private function hikerDashboardRedirectUrl(): string
    {
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
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
