<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\AuditLogger;
use App\Services\EmailService;
use App\Services\SmsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    private const PH_MOBILE_REGEX = '/^\+639\d{9}$/';
    private const VERIFICATION_RESEND_COOLDOWN_SECONDS = 60;

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
    protected SmsService $smsService;

    public function __construct(EmailService $emailService, SmsService $smsService)
    {
        $this->emailService = $emailService;
        $this->smsService = $smsService;
    }

    /**
     * Handle the registration request using AJAX.
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => ['required', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
            'email' => [
                'required', 'string', 'email', 'max:255',
                function (string $attribute, mixed $value, \Closure $fail): void {
                    $existing = User::where('email', (string) $value)->first();
                    if ($existing && $existing->email_verified_at !== null) {
                        $fail('An account with this email already exists.');
                    }
                },
            ],
            'phone' => ['required', 'string', 'regex:'.self::PH_MOBILE_REGEX],
            'password' => ['required', 'string', 'min:8'],
            'confirm_details' => ['accepted'],
        ], [
            'phone.regex' => 'Use a valid PH mobile number format: +639XXXXXXXXX.',
            'confirm_details.accepted' => 'Please confirm your details are correct.',
            'email.unique' => 'An account with this email already exists.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $request->session()->put('pending_registration', [
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'role' => User::ROLE_HIKER,
        ]);

        return response()->json([
            'success' => true,
            'email' => $request->email,
            'phone' => $request->phone,
            'message' => 'Details saved. Choose where to receive your verification code.'
        ]);
    }

    public function sendVerificationCode(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'email'],
            'channel' => ['required', 'in:email,sms'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        $email = (string) $request->email;
        $channel = (string) $request->channel;
        $pending = $request->session()->get('pending_registration');
        $user = User::where('email', $email)->first();

        $cooldownKey = 'verification_code_last_sent_at_'.sha1($email);
        $lastSentAt = $request->session()->get($cooldownKey);
        if (is_string($lastSentAt)) {
            $lastSent = \Illuminate\Support\Carbon::parse($lastSentAt);
            $secondsSinceLast = $lastSent->diffInSeconds(now());
            $remaining = self::VERIFICATION_RESEND_COOLDOWN_SECONDS - $secondsSinceLast;
            if ($remaining > 0) {
                return response()->json([
                    'success' => false,
                    'message' => "Please wait {$remaining}s before sending another code.",
                    'seconds_remaining' => $remaining,
                ], 429);
            }
        }

        if ($user && $user->email_verified_at !== null) {
            return response()->json(['success' => false, 'message' => 'Account is already verified.'], 422);
        }

        $hasPending = is_array($pending) && (($pending['email'] ?? null) === $email);
        if (! $hasPending && ! $user) {
            return response()->json([
                'success' => false,
                'message' => 'Please complete the registration form again before requesting a code.',
            ], 422);
        }

        $firstNameForMessage = $hasPending ? (string) ($pending['first_name'] ?? '') : (string) $user?->first_name;
        $phoneForMessage = $hasPending ? (string) ($pending['phone'] ?? '') : (string) $user?->phone;

        if ($channel === 'sms' && preg_match(self::PH_MOBILE_REGEX, $phoneForMessage) !== 1) {
            return response()->json([
                'success' => false,
                'message' => 'Phone number must be in +639XXXXXXXXX format for SMS verification.',
            ], 422);
        }

        $code = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        $sent = false;

        if ($channel === 'email') {
            $sent = $this->emailService->sendVerificationCode($email, $code, $firstNameForMessage);
        }

        if ($channel === 'sms') {
            $sent = $this->smsService->sendVerificationCode($phoneForMessage, $code);
        }

        if (! $sent) {
            return response()->json([
                'success' => false,
                'message' => $channel === 'sms'
                    ? 'Could not send SMS code right now. Please check your number or try email.'
                    : 'Could not send email code right now. Please try again.',
            ], 500);
        }

        if ($user) {
            if ($hasPending) {
                $user->first_name = (string) ($pending['first_name'] ?? $user->first_name);
                $user->last_name = (string) ($pending['last_name'] ?? $user->last_name);
                $user->phone = (string) ($pending['phone'] ?? $user->phone);
                $user->password = (string) ($pending['password'] ?? $user->password);
                $user->role = (string) ($pending['role'] ?? $user->role ?? User::ROLE_HIKER);
            }
            $user->verification_code = Hash::make($code);
            $user->verification_code_expires_at = now()->addMinutes(10);
            $user->save();
        } else {
            /** @var array<string, mixed> $pending */
            $user = User::create([
                'first_name' => (string) ($pending['first_name'] ?? ''),
                'last_name' => (string) ($pending['last_name'] ?? ''),
                'email' => $email,
                'phone' => (string) ($pending['phone'] ?? ''),
                'password' => (string) ($pending['password'] ?? ''),
                'role' => (string) ($pending['role'] ?? User::ROLE_HIKER),
                'verification_code' => Hash::make($code),
                'verification_code_expires_at' => now()->addMinutes(10),
            ]);

            AuditLogger::log('user.registered', "Registered new hiker account {$user->email}", $user, $user, [
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
            ], $user);
        }

        $request->session()->put($cooldownKey, now()->toIso8601String());

        return response()->json([
            'success' => true,
            'message' => $channel === 'sms'
                ? 'Verification code sent to your phone.'
                : 'Verification code sent to your email.',
            'cooldown_seconds' => self::VERIFICATION_RESEND_COOLDOWN_SECONDS,
        ]);
    }

    public function sendForgotPasswordCode(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'channel' => ['required', 'in:email,sms'],
            'email' => ['nullable', 'email'],
            'phone' => ['nullable', 'string', 'regex:'.self::PH_MOBILE_REGEX],
        ], [
            'phone.regex' => 'Use format +639XXXXXXXXX.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        $channel = (string) $request->channel;
        $user = $channel === 'sms'
            ? User::where('phone', (string) $request->phone)->first()
            : User::where('email', (string) $request->email)->first();

        if (! $user) {
            return response()->json([
                'success' => false,
                'message' => $channel === 'sms'
                    ? 'No account found for that phone number.'
                    : 'No account found for that email.',
            ], 404);
        }

        $target = $channel === 'sms' ? (string) $user->phone : (string) $user->email;
        $cooldownKey = 'forgot_password_code_last_sent_at_'.sha1($channel.'|'.$target);
        $lastSentAt = $request->session()->get($cooldownKey);
        if (is_string($lastSentAt)) {
            $lastSent = \Illuminate\Support\Carbon::parse($lastSentAt);
            $secondsSinceLast = $lastSent->diffInSeconds(now());
            $remaining = self::VERIFICATION_RESEND_COOLDOWN_SECONDS - $secondsSinceLast;
            if ($remaining > 0) {
                return response()->json([
                    'success' => false,
                    'message' => "Please wait {$remaining}s before sending another code.",
                    'seconds_remaining' => $remaining,
                ], 429);
            }
        }

        $code = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        $user->password_change_code = Hash::make($code);
        $user->password_change_code_expires_at = now()->addMinutes(10);
        $user->save();

        $sent = $channel === 'sms'
            ? $this->smsService->sendPasswordResetCode((string) $user->phone, $code)
            : $this->emailService->sendPasswordChangeCode((string) $user->email, $code, (string) $user->first_name);

        if (! $sent) {
            return response()->json([
                'success' => false,
                'message' => $channel === 'sms'
                    ? 'Could not send SMS reset code right now. Please try again.'
                    : 'Could not send email reset code right now. Please try again.',
            ], 500);
        }

        $request->session()->put($cooldownKey, now()->toIso8601String());

        return response()->json([
            'success' => true,
            'message' => $channel === 'sms'
                ? 'Reset code sent to your phone.'
                : 'Reset code sent to your email.',
            'cooldown_seconds' => self::VERIFICATION_RESEND_COOLDOWN_SECONDS,
        ]);
    }

    public function resetForgotPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'channel' => ['required', 'in:email,sms'],
            'email' => ['nullable', 'email'],
            'phone' => ['nullable', 'string', 'regex:'.self::PH_MOBILE_REGEX],
            'code' => ['required', 'string', 'size:6'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'phone.regex' => 'Use format +639XXXXXXXXX.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        $channel = (string) $request->channel;
        $user = $channel === 'sms'
            ? User::where('phone', (string) $request->phone)->first()
            : User::where('email', (string) $request->email)->first();
        if (! $user) {
            return response()->json([
                'success' => false,
                'message' => $channel === 'sms'
                    ? 'No account found for that phone number.'
                    : 'No account found for that email.',
            ], 404);
        }

        if (! $user->password_change_code || ! $user->password_change_code_expires_at || $user->password_change_code_expires_at->isPast()) {
            return response()->json([
                'success' => false,
                'message' => 'Code expired or missing. Request a new code.',
            ], 422);
        }

        if (! Hash::check((string) $request->code, $user->password_change_code)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid verification code.',
            ], 422);
        }

        $user->password = (string) $request->password;
        $user->password_change_code = null;
        $user->password_change_code_expires_at = null;
        $user->save();

        AuditLogger::log(
            $channel === 'sms' ? 'user.password_reset_via_phone' : 'user.password_reset_via_email',
            "Password reset via {$channel} for {$user->email}",
            $user,
            $user
        );

        return response()->json(['success' => true]);
    }

    /**
     * Verify the email using the 6-digit code.
     */
    public function verifyEmail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'email'],
            'code' => ['required', 'string', 'size:6'],
            'channel' => ['nullable', 'in:email,sms'],
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
        $request->session()->forget('pending_registration');

        Auth::login($user, true);

        $request->session()->regenerate();

        AuditLogger::log('user.email_verified', "Verified email and signed in: {$user->email}", $user, $user, [], $user);

        return response()->json([
            'success' => true,
            'redirect' => $this->dashboardRedirectUrl($user, true),
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
            // Admin-created accounts (tour guides and admins) don't go through
            // the OTP registration flow. If an older row was saved before we
            // started stamping email_verified_at at creation time, auto-verify
            // it here so the user can sign in without getting stuck.
            if ($user->isTourGuide() || $user->isAdmin()) {
                $user->email_verified_at = now();
                $user->verification_code = null;
                $user->verification_code_expires_at = null;
                $user->save();
            } else {
                return response()->json([
                    'success' => false,
                    'needs_verification' => true,
                    'message' => 'Please verify your email address to continue.'
                ], 403);
            }
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
    private function dashboardRedirectUrl(User $user, bool $isFreshVerification = false): string
    {
        if ($user->isAdmin()) {
            return $this->withFirstLoginFlag(route('admin.dashboard'), $isFreshVerification);
        }

        if ($user->isTourGuide()) {
            $url = route('tour-guide.dashboard');
            $sec = session()->pull('after_login_section');
            if (is_string($sec) && in_array($sec, self::TOUR_GUIDE_HASH_SECTIONS, true)) {
                return $this->withFirstLoginFlag($url.'#'.$sec, $isFreshVerification);
            }

            return $this->withFirstLoginFlag($url, $isFreshVerification);
        }

        $url = route('hikers.dashboard');
        $sec = session()->pull('after_login_section');

        if (is_string($sec) && in_array($sec, self::HIKER_HASH_SECTIONS, true)) {
            return $this->withFirstLoginFlag($url.'#'.$sec, $isFreshVerification);
        }

        return $this->withFirstLoginFlag($url, $isFreshVerification);
    }

    private function withFirstLoginFlag(string $url, bool $isFreshVerification): string
    {
        if (! $isFreshVerification) {
            return $url;
        }

        [$base, $hash] = array_pad(explode('#', $url, 2), 2, null);
        $separator = str_contains($base, '?') ? '&' : '?';
        $tagged = $base.$separator.'first_login=1';

        return $hash !== null ? $tagged.'#'.$hash : $tagged;
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
